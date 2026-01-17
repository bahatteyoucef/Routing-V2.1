<template>

    <!-- Modal -->
    <div class="modal fade" id="ModalClientUpdate" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl expanded_modal modal-dialog-scrollable">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Update the Client : {{client.old_CustomerNameE}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body mt-3">

                    <form>
                        <div class="row">
                            <div class="col-sm-9">
                                <div class="row mt-3 mb-3">
                                    <div class="col-sm-4">
                                        <label for="CustomerIdentifier" class="form-label">ID Client</label>
                                        <input type="text"              class="form-control"        id="CustomerIdentifier"     v-model="client.CustomerIdentifier"                             :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))">
                                    </div>

                                    <div class="col-sm-4">
                                        <label for="text"               class="form-label">Client Ouvert</label>
                                        <select                         class="form-select"         id="OpenCustomer"           v-model="client.OpenCustomer"           @change="setStatus()"   :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))">
                                            <option     value='Ferme'>Ferme</option>
                                            <option     value='Ouvert'>Ouvert</option>
                                        </select>
                                    </div>

                                    <div class="col-sm-4">
                                        <label for="status"             class="form-label">Status</label>
                                        <select                         class="form-select"         id="status"                 v-model="client.status"                 :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))">
                                            <option v-if="client.OpenCustomer   ==  'Ouvert'"   value="confirmed" selected>confirmed</option>
                                            <option v-if="client.OpenCustomer   ==  'Ouvert'"   value="validated">validated</option>
                                            <option v-if="client.OpenCustomer   ==  'Ouvert'"   value="pending">pending</option>
                                            <option v-if="client.OpenCustomer   ==  'Ouvert'"   value="nonvalidated">nonvalidated</option>
                                            <option v-if="client.OpenCustomer   ==  'Ouvert'"   value="visible">visible</option>

                                            <option v-if="client.OpenCustomer   ==  'Ferme'"            value="ferme">ferme</option>
                                            <option v-if="client.OpenCustomer   ==  'refus'"            value="refus">refus</option>
                                            <option v-if="client.OpenCustomer   ==  'Introuvable'"      value="introuvable">introuvable</option>
                                        </select>

                                        <div v-if="client.status    ==  'nonvalidated'" class="mt-3">
                                            <div class="form-group">
                                                <label      for="nonvalidated_details" class="form-label">NonValidated Details</label>
                                                <textarea   class="form-control" id="nonvalidated_details" rows="3"             v-model="client.nonvalidated_details"   :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!--  -->

                                <div class="row mt-3 mb-3">
                                    <div class="col-sm-4">
                                        <label for="NewCustomer"        class="form-label">Nouveau Client</label>
                                        <select                         class="form-select"         id="NewCustomer"                 v-model="client.NewCustomer"                       :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))">
                                            <option     value="Nouveau Client">Nouveau Client</option>
                                            <option     value="Client Existant">Client Existant</option>
                                        </select>
                                    </div>

                                    <div class="col-sm-4">
                                        <div v-if="client.OpenCustomer  === 'Ouvert'">
                                            <label for="CustomerNameE"      class="form-label">Nom et Prénom de l'Acheteur</label>
                                            <input type="text"              class="form-control"        id="CustomerNameE"          v-model="client.CustomerNameE"                          :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <label for="CustomerNameA"      class="form-label">Raison Sociale</label>
                                        <input type="text"              class="form-control"        id="CustomerNameA"          v-model="client.CustomerNameA"                          :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))">
                                    </div>
                                </div>

                                <!--  -->

                                <div class="row mt-3 mb-3">
                                    <div class="col-sm-4">
                                        <div v-if="client.OpenCustomer  === 'Ouvert'">
                                            <label for="Tel"            class="form-label">Téléphone</label>
                                            <input type="text"          class="form-control"        id="Tel"            v-model="client.Tel"                :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <label for="tel_status"         class="form-label">Téléphone Status</label>
                                        <select                         class="form-select"         id="tel_status"     v-model="client.tel_status"         :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))">
                                            <option value="validated" selected>validated</option>
                                            <option value="pending">pending</option>
                                            <option value="nonvalidated">nonvalidated</option>
                                        </select>
                                    </div>

                                    <div v-if="client.tel_status    ==  'nonvalidated'" class="col-sm-4">
                                        <label      for="tel_comment">Téléphone Commentaire</label>
                                        <textarea   class="form-control"    id="tel_comment"    rows="3"                v-model="client.tel_comment"        :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))"></textarea>
                                    </div>
                                </div>

                                <!--  -->

                                <div class="row mt-3 mb-3">
                                    <div class="col-sm-3">
                                        <label for="text"               class="form-label">Type de Magasin</label>
                                        <select                         class="form-select"         id="CustomerType"                 v-model="client.CustomerType"                     :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))">
                                            <option     :value="'AG Self'">AG Self</option>
                                            <option     :value="'AG Tradi'">AG Tradi</option>
                                            <option     :value="'Superette'">Superette</option>

                                            <option     :value="'Gros Mix'">Gros Mix</option>
                                            <option     :value="'Gros Sec'">Gros Sec</option>
                                            <option     :value="'Gros Frais'">Gros Frais</option>

                                            <option     :value="'Demis Gros Mix'">Demis Gros Mix</option>
                                            <option     :value="'Demis Gros Sec'">Demis Gros Sec</option>
                                            <option     :value="'Demis Gros Frais'">Demis Gros Frais</option>

                                            <option     :value="'Produit laitier et lben'">Produit laitier et lben</option>
                                            <option     :value="'Produit Patisseries'">Produit Patisseries</option>

                                            <option     :value="'Fast Food'">Fast Food</option>
                                            <option     :value="'Restaurant'">Restaurant</option>
                                            <option     :value="'Cafeteria'">Cafeteria</option>
                                            <option     :value="'HyperMarché'">HyperMarché</option>
                                        </select>
                                    </div>

                                    <div class="col-sm-3">
                                        <div v-if="client.OpenCustomer  === 'Ouvert'">
                                            <label for="SuperficieMagasin"  class="form-label">Superficie du magasin</label>

                                            <select                         class="form-select"         id="SuperficieMagasin"              v-model="client.SuperficieMagasin"      :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))">
                                                <option     :value="'Moins de 30 M'">Moins de 30 M</option>
                                                <option     :value="'DE 30 a 60'">DE 30 a 60</option>
                                                <option     :value="'DE 30 a 100'">DE 30 a 100</option>
                                                <option     :value="'DE 100 a 200'">DE 100 a 200</option>
                                                <option     :value="'Plus de 200'">Plus de 200</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div v-if="client.OpenCustomer  === 'Ouvert'" class="col-sm-3">
                                        <label for="text"               class="form-label">Source d'Achat</label>
                                        <select                         class="form-select"         id="BrandSourcePurchase"                 v-model="client.BrandSourcePurchase"   :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))">
                                            <option     value="DD">DD</option>
                                            <option     value="DI">DI</option>
                                            <option     value="Pas d'achat">Pas d'achat</option>
                                        </select>
                                    </div>

                                    <div v-if="client.OpenCustomer  === 'Ouvert'" class="col-sm-3">
                                        <label for="NbrAutomaticCheckouts"  class="form-label">Nombre de caisses automatique</label>
                                        <select                             class="form-select"         id="NbrAutomaticCheckouts"      v-model="client.NbrAutomaticCheckouts"  :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))">
                                            <option     :value="'Plus de 1'">Plus de 1</option>
                                            <option     :value="'1'">1</option>
                                            <option     :value="'Pas de caisse automatique'">Pas de caisse automatique</option>
                                        </select>
                                    </div>
                                </div>

                                <!--  -->

                                <div class="row mt-3 mb-3">
                                    <div class="col-sm-4">
                                        <label for="JPlan"              class="form-label">Nom de Vendeur</label>
                                        <input type="text"              class="form-control"        id="JPlan"                  v-model="client.JPlan"                      :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))">
                                    </div>

                                    <div class="col-sm-4">
                                        <label for="Frequency"          class="form-label">Frequency</label>

                                        <select                         class="form-select"         id="Frequency"              v-model="client.Frequency"                  :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))">
                                            <option     :value="'1 par Semaine(7J)'">1 par Semaine(7J)</option>
                                            <option     :value="'1 par 15J'">1 par 15J</option>
                                        </select>
                                    </div>

                                    <div class="col-sm-4">
                                        <label for="Journee"            class="form-label">Journee</label>
                                        <input type="text"              class="form-control"        id="Journee"                  v-model="client.Journee"                  :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))">
                                    </div>
                                </div>

                                <!--  -->

                                <div class="row mt-3 mb-3">
                                    <div class="col-sm-4">
                                        <div v-if="client.OpenCustomer  === 'Ouvert'">
                                            <label for="text"               class="form-label">Disponibilité Produits</label>
                                            <select                         class="form-select"         id="BrandAvailability"          v-model="client.BrandAvailability"  @change="brandAvailabilityChanged()"    :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))">
                                                <option     value="Non">No</option>
                                                <option     value="Oui">Yes</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-8">
                                        <div v-if="client.OpenCustomer  === 'Ouvert'" v-show="client.BrandAvailability  ==  'Oui'" class="row">
                                            <div>
                                                <label for="text"               class="form-label">Produits</label>
                                            </div>

                                            <div class="form-check col-sm-4">
                                                <input class="form-check-input" type="checkbox" value="Loya Lait" id="Loya Lait"  v-model="client.AvailableBrands"      :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))">
                                                <label class="form-check-label" for="Loya Lait">
                                                    Loya Lait
                                                </label>
                                            </div>

                                            <div class="form-check col-sm-4">
                                                <input class="form-check-input" type="checkbox" value="Loya Fromage" id="Loya Fromage"  v-model="client.AvailableBrands"        :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))">
                                                <label class="form-check-label" for="Loya Fromage">
                                                    Loya Fromage
                                                </label>
                                            </div>

                                            <div class="form-check col-sm-4">
                                                <input class="form-check-input" type="checkbox" value="Berbere" id="Berbere"  v-model="client.AvailableBrands"      :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))">
                                                <label class="form-check-label" for="Berbere">
                                                    Berbere
                                                </label>
                                            </div>

                                            <div class="form-check col-sm-4">
                                                <input class="form-check-input" type="checkbox" value="Cowbell" id="Cowbell"  v-model="client.AvailableBrands"      :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))">
                                                <label class="form-check-label" for="Cowbell">
                                                    Cowbell
                                                </label>
                                            </div>

                                            <div class="form-check col-sm-4">
                                                <input class="form-check-input" type="checkbox" value="Twisco" id="Twisco"  v-model="client.AvailableBrands"        :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))">
                                                <label class="form-check-label" for="Twisco">
                                                    Twisco
                                                </label>
                                            </div>

                                            <div class="form-check col-sm-4">
                                                <input class="form-check-input" type="checkbox" value="PromaCafé" id="PromaCafé"  v-model="client.AvailableBrands"  :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))">
                                                <label class="form-check-label" for="PromaCafé">
                                                    PromaCafé
                                                </label>
                                            </div>

                                            <div class="form-check col-sm-4">
                                                <input class="form-check-input" type="checkbox" value="Amila" id="Amila"  v-model="client.AvailableBrands"          :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))">
                                                <label class="form-check-label" for="Amila">
                                                    Amila
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!--  -->

                                <div class="row mt-3 mb-3">
                                    <div class="col-sm-6">
                                        <label for="DistrictNo"         class="form-label">Willaya</label>
                                        <select                         class="form-select"         id="DistrictNo"             v-model="client.DistrictNo"     @change="getCites()"    :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))">
                                            <option v-for="willaya in willayas" :key="willaya.DistrictNo" :value="willaya.DistrictNo">{{willaya.DistrictNameE}} ({{willaya.DistrictNo}})</option>
                                        </select>
                                    </div>

                                    <div class="col-sm-6">
                                        <label for="CityNo"             class="form-label">Commune</label>
                                        <select                         class="form-select"         id="CityNo"                 v-model="client.CityNo"                                 :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))">
                                            <option v-for="city in cities" :key="city.CityNo" :value="city.CityNo">{{city.CityNameE}} ({{city.CityNo}})</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mt-3 mb-3">
                                    <div class="col-sm-3">
                                        <label for="RvrsGeoAddress"     class="form-label">RvrsGeoAddress</label>
                                        <textarea                       class="form-control"        :id="'RvrsGeoAddress_'+client.id"   rows="3"    v-model="client.RvrsGeoAddress"     :disabled="(true)||!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))"></textarea>
                                    </div>

                                    <div class="col-sm-3">
                                        <label for="Address"            class="form-label">Adresse</label>
                                        <input type="text"              class="form-control"        id="Address"                v-model="client.Address"                                :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))">
                                    </div>

                                    <div class="col-sm-3">
                                        <label for="Neighborhood"       class="form-label">Quartier</label>
                                        <input type="text"              class="form-control"        id="Neighborhood"           v-model="client.Neighborhood"                           :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))">
                                    </div>

                                    <div class="col-sm-3">
                                        <label for="Landmark"           class="form-label">Point de Repere</label>
                                        <textarea                       class="form-control"        id="Landmark"   rows="3"    v-model="client.Landmark"                               :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))"></textarea>
                                    </div>
                                </div>

                                <!--  -->

                                <div class="row mt-3 mb-3">
                                    <div class="col-sm-4">
                                        <label for="Latitude"           class="form-label">Latitude (Latitude)</label>
                                        <input type="text"              class="form-control"        id="Latitude"               v-model="client.Latitude"   :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))">
                                    </div>

                                    <div class="col-sm-4">
                                        <label for="Longitude"          class="form-label">Longitude (Longitude)</label>
                                        <input type="text"              class="form-control"        id="Longitude"              v-model="client.Longitude"  :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))">
                                    </div>

                                    <div class="col-sm-2 mt-auto">
                                        <button type="button"           class="btn btn-primary w-100"   @click="getRvrsGeoAddress()"                                            :disabled="((!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice'))))||(check_gps_clicked))">Get RvrsAddress</button>
                                    </div>

                                    <div class="col-sm-2 mt-auto">
                                        <button type="button"           class="btn btn-primary w-100"   @click="showPositionOnMapMultiMap('show_modal_client_update_map')"      :disabled="((!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice'))))||(check_gps_clicked))">Show Position <i class="mdi mdi-crosshairs-gps"></i></button>
                                    </div>
                                </div>

                                <div class="row mt-3 mb-3">
                                    <div id="show_modal_client_update_map" style="width: 100%; height: 200px;"></div>
                                </div>

                                <!--  -->

                                <hr class="mt-5 mb-5"/>

                                <div class="row mt-3 mb-3">
                                    <div class="col-sm-12">
                                        <h5><b><u>Clients à proximité</u></b></h5>

                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th class="text-wrap">CustomerNameE</th>
                                                    <th class="text-wrap">CustomerNameA</th>
                                                    <th class="text-wrap">CustomerType</th>
                                                </tr>
                                            </thead>
                                            
                                            <tbody>
                                                <tr v-for="client in close_clients" :key="client">
                                                    <td class="text-wrap">{{client.CustomerNameE}}</td>
                                                    <td class="text-wrap">{{client.CustomerNameA}}</td>
                                                    <td class="text-wrap">{{client.CustomerType}}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!--  -->

                                <div class="row mt-3 mb-3">
                                    <div class="col-sm-3">
                                        <label for="owner"          class="form-label">Owner</label>
                                        <select                     class="form-select"     id="owner"          v-model="client.owner">
                                            <option value=""></option>
                                            <option v-for="user in users"                   :key="user.id"      :value="user.id">{{user.username}}</option>
                                        </select>
                                    </div>

                                    <div class="col-sm-9">
                                        <label      for="comment">Commentaire</label>
                                        <textarea   class="form-control"    id="comment"    rows="3"    v-model="client.comment"    :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="row mt-3 mb-3">
                                    <div v-if="client.OpenCustomer  === 'Ouvert'" class="col-sm-12">
                                        <div v-show="client.CustomerCode   ==  ''"     class="mt-1 p-0">
                                            <div    id="reader_update" class="scanner_reader w-100"></div>
                                        </div>

                                        <div class="mt-1 p-0">
                                            <div    id="customerCode_value"             class="text-center">
                                                <label for="CustomerCode"       class="form-label">Code-Barre</label>
                                                <input type="text"              class="form-control"        id="CustomerCode"               v-model="client.CustomerCode"   :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))">
                                            </div>
                                        </div>

                                        <!--  -->

                                        <div class="mt-3 mb-3 w-100">
                                            <div class="w-100" id="refresh_client_barcode_button">
                                                <button type="button" class="btn btn-primary w-100"     @click="setBarCodeReader()"     :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))">Capturer Code-Barre</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>                        

                                <div v-if="mode === 'permanent'" class="row mt-3 mb-3">
                                    <div v-if="client.OpenCustomer  === 'Ouvert'" class="col-sm-12">
                                        <label for="CustomerBarCode_image_update"   class="form-label">Image Code-Barre</label>
                                        <input type="file"                          class="form-control"        id="CustomerBarCode_image_update"       accept="image/*"    capture     @change="customerBarCodeImage()"     :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))">

                                        <div class="img-magnifier-container" id="CustomerBarCode_image_display_update_container" style="display: none">
                                            <img
                                                id="CustomerBarCode_image_display_update"
                                                class="w-100"
                                                @load="$magnify('CustomerBarCode_image_display_update', 3, 'CustomerBarCode_image_display_update_container')"
                                            />
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <label for="facade_image_update"    class="form-label">Image Facade</label>
                                        <input type="file"                  class="form-control"    id="facade_image_update"                            accept="image/*"    @change="facadeImage()"                                              :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))">

                                        <div class="img-magnifier-container" id="facade_image_display_update_container" style="display: none">
                                            <img
                                                id="facade_image_display_update"
                                                class="w-100"
                                                @load="$magnify('facade_image_display_update', 3, 'facade_image_display_update_container')"
                                            />
                                        </div>
                                    </div>

                                    <div v-if="client.OpenCustomer  === 'Ouvert'" class="col-sm-12">
                                        <label for="in_store_image_update"  class="form-label">Image In-Store</label>
                                        <input type="file"                  class="form-control"    id="in_store_image_update"             accept="image/*"    @change="inStoreImage()"                                             :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))">

                                        <div class="img-magnifier-container" id="in_store_image_display_update_container" style="display: none">
                                            <img
                                                id="in_store_image_display_update"
                                                class="w-100"
                                                @load="$magnify('in_store_image_display_update', 3, 'in_store_image_display_update_container')"
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>

                <div v-if="((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))"   class="modal-footer"    style="display: flex; justify-content: space-between;">
                    <div class="left-buttons"   style="display: flex;">
                        <button type="button"   class="btn btn-danger float-left" @click="deleteData()">Delete</button>
                    </div>

                    <div class="right-buttons"  style="display: flex; margin-left: auto;">
                        <button type="button"   class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button"   class="btn btn-primary"                             @click="sendData()"                                                 >Confirm</button>
                    </div>
                </div>

            </div>
        </div>
    </div>

