<template>
  <div class="p-3 m-2 h-100" style="background-color: #f2edf3; padding : 15px;">
    <div class="row">
      <!-- Stats Details -->
      <div class="col-sm-12 grid-margin mb-0">
        <div class="card">
          <div class="card-body p-0 pt-1">
            <div class="col-sm-12 p-2" id="stats_details_filters">
              <div class="row">
                <h3 class="text-center">Statistics Details</h3>
              </div>

              <div class="row mt-3">
                <!-- Select Map  -->
                <div class="col-sm-2 p-1">
                  <Multiselect
                    v-model="route_link"
                    :options="liste_route_link"
                    mode="single"
                    placeholder="Select Map"
                    :close-on-select="true"
                    :searchable="true"
                    :create-option="false"
                    :canDeselect="false"
                    :canClear="false"
                    :allowAbsent="false"
                  />
                </div>

                <!-- Select Mode  -->
                <div class="col-sm-2 p-1">
                  <Multiselect
                    v-model="stats_mode"
                    :options="modes"
                    mode="single"
                    placeholder="Select Mode"
                    :close-on-select="true"
                    :searchable="true"
                    :create-option="false"
                    :canDeselect="false"
                    :canClear="false"
                    :allowAbsent="false"
                  />
                </div>

                <!-- Select Primary Property  -->
                <div v-if="(stats_mode == 'standard_mode') || (stats_mode == 'by_property_mode')" class="col-sm-2 p-1">
                  <Multiselect
                    v-model="primary_property"
                    :options="properties"
                    mode="single"
                    placeholder="Select Primary Property"
                    :close-on-select="true"
                    :searchable="true"
                    :create-option="false"
                    :canDeselect="false"
                    :canClear="false"
                    :allowAbsent="false"
                  />
                </div>

                <!-- Select Secondary Property  -->
                <div v-if="(stats_mode == 'by_property_mode') || (stats_mode == 'articles_availability_by_property_mode') || (stats_mode == 'articles_nbr_facing_by_property_mode')" class="col-sm-2 p-1">
                  <Multiselect
                    v-model="secondary_property"
                    :options="properties"
                    mode="single"
                    placeholder="Select Secondary Property"
                    :close-on-select="true"
                    :searchable="true"
                    :create-option="false"
                    :canDeselect="false"
                    :canClear="false"
                    :allowAbsent="false"
                  />
                </div>

                <!-- Select Date Range  -->
                <div class="col-sm-1 p-1">
                  <input type="date" class="form-control h-100" v-model="start_date"/>
                </div>

                <!-- Select Date Range  -->
                <div class="col-sm-1 p-1">
                  <input type="date" class="form-control h-100" v-model="end_date"/>
                </div>

                <!-- Get Data      -->
                <div class="col-sm-2 p-1">
                  <button
                    class="btn primary w-100 h-100"
                    @click="getStatsDetails()"
                  >Get Data</button>
                </div>
              </div>

              <div class="row">
                <!-- Order direction -->
                <div v-if="(stats_mode == 'standard_mode') || (stats_mode == 'by_property_mode')" class="col-sm-2 p-1">
                  <Multiselect
                    v-model="orderDir"
                    :options="[{value : 'desc', label : 'Desc'}, {value : 'asc', label : 'Asc'}]"
                    mode="single"
                    placeholder="Select Order Direction"
                    :close-on-select="true"
                    :searchable="true"
                    :create-option="false"
                    :canDeselect="false"
                    :canClear="false"
                    :allowAbsent="false"
                  />
                </div>

                <!-- Order rule -->
                <div v-if="(stats_mode == 'standard_mode') || (stats_mode == 'by_property_mode')" class="col-sm-2 p-1">
                  <Multiselect
                    v-if="stats_mode === 'standard_mode'"
                    v-model="orderRule"
                    :options="[{value : 'standard_value', label : 'Order by Value (label)'}, {value : 'standard_count', label : 'Order by Count'}]"
                    mode="single"
                    placeholder="Select Order Rule"
                    :close-on-select="true"
                    :searchable="true"
                    :create-option="false"
                    :canDeselect="false"
                    :canClear="false"
                    :allowAbsent="false"
                  />

                  <Multiselect
                    v-if="stats_mode === 'by_property_mode'"
                    v-model="orderRule"
                    :options="[{value : 'rows_value', label : 'Rows: Order by Value (label)'}, {value : 'cols_value', label : 'Columns: Order by Value (label)'}
                              , {value : 'rows_total', label : 'Rows: Order by Row Total'}, {value : 'cols_total', label : 'Columns: Order by Column Total'}]"
                    mode="single"
                    placeholder="Select Order Rule"
                    :close-on-select="true"
                    :searchable="true"
                    :create-option="false"
                    :canDeselect="false"
                    :canClear="false"
                    :allowAbsent="false"
                  />
                </div>

                <!-- Percentage base selector (only for by_property_mode) -->
                <div v-if="stats_mode === 'by_property_mode'" class="col-sm-2 p-1">
                  <Multiselect
                    v-model="percentageBaseOption"
                    :options="[{value : 'both', label : 'Total by both properties'}, {value : 'primary', label : 'Total by primary property'}, {value : 'secondary', label : 'Total by secondary property'}]"
                    mode="single"
                    placeholder="Select Percentage Base Option"
                    :close-on-select="true"
                    :searchable="true"
                    :create-option="false"
                    :canDeselect="false"
                    :canClear="false"
                    :allowAbsent="false"
                  />
                </div>
              </div>

              <hr class="mt-5 mb-5"/>

              <div id="stats_details_conditions">
                <div class="row mt-3">
                    <!-- Select Mode  -->
                    <div class="col-sm-2 p-1">
                        <button class="btn btn-primary w-100 h-100" @click="addCondition">+ Add condition</button>
                    </div>

                    <!-- Conditions Logic  -->
                    <div class="col-sm-2 p-1">
                        <Multiselect
                            v-model="conditionsLogic"
                            :options="['AND', 'OR']"
                            mode="single"
                            :close-on-select="true"
                            :searchable="true"
                            :create-option="false"
                            :canDeselect="false"
                            :canClear="false"
                            :allowAbsent="false"
                        />
                    </div>
                </div>

                <!--  -->

                <div class="row mt-3 p-1">
                    <div v-if="conditions.length" class="p-0">
                        <div class="condition-row row g-2 mb-2" v-for="cond in conditions" :key="cond.id">
                            <!-- 0) type -->
                            <div class="col-sm-2">
                                <select class="form-select h-100" v-model="cond.type" @change="onCondTypeChange(cond)"  disabled>
                                    <option value="string">String</option>
                                    <option value="number">Number</option>
                                    <!-- ADDED: date type option -->
                                    <option value="date">Date</option>
                                </select>
                            </div>

                            <!-- 1) property select (target column) -->
                            <div class="col-sm-2">
                                <Multiselect
                                    v-model="cond.property"
                                    :options="properties"
                                    mode="single"
                                    placeholder="Select Condition Property"
                                    :close-on-select="true"
                                    :searchable="true"
                                    :create-option="false"
                                    :canDeselect="false"
                                    :canClear="false"
                                    :allowAbsent="false"
  
                                    @input="onConditionPropertyChange(cond)"
                                />
                            </div>

                            <!-- 2) operator -->
                            <div class="col-sm-2">
                                <select class="form-select h-100" v-model="cond.operator">
                                    <option v-for="op in operatorOptionsFor(cond.type)" :key="op.value" :value="op.value">{{ op.label }}</option>
                                </select>
                            </div>

                            <!-- 3) value input(s) -->
                            <div class="col-sm-4">
                                <!-- If operator needs no value -->
                                <div v-if="operatorNeedsNoValue(cond.operator)" class="form-control-plaintext h-100">
                                    <small class="text-muted h-100">No value required</small>
                                </div>

                                <!-- If operator is range (between) -->
                                <div v-else-if="operatorIsRange(cond.operator) && cond.type !== 'date'" class="h-100 d-flex gap-1">
                                    <input type="text" class="form-control h-100" v-model="cond.inputValueLow" placeholder="low"/>
                                    <input type="text" class="form-control h-100" v-model="cond.inputValueHigh" placeholder="high"/>
                                </div>

                                <!-- DATE: between => two date inputs -->
                                <div v-else-if="cond.type === 'date' && cond.operator === 'between'" class="h-100 d-flex gap-1">
                                    <input type="date" class="form-control h-100" v-model="cond.inputValueLow" />
                                    <input type="date" class="form-control h-100" v-model="cond.inputValueHigh" />
                                </div>

                                <!-- DATE: single date -->
                                <div v-else-if="cond.type === 'date'">
                                  <input type="date" class="form-control h-100" v-model="cond.inputValue" />
                                </div>

                                <!-- If operator expects multiple values (in, not_in, contains_any, contains_all) -->
                                <div class="h-100" v-else-if="operatorIsMulti(cond.operator)">
                                    <Multiselect
                                        v-if="valueOptionsFor(cond.property).length"
                                        v-model="cond.selectedValues"
                                        :options="valueOptionsFor(cond.property)"
                                        mode="tags"
                                        placeholder="Select or type values (tags)"
                                    />
                                    <input v-else type="text" class="form-control h-100" v-model="cond.inputValue" placeholder="comma separated values"/>
                                </div>

                                <!-- Otherwise single value: either a select (if options exist) or text input -->
                                <div class="h-100" v-else>
                                  <input type="text" class="form-control h-100" v-model="cond.inputValue" placeholder="Type value to compare..."/>
                                </div>
                            </div>

                            <!-- 4) remove -->
                            <div class="col-sm-2">
                                <button class="btn btn-danger h-100 w-100" @click="removeCondition(cond.id)">Remove</button>
                            </div>
                        </div>
                    </div>
                </div>
              </div>
            </div> <!-- end filters area -->
          </div>
        </div>
      </div>

      <!-- report & child -->
      <div v-if="stats_details" class="col-sm-12 grid-margin">
        <ByDynamicProperty
          ref="ByDynamicProperty"
          :key="stats_details"
          :stats_details="stats_details"
          :stats_mode="stats_mode"
          :primary_property="primary_property"
          :secondary_property="secondary_property"
          :percentageBaseOption="percentageBaseOption"
        />
      </div>
    </div>
  </div>
