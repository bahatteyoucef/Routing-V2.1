<template>

    <div>

        <div
            class="row d-sm-flex justify-content-between align-items-start"
        >
            <!-- Header -->
            <div class="col-sm-10 mt-3 mb-3">
                <h4>Add Complaint</h4>
            </div>

            <div>
                <!-- Type -->
                <div class="mb-3">
                    <label for="type_complaint"     class="form-label">Complaint Type</label>
                    <select                         class="form-select"         id="type_complaint"                 v-model="complaint.type_complaint">
                        <option value="quality control problems" selected>Quality Control Problems</option>
                        <option value="late deliveries">Late Deliveries</option>
                        <option value="incorrect shipments">Incorrect Shipments</option>
                        <option value="inadequate packaging">Inadequate Packaging</option>
                        <option value="pricing disputes">Pricing Disputes</option>
                        <option value="poor communication">Poor Communication</option>
                    </select>
                </div>

                <!-- Description -->
                <div class="form-group mb-3">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" rows="3" v-model="complaint.description"></textarea>
                </div>

                <div        style="display: flex; justify-content: space-between;">
                    <div    class="right-buttons"  style="display: flex; margin-left: auto;">
                        <button type="button" class="btn btn-secondary mb-3 mt-3"   @click="$goBack()">Back</button>
                        <button type="button" class="btn btn-primary mb-3 mt-3"     @click="sendData()">Confirm</button>
                    </div>
                </div>

            </div>
        </div>

    </div>


</template>

<script>

export default {
    
    data() {

        return {

            complaint : {

                type_complaint  :   ""  ,
                description     :   ""
            }
        }
    },

    mounted() {

    },

    methods : {

        async sendData() {

            // Show Loading Page
            this.$showLoadingPage()

            let formData = new FormData();

            formData.append("type_complaint"        , this.complaint.type_complaint)
            formData.append("description"           , this.complaint.description)

            formData.append("id_client"             , this.$route.params.id_client)
            formData.append("id_route_import"       , this.$route.params.id_route_import)

            const res   =   await this.$callApi('post' ,   '/complaints/store'    ,   formData)         
            console.log(res)

            // Hide Loading Page
            this.$hideLoadingPage()

            if(res.status===200){
                
                // Send Feedback
                this.$feedbackSuccess(res.data["header"]     ,   res.data["message"])

                // Close Modal
                this.$goBack()
			}
            
            else{

                // Send Errors
                this.$showErrors("Error !", res.data.errors)
			}
        },
    }
}

</script>
