<template>

    <div class="p-3 m-2 h-100" style="background-color: #f2edf3; padding : 15px;">
        <!-- Filters            -->
        <div class="col-sm-12 p-2" id="stats_filters">
            <div class="card">
                <div class="card-body p-0">
                    <div class="row align-content-middle justify-content-center">

                        <!-- CustomerType -->
                        <div class="col">
                            <Multiselect
                                v-model     =   "selected_CustomerTypes"
                                :options    =   "[
                                                    {value: 'Alimentation Generale' , label: 'Alimentation Generale'}   , 
                                                    {value: 'Petit Superette'       , label: 'Petit Superette'}         ,  
                                                    {value: 'Grande Superette'      , label: 'Grande Superette'}        , 
                                                    {value: 'HyperMarché'           , label: 'HyperMarché'}             ,
                                                    {value: 'Special Detergent'     , label: 'Special Detergent'}  
                                                ]"

                                mode        =   "tags"
                                placeholder =   "Filter By CustomerType"
                                class       =   ""

                                :close-on-select    =   "false"
                                :searchable         =   "true"
                                :create-option      =   "false"
                            />
                        </div>

                        <!-- NbrVitrines -->
                        <div class="col">
                            <Multiselect
                                v-model     =   "selected_NbrVitrines"
                                :options    =   "[
                                                    {value: '1', label: '1'}    , 
                                                    {value: '2', label: '2'}    ,  
                                                    {value: '3', label: '3'}    , 
                                                    {value: '4', label: '4'}    ,
                                                    {value: '5', label: '5'}    , 
                                                    {value: '6', label: '6'}    , 
                                                    {value: '7', label: '7'}    , 
                                                    {value: '8', label: '8'}    , 
                                                    {value: '9', label: '9'}    , 
                                                    {value: '10', label: '10'}
                                                ]"
                                mode        =   "tags"
                                placeholder =   "Filter By NbrVitrines"
                                class       =   ""

                                :close-on-select    =   "false"
                                :searchable         =   "true"
                                :create-option      =   "false"
                            />
                        </div>

                        <!-- SuperficieMagasin -->
                        <div class="col">
                            <Multiselect
                                v-model     =   "selected_SuperficieMagasins"
                                :options    =   "[
                                                    {value: 'Moins de 20 M'  , label: 'Moins de 20 Metres'}     , 
                                                    {value: 'DE 20 a 50'     , label: 'DE 20 a 50 Metres'}      ,  
                                                    {value: 'DE 50 a 100'    , label: 'DE 50 a 100 Metres'}     , 
                                                    {value: 'Plus de 100'    , label: 'Plus de 100 Metres'}
                                                ]"
                                mode        =   "tags"
                                placeholder =   "Filter By SuperficieMagasins"
                                class       =   ""

                                :close-on-select    =   "false"
                                :searchable         =   "true"
                                :create-option      =   "false"
                            />
                        </div>

                        <!-- Select Date Range  -->
                        <div class="col">
                            <input type="date"                        class="form-control" v-model="start_date"/>
                        </div>
                        <!--  -->

                        <!-- Select Date Range  -->
                        <div class="col">
                            <input type="date"                        class="form-control" v-model="end_date"/>
                        </div>
                        <!--  -->

                        <!-- Get Range      -->
                        <div class="col">
                            <button class="btn primary w-100"   @click="getData()">Get Data</button>
                        </div>
                        <!--  -->

                    </div>
                </div>
            </div>
        </div>
        <!--                    -->

        <!--                    -->
        <div v-if="route_import" class="mt-3">
            <DynamicScroller
                class="scroller mt-2 mb-2"
                :items="clients"
                :min-item-size="480"
                key-field="id"
                page-mode
                ref="clientsScroller"
            >
                <template v-slot="{ item: client, index, active }">
                    <DynamicScrollerItem
                        :item="client"
                        :active="active"
                        :size-dependencies="[ client.sizeKey ]"
                        :data-index="index"
                    >
                        <div :id="'client_'+client.id" class="client-item shadow-sm rounded">
                            <div class="card mb-0">
                                <div class="card-body p-0">

                                <div class="mb-5 text-center">
                                    <u><h5 class="card-title mt-2">{{ client.CustomerNameE }} ({{ client.created_at }})</h5></u>
                                </div>

                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="row">
                                            <div class="col-sm-5">
                                                <div class="row mt-3 mb-3">
                                                    <div class="col-sm-12">
                                                        <div v-if="client.OpenCustomer === 'Ouvert'">
                                                            <label for="CustomerNameE" class="form-label">Nom et Prénom de l'Acheteur</label>
                                                            <input type="text"
                                                                class="form-control"
                                                                :id="'CustomerNameE_'+client.id"
                                                                v-model="client.CustomerNameE"
                                                                @input="updateSizeKey(client)"
                                                                :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))">
                                                        </div>
                                                    </div>
                                                </div>

                                            <div class="row mt-3 mb-3">
                                                <div class="col-sm-12">
                                                    <label for="CustomerNameA" class="form-label">Raison Sociale</label>
                                                    <input type="text"
                                                            class="form-control"
                                                            :id="'CustomerNameA_'+client.id"
                                                            v-model="client.CustomerNameA"
                                                            @input="updateSizeKey(client)"
                                                            :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))">
                                                </div>
                                            </div>

                                            <div class="row mt-3 mb-3">
                                                <div class="col-sm-12">
                                                    <div v-if="(((client.NewCustomer === 'Nouveau Client')&&(client.OpenCustomer === 'Ouvert'))||(client.NewCustomer === 'Client Existant'))">
                                                        <label for="Tel" class="form-label">Téléphone</label>
                                                        <input type="text"
                                                            class="form-control"
                                                            :id="'Tel_'+client.id"
                                                            v-model="client.Tel"
                                                            @input="updateSizeKey(client)"
                                                            :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mt-3 mb-3">
                                                <div class="col-sm-12">
                                                    <label for="Address" class="form-label">Adresse</label>
                                                    <input type="text"
                                                            class="form-control"
                                                            :id="'Address_'+client.id"
                                                            v-model="client.Address"
                                                            @input="updateSizeKey(client)"
                                                            :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))">
                                                </div>
                                            </div>

                                            <div class="row mt-3 mb-3">
                                                <div class="col-sm-12">
                                                    <label for="Neighborhood" class="form-label">Quartier</label>
                                                    <input type="text"
                                                            class="form-control"
                                                            :id="'Neighborhood_'+client.id"
                                                            v-model="client.Neighborhood"
                                                            @input="updateSizeKey(client)"
                                                            :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))">
                                                </div>
                                            </div>

                                            <div class="row mt-3 mb-3">
                                                <div class="col-sm-12">
                                                    <label for="Landmark" class="form-label">Point de Repere</label>
                                                    <textarea class="form-control"
                                                                :id="'Landmark_'+client.id"
                                                                rows="3"
                                                                v-model="client.Landmark"
                                                                @input="updateSizeKey(client)"
                                                                :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))"></textarea>
                                                </div>
                                            </div>
                                            </div>

                                            <div class="col-sm-7 d-flex">
                                                <div class="col">
                                                    <label for="facade_image_update" class="form-label">Image Facade :</label>
                                                    <button type="button"
                                                            class="btn btn-secondary w-100 mb-1"
                                                            @click="$clickFile('facade_image_update_'+client.id)"
                                                            :disabled="((client.status_original == 'confirmed')||(client.status_original == 'validated'))">
                                                    <i class="mdi mdi-camera"></i>
                                                    </button>

                                                    <input type="file"
                                                        class="form-control"
                                                        :id="'facade_image_update_'+client.id"
                                                        accept="image/*"
                                                        @change="facadeImage(index)"
                                                        style="display:none"
                                                        :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))">

                                                    <img class="mt-1 w-100"
                                                        :id="'facade_image_display_update_'+client.id"
                                                        :key="client.facade_image_display || client.facade_image || client.id"
                                                        :src="client.facade_image_display || ('/uploads/clients/'+client.id+'/'+client.facade_image || '')"
                                                        @load="onImageLoad(client)"
                                                        @error="onImageLoad(client)"
                                                        alt="facade" />

                                                </div>
                                            </div>

                                        </div>

                                        <div class="row">
                                            <div class="col-sm-12">
                                                <button type="button" class="btn btn-success w-100" style="margin-top: 30px;" @click="sendData(index)">Validate</button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <!-- right column (old_* display) -->
                                        <div class="row">
                                            <div class="col-sm-5">
                                                <div class="row mt-3 mb-3">
                                                    <div class="col-sm-12">
                                                    <div v-if="client.OpenCustomer === 'Ouvert'">
                                                        <label class="form-label">Nom et Prénom de l'Acheteur : <b>{{ client.old_CustomerNameE }}</b></label>
                                                    </div>
                                                    </div>
                                                </div>

                                                <div class="row mt-3 mb-3">
                                                    <div class="col-sm-12">
                                                    <label class="form-label">Raison Sociale : <b>{{ client.old_CustomerNameA }}</b></label>
                                                    </div>
                                                </div>

                                                <div class="row mt-3 mb-3">
                                                    <div class="col-sm-12">
                                                    <div v-if="(((client.NewCustomer === 'Nouveau Client')&&(client.OpenCustomer === 'Ouvert'))||(client.NewCustomer === 'Client Existant'))">
                                                        <label class="form-label">Téléphone : <b>{{ client.old_Tel }}</b></label>
                                                    </div>
                                                    </div>
                                                </div>

                                                <div class="row mt-3 mb-3">
                                                    <div class="col-sm-12">
                                                    <label class="form-label">Adresse : <b>{{ client.old_Address }}</b></label>
                                                    </div>
                                                </div>

                                                <div class="row mt-3 mb-3">
                                                    <div class="col-sm-12">
                                                    <label class="form-label">Quartier : <b>{{ client.old_Neighborhood }}</b></label>
                                                    </div>
                                                </div>

                                                <div class="row mt-3 mb-3">
                                                    <div class="col-sm-12">
                                                    <label class="form-label">Point de Repere : <b>{{ client.old_Landmark }}</b></label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-7 d-flex">
                                                <div class="col">
                                                    <label class="form-label">Image Old Facade :</label>
                                                    <img class="mt-1 w-100"
                                                        :id="'old_facade_image_display_update_'+client.id"
                                                        :src="'/uploads/clients/'+client.id+'/'+client.old_facade_image" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                </div> <!-- card-body -->
                            </div> <!-- card -->
                        </div> <!-- client-item -->
                    </DynamicScrollerItem>
                </template>
            </DynamicScroller>
        </div>
        <!--                    -->
    </div>

