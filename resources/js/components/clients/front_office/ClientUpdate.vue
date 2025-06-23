<template>

    <GPSErrorComponent v-show="show_gps_error"></GPSErrorComponent>

    <div class="mt-3">

        <div class="page-header mb-2">
            <div class="container w-100">
                <h6 class="text-center mb-0">
                    Modifier le Client : {{ client.old_CustomerNameE }} 
                </h6>
            </div>
        </div>

        <div class="page-header mb-0">
            <div class="container w-100">
                <div v-if="client.status_original   ==  'nonvalidated'">
                    <span class="text-small text-danger">{{ client.nonvalidated_details }}</span>
                </div>
            </div>
        </div>

        <hr />

        <div class="container"  style="height : 60vh; overflow : auto;">
            <form>
                <div class="slideshow-container">

                    <!-- OpenCustomer -->
                    <div v-show="false" class="mySlides slide_1 mt-3">
                        <div>
                            <div class="mb-1">
                                <label for="NewCustomer"        class="form-label fw-bold">Nouveau Client</label>
                                <select                         class="form-select"         id="NewCustomer"                 v-model="client.NewCustomer"       :disabled="client.status_original    ==  'validated'">
                                    <option     value="Nouveau Client">Nouveau Client</option>
                                    <option     value="Client Existant">Client Existant</option>
                                </select>
                            </div>

                            <div class="mb-1">
                                <label for="text"               class="form-label fw-bold">Client Ouvert</label>
                                <select                         class="form-select"         id="OpenCustomer"                 v-model="client.OpenCustomer"     @change="setStatus()"   :disabled="client.status_original    ==  'validated'"   >
                                    <option     value="Ferme">Ferme</option>
                                    <option     value="Ouvert">Ouvert</option>
                                </select>
                            </div>
                        </div>

                        <!--  -->

                        <div class="mt-5">
                            <ul class="pl-3">
                                <li>Sélectionnez si le client est ouvert ou non (exemple : <span class="fw-bold">Oui</span>).</li>
                            </ul>
                        </div>
                    </div>

                    <!-- CustomerIdentifier -->
                    <div v-show="false" class="mySlides slide_2 mt-3">
                        <div>
                            <label for="CustomerIdentifier" class="form-label fw-bold">ID Client</label>
                            <input type="text"              class="form-control"        id="CustomerIdentifier"          v-model="client.CustomerIdentifier">
                        </div>

                        <!--  -->

                        <div class="mt-5">
                            <ul class="pl-3">
                                <li>Saisissez l'identifiant du client (exemple : <span class="fw-bold">23</span>).</li>
                            </ul>
                        </div>
                    </div>

                    <!-- CustomerCode -->
                    <div v-if="client.OpenCustomer  === 'Ouvert'" v-show="false" class="mySlides slide_3 mt-3">
                        <div>
                            <label for="CustomerBarCode_image"  class="form-label fw-bold">Code-Barre</label>
                            <div v-show="client.CustomerCode   ==  ''"     class="mt-1 p-0">
                                <div    id="reader" class="scanner_reader w-100"></div>
                            </div>

                            <div v-show="client.CustomerCode   !=  ''"     class="mt-1 p-0">
                                <div    id="result"></div>
                            </div>

                            <div v-show="client.CustomerCode   !=  ''"     class="mt-1 p-0">
                                <div    id="customerCode_value"              class="text-center">
                                    <span class="">Code-Barre : {{ client.CustomerCode }}</span>
                                </div>
                            </div>
                        </div>

                        <!--  -->

                        <div class="mt-1 mb-1 w-100">
                            <div class="w-100" id="refresh_client_barcode_button">
                                <button type="button" class="btn btn-primary w-100"     @click="setBarCodeReader()"     :disabled="client.status_original    ==  'validated'">Capturer Code-Barre</button>
                            </div>
                        </div>

                        <!--  -->

                        <div class="mt-5">
                            <ul class="pl-3">
                                <li class="mt-3">Cliquez sur le bouton <button type="button" class="btn btn-primary p-1" style="font-size : 11px">Capturer Code-Barre</button> pour scanner le code-barre à l'aide de la caméra de votre téléphone.</li>
                                <li class="mt-3">🚨 Attention : Veuillez ne pas scanner de code-barres contenant les caractères suivants :<span class="restricted-chars">>/ \ : * ? " &lt; &gt; | &amp; (espace)</span></li>
                            </ul>
                        </div>
                    </div>

                    <!-- CustomerBarCode_image -->
                    <div v-if="client.OpenCustomer  === 'Ouvert'" v-show="false" class="mySlides slide_4 mt-3">
                        <div>
                            <label for="CustomerBarCode_image_update"  class="form-label fw-bold">Code-Barre Image</label>
                            <button type="button"                       class="btn btn-secondary w-100 mb-1" @click="$clickFile('CustomerBarCode_image_update')"        :disabled="client.status_original    ==  'validated'"><i class="mdi mdi-camera"></i></button>

                            <input type="file"                          class="form-control"    id="CustomerBarCode_image_update"                   accept="image/*"    capture         @change="customerBarCodeImage()"    :disabled="client.status_original    ==  'validated'"    style="display:none">
                            <img                                                                id="CustomerBarCode_image_display_update"           src=""              class="w-100">
                        </div>

                        <!--  -->

                        <div class="mt-5">
                            <ul class="pl-3">
                                <li class="mt-3">Cliquez sur le bouton <button type="button" class="btn btn-secondary p-1" style="font-size : 11px"><i class="mdi mdi-camera"></i></button> pour ajouter la photo du "code-barre".</li>
                            </ul>
                        </div>
                    </div>

                    <!-- CustomerNameE -->
                    <div v-if="client.OpenCustomer  === 'Ouvert'" v-show="false" class="mySlides slide_5 mt-3">
                        <div>
                            <label for="CustomerNameE"      class="form-label fw-bold">Nom et Prénom de l'Acheteur</label>
                            <input type="text"              class="form-control"        id="CustomerNameE"          v-model="client.CustomerNameE"  :disabled="client.status_original    ==  'validated'">
                        </div>

                        <!--  -->

                        <div class="mt-5">
                            <ul class="pl-3">
                                <li>Saisissez le nom et prénom du client en <span class="fw-bold">MAJISCULE</span> (exemple : <span class="fw-bold">BAKHNACH IMAD</span>).</li>
                            </ul>
                        </div>
                    </div>

                    <!-- CustomerNameA -->
                    <div v-show="false" class="mySlides slide_6 mt-3">
                        <div>
                            <label for="CustomerNameA"      class="form-label fw-bold">Raison Sociale</label>
                            <input type="text"              class="form-control"        id="CustomerNameA"          v-model="client.CustomerNameA"  :disabled="client.status_original    ==  'validated'">
                        </div>

                        <!--  -->

                        <div class="mt-5">
                            <ul class="pl-3">
                                <li>Saisissez le nom du magasin en <span class="fw-bold">MAJISCULE</span>, généralement affiché sur sa façade (exemple : <span class="fw-bold">SUPERETTE ESSALEM</span>).</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Tel -->
                    <div v-if="client.OpenCustomer  === 'Ouvert'" v-show="false" class="mySlides slide_7 mt-3">
                        <div>
                            <label for="Tel"                class="form-label fw-bold">Téléphone</label>
                            <input type="text"              class="form-control"        id="Tel"                    v-model="client.Tel"            :disabled="client.status_original    ==  'validated'">
                        </div>

                        <!--  -->

                        <div class="mt-5">
                            <ul class="pl-3">
                                <li>Saisissez le numéro de téléphone du client au format correct (10 chiffres, commençant par 0) (exemple : <span class="fw-bold">0654123487</span>).</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Address -->
                    <div v-show="false" class="mySlides slide_8 mt-3">
                        <div>
                            <label for="Address"            class="form-label fw-bold">Adresse</label>
                            <input type="text"              class="form-control"        id="Address"                v-model="client.Address"        :disabled="client.status_original    ==  'validated'">
                        </div>

                        <!--  -->

                        <div class="mt-5">
                            <ul class="pl-3">
                                <li>Saisissez l'adresse du magasin (exemple : <span class=fw-bold>Rue Mohamed Belouizdad - Alger Centre</span>).</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Neighborhood -->
                    <div v-show="false" class="mySlides slide_9 mt-3">
                        <div>
                            <label for="Neighborhood"       class="form-label fw-bold">Quartier</label>
                            <input type="text"              class="form-control"        id="Neighborhood"           v-model="client.Neighborhood"   :disabled="client.status_original    ==  'validated'">
                        </div>

                        <!--  -->

                        <div class="mt-5">
                            <ul class="pl-3">
                                <li>Saisissez le nom du quartier du magasin (exemple : <span class="fw-bold">Belcourt</span>).</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Landmark -->
                    <div v-show="false" class="mySlides slide_10 mt-3">
                        <div>
                            <label for="Landmark"           class="form-label fw-bold">Point de Repere</label>
                            <textarea                       class="form-control"        id="Landmark"   rows="3"    v-model="client.Landmark"       :disabled="client.status_original    ==  'validated'"></textarea>
                        </div>

                        <!--  -->

                        <div class="mt-5">
                            <ul class="pl-3">
                                <li>Saisissez un point de repère pour le client (exemple : <span class="fw-bold">à côté de la Grande Poste</span>).</li>
                            </ul>
                        </div>
                    </div>

                    <!-- DistrictNo --> 
                    <div v-show="false" class="mySlides slide_11 mt-3">
                        <div>
                            <label for="DistrictNo"         class="form-label fw-bold">Willaya</label>
                            <select                         class="form-select"         id="DistrictNo"             v-model="client.DistrictNo"     @change="getCites()">
                                <option v-for="willaya in willayas" :key="willaya.DistrictNo" :value="willaya.DistrictNo">{{willaya.DistrictNameE}} ({{willaya.DistrictNo}})</option>
                            </select>
                        </div>

                        <!--  -->

                        <div class="mt-5">
                            <ul class="pl-3">
                                <li>La willaya où se situe le magasin du client est sélectionnée automatiquement (exemple : <span class="fw-bold">Alger</span>).</li>
                            </ul>
                        </div>
                    </div>

                    <!-- CityNo -->
                    <div v-show="false" class="mySlides slide_12 mt-3">
                        <div>
                            <label for="CityNo"             class="form-label fw-bold">Commune</label>
                            <select                         class="form-select"         id="CityNo"                 v-model="client.CityNo"                                 :disabled="client.status_original    ==  'validated'">
                                <option v-for="cite in cites" :key="cite.CITYNO" :value="cite.CITYNO">{{cite.CityNameE}} ({{cite.CITYNO}})</option>
                            </select>
                        </div>

                        <!--  -->

                        <div class="mt-5">
                            <ul class="pl-3">
                                <li>Saisissez la commune où se situe le magasin (exemple : <span class="fw-bold">Saoula</span>).</li>
                            </ul>
                        </div>
                    </div>

                    <!-- CustomerType -->
                    <div v-show="false" class="mySlides slide_13 mt-3">
                        <div>
                            <label for="text"               class="form-label fw-bold">Type de Magasin</label>
                            <select                         class="form-select"         id="CustomerType"                 v-model="client.CustomerType"                     :disabled="client.status_original    ==  'validated'">
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

                        <!--  -->

                        <div class="mt-5">
                            <ul class="pl-3">
                                <li>Selectionnez le type de magasin (exemple : <span class="fw-bold">AG Self</span>).</li>
                                <li><span class="fw-bold">AG Self</span> : </li>	Se référer au frigo du fromage : Se sert soi-même en libre-service
                                <li><span class="fw-bold">AG Tradi</span> : </li>	Se référer au frigo du fromage : Frigo traditionnel le vendeur qui distribue au client 
                                <li><span class="fw-bold">Superette</span> : </li> 	Condition :  il faut minimum 2 caisses ACTIVE 
                                <li><span class="fw-bold">Gros Mix</span> : </li>	Condition : le pdv n'ouvre pas les cartons et vends uniquement aux détaillants
                                <li><span class="fw-bold">Gros Sec</span> : </li>	Condition : le pdv n'ouvre pas les cartons et vends uniquement aux détaillants
                                <li><span class="fw-bold">Gros frais</span> : </li>	Condition : le pdv n'ouvre pas les cartons et vends uniquement aux détaillants
                                <li><span class="fw-bold">Demis Gros Mix</span> : </li>	Condition : le pdv ouvre les carton pour vendre à l'unité
                                <li><span class="fw-bold">Demis Gros Sec</span> : </li>	Condition : le pdv ouvre les carton pour vendre à l'unité
                                <li><span class="fw-bold">Demis Gros frais</span> : </li>	Condition : le pdv ouvre les carton pour vendre à l'unité
                                <li><span class="fw-bold">Demis Gros frais</span> : </li>x	Changer le type en : Produit laitier et lben
                            </ul>
                        </div>
                    </div>

                    <!-- BrandSourcePurchase -->
                    <div v-if="client.OpenCustomer  === 'Ouvert'" v-show="false" class="mySlides slide_14 mt-3">
                        <div>
                            <label for="text"               class="form-label fw-bold">Source d'Achat</label>
                            <select                         class="form-select"         id="BrandSourcePurchase"                 v-model="client.BrandSourcePurchase"       :disabled="client.status_original    ==  'validated'">
                                <option     value="DD">DD</option>
                                <option     value="DI">DI</option>
                                <option     value="Pas d'achat">Pas d'achat</option>
                            </select>
                        </div>

                        <!--  -->

                        <div class="mt-5">
                            <ul class="pl-3">
                                <li>Selectionnez la source d'achat du client.

                                    <ul class="pt-3">
                                        <li><span class="fw-bold">DD</span> : Achat directement auprès du distributeur.</li>
                                        <li><span class="fw-bold">DI</span> : Achat auprès d'un grossiste ou de vendeurs mobiles.</li>
                                        <li><span class="fw-bold">Pas d'achat</span> : Le produit n'est pas disponible dans le magasin et le client ne l'achète pas.</li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- NbrAutomaticCheckouts -->
                    <div v-if="client.OpenCustomer  === 'Ouvert'" v-show="false" class="mySlides slide_15 mt-3">
                        <div>
                            <label for="NbrAutomaticCheckouts"       class="form-label fw-bold">Nombre de caisses automatique</label>

                            <select                         class="form-select"         id="NbrAutomaticCheckouts"                 v-model="client.NbrAutomaticCheckouts">
                                <option     :value="'Plus de 1'">Plus de 1</option>
                                <option     :value="'1'">1</option>
                                <option     :value="'Pas de caisse automatique'">Pas de caisse automatique</option>
                            </select>
                        </div>

                        <!--  -->

                        <div class="mt-5">
                            <ul class="pl-3">
                                <li>Sélectionnez le nombre de caisses automatiques dans le magasin (exemple : <span class="fw-bold">1</span>).</li>
                            </ul>
                        </div>
                    </div>

                    <!-- SuperficieMagasin -->
                    <div v-if="client.OpenCustomer  === 'Ouvert'" v-show="false" class="mySlides slide_16 mt-3">
                        <div>
                            <label for="SuperficieMagasin"  class="form-label fw-bold">Superficie du magasin</label>

                            <select                         class="form-select"         id="SuperficieMagasin"                 v-model="client.SuperficieMagasin">
                                <option     :value="'Moins de 30 M'">Moins de 30 M</option>
                                <option     :value="'DE 30 a 60'">DE 30 a 60</option>
                                <option     :value="'DE 30 a 100'">DE 30 a 100</option>
                                <option     :value="'DE 100 a 200'">DE 100 a 200</option>
                                <option     :value="'Plus de 200'">Plus de 200</option>
                            </select>
                        </div>

                        <!--  -->

                        <div class="mt-5">
                            <ul class="pl-3">
                                <li>Sélectionnez la superficie du magasin (exemple : <span class="fw-bold">Moins de 30 M</span>).</li>
                            </ul>
                        </div>
                    </div>

                    <!-- JPlan -->
                    <div v-show="false" class="mySlides slide_17 mt-3">
                        <div>
                            <label for="JPlan"              class="form-label fw-bold">Nom de Vendeur</label>
                            <input type="text"              class="form-control"        id="JPlan"          v-model="client.JPlan"                                          :disabled="client.status_original    ==  'validated'">
                        </div>

                        <!--  -->

                        <div class="mt-5">
                            <ul class="pl-3">
                                <li>Saisissez le nom du vendeur habituel qui visite le magasin, en <span class="fw-bold">MAJISCULE</span> (exemple : <span class="fw-bold">BOULEKRINAT Omar</span>).</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Frequency -->
                    <div v-show="false" class="mySlides slide_18 mt-3">
                        <div>
                            <label for="Frequency"          class="form-label fw-bold">Frequency</label>

                            <select                         class="form-select"         id="Frequency"                 v-model="client.Frequency">
                                <option     :value="'1 par Semaine(7J)'">1 par Semaine(7J)</option>
                                <option     :value="'1 par 15J'">1 par 15J</option>
                            </select>
                        </div>

                        <!--  -->

                        <div class="mt-5">
                            <ul class="pl-3">
                                <li>Selectionnez la frequence de visite du client (exemple : <span class="fw-bold">1 par 15J</span>).</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Journee -->
                    <div v-show="false" class="mySlides slide_19 mt-3">
                        <div>
                            <label for="Journee"            class="form-label fw-bold">Journee</label>

                            <select                         class="form-select"         id="Journee"    v-model="client.Journee"    :disabled="client.status_original    ==  'validated'">
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

                        <!--  -->

                        <div class="mt-5">
                            <ul class="pl-3">
                                <li>Selectionnez la journée de visite du client (exemple : <span class="fw-bold">Samedi 1 (Jour 1)</span>).</li>
                            </ul>
                        </div>
                    </div>

                    <!-- BrandAvailability + AvailableBrands + In-Store Image -->
                    <div v-if="client.OpenCustomer  === 'Ouvert'" v-show="false" class="mySlides slide_20 mt-3">
                        <div>
                            <div class="mb-1">
                                <label for="text"               class="form-label fw-bold">Disponibilité Produits</label>
                                <select                         class="form-select"         id="BrandAvailability"                 v-model="client.BrandAvailability"           :disabled="client.status_original    ==  'validated'"   @change="brandAvailabilityChanged()">
                                    <option     value='Non'>Non</option>
                                    <option     value='Oui'>Oui</option>
                                </select>
                            </div>

                            <div class="mt-1"   v-show="client.BrandAvailability  ==  'Oui'">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="Loya Lait" id="Loya Lait"  v-model="client.AvailableBrands">
                                    <label class="form-check-label" for="Loya Lait">
                                        Loya Lait
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="Loya Fromage" id="Loya Fromage"  v-model="client.AvailableBrands"  >
                                    <label class="form-check-label" for="Loya Fromage">
                                        Loya Fromage
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="Berbere" id="Berbere"  v-model="client.AvailableBrands">
                                    <label class="form-check-label" for="Berbere">
                                        Berbere
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="Cowbell" id="Cowbell"  v-model="client.AvailableBrands">
                                    <label class="form-check-label" for="Cowbell">
                                        Cowbell
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="Twisco" id="Twisco"  v-model="client.AvailableBrands"  >
                                    <label class="form-check-label" for="Twisco">
                                        Twisco
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="PromaCafé" id="PromaCafé"  v-model="client.AvailableBrands">
                                    <label class="form-check-label" for="PromaCafé">
                                        PromaCafé
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="Amila" id="Amila"  v-model="client.AvailableBrands"    >
                                    <label class="form-check-label" for="Amila">
                                        Amila
                                    </label>
                                </div>
                            </div>

                            <div class="mt-1"   v-show="client.BrandAvailability  ==  'Oui'">
                                <label for="in_store_image_update"  class="form-label fw-bold">Image In-Store</label>
                                <button type="button"               class="btn btn-secondary w-100 mb-1" @click="$clickFile('in_store_image_update')"   :disabled="client.status_original    ==  'validated'"><i class="mdi mdi-camera"></i></button>

                                <input type="file"                  class="form-control"    id="in_store_image_update"             accept="image/*"     capture  @change="inStoreImage()"    :disabled="client.status_original    ==  'validated'"    style="display:none">
                                <img                                                        id="in_store_image_display_update"     src=""                       class="w-100">
                            </div>
                        </div>

                        <!--  -->

                        <div class="mt-5">
                            <ul class="pl-3">
                                <li>Sélectionnez si le produit est disponible ou non (exemple : <span class="fw-bold">Oui</span>).</li>
                                <li>Si le produit est disponible, veuillez prendre une photo du rayon où le produit est placé dans le magasin en cliquant sur le bouton <button type="button" class="btn btn-secondary p-1" style="font-size : 11px"><i class="mdi mdi-camera"></i></button></li>
                            </ul>
                        </div>
                    </div>

                    <!-- Comment -->
                    <div v-show="false" class="mySlides slide_21 mt-3">
                        <div>
                            <label      for="comment" class="form-label fw-bold">Commentaire</label>
                            <textarea   class="form-control"    id="comment"    rows="3"    v-model="client.comment"    :disabled="client.status_original    ==  'validated'"></textarea>
                        </div>

                        <!--  -->

                        <div class="mt-5">
                            <ul class="pl-3">
                                <li>Ajoutez un commentaire si vous souhaitez préciser quelque chose (exemple : <span class="fw-bold">Le magasin sera en rénovation à partir de demain, retour prévu dans deux semaines.</span>).</li>
                            </ul>
                        </div>
                    </div>

                    <!-- facade_image -->
                    <div v-show="false" class="mySlides slide_22 mt-3">
                        <div>
                            <label for="facade_image_update"    class="form-label fw-bold">Image Facade</label>
                            <button type="button"               class="btn btn-secondary w-100 mb-1" @click="$clickFile('facade_image_update')"     :disabled="client.status_original    ==  'validated'"><i class="mdi mdi-camera"></i></button>

                            <input type="file"                  class="form-control"    id="facade_image_update"               accept="image/*"     capture  @change="facadeImage()"     :disabled="client.status_original    ==  'validated'"    style="display:none">
                            <img                                                        id="facade_image_display_update"       src=""                       class="w-100">
                        </div>

                        <!--  -->

                        <div class="mt-5">
                            <ul class="pl-3">
                                <li>Prenez une photo bien cadrée de la façade du magasin en cliquant sur le bouton <button type="button" class="btn btn-secondary p-1" style="font-size : 11px"><i class="mdi mdi-camera"></i></button></li>
                            </ul>
                        </div>
                    </div>

                    <!-- GPS -->
                    <div v-show="false" class="mySlides slide_23 mt-3">
                        <div>
                            <label for="CustomerCode"       class="form-label fw-bold">Detecter la Position Actuel <button class="btn btn-sm" @click.prevent="showPositionOnMap('show_map')"    :disabled="((client.status_original    ==  'validated')||(check_gps_clicked))"><i class="mdi mdi-reload"></i></button></label>
                            <p class="text-secondary text-small mb-1">Latitude : {{ client.Latitude }}</p>
                            <p class="text-secondary text-small mb-1">Longitude : {{ client.Longitude }}</p>

                            <div id="show_map" style="width: 100%; height: 200px;"></div>

                            <hr />

                            <h5>Clients a Proximité</h5>

                            <hr />

                            <table class="table table-bordered mt-1">
                                <thead>
                                    <tr>
                                        <th class="text-wrap">Acheteur</th>
                                        <th class="text-wrap">Raison Social</th>
                                        <th class="text-wrap">Type</th>
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

                        <!--  -->

                        <div class="mt-5">
                            <ul class="pl-3">
                                <li>Vérifiez si votre position GPS est correcte. Si ce n'est pas le cas, cliquez sur <i class="mdi mdi-reload"></i> pour l'actualiser.</li>
                                <li>Le tableau affiche la liste des clients proches de votre position actuelle.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!--  -->

        <div v-if="point_is_inside_user_polygons">

            <div class="container start-0 w-100"          style="bottom: 65px;">
                <div class="row justify-content-center">
                    <div class="col-6 mt-3">
                        <button v-if="((slideIndex    >   1)&&(getIsOnline))"                   type="button" class="btn btn-secondary w-100"   @click="plusSlides(-1)">Precedent</button>
                    </div>

                    <div class="col-6 mt-3">
                        <button v-if="((slideIndex    <   total_questions)&&(getIsOnline))"     type="button" class="btn btn-primary w-100"     @click="plusSlides(1)">Suivant</button>
                    </div>
                </div>
            </div>

            <div class="container start-0 w-100 mb-3"     style="bottom: 0px;">
                <div class="row justify-content-center">
                    <div class="col mt-3">
                        <button v-if="((slideIndex  ==  total_questions)&&(client.status_original   !=  'validated')&&(getIsOnline))"      type="button" class="btn btn-primary w-100"     @click="sendData()">Confirmer</button>
                    </div>
                </div>
            </div>            

        </div>

    </div>

