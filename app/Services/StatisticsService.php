<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Throwable;
use Exception;

class StatisticsService {

    public function selfServiceStatistics(Request $request) {

        try {
            $params = $this->prepareParams($request);
            $this->validateByMode($params);

            // main dispatcher by stats_mode
            switch ($params['stats_mode']) {
                case 'standard_mode':
                    return $this->handleStandardMode($params);
                case 'by_property_mode':
                    return $this->handleByPropertyMode($params);
                default:
                    throw new Exception("Unsupported stats_mode '{$params['stats_mode']}'");
            }
        } catch (Throwable $e) {
            Log::error('StatisticsService::selfServiceStatistics failed', ['exception' => $e]);
            // rethrow or return consistent error structure (controller may wrap)
            throw $e;
        }
    }

    /* ----------------------------- Param & Validation ----------------------------- */
    protected function prepareParams(Request $request): array {

        $validator = Validator::make($request->all(), [
            'start_date' => ['required', 'date'],
            'end_date'   => ['required', 'date'],
            'order_dir'  => ['nullable', 'string'],
            'order_rule' => ['nullable', 'string'],
            'route_link' => ['nullable', 'string'],
            'percentage_base' => ['nullable', 'string'],
            'conditions_logic' => ['nullable', 'string'],
            'primary_property' => ['nullable', 'string'],
            'secondary_property' => ['nullable', 'string'],
        ]);
        if ($validator->fails()) {
            throw new Exception('Invalid request parameters: ' . implode('; ', $validator->errors()->all()));
        }

        $startDate = Carbon::parse($request->get('start_date'))->startOfDay();
        $endDate   = Carbon::parse($request->get('end_date'))->endOfDay();

        // normalize and validate order_dir
        $orderDirRaw = strtolower((string)$request->get('order_dir', 'desc'));
        $orderDir = in_array($orderDirRaw, $this->allowedOrderDirs(), true) ? $orderDirRaw : 'desc';

        // validate order_rule
        $orderRuleRaw = (string)$request->get('order_rule', 'standard_count');
        $orderRule = in_array($orderRuleRaw, $this->allowedOrderRules(), true) ? $orderRuleRaw : 'standard_count';

        // route_link: constrain length and pattern (alnum + - _)
        $routeLink = $request->get('route_link', null);
        if ($routeLink !== null) {
            $routeLinkStr = (string)$routeLink;
            if (!preg_match('/^[A-Za-z0-9_\-]{1,64}$/', $routeLinkStr)) {
                throw new Exception('Invalid route_link format.');
            }
            $routeLink = $routeLinkStr;
        }

        // percentage base
        $percentageBaseRaw = strtolower((string)$request->get('percentage_base', 'both'));
        $percentageBase = in_array($percentageBaseRaw, $this->allowedPercentageBases(), true) ? $percentageBaseRaw : 'both';

        // conditions logic
        $conditions_logic = strtolower($request->get('conditions_logic', 'and'));
        if (!in_array($conditions_logic, ['and','or'], true)) $conditions_logic = 'and';

        // parse conditions (limit number of conditions to avoid blow-up)
        $rawConditions = $request->get('conditions', null);
        $conditions = [];
        if ($rawConditions) {
            $decoded = json_decode($rawConditions, true);
            if (is_array($decoded)) {
                if (count($decoded) > 50) {
                    throw new Exception('Too many conditions (max 50).');
                }
                $conditions = $decoded;
            }
        }

        // Validate primary/secondary property columns (if present)
        $primary_property = $request->get('primary_property');
        if ($primary_property) $this->assertValidClientColumn($primary_property);

        $secondary_property = $request->get('secondary_property');
        if ($secondary_property) $this->assertValidClientColumn($secondary_property);

        return [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'route_link' => $routeLink,
            'order_dir' => $orderDir,
            'order_rule' => $orderRule,
            'primary_property' => $primary_property,
            'secondary_property' => $secondary_property,
            'stats_mode' => $request->get('stats_mode', 'standard_mode'),
            'conditions' => $conditions,
            'conditions_logic' => $conditions_logic,
            'percentage_base' => $percentageBase,
            'attach_clients' => true,
        ];
    }

    /**
     * Validate input depending on selected mode.
     * Throws Exception on invalid input.
     */
    protected function validateByMode(array $p): void {

        // Basic generic rules already checked in prepareParams
        $mode = $p['stats_mode'];
        if ($mode === 'standard_mode') {
            if (empty($p['primary_property'])) {
                throw new Exception("primary_property is required for standard_mode");
            }
            // ensure column exists (if scalar) - we'll detect JSON sample later, but basic check:
            // skip strict Schema check here because column might be JSON or nested
            if (!Schema::hasTable('clients')) {
                throw new Exception("clients table not found");
            }
        }

        if ($mode === 'by_property_mode') {
            if (empty($p['primary_property']) || empty($p['secondary_property'])) {
                throw new Exception("primary_property and secondary_property are required for by_property_mode");
            }
            if (!Schema::hasTable('clients')) {
                throw new Exception("clients table not found");
            }
        }
    }

    /* ----------------------------- Query Helpers ----------------------------- */

