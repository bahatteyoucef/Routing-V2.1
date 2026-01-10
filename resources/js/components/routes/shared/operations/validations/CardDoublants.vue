<template>
    <div class="row h-equal p-0 pl-2 m-0 mb-5">
        <div class="col p-1 h-100">
            <div class="card h-100 bg-gradient-primary card-img-holder text-white p-3">
                <div class="card-body p-1">
                    <div class="row">
                        <div class="col-sm-10">
                            <h4 class="font-weight-normal mb-3" role="button" @click="showValidationClients('CustomerCode')">CustomerCode Doubles</h4>
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
                            <h4 class="font-weight-normal mb-3" role="button" @click="showValidationClients('CustomerNameE')">CustomerNameE Doubles</h4>
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
                            <h4 class="font-weight-normal mb-3" role="button" @click="showValidationClients('Tel')">Tel Doubles</h4>
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
                            <h4 class="font-weight-normal mb-3" role="button" @click="showValidationClients('GPS')">GPS Doubles</h4>
                        </div>

                        <div class="col-sm-2">
                            <i class="mdi mdi-refresh mdi-24px float-right"    role="button"   @click="getDoubleGPS()"></i>
                        </div>
                    </div>
                    <h2 class="mb-1 animate__animated animate__pulse">{{ getDoublant.getDoublantGPS.length }}</h2>
                </div>
            </div>
        </div>
    </div>

    <hr />

    <div class="row h-equal changing_height_animation p-0 m-0" id="validation_clients_parent" style="height: 0px" >
        <ShowValidationClients      ref="ShowValidationClients"     :validation_type="validation_type"    :validation_clients="validation_clients"    :total_clients="total_clients"></ShowValidationClients>
    </div>

</template>

<script>

import ShowValidationClients    from    "./ShowValidationClients.vue"

