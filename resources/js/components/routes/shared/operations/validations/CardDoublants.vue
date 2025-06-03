<template>
    <div class="row h-equal p-0 pl-2 m-0 mb-5">
        <div class="col p-1 h-100">
            <div class="card h-100 bg-gradient-primary card-img-holder text-white p-3">
                <div class="card-body p-1">
                    <div class="row">
                        <div class="col-sm-10">
                            <h4 class="font-weight-normal mb-3" role="button" @click="showValidationClients('CustomerCode', getDoublant.getDoublantCustomerCode)">CustomerCode Doubles</h4>
                        </div>

                        <div class="col-sm-2">
                            <i class="mdi mdi-refresh mdi-24px float-right"    role="button"   @click="getDoubleCustomerCode()"></i>
                        </div>
                    </div>
                    <h2 class="mb-1 animate__animated animate__pulse">{{ getDoublant.getDoublantCustomerCode.length }}</h2>
                </div>
            </div>
        </div>

        <div class="col p-1 h-100">
            <div class="card h-100 bg-gradient-primary card-img-holder text-white p-3">
                <div class="card-body p-1">
                    <div class="row">
                        <div class="col-sm-10">
                            <h4 class="font-weight-normal mb-3" role="button" @click="showValidationClients('CustomerNameE', getDoublant.getDoublantCustomerNameE)">CustomerNameE Doubles</h4>
                        </div>

                        <div class="col-sm-2">
                            <i class="mdi mdi-refresh mdi-24px float-right"    role="button"   @click="getDoubleCustomerNameE()"></i>
                        </div>
                    </div>
                    <h2 class="mb-1 animate__animated animate__pulse">{{ getDoublant.getDoublantCustomerNameE.length }}</h2>
                </div>
            </div>
        </div>

        <div class="col p-1 h-100">
            <div class="card h-100 bg-gradient-primary card-img-holder text-white p-3">
                <div class="card-body p-1">
                    <div class="row">
                        <div class="col-sm-10">
                            <h4 class="font-weight-normal mb-3" role="button" @click="showValidationClients('Tel', getDoublant.getDoublantTel)">Tel Doubles</h4>
                        </div>

                        <div class="col-sm-2">
                            <i class="mdi mdi-refresh mdi-24px float-right"    role="button"   @click="getDoubleTel()"></i>
                        </div>
                    </div>
                    <h2 class="mb-1 animate__animated animate__pulse">{{ getDoublant.getDoublantTel.length }}</h2>
                </div>
            </div>
        </div>

        <div class="col p-1 h-100">
            <div class="card h-100 bg-gradient-primary card-img-holder text-white p-3">
                <div class="card-body p-1">
                    <div class="row">
                        <div class="col-sm-10">
                            <h4 class="font-weight-normal mb-3" role="button" @click="showValidationClients('GPS', getDoublant.getDoublantLatitudeLongitude)">GPS Doubles</h4>
                        </div>

                        <div class="col-sm-2">
                            <i class="mdi mdi-refresh mdi-24px float-right"    role="button"   @click="getDoubleGPS()"></i>
                        </div>
                    </div>
                    <h2 class="mb-1 animate__animated animate__pulse">{{ getDoublant.getDoublantLatitudeLongitude.length }}</h2>
                </div>
            </div>
        </div>
    </div>

    <hr />

    <div v-if="validation_type_chosen"     class="row h-equal changing_height_animation p-0 m-0" id="show_validation_clients_parent" style="height: 0px" >
        <ShowValidationClients      ref="ShowValidationClients"></ShowValidationClients>
    </div>

    <!--  -->

    <ModalClientUpdate      v-if="id_route_import_tempo"    ref="ModalClientUpdate"     :id_route_import_tempo="id_route_import_tempo"      :mode="'temporary'"     :update_type="'validation'"     :validation_type="validation_type">   </ModalClientUpdate>

</template>

<script>

import ShowValidationClients    from    "./ShowValidationClients.vue"

import ModalClientUpdate        from    "@/components/clients/shared/ModalClientUpdate.vue"

