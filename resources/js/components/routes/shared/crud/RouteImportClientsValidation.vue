<template>
    <div class="p-3 m-2 h-100" style="background-color: #f2edf3; padding : 15px;">
        <div class="col-sm-12 p-2" id="stats_filters">
        <div class="card">
            <div class="card-body p-0">
            <div class="row align-content-middle justify-content-center">
                <div class="col">
                <Multiselect v-model="selected_CustomerTypes"
                            :options="[{value: 'Alimentation Generale' , label: 'Alimentation Generale'}, {value: 'Petit Superette', label: 'Petit Superette'}, {value: 'Grande Superette', label: 'Grande Superette'}, {value: 'HyperMarché', label: 'HyperMarché'}, {value: 'Special Detergent', label: 'Special Detergent'}]"
                            mode="tags" placeholder="Filter By CustomerType" :close-on-select="false" :searchable="true" :create-option="false" />
                </div>

                <div class="col">
                <Multiselect v-model="selected_NbrVitrines"
                            :options="[{value: '1', label: '1'}, {value: '2', label: '2'}, {value: '3', label: '3'}, {value: '4', label: '4'}, {value: '5', label: '5'}, {value: '6', label: '6'}, {value: '7', label: '7'}, {value: '8', label: '8'}, {value: '9', label: '9'}, {value: '10', label: '10'}]"
                            mode="tags" placeholder="Filter By NbrVitrines" :close-on-select="false" :searchable="true" :create-option="false" />
                </div>

                <div class="col">
                <Multiselect v-model="selected_SuperficieMagasins"
                            :options="[{value: 'Moins de 20 M', label: 'Moins de 20 Metres'}, {value: 'DE 20 a 50', label: 'DE 20 a 50 Metres'}, {value: 'DE 50 a 100', label: 'DE 50 a 100 Metres'}, {value: 'Plus de 100', label: 'Plus de 100 Metres'}]"
                            mode="tags" placeholder="Filter By SuperficieMagasins" :close-on-select="false" :searchable="true" :create-option="false" />
                </div>

                <div class="col">
                <input type="date" class="form-control" v-model="start_date" />
                </div>

                <div class="col">
                <input type="date" class="form-control" v-model="end_date" />
                </div>

                <div class="col">
                <button class="btn primary w-100" @click="getData()">Get Data</button>
                </div>
            </div>
            </div>
        </div>
        </div>

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
                        <u><h5 class="card-title mt-2">{{ client.CustomerIdentifier }} - {{ client.CustomerNameE }} ({{ client.created_at }})</h5></u>
                    </div>

                    <div class="row">
                        <div class="col-sm-5">
                        <div class="row mt-3 mb-3">
                            <div class="col-sm-4">
                            <div v-if="(client.NewCustomer == 'Client Existant')">
                                <label class="form-label">ID Client</label>
                                <input type="text" class="form-control" :id="'CustomerIdentifier_'+client.id" v-model="client.CustomerIdentifier" @input="updateSizeKey(client)"
                                    :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))">
                            </div>
                            </div>

                            <div class="col-sm-4">
                            <label class="form-label">Client Ouvert</label>
                            <select class="form-select" :id="'OpenCustomer_'+client.id" v-model="client.OpenCustomer" @change="() => setStatus(index)" :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))">
                                <option value="Ouvert">Ouvert</option>
                                <option value="Ferme">Ferme</option>
                                <option value="refus">refus</option>
                                <option v-if="(client.NewCustomer == 'Client Existant')" value="Introuvable">Introuvable</option>
                            </select>
                            </div>

                            <div class="col-sm-4">
                            <label class="form-label">Status</label>
                            <select class="form-select" :id="'status_'+client.id" v-model="client.status" @change="() => updateSizeKey(client)" :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))">
                                <option v-if="client.OpenCustomer == 'Ouvert'" value="confirmed" selected>confirmed</option>
                                <option v-if="client.OpenCustomer == 'Ouvert'" value="validated">validated</option>
                                <option v-if="client.OpenCustomer == 'Ouvert'" value="pending">pending</option>
                                <option v-if="client.OpenCustomer == 'Ouvert'" value="nonvalidated">nonvalidated</option>
                                <option v-if="client.OpenCustomer == 'Ouvert'" value="visible">visible</option>

                                <option v-if="client.OpenCustomer == 'Ferme'" value="ferme">ferme</option>
                                <option v-if="client.OpenCustomer == 'refus'" value="refus">refus</option>
                                <option v-if="client.OpenCustomer == 'Introuvable'" value="introuvable">introuvable</option>
                            </select>

                            <div v-if="client.status == 'nonvalidated'" class="mt-3">
                                <div class="form-group">
                                <label class="form-label">NonValidated Details</label>
                                <textarea class="form-control" :id="'nonvalidated_details_'+client.id" rows="3" v-model="client.nonvalidated_details" @input="updateSizeKey(client)" :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))" ></textarea>
                                </div>
                            </div>
                            </div>
                        </div>

                        <!-- rest of inputs: add @input where user edits can change height -->

                        <div class="row mt-3 mb-3">
                            <div class="col-sm-3">
                            <div v-if="client.OpenCustomer === 'Ouvert'">
                                <label class="form-label">CustomerBarCodeExiste</label>
                                <select class="form-select" :id="'CustomerCode_'+client.id" v-model="client.CustomerBarCodeExiste" @change="() => updateSizeKey(client)" :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))">
                                <option value="Non">Non</option>
                                <option value="Oui">Oui</option>
                                </select>
                            </div>
                            </div>

                            <div class="col-sm-3">
                            <div v-if="client.OpenCustomer === 'Ouvert'">
                                <label class="form-label">Code-Barre</label>
                                <input type="text" class="form-control" :id="'CustomerCode_'+client.id" v-model="client.CustomerCode" @input="updateSizeKey(client)" :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))" />
                            </div>
                            </div>

                            <div class="col-sm-3">
                            <div v-if="client.OpenCustomer === 'Ouvert'">
                                <label class="form-label">Nom et Prénom de l'Acheteur</label>
                                <input type="text" class="form-control" :id="'CustomerNameE_'+client.id" v-model="client.CustomerNameE" @input="updateSizeKey(client)" :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))" />
                            </div>
                            </div>

                            <div class="col-sm-3">
                            <label class="form-label">Raison Sociale</label>
                            <input type="text" class="form-control" :id="'CustomerNameA_'+client.id" v-model="client.CustomerNameA" @input="updateSizeKey(client)" :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))" />
                            </div>
                        </div>

                        <div class="row mt-3 mb-3">
                            <div class="col-sm-4">
                            <div v-if="(((client.NewCustomer === 'Nouveau Client')&&(client.OpenCustomer === 'Ouvert'))||(client.NewCustomer === 'Client Existant'))">
                                <label class="form-label">Téléphone</label>
                                <input type="text" class="form-control" :id="'Tel_'+client.id" v-model="client.Tel" @input="updateSizeKey(client)" :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))" />
                            </div>
                            </div>

                            <div class="col-sm-4">
                            <div v-if="(((client.NewCustomer === 'Nouveau Client')&&(client.OpenCustomer === 'Ouvert'))||(client.NewCustomer === 'Client Existant'))">
                                <label class="form-label">Téléphone Status</label>
                                <select class="form-select" :id="'tel_status_'+client.id" v-model="client.tel_status" @change="() => updateSizeKey(client)" :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))">
                                <option value="validated" selected>validated</option>
                                <option value="pending">pending</option>
                                <option value="nonvalidated">nonvalidated</option>
                                </select>
                            </div>
                            </div>

                            <div v-if="client.tel_status == 'nonvalidated'" class="col-sm-4">
                            <div v-if="(((client.NewCustomer === 'Nouveau Client')&&(client.OpenCustomer === 'Ouvert'))||(client.NewCustomer === 'Client Existant'))">
                                <label>Téléphone Commentaire</label>
                                <textarea class="form-control" :id="'tel_comment_'+client.id" rows="3" v-model="client.tel_comment" @input="updateSizeKey(client)" :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))" ></textarea>
                            </div>
                            </div>
                        </div>

                        <!-- continue inputs... many already have @input where appropriate -->

                        </div> <!-- col-sm-5 -->

                        <div class="col-sm-7">
                        <div class="row">
                            <div v-if="client.OpenCustomer === 'Ouvert'" class="col">
                            <label class="form-label">Image Code-Barre :</label>
                            <button type="button" class="btn btn-secondary w-100 mb-1" @click="$clickFile('CustomerBarCode_image_update_'+client.id)" :disabled="((client.status_original == 'confirmed')||(client.status_original == 'validated'))"><i class="mdi mdi-camera"></i></button>

                            <input type="file" class="form-control" :id="'CustomerBarCode_image_update_'+client.id" accept="image/*" capture @change="customerBarCodeImage(index)" style="display:none" :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))" />

                            <img class="mt-1 w-100"
                                :key="client.CustomerBarCode_image_display || client.CustomerBarCode_image || client.id + '-bc'"
                                :src="client.CustomerBarCode_image_display || ('/uploads/clients/'+client.id+'/'+client.CustomerBarCode_image || '')"
                                @load="onImageLoad(client)"
                                @error="onImageLoad(client)"
                                alt="barcode" />
                            </div>

                            <div class="col">
                            <label class="form-label">Image Facade :</label>
                            <button type="button" class="btn btn-secondary w-100 mb-1" @click="$clickFile('facade_image_update_'+client.id)" :disabled="((client.status_original == 'confirmed')||(client.status_original == 'validated'))"><i class="mdi mdi-camera"></i></button>

                            <input type="file" class="form-control" :id="'facade_image_update_'+client.id" accept="image/*" @change="facadeImage(index)" style="display:none" :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))" />

                            <img class="mt-1 w-100"
                                :key="client.facade_image_display || client.facade_image || client.id + '-fac'"
                                :src="client.facade_image_display || ('/uploads/clients/'+client.id+'/'+client.facade_image || '')"
                                @load="onImageLoad(client)"
                                @error="onImageLoad(client)"
                                alt="facade" />
                            </div>
                        </div>

                        <div class="row">
                            <div v-if="client.OpenCustomer === 'Ouvert'" class="col">
                            <label class="form-label">Image In-Store :</label>
                            <button type="button" class="btn btn-secondary w-100 mb-1" @click="$clickFile('in_store_image_update_'+client.id)" :disabled="((client.status_original == 'confirmed')||(client.status_original == 'validated'))"><i class="mdi mdi-camera"></i></button>

                            <input type="file" class="form-control" :id="'in_store_image_update_'+client.id" accept="image/*" @change="inStoreImage(index)" style="display:none" :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))" />

                            <img class="mt-1 w-100"
                                :key="client.in_store_image_display || client.in_store_image || client.id + '-inst'"
                                :src="client.in_store_image_display || ('/uploads/clients/'+client.id+'/'+client.in_store_image || '')"
                                @load="onImageLoad(client)"
                                @error="onImageLoad(client)"
                                alt="instore" />
                            </div>

                            <div v-if="client.OpenCustomer === 'Ouvert'" class="col">
                            <label class="form-label">Image CustomerBarCodeExiste :</label>
                            <button type="button" class="btn btn-secondary w-100 mb-1" @click="$clickFile('CustomerBarCodeExiste_image_update_'+client.id)" :disabled="((client.status_original == 'confirmed')||(client.status_original == 'validated'))"><i class="mdi mdi-camera"></i></button>

                            <input type="file" class="form-control" :id="'CustomerBarCodeExiste_image_update_'+client.id" accept="image/*" @change="customerBarCodeExisteImage(index)" style="display:none" :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))" />

                            <img class="mt-1 w-100"
                                :key="client.CustomerBarCodeExiste_image_display || client.CustomerBarCodeExiste_image || client.id + '-bcex'"
                                :src="client.CustomerBarCodeExiste_image_display || ('/uploads/clients/'+client.id+'/'+client.CustomerBarCodeExiste_image || '')"
                                @load="onImageLoad(client)"
                                @error="onImageLoad(client)"
                                alt="bcex" />
                            </div>
                        </div>
                        </div>
                    </div>

                    <!-- bottom rows with RvrsGeoAddress, owner, comment, Validate button -->
                    <div class="row">
                        <div class="row mt-3 mb-3">
                        <div class="col-sm-3">
                            <label class="form-label">Latitude (Latitude)</label>
                            <input type="text" class="form-control" id="Latitude" v-model="client.Latitude" @input="updateSizeKey(client)" :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))" />
                        </div>

                        <div class="col-sm-3">
                            <label class="form-label">Longitude (Longitude)</label>
                            <input type="text" class="form-control" id="Longitude" v-model="client.Longitude" @input="updateSizeKey(client)" :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))" />
                        </div>

                        <div class="col-sm-3">
                            <label class="form-label">RvrsGeoAddress</label>
                            <textarea class="form-control" :id="'RvrsGeoAddress_'+client.id" rows="3" v-model="client.RvrsGeoAddress" @input="updateSizeKey(client)" :disabled="true"></textarea>
                        </div>

                        <div class="col-sm-3">
                            <button type="button" class="btn btn-primary w-100" @click="getRvrsGeoAddress(index)">Get RvrsAddress</button>
                        </div>
                        </div>

                        <div class="row mt-3 mb-3">
                        <div class="col-sm-5">
                            <label class="form-label">Owner</label>
                            <select class="form-select" id="owner" v-model="client.owner" @change="() => updateSizeKey(client)">
                            <option value=""></option>
                            <option v-for="user in users" :key="user.id" :value="user.id">{{ user.username }}</option>
                            </select>
                        </div>

                        <div class="col-sm-5">
                            <label class="form-label">Commentaire</label>
                            <textarea class="form-control" id="comment" rows="3" v-model="client.comment" @input="updateSizeKey(client)" :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))" ></textarea>
                        </div>

                        <div class="col-sm-2">
                            <button type="button" class="btn btn-success w-100" style="margin-top: 30px;" @click="sendData(index)">Validate</button>
                        </div>
                        </div>
                    </div>

                    </div>
                </div>
                </div>
            </DynamicScrollerItem>
            </template>
        </DynamicScroller>
        </div>
    </div>
