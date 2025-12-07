<template>
  <div>
    <!-- Title / toggle -->
    <div class="card mt-3 mb-3">
        <div class="card-body p-0">
            <div class="report_div" id="by_dynamic_property_filters_report">
              <div class="row">
                <!-- Chart type -->
                <div class="col-sm-3">
                  <select class="form-select h-100" v-model="chartType"                             :disabled="chartBuilding" @change="onLocalChartTypeChange">
                    <option value="bar">Bar</option>
                    <option value="line">Line</option>
                    <option value="pie">Pie</option>
                  </select>
                </div>

                <!-- Export Buttons -->
                <div class="col-sm-4">
                  <button class="btn btn-success w-100 h-100" @click="exportStatsWithChartExcel()"  :disabled="chartBuilding">Export XLSX</button>
                </div>

                <div class="col-sm-4">
                  <button class="btn btn-secondary w-100 h-100" @click="exportChartPngAsFile()"     :disabled="chartBuilding">Chart PNG</button>
                </div>

                <div class="col-sm-1 text-right">
                  <img
                    :src="'/images/switch_arrows.png'"
                    @click="toggleChartTable()"
                    role="button"
                    class="mb-0 mr-2"
                  />
                </div>
              </div>
            </div>
        </div>
    </div>

    <!-- Chart -->
    <div v-show="showChart" class="card mt-3 mb-3">
      <div class="card-body p-0">
        <div class="col-sm-12">
          <div id="chart_scroll" class="chart_scroll pt-4 pb-1">
            <div id="chart_container" class="chart_container">
              <canvas ref="reportChart" id="reportChart"></canvas>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Tables (Numbers + Percentages) -->
    <div v-show="!showChart" class="card mt-3 mb-3">
      <div class="col-sm-12">
        <!-- NUMBERS TABLE -->
        <div v-if="tableNumbers" class="mb-4">
          <h5><u>Details (Numbers)</u></h5>
          <div class="table-responsive mt-3">
            <table class="table">
              <thead>
                <tr>
                  <th class='col' v-for="(c,i) in tableNumbers.columns" :key="'num-h-'+i">{{ c }}</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(row,ri) in tableNumbers.rows" :key="'num-r-'+ri">
                  <th class='col'>{{ row.label }}</th>

                  <!-- MATRIX case: row.values exists -->
                  <template v-if="row.values && Array.isArray(row.values)">
                    <td class='col' v-for="(v,ci) in row.values" :key="'num-v-'+ci">{{ v }}</td>
                    <th class='col'>{{ row.total }}</th>
                  </template>

                  <!-- STANDARD case: single count column -->
                  <template v-else-if="typeof row.count !== 'undefined'">
                    <td class='col'>{{ row.count }}</td>
                  </template>

                  <!-- FALLBACK: if tableColumns are dynamic, attempt to print fields other than label -->
                  <template v-else>
                    <td class='col' v-for="(col,ci) in (tableNumbers.columns.slice(1) || [])" :key="'num-f-'+ci">
                      {{ row[col] ?? '' }}
                    </td>
                  </template>
                </tr>

                <!-- totals row -->
                <tr>
                  <th>{{ tableNumbers.totals?.label ?? 'Total' }}</th>

                  <!-- MATRIX totals -->
                  <template v-if="Array.isArray(tableNumbers.totals?.values)">
                    <th class='col' v-for="(v,ci) in tableNumbers.totals.values" :key="'num-t-'+ci">{{ v }}</th>
                    <th class='col'>{{ tableNumbers.totals.total }}</th>
                  </template>

                  <!-- STANDARD totals -->
                  <template v-else-if="typeof tableNumbers.totals?.count !== 'undefined'">
                    <th class='col'>{{ tableNumbers.totals.count }}</th>
                  </template>

                  <!-- FALLBACK empty cells to match header -->
                  <template v-else>
                    <th class='col' v-for="(col,ci) in (tableNumbers.columns.slice(1) || [])" :key="'num-empty-'+ci">&nbsp;</th>
                  </template>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <hr class="mt-5 mb-5" />

        <!-- PERCENTAGES TABLE -->
        <div v-if="tablePercentages" class="mb-2">
          <h5><u>Details (Percent % of total)</u></h5>
          <div class="table-responsive mt-3">
            <table class="table">
              <thead>
                <tr>
                  <th class='col' v-for="(c,i) in tablePercentages.columns" :key="'pct-h-'+i">{{ c }}</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(row,ri) in tablePercentages.rows" :key="'pct-r-'+ri">
                  <th class='col'>{{ row.label }}</th>

                  <template v-if="row.values && Array.isArray(row.values)">
                    <td class='col' v-for="(v,ci) in row.values" :key="'pct-v-'+ci">{{ formatPercent(v) }}</td>
                    <th>{{ formatPercent(row.total) }}</th>
                  </template>

                  <template v-else>
                    <td class='col'>{{ formatPercent(row.percent ?? row.count ?? 0) }}</td>
                  </template>
                </tr>

                <!-- totals row -->
                <tr>
                  <th>{{ tablePercentages.totals?.label ?? 'Total' }}</th>

                  <template v-if="Array.isArray(tablePercentages.totals?.values)">
                    <th class='col' v-for="(v,ci) in tablePercentages.totals.values" :key="'pct-t-'+ci">{{ formatPercent(v) }}</th>
                    <th class='col'>{{ formatPercent(tablePercentages.totals.total) }}</th>
                  </template>

                  <template v-else>
                    <th class='col'>{{ formatPercent(tablePercentages.totals?.percent ?? 0) }}</th>
                  </template>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Fallback: if there is no numbers table, show earlier single-table behavior (compat) -->
        <div v-if="!tableNumbers && tableDataNormalized.rows.length" class="mt-2">
          <h6>Details</h6>
          <div class="table-responsive">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th class='col' v-for="(c,i) in tableDataNormalized.columns" :key="'f-h-'+i">{{ c }}</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(row,ri) in tableDataNormalized.rows" :key="'f-r-'+ri">
                  <th>{{ row.label }}</th>
                  <td class='col' v-if="typeof row.count !== 'undefined'">{{ row.count }}</td>
                  <template v-else>
                    <td class='col' v-for="(col,ci) in tableDataNormalized.columns.slice(1)" :key="'f-c-'+ci">{{ row[col] ?? '' }}</td>
                  </template>
                </tr>
                <tr>
                  <th class='col'>{{ tableDataNormalized.totals?.label ?? 'Total' }}</th>
                  <th class='col' v-if="typeof tableDataNormalized.totals?.count !== 'undefined'">{{ tableDataNormalized.totals.count }}</th>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

      </div>
    </div>

    <!-- Clients -->
    <div class="card mt-3 mb-3">
        <div class="card-body p-0">
            <div class="report_div" id="by_dynamic_property_clients_report">
                <div id="statistic_details_clients_container" class="table-container mt-5">
                    <table class="table table-hover table-bordered statistic_details_clients mt-0 mb-0 w-100" id="statistic_details_clients">
                        <thead>
                            <tr>
                                <th role="button">#</th>
                                <th v-for="route_import_client_column in statistic_details_clients_columns" :key="route_import_client_column" role="button">{{ route_import_client_column.title }}</th>
                            </tr>

                            <tr id="statistic_details_clients_filters" class="statistic_details_clients_filters">
                                <th></th>

                                <th v-for="route_import_client_column in statistic_details_clients_columns" :key="route_import_client_column">
                                    <div class="filter_group" :data-column="route_import_client_column.title">
                                        <select class="filter_type form-select-sm w-100 mb-2">
                                            <option value="contains">contains</option>
                                            <option value="not_contains">not contains</option>
                                            <option value="exact">exact</option>
                                            <option value="starts_with">starts with</option>
                                            <option value="ends_with">ends with</option>
                                        </select>
                                        <input
                                            type="text"
                                            class="form-control form-control-sm filter_input"
                                            :placeholder="route_import_client_column.title"
                                        />
                                    </div>
                                </th>
                            </tr>
                        </thead>

                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- -->

    <!-- Modal Clients -->
    <div>
      <ModalSelfServiceChartClients ref="ModalSelfServiceChartClients"></ModalSelfServiceChartClients>
    </div>
    <!-- -->
  </div>