export default {

    data() {
        return {

            validation_type_chosen                         :   null    ,
            show_validation_clients_child_clicked   :   false   ,

            //

            validation_type                            :   ""
        }
    },

    props       :   ["getDoublant", "total_clients", "mode", "id_route_import_tempo", "id_route_import"],

    components  :   {
        ShowValidationClients   :   ShowValidationClients   ,
        ModalClientUpdate       :   ModalClientUpdate       
    },

    methods     :   {

        async showValidationClients(validation_type_chosen, validation_clients) {

            if(!this.show_validation_clients_child_clicked) {

                //
                this.validation_type                           =   validation_type_chosen
                console.log(this.validation_type)

                //
                this.show_validation_clients_child_clicked  =   true

                //
                if(this.validation_type_chosen !=  validation_type_chosen) {

                    this.validation_type_chosen                        =   validation_type_chosen

                    //
                    setTimeout(async () => {
                        const show_validation_clients_parent_div            =   document.getElementById("show_validation_clients_parent")
                        show_validation_clients_parent_div.style.height     =   "750px"
                        // show_validation_clients_parent_div.style.padding   =   "15px"
                        show_validation_clients_parent_div.classList.add("mt-5")

                        //
                        await this.$refs.ShowValidationClients.setResumeValidateMap(validation_type_chosen, validation_clients, this.total_clients)

                        //
                        this.show_validation_clients_child_clicked  =   false
                    }, 0);
                }

                else {

                    //
                    const show_validation_clients_parent_div           =   document.getElementById("show_validation_clients_parent")
                    show_validation_clients_parent_div.style.height    =   "0px"

                    //
                    setTimeout(async () => {                    
                        this.validation_type_chosen                                =   null

                        //
                        this.show_validation_clients_child_clicked          =   false

                        //
                        show_validation_clients_parent_div.classList.remove("mt-5")

                    }, 255);
                }
            }
        },

        //

        async getDoubleCustomerCode() {

            this.$showLoadingPage()

            if(this.mode    ==  "temporary") {
                const res                                   =   await this.$callApi("post"  ,   "/route_import_tempo/"+this.id_route_import_tempo+"/clients_tempo/doubles/CustomerCode"     , null)
                this.getDoublant.getDoublantCustomerCode    =   res.data
            }

            if(this.mode    ==  "permanent") {
                const res                                   =   await this.$callApi("post"  ,   "/route_import/"+this.id_route_import+"/clients/doubles/CustomerCode"                       , null)
                this.getDoublant.getDoublantCustomerCode    =   res.data
            }

            //
            this.emitter.emit("refreshDoublantCustomerCode"     ,   this.getDoublant.getDoublantCustomerCode)

            //
            await this.$refs.ShowValidationClients.setResumeValidateMap("CustomerCode", this.getDoublant.getDoublantCustomerCode, this.total_clients)

            //
            this.$hideLoadingPage()
        },

        async getDoubleCustomerNameE() {

            this.$showLoadingPage()

            if(this.mode    ==  "temporary") {
                const res                                   =   await this.$callApi("post"  ,   "/route_import_tempo/"+this.id_route_import_tempo+"/clients_tempo/doubles/CustomerNameE"    , null)
                this.getDoublant.getDoublantCustomerNameE   =   res.data
            }

            if(this.mode    ==  "permanent") {
                const res                                   =   await this.$callApi("post"  ,   "/route_import/"+this.id_route_import+"/clients/doubles/CustomerNameE"                      , null)
                this.getDoublant.getDoublantCustomerNameE   =   res.data
            }

            //
            this.emitter.emit("refreshDoublantCustomerNameE"     ,  this.getDoublant.getDoublantCustomerNameE)

            //
            await this.$refs.ShowValidationClients.setResumeValidateMap("CustomerNameE", this.getDoublant.getDoublantCustomerNameE, this.total_clients)

            //
            this.$hideLoadingPage()
        },

        async getDoubleTel() {
  
            this.$showLoadingPage()

            if(this.mode    ==  "temporary") {
                const res                           =   await this.$callApi("post"  ,   "/route_import_tempo/"+this.id_route_import_tempo+"/clients_tempo/doubles/Tel"  , null)
                this.getDoublant.getDoublantTel     =   res.data
            }

            if(this.mode    ==  "permanent") {
                const res                           =   await this.$callApi("post"  ,   "/route_import/"+this.id_route_import+"/clients/doubles/Tel"                    , null)
                this.getDoublant.getDoublantTel     =   res.data
            }

            //
            this.emitter.emit("refreshDoublantTel"     ,    this.getDoublant.getDoublantTel)

            //
            await this.$refs.ShowValidationClients.setResumeValidateMap("Tel", this.getDoublant.getDoublantTel, this.total_clients)

            //
            this.$hideLoadingPage()
        },

        async getDoubleGPS() {
  
            this.$showLoadingPage()

            if(this.mode    ==  "temporary") {
                const res                                       =   await this.$callApi("post"  ,   "/route_import_tempo/"+this.id_route_import_tempo+"/clients_tempo/doubles/GPS"  , null)
                this.getDoublant.getDoublantLatitudeLongitude   =   res.data
            }

            if(this.mode    ==  "permanent") {
                const res                                       =   await this.$callApi("post"  ,   "/route_import/"+this.id_route_import+"/clients/doubles/GPS"                    , null)
                this.getDoublant.getDoublantLatitudeLongitude   =   res.data
            }

            //
            this.emitter.emit("refreshDoublantLatitudeLongitude"    ,   this.getDoublant.getDoublantLatitudeLongitude)

            //
            await this.$refs.ShowValidationClients.setResumeValidateMap("GPS", this.getDoublant.getDoublantLatitudeLongitude, this.total_clients)

            //
            this.$hideLoadingPage()
        }
    }
}

</script>