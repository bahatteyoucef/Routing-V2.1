<template>
    <div class="content-wrapper p-3">
        <div class="card">
            <div class="card-body">
                <h5 class="modal-title">Import a new Routing</h5>
                <hr />
                <form class="row mt-3 mb-3">
                    <div class="col-sm-3">
                        <label class="form-label">Label</label>
                        <input type="text" class="form-control" v-model="route_import.libelle">
                    </div>
                    <div class="col-sm-3">
                        <label class="form-label">File</label>
                        <input type="file" class="form-control" @change="handleFileUpload($event)"
                               accept=".csv,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                    </div>
                    <div class="col-sm-3">
                         <label class="form-label">Districts</label>
                         <Multiselect v-model="route_import.districts" :options="districts" mode="tags" 
                                      :searchable="true" placeholder="Select Districts"/>
                    </div>
                    <div class="col-sm-2 mt-auto">
                        <button type="button" class="btn btn-primary" :disabled="!rdy_send" @click="sendData()">Import</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import Multiselect from "@vueform/multiselect";
import { processExcelFile, normalizeClientsData, validateHeaders } from "@/services/ExcelService"; // Update path

export default {
    components: { Multiselect },
    data() {
        return {
            route_import: {
                libelle: "",
                file: null,
                districts: [],
                new_upload: true
            },
            rdy_send: false,
            clients: [],
            districts: [] // populated via API
        };
    },
    async mounted() {
        await this.getComboData();
    },
    methods: {
        async getComboData() {
            // ... (Keep your existing API call logic here)
             const res = await this.$callApi("post", "/rtm-willayas", null);
             this.districts = res.data.willayas.map(d => ({
                 value: d.DistrictNo,
                 label: `${d.DistrictNameE} (${d.DistrictNo})`
             }));
        },

        async handleFileUpload(event) {
            const file = event.target.files[0];
            if (!file) return;

            this.rdy_send = false;
            await this.$showLoadingPage();

            try {
                // 1. Parse File
                let rawData = await processExcelFile(file);

                if (rawData.length === 0) {
                    this.rdy_send = true;
                    this.$feedbackWarning("Warning", "File is empty");
                    return;
                }

                // 2. Validate Headers
                const warnings = validateHeaders(rawData[0]);
                if (warnings.length > 0) {
                    this.rdy_send = true;
                    this.$showWarnings("Structure Warning", warnings);
                    // Decide if you want to block upload or allow it with warnings. 
                    // Your original code allowed it.
                }

                // 3. Normalize Data (GPS & Defaults)
                this.clients = normalizeClientsData(rawData);

                // 4. Validate Content (Customer Codes)
                if (this.validateContent(this.clients)) {
                    this.rdy_send = true;
                    this.route_import.file = file; // Store file if needed for upload
                }

            } catch (error) {
                console.error(error);
                this.$showErrors("Error", ["Failed to process file"]);
            } finally {
                await this.$hideLoadingPage();
            }
        },

        validateContent(clients) {
            for (let client of clients) {
                if (!this.$isValidForFileName(client.CustomerCode)) {
                    this.$showErrors("Error", [`${client.CustomerNameE} CustomerCode contains forbidden chars`]);
                    return false;
                }
            }

            return true;
        },

        async sendData() {
            if (!this.rdy_send) return;

            await this.$showLoadingPage();
            
            let formData = new FormData();
            formData.append("libelle", this.route_import.libelle);
            formData.append("data", JSON.stringify(this.clients));

            if(this.route_import.districts.length   >=  1) formData.append("districts", JSON.stringify(this.route_import.districts));

            const res = await this.$callApi('post', '/route-imports-tempo/store', formData);

            await this.$hideLoadingPage();

            if (res.status === 200) {
                this.$feedbackSuccess(res.data["header"], res.data["message"]);
                this.$goTo('/route-imports-tempo/last-imported');
            } else {
                this.$showWarnings("Error !", res.data.errors);
            }
        }
    }
};
</script>