</template>

<script>

import ModalSelfServiceChartClients  from  "./ModalSelfServiceChartClients.vue"

import DatatableHelper    from  "@/services/DatatableHelper"

export default {

  name: "ByDynamicProperty",

  props: {
    stats_details: {
      type: Object,
      required: true
    },

    primary_property: {
      type: String,
      required: true
    },

    secondary_property: {
      type: String,
    },

    percentageBaseOption: {
      type: String,
      required: true
    },
  },

  data() {
    return {
      chartInstance: null,
      showChart: true,

      //
      chartType: "bar",
      chartBuilding: false,

      //
      statistic_details_clients_data                : [],
      statistic_details_clients_columns             : [
                                                        { data: "id"                    , title: "Id"                   },
                                                        { data: "created_at"            , title: "Created At"           },
                                                        { data: "status"                , title: "Status"               },
                                                        { data: "owner"                 , title: "OwnerName"            },
                                                        { data: "CustomerCode"          , title: "CustomerCode"         },
                                                        { data: "CustomerNameE"         , title: "CustomerNameE"        },
                                                        { data: "CustomerNameA"         , title: "CustomerNameA"        },
                                                        { data: "DistrictNameE"         , title: "DistrictNameE"        },
                                                        { data: "CityNameE"             , title: "CityNameE"            },
                                                        { data: "Address"               , title: "Address"              },
                                                        { data: "Tel"                   , title: "Tel"                  },
                                                        { data: "CustomerType"          , title: "CustomerType"         },
                                                        { data: "JPlan"                 , title: "JPlan"                },
                                                        { data: "Journee"               , title: "Journee"              },
                                                        { data: "Frequency"             , title: "Frequency"            },
                                                     ],

      //
      datatable_statistic_details_clients           : null,
      datatable_statistic_details_clients_instance  : null
    };
  },

  computed: {
    // Title - you can customize based on incoming meta if needed
    title() {
      return this.stats_details?.title ?? "";
    },

    // Normalize chart data: accept either stats_details.chart or stats_details.labels/datasets
    chartDataNormalized() {
      const s = this.stats_details || {};
      if (s.chart && s.chart.labels && s.chart.datasets) {
        return s.chart;
      }
      // older shapes: top-level labels + datasets
      if (s.labels && s.datasets) {
        return { labels: s.labels, datasets: s.datasets };
      }
      // fallback empty
      return { labels: [], datasets: [] };
    },

    // Backwards-compatible table (original fallback)
    tableDataNormalized() {
      const s = this.stats_details || {};
      return s.table || s.tableData || { columns: [], rows: [], totals: {} };
    },

    // PRIMARY: numbers table (preferred)
    tableNumbers() {
      if (this.stats_details?.table_numbers) return this.stats_details.table_numbers;
      if (this.stats_details?.table) return this.stats_details.table;
      const t = this.tableDataNormalized;
      if ((t.rows || []).length) return t;
      return null;
    },

    // PERCENTAGES: either from backend or computed from numbers table
    tablePercentages() {
      if (this.stats_details?.table_percentages) return this.stats_details.table_percentages;
      const nums = this.tableNumbers;
      if (!nums) return null;
      return this.computePercentagesFromNumbers(nums);
    },
  },

  watch: {
    stats_details: {
      handler() {
        // rebuild chart & table when new data arrives
        this.buildChart();
        
        // ensure clients list and client table are prepared whenever stats_details arrives
        // do it after DOM update so rows exist for DataTable init
        this.$nextTick(() => {      
          // set clients array from backend if present
          this.statistic_details_clients_data = this.stats_details?.clients || [];
          this.prepareClientsTable();
        });
      },
      deep: true,
      immediate: true
    },

    chartType: {
      handler() {
        // rebuild chart when chartType changes
        this.buildChart();
      }
    }
  },

  components : {
    ModalSelfServiceChartClients : ModalSelfServiceChartClients
  },

  mounted() {

    //
    this.datatable_statistic_details_clients_instance   =   new DatatableHelper()

    this.buildChart();
    this.prepareClientsTable()

    this.$nextTick(() => {
      if (this.emitter) this.emitter?.emit?.("show_by_customer_type_report_content_ready");
    });
  },

  beforeUnmount() {
    this.destroyChart();
  },

  methods: {

    toggleChartTable() {
      this.showChart = !this.showChart;
    },

    destroyChart() {
      if (this.chartInstance) {
        try {
          this.chartInstance.destroy();
        } catch (e) { /* ignore */ }
        this.chartInstance = null;
      }
    },

    /* */

    async buildChart() {
      // mark building (disable UI while building)
      this.chartBuilding = true;

      try {
        // destroy existing chart first
        this.destroyChart();

        const canvas = this.$refs.reportChart;
        if (!canvas) return;
        const ctx = canvas.getContext?.("2d");
        if (!ctx) return;

        // prepare data for Chart.js
        const chartPayload = this.chartDataNormalized || { labels: [], datasets: [] };

        console.log(chartPayload.labels)

        // ensure datasets are objects (Chart.js expects label + data arrays)
        let datasets = (chartPayload.datasets || []).map((ds, idx) => {
          if (Array.isArray(ds)) {
            return { label: `Series ${idx+1}`, data: ds };
          }
          return {
            ...ds, 
            label: ds.label ?? `Series ${idx+1}`,
            data: ds.data ?? [],
            backgroundColor: ds.backgroundColor,
            borderColor: ds.borderColor
          };
        });

        // Chart type (from prop / local value)
        const type = (this.chartType || "bar").toLowerCase();

        // Build safe labels: convert anything to string and optionally insert line-breaks
        let labelsForChart = (chartPayload.labels || []).map(lbl => {
          const s = String(lbl ?? '');
          const parts = this.$splitOnSpaceChartJSLabels([s])[0] || [s];
          return parts.join('\n'); // Chart.js supports multi-line labels
        });

        // plugin instances
        const valueOnTopPlugin = this.$valueOnTopOfEachBarPlugin('value_on_top', true, 2);
        const after_chart_rendered_plugin = this.$chartRendered(this.setReportChart);

        datasets = this.$setDefaultColors(datasets, labelsForChart);

        console.log(labelsForChart)

        console.log('chartPayload.datasets (raw):', chartPayload.datasets);
        console.log('datasets normalized (before chart):', datasets);

        // base config
        const chartConfig = {
          type: type,
          data: {
            labels: labelsForChart,
            datasets: datasets
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
              legend: { position: "top" }
              // plugin-specific options will be added below
            },
            scales: {},

            onClick: async (evt) => {
              // Try to get a single element under the click (the exact dataset segment)
              const elems = this.chartInstance.getElementsAtEventForMode(evt, 'nearest', { intersect: true }, true);
              if (elems && elems.length) {
                const el = elems[0]; // single element (datasetIndex + index)
                await this.openForDatasetAtIndex(el.index, el.datasetIndex);
                return;
              }

              // Fallback: get all elements that share the same index (useful for stacked bars or clicking label area)
              const allAtIndex = this.chartInstance.getElementsAtEventForMode(evt, 'index', { intersect: false }, true);
              if (allAtIndex && allAtIndex.length) {
                // Decide behaviour: open aggregated clients for the label (all datasets)
                // We'll take the common index of the first element
                const idx = allAtIndex[0].index;
                await this.openForAllDatasetsAtIndex(idx);
              }
            },
          },
          plugins: [ valueOnTopPlugin, after_chart_rendered_plugin, ChartDataLabels ]
        };

        // scales for bar/line
        if (type === "bar" || type === "line") {
          chartConfig.options.scales = {
            x: { stacked: false, ticks: { autoSkip: false } },
            y: { beginAtZero: true, ticks: { precision: 0 } }
          };
        } else {
          // no scales for pie/doughnut
          chartConfig.options.scales = undefined;
        }

        // ----- ChartDataLabels configuration (ONLY active for pie/doughnut) -----
        const hasDataLabels = typeof ChartDataLabels !== 'undefined';
        if (hasDataLabels) {
          // Register plugin if needed (if you use modular Chart.js you may need Chart.register)
          try {
            if (Chart && Chart.register && !Chart.registry?.plugins?.get?.('datalabels')) {
              // register once (safe)
              Chart.register(ChartDataLabels);
            }
          } catch (e) {
            // ignore registration errors
          }

          // Put ChartDataLabels plugin into chartConfig.plugins only if available
          chartConfig.plugins.push(ChartDataLabels);

          // Build per-plugin options: display only for pie/doughnut, else hide it
          const isPieLike = (type === 'pie' || type === 'doughnut');

          chartConfig.options.plugins.datalabels = {
            // only display for pie/doughnut
            display: isPieLike ? true : false,

            // white color inside the pie
            color: '#ffffff',

            // center labels for pie slices
            anchor: 'center',
            align: 'center',

            // font settings
            font: {
              weight: 'bold',
              size: 12
            },

            // clamp so labels don't overflow
            clamp: true,
            clip: true,

            // formatter: show percentage on first line, raw value second line
            formatter: (value, ctx) => {
              // if not pie, hide
              if (!isPieLike) return '';

              // total across dataset (for percentage)
              const data = ctx.chart.data.datasets[ctx.datasetIndex].data;
              const total = data.reduce((acc, v) => acc + (Number(v) || 0), 0);
              const pct = total ? ((Number(value) || 0) / total * 100) : 0;

              // choose number formatting: 2 decimals for percent, integer for value
              const pctStr = !isNaN(pct) ? pct.toFixed(2) + '%' : '';
              const valStr = (Number(value) || 0).toString();

              // ChartDataLabels supports multi-line if you return an array or a string with '\n'
              // Put percent first line, value second line
              return `${pctStr}\n${valStr}`;
            },

            // optionally a text shadow or background to improve readability
            // you can uncomment these if you want a subtle stroke
            // textStrokeColor: 'rgba(0,0,0,0.25)',
            // textStrokeWidth: 2,
          };
        } else {
          // if plugin not present, ensure we don't push undefined; keep it disabled silently
          // plugin will just not render labels — that's fine
          chartConfig.options.plugins.datalabels = { display: false };
        }

        // create Chart (assumes Chart global available)
        try {
          this.chartInstance = new Chart(ctx, chartConfig);
        } catch (e) {
          // if Chart isn't available or config fails, keep page usable and log
          // console.warn('Chart build error:', e);
          this.chartInstance = null;
        }

        this.emitter.on('reportChart_rendered', () => {

            this.emitter.off('reportChart_rendered')

            //
            this.chartBuilding  =   false
        })
      }
      catch(e) {

      }
    },

    setReportChart() {

        const canvas_to_blob = document.getElementById('reportChart');
        if (!canvas_to_blob) return;

        //
        this.emitter.emit('reportChart_rendered')
    },

    // ---------- Helpers for percentages ----------
    computePercentagesFromNumbers(numbersTable) {
      const totals = numbersTable.totals || {};
      let grandTotal = null;

      if (typeof totals.total !== "undefined") grandTotal = Number(totals.total) || 0;
      else if (typeof totals.count !== "undefined") grandTotal = Number(totals.count) || 0;

      if (!grandTotal) {
        if (numbersTable.rows && numbersTable.rows.length) {
          grandTotal = numbersTable.rows.reduce((acc, r) => {
            if (Array.isArray(r.values)) return acc + (Number(r.total) || 0);
            return acc + (Number(r.count) || 0);
          }, 0);
        } else {
          grandTotal = 0;
        }
      }

      const pctTable = {
        columns: numbersTable.columns ? [...numbersTable.columns] : [],
        rows: [],
        totals: {}
      };

      if (numbersTable.rows.length && typeof numbersTable.rows[0].count !== "undefined") {
        pctTable.columns = [ numbersTable.columns?.[0] ?? 'Label', 'Percent (%)' ];
        pctTable.rows = numbersTable.rows.map(r => {
          const cnt = Number(r.count) || 0;
          const pct = grandTotal ? +((cnt / grandTotal) * 100).toFixed(2) : 0.0;
          return { label: r.label, percent: pct };
        });
        pctTable.totals = { label: totals.label ?? 'Total', percent: grandTotal ? +(((Number(grandTotal) / grandTotal) * 100).toFixed(2)) : 0.0 };
        return pctTable;
      }

      if (numbersTable.rows.length && Array.isArray(numbersTable.rows[0].values)) {
        pctTable.columns = Array.isArray(numbersTable.columns) ? [...numbersTable.columns] : [];
        pctTable.rows = numbersTable.rows.map(r => {
          const valuesPct = (r.values || []).map(v => {
            const n = Number(v) || 0;
            return grandTotal ? +((n / grandTotal) * 100).toFixed(2) : 0.0;
          });
          const rowTotalPct = grandTotal ? +(((Number(r.total) || 0) / grandTotal) * 100).toFixed(2) : 0.0;
          return { label: r.label, values: valuesPct, total: rowTotalPct };
        });

        const totalsValuesPct = (numbersTable.totals?.values || []).map(v => {
          const n = Number(v) || 0;
          return grandTotal ? +((n / grandTotal) * 100).toFixed(2) : 0.0;
        });
        pctTable.totals = { label: numbersTable.totals?.label ?? 'Total', values: totalsValuesPct, total: grandTotal ? +((grandTotal / grandTotal) * 100).toFixed(2) : 0.0 };
        return pctTable;
      }

      return pctTable;
    },

    formatPercent(v) {
      if (v === null || typeof v === "undefined") return "0.00%";
      const n = Number(v) || 0;
      return n.toFixed(2) + "%";
    },

    //

    async exportStatsWithChartExcel(filenamePrefix = 'stats_export') {
      try {
        const tableNumbers = this.stats_details?.table_numbers ?? this.stats_details?.table ?? null;
        const tablePercentages = this.stats_details?.table_percentages ?? this.tablePercentages ?? null;
        const clients = Array.isArray(this.stats_details?.clients) ? this.stats_details.clients : [];

        if (!tableNumbers && !tablePercentages && !clients.length) {
          this.$showErrors("Error !", ["No data available to export."]);
          return;
        }

        const canvas = this.findChartCanvas();
        const base64Image = canvas ? this.canvasToBase64NoPrefix(canvas) : null;
        const nowIso = new Date().toISOString().slice(0,19).replace(/[:T]/g, '_');
        const filename = `${filenamePrefix}_${nowIso}.xlsx`;

        // Helper: turn table object into rows (numbers table)
        const makeRowsFromNumbers = (tbl) => {
          const rows = [];
          if (!tbl) return rows;
          if (Array.isArray(tbl.rows)) {
            for (const r of tbl.rows) {
              if (Array.isArray(r.values)) {
                rows.push([ r.label, ...r.values.map(v => (v === null || typeof v === 'undefined') ? '' : v), (typeof r.total !== 'undefined' ? r.total : '') ]);
              } else if (typeof r.count !== 'undefined') {
                rows.push([ r.label, Number(r.count) ]);
              } else {
                // fallback: attempt to map columns
                const cols = tbl.columns || [];
                const rowArr = cols.map((c, i) => i === 0 ? (r.label ?? '') : (r.values && r.values[i-1]) ?? (r.count ?? ''));
                rows.push(rowArr);
              }
            }
          }
          return rows;
        };

        // Helper: turn percentages table into rows (similar to numbers)
        const makeRowsFromPercentages = (tbl) => {
          const rows = [];
          if (!tbl) return rows;
          if (Array.isArray(tbl.rows)) {
            for (const r of tbl.rows) {
              if (Array.isArray(r.values)) {
                rows.push([ r.label, ...r.values.map(v => (v === null || typeof v === 'undefined') ? '' : v), (typeof r.total !== 'undefined' ? r.total : '') ]);
              } else if (typeof r.percent !== 'undefined') {
                rows.push([ r.label, Number(r.percent) ]);
              } else if (typeof r.count !== 'undefined') {
                // sometimes percent table might be in count form
                rows.push([ r.label, Number(r.count) ]);
              } else {
                const cols = tbl.columns || [];
                const rowArr = cols.map((c, i) => i === 0 ? (r.label ?? '') : (r.values && r.values[i-1]) ?? (r.percent ?? r.count ?? ''));
                rows.push(rowArr);
              }
            }
          }
          return rows;
        };

        // Helper: clients worksheet columns + rows (pick same columns as your UI)
        const clientCols = [
          { header: 'Index', key: 'idx', width: 8 },
          { header: 'Id', key: 'id', width: 10 },
          { header: 'Created At', key: 'created_at', width: 20 },
          { header: 'Status', key: 'status', width: 12 },
          { header: 'OwnerName', key: 'owner_name', width: 20 },
          { header: 'CustomerCode', key: 'CustomerCode', width: 18 },
          { header: 'CustomerNameE', key: 'CustomerNameE', width: 25 },
          { header: 'CustomerNameA', key: 'CustomerNameA', width: 25 },
          { header: 'DistrictNameE', key: 'DistrictNameE', width: 20 },
          { header: 'CityNameE', key: 'CityNameE', width: 20 },
          { header: 'Address', key: 'Address', width: 30 },
          { header: 'Tel', key: 'Tel', width: 14 },
          { header: 'CustomerType', key: 'CustomerType', width: 16 },
          { header: 'JPlan', key: 'JPlan', width: 12 },
          { header: 'Journee', key: 'Journee', width: 12 },
          { header: 'Frequency', key: 'Frequency', width: 12 }
        ];
        const clientRows = clients.map((c, idx) => ([
          idx + 1,
          c.id ?? '',
          c.created_at ?? '',
          c.status ?? '',
          c.owner_name ?? '',
          c.CustomerCode ?? '',
          c.CustomerNameE ?? '',
          c.CustomerNameA ?? '',
          c.DistrictNameE ?? '',
          c.CityNameE ?? '',
          c.Address ?? '',
          c.Tel ?? '',
          c.CustomerType ?? '',
          c.JPlan ?? '',
          c.Journee ?? '',
          c.Frequency ?? ''
        ]));

        // ExcelJS path (preferred when available globally)
        if (window.ExcelJS && typeof window.ExcelJS.Workbook === 'function' && window.saveAs) {
          const workbook = new window.ExcelJS.Workbook();

          // Numbers sheet (if exists)
          if (tableNumbers) {
            const wsNum = workbook.addWorksheet('Numbers');
            // build columns from tableNumbers.columns if present, else generic
            const cols = (tableNumbers.columns || []).map((c, i) => ({ header: String(c), key: `c${i}`, width: Math.max(10, String(c).length + 4) }));
            if (!cols.length) {
              cols.push({ header: 'Label', key: 'c0', width: 30 });
              cols.push({ header: 'Value', key: 'c1', width: 15 });
            }
            wsNum.columns = cols;
            const numRows = makeRowsFromNumbers(tableNumbers);
            for (const r of numRows) wsNum.addRow(r);
            // totals row formatting (if provided)
            try { wsNum.getRow(1).font = { bold: true }; } catch(e){/*ignore*/}

            // Add chart image to numbers sheet (if available)
            if (base64Image) {
              const imgId = workbook.addImage({ base64: base64Image, extension: 'png' });
              const lastRowNumber = wsNum.lastRow ? wsNum.lastRow.number : 1;
              wsNum.addImage(imgId, {
                tl: { col: 0.5, row: lastRowNumber + 1.5 },
                ext: { width: canvas.width, height: canvas.height }
              });
            }
          }

          // Percentages sheet (if exists)
          if (tablePercentages) {
            const wsPct = workbook.addWorksheet('Percentages');
            const pctCols = (tablePercentages.columns || []).map((c, i) => ({ header: String(c), key: `p${i}`, width: Math.max(10, String(c).length + 4) }));
            if (!pctCols.length) {
              pctCols.push({ header: 'Label', key: 'p0', width: 30 });
              pctCols.push({ header: 'Percent (%)', key: 'p1', width: 15 });
            }
            wsPct.columns = pctCols;
            const pctRows = makeRowsFromPercentages(tablePercentages);
            for (const r of pctRows) wsPct.addRow(r);
            try { wsPct.getRow(1).font = { bold: true }; } catch(e){/*ignore*/}
          }

          // Clients sheet (if any clients)
          if (clientRows.length) {
            const wsClients = workbook.addWorksheet('Clients');
            wsClients.columns = clientCols;
            for (const r of clientRows) wsClients.addRow(r);
            try { wsClients.getRow(1).font = { bold: true }; } catch(e){/*ignore*/}
          }

          // write and download
          const buffer = await workbook.xlsx.writeBuffer();
          const blob = new Blob([buffer], { type: 'application/octet-stream' });
          saveAs(blob, filename);
          this.$feedbackSuccess("Export", "Excel file (Numbers + Percentages + Clients) downloaded");
          return;
        }

        // Fallback: generate separate CSV files + PNG
        // 1) Numbers CSV
        const makeCsvFromTable = (tbl) => {
          if (!tbl) return null;
          const cols = tbl.columns || [];
          const rows = [];
          rows.push(cols.map(c => `"${String(c).replace(/"/g,'""')}"`).join(','));
          const rrows = makeRowsFromNumbers(tbl);
          for (const r of rrows) rows.push(r.map(cell => `"${String(cell ?? '').replace(/"/g,'""')}"`).join(','));
          return rows.join('\n');
        };

        const makeCsvFromPercentTable = (tbl) => {
          if (!tbl) return null;
          const cols = tbl.columns || [];
          const rows = [];
          rows.push(cols.map(c => `"${String(c).replace(/"/g,'""')}"`).join(','));
          const rrows = makeRowsFromPercentages(tbl);
          for (const r of rrows) rows.push(r.map(cell => `"${String(cell ?? '').replace(/"/g,'""')}"`).join(','));
          return rows.join('\n');
        };

        const makeCsvFromClients = (clientsArr) => {
          if (!clientsArr || !clientsArr.length) return null;
          const headers = clientCols.map(c => c.header);
          const rows = [];
          rows.push(headers.map(h => `"${String(h).replace(/"/g,'""')}"`).join(','));
          clientsArr.forEach((c, idx) => {
            const row = [
              idx + 1,
              c.id ?? '',
              c.created_at ?? '',
              c.status ?? '',
              c.owner_name ?? '',
              c.CustomerCode ?? '',
              c.CustomerNameE ?? '',
              c.CustomerNameA ?? '',
              c.DistrictNameE ?? '',
              c.CityNameE ?? '',
              c.Address ?? '',
              c.Tel ?? '',
              c.CustomerType ?? '',
              c.JPlan ?? '',
              c.Journee ?? '',
              c.Frequency ?? ''
            ];
            rows.push(row.map(cell => `"${String(cell ?? '').replace(/"/g,'""')}"`).join(','));
          });
          return rows.join('\n');
        };

        // Download CSV(s)
        const csvNums = makeCsvFromTable(tableNumbers);
        if (csvNums) {
          const blobNum = new Blob([csvNums], { type: 'text/csv;charset=utf-8;' });
          saveAs(blobNum, filename.replace(/\.xlsx$/, '_numbers.csv'));
        }

        const csvPct = makeCsvFromPercentTable(tablePercentages);
        if (csvPct) {
          const blobPct = new Blob([csvPct], { type: 'text/csv;charset=utf-8;' });
          saveAs(blobPct, filename.replace(/\.xlsx$/, '_percentages.csv'));
        }

        const csvClients = makeCsvFromClients(clients);
        if (csvClients) {
          const blobClients = new Blob([csvClients], { type: 'text/csv;charset=utf-8;' });
          saveAs(blobClients, filename.replace(/\.xlsx$/, '_clients.csv'));
        }

        // Chart as PNG as separate download
        if (base64Image) {
          const dataUrl = 'data:image/png;base64,' + base64Image;
          const link = document.createElement('a');
          link.href = dataUrl;
          link.download = filename.replace(/\.xlsx$/, '.png');
          document.body.appendChild(link);
          link.click();
          document.body.removeChild(link);
        }

        this.$feedbackSuccess("Export", "CSV(s) + chart PNG downloaded (ExcelJS not present)");
      } catch (err) {
        console.error(err);
        this.$showErrors("Error !", [err.message || err]);
      }
    },

    exportChartPngAsFile(filenamePrefix = 'chart') {
      try {
        const canvas = this.findChartCanvas();
        if (!canvas) {
          this.$showErrors("Error !", ["Chart canvas not found"]);
          return;
        }
        const dataUrl = canvas.toDataURL('image/png');
        const link = document.createElement('a');
        link.href = dataUrl;
        const nowIso = new Date().toISOString().slice(0,19).replace(/[:T]/g,'_');
        link.download = `${filenamePrefix}_${nowIso}.png`;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        this.$feedbackSuccess("Export", "Chart PNG downloaded");
      } catch (e) {
        console.error(e);
        this.$showErrors("Error !", ["Failed to export chart PNG"]);
      }
    },

    // find chart canvas (prefer child ref)
    findChartCanvas() {
      // try child
      try {
        const child = this.$refs.ByDynamicProperty;
        if (child && child.$refs && child.$refs.reportChart) return child.$refs.reportChart;
      } catch (e) { /* ignore */ }
      // fallback DOM
      const parent = document.getElementById('by_dynamic_property_report');
      if (parent) {
        const c = parent.querySelector('canvas');
        if (c) return c;
      }
      return document.querySelector('canvas');
    },

    // ------------------------------
    // Export functions (Excel with embedded chart image or fallback CSV + PNG)
    // ------------------------------
    canvasToBase64NoPrefix(canvas) {
      if (!canvas) return null;
      const dataUrl = canvas.toDataURL('image/png');
      return dataUrl.split(',')[1];
    },

    getWorksheetColumnsFromTable(tableNumbers) {
      const cols = (tableNumbers?.columns || []).map((c, idx) => {
        return { header: String(c), key: `c${idx}`, width: Math.max(10, String(c).length + 4) };
      });
      return cols;
    },

    getWorksheetRowsFromTable(tableNumbers) {
      const rows = [];
      const cols = tableNumbers?.columns || [];
      for (const r of (tableNumbers?.rows || [])) {
        if (Array.isArray(r.values)) {
          const rowArr = [ r.label, ...r.values.map(v => Number(v) || 0), (typeof r.total !== 'undefined' ? Number(r.total) : '') ];
          rows.push(rowArr);
        } else if (typeof r.count !== 'undefined') {
          const rowArr = [ r.label, Number(r.count) ];
          rows.push(rowArr);
        } else {
          const rowArr = cols.map((c, i) => {
            if (i === 0) return r.label ?? '';
            return (r.values && r.values[i-1]) ?? (r.count ?? '');
          });
          rows.push(rowArr);
        }
      }
      return rows;
    },

    //  //  //  //  //
    //  //  //  //  //
    //  //  //  //  //

    prepareClientsTable() {
      try {
          // destroy existing DataTable if present
          if (this.datatable_statistic_details_clients) {
            try { this.datatable_statistic_details_clients.destroy(); } catch(e){ /* ignore */ }
            this.datatable_statistic_details_clients = null;
          }

          // assign clients to reactive array used by the table template
          this.statistic_details_clients = this.stats_details.clients || [];

          // Wait DOM to render rows
          this.$nextTick(async () => {
            // Initialize DataTable directly with options (no pagination)
            // If you have a wrapper $DataTableCreate that accepts options, use that instead
            // Example using jQuery DataTables:
            this.datatable_statistic_details_clients = await this.datatable_statistic_details_clients_instance.$DataTableCreate("statistic_details_clients", this.statistic_details_clients_data, this.statistic_details_clients_columns, null, null, null, null, null, "Map Clients")
          });

      } catch (e) {
          console.error(e);
      }
    },

    //  //  //  //  //

    // Clicked the label area (or stacked area) — aggregate across datasets
    async openForAllDatasetsAtIndex(labelIndex) {
      // FIX: Use original label
      const rawLabel = this.chartDataNormalized.labels[labelIndex];

      // collect all dataset keys
      const keys = this.chartInstance.data.datasets.map(d => d.clientKey || d.key || d.label);

      // filter clients
      if(this.secondary_property) {
          this.selectedClients = this.statistic_details_clients.filter(c => 
              (String(c[this.primary_property]) === String(rawLabel)) && 
              (keys.includes(c[this.secondary_property]))
          );
      } else {
          this.selectedClients = this.statistic_details_clients.filter(c => 
              (String(c[this.primary_property]) === String(rawLabel))
          );
      }

      // ShowModal
      var ModalSelfServiceChartClients = new Modal(document.getElementById("ModalSelfServiceChartClients"));
      ModalSelfServiceChartClients.show();

      this.$refs.ModalSelfServiceChartClients.setDataTable(this.selectedClients);
    },

    // Clicked a single dataset segment (labelIndex + datasetIndex)
    async openForDatasetAtIndex(labelIndex, datasetIndex) {
      const rawLabel = this.chartDataNormalized.labels[labelIndex]; 
      
      const dataset = this.chartInstance.data.datasets[datasetIndex];
      
      // Ensure we are checking the correct key property. 
      // Sometimes users use 'key', 'id', or 'clientKey'.
      const key = dataset.clientKey || dataset.key || dataset.label; 

      console.log("Filtering by:", { rawLabel, key }); // Debugging aid

      this.selectedClients = this.getClientsByLabelAndDatasetKey(rawLabel, key);
      
      await this.$nextTick();

      // ShowModal
      var ModalSelfServiceChartClients = new Modal(document.getElementById("ModalSelfServiceChartClients"));
      ModalSelfServiceChartClients.show();

      await this.$refs.ModalSelfServiceChartClients.setDataTable(this.selectedClients);
    },

    //
    getClientsByLabelAndDatasetKey(label, key) {
      // e.g. clients where month === label and ownerKey === key
      if(this.secondary_property) return this.statistic_details_clients.filter(c => (c[this.primary_property] === label) && (c[this.secondary_property] === key));
      else                        return this.statistic_details_clients.filter(c => (c[this.primary_property] === label));
    },
  }
}
</script>

<style scoped>
  .chart_container {
    width: 100%;
    height: 360px; /* give chart a fixed height; adjust as needed */
    position: relative;
  }
  .table_container {
    max-height: 500px;
    overflow: auto;
  }
</style>