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

                    <div class="col-sm-2">
                        <div id="show_hide_values_toggle_div" class="h-100 w-100">
                            <div class="h-100 w-100" id="showValuesToggle_div">
                                <label class="switch btn-color-mode-switch h-100 w-100">
                                    <input type="checkbox"        name="showValuesToggle"   id="showValuesToggle"   v-model="showValuesToggle"      :disabled="chartBuilding"   @change="buildChart()"  true-value="1"  false-value="0">
                                    <label for="showValuesToggle" data-on="Show Values"     data-off="Hide Values"                                  class="btn-color-mode-switch-inner h-100"></label>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Export Buttons -->
                    <div class="col-sm-3">
                        <button class="btn btn-success w-100 h-100" @click="exportStatsWithChartExcel()"  :disabled="chartBuilding">Export XLSX</button>
                    </div>

                    <div class="col-sm-3">
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
                <div id="chart_scroll" class="chart_scroll pt-4 pb-1" style="width: 100%; overflow-x: auto; overflow-y: hidden;">
                    <div id="chart_container" class="chart_container" style="position: relative; height: 500px; width: 100%;">
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
                                <td class='col' v-if="row.count !== undefined">{{ row.count }}</td>
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

    stats_mode: {
      type: String,
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
                                                        { data: "owner"                 , title: "owner_username"       },
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
      datatable_statistic_details_clients_instance  : null,

      //
      showValuesToggle                              : "1", // Default to true (visible)
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

    async buildChart_new() {
        console.log(this.showValuesToggle);

        this.chartBuilding = true;

        try {
            this.destroyChart();

            const canvas = this.$refs.reportChart;
            if (!canvas) return;
            const ctx = canvas.getContext?.("2d");
            if (!ctx) return;

            const chartPayload = this.chartDataNormalized || { labels: [], datasets: [] };

            // 1. Prepare standard Datasets (Types)
            let datasets = (chartPayload.datasets || []).map((ds, idx) => {
                if (Array.isArray(ds)) {
                    return { label: `Series ${idx + 1}`, data: ds };
                }
                return {
                    ...ds,
                    label: ds.label ?? `Series ${idx + 1}`,
                    data: Array.isArray(ds.data) ? ds.data : [],
                    backgroundColor: ds.backgroundColor,
                    borderColor: ds.borderColor
                };
            });

            // 2. Prepare standard Labels (Cities / slices)
            let labelsForChart = (chartPayload.labels || []).map(lbl => {
                const s = String(lbl ?? '');
                const parts = this.$splitOnSpaceChartJSLabels([s])[0] || [s];
                return parts.join('\n');
            });

            const type = (this.chartType || "bar").toLowerCase();
            const isPieLike = (type === 'pie' || type === 'doughnut');

            // ######################################################
            // ### TRANSPOSE DATA FOR CONCENTRIC RINGS ONLY WHEN NEEDED ###
            // ######################################################
            if (isPieLike) {
                // Decide whether we SHOULD transpose:
                // - If stats_mode === 'by_property_mode' then the payload is the "many datasets = types" shape and MUST be transposed.
                // - If stats_mode is missing, assume transposition is needed only when there are multiple datasets.
                const shouldTranspose = (this.stats_mode === 'by_property_mode')
                    || (!this.stats_mode && datasets.length > 1);

                if (shouldTranspose) {
                    // Build typeLabels from dataset labels (these become pie slices)
                    const typeLabels = datasets.map(ds => String(ds.label ?? '') || 'Untitled');

                    // Build new datasets: one dataset per original label (city),
                    // where each dataset.data is an array of values across types
                    const cityDatasets = labelsForChart.map((cityLabel, cityIndex) => {
                        const cityData = datasets.map(typeDataset => {
                            // ensure numeric and safe
                            const v = typeDataset.data?.[cityIndex];
                            return Number(v) || 0;
                        });
                        return {
                            label: cityLabel,
                            data: cityData,
                        };
                    });

                    // Replace variables
                    labelsForChart = typeLabels;
                    datasets = cityDatasets;
                } else {
                    // No transpose; ensure dataset data lengths match labels (pad with zeros if needed)
                    datasets = datasets.map(ds => {
                        const wanted = labelsForChart.length;
                        const copy = Array.from(ds.data || []);
                        while (copy.length < wanted) copy.push(0);
                        if (copy.length > wanted) copy.length = wanted; // trim if longer
                        return { ...ds, data: copy };
                    });
                }
            } else {
                // bar/line: ensure each dataset length matches labels (pad/trim)
                datasets = datasets.map(ds => {
                    const wanted = labelsForChart.length;
                    const copy = Array.isArray(ds.data) ? Array.from(ds.data) : [];
                    while (copy.length < wanted) copy.push(0);
                    if (copy.length > wanted) copy.length = wanted;
                    return { ...ds, data: copy };
                });
            }
            // ######################################################

            const after_chart_rendered_plugin = this.$chartRendered(this.setReportChart);
            let activePlugins = [after_chart_rendered_plugin];

            // Only push ChartDataLabels into activePlugins if we actually have it (we register later too)
            if (typeof ChartDataLabels !== 'undefined') {
                activePlugins.push(ChartDataLabels);
            }

            // Only push the "Value On Top" plugin if toggle is 1
            if (this.showValuesToggle == "1") {
                const valueOnTopPlugin = this.$valueOnTopOfEachBarPlugin('value_on_top', true, 2);
                activePlugins.push(valueOnTopPlugin);
            }

            datasets = this.$setDefaultColors(datasets, labelsForChart, type);

            const numDataPoints = labelsForChart.length;
            const minWidthPerPoint = 40;
            const totalWidth = numDataPoints * minWidthPerPoint;

            const container = document.getElementById('chart_container');
            const wrapper = document.getElementById('chart_scroll');

            if (container && wrapper) {
                if (totalWidth > wrapper.clientWidth) {
                    container.style.width = `${totalWidth}px`;
                } else {
                    container.style.width = '100%';
                }
            }

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
                        // Legend visible always
                        legend: {
                            position: "top",
                            display: true
                        },
                    },
                    scales: {},
                    onClick: async (evt) => {
                        const elems = this.chartInstance.getElementsAtEventForMode(evt, 'nearest', { intersect: true }, true);
                        if (elems && elems.length) {
                            const el = elems[0];
                            await this.openForDatasetAtIndex(el.index, el.datasetIndex);
                            return;
                        }
                        const allAtIndex = this.chartInstance.getElementsAtEventForMode(evt, 'index', { intersect: false }, true);
                        if (allAtIndex && allAtIndex.length) {
                            const idx = allAtIndex[0].index;
                            await this.openForAllDatasetsAtIndex(idx);
                        }
                    },
                },
                plugins: activePlugins
            };

            if (type === "bar" || type === "line") {
                chartConfig.options.scales = {
                    x: { stacked: false, ticks: { autoSkip: false } },
                    y: { beginAtZero: true, ticks: { precision: 0 } }
                };
            } else {
                chartConfig.options.scales = undefined;
            }

            const hasDataLabels = typeof ChartDataLabels !== 'undefined';
            if (hasDataLabels) {
                try {
                    if (Chart && Chart.register && !Chart.registry?.plugins?.get?.('datalabels')) {
                        Chart.register(ChartDataLabels);
                    }
                } catch (e) { /* ignore registration errors */ }

                // Configure datalabels plugin only (already added to plugins array above)
                chartConfig.options.plugins.datalabels = {
                    display: (context) => {
                        return isPieLike && (this.showValuesToggle == "1");
                    },
                    color: '#ffffff',
                    anchor: 'center',
                    align: 'center',
                    font: { weight: 'bold', size: 10 },
                    clamp: true,
                    clip: true,
                    formatter: (value, ctx) => {
                        if (!isPieLike) return '';
                        const data = ctx.chart.data.datasets[ctx.datasetIndex].data;
                        const total = data.reduce((acc, v) => acc + (Number(v) || 0), 0);
                        const pct = total ? ((Number(value) || 0) / total * 100) : 0;
                        if (pct < 3) return '';
                        return !isNaN(pct) ? pct.toFixed(1) + '%' : '';
                    }
                };
            } else {
                chartConfig.options.plugins.datalabels = { display: false };
            }

            try {
                this.chartInstance = new Chart(ctx, chartConfig);
            } catch (e) {
                console.error('Chart creation failed:', e);
                this.chartInstance = null;
            }

            this.emitter.on('reportChart_rendered', () => {
                this.emitter.off('reportChart_rendered');
                this.chartBuilding = false;
            });
        } catch (e) {
            console.error(e);
            this.chartBuilding = false;
        }
    },

    async buildChart() {
        console.log(this.showValuesToggle);

        this.chartBuilding = true;

        try {
            this.destroyChart();

            const canvas = this.$refs.reportChart;
            if (!canvas) return;
            const ctx = canvas.getContext?.("2d");
            if (!ctx) return;

            const chartPayload = this.chartDataNormalized || { labels: [], datasets: [] };

            // 1. Prepare standard Datasets (Types)
            let datasets = (chartPayload.datasets || []).map((ds, idx) => {
                if (Array.isArray(ds)) {
                    return { label: `Series ${idx + 1}`, data: ds };
                }
                return {
                    ...ds,
                    label: (ds.label ?? `Series ${idx + 1}`),
                    data: Array.isArray(ds.data) ? ds.data : [],
                    backgroundColor: ds.backgroundColor,
                    borderColor: ds.borderColor,
                    borderWidth: ds.borderWidth,
                    pointRadius: ds.pointRadius
                };
            });

            // 2. Prepare standard Labels (Cities / slices)
            let labelsForChart = (chartPayload.labels || []).map(lbl => {
                const s = String(lbl ?? '');
                const parts = this.$splitOnSpaceChartJSLabels([s])[0] || [s];
                return parts.join('\n');
            });

            const type = (this.chartType || "bar").toLowerCase();
            const isPieLike = (type === 'pie' || type === 'doughnut');

            // ######################################################
            // ### TRANSPOSE DATA FOR CONCENTRIC RINGS ONLY WHEN NEEDED ###
            // ######################################################
            if (isPieLike) {
                const shouldTranspose = (chartPayload.stats_mode === 'by_property_mode')
                    || (!chartPayload.stats_mode && datasets.length > 1);

                if (shouldTranspose) {
                    const typeLabels = datasets.map(ds => String(ds.label ?? '') || 'Untitled');

                    const cityDatasets = labelsForChart.map((cityLabel, cityIndex) => {
                        const cityData = datasets.map(typeDataset => {
                            const v = typeDataset.data?.[cityIndex];
                            return Number(v) || 0;
                        });
                        return {
                            label: cityLabel,
                            data: cityData,
                        };
                    });

                    labelsForChart = typeLabels;
                    datasets = cityDatasets;
                } else {
                    datasets = datasets.map(ds => {
                        const wanted = labelsForChart.length;
                        const copy = Array.from(ds.data || []);
                        while (copy.length < wanted) copy.push(0);
                        if (copy.length > wanted) copy.length = wanted;
                        return { ...ds, data: copy };
                    });
                }
            } else {
                // bar/line: normalize data lengths
                datasets = datasets.map(ds => {
                    const wanted = labelsForChart.length;
                    const copy = Array.isArray(ds.data) ? Array.from(ds.data) : [];
                    while (copy.length < wanted) copy.push(0);
                    if (copy.length > wanted) copy.length = wanted;
                    return { ...ds, data: copy };
                });
            }
            // ######################################################

            // Ensure each dataset has a label (important for normal legend)
            datasets = datasets.map((ds, i) => ({ ...ds, label: String(ds.label ?? `Series ${i+1}`) }));

            const after_chart_rendered_plugin = this.$chartRendered(this.setReportChart);
            let activePlugins = [after_chart_rendered_plugin];

            // Only push ChartDataLabels if present
            const hasChartDataLabels = typeof ChartDataLabels !== 'undefined';
            if (hasChartDataLabels) activePlugins.push(ChartDataLabels);

            if (this.showValuesToggle == "1") {
                const valueOnTopPlugin = this.$valueOnTopOfEachBarPlugin('value_on_top', true, 2);
                activePlugins.push(valueOnTopPlugin);
            }

            datasets = this.$setDefaultColors(datasets, labelsForChart, type);

            // --- Prepare original arrays for backgrounds, borders, widths, pointRadius ---
            const originalColorsPerDataset = datasets.map(ds => {
                if (Array.isArray(ds.backgroundColor)) {
                    const arr = ds.backgroundColor.slice();
                    if (arr.length < labelsForChart.length) {
                        const fillVal = arr[arr.length - 1] ?? (ds.borderColor ?? 'rgba(0,0,0,0.1)');
                        while (arr.length < labelsForChart.length) arr.push(fillVal);
                    } else if (arr.length > labelsForChart.length) arr.length = labelsForChart.length;
                    return arr.slice();
                } else {
                    const colorVal = ds.backgroundColor ?? ds.borderColor ?? 'rgba(54,162,235,0.6)';
                    return Array.from({ length: labelsForChart.length }, () => colorVal);
                }
            });

            const originalBorderPerDataset = datasets.map(ds => {
                if (Array.isArray(ds.borderColor)) {
                    const arr = ds.borderColor.slice();
                    if (arr.length < labelsForChart.length) {
                        const fillVal = arr[arr.length - 1] ?? 'rgba(0,0,0,0.1)';
                        while (arr.length < labelsForChart.length) arr.push(fillVal);
                    } else if (arr.length > labelsForChart.length) arr.length = labelsForChart.length;
                    return arr.slice();
                } else {
                    const colorVal = ds.borderColor ?? 'rgba(0,0,0,0.1)';
                    return Array.from({ length: labelsForChart.length }, () => colorVal);
                }
            });

            const originalBorderWidthPerDataset = datasets.map(ds => {
                if (Array.isArray(ds.borderWidth)) {
                    const arr = ds.borderWidth.slice();
                    if (arr.length < labelsForChart.length) {
                        const fillVal = arr[arr.length - 1] ?? (typeof ds.borderWidth === 'number' ? ds.borderWidth : 1);
                        while (arr.length < labelsForChart.length) arr.push(fillVal);
                    } else if (arr.length > labelsForChart.length) arr.length = labelsForChart.length;
                    return arr.slice();
                } else {
                    const bw = (typeof ds.borderWidth === 'number') ? ds.borderWidth : 1;
                    return Array.from({ length: labelsForChart.length }, () => bw);
                }
            });

            // pointRadius original (for line charts points)
            const originalPointRadiusPerDataset = datasets.map(ds => {
                if (Array.isArray(ds.pointRadius)) {
                    const arr = ds.pointRadius.slice();
                    if (arr.length < labelsForChart.length) {
                        const fillVal = arr[arr.length - 1] ?? (typeof ds.pointRadius === 'number' ? ds.pointRadius : 3);
                        while (arr.length < labelsForChart.length) arr.push(fillVal);
                    } else if (arr.length > labelsForChart.length) arr.length = labelsForChart.length;
                    return arr.slice();
                } else {
                    const pr = (typeof ds.pointRadius === 'number') ? ds.pointRadius : 3;
                    return Array.from({ length: labelsForChart.length }, () => pr);
                }
            });

            // Ensure each dataset.backgroundColor/borderColor/borderWidth/pointRadius are arrays
            datasets = datasets.map((ds, i) => {
                const bg = Array.isArray(ds.backgroundColor) ? ds.backgroundColor.slice() : originalColorsPerDataset[i].slice();
                const br = Array.isArray(ds.borderColor) ? ds.borderColor.slice() : originalBorderPerDataset[i].slice();
                const bw = Array.isArray(ds.borderWidth) ? ds.borderWidth.slice() : originalBorderWidthPerDataset[i].slice();
                const pr = Array.isArray(ds.pointRadius) ? ds.pointRadius.slice() : originalPointRadiusPerDataset[i].slice();
                // normalize lengths
                bg.length = labelsForChart.length; br.length = labelsForChart.length;
                bw.length = labelsForChart.length; pr.length = labelsForChart.length;
                // fill undefined slots with originals
                for (let k=0;k<labelsForChart.length;k++){
                    if (bg[k] === undefined) bg[k] = originalColorsPerDataset[i][k];
                    if (br[k] === undefined) br[k] = originalBorderPerDataset[i][k];
                    if (bw[k] === undefined) bw[k] = originalBorderWidthPerDataset[i][k];
                    if (pr[k] === undefined) pr[k] = originalPointRadiusPerDataset[i][k];
                }
                return { ...ds, backgroundColor: bg, borderColor: br, borderWidth: bw, pointRadius: pr };
            });

            const numDataPoints = labelsForChart.length;
            const minWidthPerPoint = 40;
            const totalWidth = numDataPoints * minWidthPerPoint;

            const container = document.getElementById('chart_container');
            const wrapper = document.getElementById('chart_scroll');

            if (container && wrapper) {
                if (totalWidth > wrapper.clientWidth) {
                    container.style.width = `${totalWidth}px`;
                } else {
                    container.style.width = '100%';
                }
            }

            // Build legend configuration:
            let legendConfig = {
                position: "top",
                display: true,
                labels: { color: '#000' }
            };

            const wantsLegendAsXAxisLabels = (!isPieLike)
                && (chartPayload.stats_mode === 'standard_mode' || !chartPayload.stats_mode)
                && (datasets.length === 1);

            if (wantsLegendAsXAxisLabels) {
                legendConfig = {
                    position: 'top',
                    display: true,
                    labels: {
                        generateLabels: (chart) => {
                            const ds = chart.data.datasets[0];
                            const colors = Array.isArray(ds.backgroundColor) ? ds.backgroundColor : originalColorsPerDataset[0];
                            return labelsForChart.map((lab, idx) => ({
                                text: lab.replace(/\n/g, ' '),
                                fillStyle: colors[idx] ?? 'rgba(0,0,0,0.1)',
                                hidden: !!(chart._hiddenIndexes && chart._hiddenIndexes.has(idx)),
                                index: idx
                            }));
                        },
                        color: '#000',
                    },
                    onClick: (evt, legendItem, legend) => {
                        const chart = legend.chart;
                        const idx = legendItem.index;
                        if (!chart._hiddenIndexes) chart._hiddenIndexes = new Set();

                        const isHidden = chart._hiddenIndexes.has(idx);
                        if (isHidden) {
                            // show: restore colors/borders/widths/pointRadius for all datasets at index idx
                            chart._hiddenIndexes.delete(idx);
                            chart.data.datasets.forEach((dset, dIdx) => {
                                dset.backgroundColor[idx] = originalColorsPerDataset[dIdx][idx] ?? dset.backgroundColor[idx];
                                dset.borderColor[idx] = originalBorderPerDataset[dIdx][idx] ?? dset.borderColor[idx];
                                dset.borderWidth[idx] = originalBorderWidthPerDataset[dIdx][idx] ?? dset.borderWidth[idx];
                                if (dset.pointRadius && Array.isArray(dset.pointRadius)) {
                                    dset.pointRadius[idx] = originalPointRadiusPerDataset[dIdx][idx] ?? dset.pointRadius[idx];
                                }
                            });
                        } else {
                            // hide: set backgroundColor/borderColor transparent and borderWidth 0 and pointRadius 0
                            chart._hiddenIndexes.add(idx);
                            chart.data.datasets.forEach((dset) => {
                                dset.backgroundColor[idx] = 'rgba(0,0,0,0)';
                                dset.borderColor[idx] = 'rgba(0,0,0,0)';
                                dset.borderWidth[idx] = 0;
                                if (dset.pointRadius && Array.isArray(dset.pointRadius)) {
                                    dset.pointRadius[idx] = 0;
                                }
                            });
                        }
                        chart.update();
                    }
                };
            }

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
                        legend: legendConfig,
                    },
                    scales: {},
                    onClick: async (evt) => {
                        const elems = this.chartInstance.getElementsAtEventForMode(evt, 'nearest', { intersect: true }, true);
                        if (elems && elems.length) {
                            const el = elems[0];
                            await this.openForDatasetAtIndex(el.index, el.datasetIndex);
                            return;
                        }
                        const allAtIndex = this.chartInstance.getElementsAtEventForMode(evt, 'index', { intersect: false }, true);
                        if (allAtIndex && allAtIndex.length) {
                            const idx = allAtIndex[0].index;
                            await this.openForAllDatasetsAtIndex(idx);
                        }
                    },
                },
                plugins: activePlugins
            };

            if (type === "bar" || type === "line") {
                chartConfig.options.scales = {
                    x: { stacked: false, ticks: { autoSkip: false } },
                    y: { beginAtZero: true, ticks: { precision: 0 } }
                };
            } else {
                chartConfig.options.scales = undefined;
            }

            if (hasChartDataLabels) {
                try {
                    if (Chart && Chart.register && !Chart.registry?.plugins?.get?.('datalabels')) {
                        Chart.register(ChartDataLabels);
                    }
                } catch (e) { /* ignore registration issues */ }

                chartConfig.options.plugins.datalabels = {
                    display: (context) => {
                        return isPieLike && (this.showValuesToggle == "1");
                    },
                    color: '#ffffff',
                    anchor: 'center',
                    align: 'center',
                    font: { weight: 'bold', size: 10 },
                    clamp: true,
                    clip: true,
                    formatter: (value, ctx) => {
                        if (!isPieLike) return '';
                        const data = ctx.chart.data.datasets[ctx.datasetIndex].data;
                        const total = data.reduce((acc, v) => acc + (Number(v) || 0), 0);
                        const pct = total ? ((Number(value) || 0) / total * 100) : 0;
                        if (pct < 3) return '';
                        return !isNaN(pct) ? pct.toFixed(1) + '%' : '';
                    }
                };
            } else {
                chartConfig.options.plugins.datalabels = { display: false };
            }

            try {
                this.chartInstance = new Chart(ctx, chartConfig);
                // Attach originals for debugging/inspection
                this.chartInstance._originalColorsPerDataset = originalColorsPerDataset;
                this.chartInstance._originalBorderPerDataset = originalBorderPerDataset;
                this.chartInstance._originalBorderWidthPerDataset = originalBorderWidthPerDataset;
                this.chartInstance._originalPointRadiusPerDataset = originalPointRadiusPerDataset;
            } catch (e) {
                console.error('Chart creation failed:', e);
                this.chartInstance = null;
            }

            this.emitter.on('reportChart_rendered', () => {
                this.emitter.off('reportChart_rendered');
                this.chartBuilding = false;
            });
        } catch (e) {
            console.error(e);
            this.chartBuilding = false;
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
          { header: 'owner_username', key: 'owner_username', width: 20 },
          { header: 'CustomerCode', key: 'CustomerCode', width: 18 },
          { header: 'CustomerNameE', key: 'CustomerNameE', width: 25 },
          { header: 'CustomerNameA', key: 'CustomerNameA', width: 25 },
          { header: 'DistrictNameE', key: 'DistrictNameE', width: 20 },
          { header: 'CityNameE', key: 'CityNameE', width: 20 },
          { header: 'Address', key: 'Address', width: 30 },
          { header: 'RvrsGeoAddress', key: 'RvrsGeoAddress', width: 30},
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
          c.owner_username ?? '',
          c.CustomerCode ?? '',
          c.CustomerNameE ?? '',
          c.CustomerNameA ?? '',
          c.DistrictNameE ?? '',
          c.CityNameE ?? '',
          c.Address ?? '',
          c.RvrsGeoAddress ?? '',
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
              c.owner_username ?? '',
              c.CustomerCode ?? '',
              c.CustomerNameE ?? '',
              c.CustomerNameA ?? '',
              c.DistrictNameE ?? '',
              c.CityNameE ?? '',
              c.Address ?? '',
              c.RvrsGeoAddress ?? '',
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

    //
    getClientsByLabelAndDatasetKey(label, key) {
      // e.g. clients where month === label and ownerKey === key
      if(this.secondary_property) return this.statistic_details_clients.filter(c => (c[this.primary_property] === label) && (c[this.secondary_property] === key));
      else                        return this.statistic_details_clients.filter(c => (c[this.primary_property] === label));
    },

    //  //  //

    // Helper to determine if the current chart is using the transposed (Concentric) logic
    isChartTransposed() {
        const chartPayload = this.chartDataNormalized || { labels: [], datasets: [] };
        const type = (this.chartType || "bar").toLowerCase();
        const isPieLike = (type === 'pie' || type === 'doughnut');
        
        // This condition must match exactly the logic used in buildChart
        return isPieLike && (
            (chartPayload.stats_mode === 'by_property_mode') || 
            (!chartPayload.stats_mode && chartPayload.datasets.length > 1)
        );
    },

    // Clicked a single dataset segment (labelIndex + datasetIndex)
    async openForDatasetAtIndex(labelIndex, datasetIndex) {
        const isTransposed = this.isChartTransposed();
        
        let rawLabel; // The primary property (e.g., City/Date)
        let key;      // The secondary property (e.g., Status/Series)

        if (isTransposed) {
            // --- TRANSPOSED MODE (Concentric Pie) ---
            // visual datasetIndex = index of the Original Label (Ring)
            // visual labelIndex   = index of the Original Dataset (Slice Color)

            // 1. Get Label from the normalized labels array using the *datasetIndex*
            rawLabel = this.chartDataNormalized.labels[datasetIndex];

            // 2. Get Key from the normalized datasets array using the *labelIndex*
            const originalDataset = this.chartDataNormalized.datasets[labelIndex];
            key = originalDataset ? (originalDataset.clientKey || originalDataset.key || originalDataset.label) : null;

        } else {
            // --- STANDARD MODE ---
            rawLabel = this.chartDataNormalized.labels[labelIndex];
            
            // In standard mode, we can trust the chartInstance's dataset or the normalized one at datasetIndex
            const dataset = this.chartInstance.data.datasets[datasetIndex];
            key = dataset.clientKey || dataset.key || dataset.label;
        }

        console.log(`Filtering (${isTransposed ? 'Transposed' : 'Standard'}) by:`, { rawLabel, key });

        this.selectedClients = this.getClientsByLabelAndDatasetKey(rawLabel, key);
        
        await this.$nextTick();

        // ShowModal
        var ModalSelfServiceChartClients = new Modal(document.getElementById("ModalSelfServiceChartClients"));
        ModalSelfServiceChartClients.show();

        if (this.$refs.ModalSelfServiceChartClients) {
            this.$refs.ModalSelfServiceChartClients.setDataTable(this.selectedClients);
        }
    },

    // Clicked the label area (or stacked area)
    async openForAllDatasetsAtIndex(indexFromEvent) {
        const isTransposed = this.isChartTransposed();
        let rawLabel;
        
        // In transposed mode, the concept of "Index" usually refers to the Legend (The Series/Status).
        // In standard mode, "Index" refers to the X-Axis Label (The City/Date).
        
        if (isTransposed) {
            // If transposed, clicking an "Index" (like via legend) implies filtering by the Series (the visual Label)
            // It gets complicated here because 'rawLabel' usually expects the Primary Property.
            // Depending on your UX, you might want to disable this for Concentric pies or handle it specifically.
            // Assuming you want to show all clients for a specific Series across all Cities:
            
            const originalDataset = this.chartDataNormalized.datasets[indexFromEvent];
            const targetKey = originalDataset ? (originalDataset.clientKey || originalDataset.key || originalDataset.label) : null;
            
            // Filter: All clients where Secondary Property == targetKey
            if (this.secondary_property) {
                this.selectedClients = this.statistic_details_clients.filter(c => 
                    String(c[this.secondary_property]) === String(targetKey)
                );
            } else {
                // If no secondary property, this state is ambiguous in transposed mode
                this.selectedClients = [];
            }
        } else {
            // --- STANDARD MODE ---
            rawLabel = this.chartDataNormalized.labels[indexFromEvent];

            // collect all dataset keys currently visible
            const keys = this.chartInstance.data.datasets.map(d => d.clientKey || d.key || d.label);

            if (this.secondary_property) {
                this.selectedClients = this.statistic_details_clients.filter(c => 
                    (String(c[this.primary_property]) === String(rawLabel)) && 
                    (keys.includes(c[this.secondary_property]))
                );
            } else {
                this.selectedClients = this.statistic_details_clients.filter(c => 
                    (String(c[this.primary_property]) === String(rawLabel))
                );
            }
        }

        // ShowModal
        var ModalSelfServiceChartClients = new Modal(document.getElementById("ModalSelfServiceChartClients"));
        ModalSelfServiceChartClients.show();

        if (this.$refs.ModalSelfServiceChartClients) {
            this.$refs.ModalSelfServiceChartClients.setDataTable(this.selectedClients);
        }
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

  /* make the inner label a stable positioning context */
  /* make the inner label a stable positioning context */
  /* make the inner label a stable positioning context */
  /* make the inner label a stable positioning context */
  /* make the inner label a stable positioning context */
  /* make the inner label a stable positioning context */
  /* make the inner label a stable positioning context */
  /* make the inner label a stable positioning context */

  /* make the inner label a stable positioning context */
  #show_hide_values_toggle_div .btn-color-mode-switch > label.btn-color-mode-switch-inner {
    width: 100%;
    height: 50px;
    position: relative;    /* already set, but ensure present */
    display: block;
    box-sizing: border-box;
  }

  /* center both pseudo-elements vertically using transform */
  #show_hide_values_toggle_div .btn-color-mode-switch > label.btn-color-mode-switch-inner:before,
  #show_hide_values_toggle_div .btn-color-mode-switch > label.btn-color-mode-switch-inner:after {
    top: 50%;
    transform: translateY(-50%);
    position: absolute;
    pointer-events: none; /* avoid interfering with the checkbox */
  }

  /* keep the "on" text on the right (visually centered vertically now) */
  #show_hide_values_toggle_div .btn-color-mode-switch > label.btn-color-mode-switch-inner:before {
    content: attr(data-on);
    font-size: 12px;
    font-weight: 500;
    right: 20px;
    line-height: 1;
  }

  /* style the knob and center its text with flexbox */
  #show_hide_values_toggle_div .btn-color-mode-switch > label.btn-color-mode-switch-inner:after {
    content: attr(data-off);
    width: 50%;
    height: 40px;
    background: #fff;
    border-radius: 26px;
    left: 3px;
    /* top and transform already set above */
    display: flex;              /* center text horizontally + vertically */
    align-items: center;
    justify-content: center;
    text-align: center;
    transition: all 0.3s ease;
    box-shadow: 0 0 6px -2px #111;
    padding: 0;                 /* let flex handle centering */
    line-height: 1;
  }

  /* checked state: move knob and swap the displayed label */
  #show_hide_values_toggle_div .btn-color-mode-switch input[type="checkbox"]:checked + label.btn-color-mode-switch-inner {
    background: #2c78bd;
    color: #fff;
  }

  #show_hide_values_toggle_div .btn-color-mode-switch input[type="checkbox"]:checked + label.btn-color-mode-switch-inner:after {
    content: attr(data-on);
    left: 49%;                  /* places the knob at the right half */
    /* top/transform stay to keep vertical centering */
    background: #3c3c3c;
    color: #fff;
  }

  #show_hide_values_toggle_div .btn-color-mode-switch input[type="checkbox"]:checked + label.btn-color-mode-switch-inner:before {
    /* swap the smaller on/off copy position so the right/left text flips correctly */
    content: attr(data-off);
    right: auto;
    left: 20px;
  }

</style>