    /**
     * Apply the generic conditions array to a query builder.
     * The conditions structure follows the original shape: array of {target, operator, value, type?}
     *
     * This method validates date formats for date-like operators and applies a safe where/orWhere style.
     */
    protected function applyConditionsToQuery($query, array $conditions, string $logic = 'and'): void {

        if (empty($conditions)) return;

        // validate each condition target is a real column (or allowed)
        foreach ($conditions as $ic => $c) {
            if (!isset($c['target'])) continue;
            // allow only simple column names
            if (!array_key_exists('target', $c) || !is_string($c['target'])) {
                throw new Exception("Invalid condition target at index {$ic}.");
            }
            // check existence
            $target = $c['target'];
            $this->assertValidClientColumn($target);
        }

        $stringOps = [
            'equals','not_equals','contains','not_contains','starts_with','ends_with',
            'in','not_in','contains_any','contains_all','is_null','is_not_null','is_empty','is_empty_string','is_not_empty','is_not_empty_string',
            'is_true','is_false','after','before'
        ];
        $numberOps = [
            'equals','not_equals','greater_than','less_than','gte','lte','between',
            'in','not_in','is_null','is_not_null','is_empty','is_empty_string','is_not_empty','is_not_empty_string', 'after','before'
        ];

        $trueList = ['1','true','yes','y','available','present','oui'];
        $trueListLower = array_map('strtolower', $trueList);
        $trueIn = implode("','", array_map('addslashes', $trueListLower));

        $query->where(function ($q) use ($conditions, $logic, $stringOps, $numberOps, $trueIn) {
            $first = true;
            $getMethod = function ($first, $logic) {
                if ($first) return 'where';
                return ($logic === 'or') ? 'orWhere' : 'where';
            };

            foreach ($conditions as $c) {
                if (!isset($c['target']) || !isset($c['operator'])) continue;
                $target = $c['target'];
                $op = $c['operator'];
                $val = $c['value'] ?? null;
                $type = isset($c['type']) ? strtolower((string)$c['type']) : null;

                if (!in_array($op, array_merge($stringOps, $numberOps), true)) continue;

                $method = $getMethod($first, $logic);
                $rawMethod = ($method === 'where') ? 'whereRaw' : 'orWhereRaw';
                $inMethod = ($method === 'where') ? 'whereIn' : 'orWhereIn';
                $notInMethod = ($method === 'where') ? 'whereNotIn' : 'orWhereNotIn';
                $nullMethod = ($method === 'where') ? 'whereNull' : 'orWhereNull';
                $notNullMethod = ($method === 'where') ? 'whereNotNull' : 'orWhereNotNull';
                $betweenMethod = ($method === 'where') ? 'whereBetween' : 'orWhereBetween';

                // --- 1. DETERMINE IF THIS IS A DATE OPERATION ---
                $isDateTarget = false;

                if ($type === 'date') {
                    // Explicitly declared as date
                    $isDateTarget = true;
                } elseif ($type === 'string' || $type === 'number') {
                    // Explicitly declared as NOT date
                    $isDateTarget = false;
                } else {
                    // No type provided: Safe Guessing Logic
                    // We only assume it's a date if:
                    // 1. The operator is strictly time-based ('after', 'before')
                    // 2. OR the column name explicitly looks like a date (ends in _date, _at)
                    $strictDateOps = ['after', 'before'];
                    $nameImpliesDate = (is_string($target) && preg_match('/(_date|date$|_at$|updated_at|created_at)/i', $target));

                    if (in_array($op, $strictDateOps, true)) {
                        $isDateTarget = true;
                    } elseif ($nameImpliesDate) {
                        $isDateTarget = true;
                    }
                }
                
                // --- 2. EXECUTE DATE LOGIC ---
                if ($isDateTarget) {
                    // normalize alias
                    if ($op === 'after') $op = 'greater_than';
                    if ($op === 'before') $op = 'less_than';

                    $validateDate = function ($d) {
                        if ($d === null || $d === '') return false;
                        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $d)) return false;
                        try {
                            $dt = \Carbon\Carbon::createFromFormat('Y-m-d', $d);
                            return $dt && $dt->format('Y-m-d') === $d;
                        } catch (Throwable $ex) {
                            return false;
                        }
                    };

                    if ($op === 'between') {
                        if (!is_array($val) || count($val) < 2) {
                            throw new Exception("Date 'between' requires array of two dates for target '{$target}'.");
                        }
                        if (!$validateDate($val[0]) || !$validateDate($val[1])) {
                            throw new Exception("Invalid YYYY-MM-DD date for 'between' on '{$target}'.");
                        }
                        $q->{$rawMethod}("STR_TO_DATE({$target}, '%Y-%m-%d') BETWEEN STR_TO_DATE(?, '%Y-%m-%d') AND STR_TO_DATE(?, '%Y-%m-%d')", [$val[0], $val[1]]);
                    } elseif (in_array($op, ['equals','greater_than','less_than','gte','lte'], true)) {
                        if (!$validateDate((string)$val)) {
                            throw new Exception("Invalid YYYY-MM-DD date for '{$target}'.");
                        }
                        $map = ['equals'=>'=','greater_than'=>'>','less_than'=>'<','gte'=>'>=','lte'=>'<='];
                        $sqlOp = $map[$op];
                        $q->{$rawMethod}("STR_TO_DATE({$target}, '%Y-%m-%d') {$sqlOp} STR_TO_DATE(?, '%Y-%m-%d')", [(string)$val]);
                    } elseif ($op === 'is_null') {
                        $q->{$nullMethod}($target);
                    } elseif ($op === 'is_not_null') {
                        $q->{$notNullMethod}($target);
                    } else {
                        // fallback equality
                        if (!$validateDate((string)$val)) throw new Exception("Invalid date for '{$target}'.");
                        $q->{$rawMethod}("STR_TO_DATE({$target}, '%Y-%m-%d') = STR_TO_DATE(?, '%Y-%m-%d')", [(string)$val]);
                    }

                    $first = false;
                    continue;
                }

                // --- 3. EXECUTE STRING/NUMBER LOGIC ---
                // non-date handling (common cases)
                if (in_array($op, ['equals','not_equals','greater_than','less_than','gte','lte'], true)) {
                    $operatorMap = [
                        'equals' => '=',
                        'not_equals' => '!=',
                        'greater_than' => '>',
                        'less_than' => '<',
                        'gte' => '>=',
                        'lte' => '<='
                    ];
                    $sqlOp = $operatorMap[$op];
                    $q->{$method}($target, $sqlOp, $val);
                } elseif (in_array($op, ['contains','not_contains','starts_with','ends_with'], true)) {
                    if ($op === 'contains') $q->{$method}($target, 'LIKE', "%{$val}%");
                    if ($op === 'not_contains') $q->{$method}($target, 'NOT LIKE', "%{$val}%");
                    if ($op === 'starts_with') $q->{$method}($target, 'LIKE', "{$val}%");
                    if ($op === 'ends_with') $q->{$method}($target, 'LIKE', "%{$val}");
                } elseif (in_array($op, ['in','not_in'], true)) {
                    $arr = is_array($val) ? $val : (is_string($val) ? array_filter(array_map('trim', explode(',', $val))) : []);
                    if (!empty($arr)) {
                        if ($op === 'in') $q->{$inMethod}($target, $arr);
                        else $q->{$notInMethod}($target, $arr);
                    }
                } elseif ($op === 'between') {
                    if (is_array($val) && count($val) >= 2) $q->{$betweenMethod}($target, [$val[0], $val[1]]);
                } elseif ($op === 'is_null') {
                    $q->{$nullMethod}($target);
                } elseif ($op === 'is_not_null') {
                    $q->{$notNullMethod}($target);
                } elseif (in_array($op, ['is_empty','is_empty_string'], true)) {
                    $raw = "(TRIM(COALESCE({$target},'')) = '')";
                    if ($method === 'where') $q->where(function ($qq) use ($target, $raw) { $qq->whereNull($target)->orWhereRaw($raw); });
                    else $q->orWhere(function ($qq) use ($target, $raw) { $qq->whereNull($target)->orWhereRaw($raw); });
                } elseif (in_array($op, ['is_not_empty','is_not_empty_string'], true)) {
                    $raw = "(TRIM(COALESCE({$target},'')) != '')";
                    if ($method === 'where') $q->where(function ($qq) use ($target, $raw) { $qq->whereNotNull($target)->whereRaw($raw); });
                    else $q->orWhere(function ($qq) use ($target, $raw) { $qq->whereNotNull($target)->whereRaw($raw); });
                } elseif ($op === 'contains_any' || $op === 'contains_all') {
                    $arr = is_array($val) ? $val : (is_string($val) ? array_filter(array_map('trim', explode(',', $val))) : []);
                    if (!empty($arr)) {
                        $innerFirst = true;
                        if ($method === 'where') {
                            $q->where(function ($qq) use ($target, $arr, $op, &$innerFirst) {
                                foreach ($arr as $s) {
                                    if ($innerFirst) { $qq->where($target, 'LIKE', "%{$s}%"); $innerFirst = false; }
                                    else { if ($op === 'contains_any') $qq->orWhere($target, 'LIKE', "%{$s}%"); else $qq->where($target, 'LIKE', "%{$s}%"); }
                                }
                            });
                        } else {
                            $q->orWhere(function ($qq) use ($target, $arr, $op, &$innerFirst) {
                                foreach ($arr as $s) {
                                    if ($innerFirst) { $qq->where($target, 'LIKE', "%{$s}%"); $innerFirst = false; }
                                    else { if ($op === 'contains_any') $qq->orWhere($target, 'LIKE', "%{$s}%"); else $qq->where($target, 'LIKE', "%{$s}%"); }
                                }
                            });
                        }
                    }
                } elseif ($op === 'is_true' || $op === 'is_false') {
                    if ($op === 'is_true') $q->{$rawMethod}("LOWER(TRIM(COALESCE({$target},''))) IN ('{$trueIn}')");
                    else $q->{$rawMethod}("LOWER(TRIM(COALESCE({$target},''))) NOT IN ('{$trueIn}')");
                }

                $first = false;
            }
        });
    }

    /**
     * Returns a base clients query for the date range and optional route filter.
     */
    protected function baseClientsQuery(array $p) {

        return DB::table('clients')
            ->whereRaw("STR_TO_DATE(clients.created_at, '%d %M %Y') BETWEEN ? AND ?", [
                $p['start_date']->toDateString(),
                $p['end_date']->toDateString()
            ])
            ->when($p['route_link'], function ($q) use ($p) {
                $q->where('clients.id_route_import', $p['route_link']);
            });
    }

    /**
     * Fetch clients if requested (no pagination).
     * Returns a collection.
     */
    protected function fetchClientsIfRequested(array $p) {

        if (empty($p['attach_clients'])) return null;

        $q = $this->baseClientsQuery($p);
        if (!empty($p['conditions'])) $this->applyConditionsToQuery($q, $p['conditions'], $p['conditions_logic']);

        return $q->get();
    }

    /* ----------------------------- Ordering & Percentages ----------------------------- */

    /**
     * Apply ordering to table rows and groups.
     * This mirrors original behaviour: supports col/row ordering rules.
     */
    protected function applyOrdering(array &$table_rows, array &$groups, string $orderRule, string $orderDir): void {

        $asc = ($orderDir === 'asc');

        $reorderColumns = function (&$table_rows, &$groups, $newOrderIndices) {
            $newGroups = [];
            foreach ($newOrderIndices as $idx) $newGroups[] = $groups[$idx];
            foreach ($table_rows as &$r) {
                $oldVals = $r['values'] ?? [];
                $newVals = [];
                foreach ($newOrderIndices as $idx) $newVals[] = $oldVals[$idx] ?? 0;
                $r['values'] = $newVals;
            }
            $groups = $newGroups;
        };

        // columns ordering
        if (in_array($orderRule, ['cols_value','cols_total'], true)) {
            $colCount = count($groups);
            $colTotals = array_fill(0, $colCount, 0);
            foreach ($table_rows as $r) {
                $vals = $r['values'] ?? [];
                for ($i = 0; $i < $colCount; $i++) $colTotals[$i] += isset($vals[$i]) ? (int)$vals[$i] : 0;
            }
            $indices = range(0, $colCount - 1);

            if ($orderRule === 'cols_value') {
                usort($indices, function ($a, $b) use ($groups, $asc) {
                    $cmp = strnatcmp($groups[$a], $groups[$b]);
                    return $asc ? $cmp : -$cmp;
                });
            } else {
                usort($indices, function ($a, $b) use ($colTotals, $asc) {
                    if ($colTotals[$a] === $colTotals[$b]) return 0;
                    return $asc ? ($colTotals[$a] <=> $colTotals[$b]) : ($colTotals[$b] <=> $colTotals[$a]);
                });
            }
            $reorderColumns($table_rows, $groups, $indices);
        }

        // rows ordering
        if (in_array($orderRule, ['rows_value', 'rows_total'], true)) {
            usort($table_rows, function ($a, $b) use ($orderRule, $asc) {
                if ($orderRule === 'rows_value') {
                    $cmp = strnatcmp($a['label'], $b['label']);
                    return $asc ? $cmp : -$cmp;
                } else {
                    $ta = intval($a['total'] ?? array_sum($a['values'] ?? []));
                    $tb = intval($b['total'] ?? array_sum($b['values'] ?? []));
                    if ($ta === $tb) return 0;
                    return $asc ? ($ta <=> $tb) : ($tb <=> $ta);
                }
            });
        }
    }

    /**
     * Compute percentages for by_property-like table structure.
     * Accepts table_numbers with columns, rows (each row with values[] or count), totals.
     */
    protected function computeByPropertyPercentages(array $table_numbers, string $percentageBase) {

        $rows = $table_numbers['rows'] ?? [];
        $totals = $table_numbers['totals'] ?? [];

        // Detect matrix
        $isMatrix = isset($rows[0]) && isset($rows[0]['values']) && is_array($rows[0]['values']);

        if (!$isMatrix) {
            // simple (label,count) rows
            $grand = $totals['count'] ?? 0;
            if (!$grand) {
                foreach ($rows as $r) {
                    if (isset($r['count'])) $grand += (int)$r['count'];
                }
            }
            $pct = ['columns' => $table_numbers['columns'] ?? [], 'rows' => [], 'totals' => []];
            foreach ($rows as $r) {
                $cnt = isset($r['count']) ? (int)$r['count'] : 0;
                $pct['rows'][] = ['label' => $r['label'], 'percent' => $grand ? round(($cnt/$grand)*100, 2) : 0.0];
            }
            $pct['totals'] = ['label' => $totals['label'] ?? 'Total', 'percent' => $grand ? 100.0 : 0.0];
            return $pct;
        }

        // matrix case
        $grandTotal = $totals['total'] ?? null;
        if ($grandTotal === null) {
            $grandTotal = 0;
            foreach ($rows as $r) $grandTotal += isset($r['total']) ? (int)$r['total'] : array_sum($r['values'] ?? []);
        }

        $colTotals = $totals['values'] ?? null;
        if ($colTotals === null || !is_array($colTotals) || !count($colTotals)) {
            $numCols = count($rows[0]['values'] ?? []);
            $colTotals = array_fill(0, $numCols, 0);
            foreach ($rows as $r) {
                foreach ($r['values'] as $i => $v) $colTotals[$i] += (int)$v;
            }
        }

        $pctTable = ['columns' => $table_numbers['columns'] ?? [], 'rows' => [], 'totals' => []];

        foreach ($rows as $r) {
            $rowVals = $r['values'];
            $rowTotal = isset($r['total']) ? (int)$r['total'] : array_sum($rowVals);

            if ($percentageBase === 'primary') {
                $rowPctVals = [];
                foreach ($rowVals as $v) $rowPctVals[] = $rowTotal ? round(((int)$v / $rowTotal) * 100.0, 2) : 0.0;
                $pctTable['rows'][] = ['label' => $r['label'], 'values' => $rowPctVals, 'total' => $rowTotal ? 100.0 : 0.0];
            } elseif ($percentageBase === 'secondary') {
                $rowPctVals = [];
                foreach ($rowVals as $i => $v) {
                    $colTot = (float)($colTotals[$i] ?? 0);
                    $rowPctVals[] = $colTot ? round(((int)$v / $colTot) * 100.0, 2) : 0.0;
                }
                $pctTable['rows'][] = ['label' => $r['label'], 'values' => $rowPctVals, 'total' => $grandTotal ? round(($rowTotal / $grandTotal) * 100.0, 2) : 0.0];
            } else {
                $rowPctVals = [];
                foreach ($rowVals as $v) $rowPctVals[] = $grandTotal ? round(((int)$v / $grandTotal) * 100.0, 2) : 0.0;
                $pctTable['rows'][] = ['label' => $r['label'], 'values' => $rowPctVals, 'total' => $grandTotal ? round(($rowTotal / $grandTotal) * 100.0, 2) : 0.0];
            }
        }

        // totals
        if ($percentageBase === 'primary') {
            $totalsPctVals = [];
            foreach ($colTotals as $ct) $totalsPctVals[] = $grandTotal ? round(((int)$ct / $grandTotal) * 100.0, 2) : 0.0;
            $pctTable['totals'] = ['label' => $totals['label'] ?? 'Total', 'values' => $totalsPctVals, 'total' => $grandTotal ? 100.0 : 0.0];
        } elseif ($percentageBase === 'secondary') {
            $totalsPctVals = [];
            foreach ($colTotals as $ct) $totalsPctVals[] = ($ct ? 100.0 : 0.0);
            $pctTable['totals'] = ['label' => $totals['label'] ?? 'Total', 'values' => $totalsPctVals, 'total' => $grandTotal ? 100.0 : 0.0];
        } else {
            $totalsPctVals = [];
            foreach ($colTotals as $ct) $totalsPctVals[] = $grandTotal ? round(((int)$ct / $grandTotal) * 100.0, 2) : 0.0;
            $pctTable['totals'] = ['label' => $totals['label'] ?? 'Total', 'values' => $totalsPctVals, 'total' => $grandTotal ? 100.0 : 0.0];
        }

        return $pctTable;
    }

    /* ----------------------------- MODE HANDLERS ----------------------------- */

    /**
     * Standard mode: primary_property aggregated counts.
     */
    protected function handleStandardMode(array $p) {

        $primary = $p['primary_property'];
        $baseQ = $this->baseClientsQuery($p);
        if (!empty($p['conditions'])) $this->applyConditionsToQuery($baseQ, $p['conditions'], $p['conditions_logic']);

        // determine if primary_property is JSON by sampling one value
        $sample = (clone $baseQ)->value($primary);
        $isJson = is_array(json_decode($sample, true));

        if ($isJson) {
            // chunk through rows and decode JSON arrays, count occurrences
            $counts = [];
            $q = (clone $baseQ)->select($primary)->orderBy('clients.id');

            $q->chunk(500, function ($rows) use (&$counts, $primary) {
                foreach ($rows as $row) {
                    $raw = $row->$primary;
                    if ($raw === null || $raw === '') continue;
                    $decoded = json_decode($raw, true);
                    if (!is_array($decoded)) continue;
                    foreach ($decoded as $optVal) {
                        if ($optVal === null) continue;
                        $label = trim((string)$optVal);
                        if ($label === '') continue;
                        $counts[$label] = ($counts[$label] ?? 0) + 1;
                    }
                }
            });

            // ordering
            if ($p['order_rule'] === 'standard_value') {
                if ($p['order_dir'] === 'asc') ksort($counts, SORT_NATURAL | SORT_FLAG_CASE);
                else krsort($counts, SORT_NATURAL | SORT_FLAG_CASE);
            } else {
                if ($p['order_dir'] === 'asc') asort($counts);
                else arsort($counts);
            }

            $labels = array_keys($counts);
            $data = array_map(fn($k) => (int)$counts[$k], $labels);

            $chart = (object)[
                'labels' => $labels,
                'datasets' => [(object)['label' => $primary, 'data' => $data]]
            ];

            $tableRows = [];
            $grand = 0;
            foreach ($counts as $lbl => $cnt) {
                $tableRows[] = ['label' => $lbl, 'count' => (int)$cnt];
                $grand += (int)$cnt;
            }

            $table_numbers = ['columns' => [$primary, 'Count'], 'rows' => $tableRows, 'totals' => ['label' => 'Total', 'count' => $grand]];
            $table_percentages = ['columns'=>[$primary,'Percent (%)'],'rows'=>array_map(function($r) use ($grand){ $pct = $grand ? round((($r['count']/$grand)*100.0),2) : 0.0; return ['label'=>$r['label'],'percent'=>$pct]; }, $tableRows),'totals'=>['label'=>'Total','percent'=>$grand ? 100.00 : 0.00]];

            $result = ['labels'=>$labels,'chart'=>$chart,'table_numbers'=>$table_numbers,'table_percentages'=>$table_percentages];
            if ($p['attach_clients']) $result['clients'] = $this->fetchClientsIfRequested($p);
            return $result;
        }

        // scalar path: groupBy primary_property
        $q = (clone $baseQ)
            ->select(DB::raw("{$primary} as prop"), DB::raw('COUNT(*) as total'));

        $rows = $q->groupBy($primary)->get();

        $labels = []; $counts = []; $grandTotal = 0;
        foreach ($rows as $r) {
            $label = is_null($r->prop) ? 'N/A' : (string)$r->prop;
            $total = (int)$r->total;
            $labels[] = $label; $counts[] = $total; $grandTotal += $total;
        }

        $pairs = array_combine($labels, $counts ?: []);
        if ($p['order_rule'] === 'standard_value') {
            if ($p['order_dir'] === 'asc') ksort($pairs, SORT_NATURAL | SORT_FLAG_CASE);
            else krsort($pairs, SORT_NATURAL | SORT_FLAG_CASE);
        } else {
            if ($p['order_dir'] === 'asc') asort($pairs);
            else arsort($pairs);
        }

        $labels = array_keys($pairs); $counts = array_values($pairs);

        $chart = (object)[
            'labels' => $labels,
            'datasets' => [(object)['label' => $primary, 'data' => $counts]]
        ];

        $table_rows = [];
        foreach ($labels as $i => $lbl) $table_rows[] = ['label' => $lbl, 'count' => (int)$counts[$i]];

        $table_numbers = ['columns'=>[$primary,'Count'],'rows'=>$table_rows,'totals'=>['label'=>'Total','count'=>$grandTotal]];
        $table_percentages = ['columns'=>[$primary,'Percent (%)'],'rows'=>array_map(function($r) use ($grandTotal){ $pct = $grandTotal ? round((($r['count']/$grandTotal)*100.0),2) : 0.0; return ['label'=>$r['label'],'percent'=>$pct]; }, $table_rows),'totals'=>['label'=>'Total','percent'=>$grandTotal ? 100.0 : 0.0]];

        $result = ['labels'=>$labels,'chart'=>$chart,'table_numbers'=>$table_numbers,'table_percentages'=>$table_percentages];
        if ($p['attach_clients']) $result['clients'] = $this->fetchClientsIfRequested($p);
        return $result;
    }

    /**
     * by_property_mode: handles combinations of JSON/scalar primary/secondary.
     * For brevity I preserved the main shape from the original but splitted into cases.
     */
    protected function handleByPropertyMode(array $p) {

        $primary = $p['primary_property'];
        $secondary = $p['secondary_property'];

        $baseQ = $this->baseClientsQuery($p);
        if (!empty($p['conditions'])) $this->applyConditionsToQuery($baseQ, $p['conditions'], $p['conditions_logic']);

        // sample to detect json
        $samplePrimary = (clone $baseQ)->value($primary);
        $sampleSecondary = (clone $baseQ)->value($secondary);

        $primaryIsJson = is_array(json_decode($samplePrimary, true));
        $secondaryIsJson = is_array(json_decode($sampleSecondary, true));

        // CASE A: primary JSON, secondary scalar
        if ($primaryIsJson && !$secondaryIsJson) {
            $groupQ = (clone $baseQ)->select(DB::raw("COALESCE({$secondary}, 'N/A') as grp"));
            $groups = $groupQ->groupBy($secondary)->pluck('grp')->map(fn($v) => is_null($v) ? 'N/A' : (string)$v)->toArray();

            if (empty($groups)) {
                $empty = ['labels'=>[],'groups'=>[],'chart'=>(object)['labels'=>[],'datasets'=>[]],'table_numbers'=>['columns'=>[],'rows'=>[],'totals'=>[]],'table_percentages'=>['columns'=>[],'rows'=>[],'totals'=>[]]];
                if ($p['attach_clients']) $empty['clients'] = $this->fetchClientsIfRequested($p);
                return $empty;
            }

            // for each group, chunk clients and count occurrences of options in primary JSON
            $allOptionLabels = [];
            $countsByOptionGroup = [];

            foreach ($groups as $grpIndex => $grp) {
                $q = (clone $baseQ);
                if ($grp === 'N/A') $q->whereNull($secondary);
                else $q->where($secondary, $grp);
                if (!empty($p['conditions'])) $this->applyConditionsToQuery($q, $p['conditions'], $p['conditions_logic']);

                $groupCounts = [];
                $q2 = (clone $q)->select($primary)->orderBy('clients.id');
                $q2->chunk(500, function ($rows) use (&$groupCounts, $primary) {
                    foreach ($rows as $row) {
                        $raw = $row->$primary;
                        if ($raw === null || $raw === '') continue;
                        $decoded = json_decode($raw, true);
                        if (!is_array($decoded)) continue;
                        foreach ($decoded as $optVal) {
                            if ($optVal === null) continue;
                            $label = trim((string)$optVal);
                            if ($label === '') continue;
                            $groupCounts[$label] = ($groupCounts[$label] ?? 0) + 1;
                        }
                    }
                });

                foreach ($groupCounts as $label => $cnt) {
                    $allOptionLabels[$label] = true;
                    if (!isset($countsByOptionGroup[$label])) $countsByOptionGroup[$label] = array_fill(0, count($groups), 0);
                    $countsByOptionGroup[$label][$grpIndex] = $cnt;
                }
            }

            $labels = array_keys($allOptionLabels);
            sort($labels, SORT_NATURAL);

            // ensure each label has full group array
            foreach ($labels as $lbl) {
                if (!isset($countsByOptionGroup[$lbl])) $countsByOptionGroup[$lbl] = array_fill(0, count($groups), 0);
                else {
                    $arr = $countsByOptionGroup[$lbl];
                    if (count($arr) < count($groups)) $countsByOptionGroup[$lbl] = array_pad($arr, count($groups), 0);
                }
            }

            // build chart and table
            $chart = (object)['labels' => $labels, 'datasets' => []];
            foreach ($groups as $gi => $grp) {
                $ds = (object)[
                    'label' => $grp,
                    'data' => array_map(fn($lbl) => (int)($countsByOptionGroup[$lbl][$gi] ?? 0), $labels)
                ];
                $chart->datasets[] = $ds;
            }

            $tableRows = []; $totalsByGroup = array_fill(0, count($groups), 0); $grandTotal = 0;
            foreach ($labels as $lbl) {
                $vals = $countsByOptionGroup[$lbl];
                $totalRow = array_sum($vals);
                foreach ($vals as $i => $v) $totalsByGroup[$i] += $v;
                $grandTotal += $totalRow;
                $tableRows[] = ['label'=>$lbl, 'values'=>array_map('intval',$vals), 'total'=>(int)$totalRow];
            }

            // ordering
            $this->applyOrdering($tableRows, $groups, $p['order_rule'], $p['order_dir']);

            // recompute totals after ordering
            $recomputedTotalsByGroup = array_fill(0, count($groups), 0);
            $recomputedGrand = 0;
            foreach ($tableRows as $r) {
                foreach ($r['values'] as $i => $v) $recomputedTotalsByGroup[$i] += (int)$v;
                $recomputedGrand += (int)($r['total'] ?? array_sum($r['values'] ?? []));
            }

            $table_numbers = ['columns'=>array_merge(['Option'], $groups, ['Total']), 'rows'=>$tableRows, 'totals'=>['label'=>'Total','values'=>$recomputedTotalsByGroup,'total'=>$recomputedGrand]];
            $table_percentages = $this->computeByPropertyPercentages($table_numbers, $p['percentage_base']);

            $result = ['labels'=>$labels,'groups'=>$groups,'chart'=>$chart,'table_numbers'=>$table_numbers,'table_percentages'=>$table_percentages];
            if ($p['attach_clients']) $result['clients'] = $this->fetchClientsIfRequested($p);
            return $result;
        }

        // CASE B: primary scalar, secondary JSON
        if (!$primaryIsJson && $secondaryIsJson) {
            $labelQ = (clone $baseQ)->select(DB::raw("COALESCE({$primary}, 'N/A') as lbl"));
            if (!empty($p['conditions'])) $this->applyConditionsToQuery($labelQ, $p['conditions'], $p['conditions_logic']);
            $labels = $labelQ->groupBy($primary)->pluck('lbl')->map(fn($v) => is_null($v) ? 'N/A' : (string)$v)->toArray();
            if (empty($labels)) {
                $empty = ['labels'=>[],'groups'=>[],'chart'=>(object)['labels'=>[],'datasets'=>[]],'table_numbers'=>['columns'=>[],'rows'=>[],'totals'=>[]],'table_percentages'=>['columns'=>[],'rows'=>[],'totals'=>[]]];
                if ($p['attach_clients']) $empty['clients'] = $this->fetchClientsIfRequested($p);
                return $empty;
            }

            $allOptionLabels = [];
            $counts = []; // counts[label][option] = count

            foreach ($labels as $lbl) {
                $q = (clone $baseQ);
                if ($lbl === 'N/A') $q->whereNull($primary);
                else $q->where($primary, $lbl);
                if (!empty($p['conditions'])) $this->applyConditionsToQuery($q, $p['conditions'], $p['conditions_logic']);

                $secCounts = [];
                $q2 = (clone $q)->select($secondary)->orderBy('clients.id');
                $q2->chunk(500, function ($rows) use (&$secCounts, $secondary) {
                    foreach ($rows as $row) {
                        $raw = $row->$secondary;
                        if ($raw === null || $raw === '') continue;
                        $dec = json_decode($raw, true);
                        if (!is_array($dec)) continue;
                        foreach ($dec as $optVal) {
                            if ($optVal === null) continue;
                            $label = trim((string)$optVal);
                            if ($label === '') continue;
                            $secCounts[$label] = ($secCounts[$label] ?? 0) + 1;
                        }
                    }
                });

                $counts[$lbl] = $secCounts;
                foreach ($secCounts as $optLabel => $_) $allOptionLabels[$optLabel] = true;
            }

            $groups = array_keys($allOptionLabels);
            sort($groups, SORT_NATURAL);

            $tableRows = []; $totalsByGroup = array_fill(0, count($groups), 0); $grandTotal = 0;

            foreach ($labels as $lbl) {
                $rowVals = []; $rowTotal = 0;
                foreach ($groups as $gi => $gLabel) {
                    $v = isset($counts[$lbl][$gLabel]) ? (int)$counts[$lbl][$gLabel] : 0;
                    $rowVals[] = $v; $rowTotal += $v; $totalsByGroup[$gi] += $v; $grandTotal += $v;
                }
                $tableRows[] = ['label'=>$lbl,'values'=>$rowVals,'total'=>$rowTotal];
            }

            $this->applyOrdering($tableRows, $groups, $p['order_rule'], $p['order_dir']);

            $recomputedTotalsByGroup = array_fill(0, count($groups), 0); $recomputedGrand = 0;
            foreach ($tableRows as $r) {
                foreach ($r['values'] as $i => $v) $recomputedTotalsByGroup[$i] += (int)$v;
                $recomputedGrand += (int)($r['total'] ?? array_sum($r['values'] ?? []));
            }

            $chart = (object)['labels'=>$labels,'datasets'=>[]];
            foreach ($groups as $gi => $gLabel) {
                $ds = (object)['label'=>$gLabel,'data'=>array_map(fn($r) => (int)$r['values'][$gi], $tableRows)];
                $chart->datasets[] = $ds;
            }

            $table_numbers = ['columns'=>array_merge([$primary], $groups, ['Total']),'rows'=>$tableRows,'totals'=>['label'=>'Total','values'=>$recomputedTotalsByGroup,'total'=>$recomputedGrand]];
            $table_percentages = $this->computeByPropertyPercentages($table_numbers, $p['percentage_base']);

            $result = ['labels'=>$labels,'groups'=>$groups,'chart'=>$chart,'table_numbers'=>$table_numbers,'table_percentages'=>$table_percentages];
            if ($p['attach_clients']) $result['clients'] = $this->fetchClientsIfRequested($p);
            return $result;
        }

        // CASE C: both JSON
        if ($primaryIsJson && $secondaryIsJson) {
            $primaryOptions = []; $secondaryOptions = []; $pairCounts = [];

            $q = (clone $baseQ)->select($primary, $secondary);
            if (!empty($p['conditions'])) $this->applyConditionsToQuery($q, $p['conditions'], $p['conditions_logic']);

            $q->orderBy('clients.id')->chunk(500, function($rows) use (&$primaryOptions, &$secondaryOptions, &$pairCounts, $primary, $secondary) {
                foreach ($rows as $row) {
                    $rawP = $row->$primary ?? null;
                    $rawS = $row->$secondary ?? null;
                    $decP = json_decode($rawP, true);
                    $decS = json_decode($rawS, true);
                    if (!is_array($decP) || !is_array($decS)) continue;

                    $pList = array_values(array_filter(array_map(fn($v)=>is_null($v)?null:trim((string)$v), $decP)));
                    $sList = array_values(array_filter(array_map(fn($v)=>is_null($v)?null:trim((string)$v), $decS)));

                    if (empty($pList) || empty($sList)) continue;

                    foreach ($pList as $pLabel) {
                        if ($pLabel === '') continue;
                        $primaryOptions[$pLabel] = true;
                        foreach ($sList as $sLabel) {
                            if ($sLabel === '') continue;
                            $secondaryOptions[$sLabel] = true;
                            $pairCounts[$pLabel][$sLabel] = ($pairCounts[$pLabel][$sLabel] ?? 0) + 1;
                        }
                    }
                }
            });

            $labels = array_keys($primaryOptions); sort($labels, SORT_NATURAL);
            $groups = array_keys($secondaryOptions); sort($groups, SORT_NATURAL);

            $tableRows = []; $totalsByGroup = array_fill(0, count($groups), 0); $grandTotal = 0;
            foreach ($labels as $lbl) {
                $rowVals = []; $rowTotal = 0;
                foreach ($groups as $gi => $g) {
                    $v = isset($pairCounts[$lbl][$g]) ? (int)$pairCounts[$lbl][$g] : 0;
                    $rowVals[] = $v; $rowTotal += $v; $totalsByGroup[$gi] += $v; $grandTotal += $v;
                }
                $tableRows[] = ['label'=>$lbl,'values'=>$rowVals,'total'=>$rowTotal];
            }

            $this->applyOrdering($tableRows, $groups, $p['order_rule'], $p['order_dir']);

            $recomputedTotalsByGroup = array_fill(0, count($groups), 0); $recomputedGrand = 0;
            foreach ($tableRows as $r) {
                foreach ($r['values'] as $i => $v) $recomputedTotalsByGroup[$i] += (int)$v;
                $recomputedGrand += (int)($r['total'] ?? array_sum($r['values'] ?? []));
            }

            $chart = (object)['labels'=>$labels,'datasets'=>[]];
            foreach ($groups as $gi => $g) {
                $ds = (object)['label'=>$g,'data'=>array_map(fn($r)=>(int)($r['values'][$gi] ?? 0), $tableRows)];
                $chart->datasets[] = $ds;
            }

            $table_numbers = ['columns'=>array_merge(['Option (primary)'], $groups, ['Total']), 'rows'=>$tableRows, 'totals'=>['label'=>'Total','values'=>$recomputedTotalsByGroup,'total'=>$recomputedGrand]];
            $table_percentages = $this->computeByPropertyPercentages($table_numbers, $p['percentage_base']);

            $result = ['labels'=>$labels,'groups'=>$groups,'chart'=>$chart,'table_numbers'=>$table_numbers,'table_percentages'=>$table_percentages];
            if ($p['attach_clients']) $result['clients'] = $this->fetchClientsIfRequested($p);
            return $result;
        }

        // CASE D: both scalar
        $q = (clone $baseQ)
            ->select(DB::raw("{$primary} as prop"), DB::raw("{$secondary} as grp"), DB::raw('COUNT(*) as total'));

        if (!empty($p['conditions'])) $this->applyConditionsToQuery($q, $p['conditions'], $p['conditions_logic']);

        $rows = $q->groupBy($primary, $secondary)->get();

        $labels = []; $groups = [];
        foreach ($rows as $r) {
            $label = is_null($r->prop) ? 'N/A' : (string)$r->prop;
            $grp = is_null($r->grp) ? 'N/A' : (string)$r->grp;
            if (!in_array($label, $labels, true)) $labels[] = $label;
            if (!in_array($grp, $groups, true)) $groups[] = $grp;
        }

        $counts = []; $totalsByLabel = [];
        $totalsByGroup = array_fill(0, count($groups), 0); $grandTotal = 0;
        foreach ($labels as $lbl) { $counts[$lbl] = array_fill(0, count($groups), 0); $totalsByLabel[$lbl] = 0; }

        foreach ($rows as $r) {
            $label = is_null($r->prop) ? 'N/A' : (string)$r->prop;
            $grp = is_null($r->grp) ? 'N/A' : (string)$r->grp;
            $total = (int)$r->total;
            $grpIndex = array_search($grp, $groups, true);
            if ($grpIndex === false) continue;
            $counts[$label][$grpIndex] += $total;
            $totalsByLabel[$label] += $total;
            $totalsByGroup[$grpIndex] += $total;
            $grandTotal += $total;
        }

        $table_rows_numbers = [];
        foreach ($labels as $lbl) {
            $row = ['label' => $lbl, 'values' => []];
            foreach ($groups as $i => $grp) $row['values'][] = (int)$counts[$lbl][$i];
            $row['total'] = (int)$totalsByLabel[$lbl];
            $table_rows_numbers[] = $row;
        }

        $this->applyOrdering($table_rows_numbers, $groups, $p['order_rule'], $p['order_dir']);

        $recomputedTotalsByGroup = array_fill(0, count($groups), 0); $recomputedGrand = 0;
        foreach ($table_rows_numbers as $r) {
            foreach ($r['values'] as $i => $v) $recomputedTotalsByGroup[$i] += (int)$v;
            $recomputedGrand += (int)($r['total'] ?? array_sum($r['values'] ?? []));
        }

        $columns = array_merge([$primary], $groups, ['Total']);
        $totalsRowNumbers = ['label' => 'Total', 'values' => array_map('intval', $recomputedTotalsByGroup), 'total' => $recomputedGrand];
        $table_numbers = ['columns' => $columns, 'rows' => $table_rows_numbers, 'totals' => $totalsRowNumbers];
        $table_percentages = $this->computeByPropertyPercentages($table_numbers, $p['percentage_base']);

        $chartDatasets = [];
        foreach ($groups as $i => $grp) {
            $dataset = (object)[
                'label' => $grp,
                'data' => array_map(fn($lblRow) => (int)($lblRow['values'][$i] ?? 0), $table_rows_numbers)
            ];
            $chartDatasets[] = $dataset;
        }
        $chart = (object)['labels' => array_map(fn($r) => $r['label'], $table_rows_numbers), 'datasets' => $chartDatasets];

        $result = ['labels'=>$labels,'groups'=>$groups,'chart'=>$chart,'table_numbers'=>$table_numbers,'table_percentages'=>$table_percentages];
        if ($p['attach_clients']) $result['clients'] = $this->fetchClientsIfRequested($p);
        return $result;
    }

    /**
    * Helper: allowed order rules and order dirs.
    */
    protected function allowedOrderRules(): array {

        return [
            'standard_value', 'standard_count',    // original standard variants
            'cols_value', 'cols_total',
            'rows_value', 'rows_total',
        ];
    }

    protected function allowedOrderDirs(): array {
        return ['asc', 'desc'];
    }

    protected function allowedPercentageBases(): array {
        return ['primary', 'secondary', 'both'];
    }

    /**
    * Helper: validate that a column identifier is safe and exists on clients table.
    * Throws Exception if invalid.
    */
    protected function assertValidClientColumn(string $col): void {

        // quick syntax check
        if (!preg_match('/^[A-Za-z0-9_]+$/', $col)) {
            throw new \Exception("Invalid column identifier '{$col}'.");
        }

        // ensure column exists on clients table
        static $colsCache = null;
        if ($colsCache === null) {
            $colsCache = Schema::getColumnListing('clients');
        }
        if (!in_array($col, $colsCache, true)) {
            throw new \Exception("Unknown column '{$col}' on clients table.");
        }
    }
}