</template>

<script>

import {mapGetters, mapActions} from    "vuex"

//

import GPSErrorComponent        from    "@/template/partials/GPSErrorComponent.vue"

export default {

    data() {
        return {

            client      :   {

                // Images   
                CustomerBarCode_image                   :   '',
                facade_image                            :   '',
                in_store_image                          :   '',

                CustomerBarCode_image_original_name     :   '',
                facade_image_original_name              :   '',
                in_store_image_original_name            :   '',

                //  
                CustomerBarCode_image_updated           :   false,
                facade_image_updated                    :   false,
                in_store_image_updated                  :   false,

                // Client
                id                  :   '',

                NewCustomer         :   '',

                OpenCustomer        :   '',

                CustomerCode        :   '',

                old_CustomerNameE   :   '',

                CustomerNameE       :   '',
                CustomerNameA       :   '',

                Tel                 :   '',
                tel_status          :   'nonvalidated',
                tel_comment         :   '',

                Address             :   '',
                Neighborhood        :   '',
                Landmark            :   '',

                DistrictNo          :   '',
                DistrictNameE       :   '',

                CityNo              :   '',
                CityNameE           :   '',

                Latitude            :   '',
                Longitude           :   '',

                // Type
                CustomerType        :   '',
                BrandAvailability   :   'Non',
                BrandSourcePurchase :   '',

                // Journey Plan
                JPlan               :   '',
                Journee             :   '',

                // 
                status                  :   'pending',
                status_original         :   '',
                nonvalidated_details    :   '', 

                // Comment
                comment                 :   ''
            },

            willayas                        :   []      ,
            cites                           :   []      ,

            // 
            liste_journey_plan              :   []      ,
            liste_journee                   :   []      ,
            liste_type_client               :   []      ,

            //

            all_clients                     :   []      ,
            close_clients                   :   []      ,
            min_distance                    :   0.05    ,

            //

            scanner                         :   null    ,

            //

            point_is_inside_user_polygons   :   true    ,

            //

            slideIndex                      :   1       ,

            //

            total_questions                 :   23      ,

            //
            check_gps_clicked               :   false   ,
            show_gps_error                  :   false   ,
            watchGPS                        :   null
        }
    },

    computed : {

        ...mapGetters({
            getUser         :   'authentification/getUser'  ,
            getIsOnline     :   'internet/getIsOnline'
        }),
    },

    components : {

        GPSErrorComponent   :   GPSErrorComponent
    },

    beforeUnmount() {

        if(this.scanner) {

            this.scanner.clear().then(_ => {

            }).catch(error => {

            });
        }

        if (this.watchGPS) {
            navigator.geolocation.clearWatch(this.watchGPS);
            this.watchGPS = null; // Reset the variable to null after clearing
        }
    },

    async mounted() {

        //
        this.slideIndex     =   this.$showSlides(this.slideIndex, this.slideIndex);

        //
        await this.getData()

        //
        this.setTotalQuestions()
    },  

    methods : {

        ...mapActions("client" ,  [
            "setUpdateClientAction"   ,
        ]),

        //

        async sendData() {

            // Validation de la question
            let validation          =   this.validationQuestion()

            if(validation   ==  true)  {

                this.$showLoadingPage()

                if(typeof this.willayas != "undefined") {

                    // Set Client
                    this.client.DistrictNameE   =   this.getDistrictNameE(this.client.DistrictNo)
                }

                if(typeof this.cites != "undefined") {

                    // Set 
                    this.client.CityNameE       =   this.getCityNameE(this.client.CityNo)
                }

                let formData = new FormData();

                if(this.client.OpenCustomer  === 'Ouvert') {

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

                    formData.append("comment"                               ,   this.client.comment)
                }

                else {
                    formData.append("NewCustomer"                           ,   this.client.NewCustomer)
                    formData.append("OpenCustomer"                          ,   this.client.OpenCustomer)

                    formData.append("CustomerIdentifier"                    ,   this.client.CustomerIdentifier)
                    formData.append("CustomerCode"                          ,   '')
                    formData.append("CustomerNameE"                         ,   '')
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
                    formData.append("Tel"                                   ,   '')
                    formData.append("tel_status"                            ,   'nonvalidated')
                    formData.append("tel_comment"                           ,   '')
                    formData.append("CustomerType"                          ,   this.client.CustomerType)
                    formData.append("BrandAvailability"                     ,   'Non')
                    formData.append("BrandSourcePurchase"                   ,   '')

                    formData.append("JPlan"                                 ,   this.client.JPlan)
                    formData.append("Journee"                               ,   this.client.Journee)

                    formData.append("Frequency"                             ,   this.client.Frequency)
                    formData.append("SuperficieMagasin"                     ,   this.client.SuperficieMagasin)
                    formData.append("NbrAutomaticCheckouts"                 ,   '')
                    formData.append("AvailableBrands"                       ,   JSON.stringify([]))

                    formData.append("CustomerBarCode_image"                 ,   '')
                    formData.append("facade_image"                          ,   this.client.facade_image)
                    formData.append("in_store_image"                        ,   '')

                    formData.append("CustomerBarCode_image_original_name"   ,   '')
                    formData.append("facade_image_original_name"            ,   this.client.facade_image_original_name)
                    formData.append("in_store_image_original_name"          ,   '')

                    formData.append("status"                                ,   'pending')
                    formData.append("nonvalidated_details"                  ,   '')

                    formData.append("comment"                               ,   this.client.comment)
                }

                const res                   =   await this.$callApi("post"  ,   "/route_import/"+this.$route.params.id_route_import+"/clients/"+this.client.id+"/update",   formData)

                if(res.status===200){

                    // Hide Loading Page
                    this.$hideLoadingPage()

                    // Send Feedback
                    this.$feedbackSuccess(res.data["header"]    ,   res.data["message"])

                    // Go Back
                    this.$goBack()
                }
                
                else{

                    // Hide Loading Page
                    this.$hideLoadingPage()

                    // Send Errors
                    this.$showErrors("Error !", res.data.errors)
                }
            }

            else {

                this.$showErrors("Error !"  ,   ["Veuillez répondre en respectant les conditions des questions avant de valider !"])
            }
        },

        //

        async getData() {

            const res           =   await this.$callApi("post"  ,   "/route/obs/route_import/"+this.$route.params.id_route_import+"/details",   null)
            this.all_clients    =   res.data.route_import.data

            //

            await this.getClientData()  
            await this.getComboData()
        },

        async getClientData() {

            const res                                           =   await this.$callApi("post"  ,   "/route_import/"+this.$route.params.id_route_import+"/clients/"+this.$route.params.id_client+"/show",   null)
            let client                                          =   res.data

            this.client.id                                      =   client.id

            this.client.NewCustomer                             =   client.NewCustomer
            this.client.OpenCustomer                            =   client.OpenCustomer

            this.client.CustomerIdentifier                      =   client.CustomerIdentifier
            this.client.CustomerCode                            =   client.CustomerCode

            this.client.old_CustomerNameE                       =   client.CustomerNameE

            this.client.CustomerNameE                           =   client.CustomerNameE
            this.client.CustomerNameA                           =   client.CustomerNameA
            this.client.Latitude                                =   client.Latitude
            this.client.Longitude                               =   client.Longitude

            this.client.Address                                 =   client.Address
            this.client.Neighborhood                            =   client.Neighborhood
            this.client.Landmark                                =   client.Landmark

            this.client.DistrictNo                              =   client.DistrictNo
            this.client.DistrictNameE                           =   client.DistrictNameE

            await this.getCites()

            this.client.CityNo                                  =   client.CityNo
            this.client.CityNameE                               =   client.CityNameE

            this.client.Tel                                     =   client.Tel
            this.client.tel_status                              =   client.tel_status
            this.client.tel_comment                             =   client.tel_comment

            this.client.CustomerType                            =   client.CustomerType
            this.client.BrandAvailability                       =   client.BrandAvailability
            this.client.BrandSourcePurchase                     =   client.BrandSourcePurchase

            this.client.JPlan                                   =   client.JPlan
            this.client.Journee                                 =   client.Journee

            this.client.Frequency                               =   client.Frequency
            this.client.AvailableBrands                         =   client.AvailableBrands_array_formatted // client.AvailableBrands
            this.client.NbrAutomaticCheckouts                   =   client.NbrAutomaticCheckouts
            this.client.SuperficieMagasin                       =   client.SuperficieMagasin

            // this.client.status                                  =   client.status
            this.client.status_original                         =   client.status
            this.client.nonvalidated_details                    =   client.nonvalidated_details

            this.client.comment                                 =   client.comment

            this.client.CustomerBarCode_image                   =   client.CustomerBarCode_image
            this.client.facade_image                            =   client.facade_image
            this.client.in_store_image                          =   client.in_store_image

            this.client.CustomerBarCode_image_original_name     =   client.CustomerBarCode_image_original_name
            this.client.facade_image_original_name              =   client.facade_image_original_name
            this.client.in_store_image_original_name            =   client.in_store_image_original_name

            // 
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

            //
            this.checkClients()
        },

        async getComboData() {

            const res_3                     =   await this.$callApi("post"  ,   "/route_import/"+this.$route.params.id_route_import+"/districts" ,   null)
            this.willayas                   =   res_3.data
        },

        async getCites() {

            // Show Loading Page
            this.$showLoadingPage()

            const res_3                     =   await this.$callApi("post"  ,   "/rtm_willayas/"+this.client.DistrictNo+"/rtm_cites"         ,   null)
            this.cites                      =   res_3.data

            this.client.CityNo              =   ""

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

        //

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

                this.client.CustomerBarCode_image_updated            =   true

                this.client.CustomerBarCode_image_original_name      =   CustomerBarCode_image.name
                this.client.CustomerBarCode_image                    =   await this.$compressImage(CustomerBarCode_image)

                //

                let CustomerBarCode_image_base64                     =   await this.$imageToBase64(this.client.CustomerBarCode_image)

                let CustomerBarCode_image_display                    =   document.getElementById("CustomerBarCode_image_display_update")
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

                const facade_image_display_update          =   document.getElementById("facade_image_display_update")

                if(facade_image_display_update) {

                    facade_image_display_update.src            =   ""
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

        async inStoreImage() {

            const in_store_image  =   document.getElementById("in_store_image_update").files[0];

            if(in_store_image) {

                this.client.in_store_image_updated      =   true

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
            }
        },

        //     

        base64ToImage(image_base64, image_display_div) {

            this.$base64ToImage(image_base64, image_display_div)
        },

        //

        async checkInsidePolygon() {

            let response                        =   await this.$currentPosition(this.getUser.accuracy)

            if(response.success) {

                //
                this.show_gps_error                 =   false

                //
                this.point_is_inside_user_polygons  =   true
            }

            else {

                //
                this.show_gps_error                 =   true

                //
                await this.$nextTick()

                //
                this.point_is_inside_user_polygons  =   false

                //
                this.$customMessages("GPS Error", "Vérifiez si votre GPS est activée", "error", "OK", "", "", "")

                //
                this.checkGPS()
            }
        },

        async showPositionOnMap(map_id) {

            if(!this.check_gps_clicked) {

                this.check_gps_clicked              =   true

                //
                this.point_is_inside_user_polygons  =   false

                //
                let response                        =   await this.$currentPosition(this.getUser.accuracy)

                if(response.success) {

                    //
                    this.show_gps_error                 =   false

                    //
                    this.client.Latitude                =   response.position.coords.latitude
                    this.client.Longitude               =   response.position.coords.longitude

                    //
                    await this.$nextTick()

                    //
                    let position_marker                 =   this.$showPositionOnMap(map_id, this.client.Latitude, this.client.Longitude, this.getUser.user_territories)

                    //
                    this.checkClients()

                    //
                    // this.point_is_inside_user_polygons  =   this.$checkMarkerInsideUserPolygons(position_marker)
                    this.point_is_inside_user_polygons  =   true

                    // Send Feedback
                    this.$feedbackSuccess('Success !'   ,   'Le GPS a été pris avec succès ')

                    //
                    this.check_gps_clicked              =   false
                }

                else {

                    //
                    this.show_gps_error                 =   true

                    //
                    this.client.Latitude                =   0
                    this.client.Longitude               =   0

                    //
                    await this.$nextTick()

                    //
                    let position_marker                 =   this.$showPositionOnMap(map_id, this.client.Latitude, this.client.Longitude, this.getUser.user_territories)

                    //
                    this.checkClients()

                    //
                    // this.point_is_inside_user_polygons  =   this.$checkMarkerInsideUserPolygons(position_marker)
                    this.point_is_inside_user_polygons  =   false

                    //
                    this.$customMessages("GPS Error", "Vérifiez si votre GPS est activée", "error", "OK", "", "", "")

                    //
                    this.checkGPS(map_id)

                    //
                    this.check_gps_clicked              =   false
                }
            }
        },

        checkGPS(map_id) {

            if (this.watchGPS) return;

            this.watchGPS   =   navigator.geolocation.watchPosition(
                async (pos) => {
                    const accuracy  =   pos.coords.accuracy;
                    
                    console.log(accuracy)

                    //
                    if (Math.ceil(accuracy) <= this.getUser.accuracy) {

                        this.show_gps_error     =   false

                        //
                        this.client.Latitude    =   pos.coords.latitude
                        this.client.Longitude   =   pos.coords.longitude

                        if(map_id) {

                            //
                            await this.$nextTick()

                            //
                            let position_marker                 =   this.$showPositionOnMap(map_id, this.client.Latitude, this.client.Longitude, this.getUser.user_territories)

                            //
                            this.checkClients()

                            //
                            // this.point_is_inside_user_polygons  =   this.$checkMarkerInsideUserPolygons(position_marker)
                            this.point_is_inside_user_polygons  =   true

                            // Send Feedback
                            this.$feedbackSuccess('Success !'   ,   'Le GPS a été pris avec succès ')
                        }

                        else {

                            //
                            await this.$nextTick()

                            //
                            this.checkClients()

                            //
                            // this.point_is_inside_user_polygons  =   this.$checkMarkerInsideUserPolygonsWithoutMap(this.client.Latitude, this.client.Longitude, this.getUser.user_territories)
                            this.point_is_inside_user_polygons  =   true

                            // Send Feedback
                            this.$feedbackSuccess('Success !'   ,   'Le GPS a été pris avec succès ')
                        }

                        //
                        navigator.geolocation.clearWatch(this.watchGPS); // Stop watching
                        this.watchGPS = null; // Reset watcher

                        //
                        this.check_gps_clicked              =   false
                    }
                },
                (err) => {
                    this.show_gps_error     =   true;
                },
                {
                    enableHighAccuracy: true,   // Use high-accuracy mode
                    maximumAge: 0,              // No cached data
                    timeout: 10000,              // Timeout for location retrieval
                }
            );
        },

        //

        async setBarCodeReader() {

            const reader    =   document.getElementById('reader')

            // 
            this.client.CustomerCode    =   ""

            if(reader) {

                reader.style.display        =   "block";

                //
                const requestCamera = async () => {
                    const devices       = await navigator.mediaDevices.enumerateDevices();
                    const videoDevices  = devices.filter(device => device.kind === 'videoinput');
                    const backCamera    = videoDevices.find(device => device.label.includes('back') || device.label.includes('rear'));
                    
                    if (backCamera) {

                        return { exact: backCamera.deviceId };
                    }
                    
                    return undefined;
                };

                //
                this.scanner = new Html5QrcodeScanner('reader', {

                    qrbox   : window.innerWidth < 500 ? { width: 200, height: 200 } : { width: 250, height: 250 },

                    fps     : navigator.hardwareConcurrency > 4 ? 20 : 10,

                    // supportedScanTypes  : [
                    //     Html5QrcodeScanType.SCAN_TYPE_CAMERA
                    // ],
                });

                try {
                    await this.scanner.render(this.success, this.error, requestCamera);
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

            //
            this.scanner.clear();

            document.getElementById('reader').style.display =   "none"
            // Removes reader element from DOM since no longer needed 
        },

        error(err) {

            // Prints any errors to the console
            console.error("");
        },

        //

        plusSlides(current_slide) {

            // Go Next
            if(current_slide    ==  1) {

                // Validation de la question
                let validation          =   this.validationQuestion()

                if(validation   ==  true)  {

                    this.slideIndex     =   this.$plusSlides(this.slideIndex += current_slide, this.slideIndex)
                }

                else {

                    this.$showErrors("Error !"  ,   ["Veuillez répondre en respectant les conditions des questions avant de passer à la page suivante !"])
                    return false;
                }
                //

                // Verifier Si La Question GPS
                if(this.slideIndex  ==  this.total_questions) {

                    //
                    let position_marker                 =   this.$showPositionOnMap("show_map", this.client.Latitude, this.client.Longitude, this.getUser.user_territories)

                    //
                    // this.point_is_inside_user_polygons  =   this.$checkMarkerInsideUserPolygons(position_marker)
                    this.point_is_inside_user_polygons  =   true
                }
                //
            }

            else {

                this.slideIndex     =   this.$plusSlides(this.slideIndex += current_slide, this.slideIndex)
            }
        },

        currentSlide(current_slide) {

            this.slideIndex     =   this.$currentSlide(this.slideIndex = current_slide, this.slideIndex)
        },

        //

        validationQuestion() {

            //Slide 1
            if(this.slideIndex  ==  1) {

                if(((this.client.NewCustomer    === "Nouveau Client")||(this.client.NewCustomer    === "Client Existant"))&&((this.client.OpenCustomer    === "Ferme")||(this.client.OpenCustomer   === "Ouvert"))) {

                    return true
                }

                else {

                    return false
                }
            }

            else {
                if(this.client.OpenCustomer     === "Ferme") {

                    // Slide 2
                    if(this.slideIndex  ==  2) {

                        if(this.client.CustomerIdentifier !==  "") {

                            return true;
                        }

                        else {

                            return false
                        }
                    }

                    // Slide 3
                    if(this.slideIndex  ==  3) {

                        if((this.client.CustomerNameA !== "")&&(this.$isUppercase(this.client.CustomerNameA))) {

                            return true;
                        }

                        else {

                            return false
                        }
                    }

                    // Slide 4
                    if(this.slideIndex  ==  4) {

                        if(this.client.Address !==  "") {

                            return true;
                        }

                        else {

                            return false
                        }
                    }

                    // Slide 5
                    if(this.slideIndex  ==  5) {

                        if(this.client.Neighborhood !==  "") {

                            return true;
                        }

                        else {

                            return false
                        }
                    }

                    // Slide 6
                    if(this.slideIndex  ==  6) {

                        if(this.client.Landmark !==  "") {

                            return true;
                        }

                        else {

                            return false
                        }
                    }

                    // Slide 7
                    if(this.slideIndex  ==  7) {

                        if(this.client.DistrictNo !==  "") {

                            return true;
                        }

                        else {

                            return false
                        }
                    }

                    // Slide 8
                    if(this.slideIndex  ==  8) {

                        if(this.client.CityNo !==  "") {

                            return true;
                        }

                        else {

                            return false
                        }
                    }

                    // Slide 9
                    if(this.slideIndex  ==  9) {

                        if(this.client.CustomerType !==  "") {

                            return true;
                        }

                        else {

                            return false
                        }
                    }

                    // Slide 10
                    if(this.slideIndex  ==  10) {

                        if((this.client.JPlan !== "")&&(this.$isUppercase(this.client.JPlan))) {

                            return true;
                        }

                        else {

                            return false
                        }
                    }

                    // Slide 11
                    if(this.slideIndex  ==  11) {

                        if(this.client.Frequency !==  "") {

                            return true;
                        }

                        else {

                            return false
                        }
                    }

                    // Slide 12
                    if(this.slideIndex  ==  12) {

                        if(this.client.Journee !==  "") {

                            return true;
                        }

                        else {

                            return false
                        }
                    }

                    // Slide 14
                    if(this.slideIndex  ==  14) {

                        if((this.client.facade_image !==  "")&&(this.client.facade_image_original_name   !==  "")) {

                            return true;
                        }

                        else {

                            return false
                        }
                    }

                    // Slide 15
                    if(this.slideIndex  ==  15) {

                        if( ((!isNaN(this.client.Latitude)) &&(this.client.Latitude     !== "") &&(isFinite(Number(this.client.Latitude))))   &&
                            ((!isNaN(this.client.Longitude))&&(this.client.Longitude    !== "") &&(isFinite(Number(this.client.Longitude))))   ) {

                            return true;
                        }

                        else {

                            return false
                        }
                    }

                    return true;
                }

                else {

                    // Slide 2
                    if(this.slideIndex  ==  2) {

                        if(this.client.CustomerIdentifier !==  "") {

                            return true;
                        }

                        else {

                            return false
                        }
                    }

                    // Slide 3
                    if(this.slideIndex  ==  3) {

                        if(this.client.CustomerCode !==  "") {

                            return true;
                        }

                        else {

                            return false
                        }
                    }

                    // Slide 4
                    if(this.slideIndex  ==  4) {

                        if((this.client.CustomerBarCode_image !==  "")&&(this.client.CustomerBarCode_image_original_name  !==  "")) {

                            return true;
                        }

                        else {

                            return false
                        }
                    }

                    // Slide 5
                    if(this.slideIndex  ==  5) {

                        if((this.client.CustomerNameE !== "")&&(this.$isUppercase(this.client.CustomerNameE))) {

                            return true;
                        }

                        else {

                            return false
                        }
                    }

                    // Slide 6
                    if(this.slideIndex  ==  6) {

                        if((this.client.CustomerNameA !== "")&&(this.$isUppercase(this.client.CustomerNameA))) {

                            return true;
                        }

                        else {

                            return false
                        }
                    }

                    // Slide 7
                    if(this.slideIndex  ==  7) {

                        if((this.client.Tel !== "")&&((this.client.Tel.startsWith('05'))||(this.client.Tel.startsWith('06'))||(this.client.Tel.startsWith('07')))&&(!isNaN(parseInt(this.client.Tel)))&&(this.client.Tel.length == 10)){
                            return true;
                        }

                        else {

                            return false
                        }
                    }

                    // Slide 8
                    if(this.slideIndex  ==  8) {

                        if(this.client.Address !==  "") {

                            return true;
                        }

                        else {

                            return false
                        }
                    }

                    // Slide 9
                    if(this.slideIndex  ==  9) {

                        if(this.client.Neighborhood !==  "") {

                            return true;
                        }

                        else {

                            return false
                        }
                    }

                    // Slide 10
                    if(this.slideIndex  ==  10) {

                        if(this.client.Landmark !==  "") {

                            return true;
                        }

                        else {

                            return false
                        }
                    }

                    // Slide 11
                    if(this.slideIndex  ==  11) {

                        if(this.client.DistrictNo !==  "") {

                            return true;
                        }

                        else {

                            return false
                        }
                    }

                    // Slide 12
                    if(this.slideIndex  ==  12) {

                        if(this.client.CityNo !==  "") {

                            return true;
                        }

                        else {

                            return false
                        }
                    }

                    // Slide 13
                    if(this.slideIndex  ==  13) {

                        if(this.client.CustomerType !==  "") {

                            return true;
                        }

                        else {

                            return false
                        }
                    }

                    // Slide 14
                    if(this.slideIndex  ==  14) {

                        if(this.client.BrandSourcePurchase !==  "") {

                            return true;
                        }

                        else {

                            return false
                        }
                    }

                    // Slide 15
                    if(this.slideIndex  ==  15) {

                        if(this.client.NbrAutomaticCheckouts !== "") {

                            return true;
                        }

                        else {

                            return false
                        }
                    }

                    // Slide 16
                    if(this.slideIndex  ==  16) {

                        if(this.client.SuperficieMagasin !== "") {

                            return true;
                        }

                        else {

                            return false
                        }
                    }

                    // Slide 17
                    if(this.slideIndex  ==  17) {

                        if((this.client.JPlan !== "")&&(this.$isUppercase(this.client.JPlan))) {

                            return true;
                        }

                        else {

                            return false
                        }
                    }

                    // Slide 18
                    if(this.slideIndex  ==  18) {

                        if(this.client.Frequency !==  "") {

                            return true;
                        }

                        else {

                            return false
                        }
                    }

                    // Slide 19
                    if(this.slideIndex  ==  19) {

                        if(this.client.Journee !==  "") {

                            return true;
                        }

                        else {

                            return false
                        }
                    }

                    // Slide 20
                    if(this.slideIndex  ==  20) {

                        if(this.client.BrandAvailability   === 'Non') {

                            return true
                        }

                        else {

                            if((this.client.in_store_image !==  "")&&(this.client.in_store_image_original_name   !==  "")&&
                                (this.client.AvailableBrands.length >   0)) {

                                return true
                            }

                            else {

                                return false
                            }
                        }
                    }

                    // Slide 22
                    if(this.slideIndex  ==  22) {

                        if((this.client.facade_image !==  "")&&(this.client.facade_image_original_name   !==  "")) {

                            return true;
                        }

                        else {

                            return false
                        }
                    }

                    // Slide 23
                    if(this.slideIndex  ==  23) {

                        if( ((!isNaN(this.client.Latitude)) &&(this.client.Latitude     !== "") &&(isFinite(Number(this.client.Latitude))))   &&
                            ((!isNaN(this.client.Longitude))&&(this.client.Longitude    !== "") &&(isFinite(Number(this.client.Longitude))))   ) {

                            return true;
                        }

                        else {

                            return false
                        }
                    }

                    return true;
                }
            }
        },

        //

        setTotalQuestions() {

            this.total_questions    =   document.getElementsByClassName("mySlides").length
        },

        setStatus() {

            if(this.client.OpenCustomer ==  "Ferme") {
                this.client.status     =   "ferme"
            }

            else {
                this.client.status     =   "pending"
            }

            this.setTotalQuestions()
        }
    }
};

</script>