<template>

    <div class="p-3 m-2 h-100" style="background-color: #f2edf3; padding : 15px;">
        <!-- Filters            -->
        <div class="col-sm-12 p-2" id="stats_filters">
            <div class="card">
                <div class="card-body p-0">
                    <div class="row align-content-middle justify-content-center">

                        <!-- Title  -->
                        <!-- 
                            <div class="col-sm-6">
                                <h4 class="card-title mt-2">List of clients 'pending' of route import : {{ route_import.libelle }}</h4>
                            </div> 
                        -->
                        <!--        -->

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
            <div v-for="client, index in clients" :key="index" :id="'client_'+client.id" class="mb-2 shadow-sm rounded">
                <div class="card">
                    <div class="card-body p-0">

                        <div class="mb-5 text-center">
                            <u><h5 class="card-title mt-2">{{ client.CustomerIdentifier }} - {{ client.CustomerNameE }} ({{ client.created_at }})</h5></u>
                        </div>

                        <div class="row">
                            <div class="col-sm-5">
                                <div class="row mt-3 mb-3">
                                    <div class="col-sm-4">
                                        <div v-if="(client.NewCustomer   ==  'Client Existant')">
                                            <label for="CustomerIdentifier" class="form-label">ID Client</label>
                                            <input type="text"              class="form-control"        :id="'CustomerIdentifier_'+client.id"       v-model="client.CustomerIdentifier"                                 :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <label for="text"               class="form-label">Client Ouvert</label>
                                        <select                         class="form-select"             :id="'OpenCustomer_'+client.id"             v-model="client.OpenCustomer"           @change="setStatus(index)"  :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))">
                                            <option     value="Ouvert">Ouvert</option>
                                            <option     value="Ferme">Ferme</option>
                                            <option     value="refus">refus</option>
                                            <option     v-if="(client.NewCustomer   ==  'Client Existant')" value="Introuvable">Introuvable</option>
                                        </select>
                                    </div>

                                    <div class="col-sm-4">
                                        <label for="status"             class="form-label">Status</label>
                                        <select                         class="form-select"             :id="'status_'+client.id"                   v-model="client.status"                                             :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))">
                                            <option v-if="client.OpenCustomer   ==  'Ouvert'"           value="confirmed" selected>confirmed</option>
                                            <option v-if="client.OpenCustomer   ==  'Ouvert'"           value="validated">validated</option>
                                            <option v-if="client.OpenCustomer   ==  'Ouvert'"           value="pending">pending</option>
                                            <option v-if="client.OpenCustomer   ==  'Ouvert'"           value="nonvalidated">nonvalidated</option>
                                            <option v-if="client.OpenCustomer   ==  'Ouvert'"           value="visible">visible</option>

                                            <option v-if="client.OpenCustomer   ==  'Ferme'"            value="ferme">ferme</option>
                                            <option v-if="client.OpenCustomer   ==  'refus'"            value="refus">refus</option>
                                            <option v-if="client.OpenCustomer   ==  'Introuvable'"      value="introuvable">introuvable</option>
                                        </select>

                                        <div v-if="client.status    ==  'nonvalidated'" class="mt-3">
                                            <div class="form-group">
                                                <label      for="nonvalidated_details" class="form-label">NonValidated Details</label>
                                                <textarea   class="form-control"                        :id="'nonvalidated_details_'+client.id" rows="3"             v-model="client.nonvalidated_details"   :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!--  -->

                                <div class="row mt-3 mb-3">
                                    <div class="col-sm-3">
                                        <div v-if="client.OpenCustomer  === 'Ouvert'">
                                            <label for="text"               class="form-label">CustomerBarCodeExiste</label>
                                            <select                         class="form-select"         :id="'CustomerCode_'+client.id"                 v-model="client.CustomerBarCodeExiste"   :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))">
                                                <option     value="Non">Non</option>
                                                <option     value="Oui">Oui</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div v-if="client.OpenCustomer  === 'Ouvert'">
                                            <label for="CustomerCode"      class="form-label">Code-Barre</label>
                                            <input type="text"              class="form-control"        :id="'CustomerCode_'+client.id"               v-model="client.CustomerCode"   :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))">
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div v-if="client.OpenCustomer  === 'Ouvert'">
                                            <label for="CustomerNameE"      class="form-label">Nom et Prénom de l'Acheteur</label>
                                            <input type="text"              class="form-control"        :id="'CustomerNameE_'+client.id"        v-model="client.CustomerNameE"                          :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))">
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <label for="CustomerNameA"      class="form-label">Raison Sociale</label>
                                        <input type="text"              class="form-control"            :id="'CustomerNameA_'+client.id"        v-model="client.CustomerNameA"                          :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))">
                                    </div>
                                </div>

                                <!--  -->

                                <div class="row mt-3 mb-3">
                                    <div class="col-sm-4">
                                        <div v-if="(((client.NewCustomer === 'Nouveau Client')&&(client.OpenCustomer === 'Ouvert'))||(client.NewCustomer === 'Client Existant'))">
                                            <label for="Tel"            class="form-label">Téléphone</label>
                                            <input type="text"          class="form-control"            :id="'Tel_'+client.id"            v-model="client.Tel"                :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div v-if="(((client.NewCustomer === 'Nouveau Client')&&(client.OpenCustomer === 'Ouvert'))||(client.NewCustomer === 'Client Existant'))">
                                            <label for="tel_status"         class="form-label">Téléphone Status</label>
                                            <select                         class="form-select"         :id="'tel_status_'+client.id"     v-model="client.tel_status"         :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))">
                                                <option value="validated" selected>validated</option>
                                                <option value="pending">pending</option>
                                                <option value="nonvalidated">nonvalidated</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div v-if="client.tel_status    ==  'nonvalidated'" class="col-sm-4">
                                        <div v-if="(((client.NewCustomer === 'Nouveau Client')&&(client.OpenCustomer === 'Ouvert'))||(client.NewCustomer === 'Client Existant'))">
                                            <label      for="tel_comment">Téléphone Commentaire</label>
                                            <textarea   class="form-control"                            :id="'tel_comment_'+client.id"    rows="3"                v-model="client.tel_comment"        :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <!--  -->

                                <div class="row mt-3 mb-3">
                                    <div class="col-sm-4">
                                        <label for="text"               class="form-label">Type de Magasin</label>
                                        <select                         class="form-select"             :id="'CustomerType_'+client.id"                 v-model="client.CustomerType"                     :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))">
                                            <option     :value="'Alimentation Generale'">Alimentation Generale</option>
                                            <option     :value="'Petit Superette'">Petit Superette</option>
                                            <option     :value="'Grande Superette'">Grande Superette</option>
                                            <option     :value="'HyperMarché'">HyperMarché</option>
                                            <option     :value="'Special Detergent'">Special Detergent</option>
                                        </select>
                                    </div>

                                    <div class="col-sm-4">
                                        <div v-if="(client.OpenCustomer  === 'Ouvert')">
                                            <label for="text"               class="form-label">Nombre de Vitrines</label>
                                            <select                         class="form-select"         :id="'NbrVitrines_'+client.id"                 v-model="client.NbrVitrines"           :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))">
                                                <option     value="1">1</option>
                                                <option     value="2">2</option>
                                                <option     value="3">3</option>
                                                <option     value="4">4</option>
                                                <option     value="5">5</option>
                                                <option     value="6">6</option>
                                                <option     value="7">7</option>
                                                <option     value="8">8</option>
                                                <option     value="9">9</option>
                                                <option     value="10">10</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div v-if="client.OpenCustomer  === 'Ouvert'">
                                            <label for="NbrAutomaticCheckouts"  class="form-label">Nombre de caisses automatique</label>
                                            <select                             class="form-select"         :id="'NbrAutomaticCheckouts_'+client.id"          v-model="client.NbrAutomaticCheckouts"  :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))">
                                                <option     :value="'Plus de 1'">Plus de 1</option>
                                                <option     :value="'1'">1</option>
                                                <option     :value="'Pas de caisse automatique'">Pas de caisse automatique</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-3 mb-3">
                                    <div class="col-sm-3">
                                        <div v-if="client.OpenCustomer  === 'Ouvert'">
                                            <!-- <label for="text"               class="form-label">Source d'Achat</label>
                                            <select                         class="form-select"         id="BrandSourcePurchase"                 v-model="client.BrandSourcePurchase"   :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))">
                                                <option     value="DD">DD</option>
                                                <option     value="DI">DI</option>
                                                <option     value="Pas d'achat">Pas d'achat</option>
                                            </select> -->
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div v-if="client.OpenCustomer  === 'Ouvert'">
                                            <!-- <label for="text"               class="form-label">Disponibilité Produits</label>
                                            <select                         class="form-select"         id="BrandAvailability"              v-model="client.BrandAvailability"      :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))">
                                                <option     value="Non">No</option>
                                                <option     value="Oui">Yes</option>
                                            </select> -->
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <!-- <label for="JPlan"      class="form-label">JPlan</label>
                                        <Multiselect
                                            v-model             =   "client.JPlan"
                                            :options            =   "salesmen"
                                            mode                =   "single" 
                                            placeholder         =   "Select Salesman"
                                            class               =   "mt-1"
                                            id                  =   "JPlan"

                                            :close-on-select    =   "true"
                                            :searchable         =   "true"
                                            :create-option      =   "false"

                                            :canDeselect        =   "true"
                                            :canClear           =   "true"
                                            :allowAbsent        =   "false"

                                            :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))"
                                        /> -->
                                    </div>

                                    <div class="col-sm-3">
                                        <!-- <label for="Journee"            class="form-label">Journee</label>
                                        <select                         class="form-select"         id="Journee"                v-model="client.Journee"                    :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))">
                                            <option     :value="'Samedi 1'">Samedi 1</option>
                                            <option     :value="'Dimanche 1'">Dimanche 1</option>
                                            <option     :value="'Lundi 1'">Lundi 1</option>
                                            <option     :value="'Mardi 1'">Mardi 1</option>
                                            <option     :value="'Mercredi 1'">Mercredi 1</option>
                                            <option     :value="'Jeudi 1'">Jeudi 1</option>
                                            <option     :value="'Samedi 2'">Samedi 2</option>
                                            <option     :value="'Dimanche 2'">Dimanche 2</option>
                                            <option     :value="'Lundi 2'">Lundi 2</option>
                                            <option     :value="'Mardi 2'">Mardi 2</option>
                                            <option     :value="'Mercredi 2'">Mercredi 2</option>
                                            <option     :value="'Jeudi 2'">Jeudi 2</option>
                                        </select> -->
                                    </div>
                                </div>

                                <div class="row mt-3 mb-3">
                                    <div class="col-sm-4">
                                        <div v-if="client.OpenCustomer  === 'Ouvert'">
                                            <label for="SuperficieMagasin"  class="form-label">Superficie du magasin</label>

                                            <select                         class="form-select"         :id="'SuperficieMagasin_'+client.id"              v-model="client.SuperficieMagasin"      :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))">
                                                <option     :value="'Moins de 20 M'">Moins de 20 Metres</option>
                                                <option     :value="'DE 20 a 50'">DE 20 a 50 Metres</option>
                                                <option     :value="'DE 50 a 100'">DE 50 a 100 Metres</option>
                                                <option     :value="'Plus de 100'">Plus de 100 Metres</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <label for="DistrictNo"         class="form-label">Willaya</label>
                                        <select                         class="form-select"             :id="'DistrictNo_'+client.id"             v-model="client.DistrictNo"     :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))">
                                            <option v-for="willaya in willayas" :key="willaya.DistrictNo" :value="willaya.DistrictNo">{{willaya.DistrictNo}}- {{willaya.DistrictNameE}}</option>
                                        </select>
                                    </div>

                                    <div class="col-sm-4">
                                        <label for="CityNo"             class="form-label">Commune</label>
                                        <select                         class="form-select"             :id="'CityNo_'+client.id"                 v-model="client.CityNo"                                 :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))">
                                            <option v-for="cite in cites" :key="cite.CITYNO" :value="cite.CITYNO">{{cite.CITYNO}}- {{cite.CityNameE}}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mt-3 mb-3">
                                    <div class="col-sm-4">
                                        <label for="Address"            class="form-label">Adresse</label>
                                        <input type="text"              class="form-control"            :id="'Address_'+client.id"                v-model="client.Address"                                :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))">
                                    </div>

                                    <div class="col-sm-4">
                                        <label for="Neighborhood"       class="form-label">Quartier</label>
                                        <input type="text"              class="form-control"        :id="'Neighborhood_'+client.id"           v-model="client.Neighborhood"                           :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))">
                                    </div>

                                    <div class="col-sm-4">
                                        <label for="Landmark"           class="form-label">Point de Repere</label>
                                        <textarea                       class="form-control"        :id="'Landmark_'+client.id"   rows="3"    v-model="client.Landmark"                               :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-7">
                                <div class="row">
                                    <div v-if="client.OpenCustomer  === 'Ouvert'"   class="col">
                                        <label for="CustomerBarCode_image_update"   class="form-label">Image Code-Barre :</label>
                                        <button type="button"                       class="btn btn-secondary w-100 mb-1"    @click="$clickFile('CustomerBarCode_image_update_'+client.id)"                                                              :disabled="((client.status_original    ==  'confirmed')||(client.status_original    ==  'validated'))"><i class="mdi mdi-camera"></i></button>

                                        <input type="file"                          class="form-control"        :id="'CustomerBarCode_image_update_'+client.id"                       accept="image/*"    capture     @change="customerBarCodeImage(index)"         style="display:none"    :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))">
                                        <img                                        class="mt-1 w-100"          :id="'CustomerBarCode_image_display_update_'+client.id"               :src="'/uploads/clients/'+client.id+'/'+client.CustomerBarCode_image">
                                    </div>

                                    <div class="col">
                                        <label for="facade_image_update"            class="form-label">Image Facade :</label>
                                        <button type="button"                       class="btn btn-secondary w-100 mb-1"    @click="$clickFile('facade_image_update_'+client.id)"                                                                           :disabled="((client.status_original    ==  'confirmed')||(client.status_original    ==  'validated'))"><i class="mdi mdi-camera"></i></button>

                                        <input type="file"                          class="form-control"        :id="'facade_image_update_'+client.id"                      accept="image/*"    @change="facadeImage(index)"                                style="display:none"    :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))">
                                        <img                                        class="mt-1 w-100"          :id="'facade_image_display_update_'+client.id"              :src="'/uploads/clients/'+client.id+'/'+client.facade_image">
                                    </div>
                                </div>

                                <div class="row">
                                    <div v-if="client.OpenCustomer  === 'Ouvert'"   class="col">
                                        <label for="in_store_image_update"          class="form-label">Image In-Store :</label>
                                        <button type="button"                       class="btn btn-secondary w-100 mb-1"    @click="$clickFile('in_store_image_update_'+client.id)"                                                                         :disabled="((client.status_original    ==  'confirmed')||(client.status_original    ==  'validated'))"><i class="mdi mdi-camera"></i></button>

                                        <input type="file"                          class="form-control"        :id="'in_store_image_update_'+client.id"                    accept="image/*"    @change="inStoreImage(index)"                               style="display:none"    :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))">
                                        <img                                        class="mt-1 w-100"          :id="'in_store_image_display_update_'+client.id"            :src="'/uploads/clients/'+client.id+'/'+client.in_store_image">
                                    </div>

                                    <div v-if="client.OpenCustomer  === 'Ouvert'"   class="col">
                                        <label for="CustomerBarCodeExiste_image_update"          class="form-label">Image CustomerBarCodeExiste :</label>
                                        <button type="button"                       class="btn btn-secondary w-100 mb-1"    @click="$clickFile('CustomerBarCodeExiste_image_update_'+client.id)"                                                                         :disabled="((client.status_original    ==  'confirmed')||(client.status_original    ==  'validated'))"><i class="mdi mdi-camera"></i></button>

                                        <input type="file"                          class="form-control"        :id="'CustomerBarCodeExiste_image_update_'+client.id"                    accept="image/*"    @change="customerBarCodeExisteImage(index)"                               style="display:none"    :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))">
                                        <img                                        class="mt-1 w-100"          :id="'CustomerBarCodeExiste_image_display_update_'+client.id"            :src="'/uploads/clients/'+client.id+'/'+client.CustomerBarCodeExiste_image">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="row mt-3 mb-3">
                                <div class="col-sm-2">
                                    <label for="Latitude"           class="form-label">Latitude (Latitude)</label>
                                    <input type="text"              class="form-control"        id="Latitude"               v-model="client.Latitude"   :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))">
                                </div>

                                <div class="col-sm-2">
                                    <label for="Longitude"          class="form-label">Longitude (Longitude)</label>
                                    <input type="text"              class="form-control"        id="Longitude"              v-model="client.Longitude"  :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))">
                                </div>

                                <div class="col-sm-2">
                                    <label for="owner"          class="form-label">Owner</label>
                                    <select                     class="form-select"     id="owner"          v-model="client.owner">
                                        <option value=""></option>
                                        <option v-for="user in users"                   :key="user.id"      :value="user.id">{{user.username}}</option>
                                    </select>
                                </div>

                                <div class="col-sm-4">
                                    <label      for="comment">Commentaire</label>
                                    <textarea   class="form-control"    id="comment"    rows="3"    v-model="client.comment"    :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))"></textarea>
                                </div>

                                <div class="col-sm-2">
                                    <button type="button"           class="btn btn-success w-100"   style="margin-top: 30px;"   @click="sendData(index)">Validate</button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!--                    -->
    </div>