</template>

<script>

import {mapGetters, mapActions} from    "vuex"

import emitter                  from    "@/utils/emitter"

import moment                   from    "moment"
import "moment-timezone"

export default {

    data() {
        return {

            client      :   {

                id                                      :   '',

                //
                id_route_import                         :   '',

                //
                NewCustomer                             :   '',

                //
                OpenCustomer                            :   '',

                // Slide 1
                CustomerIdentifier                      :   '',                

                // Slide 2
                CustomerCode                            :   '',

                // Slide 3
                CustomerBarCode_image                   :   '',
                CustomerBarCode_image_original_name     :   '',

                // Slide 4
                old_CustomerNameE                       :   '',
                CustomerNameE                           :   '',

                // Slide 5
                CustomerNameA                           :   '',

                // Slide 6
                Tel                                     :   '',
                tel_status                              :   'pending',
                tel_comment                             :   '',

                // Slide 7
                Address                                 :   '',
                RvrsGeoAddress                          :   '',

                // Slide 8
                Neighborhood                            :   '',

                // Slide 9
                Landmark                                :   '',

                // Slide 10
                DistrictNo                              :   '',
                DistrictNameE                           :   '',

                // Slide 11
                CityNo                                  :   '',
                CityNameE                               :   '',

                // Slide 12
                CustomerType                            :   '',

                // Slide 13
                BrandSourcePurchase                     :   '',

                // Slide 14
                NbrAutomaticCheckouts                   :   '',

                // Slide 15
                SuperficieMagasin                       :   '',

                // Slide 16
                JPlan                                   :   '',

                // Slide 17 
                Frequency                               :   '',

                // Slide 18
                Journee                                 :   '',

                // Slide 19
                BrandAvailability                       :   'Non',

                AvailableBrands                         :   [],
                AvailableBrands_array_formatted         :   [],
                AvailableBrands_string_formatted        :   "",

                in_store_image                          :   '',
                in_store_image_original_name            :   '',

                // Slide 20
                comment                                 :   '',

                // Slide 21
                facade_image                            :   '',
                facade_image_original_name              :   '',

                // Slide 22
                Latitude                                :   '',
                Longitude                               :   '',

                // Slide 17
                status                                  :   '',
                status_original                         :   '',
                nonvalidated_details                    :   '', 

                //
                CustomerBarCode_image_updated           :   false,
                facade_image_updated                    :   false,
                in_store_image_updated                  :   false,

                //
                owner                                   :   '',
                owner_username                              :   ''
            },

            cities                           :   []      ,

            //

            all_clients                     :   []      ,
            close_clients                   :   []      ,
            min_distance                    :   0.05    ,

            //

            scanner                         :   null    ,

            //

            willayas                        :   []      ,
            users                           :   []      ,

            //

            check_gps_clicked               :   false   ,
            show_modal_client_update_map    :   null    ,
            position_marker                 :   null    ,
            close_clients_markers           :   []      ,
        }
    },

    props : ["id_route_import", "update_type", "id_route_import_tempo", "mode", "validation_type", "districts_all", "users_all"],

    mounted() {
        this.clearData("#ModalClientUpdate")
    },

    created() {
        // 1. Initialize non-reactive storage for files
        this._rawFiles = {
            facade_image: null,
            CustomerBarCode_image: null,
            in_store_image: null
        };
    },

    beforeUnmount() {
        // 2. Cleanup memory when modal closes
        const fields = ['facade_image', 'CustomerBarCode_image', 'in_store_image'];
        fields.forEach(field => this.revokeImage(field));
    },

    methods : {

        async sendData() {
            await this.$showLoadingPage();

            // 1. Refresh Geographic Names (Ensure they match the IDs)
            if (this.willayas.length > 0) {
                this.client.DistrictNameE = this.getDistrictNameE(this.client.DistrictNo);
            }
            if (this.cities.length > 0) {
                this.client.CityNameE = this.getCityNameE(this.client.CityNo);
            }

            // 2. Prepare Payload Object (Metadata Only)
            // We construct a plain JS object first. We do NOT put binary files here.
            let payload = {};
            const status = this.client.OpenCustomer; // 'Ouvert', 'Ferme', 'Refus', 'Introuvable'

            // --- A. Base Fields (Common to all statuses) ---
            const baseFields = {
                NewCustomer: this.client.NewCustomer,
                OpenCustomer: this.client.OpenCustomer,
                CustomerIdentifier: this.client.CustomerIdentifier,
                CustomerNameA: this.client.CustomerNameA,
                Latitude: this.client.Latitude,
                Longitude: this.client.Longitude,
                Address: this.client.Address,
                RvrsGeoAddress: this.client.RvrsGeoAddress,
                Neighborhood: this.client.Neighborhood,
                Landmark: this.client.Landmark,
                DistrictNo: this.client.DistrictNo,
                DistrictNameE: this.client.DistrictNameE,
                CityNo: this.client.CityNo,
                CityNameE: this.client.CityNameE,
                CustomerType: this.client.CustomerType,
                
                // For images in JSON, we send the existing filename string (or empty).
                // The actual binary update happens in step 4 via _rawFiles.
                facade_image: typeof this.client.facade_image === 'string' ? this.client.facade_image : '',
                facade_image_original_name: this.client.facade_image_original_name,
                facade_image_updated: this.client.facade_image_updated,
                
                comment: this.client.comment
            };

            // --- B. Status Specific Logic ---
            if (status === 'Ouvert') {
                payload = {
                    ...baseFields,
                    CustomerCode: this.client.CustomerCode,
                    CustomerNameE: this.client.CustomerNameE,
                    Tel: this.client.Tel,
                    tel_status: this.client.tel_status,
                    tel_comment: this.client.tel_comment,
                    BrandAvailability: this.client.BrandAvailability,
                    BrandSourcePurchase: this.client.BrandSourcePurchase,
                    JPlan: this.client.JPlan,
                    Journee: this.client.Journee,
                    Frequency: this.client.Frequency,
                    SuperficieMagasin: this.client.SuperficieMagasin,
                    NbrAutomaticCheckouts: this.client.NbrAutomaticCheckouts,
                    
                    // Convert arrays to JSON strings
                    AvailableBrands: JSON.stringify(this.client.AvailableBrands),
                    
                    // Image Flags
                    CustomerBarCode_image_updated: this.client.CustomerBarCode_image_updated,
                    in_store_image_updated: this.client.in_store_image_updated,
                    
                    // Image Filenames (Strings)
                    CustomerBarCode_image: typeof this.client.CustomerBarCode_image === 'string' ? this.client.CustomerBarCode_image : '',
                    in_store_image: typeof this.client.in_store_image === 'string' ? this.client.in_store_image : '',
                    
                    CustomerBarCode_image_original_name: this.client.CustomerBarCode_image_original_name,
                    in_store_image_original_name: this.client.in_store_image_original_name,
                    
                    status: this.client.status,
                    nonvalidated_details: this.client.nonvalidated_details,
                    owner: this.client.owner // Maintain owner if needed
                };
            } else if (status === 'Ferme' || status === 'refus') {
                payload = {
                    ...baseFields,
                    // Reset/Clear specific fields for closed/refused
                    CustomerCode: '',
                    CustomerNameE: '',
                    Tel: '',
                    tel_status: 'nonvalidated',
                    tel_comment: '',
                    BrandAvailability: 'Non',
                    BrandSourcePurchase: '',
                    JPlan: this.client.JPlan,
                    Journee: this.client.Journee,
                    Frequency: this.client.Frequency,
                    SuperficieMagasin: this.client.SuperficieMagasin,
                    NbrAutomaticCheckouts: '',
                    AvailableBrands: JSON.stringify([]),
                    
                    // Clear images
                    CustomerBarCode_image: '',
                    in_store_image: '',
                    CustomerBarCode_image_original_name: '',
                    in_store_image_original_name: '',
                    
                    // Force update flags to true so backend clears them
                    CustomerBarCode_image_updated: true,
                    in_store_image_updated: true,
                    
                    status: 'pending',
                    nonvalidated_details: ''
                };
            } else if (status === 'Introuvable') {
                payload = {
                    ...baseFields,
                    CustomerCode: '',
                    CustomerNameE: '',
                    Tel: this.client.Tel,
                    tel_status: 'nonvalidated',
                    tel_comment: '',
                    NbrAutomaticCheckouts: '',
                    SuperficieMagasin: this.client.SuperficieMagasin,
                    BrandAvailability: this.client.BrandAvailability,
                    BrandSourcePurchase: this.client.BrandSourcePurchase,
                    
                    // Clear images
                    CustomerBarCode_image: '',
                    in_store_image: '',
                    CustomerBarCode_image_original_name: '',
                    in_store_image_original_name: '',
                    
                    CustomerBarCode_image_updated: true,
                    in_store_image_updated: true,
                    
                    JPlan: this.client.JPlan,
                    Journee: this.client.Journee,
                    status: 'pending',
                    nonvalidated_details: ''
                };
            }

            // 3. Timer Logic
            if (this.client.status_original === 'visible') {
                payload.start_adding_date = moment().format(); // Or use stored start date
                payload.finish_adding_date = moment().format();
            }

            // 4. Create FormData and Append Text Fields
            const formData = new FormData();
            for (const key in payload) {
                let value = payload[key];
                if (value === null || value === undefined) value = '';
                formData.append(key, value);
            }

            // 5. Append BINARY Files (from non-reactive storage)
            // This is the key optimization step
            if (this._rawFiles) {
                Object.keys(this._rawFiles).forEach(key => {
                    if (this._rawFiles[key]) {
                        formData.append(key, this._rawFiles[key]);
                    }
                });
            }

            // 6. Send to API based on Mode (Permanent vs Temporary)
            try {
                let res;
                if (this.mode == "permanent") {
                    res = await this.$callApi("post", `/route-imports/${this.id_route_import}/clients/${this.client.id}/update`, formData);
                } else if (this.mode == "temporary") {
                    res = await this.$callApi("post", `/route-imports-tempo/${this.id_route_import_tempo}/clients-tempo/${this.client.id}/update`, formData);
                }

                if (res.status === 200) {
                    await this.$hideLoadingPage();
                    this.$feedbackSuccess(res.data["header"], res.data["message"]);

                    this.client.AvailableBrands_array_formatted     =   this.client.AvailableBrands
                    this.client.AvailableBrands_string_formatted    =   this.client.AvailableBrands.join(', ')

                    // Validation Logic / Event Emission
                    if (this.update_type == "validation") {
                        emitter.emit("updateDoubles" + this.validation_type, this.client);
                    } else {
                        // Generic update event for parent
                        emitter.emit('updateClient', this.client);
                    }

                    await this.$hideModal("ModalClientUpdate");
                } else {
                    await this.$hideLoadingPage();
                    this.$showErrors("Error !", res.data.errors);
                }
            } catch (error) {
                console.error(error);
                await this.$hideLoadingPage();
                this.$showErrors("System Error", ["An unexpected error occurred."]);
            } finally {
            }
        },

        async deleteData() {
            await this.$showLoadingPage();

            try {
                let res;
                
                // Branching Logic
                if (this.mode == "permanent") {
                    res = await this.$callApi("post", `/route-imports/${this.id_route_import}/clients/${this.client.id}/delete`, null);
                } else if (this.mode == "temporary") {
                    res = await this.$callApi("post", `/route-imports-tempo/${this.id_route_import_tempo}/clients-tempo/${this.client.id}/delete`, null);
                }

                // Success Handling
                if (res.status === 200) {
                    await this.$hideLoadingPage();
                    this.$feedbackSuccess(res.data["header"], res.data["message"]);
                    
                    // Validation Logic
                    if (this.update_type == "validation") {
                        emitter.emit("deleteDoubles" + this.validation_type, this.client);
                    } else {
                        emitter.emit('reSetDelete', this.client);
                    }

                    await this.$hideModal("ModalClientUpdate");
                } else {
                    await this.$hideLoadingPage();
                    this.$showErrors("Error !", res.data.errors);
                }

            } catch (error) {
                console.error("Delete Error", error);
                await this.$hideLoadingPage();
                this.$showErrors("System Error", ["An unexpected error occurred during deletion."]);
            } finally {
            }
        },

        //  //  //  //  //

        async getData(client, all_clients) {
            await this.$showLoadingPage();
            try {
                // Handle All Clients List Logic
                if (this.update_type == "validation") {
                    const route = this.mode === "permanent" 
                        ? `/route-imports/${this.id_route_import}/clients`
                        : `/route-imports-tempo/${this.id_route_import_tempo}/clients-tempo`;
                    
                    const res = await this.$callApi("post", route, null);
                    this.all_clients = res.data.clients;
                } else if (this.update_type == "normal_update") {
                    this.all_clients = all_clients;
                }

                await this.getComboData();

                await this.getClientData(client);

                // Map logic
                await this.showPositionOnMapMultiMap("show_modal_client_update_map");

                //
                await this.$hideLoadingPage();
            } finally {
            }
        },

        async getClientData(client) {
            // Reset Raw Files
            this._rawFiles = {
                facade_image            : null,
                CustomerBarCode_image   : null,
                in_store_image          : null
            };

            // Clone data to avoid mutating parent directly until save
            // Using Object.assign to copy properties
            Object.assign(this.client, client);

            // Handle Specific Fields
            this.client.old_CustomerNameE = client.CustomerNameE;
            this.client.status_original = client.status;

            // --- IMAGE PREVIEW LOGIC ---
            // Instead of using $createFile (which is heavy), simply point the URL to the server
            
            const imageFields = ['CustomerBarCode_image', 'facade_image', 'in_store_image'];
            
            imageFields.forEach(field => {
                // Set original names
                this.client[`${field}_original_name`] = client[`${field}_original_name`];
                
                // Set Preview URL
                if (client[field]) {
                    this.client[`${field}_currentObjectURL`] = `/uploads/clients/${client.id}/${client[field]}`;
                    
                    // Show container if it exists (for your magnifier logic)
                    const container = document.getElementById(`${field}_display_update_container`);
                    if(container) container.style.display = "block";
                } else {
                    this.client[`${field}_currentObjectURL`] = null;
                     const container = document.getElementById(`${field}_display_update_container`);
                    if(container) container.style.display = "none";
                }
            });

            // If you need specific logic like getUsers/Cites, keep them:
            await this.getUsers();
            if(this.client.DistrictNo) await this.getCites();

            // Re-Assign Cities 
            this.client.CityNo  =   client.CityNo
        },

        async getComboData() {

            if(this.districts_all) {
                this.willayas   =   this.districts_all  
            }

            else {
                const res_2     =   await this.$callApi("post"  ,   "/rtm-willayas"     ,   null)
                this.willayas   =   res_2.data.willayas
            }
        },

        async getUsers() {

            if(this.users_all) {
                this.users          =   this.users_all  
            }

            else {
                const res_1         =   await this.$callApi("post"  ,   "/users/combo"  ,   null)
                this.users          =   res_1.data.users
            }
        },

        async getCites() {

            // Show Loading Page
            await this.$showLoadingPage()

            const res_3     =   await this.$callApi("post"  ,   "/rtm-willayas/"+this.client.DistrictNo+"/rtm-cities"         ,   null)
            this.cities     =   res_3.data.cities

            this.client.CityNo      =   ""

            // Hide Loading Page
            await this.$hideLoadingPage()
        },

        //  //  //  //  //

        getDistrictNameE(DistrictNo) {

            for (let i = 0; i < this.willayas.length; i++) {

                if(this.willayas[i].DistrictNo  ==  DistrictNo) {

                    return this.willayas[i].DistrictNameE
                }                
            }
        },

        getCityNameE(CityNo) {

            for (let i = 0; i < this.cities.length; i++) {

                if(this.cities[i].CityNo  ==  CityNo) {

                    return this.cities[i].CityNameE
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

        //  //  //  //  //

        async handleImageUpload(event, fieldKey) {
            const file = event.target.files[0];
            if (!file) return;

            this.revokeImage(fieldKey); // Clear old memory

            try {
                // Compress
                const compressedFile = await this.$compressImage(file);
                const objectUrl = URL.createObjectURL(compressedFile);

                // Store Binary in non-reactive object
                this._rawFiles[fieldKey] = compressedFile;

                // Update Vue State (Metadata & Preview only)
                this.client[`${fieldKey}_currentObjectURL`] = objectUrl;
                this.client[`${fieldKey}_original_name`] = file.name;
                this.client[`${fieldKey}_updated`] = true;

                // Handle Display Containers (Logic from your original code)
                const containerId = `${fieldKey}_display_update_container`;
                const container = document.getElementById(containerId);
                if (container) container.style.display = "block";

                // Clear input value to allow re-selecting same file
                event.target.value = '';

            } catch (e) {
                console.error(`Error uploading ${fieldKey}:`, e);
            }
        },

        revokeImage(fieldKey) {
            if (this.client[`${fieldKey}_currentObjectURL`]?.startsWith('blob:')) {
                URL.revokeObjectURL(this.client[`${fieldKey}_currentObjectURL`]);
            }
        },

        //  //  //  //  //

        brandAvailabilityChanged() {

            if(this.client.BrandAvailability   === 'Non') {

                this.client.AvailableBrands                 =   []

                this.client.in_store_image_original_name    =   ""
                this.client.in_store_image                  =   ""

                this.client.in_store_image_updated          =   true

                const in_store_image_display_update         =   document.getElementById("in_store_image_display_update")

                if(in_store_image_display_update) {

                    in_store_image_display_update.src           =   ""
                }
            }
        },

        setStatus() {

            if(this.client.OpenCustomer ==  "Ferme") {
                this.client.status     =   "ferme"
            }

            else {
                this.client.status     =   ""
            }
        },

        //  //  //  //  //

        async showPositionOnMapMultiMap(map_id) {
            
            if (!this.check_gps_clicked) {
                this.check_gps_clicked = true;

                try {
                    
                    await this.$nextTick();
                    
                    // MODAL VISIBILITY HANDLING
                    await new Promise(resolve => {
                        const modal = document.getElementById('ModalClientUpdate');
                        
                        const visibilityCheck = () => {
                            if (modal.offsetWidth > 0 && modal.offsetHeight > 0) {
                                modal.removeEventListener('transitionend', visibilityCheck);
                                resolve();
                            }
                        };

                        if (modal.offsetWidth > 0 && modal.offsetHeight > 0) {
                            resolve();
                        } else {
                            modal.addEventListener('transitionend', visibilityCheck);
                        }
                    });

                    //
                    this.checkClients()

                    // MAP INITIALIZATION
                    const map_object                    =   await this.$showPositionOnMapMultiMap(this.show_modal_client_update_map, map_id, this.client, this.close_clients);

                    // STATE MANAGEMENT
                    this.show_modal_client_update_map   =   map_object.show_map;
                    this.position_marker                =   map_object.position_marker;
                    this.close_clients_markers          =   map_object.close_clients_markers;

                    this.position_marker.on('dragend', async (e) => {

                        //
                        const new_position      =   e.target.getLatLng();

                        //
                        this.client.Latitude    =   new_position.lat
                        this.client.Longitude   =   new_position.lng

                        //
                        this.position_marker.off('dragend')

                        //
                        this.check_gps_clicked  =   false

                        //
                        await this.showPositionOnMapMultiMap(map_id)
                    });

                } catch (error) {
                    console.log(error);
                } finally {
                    this.check_gps_clicked = false;
                }
            }
        },

        checkClients() {

            this.close_clients  =   []

            let distance        =   0

            for (let i = 0; i < this.all_clients.length; i++) {

                if(this.all_clients[i].id   !=  this.client.id) {

                    distance        =   this.getDistance(this.client.Latitude, this.client.Longitude, this.all_clients[i].Latitude, this.all_clients[i].Longitude)

                    if(distance <=  this.min_distance) {
                    
                        this.close_clients.push(this.all_clients[i])
                    }
                }
            }
        },

        getDistance(latitude_1, longitude_1, latitude_2, longitude_2) {

            return this.$map.$setDistanceStraight(latitude_1, longitude_1, latitude_2, longitude_2)
        },

        async getRvrsGeoAddress() {

            const address   =   await this.$getAddressFromLocationIQ(this.client.Latitude, this.client.Longitude);
            
            // Assuming 'this.client.Address' is where you want to store it
            if(address) {
                this.client.RvrsGeoAddress  =   address;
            }
        },

        //  //  //  //  //

        async setBarCodeReader() {

            const reader_update    =   document.getElementById('reader_update')

            // 
            this.client.CustomerCode    =   ""

            if(reader_update) {

                reader_update.style.display        =   "block";

                //
                this.scanner = new Html5QrcodeScanner('reader_update', {

                    qrbox   : {
                        width   : 250,
                        height  : 250,
                    },

                    fps     : 20
                });

                try {
                    await this.scanner.render(this.success, this.error);
                } catch (error) {
                    console.error('Error rendering scanner:', error);
                }
            }
        },

        success(result) {
             
            // 
            if(this.$isValidForFileName(result)) {

                // 
                this.client.CustomerCode    =   result
            }

            else {

                // 
                this.client.CustomerCode    =   ""
                this.$showErrors("Error !"  ,   ["Votre code-barres contient des caractères interdits : / \ : * ? \" < > | &; (espace)"])
            }

            this.scanner.clear();

            document.getElementById('reader_update').style.display =   "none"
            // Removes reader_update element from DOM since no longer needed 
        },

        error(err) {

            // Prints any errors to the console
            console.error("");
        },

        //  //  //  //  //

        clearData() {
            // 1. Revoke Object URLs to free memory
            const fields = ['facade_image', 'CustomerBarCode_image', 'in_store_image'];
            fields.forEach(field => {
                if (this.client[`${field}_currentObjectURL`]?.startsWith('blob:')) {
                    URL.revokeObjectURL(this.client[`${field}_currentObjectURL`]);
                }
                // Reset Vue State for images
                this.client[field] = '';
                this.client[`${field}_original_name`] = '';
                this.client[`${field}_currentObjectURL`] = null;
                this.client[`${field}_updated`] = false;

                // Hide Display Containers
                const container = document.getElementById(`${field}_display_update_container`);
                if (container) container.style.display = "none";
            });

            // 2. Clear Raw Files (Binary Data)
            this._rawFiles = {
                facade_image: null,
                CustomerBarCode_image: null,
                in_store_image: null
            };

            // 3. Reset Text Fields (Optional - depending on if you want a blank slate or just image cleanup)
            // Usually, in a modal, you overwrite these in `getData`, but it's good practice to reset keys.
            this.client.id = '';
            this.client.CustomerNameE = '';
            this.client.CustomerNameA = '';
            this.client.AvailableBrands = [];
            this.client.status = '';
            
            // 4. Reset Inputs in DOM
            const inputIds = ['facade_image_update', 'CustomerBarCode_image_update', 'in_store_image_update'];
            inputIds.forEach(id => {
                const el = document.getElementById(id);
                if (el) el.value = '';
            });
            
            // 5. Reset Map if initialized
            if(this.position_marker) {
                // Logic to remove marker from map if necessary
                this.position_marker = null;
            }
        }
    }
};

</script>