</template>

<script>

import 'vue-virtual-scroller/dist/vue-virtual-scroller.css'
import { RecycleScroller, DynamicScroller, DynamicScrollerItem }    from    'vue-virtual-scroller'

import Multiselect              from '@vueform/multiselect'

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
    },

    beforeUnmount() {

        // revoke any created object URLs to avoid leaks
        this.clients.forEach(c => {
            if (c._facade_object_url) try { URL.revokeObjectURL(c._facade_object_url) } catch(e) {}
            if (c._CustomerBarCode_object_url) try { URL.revokeObjectURL(c._CustomerBarCode_object_url) } catch(e) {}
            if (c._in_store_object_url) try { URL.revokeObjectURL(c._in_store_object_url) } catch(e) {}
            if (c._CustomerBarCodeExiste_object_url) try { URL.revokeObjectURL(c._CustomerBarCodeExiste_object_url) } catch(e) {}
        })

        if (this.scanner) {
            try { this.scanner.clear() } catch(e) {}
        }
    },

    components: {
        RecycleScroller,
        DynamicScroller,
        DynamicScrollerItem,
        Multiselect
    },

    methods : {

        async sendData(index) {

            this.$showLoadingPage()

            // Set Client
            let client              =   this.clients[index]

            client.DistrictNameE    =   this.getDistrictNameE(client.DistrictNo)
            client.CityNameE        =   this.getCityNameE(client.CityNo)
            client.owner_username   =   this.getOwnerUsername(client.owner)

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

                    formData.append("CustomerBarCodeExiste_image_original_name"     ,   client.CustomerBarCodeExiste_image_original_name)
                    formData.append("CustomerBarCode_image_original_name"           ,   client.CustomerBarCode_image_original_name)
                    formData.append("facade_image_original_name"                    ,   client.facade_image_original_name)
                    formData.append("in_store_image_original_name"                  ,   client.in_store_image_original_name)

                    formData.append("CustomerBarCodeExiste_image_updated"   ,   client.CustomerBarCodeExiste_image_updated)
                    formData.append("CustomerBarCode_image_updated"         ,   client.CustomerBarCode_image_updated)
                    formData.append("facade_image_updated"                  ,   client.facade_image_updated)
                    formData.append("in_store_image_updated"                ,   client.in_store_image_updated)

                    formData.append("status"                                ,   client.status /* client.status */)
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

                if(client.OpenCustomer  === 'Ferme') {

                    formData.append("CustomerIdentifier"                    ,   client.CustomerIdentifier)

                    formData.append("NewCustomer"                           ,   client.NewCustomer)
                    formData.append("OpenCustomer"                          ,   client.OpenCustomer)

                    formData.append("CustomerBarCodeExiste"                 ,   '')
                    formData.append("CustomerCode"                          ,   '')
                    formData.append("CustomerNameE"                         ,   '')
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

                    formData.append("NbrVitrines"                           ,   '')
                    formData.append("NbrAutomaticCheckouts"                 ,   '')

                    formData.append("SuperficieMagasin"                     ,   '')

                    formData.append("CustomerBarCodeExiste_image"           ,   '')
                    formData.append("CustomerBarCode_image"                 ,   '')
                    formData.append("facade_image"                          ,   client.facade_image)
                    formData.append("in_store_image"                        ,   '')

                    formData.append("CustomerBarCodeExiste_image_original_name" ,   '')
                    formData.append("CustomerBarCode_image_original_name"       ,   '')
                    formData.append("facade_image_original_name"                ,   client.facade_image_original_name)
                    formData.append("in_store_image_original_name"              ,   '')

                    formData.append("CustomerBarCodeExiste_image_updated"   ,   true)
                    formData.append("CustomerBarCode_image_updated"         ,   true)
                    formData.append("facade_image_updated"                  ,   client.facade_image_updated)
                    formData.append("in_store_image_updated"                ,   true)

                    formData.append("status"                                ,   client.status)
                    formData.append("nonvalidated_details"                  ,   '')

                    formData.append("comment"                               ,   client.comment)

                    formData.append("JPlan"                                 ,   client.JPlan)
                    formData.append("Journee"                               ,   client.Journee)
                    formData.append("BrandAvailability"                     ,   client.BrandAvailability)
                    formData.append("BrandSourcePurchase"                   ,   client.BrandSourcePurchase)

                    formData.append("Tel"                                   ,   client.Tel)
                    formData.append("tel_status"                            ,   'nonvalidated')
                    formData.append("tel_comment"                           ,   '')

                    formData.append("owner"                                 ,   client.owner)
                }

                if(client.OpenCustomer  === 'refus') {

                    formData.append("CustomerIdentifier"                    ,   client.CustomerIdentifier)

                    formData.append("NewCustomer"                           ,   client.NewCustomer)
                    formData.append("OpenCustomer"                          ,   client.OpenCustomer)

                    formData.append("CustomerBarCodeExiste"                 ,   '')
                    formData.append("CustomerCode"                          ,   '')
                    formData.append("CustomerNameE"                         ,   '')
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

                    formData.append("NbrVitrines"                           ,   '')
                    formData.append("NbrAutomaticCheckouts"                 ,   '')

                    formData.append("SuperficieMagasin"                     ,   '')

                    formData.append("CustomerBarCodeExiste_image"           ,   '')
                    formData.append("CustomerBarCode_image"                 ,   '')
                    formData.append("facade_image"                          ,   client.facade_image)
                    formData.append("in_store_image"                        ,   '')

                    formData.append("CustomerBarCodeExiste_image_original_name" ,   '')
                    formData.append("CustomerBarCode_image_original_name"       ,   '')
                    formData.append("facade_image_original_name"                ,   client.facade_image_original_name)
                    formData.append("in_store_image_original_name"              ,   '')

                    formData.append("CustomerBarCodeExiste_image_updated"   ,   true)
                    formData.append("CustomerBarCode_image_updated"         ,   true)
                    formData.append("facade_image_updated"                  ,   client.facade_image_updated)
                    formData.append("in_store_image_updated"                ,   true)

                    formData.append("status"                                ,   client.status)
                    formData.append("nonvalidated_details"                  ,   '')

                    formData.append("comment"                               ,   client.comment)

                    formData.append("JPlan"                                 ,   client.JPlan)
                    formData.append("Journee"                               ,   client.Journee)
                    formData.append("BrandAvailability"                     ,   client.BrandAvailability)
                    formData.append("BrandSourcePurchase"                   ,   client.BrandSourcePurchase)

                    formData.append("Tel"                                   ,   client.Tel)
                    formData.append("tel_status"                            ,   'nonvalidated')
                    formData.append("tel_comment"                           ,   '')

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

                    console.log(client.NbrAutomaticCheckouts)

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

                    formData.append("status"                                ,   client.status /* client.status */)
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

                if(client.OpenCustomer  === 'Ferme') {

                    formData.append("NewCustomer"                           ,   client.NewCustomer)
                    formData.append("OpenCustomer"                          ,   client.OpenCustomer)

                    formData.append("CustomerIdentifier"                    ,   client.CustomerIdentifier)
                    formData.append("CustomerBarCodeExiste"                 ,   '')
                    formData.append("CustomerCode"                          ,   '')
                    formData.append("CustomerNameE"                         ,   '')
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

                    formData.append("NbrVitrines"                           ,   '')
                    formData.append("NbrAutomaticCheckouts"                 ,   '')

                    formData.append("SuperficieMagasin"                     ,   client.SuperficieMagasin)

                    formData.append("CustomerBarCodeExiste_image_updated"   ,   true)
                    formData.append("CustomerBarCode_image_updated"         ,   true)
                    formData.append("facade_image_updated"                  ,   client.facade_image_updated)
                    formData.append("in_store_image_updated"                ,   true)

                    formData.append("CustomerBarCodeExiste_image"           ,   '')
                    formData.append("CustomerBarCode_image"                 ,   '')
                    formData.append("facade_image"                          ,   client.facade_image)
                    formData.append("in_store_image"                        ,   '')

                    formData.append("CustomerBarCodeExiste_image_original_name"     ,   '')
                    formData.append("CustomerBarCode_image_original_name"           ,   '')
                    formData.append("facade_image_original_name"                    ,   client.facade_image_original_name)
                    formData.append("in_store_image_original_name"                  ,   '')

                    formData.append("CustomerBarCodeExiste_image_updated"   ,   true)
                    formData.append("CustomerBarCode_image_updated"         ,   true)
                    formData.append("facade_image_updated"                  ,   client.facade_image_updated)
                    formData.append("in_store_image_updated"                ,   true)

                    formData.append("status"                                ,   client.status)
                    formData.append("nonvalidated_details"                  ,   '')

                    formData.append("comment"                               ,   client.comment)

                    formData.append("JPlan"                                 ,   client.JPlan)
                    formData.append("Journee"                               ,   client.Journee)
                    formData.append("BrandAvailability"                     ,   client.BrandAvailability)
                    formData.append("BrandSourcePurchase"                   ,   client.BrandSourcePurchase)

                    formData.append("Tel"                                   ,   client.Tel)
                    formData.append("tel_status"                            ,   'nonvalidated')
                    formData.append("tel_comment"                           ,   '')

                    formData.append("owner"                                 ,   client.owner)
                }

                if(client.OpenCustomer  === 'refus') {

                    formData.append("NewCustomer"                           ,   client.NewCustomer)
                    formData.append("OpenCustomer"                          ,   client.OpenCustomer)

                    formData.append("CustomerIdentifier"                    ,   client.CustomerIdentifier)
                    formData.append("CustomerBarCodeExiste"                 ,   '')
                    formData.append("CustomerCode"                          ,   '')
                    formData.append("CustomerNameE"                         ,   '')
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

                    formData.append("NbrVitrines"                           ,   '')
                    formData.append("NbrAutomaticCheckouts"                 ,   '')

                    formData.append("SuperficieMagasin"                     ,   client.SuperficieMagasin)

                    formData.append("CustomerBarCodeExiste_image_updated"   ,   true)
                    formData.append("CustomerBarCode_image_updated"         ,   true)
                    formData.append("facade_image_updated"                  ,   client.facade_image_updated)
                    formData.append("in_store_image_updated"                ,   true)

                    formData.append("CustomerBarCodeExiste_image"           ,   '')
                    formData.append("CustomerBarCode_image"                 ,   '')
                    formData.append("facade_image"                          ,   client.facade_image)
                    formData.append("in_store_image"                        ,   '')

                    formData.append("CustomerBarCodeExiste_image_original_name"     ,   '')
                    formData.append("CustomerBarCode_image_original_name"           ,   '')
                    formData.append("facade_image_original_name"                    ,   client.facade_image_original_name)
                    formData.append("in_store_image_original_name"                  ,   '')

                    formData.append("CustomerBarCodeExiste_image_updated"   ,   true)
                    formData.append("CustomerBarCode_image_updated"         ,   true)
                    formData.append("facade_image_updated"                  ,   client.facade_image_updated)
                    formData.append("in_store_image_updated"                ,   true)

                    formData.append("status"                                ,   client.status)
                    formData.append("nonvalidated_details"                  ,   '')

                    formData.append("comment"                               ,   client.comment)

                    formData.append("JPlan"                                 ,   client.JPlan)
                    formData.append("Journee"                               ,   client.Journee)
                    formData.append("BrandAvailability"                     ,   client.BrandAvailability)
                    formData.append("BrandSourcePurchase"                   ,   client.BrandSourcePurchase)

                    formData.append("Tel"                                   ,   client.Tel)
                    formData.append("tel_status"                            ,   'nonvalidated')
                    formData.append("tel_comment"                           ,   '')

                    formData.append("owner"                                 ,   client.owner)
                }

                if(client.OpenCustomer  === 'Introuvable') {

                    formData.append("NewCustomer"                           ,   client.NewCustomer)
                    formData.append("OpenCustomer"                          ,   client.OpenCustomer)

                    formData.append("CustomerIdentifier"                    ,   client.CustomerIdentifier)
                    formData.append("CustomerBarCodeExiste"                 ,   '')
                    formData.append("CustomerCode"                          ,   '')
                    formData.append("CustomerNameE"                         ,   '')
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

                    formData.append("NbrVitrines"                           ,   '')
                    formData.append("NbrAutomaticCheckouts"                 ,   '')

                    formData.append("SuperficieMagasin"                     ,   client.SuperficieMagasin)

                    formData.append("CustomerBarCodeExiste_image_updated"   ,   true)
                    formData.append("CustomerBarCode_image_updated"         ,   true)
                    formData.append("facade_image_updated"                  ,   client.facade_image_updated)
                    formData.append("in_store_image_updated"                ,   true)

                    formData.append("CustomerBarCodeExiste_image"           ,   '')
                    formData.append("CustomerBarCode_image"                 ,   '')
                    formData.append("facade_image"                          ,   client.facade_image)
                    formData.append("in_store_image"                        ,   '')

                    formData.append("CustomerBarCodeExiste_image_original_name" ,   '')
                    formData.append("CustomerBarCode_image_original_name"       ,   '')
                    formData.append("facade_image_original_name"                ,   client.facade_image_original_name)
                    formData.append("in_store_image_original_name"              ,   '')

                    formData.append("CustomerBarCodeExiste_image_updated"   ,   true)
                    formData.append("CustomerBarCode_image_updated"         ,   true)
                    formData.append("facade_image_updated"                  ,   client.facade_image_updated)
                    formData.append("in_store_image_updated"                ,   true)

                    formData.append("status"                                ,   client.status)
                    formData.append("nonvalidated_details"                  ,   '')

                    formData.append("comment"                               ,   client.comment)

                    formData.append("JPlan"                                 ,   client.JPlan)
                    formData.append("Journee"                               ,   client.Journee)
                    formData.append("BrandAvailability"                     ,   client.BrandAvailability)
                    formData.append("BrandSourcePurchase"                   ,   client.BrandSourcePurchase)

                    formData.append("Tel"                                   ,   client.Tel)
                    formData.append("tel_status"                            ,   'nonvalidated')
                    formData.append("tel_comment"                           ,   '')

                    formData.append("owner"                                 ,   client.owner)
                }
            }

            const res                   =   await this.$callApi("post"  ,   "/route-imports/"+client.id_route_import+"/clients/"+client.id+"/update",   formData)
            console.log(res)

            if(res.status===200){

                // Hide Loading Page
                this.$hideLoadingPage()

                // Send Feedback
                this.$feedbackSuccess(res.data["header"]    ,   res.data["message"])

                //
                // revoke any object URLs before removing
                if (client._facade_object_url)                  try { URL.revokeObjectURL(client._facade_object_url)                } catch(e) {}
                if (client._CustomerBarCode_object_url)         try { URL.revokeObjectURL(client._CustomerBarCode_object_url)       } catch(e) {}
                if (client._in_store_object_url)                try { URL.revokeObjectURL(client._in_store_object_url)              } catch(e) {}
                if (client._CustomerBarCodeExiste_object_url)   try { URL.revokeObjectURL(client._CustomerBarCodeExiste_object_url) } catch(e) {}

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

        // -------------------- Initialization helpers --------------------
        initClients(receivedClients) {
            if (!Array.isArray(receivedClients)) return;
            // create a fresh array with our helper fields so all properties are reactive
            this.clients = receivedClients.map(c => {
            const copy = Object.assign({}, c);
            copy.imagesLoadedCount = 0;
            copy.facade_image_display = copy.facade_image_display || '';
            copy.CustomerBarCode_image_display = copy.CustomerBarCode_image_display || '';
            copy.in_store_image_display = copy.in_store_image_display || '';
            copy.CustomerBarCodeExiste_image_display = copy.CustomerBarCodeExiste_image_display || '';
            copy._facade_object_url = null;
            copy._CustomerBarCode_object_url = null;
            copy._in_store_object_url = null;
            copy._CustomerBarCodeExiste_object_url = null;
            copy.sizeKey = this.computeSizeKey(copy);
            return copy;
            });
        },

        computeSizeKey(client) {
            return [
            client.OpenCustomer,
            client.NewCustomer,
            client.status,
            client.tel_status,
            (client.nonvalidated_details || '').length > 0 ? 'nv' : '',
            (client.tel_comment || '').length > 0 ? 'telc' : '',
            (client.comment || '').length > 0 ? 'c' : '',
            (client.Landmark || '').length > 0 ? 'lm' : '',
            (client.CustomerNameE || '').length > 0 ? 'name' : '',
            (client.Address || '').length > 0 ? 'addr' : '',
            (client.RvrsGeoAddress || '').length > 0 ? 'ra' : '',
            client.imagesLoadedCount || 0,
            client.CustomerBarCode_image ? 'bcimg' : '',
            client.facade_image ? 'facimg' : '',
            client.in_store_image ? 'instore' : '',
            client.CustomerBarCodeExiste_image ? 'bcex' : ''
            ].join('|');
        },

        updateSizeKey(client) {
            client.sizeKey = this.computeSizeKey(client);
        },

        onImageLoad(client) {
            client.imagesLoadedCount = (client.imagesLoadedCount || 0) + 1;
            this.updateSizeKey(client);
        },

        //

        async facadeImage(index) {
            const input = document.getElementById('facade_image_update_' + this.clients[index].id);
            const file = input?.files?.[0];
            const client = this.clients[index];


            // revoke previous object URL if exists
            if (client._facade_object_url) {
            try { URL.revokeObjectURL(client._facade_object_url); } catch(e) {}
            client._facade_object_url = null;
            }


            // clear display immediately to avoid showing previous client's image in recycled DOM
            client.facade_image_display = '';
            client.facade_image_updated = true;


            if (!file) {
            client.facade_image_original_name = '';
            client.facade_image = '';
            this.updateSizeKey(client);
            return;
            }


            // immediate temporary preview using raw file
            const tempUrl = URL.createObjectURL(file);
            client.facade_image_display = tempUrl;
            client._facade_object_url = tempUrl;
            this.updateSizeKey(client);


            // async compress / replace preview with compressed blob object URL if possible
            try {
            const compressed = await this.$compressImage(file);
            client.facade_image = compressed;
            client.facade_image_original_name = file.name;


            // revoke temp and create final object url
            if (client._facade_object_url) { try { URL.revokeObjectURL(client._facade_object_url); } catch(e) {} }
            if (compressed instanceof Blob) {
            const objUrl = URL.createObjectURL(compressed);
            client.facade_image_display = objUrl;
            client._facade_object_url = objUrl;
            } else {
            // fallback if compress returns base64 string
            let data = await this.$imageToBase64(compressed);
            if (!data.startsWith('data:')) data = 'data:image/jpeg;base64,' + data;
            client.facade_image_display = data;
            }
            } catch (err) {
            // compression failed - keep temporary preview
            console.error('facade compress error', err);
            }


            this.updateSizeKey(client);
        },

        async customerBarCodeImage(index) {
            const input = document.getElementById('CustomerBarCode_image_update_' + this.clients[index].id);
            const file = input?.files?.[0];
            const client = this.clients[index];


            if (client._CustomerBarCode_object_url) { try { URL.revokeObjectURL(client._CustomerBarCode_object_url) } catch(e) {} client._CustomerBarCode_object_url = null }


            client.CustomerBarCode_image_display = '';
            client.CustomerBarCode_image_updated = true;


            if (!file) {
            client.CustomerBarCode_image_original_name = '';
            client.CustomerBarCode_image = '';
            this.updateSizeKey(client);
            return;
            }


            const tempUrl = URL.createObjectURL(file);
            client.CustomerBarCode_image_display = tempUrl;
            client._CustomerBarCode_object_url = tempUrl;
            this.updateSizeKey(client);


            try {
            const compressed = await this.$compressImage(file);
            client.CustomerBarCode_image = compressed;
            client.CustomerBarCode_image_original_name = file.name;


            if (client._CustomerBarCode_object_url) { try { URL.revokeObjectURL(client._CustomerBarCode_object_url) } catch(e) {} }
            if (compressed instanceof Blob) {
            const objUrl = URL.createObjectURL(compressed);
            client.CustomerBarCode_image_display = objUrl;
            client._CustomerBarCode_object_url = objUrl;
            } else {
            let data = await this.$imageToBase64(compressed);
            if (!data.startsWith('data:')) data = 'data:image/jpeg;base64,' + data;
            client.CustomerBarCode_image_display = data;
            }
            } catch (err) {
            console.error('barcode compress error', err);
            }


            this.updateSizeKey(client);
        },

        async customerBarCodeExisteImage(index) {
            const input = document.getElementById('CustomerBarCodeExiste_image_update_' + this.clients[index].id);
            const file = input?.files?.[0];
            const client = this.clients[index];


            if (client._CustomerBarCodeExiste_object_url) { try { URL.revokeObjectURL(client._CustomerBarCodeExiste_object_url) } catch(e) {} client._CustomerBarCodeExiste_object_url = null }


            client.CustomerBarCodeExiste_image_display = '';
            client.CustomerBarCodeExiste_image_updated = true;


            if (!file) {
            client.CustomerBarCodeExiste_image_original_name = '';
            client.CustomerBarCodeExiste_image = '';
            this.updateSizeKey(client);
            return;
            }


            const tempUrl = URL.createObjectURL(file);
            client.CustomerBarCodeExiste_image_display = tempUrl;
            client._CustomerBarCodeExiste_object_url = tempUrl;
            this.updateSizeKey(client);


            try {
            const compressed = await this.$compressImage(file);
            client.CustomerBarCodeExiste_image = compressed;
            client.CustomerBarCodeExiste_image_original_name = file.name;


            if (client._CustomerBarCodeExiste_object_url) { try { URL.revokeObjectURL(client._CustomerBarCodeExiste_object_url) } catch(e) {} }
            if (compressed instanceof Blob) {
            const objUrl = URL.createObjectURL(compressed);
            client.CustomerBarCodeExiste_image_display = objUrl;
            client._CustomerBarCodeExiste_object_url = objUrl;
            } else {
            let data = await this.$imageToBase64(compressed);
            if (!data.startsWith('data:')) data = 'data:image/jpeg;base64,' + data;
            client.CustomerBarCodeExiste_image_display = data;
            }
            } catch (err) {
            console.error('bcex compress error', err);
            }


            this.updateSizeKey(client);
        },

        async inStoreImage(index) {
            const input = document.getElementById('in_store_image_update_' + this.clients[index].id);
            const file = input?.files?.[0];
            const client = this.clients[index];


            if (client._in_store_object_url) { try { URL.revokeObjectURL(client._in_store_object_url) } catch(e) {} client._in_store_object_url = null }


            client.in_store_image_display = '';
            client.in_store_image_updated = true;


            if (!file) {
            client.in_store_image_original_name = '';
            client.in_store_image = '';
            this.updateSizeKey(client);
            return;
            }


            const tempUrl = URL.createObjectURL(file);
            client.in_store_image_display = tempUrl;
            client._in_store_object_url = tempUrl;
            this.updateSizeKey(client);


            try {
            const compressed = await this.$compressImage(file);
            client.in_store_image = compressed;
            client.in_store_image_original_name = file.name;


            if (client._in_store_object_url) { try { URL.revokeObjectURL(client._in_store_object_url) } catch(e) {} }
            if (compressed instanceof Blob) {
            const objUrl = URL.createObjectURL(compressed);
            client.in_store_image_display = objUrl;
            client._in_store_object_url = objUrl;
            } else {
            let data = await this.$imageToBase64(compressed);
            if (!data.startsWith('data:')) data = 'data:image/jpeg;base64,' + data;
            client.in_store_image_display = data;
            }
            } catch (err) {
            console.error('instore compress error', err);
            }


            this.updateSizeKey(client);
        },

        //

        async getComboData() {

            const res_1     =   await this.$callApi("post"  ,   "/route-imports/"+this.$route.params.id_route_import+"/users/frontOffice"    ,   null)
            const res_2     =   await this.$callApi("post"  ,   "/route-imports/"+this.$route.params.id_route_import+"/districts"            ,   null)
            const res_3     =   await this.$callApi("post"  ,   "/route-imports/"+this.$route.params.id_route_import+"/cities"               ,   null)

            this.users      =   res_1.data
            this.willayas   =   res_2.data
            this.cites      =   res_3.data
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

                if(this.cites[i].CityNo  ==  CityNo) {

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

                formData.append("status",   "pending")

                if(this.start_date  !=  "") formData.append("start_date"    ,   this.start_date)
                if(this.end_date    !=  "") formData.append("end_date"      ,   this.end_date)

                if(this.selected_CustomerTypes      !=  "") formData.append("selected_CustomerTypes"        ,   JSON.stringify(this.selected_CustomerTypes))
                if(this.selected_NbrVitrines        !=  "") formData.append("selected_NbrVitrines"          ,   JSON.stringify(this.selected_NbrVitrines))
                if(this.selected_SuperficieMagasins !=  "") formData.append("selected_SuperficieMagasins"   ,   JSON.stringify(this.selected_SuperficieMagasins))

                //
                this.$callApi("post",   "/route-imports/"+this.$route.params.id_route_import+"/clients",   formData)
                .then(async (res)=> {

                    console.log(res)

                    this.route_import   =   res.data.route_import
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

            const el = document.getElementById('reader_update_' + this.clients[index].id);
            if (el) el.style.display = 'none';
        },

        error(err) {

            // Prints any errors to the console
            console.error("");
        },

        //

        setStatus(index) {

            if(this.clients[index].OpenCustomer ==  "Ferme") {
                this.clients[index].status     =   "ferme"
            }

            else {

                if(this.clients[index].OpenCustomer ==  "refus") {
                    this.clients[index].status     =   "refus"
                }

                else {

                    if(this.clients[index].OpenCustomer ==  "Ouvert") {
                        this.clients[index].status     =   "pending"
                    }

                    else {
                        if(this.clients[index].OpenCustomer ==  "Introuvable") {
                            this.clients[index].status     =   "introuvable"
                        }
                    }
                }
            }

            // 
            if(this.clients[index].NewCustomer  ==  "Nouveau Client") {
                // if he was ferme and then became ouvert i keep all info
                if(this.clients[index].OpenCustomer ==  "Ouvert") {
                    this.clients[index].CustomerIdentifier                      =   "Nouveau"
                }

                // i only keep info that are in ferme questionnary
                if(this.clients[index].OpenCustomer ==  "Ferme") {
                    this.clients[index].CustomerIdentifier                      =   "Nouveau"

                    this.clients[index].CustomerCode                            =   ""
                    this.clients[index].CustomerBarCode_image                   =   ""
                    this.clients[index].CustomerBarCode_image_original_name     =   ""

                    this.clients[index].CustomerBarCodeExiste                       =   ""
                    this.clients[index].CustomerBarCodeExiste_image                 =   ""
                    this.clients[index].CustomerBarCodeExiste_image_original_name   =   ""

                    this.clients[index].CustomerNameE                           =   ""

                    this.clients[index].Tel                                     =   ""
                    this.clients[index].tel_status                              =   "nonvalidated"
                    this.clients[index].tel_comment                             =   ""

                    this.clients[index].NbrAutomaticCheckouts                   =   ""
                    this.clients[index].NbrVitrines                             =   ""
                    this.clients[index].SuperficieMagasin                       =   ""
                    this.clients[index].in_store_image                          =   ""
                    this.clients[index].in_store_image_original_name            =   ""

                    this.clients[index].CustomerBarCodeExiste_image_updated     =   true
                    this.clients[index].CustomerBarCode_image_updated           =   true
                    this.clients[index].in_store_image_updated                  =   true
                }

                //
                if(this.clients[index].OpenCustomer ==  "refus") {
                    this.clients[index].CustomerIdentifier                      =   "Nouveau"

                    this.clients[index].CustomerCode                            =   ""
                    this.clients[index].CustomerBarCode_image                   =   ""
                    this.clients[index].CustomerBarCode_image_original_name     =   ""

                    this.clients[index].CustomerBarCodeExiste                       =   ""
                    this.clients[index].CustomerBarCodeExiste_image                 =   ""
                    this.clients[index].CustomerBarCodeExiste_image_original_name   =   ""

                    this.clients[index].CustomerNameE                           =   ""

                    this.clients[index].Tel                                     =   ""
                    this.clients[index].tel_status                              =   "nonvalidated"
                    this.clients[index].tel_comment                             =   ""

                    this.clients[index].NbrAutomaticCheckouts                   =   ""
                    this.clients[index].NbrVitrines                             =   ""
                    this.clients[index].SuperficieMagasin                       =   ""
                    this.clients[index].in_store_image                          =   ""
                    this.clients[index].in_store_image_original_name            =   ""

                    this.clients[index].CustomerBarCodeExiste_image_updated     =   true
                    this.clients[index].CustomerBarCode_image_updated           =   true
                    this.clients[index].in_store_image_updated                  =   true
                }

                //
                if(this.clients[index].OpenCustomer ==  "Introuvable") {
                    this.clients[index].CustomerIdentifier                      =   "Nouveau"

                    this.clients[index].CustomerCode                            =   ""
                    this.clients[index].CustomerBarCode_image                   =   ""
                    this.clients[index].CustomerBarCode_image_original_name     =   ""

                    this.clients[index].CustomerBarCodeExiste                       =   ""
                    this.clients[index].CustomerBarCodeExiste_image                 =   ""
                    this.clients[index].CustomerBarCodeExiste_image_original_name   =   ""

                    this.clients[index].CustomerNameE                           =   ""

                    this.clients[index].Tel                                     =   ""
                    this.clients[index].tel_status                              =   "nonvalidated"
                    this.clients[index].tel_comment                             =   ""

                    this.clients[index].NbrAutomaticCheckouts                   =   ""
                    this.clients[index].NbrVitrines                             =   ""
                    this.clients[index].SuperficieMagasin                       =   ""
                    this.clients[index].in_store_image                          =   ""
                    this.clients[index].in_store_image_original_name            =   ""

                    this.clients[index].CustomerBarCodeExiste_image_updated     =   true
                    this.clients[index].CustomerBarCode_image_updated           =   true
                    this.clients[index].in_store_image_updated                  =   true
                }
            }

            if(this.clients[index].NewCustomer  ==  "Client Existant") {
                if(this.clients[index].OpenCustomer ==  "Ouvert") {

                }

                if(this.clients[index].OpenCustomer ==  "Ferme") {

                    this.clients[index].CustomerCode                            =   ""
                    this.clients[index].CustomerBarCode_image                   =   ""
                    this.clients[index].CustomerBarCode_image_original_name     =   ""

                    this.clients[index].CustomerBarCodeExiste                       =   ""
                    this.clients[index].CustomerBarCodeExiste_image                 =   ""
                    this.clients[index].CustomerBarCodeExiste_image_original_name   =   ""

                    this.clients[index].CustomerNameE                           =   ""

                    this.clients[index].Tel                                     =   ""
                    this.clients[index].tel_status                              =   "nonvalidated"
                    this.clients[index].tel_comment                             =   ""

                    this.clients[index].NbrAutomaticCheckouts                   =   ""
                    this.clients[index].NbrVitrines                             =   ""
                    this.clients[index].SuperficieMagasin                       =   ""
                    this.clients[index].in_store_image                          =   ""
                    this.clients[index].in_store_image_original_name            =   ""

                    this.clients[index].CustomerBarCodeExiste_image_updated     =   true
                    this.clients[index].CustomerBarCode_image_updated           =   true
                    this.clients[index].in_store_image_updated                  =   true
                }

                if(this.clients[index].OpenCustomer ==  "refus") {
                    this.clients[index].CustomerCode                            =   ""
                    this.clients[index].CustomerBarCode_image                   =   ""
                    this.clients[index].CustomerBarCode_image_original_name     =   ""

                    this.clients[index].CustomerBarCodeExiste                       =   ""
                    this.clients[index].CustomerBarCodeExiste_image                 =   ""
                    this.clients[index].CustomerBarCodeExiste_image_original_name   =   ""

                    this.clients[index].CustomerNameE                           =   ""

                    this.clients[index].Tel                                     =   ""
                    this.clients[index].tel_status                              =   "nonvalidated"
                    this.clients[index].tel_comment                             =   ""

                    this.clients[index].NbrAutomaticCheckouts                   =   ""
                    this.clients[index].NbrVitrines                             =   ""
                    this.clients[index].SuperficieMagasin                       =   ""
                    this.clients[index].in_store_image                          =   ""
                    this.clients[index].in_store_image_original_name            =   ""

                    this.clients[index].CustomerBarCodeExiste_image_updated     =   true
                    this.clients[index].CustomerBarCode_image_updated           =   true
                    this.clients[index].in_store_image_updated                  =   true
                }

                if(this.clients[index].OpenCustomer ==  "Introuvable") {
                    this.clients[index].CustomerCode                            =   ""
                    this.clients[index].CustomerBarCode_image                   =   ""
                    this.clients[index].CustomerBarCode_image_original_name     =   ""

                    this.clients[index].CustomerBarCodeExiste                       =   ""
                    this.clients[index].CustomerBarCodeExiste_image                 =   ""
                    this.clients[index].CustomerBarCodeExiste_image_original_name   =   ""

                    this.clients[index].CustomerNameE                           =   ""

                    this.clients[index].Tel                                     =   ""
                    this.clients[index].tel_status                              =   "nonvalidated"
                    this.clients[index].tel_comment                             =   ""

                    this.clients[index].NbrAutomaticCheckouts                   =   ""
                    this.clients[index].NbrVitrines                             =   ""
                    this.clients[index].SuperficieMagasin                       =   ""
                    this.clients[index].in_store_image                          =   ""
                    this.clients[index].in_store_image_original_name            =   ""

                    this.clients[index].CustomerBarCodeExiste_image_updated     =   true
                    this.clients[index].CustomerBarCode_image_updated           =   true
                    this.clients[index].in_store_image_updated                  =   true
                }
            }

            this.updateSizeKey(client);
        },
    }
};

</script>

<style scoped>
.client-item {
    box-sizing: border-box; /* ensure padding is counted */
    padding-bottom: 30px; /* the gap you want */
}


.client-item .card {
    margin-bottom: 0; /* remove card default bottom margin so there is no duplicate spacing */
}
</style>