export default {

    data() {
        return {

            validation_clients_child_clicked            :   false   ,

            //

            validation_type                             :   ""      ,
            validation_clients                          :   []
        }
    },

    props       :   ["getDoublant", "total_clients", "mode", "id_route_import_tempo", "id_route_import"],

    components  :   {
        ShowValidationClients   :   ShowValidationClients
    },

    mounted() {

        this.emitter.on("updateDoublesCustomerCode"         , async (client)    =>  {
            await this.updateClientJSON(client)
            this.emitter.emit("reSetUpdate", client)
        })

        this.emitter.on("updateDoublesCustomerNameE"        , async (client)    =>  {
            await this.updateClientJSON(client)
            this.emitter.emit("reSetUpdate", client)
        })

        this.emitter.on("updateDoublesTel"                  , async (client)    =>  {
            await this.updateClientJSON(client)
            this.emitter.emit("reSetUpdate", client)
        })

        this.emitter.on("updateDoublesGPS"                  , async (client)    =>  {
            await this.updateClientJSON(client)
            this.emitter.emit("reSetUpdate", client)
        })

        //

        this.emitter.on("deleteDoublesCustomerCode"         , async (client)    =>  {
            await this.deleteClientJSON(client)
            this.emitter.emit("reSetDelete", client)
        })

        this.emitter.on("deleteDoublesCustomerNameE"        , async (client)    =>  {
            await this.deleteClientJSON(client)
            this.emitter.emit("reSetDelete", client)
        })

        this.emitter.on("deleteDoublesTel"                  , async (client)    =>  {
            await this.deleteClientJSON(client)
            this.emitter.emit("reSetDelete", client)
        })

        this.emitter.on("deleteDoublesGPS"                  , async (client)    =>  {
            await this.deleteClientJSON(client)
            this.emitter.emit("reSetDelete", client)
        })
    },

    methods     :   {

        async showValidationClients(validation_type) {

            if(!this.validation_clients_child_clicked) {

                //
                this.validation_clients_child_clicked   =   true

                //
                if(this.validation_type  !=  validation_type) {

                    this.validation_type    =   validation_type

                    if(validation_type   ==  "CustomerCode")     this.validation_clients         =   this.getDoublant.getDoublantCustomerCode
                    if(validation_type   ==  "CustomerNameE")    this.validation_clients         =   this.getDoublant.getDoublantCustomerNameE
                    if(validation_type   ==  "Tel")              this.validation_clients         =   this.getDoublant.getDoublantTel
                    if(validation_type   ==  "GPS")              this.validation_clients         =   this.getDoublant.getDoublantGPS

                    //
                    setTimeout(async () => {

                        const validation_clients_parent_div             =   document.getElementById("validation_clients_parent")
                        validation_clients_parent_div.style.height      =   "750px"
                        validation_clients_parent_div.classList.add("mt-5")

                        //
                        await this.$refs.ShowValidationClients.setDatatable()

                        //
                        this.validation_clients_child_clicked  =   false
                    }, 0);
                }

                else {

                    //
                    const validation_clients_parent_div             =   document.getElementById("validation_clients_parent")
                    validation_clients_parent_div.style.height      =   "0px"

                    //
                    setTimeout(async () => {                    
                        this.validation_type                            =   null

                        //
                        this.validation_clients_child_clicked           =   false

                        //
                        validation_clients_parent_div.classList.remove("mt-5")

                    }, 255);
                }
            }
        },

        //

        async getDoubleCustomerCode() {

            await this.$showLoadingPage()

            if(this.mode    ==  "temporary") {
                const res                                   =   await this.$callApi("post"  ,   "/route-imports-tempo/"+this.id_route_import_tempo+"/clients-tempo/doubles/CustomerCode"     , null)
                this.getDoublant.getDoublantCustomerCode    =   res.data.doubles_customer_code
            }

            if(this.mode    ==  "permanent") {
                const res                                   =   await this.$callApi("post"  ,   "/route-imports/"+this.id_route_import+"/clients/doubles/CustomerCode"                       , null)
                this.getDoublant.getDoublantCustomerCode    =   res.data.doubles_customer_code
            }

            //
            this.emitter.emit("refreshDoublantCustomerCode"     ,   this.getDoublant.getDoublantCustomerCode)

            //
            await this.$nextTick()

            //
            this.validation_type        =   "CustomerCode"
            this.validation_clients     =   this.getDoublant.getDoublantCustomerCode

            //
            await this.$nextTick()

            //
            await this.$refs.ShowValidationClients.setDatatable()

            //
            await this.$hideLoadingPage()
        },

        async getDoubleCustomerNameE() {

            await this.$showLoadingPage()

            if(this.mode    ==  "temporary") {
                const res                                   =   await this.$callApi("post"  ,   "/route-imports-tempo/"+this.id_route_import_tempo+"/clients-tempo/doubles/CustomerNameE"    , null)
                this.getDoublant.getDoublantCustomerNameE   =   res.data.doubles_customer_namee
            }

            if(this.mode    ==  "permanent") {
                const res                                   =   await this.$callApi("post"  ,   "/route-imports/"+this.id_route_import+"/clients/doubles/CustomerNameE"                      , null)
                this.getDoublant.getDoublantCustomerNameE   =   res.data.doubles_customer_namee
            }

            //
            this.emitter.emit("refreshDoublantCustomerNameE"     ,  this.getDoublant.getDoublantCustomerNameE)

            //
            await this.$nextTick()

            //
            this.validation_type        =   "CustomerNameE"
            this.validation_clients     =   this.getDoublant.getDoublantCustomerNameE

            //
            await this.$nextTick()

            //
            await this.$refs.ShowValidationClients.setDatatable()

            //
            await this.$hideLoadingPage()
        },

        async getDoubleTel() {
  
            await this.$showLoadingPage()

            if(this.mode    ==  "temporary") {
                const res                           =   await this.$callApi("post"  ,   "/route-imports-tempo/"+this.id_route_import_tempo+"/clients-tempo/doubles/Tel"  , null)
                this.getDoublant.getDoublantTel     =   res.data.doubles_tel
            }

            if(this.mode    ==  "permanent") {
                const res                           =   await this.$callApi("post"  ,   "/route-imports/"+this.id_route_import+"/clients/doubles/Tel"                    , null)
                this.getDoublant.getDoublantTel     =   res.data.doubles_tel
            }

            //
            this.emitter.emit("refreshDoublantTel"     ,    this.getDoublant.getDoublantTel)

            //
            await this.$nextTick()

            //
            this.validation_type        =   "Tel"
            this.validation_clients     =   this.getDoublant.getDoublantTel

            //
            await this.$nextTick()

            //
            await this.$refs.ShowValidationClients.setDatatable()

            //
            await this.$hideLoadingPage()
        },

        async getDoubleGPS() {
  
            await this.$showLoadingPage()

            if(this.mode    ==  "temporary") {
                const res                                       =   await this.$callApi("post"  ,   "/route-imports-tempo/"+this.id_route_import_tempo+"/clients-tempo/doubles/GPS"  , null)
                this.getDoublant.getDoublantGPS   =   res.data.doubles_gps
            }

            if(this.mode    ==  "permanent") {
                const res                                       =   await this.$callApi("post"  ,   "/route-imports/"+this.id_route_import+"/clients/doubles/GPS"                    , null)
                this.getDoublant.getDoublantGPS   =   res.data.doubles_gps
            }

            //
            this.emitter.emit("refreshDoublantGPS"    ,   this.getDoublant.getDoublantGPS)

            //
            await this.$nextTick()

            //
            this.validation_type        =   "GPS"
            this.validation_clients     =   this.getDoublant.getDoublantGPS

            //
            await this.$nextTick()

            //
            await this.$refs.ShowValidationClients.setDatatable()

            //
            await this.$hideLoadingPage()
        },

        //

        async updateClientJSON(client) {

            const updatableFields   =   [
                "NewCustomer"               ,
                "OpenCustomer"              ,
                "CustomerIdentifier"        ,
                "CustomerCode"              ,

                "CustomerNameE"             ,
                "CustomerNameA"             ,

                "Tel"                       ,
                "tel_status"                ,
                "tel_comment"               ,

                "Latitude"                  ,
                "Longitude"                 ,

                "Address"                   ,
                "RvrsGeoAddress"            ,
                "Neighborhood"              ,
                "Landmark"                  ,

                "DistrictNo"                ,
                "DistrictNameE"             ,

                "CityNo"                    ,
                "CityNameE"                 ,

                "CustomerType"              ,

                "BrandAvailability"         ,
                "BrandSourcePurchase"       ,

                "JPlan"                     ,
                "Journee"                   ,

                "Frequency"                 ,
                "SuperficieMagasin"         ,
                "NbrAutomaticCheckouts"     ,

                "AvailableBrands"                   ,
                "AvailableBrands_array_formatted"   ,
                "AvailableBrands_string_formatted"  ,

                "status"                            ,
                "nonvalidated_details"              ,

                "owner"                             ,
                // "owner_username"                        ,

                "comment"                           ,

                "facade_image"                          ,
                "in_store_image"                        ,
                "facade_image_original_name"            ,
                "in_store_image_original_name"          ,
                "CustomerBarCode_image"                 ,
                "CustomerBarCode_image_original_name"   ,
            ];

            //
            const collectionsToUpdate = [
                this.total_clients,
                this.getDoublant.getDoublantCustomerCode,
                this.getDoublant.getDoublantCustomerNameE,
                this.getDoublant.getDoublantTel,
                this.getDoublant.getDoublantGPS
            ];

            //
            for (const arr of collectionsToUpdate) {

                const existing = arr.find(item => item.id === client.id);

                //
                if (existing) {

                    updatableFields.forEach(field => {
                        existing[field] = client[field];
                    });

                    continue;
                }
            }

            //
            await this.$refs.ShowValidationClients.setDatatable()
        },

        async deleteClientJSON(client) {

            let idx =   -1

            //      
            idx     =   this.total_clients.findIndex(c => c.id === client.id);
            if (idx !== -1) this.total_clients.splice(idx, 1);

            //
            idx     =   this.getDoublant.getDoublantCustomerCode.findIndex(c => c.id === client.id);
            if (idx !== -1) this.getDoublant.getDoublantCustomerCode.splice(idx, 1);

            //
            idx     =   this.getDoublant.getDoublantCustomerNameE.findIndex(c => c.id === client.id);
            if (idx !== -1) this.getDoublant.getDoublantCustomerNameE.splice(idx, 1);
            

            //
            idx     =   this.getDoublant.getDoublantTel.findIndex(c => c.id === client.id);
            if (idx !== -1) this.getDoublant.getDoublantTel.splice(idx, 1);
            
            //
            idx     =   this.getDoublant.getDoublantGPS.findIndex(c => c.id === client.id);
            if (idx !== -1) this.getDoublant.getDoublantGPS.splice(idx, 1);

            //
            await this.$refs.ShowValidationClients.setDatatable()
        }
    },

    watch   :   {
        validation_type(new_value, old_value) {
            this.emitter.emit("reSetValidationClientUpdate", new_value)
        }
    }
}

</script>