</template>

<script>

import Multiselect          from  '@vueform/multiselect'
import ByDynamicProperty    from  './parts/ByDynamicProperty.vue'

export default {
  name: 'StatsDetails',
  components: { Multiselect, ByDynamicProperty },

  data() {
    return {
      stats_details: null,

      // filters
      route_link: null,
      start_date: "",
      end_date: "",
      primary_property: null,
      secondary_property: null,
      stats_mode: null,

      // total
      percentageBaseOption: 'both',

      // conditions builder
      conditions: [],
      conditionsLogic: "AND", // global operator between conditions

      // operator sets (friendly labels)
      stringOperators: [
        { value: 'equals'             , label: 'Equals'                             },
        { value: 'not_equals'         , label: 'Not equals'                         },
        { value: 'contains'           , label: 'Contains'                           },
        { value: 'not_contains'       , label: 'Not contains'                       },
        { value: 'starts_with'        , label: 'Starts with'                        },
        { value: 'ends_with'          , label: 'Ends with'                          },
        { value: 'in'                 , label: 'In (comma separated)'               },
        { value: 'not_in'             , label: 'Not in (comma separated)'           },
        { value: 'contains_any'       , label: 'Contains any (multi)'               },
        { value: 'contains_all'       , label: 'Contains all (multi)'               },
        { value: 'is_null'            , label: 'Is null'                            },
        { value: 'is_not_null'        , label: 'Is not null'                        },
        { value: 'is_empty'           , label: 'Is empty (null or empty string)'    },
        { value: 'is_empty_string'    , label: 'Is empty string ("")'               },

        { value: 'is_not_empty'       , label: 'Is not empty (not null and not "")' },
        { value: 'is_not_empty_string', label: 'Is not empty string (not "")'       },

        { value: 'is_true'            , label: 'Is true'                            },
        { value: 'is_false'           , label: 'Is false'                           }
      ],

      numberOperators: [
        { value: 'equals'             , label: 'Equals'                             },
        { value: 'not_equals'         , label: 'Not equals'                         },
        { value: 'greater_than'       , label: 'Greater than'                       },
        { value: 'less_than'          , label: 'Less than'                          },
        { value: 'gte'                , label: 'Greater or equal'                   },
        { value: 'lte'                , label: 'Less or equal'                      },
        { value: 'between'            , label: 'Between (low,high)'                 },
        { value: 'in'                 , label: 'In (comma separated)'               },
        { value: 'not_in'             , label: 'Not in (comma separated)'           },
        { value: 'is_null'            , label: 'Is null'                            },
        { value: 'is_not_null'        , label: 'Is not null'                        },
        { value: 'is_empty'           , label: 'Is empty (null or empty string)'    },
        { value: 'is_empty_string'    , label: 'Is empty string ("")'               },

        { value: 'is_not_empty'       , label: 'Is not empty (not null and not "")' },
        { value: 'is_not_empty_string', label: 'Is not empty string (not "")'       },
      ],

      // ADDED: date operators
      dateOperators: [
        { value: 'equals'             , label: 'Equals (date)'                      },
        { value: 'not_equals'         , label: 'Not equals (date)'                  },
        { value: 'greater_than'       , label: 'After (>)'                          },
        { value: 'less_than'          , label: 'Before (<)'                         },
        { value: 'gte'                , label: 'On or after (>=)'                   },
        { value: 'lte'                , label: 'On or before (<=)'                  },
        { value: 'between'            , label: 'Between (start,end)'                },
        { value: 'is_null'            , label: 'Is null'                            },
        { value: 'is_not_null'        , label: 'Is not null'                        },
        { value: 'is_empty'           , label: 'Is empty (null or empty string)'    },
        { value: 'is_empty_string'    , label: 'Is empty string ("")'               },
        { value: 'is_not_empty'       , label: 'Is not empty (not null and not "")' },
        { value: 'is_not_empty_string', label: 'Is not empty string (not "")'       },
        { value: 'after'              , label: 'After (alias -> greater_than)'      },
        { value: 'before'             , label: 'Before (alias -> less_than)'        }
      ],

      // combo data
      liste_route_import_all: [],
      liste_route_link: [],

      // properties for multiselect (array of {value,label})
      properties: [],

      // helper UI
      modes: [
        { value : 'standard_mode'                           , label: 'Standard Mode'                          },
        { value : 'by_property_mode'                        , label: 'By Property Mode'                       },
      ],

      //
      orderDir: 'desc',        // 'asc' or 'desc'
      orderRule: 'standard_count' // default selected rule (change if you prefer another default)
    }
  },

  async mounted() {
    await this.getComboData()
    this.prepareOptions()
  },

  methods: {

    // ------------------------------
    // Stats fetch + clients retrieval
    // ------------------------------
    async getStatsDetails() {
      try {
        this.$showLoadingPage();

        // validate + sanitize conditions first
        const validatedObj = this.validateAndSanitizeConditions();
        if (!validatedObj.valid) {
          this.$hideLoadingPage();
          this.$showErrors("Error !", validatedObj.errors);
          return;
        }

        let formData = new FormData();
        formData.append("route_link", this.route_link);
        formData.append("start_date", this.start_date);
        formData.append("end_date", this.end_date);
        formData.append("stats_mode", this.stats_mode);
        formData.append('order_dir', this.orderDir);
        formData.append('order_rule', this.orderRule);

        if (this.primary_property)      formData.append("primary_property", this.primary_property.value ?? this.primary_property);
        if (this.secondary_property)    formData.append("secondary_property", this.secondary_property.value ?? this.secondary_property);
        if (this.percentageBaseOption)  formData.append('percentage_base', this.percentageBaseOption);

        // include global logical operator
        formData.append("conditions_logic", this.conditionsLogic);

        // attach conditions payload
        const condPayload = validatedObj.sanitized;

        if (condPayload.length) formData.append("conditions", JSON.stringify(condPayload));

        // request backend to also return matching clients (no pagination)
        const res = await this.$callApi("post", "/statistics/self-service", formData);
        console.log(res)

        // expecting: { stats_details: {...}, clients: [...] }
        if (res.status == 200) {
          this.stats_details = res.data.stats_details ?? null;

          this.$hideLoadingPage();
          this.$feedbackSuccess(res.data?.header ?? "Success", res.data?.message ?? "Statistics loaded.");
        }

        else {

          this.$hideLoadingPage();
          this.$showErrors(res.data?.header ?? "Error !", res.data?.errors);
        }
      } catch (e) {
        console.error(e);
        this.$hideLoadingPage();
        this.$showErrors("Error !", ["Failed to load statistics", e.message || e]);
      }
    },

    // ------------------------------
    // Client-side validation & sanitize for conditions
    // ------------------------------
    validateAndSanitizeConditions() {
      const errors = [];
      const sanitized = [];

      const noValueOps = ['is_null','is_not_null','is_true','is_false','is_empty','is_empty_string','is_not_empty','is_not_empty_string'];
      const multiOps = ['in','not_in','contains_any','contains_all'];

      // date regex YYYY-MM-DD
      const dateRe = /^\d{4}-\d{2}-\d{2}$/;

      for (let i = 0; i < this.conditions.length; i++) {
        const c = this.conditions[i];

        // Normalize property and operator
        const target = c.property ? (c.property.value ?? c.property) : null;
        let op = c.operator;
        
        // CRITICAL: Ensure type is passed. Default to string if missing.
        const type = c.type || 'string'; 

        // --- Basic Validation ---
        if (!target) {
          errors.push(`Condition #${i + 1}: A target property is required.`);
          continue;
        }
        if (!op) {
          errors.push(`Condition #${i + 1}: An operator is required.`);
          continue;
        }

        // Map convenience aliases for dates (Backend expects greater_than/less_than)
        if (type === 'date') {
          if (op === 'after') op = 'greater_than';
          if (op === 'before') op = 'less_than';
        }

        // Initialize the entry object with the explicit TYPE
        const entry = { target, operator: op, type: type };

        // --- Logic Chain ---
        
        // 1. Operators that require NO value
        if (noValueOps.includes(op)) {
          sanitized.push(entry);
        } 
        
        // 2. 'between' operator (range)
        else if (op === 'between') {
          const lowRaw = (c.inputValueLow ?? '').toString().trim();
          const highRaw = (c.inputValueHigh ?? '').toString().trim();

          if (lowRaw === '' || highRaw === '') {
            errors.push(`Condition #${i + 1}: Both start and end values are required for 'between'.`);
          } else {
            if (type === 'date') {
              if (!dateRe.test(lowRaw) || !dateRe.test(highRaw)) {
                errors.push(`Condition #${i + 1}: Dates must be in YYYY-MM-DD format.`);
              } else {
                entry.value = [lowRaw, highRaw];
                sanitized.push(entry);
              }
            } else if (type === 'number') {
              if (isNaN(Number(lowRaw)) || isNaN(Number(highRaw))) {
                errors.push(`Condition #${i + 1}: Values must be numeric.`);
              } else {
                entry.value = [Number(lowRaw), Number(highRaw)];
                sanitized.push(entry);
              }
            } else {
              // String between (rare but possible)
              entry.value = [lowRaw, highRaw];
              sanitized.push(entry);
            }
          }
        } 
        
        // 3. Operators that require MULTIPLE values
        else if (multiOps.includes(op)) {
          let values = Array.isArray(c.selectedValues) ? c.selectedValues : [];
          // fallback to comma separated string if select is empty
          if (values.length === 0 && (c.inputValue || '').toString().trim()) {
            values = c.inputValue.split(',').map(x => x.trim()).filter(Boolean);
          }

          if (values.length === 0) {
            errors.push(`Condition #${i + 1}: Operator '${op}' requires at least one value.`);
          } else {
            if (type === 'date') {
              const bad = values.find(v => !dateRe.test(v));
              if (bad) errors.push(`Condition #${i + 1}: All dates must be YYYY-MM-DD.`);
              else {
                entry.value = values;
                sanitized.push(entry);
              }
            } else if (type === 'number') {
               // convert to numbers
               const nums = values.map(Number);
               if (nums.some(isNaN)) errors.push(`Condition #${i + 1}: All values must be numeric.`);
               else {
                 entry.value = nums;
                 sanitized.push(entry);
               }
            } else {
              entry.value = values;
              sanitized.push(entry);
            }
          }
        } 
        
        // 4. Single Value operators
        else {
          const rawValue = (c.inputValue ?? '').toString().trim();

          if (type === 'date') {
            if (rawValue === '') errors.push(`Condition #${i + 1}: Date required.`);
            else if (!dateRe.test(rawValue)) errors.push(`Condition #${i + 1}: Invalid date format.`);
            else {
              entry.value = rawValue;
              sanitized.push(entry);
            }
          } else if (type === 'number') {
            if (rawValue === '') errors.push(`Condition #${i + 1}: Number required.`);
            else if (isNaN(Number(rawValue))) errors.push(`Condition #${i + 1}: Invalid number.`);
            else {
              entry.value = Number(rawValue);
              sanitized.push(entry);
            }
          } else {
            // String
            if (rawValue === '') errors.push(`Condition #${i + 1}: Value required.`);
            else {
              entry.value = rawValue;
              sanitized.push(entry);
            }
          }
        }
      } // end for loop

      return { valid: errors.length === 0, errors, sanitized };
    },

    // ------------------------------
    // Conditions builder helpers
    // ------------------------------
    addCondition() {
      const id = Date.now().toString(36) + Math.floor(Math.random()*1000);
      this.conditions.push({
        id,
        property: null,
        operator: "equals",
        type: "string",            // default until property selected or user changes it
        inputValue: "",
        inputValueLow: "",
        inputValueHigh: "",
        selectedValues: [],
        _userSelectedType: false   // internal flag to avoid auto-overwrite
      });
    },

    removeCondition(id) {
      this.conditions = this.conditions.filter(c => c.id !== id);
    },

    operatorOptionsFor(type) {
      if (type === 'number') return this.numberOperators;
      if (type === 'date') return this.dateOperators;
      return this.stringOperators;
    },

    operatorNeedsNoValue(op) {
      return ['is_null','is_not_null','is_true','is_false','is_empty','is_empty_string','is_not_empty','is_not_empty_string'].includes(op);
    },

    operatorIsMulti(op) {
      return ['in','not_in','contains_any','contains_all'].includes(op);
    },

    operatorIsRange(op) {
      return ['between'].includes(op);
    },

    async onConditionPropertyChange(cond) {
      this.$nextTick(() => {
        // quick safety
        if (!cond || !cond.property) {
          return;
        }

        // Reset the explicit-user-type flag because property changed
        cond._userSelectedType = false;

        // property can be either a string or { value, label, type }
        const prop = cond.property;
        const propVal = (prop && (prop.value ?? prop)) ?? null;

        // Prefer explicit metadata on property (prop.type)
        let detectedType = null;
        if (prop && typeof prop === 'object' && prop.type) {
          detectedType = prop.type; // use provided type
        } else if (typeof propVal === 'string') {
          // fallback heuristics (kept for backwards compatibility)
          const isDate = /(_date|date$|_at$|updated_at|created_at)/i.test(propVal);
          const isNumber = /(Id$|No$|Count$|Total$|Price$)/i.test(propVal);

          if (isDate) detectedType = 'date';
          else if (isNumber) detectedType = 'number';
          else detectedType = 'string';
        } else {
          detectedType = 'string';
        }

        // Only set the condition type if user didn't explicitly pick a type
        if (!cond._userSelectedType) {
          // assignment should be reactive in Vue 3; use Object.assign to be extra-safe
          Object.assign(cond, { type: detectedType });
        } else {
        }

        // Reset operator according to the effective type
        cond.operator = (cond.type === 'number' || cond.type === 'date') ? 'equals' : 'contains';

        // Reset UI values
        cond.inputValue = "";
        cond.inputValueLow = "";
        cond.inputValueHigh = "";
        cond.selectedValues = [];

      });
    },

    onCondTypeChange(cond) {
      if (!cond) return;

      // Mark that the user explicitly chose a type so auto-detection won't overwrite it
      cond._userSelectedType = true;

      // Set sensible default operator for the chosen type
      if (cond.type === 'number') cond.operator = 'equals';
      else if (cond.type === 'date') cond.operator = 'equals';
      else cond.operator = 'contains';

      // Keep values cleared for the new type
      cond.inputValue = "";
      cond.inputValueLow = "";
      cond.inputValueHigh = "";
      cond.selectedValues = [];
    },

    // ------------------------------
    // Combos / options (routes & properties)
    // ------------------------------
    async getComboData() {
      try {
        const res = await this.$callApi("post", "/route_import/combo", null)
        this.liste_route_import_all = res.data || []
        this.liste_route_link = (this.liste_route_import_all || []).map(i => ({ value: i.id, label: i.libelle }))
      } catch (e) {
        console.error(e)
      }
    },

    prepareOptions() {
      this.properties = [];
      const push = (v, l, t = 'string') => this.properties.push({ value: v, label: l, type: t });

      push("DistrictNo"          , "Wilaya / رقم الولاية"               , "number");
      push("DistrictNameE"       , "Wilaya / إسم الولاية"               , "string");
      push("CityNo"              , "Commune / رقم البلدية"             , "number");
      push("CityNameE"           , "Commune / إسم البلدية"             , "string");
      push("Address"             , "Adresse de magasin / عنوان المتجر" , "string");
      push("CustomerType"        , "Type de magasin / نوع المتجر"      , "string");
      push("CustomerNameA"       , "Raison Sociale / اسم نقطة لبيع"    , "string");
      push("CustomerNameE"       , "Nom ... / لقب واسم صاحب المحل"    , "string");
      push("Tel"                 , "Numero de téléphone / رقم الهاتف"  , "string");
      push("CustomerCode"        , "Capturer Code-Barre"                , "string");
      push("BrandSourcePurchase" , "Source d'achat ... "                , "string");
      push("status"              , "Status PDV"                         , "string");
      push("created_at"          , "Date de creation"                   , "date");
      push("tel_status"          , "Status de Telephone"                , "string");
    }
  }
}
</script>
