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
                                            <option v-if="client.OpenCustomer   !=  'Ferme'"    value="validated" selected>validated</option>
                                            <option v-if="client.OpenCustomer   !=  'Ferme'"    value="pending">pending</option>
                                            <option v-if="client.OpenCustomer   !=  'Ferme'"    value="nonvalidated">nonvalidated</option>
                                            <option v-if="client.OpenCustomer   !=  'Ferme'"    value="visible">visible</option>

                                            <option v-if="client.OpenCustomer   ==  'Ferme'"    value="ferme">ferme</option>
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
                                            <option value="nonvalidated" selected>nonvalidated</option>
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
                                        <select                         class="form-select"         id="Journee"                v-model="client.Journee"                    :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))">
                                            <option     :value="'Dimanche 1'">Dimanche 1</option>
                                            <option     :value="'Lundi 1'">Lundi 1</option>
                                            <option     :value="'Mardi 1'">Mardi 1</option>
                                            <option     :value="'Mercredi 1'">Mercredi 1</option>
                                            <option     :value="'Jeudi 1'">Jeudi 1</option>
                                            <option     :value="'Dimanche 2'">Dimanche 2</option>
                                            <option     :value="'Lundi 2'">Lundi 2</option>
                                            <option     :value="'Mardi 2'">Mardi 2</option>
                                            <option     :value="'Mercredi 2'">Mercredi 2</option>
                                            <option     :value="'Jeudi 2'">Jeudi 2</option>
                                        </select>
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
                                            <option v-for="cite in cites" :key="cite.CITYNO" :value="cite.CITYNO">{{cite.CityNameE}} ({{cite.CITYNO}})</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mt-3 mb-3">
                                    <div class="col-sm-4">
                                        <label for="Address"            class="form-label">Adresse</label>
                                        <input type="text"              class="form-control"        id="Address"                v-model="client.Address"                                :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))">
                                    </div>

                                    <div class="col-sm-4">
                                        <label for="Neighborhood"       class="form-label">Quartier</label>
                                        <input type="text"              class="form-control"        id="Neighborhood"           v-model="client.Neighborhood"                           :disabled="!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice')))">
                                    </div>

                                    <div class="col-sm-4">
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

                                    <div class="col-sm-4 mt-auto">
                                        <button type="button" class="btn btn-primary w-100" @click="showPositionOnMapMultiMap('show_modal_client_update_map')"     :disabled="((!((this.$isRole('Super Admin'))||(this.$isRole('BU Manager'))||(this.$isRole('BackOffice'))))||(check_gps_clicked))">Show Position <i class="mdi mdi-crosshairs-gps"></i></button>
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
                                            <option v-for="user in users"                   :key="user.id"      :value="user.id">{{user.nom}}</option>
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
                                                @load="$magnify('in_store_image_display_update', 3, 3, 'in_store_image_display_update_container')"
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
                tel_status                              :   'nonvalidated',
                tel_comment                             :   '',

                // Slide 7
                Address                                 :   '',

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
                owner_name                              :   ''
            },

            cites                           :   []      ,

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

    methods : {

        ...mapActions("client" ,  [
            "setUpdateClientAction"   ,
        ]),

        //

        async sendData() {

            this.$showLoadingPage()

            // Set Client
            this.client.DistrictNameE   =   this.getDistrictNameE(this.client.DistrictNo)
            this.client.CityNameE       =   this.getCityNameE(this.client.CityNo)
            this.client.owner_name      =   this.getOwnerName(this.client.owner)

            //

            if(this.client.OpenCustomer  === 'Ouvert') {
            }

            else {
                this.client.CustomerCode                                =   ''
                this.client.CustomerNameE                               =   ''
                this.client.Tel                                         =   ''
                this.client.tel_status                                  =   'nonvalidated'
                this.client.tel_comment                                 =   ''
                this.client.BrandAvailability                           =   'Non'
                this.client.BrandSourcePurchase                         =   ''
                this.client.NbrAutomaticCheckouts                       =   ''
                this.client.AvailableBrands                             =   []
                this.client.CustomerBarCode_image                       =   ''
                this.client.in_store_image                              =   ''
                this.client.CustomerBarCode_image_original_name         =   ''
                this.client.in_store_image_original_name                =   ''
                this.client.status                                      =   'ferme'
                this.client.nonvalidated_details                        =   ''
            }

            //
            let formData = new FormData();

            formData.append("NewCustomer"                           ,   this.client.NewCustomer)
            formData.append("OpenCustomer"                          ,   this.client.OpenCustomer)

            formData.append("CustomerIdentifier"                    ,   this.client.CustomerIdentifier)
            formData.append("CustomerCode"                          ,   this.client.CustomerCode)
            formData.append("CustomerNameE"                         ,   this.client.CustomerNameE)
            formData.append("CustomerNameA"                         ,   this.client.CustomerNameA)
            formData.append("Latitude"                              ,   this.client.Latitude)
            formData.append("Longitude"                             ,   this.client.Longitude)
            formData.append("Address"                               ,   this.client.Address)
            formData.append("Neighborhood"                          ,   this.client.Neighborhood)
            formData.append("Landmark"                              ,   this.client.Landmark)

            formData.append("DistrictNo"                            ,   this.client.DistrictNo)
            formData.append("DistrictNameE"                         ,   this.client.DistrictNameE)
            formData.append("CityNo"                                ,   this.client.CityNo)
            formData.append("CityNameE"                             ,   this.client.CityNameE)
            formData.append("Tel"                                   ,   this.client.Tel)
            formData.append("tel_status"                            ,   this.client.tel_status)
            formData.append("tel_comment"                           ,   this.client.tel_comment)
            formData.append("CustomerType"                          ,   this.client.CustomerType)
            formData.append("BrandAvailability"                     ,   this.client.BrandAvailability)
            formData.append("BrandSourcePurchase"                   ,   this.client.BrandSourcePurchase)

            formData.append("JPlan"                                 ,   this.client.JPlan)
            formData.append("Journee"                               ,   this.client.Journee)

            formData.append("Frequency"                             ,   this.client.Frequency)
            formData.append("SuperficieMagasin"                     ,   this.client.SuperficieMagasin)
            formData.append("NbrAutomaticCheckouts"                 ,   this.client.NbrAutomaticCheckouts)
            formData.append("AvailableBrands"                       ,   JSON.stringify(this.client.AvailableBrands))

            formData.append("CustomerBarCode_image_updated"         ,   this.client.CustomerBarCode_image_updated)
            formData.append("facade_image_updated"                  ,   this.client.facade_image_updated)
            formData.append("in_store_image_updated"                ,   this.client.in_store_image_updated)

            formData.append("CustomerBarCode_image"                 ,   this.client.CustomerBarCode_image)
            formData.append("facade_image"                          ,   this.client.facade_image)
            formData.append("in_store_image"                        ,   this.client.in_store_image)

            formData.append("CustomerBarCode_image_original_name"   ,   this.client.CustomerBarCode_image_original_name)
            formData.append("facade_image_original_name"            ,   this.client.facade_image_original_name)
            formData.append("in_store_image_original_name"          ,   this.client.in_store_image_original_name)

            formData.append("status"                                ,   this.client.status)
            formData.append("nonvalidated_details"                  ,   this.client.nonvalidated_details)

            formData.append("owner"                                 ,   this.client.owner)
            formData.append("comment"                               ,   this.client.comment)

            //
            if(this.mode    ==  "permanent") {

                const res                   =   await this.$callApi("post"  ,   "/route_import/"+this.id_route_import+"/clients/"+this.client.id+"/update",   formData)
                console.log(res)

                if(res.status===200){

                    // Send Feedback
                    this.$feedbackSuccess(res.data["header"]    ,   res.data["message"])

                    // 5) Now hide the spinner
                    this.$hideLoadingPage();

                    // Validation
                    if(this.update_type ==  "validation") {
                        this.emitter.emit("updateDoubles"+this.validation_type    , res.data.client)
                    }

                    // Update Data
                    else {
                        if(this.update_type ==  "normal_update") {
                            this.emitter.emit('reSetUpdate' , res.data.client)
                        }
                    }

                    // Close Modal
                    await this.$hideModal("ModalClientUpdate")
                }
                
                else{

                    // Send Errors
                    this.$showErrors("Error !", res.data.errors)

                    // 5) Now hide the spinner
                    this.$hideLoadingPage();
                }
            }

            else {

                if(this.mode    ==  "temporary") {

                    const res                   =   await this.$callApi("post"  ,   "/route_import_tempo/"+this.id_route_import_tempo+"/clients_tempo/"+this.client.id+"/update",   formData)

                    if(res.status===200){

                        // Send Feedback
                        this.$feedbackSuccess(res.data["header"]    ,   res.data["message"])

                        // 5) Now hide the spinner
                        this.$hideLoadingPage();

                        // Validation
                        if(this.update_type ==  "validation") {

                            this.emitter.emit("updateDoubles"+this.validation_type    , res.data.client)
                        }

                        // Close Modal
                        await this.$hideModal("ModalClientUpdate")
                    }
                    
                    else{

                        // Send Errors
                        this.$showErrors("Error !", res.data.errors)

                        // 5) Now hide the spinner
                        this.$hideLoadingPage();
                    }
                }
            }
        },

        async deleteData() {

            this.$showLoadingPage()

            if(this.mode    ==  "permanent") {

                const res                   =   await this.$callApi("post"  ,   "/route_import/"+this.id_route_import+"/clients/"+this.client.id+"/delete",   null)

                if(res.status===200){

                    // Send Feedback
                    this.$feedbackSuccess(res.data["header"]    ,   res.data["message"])

                    // 5) Now hide the spinner
                    this.$hideLoadingPage();

                    // Validation
                    if(this.update_type ==  "validation") {
                        this.emitter.emit("deleteDoubles"+this.validation_type   , this.client)
                    }

                    // Update Data
                    else {
                        this.emitter.emit('reSetDelete' , this.client)
                    }

                    // Close Modal
                    await this.$hideModal("ModalClientUpdate")
                }
                
                else{

                    // Send Errors
                    this.$showErrors("Error !", res.data.errors)

                    // 5) Now hide the spinner
                    this.$hideLoadingPage();
                }
            }

            else {

                if(this.mode    ==  "temporary") {

                    const res                   =   await this.$callApi("post"  ,   "/route_import_tempo/"+this.id_route_import_tempo+"/clients_tempo/"+this.client.id+"/delete",   null)

                    if(res.status===200){

                        // Send Feedback
                        this.$feedbackSuccess(res.data["header"]    ,   res.data["message"])

                        // 5) Now hide the spinner
                        this.$hideLoadingPage();

                        // Validation
                        if(this.update_type ==  "validation") {
                            this.emitter.emit("deleteDoubles"+this.validation_type   , this.client)
                        }

                        // Update Data
                        else {
                            this.emitter.emit('reSetDelete' , this.client)
                        }

                        // Close Modal
                        await this.$hideModal("ModalClientUpdate")
                    }
                    
                    else{

                        // Send Errors
                        this.$showErrors("Error !", res.data.errors)

                        // 5) Now hide the spinner
                        this.$hideLoadingPage();
                    }
                }
            }
        },

        //

        clearData(id_modal) {

            $(id_modal).on("hidden.bs.modal",   ()  => {

                //

                if(this.scanner) {

                    this.scanner.clear().then(_ => {

                    }).catch(error => {

                    });
                }

                //

                let CustomerBarCode_image_update                =   document.getElementById("CustomerBarCode_image_update")
                if(CustomerBarCode_image_update) {
                    CustomerBarCode_image_update.value              =   ""
                }

                let CustomerBarCode_image_display_update        =   document.getElementById("CustomerBarCode_image_display_update")
                if(CustomerBarCode_image_display_update) {
                    CustomerBarCode_image_display_update.src        =   ""
                }

                //

                let facade_image_update                         =   document.getElementById("facade_image_update")
                if(facade_image_update) {
                    facade_image_update.value                       =   ""
                }

                let facade_image_display_update                 =   document.getElementById("facade_image_display_update")
                if(facade_image_display_update) {
                    facade_image_display_update.src                 =   ""
                }

                //

                let in_store_image_update                       =   document.getElementById("in_store_image_update")
                if(in_store_image_update) {
                    in_store_image_update.value                     =   ""
                }

                let in_store_image_display_update               =   document.getElementById("in_store_image_display_update")
                if(in_store_image_display_update) {
                    in_store_image_display_update.src               =   ""
                }

                //

                this.client.CustomerBarCode_image                   =   '',
                this.client.facade_image                            =   '',
                this.client.in_store_image                          =   '',

                this.client.CustomerBarCode_image_original_name     =   '',
                this.client.facade_image_original_name              =   '',
                this.client.in_store_image_original_name            =   '',

                this.client.CustomerBarCode_image_updated           =   false
                this.client.facade_image_updated                    =   false,
                this.client.in_store_image_updated                  =   false

                // 
                this.setUpdateClientAction(null)

                // Client
                this.client.id                                      =   '',

                //
                this.client.NewCustomer                             =   '',

                //
                this.client.OpenCustomer                            =   '',

                // Slide 1
                this.client.CustomerCode                            =   '',

                // Slide 2
                this.client.CustomerBarCode_image                   =   '',
                this.client.CustomerBarCode_image_original_name     =   '',

                // Slide 3
                this.client.old_CustomerNameE                       =   '',
                this.client.CustomerNameE                           =   '',

                // Slide 4
                this.client.CustomerNameA                           =   '',

                // Slide 5
                this.client.Tel                                     =   '',
                this.client.tel_status                              =   'nonvalidated',
                this.client.tel_comment                             =   '',

                // Slide 6
                this.client.Latitude                                =   '',
                this.client.Longitude                               =   '',

                // Slide 7
                this.client.Address                                 =   '',

                // Slide 8
                this.client.Neighborhood                            =   '',

                // Slide 9
                this.client.Landmark                                =   '',

                // Slide 10
                this.client.DistrictNo                              =   '',
                this.client.DistrictNameE                           =   '',

                // Slide 11
                this.client.CityNo                                  =   '',
                this.client.CityNameE                               =   '',

                // Slide 12
                this.client.CustomerType                            =   '',

                // Slide 13
                this.client.BrandAvailability                       =   'Non',

                // Slide 14
                this.client.BrandSourcePurchase                     =   '',

                // Slide 15
                this.client.JPlan                                   =   '',

                // Slide 16 
                this.client.Journee                                 =   '',

                //
                this.client.AvailableBrands                         =   [],
                this.client.NbrAutomaticCheckouts                   =   '',
                this.client.SuperficieMagasin                       =   '',

                // Slide 17
                this.client.status_original                         =   '',
                this.client.status                                  =   '',
                this.client.nonvalidated_details                    =   '', 

                // Slide 18
                this.client.facade_image                            =   '',
                this.client.facade_image_original_name              =   '',

                // Slide 19   
                this.client.in_store_image                          =   '',
                this.client.in_store_image_original_name            =   '',

                this.client.owner                                   =   '',
                this.client.owner_name                              =   '',
                this.client.comment                                 =   '',

                //

                this.users                                          =   []  ,
                this.willayas                                       =   []  ,
                this.cites                                          =   []  ,

                this.all_clients                                    =   []  ,
                this.close_clients                                  =   []
            });
        },

        async getData(client, all_clients) {

            //
            if(this.update_type ==  "validation") {
                if(this.mode ==  "permanent") {
                    const res           =   await this.$callApi("post", "/route_import/"+this.id_route_import+"/clients", null)
                    this.all_clients    =   res.data.clients   
                }

                if(this.mode ==  "temporary") {
                    const res           =   await this.$callApi("post", "/route_import_tempo/"+this.id_route_import_tempo+"/clients_tempo", null)
                    this.all_clients    =   res.data   
                }
            }

            else {
                if(this.update_type ==  "normal_update") {
                    this.all_clients    =   all_clients
                }
            }

            //
            await this.getComboData()  
            await this.getClientData(client)  

            await this.showPositionOnMapMultiMap("show_modal_client_update_map");
        },

        async getClientData(client) {

            this.client.id                              =   client.id

            this.client.id_route_import                 =   client.id_route_import

            await this.getUsers()

            this.client.NewCustomer                     =   client.NewCustomer

            this.client.OpenCustomer                    =   client.OpenCustomer

            this.client.CustomerIdentifier              =   client.CustomerIdentifier

            this.client.CustomerCode                    =   client.CustomerCode

            this.client.old_CustomerNameE               =   client.CustomerNameE

            this.client.CustomerNameE                   =   client.CustomerNameE
            this.client.CustomerNameA                   =   client.CustomerNameA
            this.client.Latitude                        =   client.Latitude
            this.client.Longitude                       =   client.Longitude

            this.client.Address                         =   client.Address
            this.client.Neighborhood                    =   client.Neighborhood
            this.client.Landmark                        =   client.Landmark

            this.client.DistrictNo                      =   client.DistrictNo
            this.client.DistrictNameE                   =   client.DistrictNameE

            await this.getCites()

            this.client.CityNo                          =   client.CityNo
            this.client.CityNameE                       =   client.CityNameE

            this.client.Tel                             =   client.Tel
            this.client.tel_status                      =   client.tel_status
            this.client.tel_comment                     =   client.tel_comment

            this.client.CustomerType                    =   client.CustomerType
            this.client.BrandAvailability               =   client.BrandAvailability
            this.client.BrandSourcePurchase             =   client.BrandSourcePurchase

            this.client.JPlan                           =   client.JPlan
            this.client.Journee                         =   client.Journee

            this.client.Frequency                       =   client.Frequency
            this.client.AvailableBrands                 =   client.AvailableBrands_array_formatted // client.AvailableBrands
            this.client.NbrAutomaticCheckouts           =   client.NbrAutomaticCheckouts
            this.client.SuperficieMagasin               =   client.SuperficieMagasin

            this.client.status                          =   client.status
            this.client.status_original                 =   client.status
            this.client.nonvalidated_details            =   client.nonvalidated_details

            this.client.owner                           =   client.owner
            this.client.comment                         =   client.comment

            this.client.CustomerBarCode_image                   =   client.CustomerBarCode_image
            this.client.facade_image                            =   client.facade_image
            this.client.in_store_image                          =   client.in_store_image

            this.client.CustomerBarCode_image_original_name     =   client.CustomerBarCode_image_original_name
            this.client.facade_image_original_name              =   client.facade_image_original_name
            this.client.in_store_image_original_name            =   client.in_store_image_original_name

            if(this.client.CustomerBarCode_image_original_name) {
                this.$createFile(client.CustomerBarCode_image_original_name     ,   "CustomerBarCode_image_update")
                let CustomerBarCode_image_display_update                        =   document.getElementById("CustomerBarCode_image_display_update")
                CustomerBarCode_image_display_update.src                        =   "/uploads/clients/"+client.id+"/"+client.CustomerBarCode_image
            }

            if(this.client.facade_image_original_name) {
                this.$createFile(client.facade_image_original_name              ,   "facade_image_update")
                let facade_image_display_update                                 =   document.getElementById("facade_image_display_update")
                facade_image_display_update.src                                 =   "/uploads/clients/"+client.id+"/"+client.facade_image
            }

            if(this.client.in_store_image_original_name) {
                this.$createFile(client.in_store_image_original_name            ,   "in_store_image_update")
                let in_store_image_display_update                               =   document.getElementById("in_store_image_display_update")
                in_store_image_display_update.src                               =   "/uploads/clients/"+client.id+"/"+client.in_store_image
            }
        },

        async getComboData() {

            if(this.districts_all) {
                this.willayas   =   this.districts_all  
            }

            else {
                const res_2     =   await this.$callApi("post"  ,   "/rtm_willayas"     ,   null)
                this.willayas   =   res_2.data
            }
        },

        async getUsers() {

            if(this.users_all) {
                this.users          =   this.users_all  
            }

            else {
                const res_1         =   await this.$callApi("post"  ,   "/users/combo"  ,   null)
                this.users          =   res_1.data
            }
        },

        async getCites() {

            // Show Loading Page
            this.$showLoadingPage()

            const res_3     =   await this.$callApi("post"  ,   "/rtm_willayas/"+this.client.DistrictNo+"/rtm_cites"         ,   null)
            this.cites      =   res_3.data

            this.client.CityNo      =   ""

            // Hide Loading Page
            this.$hideLoadingPage()
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

                    return this.users[i].nom
                }                
            }
        },

        //

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

        //

        async customerBarCodeImage() {

            const CustomerBarCode_image  =   document.getElementById("CustomerBarCode_image_update").files[0];

            if(CustomerBarCode_image) {

                this.client.CustomerBarCode_image_updated           =   true

                this.client.CustomerBarCode_image_original_name     =   CustomerBarCode_image.name
                this.client.CustomerBarCode_image                   =   await this.$compressImage(CustomerBarCode_image)

                //

                let CustomerBarCode_image_base64                    =   await this.$imageToBase64(this.client.CustomerBarCode_image)

                let CustomerBarCode_image_display                   =   document.getElementById("CustomerBarCode_image_display_update")
                this.base64ToImage(CustomerBarCode_image_base64, CustomerBarCode_image_display)
            }

            else {

                this.client.CustomerBarCode_image_original_name     =   ""
                this.client.CustomerBarCode_image                   =   ""

                this.client.CustomerBarCode_image_updated           =   true

                const CustomerBarCode_image_display_update          =   document.getElementById("CustomerBarCode_image_display_update")

                if(CustomerBarCode_image_display_update) {

                    CustomerBarCode_image_display_update.src            =   ""
                }

                //

                const CustomerBarCode_image_display_update_container   =   document.getElementById("CustomerBarCode_image_display_update_container")

                if(CustomerBarCode_image_display_update_container) {

                    CustomerBarCode_image_display_update_container.style.display   =   "none"
                }
            }
        },

        async facadeImage() {

            const facade_image  =   document.getElementById("facade_image_update").files[0];

            if(facade_image) {

                this.client.facade_image_updated            =   true

                this.client.facade_image_original_name      =   facade_image.name
                this.client.facade_image                    =   await this.$compressImage(facade_image)

                //

                let facade_image_base64                     =   await this.$imageToBase64(this.client.facade_image)

                let facade_image_display                    =   document.getElementById("facade_image_display_update")
                this.base64ToImage(facade_image_base64, facade_image_display)
            }

            else {

                this.client.facade_image_original_name      =   ""
                this.client.facade_image                    =   ""

                this.client.facade_image_updated            =   true

                const facade_image_display_update           =   document.getElementById("facade_image_display_update")

                if(facade_image_display_update) {

                    facade_image_display_update.src            =   ""
                }

                //

                const facade_image_display_update_container   =   document.getElementById("facade_image_display_update_container")

                if(facade_image_display_update_container) {

                    facade_image_display_update_container.style.display   =   "none"
                }
            }
        },

        async inStoreImage() {

            const in_store_image  =   document.getElementById("in_store_image_update").files[0];

            if(in_store_image) {

                this.client.in_store_image_updated          =   true

                this.client.in_store_image_original_name    =   in_store_image.name
                this.client.in_store_image                  =   await this.$compressImage(in_store_image)
                
                //

                let in_store_image_base64                   =   await this.$imageToBase64(this.client.in_store_image)

                let in_store_image_display                  =   document.getElementById("in_store_image_display_update")
                this.base64ToImage(in_store_image_base64, in_store_image_display)
            }

            else {

                this.client.in_store_image_original_name    =   ""
                this.client.in_store_image                  =   ""

                this.client.in_store_image_updated          =   true

                const in_store_image_display_update         =   document.getElementById("in_store_image_display_update")

                if(in_store_image_display_update) {

                    in_store_image_display_update.src           =   ""
                }

                //

                const in_store_image_display_update_container   =   document.getElementById("in_store_image_display_update_container")

                if(in_store_image_display_update_container) {

                    in_store_image_display_update_container.style.display   =   "none"
                }
            }
        },

        //    

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

        //

        base64ToImage(image_base64, image_display_div) {

            this.$base64ToImage(image_base64, image_display_div)
        },

        //

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

        //  //  //

        setStatus() {

            if(this.client.OpenCustomer ==  "Ferme") {
                this.client.status     =   "ferme"
            }

            else {
                this.client.status     =   ""
            }
        }
    }
};

</script>