</template>

<script>

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
            selected_NbrVitrines  :   []  ,
            selected_SuperficieMagasins :   []  
        }
    },

    async mounted() {
    },

    beforeUnmount() {

        if(this.scanner) {

            this.scanner.clear().then(_ => {

            }).catch(error => {

            });
        }
    },

    components: {
        Multiselect,
    },

    methods : {

        async sendData(index) {

            this.$showLoadingPage()

            // Set Client
            let client              =   this.clients[index]

            client.DistrictNameE    =   this.getDistrictNameE(client.DistrictNo)
            client.CityNameE        =   this.getCityNameE(client.CityNo)
            client.owner_name       =   this.getOwnerName(client.owner)

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

        async customerBarCodeExisteImage(index) {

            const CustomerBarCodeExiste_image  =   document.getElementById("CustomerBarCodeExiste_image_update_"+this.clients[index].id).files[0];

            if(CustomerBarCodeExiste_image) {

                this.clients[index].CustomerBarCodeExiste_image_updated            =   true

                this.clients[index].CustomerBarCodeExiste_image_original_name      =   CustomerBarCodeExiste_image.name
                this.clients[index].CustomerBarCodeExiste_image                    =   await this.$compressImage(CustomerBarCodeExiste_image)

                //

                let CustomerBarCodeExiste_image_base64                            =   await this.$imageToBase64(this.clients[index].CustomerBarCodeExiste_image)

                let CustomerBarCodeExiste_image_display                           =   document.getElementById("CustomerBarCodeExiste_image_display_update_"+this.clients[index].id)
                this.base64ToImage(CustomerBarCodeExiste_image_base64, CustomerBarCodeExiste_image_display)
            }

            else {

                this.clients[index].CustomerBarCodeExiste_image_original_name     =   ""
                this.clients[index].CustomerBarCodeExiste_image                   =   ""

                this.clients[index].CustomerBarCodeExiste_image_updated           =   true

                const CustomerBarCodeExiste_image_display_update                  =   document.getElementById("CustomerBarCodeExiste_image_display_update_"+this.clients[index].id)

                if(CustomerBarCodeExiste_image_display_update) {

                    CustomerBarCodeExiste_image_display_update.src                    =   ""
                }
            }
        },

        async customerBarCodeImage(index) {

            const CustomerBarCode_image  =   document.getElementById("CustomerBarCode_image_update_"+this.clients[index].id).files[0];

            if(CustomerBarCode_image) {

                this.clients[index].CustomerBarCode_image_updated            =   true

                this.clients[index].CustomerBarCode_image_original_name      =   CustomerBarCode_image.name
                this.clients[index].CustomerBarCode_image                    =   await this.$compressImage(CustomerBarCode_image)

                //

                let CustomerBarCode_image_base64                            =   await this.$imageToBase64(this.clients[index].CustomerBarCode_image)

                let CustomerBarCode_image_display                           =   document.getElementById("CustomerBarCode_image_display_update_"+this.clients[index].id)
                this.base64ToImage(CustomerBarCode_image_base64, CustomerBarCode_image_display)
            }

            else {

                this.clients[index].CustomerBarCode_image_original_name     =   ""
                this.clients[index].CustomerBarCode_image                   =   ""

                this.clients[index].CustomerBarCode_image_updated           =   true

                const CustomerBarCode_image_display_update                  =   document.getElementById("CustomerBarCode_image_display_update_"+this.clients[index].id)

                if(CustomerBarCode_image_display_update) {

                    CustomerBarCode_image_display_update.src                    =   ""
                }
            }
        },

        async facadeImage(index) {

            const facade_image  =   document.getElementById("facade_image_update_"+this.clients[index].id).files[0];

            if(facade_image) {

                this.clients[index].facade_image_updated            =   true

                this.clients[index].facade_image_original_name      =   facade_image.name
                this.clients[index].facade_image                    =   await this.$compressImage(facade_image)

                //

                let facade_image_base64                             =   await this.$imageToBase64(this.clients[index].facade_image)

                let facade_image_display                            =   document.getElementById("facade_image_display_update_"+this.clients[index].id)
                this.base64ToImage(facade_image_base64, facade_image_display)
            }

            else {

                this.clients[index].facade_image_original_name      =   ""
                this.clients[index].facade_image                    =   ""

                this.clients[index].facade_image_updated            =   true

                const facade_image_display_update                   =   document.getElementById("facade_image_display_update_"+this.clients[index].id)

                if(facade_image_display_update) {

                    facade_image_display_update.src                     =   ""
                }
            }
        },

        async inStoreImage(index) {

            const in_store_image  =   document.getElementById("in_store_image_update_"+this.clients[index].id).files[0];

            if(in_store_image) {

                this.clients[index].in_store_image_updated          =   true

                this.clients[index].in_store_image_original_name    =   in_store_image.name
                this.clients[index].in_store_image                  =   await this.$compressImage(in_store_image)
                
                //

                let in_store_image_base64                           =   await this.$imageToBase64(this.clients[index].in_store_image)

                let in_store_image_display                          =   document.getElementById("in_store_image_display_update_"+this.clients[index].id)
                this.base64ToImage(in_store_image_base64, in_store_image_display)
            }

            else {

                this.clients[index].in_store_image_original_name    =   ""
                this.clients[index].in_store_image                  =   ""

                this.clients[index].in_store_image_updated          =   true

                const in_store_image_display_update                 =   document.getElementById("in_store_image_display_update_"+this.clients[index].id)

                if(in_store_image_display_update) {

                    in_store_image_display_update.src                   =   ""
                }
            }
        },

        base64ToImage(image_base64, image_display_div) {

            this.$base64ToImage(image_base64, image_display_div)
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

        getOwnerName(owner) {

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
                this.$callApi("post",   "/route_import/"+this.$route.params.id_route_import+"/clients",   formData)
                .then(async (res)=> {

                    console.log(this.$route.params.id_route_import)
                    console.log(res)

                    this.route_import   =   res.data.route_import
                    this.clients        =   res.data.clients

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
        },
    }
};

</script>