</template>

<script>

import { DynamicScroller, DynamicScrollerItem } from    'vue-virtual-scroller';
import Multiselect                              from    '@vueform/multiselect';

export default {

    data() {
        return {

            route_import    :   null    ,
            clients         :   []      ,

            //

            start_date      :   ""      ,
            end_date        :   ""      ,

            //

            users           :   []      ,
            willayas        :   []      ,
            cites           :   []      ,

            //

            scanner         :   null    ,

            //

            selected_CustomerTypes      :   []  ,
            selected_NbrVitrines        :   []  ,
            selected_SuperficieMagasins :   []  
        }
    },

    async mounted() {
        console.log(11111)
    },

    beforeUnmount() {

        if(this.scanner) {

            this.scanner.clear().then(_ => {

            }).catch(error => {

            });
        }
    },

    components: {
        Multiselect         ,
        DynamicScroller     ,
        DynamicScrollerItem
    },

    methods : {

        async sendData(index) {

            this.$showLoadingPage()

            // Set Client
            let client              =   this.clients[index]

            client.DistrictNameE    =   this.getDistrictNameE(client.DistrictNo)
            client.CityNameE        =   this.getCityNameE(client.CityNo)
            client.owner_username       =   this.getOwnerUsername(client.owner)

            let formData            =   new FormData();

            if(client.NewCustomer  === 'Nouveau Client') {

                if(client.OpenCustomer  === 'Ouvert') {

                    formData.append("CustomerIdentifier"                    ,   client.CustomerIdentifier)

                    formData.append("NewCustomer"                           ,   client.NewCustomer)
                    formData.append("OpenCustomer"                          ,   client.OpenCustomer)

                    formData.append("CustomerBarCodeExiste"                 ,   client.CustomerBarCodeExiste)
                    formData.append("CustomerCode"                          ,   client.CustomerCode)
                    formData.append("CustomerNameE"                         ,   client.CustomerNameE)
                    formData.append("CustomerNameA"                         ,   client.CustomerNameA)
                    formData.append("Latitude"                              ,   client.Latitude)
                    formData.append("Longitude"                             ,   client.Longitude)
                    formData.append("Address"                               ,   client.Address)
                    formData.append("RvrsGeoAddress"                        ,   client.RvrsGeoAddress)
                    formData.append("Neighborhood"                          ,   client.Neighborhood)
                    formData.append("Landmark"                              ,   client.Landmark)
                    formData.append("DistrictNo"                            ,   client.DistrictNo)
                    formData.append("DistrictNameE"                         ,   client.DistrictNameE)
                    formData.append("CityNo"                                ,   client.CityNo)
                    formData.append("CityNameE"                             ,   client.CityNameE)
                    formData.append("CustomerType"                          ,   client.CustomerType)

                    formData.append("NbrVitrines"                           ,   client.NbrVitrines)
                    formData.append("NbrAutomaticCheckouts"                 ,   client.NbrAutomaticCheckouts)

                    formData.append("SuperficieMagasin"                     ,   client.SuperficieMagasin)

                    formData.append("CustomerBarCodeExiste_image"           ,   client.CustomerBarCodeExiste_image)
                    formData.append("CustomerBarCode_image"                 ,   client.CustomerBarCode_image)
                    formData.append("facade_image"                          ,   client.facade_image)
                    formData.append("in_store_image"                        ,   client.in_store_image)

                    formData.append("CustomerBarCodeExiste_image_original_name" ,   client.CustomerBarCodeExiste_image_original_name)
                    formData.append("CustomerBarCode_image_original_name"       ,   client.CustomerBarCode_image_original_name)
                    formData.append("facade_image_original_name"                ,   client.facade_image_original_name)
                    formData.append("in_store_image_original_name"              ,   client.in_store_image_original_name)

                    formData.append("CustomerBarCodeExiste_image_updated"   ,   client.CustomerBarCodeExiste_image_updated)
                    formData.append("CustomerBarCode_image_updated"         ,   client.CustomerBarCode_image_updated)
                    formData.append("facade_image_updated"                  ,   client.facade_image_updated)
                    formData.append("in_store_image_updated"                ,   client.in_store_image_updated)

                    formData.append("status"                                ,   'confirmed')
                    formData.append("nonvalidated_details"                  ,   client.nonvalidated_details)

                    formData.append("comment"                               ,   client.comment)

                    formData.append("JPlan"                                 ,   client.JPlan)
                    formData.append("Journee"                               ,   client.Journee)
                    formData.append("BrandAvailability"                     ,   client.BrandAvailability)
                    formData.append("BrandSourcePurchase"                   ,   client.BrandSourcePurchase)

                    formData.append("Tel"                                   ,   client.Tel)
                    formData.append("tel_status"                            ,   client.tel_status)
                    formData.append("tel_comment"                           ,   client.tel_comment)

                    formData.append("owner"                                 ,   client.owner)
                }
            }

            if(client.NewCustomer  === 'Client Existant') {

                if(client.OpenCustomer  === 'Ouvert') {

                    formData.append("NewCustomer"                           ,   client.NewCustomer)
                    formData.append("OpenCustomer"                          ,   client.OpenCustomer)

                    formData.append("CustomerIdentifier"                    ,   client.CustomerIdentifier)
                    formData.append("CustomerBarCodeExiste"                 ,   client.CustomerBarCodeExiste)
                    formData.append("CustomerCode"                          ,   client.CustomerCode)
                    formData.append("CustomerNameE"                         ,   client.CustomerNameE)
                    formData.append("CustomerNameA"                         ,   client.CustomerNameA)
                    formData.append("Latitude"                              ,   client.Latitude)
                    formData.append("Longitude"                             ,   client.Longitude)
                    formData.append("Address"                               ,   client.Address)
                    formData.append("RvrsGeoAddress"                        ,   client.RvrsGeoAddress)
                    formData.append("Neighborhood"                          ,   client.Neighborhood)
                    formData.append("Landmark"                              ,   client.Landmark)
                    formData.append("DistrictNo"                            ,   client.DistrictNo)
                    formData.append("DistrictNameE"                         ,   client.DistrictNameE)
                    formData.append("CityNo"                                ,   client.CityNo)
                    formData.append("CityNameE"                             ,   client.CityNameE)
                    formData.append("CustomerType"                          ,   client.CustomerType)

                    formData.append("NbrVitrines"                           ,   client.NbrVitrines)
                    formData.append("NbrAutomaticCheckouts"                 ,   client.NbrAutomaticCheckouts)

                    formData.append("SuperficieMagasin"                     ,   client.SuperficieMagasin)

                    formData.append("CustomerBarCodeExiste_image"           ,   client.CustomerBarCodeExiste_image)
                    formData.append("CustomerBarCode_image"                 ,   client.CustomerBarCode_image)
                    formData.append("facade_image"                          ,   client.facade_image)
                    formData.append("in_store_image"                        ,   client.in_store_image)

                    formData.append("CustomerBarCodeExiste_image_updated"   ,   client.CustomerBarCodeExiste_image_updated)
                    formData.append("CustomerBarCode_image_updated"         ,   client.CustomerBarCode_image_updated)
                    formData.append("facade_image_updated"                  ,   client.facade_image_updated)
                    formData.append("in_store_image_updated"                ,   client.in_store_image_updated)

                    formData.append("CustomerBarCodeExiste_image_original_name" ,   client.CustomerBarCodeExiste_image_original_name)
                    formData.append("CustomerBarCode_image_original_name"       ,   client.CustomerBarCode_image_original_name)
                    formData.append("facade_image_original_name"                ,   client.facade_image_original_name)
                    formData.append("in_store_image_original_name"              ,   client.in_store_image_original_name)

                    formData.append("CustomerBarCodeExiste_image_updated"   ,   client.CustomerBarCodeExiste_image_updated)
                    formData.append("CustomerBarCode_image_updated"         ,   client.CustomerBarCode_image_updated)
                    formData.append("facade_image_updated"                  ,   client.facade_image_updated)
                    formData.append("in_store_image_updated"                ,   client.in_store_image_updated)

                    formData.append("status"                                ,   'confirmed')
                    formData.append("nonvalidated_details"                  ,   client.nonvalidated_details)

                    formData.append("comment"                               ,   client.comment)

                    formData.append("JPlan"                                 ,   client.JPlan)
                    formData.append("Journee"                               ,   client.Journee)
                    formData.append("BrandAvailability"                     ,   client.BrandAvailability)
                    formData.append("BrandSourcePurchase"                   ,   client.BrandSourcePurchase)

                    formData.append("Tel"                                   ,   client.Tel)
                    formData.append("tel_status"                            ,   client.tel_status)
                    formData.append("tel_comment"                           ,   client.tel_comment)

                    formData.append("owner"                                 ,   client.owner)
                }
            }

            const res                   =   await this.$callApi("post"  ,   "/route_import/"+client.id_route_import+"/clients/"+client.id+"/update",   formData)
            console.log(res)

            if(res.status===200){

                // Hide Loading Page
                this.$hideLoadingPage()

                // Send Feedback
                this.$feedbackSuccess(res.data["header"]    ,   res.data["message"])

                //
                // document.getElementById("client_"+client.id).remove()
                this.clients.splice(index, 1); 
            }
            
            else{

                // Hide Loading Page
                this.$hideLoadingPage()

                // Send Errors
                this.$showErrors("Error !", res.data.errors)
			}
        },

        //

        async facadeImage(index) {
            const input = document.getElementById("facade_image_update_" + this.clients[index].id);
            const file = input?.files?.[0];
            const client = this.clients[index];

            // revoke previous object URL if exists
            if (client._facade_object_url) {
                try { URL.revokeObjectURL(client._facade_object_url); } catch(e) {}
                client._facade_object_url = null;
            }

            // Clear display immediately to avoid showing previous image in recycled DOM
            client.facade_image_display = '';   // or null
            client.facade_image_updated = true;

            if (!file) {
                client.facade_image_original_name = "";
                client.facade_image = "";
                this.updateSizeKey(client);
                return;
            }

            // Optionally create a temporary preview synchronously (fast)
            // Preferred: use createObjectURL so preview is immediate
            const tempUrl = URL.createObjectURL(file);
            client.facade_image_display = tempUrl;   // immediate preview
            client._facade_object_url = tempUrl;

            // update measurement so scroller doesn't show overlap while compressing
            this.updateSizeKey(client);

            // Now do async compression/upload preparation (still keep preview)
            try {
                const compressed = await this.$compressImage(file); // your helper
                client.facade_image = compressed;
                // revoke previous tempUrl and replace with object URL for compressed blob if desired
                if (client._facade_object_url) {
                try { URL.revokeObjectURL(client._facade_object_url); } catch(e) {}
                client._facade_object_url = null;
                }
                if (compressed instanceof Blob) {
                const objUrl = URL.createObjectURL(compressed);
                client.facade_image_display = objUrl;
                client._facade_object_url = objUrl;
                } else {
                // fallback if compress returns base64
                let data = await this.$imageToBase64(compressed);
                if (!data.startsWith('data:')) data = 'data:image/jpeg;base64,' + data;
                client.facade_image_display = data;
                }
            } catch (err) {
                // compression failed — keep the temporary preview or clear it
                console.error(err);
            }

            // final measurement update
            this.updateSizeKey(client);
        },

        //

        async getComboData() {

            const res_1     =   await this.$callApi("post"  ,   "/route_import/"+this.$route.params.id_route_import+"/users/frontOffice"    ,   null)
            const res_2     =   await this.$callApi("post"  ,   "/route_import/"+this.$route.params.id_route_import+"/districts"            ,   null)
            const res_3     =   await this.$callApi("post"  ,   "/route_import/"+this.$route.params.id_route_import+"/cities"               ,   null)
            const res_4     =   await this.$callApi("post"  ,   "/route_import/"+this.$route.params.id_route_import+"/salesmen"             ,   null)

            this.users      =   res_1.data
            this.willayas   =   res_2.data
            this.cites      =   res_3.data

            //
            let salesmen                        =   res_4.data
            this.salesmen_all                   =   salesmen

            //

            this.salesmen                       =   []
            this.salesmen.push({value : "NA"    , label : "NA"})

            for (let i = 0; i < salesmen.length; i++) {

                this.salesmen.push({ value : salesmen[i].SalesmanNo , label : salesmen[i].SalesmanNo})
            }
        },

        //

        getDistrictNameE(DistrictNo) {

            for (let i = 0; i < this.willayas.length; i++) {

                if(this.willayas[i].DistrictNo  ==  DistrictNo) {

                    return this.willayas[i].DistrictNameE
                }                
            }
        },

        getCityNameE(CityNo) {

            for (let i = 0; i < this.cites.length; i++) {

                if(this.cites[i].CITYNO  ==  CityNo) {

                    return this.cites[i].CityNameE
                }                
            }
        },

        getOwnerUsername(owner) {

            for (let i = 0; i < this.users.length; i++) {

                if(this.users[i].id  ==  owner) {

                    return this.users[i].username
                }                
            }
        },

        //

        async getData() {

            try {

                this.$showLoadingPage()

                let formData    =   new FormData()

                formData.append("status",   "validated")

                if(this.start_date  !=  "") formData.append("start_date"    ,   this.start_date)
                if(this.end_date    !=  "") formData.append("end_date"      ,   this.end_date)

                if(this.selected_CustomerTypes      !=  "") formData.append("selected_CustomerTypes"        ,   JSON.stringify(this.selected_CustomerTypes))
                if(this.selected_NbrVitrines        !=  "") formData.append("selected_NbrVitrines"          ,   JSON.stringify(this.selected_NbrVitrines))
                if(this.selected_SuperficieMagasins !=  "") formData.append("selected_SuperficieMagasins"   ,   JSON.stringify(this.selected_SuperficieMagasins))

                //
                this.$callApi("post",   "/route_import/"+this.$route.params.id_route_import+"/clients",   formData)
                .then(async (res)=> {

                    console.log(this.$route.params.id_route_import)
                    console.log(res)

                    this.route_import = res.data.route_import;
                    this.initClients(res.data.clients);

                    //
                    await this.getComboData()

                    //
                    this.$hideLoadingPage()
                })
            }

            catch(e) {

                console.log(e)

                //
                this.$hideLoadingPage()
            }
        },

        //

        async setBarCodeReader(index) {

            const reader_update    =   document.getElementById('reader_update_'+this.clients[index].id)

            // 
            this.clients[index].CustomerCode     =   ""

            if(reader_update) {

                reader_update.style.display     =   "block";

                //
                this.scanner = new Html5QrcodeScanner('reader_update_'+this.clients[index].id, {

                    qrbox   : {
                        width   : 250,
                        height  : 250,
                    },

                    fps     : 20
                });

                try {
                    await this.scanner.render(this.success(index), this.error);
                } catch (error) {
                    console.error('Error rendering scanner:', error);
                }
            }
        },

        success(result, index) {
             
            // 
            if(this.$isValidForFileName(result)) {

                // 
                this.clients[index].CustomerCode    =   result
            }

            else {

                // 
                this.clients[index].CustomerCode    =   ""
                this.$showErrors("Error !"  ,   ["Votre code-barres contient des caractères interdits : / \ : * ? \" < > | &; (espace)"])
            }

            this.scanner.clear();

            document.getElementById('reader_update_'+this.clients[index].id).style.display =   "none"
            // Removes reader_update element from DOM since no longer needed 
        },

        error(err) {

            // Prints any errors to the console
            console.error("");
        },

        //  //  //  //  //

        // initialize sizeKey and image count after clients are fetched
        initClients(receivedClients) {
            if (!Array.isArray(receivedClients)) return;

            // Prefer to create a fresh array so Vue reactivity picks it up reliably
            this.clients = receivedClients.map(c => {
                // keep existing props and add our helper props
                return Object.assign({}, c, {
                imagesLoadedCount: 0,
                sizeKey: this.computeSizeKey(Object.assign({}, c, { imagesLoadedCount: 0 })),
                // initialize preview fields if you want them present
                facade_image_display: c.facade_image_display || '',
                CustomerBarCode_image_display: c.CustomerBarCode_image_display || '',
                CustomerBarCodeExiste_image_display: c.CustomerBarCodeExiste_image_display || ''
                });
            });
        },

        computeSizeKey(client) {
            return [
                client.OpenCustomer,
                client.NewCustomer,
                client.status,
                client.tel_status,
                (client.CustomerNameE || '').length ? 'n' : '',
                (client.Address || '').length ? 'a' : '',
                (client.RvrsGeoAddress || '').length ? 'r' : '',
                (client.Landmark || '').length ? 'l' : '',
                client.facade_image ? 'facimg' : '',
                client.old_facade_image ? 'oldfac' : '',
                client.imagesLoadedCount || 0
            ].join('|');
        },

        updateSizeKey(client) {
            client.sizeKey = this.computeSizeKey(client);
        },

        onImageLoad(client) {
            client.imagesLoadedCount = (client.imagesLoadedCount || 0) + 1;
            this.updateSizeKey(client);
        },
    }
};

</script>

<style scoped>

/* include in component <style> or global css */
.client-item {
    box-sizing: border-box;
    padding-bottom: 16px; /* gap between items — change to taste */
}

.client-item .card {
    margin-bottom: 0; /* avoid double spacing */
}

</style>