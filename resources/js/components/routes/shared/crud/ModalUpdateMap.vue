<template>
    <div class="modal fade" id="ModalUpdateMap" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-l modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Routing</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body mt-3">
                    <div class="row justify-content-center">
                        <div class="col-sm-11">
                            <label class="form-label">File</label>
                            <input type="file" class="form-control" @change="handleFileUpload($event)"
                                   accept=".csv,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" :disabled="!rdy_send" @click="sendData()">Confirm</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { processExcelFile, normalizeClientsData, validateHeaders } from "@/services/ExcelService";

export default {
    props: ["id_route_import"],
    data() {
        return {
            rdy_send: false,
            clients: [],
            raw_file: null
        };
    },
    methods: {
        async handleFileUpload(event) {
            const file = event.target.files[0];
            if (!file) return;

            this.rdy_send = false;
            await this.$showLoadingPage();

            try {
                let rawData = await processExcelFile(file);
                
                // IMPORTANT: Now we actually validate and normalize in the update modal too!
                const warnings = validateHeaders(rawData[0]);
                if (warnings.length > 0) {
                     this.$showWarnings("Warning", warnings);
                }

                this.clients = normalizeClientsData(rawData);
                this.raw_file = file;
                this.rdy_send = true;

            } catch (e) {
                this.$showErrors("Error", ["File processing failed"]);
            } finally {
                await this.$hideLoadingPage();
            }
        },

        async sendData() {
            await this.$showLoadingPage();
            
            let formData = new FormData();
            formData.append("data", JSON.stringify(this.clients));
            if(this.raw_file) formData.append("file", this.raw_file);

            const res = await this.$callApi('post', `/route-imports/${this.id_route_import}/update`, formData);

            await this.$hideLoadingPage();

            if (res.status === 200) {
                this.$feedbackSuccess(res.data["header"], res.data["message"]);
                this.emitter.emit('reSetClientsUpdateMap', res.data.clients);
                await this.$hideModal("ModalUpdateMap");
            } else {
                this.$showErrors("Error !", res.data.errors);
            }
        }
    }
}
</script>