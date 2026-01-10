<template>

    <!-- Modal -->
    <div class="modal fade" id="addJourneyPlanModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Add a New Territoire</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body mt-3">

                    <form>

                        <div class="mb-3">
                            <label for="JPlan"              class="form-label">Territory Type</label>
                            <select class="form-select"     v-model="territoire.type_territoire">
                                <option value='1'>Journey Plan</option>
                                <option value='2'>Journee</option>
                                <option value='3'>User</option>
                            </select>
                        </div>

                        <!--  -->

                        <div v-if="((territoire.type_territoire ==  '1')||(territoire.type_territoire ==  '2'))">
                            <div class="mb-3">
                                <label for="JPlan"              class="form-label">JPlan</label>
                                <input type="text"              class="form-control"        id="JPlan"                  v-model="territoire.JPlan">
                            </div>

                            <div class="mb-3" v-if="territoire.type_territoire ==  '2'">
                                <label for="Journee"            class="form-label">Journee</label>
                                <input type="text"              class="form-control"        id="Journee"                v-model="territoire.Journee">
                            </div>
                        </div>

                        <!--  -->

                        <div v-if="(territoire.type_territoire ==  '3')">
                            <div class="mb-3">
                                <label for="Description"        class="form-label">Description</label>
                                <input type="text"              class="form-control"        id="Description"        v-model="territoire.description">
                            </div>

                            <div class="mb-3">
                                <label for="type_user"          class="form-label">User</label>
                                <select                         class="form-select"         id="type_user"          v-model="territoire.id_user">
                                    <option v-for="user, index in users" :key="index"   :value="user.id">{{ user.username }}</option>
                                </select>
                            </div>
                        </div>

                        <!--  -->

                        <div class="mb-3">
                            <input type="color"             class="form-control form-control-color w-100"           v-model="territoire.color">
                        </div>

                    </form>

                </div>

                <!--  -->

                <div class="modal-footer"       style="display: flex; justify-content: space-between;">
                    <div class="right-buttons"  style="display: flex; margin-left: auto;">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button"   class="btn btn-primary"   @click="sendData()">Confirm</button>
                    </div>
                </div>

            </div>
        </div>
    </div>

</template>

<script>

import {mapGetters, mapActions} from    "vuex"

export default {

    data() {
        return {

            territoire      :   {

                type_territoire     :   '1'         ,

                JPlan               :   ''          ,
                Journee             :   ''          ,

                description         :   ''          ,
                id_user             :   ''          ,

                color               :   '#000000'   ,

                latlngs             :   null
            },

            users                       :   []
        }
    },

    async mounted() {

        this.clearData("#addJourneyPlanModal")

        await this.getComboData()
    },  

    methods : {

        ...mapActions("journey_plan" ,  [
            "setAddJourneyPlanAction"   ,
        ]),

        //

        async sendData() {

            await this.$showLoadingPage()

            let formData = new FormData();

            formData.append("type_territoire"   ,   this.territoire.type_territoire)
            formData.append("latlngs"           ,   JSON.stringify(this.territoire.latlngs))

            formData.append("JPlan"             ,   this.territoire.JPlan)
            formData.append("Journee"           ,   this.territoire.Journee)

            formData.append("description"       ,   this.territoire.description)
            formData.append("id_user"           ,   this.territoire.id_user)

            formData.append("color"             ,   this.territoire.color)

            if(this.territoire.type_territoire    ==  '1') {

                const res                       =   await this.$callApi("post"  ,   "/route-imports/"+this.$route.params.id_route_import+"/journey-plan-territories/store"   ,   formData)

                if(res.status===200){

                    // Hide Loading Page
                    await this.$hideLoadingPage()

                    // Send Client
                    this.emitter.emit('reSetJPlanBDTerritory')

                    // Close Modal
                    await this.$hideModal("addJourneyPlanModal")
                }
                
                else{

                    // Send Errors
                    this.$showErrors("Error !", res.data.errors)

                    // Hide Loading Page
                    await this.$hideLoadingPage()
                }            
            }

            if(this.territoire.type_territoire    ==  '2') {

                const res                   =   await this.$callApi("post"  ,   "/route-imports/"+this.$route.params.id_route_import+"/journee-territories/store"       ,   formData)

                if(res.status===200){

                    // Hide Loading Page
                    await this.$hideLoadingPage()

                    // Send Client
                    this.emitter.emit('reSetJourneeBDTerritory')

                    // Close Modal
                    await this.$hideModal("addJourneyPlanModal")
                }
                
                else{

                    // Send Errors
                    this.$showErrors("Error !", res.data.errors)

                    // Hide Loading Page
                    await this.$hideLoadingPage()
                }            
            }

            if(this.territoire.type_territoire    ==  '3') {

                const res                       =   await this.$callApi("post"  ,   "/route-imports/"+this.$route.params.id_route_import+"/user-territories/store"   ,   formData)

                if(res.status===200){

                    // Hide Loading Page
                    await this.$hideLoadingPage()

                    // Send Client
                    this.emitter.emit('reSetUserBDTerritory')

                    // Close Modal
                    await this.$hideModal("addJourneyPlanModal")
                }
                
                else{

                    // Send Errors
                    this.$showErrors("Error !", res.data.errors)

                    // Hide Loading Page
                    await this.$hideLoadingPage()
                }            
            }
        },

        //

        clearData(id_modal) {

            $(id_modal).on("hidden.bs.modal",   ()  => {

                // 
                this.setAddJourneyPlanAction(null)

                // Client
                this.territoire.type_territoire     =   '1',

                this.territoire.JPlan               =   '',
                this.territoire.Journee             =   '',

                this.territoire.description         =   '',
                this.territoire.id_user             =   '',

                this.territoire.color               =   '#000000',

                // Remove Drawings
                this.removeDrawings()
            });
        },

        removeDrawings() {

            // Remove Drawings
            this.$map.$removeDrawings()
        },

        //

        getData(latlngs) {

            this.territoire.latlngs =   latlngs
        },

        //

        async getComboData() {

            const res       =   await this.$callApi("post",     "/route-imports/"+this.$route.params.id_route_import+"/users/frontOffice",     null)
            this.users      =   res.data.users
        },
    },
};
</script>