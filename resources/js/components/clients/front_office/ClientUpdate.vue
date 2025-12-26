<template>
  <div class="page-shell d-flex flex-column">
    <!-- Header -->
    <div class="flex-shrink-0 bg-white shadow-sm z-index-1">
      <GPSErrorComponent v-show="show_gps_error"></GPSErrorComponent>
      <div class="page-header py-3 border-bottom">
        <div class="container">
          <h6 class="text-center mb-0 text_primary fw-bold">
            Ajouter un Nouveau Client ({{ slideIndex }} / {{ total_questions }})
          </h6>
          <div class="progress mt-2" style="height: 4px;">
            <div class="progress-bar" role="progressbar" :style="{ width: (slideIndex / total_questions) * 100 + '%' }"></div>
          </div>
        </div>
      </div>
    </div>

    <!-- Scrollable main -->
    <div class="page-content flex-grow-1 overflow-auto">
      <!-- add padding-bottom to avoid last item being hidden by footer -->
      <div class="container py-4" style="padding-bottom: 88px;">
        <form @submit.prevent>
          
            <div v-show="slideIndex === 1" class="mt-3">
              <div>
                <div class="mb-1">
                  <label for="NewCustomer" class="form-label fw-bold">Nouveau Client</label>
                  <select class="form-select" id="NewCustomer" v-model="client.NewCustomer"     :disabled="(client.status_original    ==  'confirmed')||(client.status_original    ==  'validated')">
                    <option value="Nouveau Client">Nouveau Client</option>
                    <option value="Client Existant">Client Existant</option>
                  </select>
                </div>
                <div class="mb-1">
                  <label for="OpenCustomer" class="form-label fw-bold">Client Ouvert</label>
                  <select class="form-select" id="OpenCustomer" v-model="client.OpenCustomer"   :disabled="(client.status_original    ==  'confirmed')||(client.status_original    ==  'validated')">
                    <option value="Ouvert">Ouvert</option>
                    <option value="Ferme">Ferme</option>
                    <option value="refus">Refus</option>
                  </select>
                </div>
              </div>
              <div class="mt-5"><ul class="pl-3"><li>Sélectionnez si le client est ouvert ou non.</li></ul></div>
            </div>

            <div v-show="slideIndex === 2" class="mt-3">
              <div>
                <label for="CustomerIdentifier" class="form-label fw-bold">ID Client</label>
                <input type="text" class="form-control" id="CustomerIdentifier" v-model="client.CustomerIdentifier" :disabled="(client.status_original    ==  'confirmed')||(client.status_original    ==  'validated')">
              </div>
              <div class="mt-5"><ul class="pl-3"><li>Saisissez l'identifiant du client.</li></ul></div>
            </div>

            <div v-show="slideIndex === 3" class="mt-3">
              <div v-if="client.OpenCustomer === 'Ouvert'">
                  <div>
                    <label class="form-label fw-bold">Code-Barre</label>
                    <div v-show="!client.CustomerCode" class="mt-1 p-0">
                      <div v-show="show_reader" id="reader" class="scanner_reader w-100"></div>
                    </div>
                    <div v-show="client.CustomerCode" class="mt-1 p-0 text-center">
                       <span class="fw-bold fs-5">Code-Barre : {{ client.CustomerCode }}</span>
                    </div>
                  </div>
                  <div class="mt-3 w-100">
                    <button type="button" class="btn btn-primary w-100" @click="setBarCodeReader()" :disabled="(client.status_original    ==  'confirmed')||(client.status_original    ==  'validated')">Capturer Code-Barre</button>
                  </div>
                  <div class="mt-5">
                    <ul class="pl-3">
                       <li>Cliquez sur "Capturer" pour scanner.</li>
                       <li class="text-danger">🚨 Pas de caractères spéciaux.</li>
                    </ul>
                  </div>
              </div>
              <div v-else class="text-center mt-5">
                  <h5>Non applicable (Client Fermé/Refus)</h5>
                  <p class="text-muted">Cliquez sur Suivant</p>
              </div>
            </div>

            <div v-show="slideIndex === 4" class="mt-3">
              <div v-if="client.OpenCustomer === 'Ouvert'">
                  <label class="form-label">Code-Barre Image</label>
                  <button type="button" class="btn btn-secondary w-100 mb-1" @click="$clickFile('CustomerBarCode_image')"   :disabled="(client.status_original    ==  'confirmed')||(client.status_original    ==  'validated')"><i class="mdi mdi-camera"></i></button>
                  
                  <input type='file' id="CustomerBarCode_image" style="display:none" accept="image/*" capture 
                         @change="handleImageUpload($event, 'CustomerBarCode_image')"   :disabled="(client.status_original    ==  'confirmed')||(client.status_original    ==  'validated')">
                  
                  <img :src="client.CustomerBarCode_image_currentObjectURL" 
                       v-show="client.CustomerBarCode_image_currentObjectURL"
                       style="width: 100%; height: auto; display: block; margin: auto; border-radius: 8px;">
              </div>
              <div v-else class="text-center mt-5"><h5>Non applicable</h5></div>
            </div>

            <div v-show="slideIndex === 5" class="mt-3">
               <div v-if="client.OpenCustomer === 'Ouvert'">
                  <label for="CustomerNameE" class="form-label fw-bold">Nom et Prénom (Acheteur)</label>
                  <input type="text" class="form-control" id="CustomerNameE" v-model="client.CustomerNameE" :disabled="(client.status_original    ==  'confirmed')||(client.status_original    ==  'validated')">
                  <div class="mt-5"><ul class="pl-3"><li>MAJUSCULE (ex: BAKHNACH IMAD).</li></ul></div>
               </div>
               <div v-else class="text-center mt-5"><h5>Non applicable</h5></div>
            </div>

            <div v-show="slideIndex === 6" class="mt-3">
              <div>
                <label for="CustomerNameA" class="form-label fw-bold">Raison Sociale</label>
                <input type="text" class="form-control" id="CustomerNameA" v-model="client.CustomerNameA"   :disabled="(client.status_original    ==  'confirmed')||(client.status_original    ==  'validated')">
              </div>
              <div class="mt-5"><ul class="pl-3"><li>Nom du magasin (ex: SUPERETTE ESSALEM).</li></ul></div>
            </div>

            <div v-show="slideIndex === 7" class="mt-3">
               <div v-if="client.OpenCustomer === 'Ouvert'">
                  <label for="Tel" class="form-label fw-bold">Téléphone</label>
                  <input type="string" class="form-control" id="Tel" v-model="client.Tel"   :disabled="(client.status_original    ==  'confirmed')||(client.status_original    ==  'validated')">
                  <div class="mt-5"><ul class="pl-3"><li>10 chiffres (ex: 0654123487).</li></ul></div>
               </div>
               <div v-else class="text-center mt-5"><h5>Non applicable</h5></div>
            </div>

            <div v-show="slideIndex === 8" class="mt-3">
              <div>
                <label for="Address" class="form-label fw-bold">Adresse</label>
                <input type="text" class="form-control" id="Address" v-model="client.Address"   :disabled="(client.status_original    ==  'confirmed')||(client.status_original    ==  'validated')">
              </div>
            </div>

            <div v-show="slideIndex === 9" class="mt-3">
              <div>
                <label for="Neighborhood" class="form-label fw-bold">Quartier</label>
                <input type="text" class="form-control" id="Neighborhood" v-model="client.Neighborhood" :disabled="(client.status_original    ==  'confirmed')||(client.status_original    ==  'validated')">
              </div>
            </div>

            <div v-show="slideIndex === 10" class="mt-3">
              <div>
                <label for="Landmark" class="form-label fw-bold">Point de Repère</label>
                <textarea class="form-control" id="Landmark" rows="3" v-model="client.Landmark" :disabled="(client.status_original    ==  'confirmed')||(client.status_original    ==  'validated')"></textarea>
              </div>
            </div>

            <div v-show="slideIndex === 11" class="mt-3">
              <div>
                <label for="DistrictNo" class="form-label fw-bold">Willaya</label>
                <select class="form-select" id="DistrictNo" v-model="client.DistrictNo" @change="getCites()"    :disabled="(client.status_original    ==  'confirmed')||(client.status_original    ==  'validated')">
                  <option v-for="willaya in willayas" :key="willaya.DistrictNo" :value="willaya.DistrictNo">
                    {{willaya.DistrictNameE}} ({{willaya.DistrictNo}})
                  </option>
                </select>
              </div>
            </div>

            <div v-show="slideIndex === 12" class="mt-3">
              <div>
                <label for="CityNo" class="form-label fw-bold">Commune</label>
                <select class="form-select" id="CityNo" v-model="client.CityNo" :disabled="(client.status_original    ==  'confirmed')||(client.status_original    ==  'validated')">
                  <option v-for="cite in cites" :key="cite.CITYNO" :value="cite.CITYNO">
                    {{cite.CityNameE}} ({{cite.CITYNO}})
                  </option>
                </select>
              </div>
            </div>

            <div v-show="slideIndex === 13" class="mt-3">
              <div>
                <label for="CustomerType" class="form-label fw-bold">Type de Magasin</label>
                <select class="form-select" id="CustomerType" v-model="client.CustomerType" :disabled="(client.status_original    ==  'confirmed')||(client.status_original    ==  'validated')">
                   <option v-for="type in liste_type_client_options" :key="type" :value="type">{{ type }}</option>
                </select>
              </div>
            </div>

            <div v-show="slideIndex === 14" class="mt-3">
               <div v-if="client.OpenCustomer === 'Ouvert'">
                  <label class="form-label fw-bold">Source d'Achat</label>
                  <select class="form-select" v-model="client.BrandSourcePurchase"  :disabled="(client.status_original    ==  'confirmed')||(client.status_original    ==  'validated')">
                    <option value="DD">DD (Distributeur)</option>
                    <option value="DI">DI (Grossiste)</option>
                    <option value="Pas d'achat">Pas d'achat</option>
                  </select>
               </div>
               <div v-else class="text-center mt-5"><h5>Non applicable</h5></div>
            </div>

            <div v-show="slideIndex === 15" class="mt-3">
               <div v-if="client.OpenCustomer === 'Ouvert'">
                  <label class="form-label fw-bold">Nombre de caisses auto</label>
                  <select class="form-select" v-model="client.NbrAutomaticCheckouts"    :disabled="(client.status_original    ==  'confirmed')||(client.status_original    ==  'validated')">
                    <option value="Plus de 1">Plus de 1</option>
                    <option value="1">1</option>
                    <option value="Pas de caisse automatique">Pas de caisse automatique</option>
                  </select>
               </div>
               <div v-else class="text-center mt-5"><h5>Non applicable</h5></div>
            </div>

             <div v-show="slideIndex === 16" class="mt-3">
               <div v-if="client.OpenCustomer === 'Ouvert'">
                  <label class="form-label fw-bold">Superficie</label>
                  <select class="form-select" v-model="client.SuperficieMagasin"    :disabled="(client.status_original    ==  'confirmed')||(client.status_original    ==  'validated')">
                    <option value="Moins de 30 M">Moins de 30 M</option>
                    <option value="DE 30 a 60">DE 30 a 60</option>
                    <option value="DE 30 a 100">DE 30 a 100</option>
                    <option value="DE 100 a 200">DE 100 a 200</option>
                    <option value="Plus de 200">Plus de 200</option>
                  </select>
               </div>
               <div v-else class="text-center mt-5"><h5>Non applicable</h5></div>
            </div>

            <div v-show="slideIndex === 17" class="mt-3">
              <div>
                <label for="JPlan" class="form-label fw-bold">Nom de Vendeur</label>
                <input type="text" class="form-control" id="JPlan" v-model="client.JPlan"   :disabled="(client.status_original    ==  'confirmed')||(client.status_original    ==  'validated')">
              </div>
            </div>

            <div v-show="slideIndex === 18" class="mt-3">
              <div>
                <label for="Frequency" class="form-label fw-bold">Fréquence</label>
                <select class="form-select" id="Frequency" v-model="client.Frequency"   :disabled="(client.status_original    ==  'confirmed')||(client.status_original    ==  'validated')">
                  <option value="1 par Semaine(7J)">1 par Semaine(7J)</option>
                  <option value="1 par 15J">1 par 15J</option>
                </select>
              </div>
            </div>

            <div v-show="slideIndex === 19" class="mt-3">
              <div>
                <label for="Journee" class="form-label fw-bold">Journée</label>
                <select class="form-select" id="Journee" v-model="client.Journee"   :disabled="(client.status_original    ==  'confirmed')||(client.status_original    ==  'validated')">
                  <option v-for="day in ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi']" :key="day+'1'" :value="day+' 1'">{{day}} 1</option>
                  <option v-for="day in ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi']" :key="day+'2'" :value="day+' 2'">{{day}} 2</option>
                </select>
              </div>
            </div>

            <div v-show="slideIndex === 20" class="mt-3">
               <div v-if="client.OpenCustomer === 'Ouvert'">
                  <div class="mb-1">
                    <label class="form-label fw-bold">Disponibilité Produits</label>
                    <select class="form-select" v-model="client.BrandAvailability" @change="brandAvailabilityChanged()" :disabled="(client.status_original    ==  'confirmed')||(client.status_original    ==  'validated')">
                      <option value="Non">Non</option>
                      <option value="Oui">Oui</option>
                    </select>
                  </div>
                  
                  <div class="mt-1" v-if="client.BrandAvailability === 'Oui'">
                    <div class="form-check" v-for="brand in ['Loya Lait', 'Loya Fromage', 'Berbere', 'Cowbell', 'Twisco', 'PromaCafé', 'Amila']" :key="brand">
                        <input class="form-check-input" type="checkbox" :value="brand" :id="brand" v-model="client.AvailableBrands" :disabled="(client.status_original    ==  'confirmed')||(client.status_original    ==  'validated')">
                        <label class="form-check-label" :for="brand">{{ brand }}</label>
                    </div>

                    <label class="form-label fw-bold mt-2">Image In-Store</label>
                    <button type="button" class="btn btn-secondary w-100 mb-1" @click="$clickFile('in_store_image')"    :disabled="(client.status_original    ==  'confirmed')||(client.status_original    ==  'validated')"><i class="mdi mdi-camera"></i></button>
                    
                    <input type='file' id="in_store_image" style="display:none" accept="image/*" capture 
                           @change="handleImageUpload($event, 'in_store_image')"    :disabled="(client.status_original    ==  'confirmed')||(client.status_original    ==  'validated')">
                           
                    <img :src="client.in_store_image_currentObjectURL" 
                         v-show="client.in_store_image_currentObjectURL"
                         style="width: 100%; height: auto; display: block; margin: auto; border-radius: 8px;">
                  </div>
               </div>
               <div v-else class="text-center mt-5"><h5>Non applicable</h5></div>
            </div>

            <div v-show="slideIndex === 21" class="mt-3">
              <div>
                <label for="comment" class="form-label fw-bold">Commentaire</label>
                <textarea class="form-control" id="comment" rows="3" v-model="client.comment"   :disabled="(client.status_original    ==  'confirmed')||(client.status_original    ==  'validated')"></textarea>
              </div>
            </div>

            <div v-show="slideIndex === 22" class="mt-3">
              <div>
                <label class="form-label fw-bold">Image Facade</label>
                <button type="button" class="btn btn-secondary w-100 mb-1" @click="$clickFile('facade_image')"  :disabled="(client.status_original    ==  'confirmed')||(client.status_original    ==  'validated')"><i class="mdi mdi-camera"></i></button>
                
                <input type='file' id="facade_image" style="display:none" accept="image/*" capture 
                       @change="handleImageUpload($event, 'facade_image')"  :disabled="(client.status_original    ==  'confirmed')||(client.status_original    ==  'validated')">
                       
                <img :src="client.facade_image_currentObjectURL" 
                     v-show="client.facade_image_currentObjectURL"
                     style="width: 100%; height: auto; display: block; margin: auto; border-radius: 8px;">
              </div>
            </div>

            <div v-show="slideIndex === 23" class="mt-3">
              <div>
                <label class="form-label fw-bold">
                    Position Actuelle 
                    <button class="btn btn-sm" @click.prevent="refreshGPS()" :disabled="check_gps_clicked"><i class="mdi mdi-reload"></i></button>
                </label>
                <p class="text-secondary text-small mb-1">Lat: {{ client.Latitude }} | Long: {{ client.Longitude }}</p>
                
                <div id="show_map" style="width: 100%; height: 200px; background-color: #eee;"></div>
                
                <hr />
                <h5>Clients à Proximité</h5>
                <table class="table table-bordered table-sm mt-1">
                  <thead>
                    <tr>
                      <th>Acheteur</th>
                      <th>Raison Sociale</th>
                      <th>Type</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(c, index) in close_clients" :key="index">
                      <td>{{c.CustomerNameE}}</td>
                      <td>{{c.CustomerNameA}}</td>
                      <td>{{c.CustomerType}}</td>
                    </tr>
                    <tr v-if="close_clients.length === 0">
                        <td colspan="3" class="text-center text-muted">Aucun client proche</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

        </form>
      </div>
    </div>

    <div class="page-footer flex-shrink-0 bg-white border-top pb-3 pt-3 w-100">
      <div class="container">
        <div class="row g-2">
          <div class="col-6">
            <button 
              v-if="((slideIndex > 1) && (getIsOnline) && (client.status_original   !=  'confirmed') && (client.status_original     !=  'validated'))"
            
              type="button" 
              class="btn btn-secondary w-100 py-2 fw-bold" 
              @click="changeSlide(-1)">
              Précédent
            </button>
          </div>

          <div class="col-6">
            <button 
              v-if="((slideIndex < total_questions) && (getIsOnline) && (client.status_original   !=  'confirmed') && (client.status_original     !=  'validated'))"
              type="button" 
              class="btn btn-primary w-100 py-2 fw-bold shadow-sm" 
              @click="changeSlide(1)">
              Suivant
            </button>

            <button 
              v-if="((slideIndex === total_questions) && (getIsOnline) && (client.status_original   !=  'confirmed') && (client.status_original     !=  'validated'))" 
              type="button" 
              class="btn btn-success w-100 py-2 fw-bold shadow-sm" 
              @click="sendData()">
              Confirmer
            </button>
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
import moment                   from    "moment"; // Ensure moment is installed

export default {

    data() {
        return {
            client: {
                NewCustomer: 'Nouveau Client', // Set default
                OpenCustomer: 'Ouvert',        // Set default
                
                // Details
                CustomerIdentifier: '',
                CustomerCode: '',
                CustomerNameE: '',
                CustomerNameA: '',
                Tel: '',
                Address: '',
                Neighborhood: '',
                Landmark: '',
                DistrictNo: '',
                DistrictNameE: '',
                CityNo: '',
                CityNameE: '',
                CustomerType: '',
                
                // Merchandising / Business logic
                BrandSourcePurchase: '',
                NbrAutomaticCheckouts: '',
                SuperficieMagasin: '',
                JPlan: '',
                Frequency: '',
                Journee: '',
                BrandAvailability: 'Non',
                AvailableBrands: [],
                comment: '',

                // Images (Data & Preview)
                // CustomerBarCode_image: '',
                CustomerBarCode_image_currentObjectURL: null,
                CustomerBarCode_image_original_name: '',

                // in_store_image: '',
                in_store_image_currentObjectURL: null,
                in_store_image_original_name: '',

                // facade_image: '',
                facade_image_currentObjectURL: null,
                facade_image_original_name: '',

                // Location & Status
                Latitude: '',
                Longitude: '',
                id: '',
                status: 'pending',
                nonvalidated_details: ''
            },

            // Configuration Options
            willayas: [],
            cites: [],
            liste_type_client_options: [
                'AG Self', 'AG Tradi', 'Superette', 'Gros Mix', 'Gros Sec', 
                'Gros Frais', 'Demis Gros Mix', 'Demis Gros Sec', 'Demis Gros Frais', 
                'Produit laitier et lben', 'Produit Patisseries', 'Fast Food', 
                'Restaurant', 'Cafeteria', 'HyperMarché'
            ],

            // App State
            all_clients: [],
            close_clients: [],
            min_distance: 0.05,
            scanner: null,
            point_is_inside_user_polygons: true,
            
            slideIndex: 1,
            total_questions: 23, // Matches template
            
            start_adding_date: "",
            check_gps_clicked: false,
            show_gps_error: false,
            watchGPS: null,

            show_reader: false
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
        this.cleanupScanner();
        if (this.watchGPS) navigator.geolocation.clearWatch(this.watchGPS);
    },

    // 2. Ensure cleanup if the user leaves the page
    beforeDestroy() { // Use 'beforeUnmount' if you are on Vue 3
        const fields = ['CustomerBarCode_image', 'facade_image', 'in_store_image'];
        fields.forEach(field => this.revokeImage(field));
    }, 

    // 1. Non-reactive storage for heavy binary blobs
    created() {
        this._rawFiles = {
            CustomerBarCode_image: null,
            facade_image: null,
            in_store_image: null
        };
    },

    async mounted() {
        this.$showLoadingPage();
        try {
            await Promise.all([this.getComboData(), this.getClientData()]);
            this.total_questions = document.getElementsByClassName("mySlides").length || 23;
        } finally {
            this.$hideLoadingPage();
        }
    },

    methods : {

        ...mapActions("client" ,  [
            "setUpdateClientAction"   ,
        ]),

        //

        async sendData_old() {

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

                if(this.client.OpenCustomer  === 'Ferme') {
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

                if(this.client.OpenCustomer  === 'refus') {
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

                if(this.client.OpenCustomer  === 'Introuvable') {

                    formData.append("NewCustomer"                           ,   this.client.NewCustomer)
                    formData.append("OpenCustomer"                          ,   this.client.OpenCustomer)

                    formData.append("CustomerIdentifier"                    ,   this.client.CustomerIdentifier)

                    formData.append("CustomerBarCodeExiste"                 ,   '')

                    formData.append("CustomerCode"                          ,   '')
                    formData.append("CustomerNameE"                         ,   '')
                    formData.append("CustomerNameA"                         ,   this.client.CustomerNameA)
                    formData.append("Latitude"                              ,   this.client.Latitude)
                    formData.append("Longitude"                             ,   this.client.Longitude)

                    formData.append("RvrsGeoAddress"                        ,   this.client.RvrsGeoAddress)
                    formData.append("Address"                               ,   this.client.Address)
                    formData.append("Neighborhood"                          ,   this.client.Neighborhood)
                    formData.append("Landmark"                              ,   this.client.Landmark)
                    formData.append("DistrictNo"                            ,   this.client.DistrictNo)
                    formData.append("DistrictNameE"                         ,   this.client.DistrictNameE)
                    formData.append("CityNo"                                ,   this.client.CityNo)
                    formData.append("CityNameE"                             ,   this.client.CityNameE)

                    formData.append("Tel"                                   ,   this.client.Tel)
                    formData.append("tel_status"                            ,   'nonvalidated')
                    formData.append("tel_comment"                           ,   '')

                    formData.append("CustomerType"                          ,   this.client.CustomerType)

                    formData.append("NbrVitrines"                           ,   '')
                    formData.append("NbrAutomaticCheckouts"                 ,   '')

                    formData.append("SuperficieMagasin"                     ,   this.client.SuperficieMagasin)

                    formData.append("CustomerBarCodeExiste_image_updated"   ,   true)
                    formData.append("CustomerBarCode_image_updated"         ,   true)
                    formData.append("facade_image_updated"                  ,   this.client.facade_image_updated)
                    formData.append("in_store_image_updated"                ,   true)

                    formData.append("CustomerBarCodeExiste_image"           ,   '')
                    formData.append("CustomerBarCode_image"                 ,   '')
                    formData.append("facade_image"                          ,   this.client.facade_image)
                    formData.append("in_store_image"                        ,   '')

                    formData.append("CustomerBarCodeExiste_image_original_name" ,   '')
                    formData.append("CustomerBarCode_image_original_name"       ,   '')
                    formData.append("facade_image_original_name"                ,   this.client.facade_image_original_name)
                    formData.append("in_store_image_original_name"              ,   '')

                    formData.append("CustomerBarCodeExiste_image_updated"   ,   true)
                    formData.append("CustomerBarCode_image_updated"         ,   true)
                    formData.append("facade_image_updated"                  ,   this.client.facade_image_updated)
                    formData.append("in_store_image_updated"                ,   true)

                    formData.append("status"                                ,   'pending')
                    formData.append("nonvalidated_details"                  ,   '')

                    formData.append("comment"                               ,   this.client.comment)

                    formData.append("JPlan"                                 ,   this.client.JPlan)
                    formData.append("Journee"                               ,   this.client.Journee)
                    formData.append("BrandAvailability"                     ,   this.client.BrandAvailability)
                    formData.append("BrandSourcePurchase"                   ,   this.client.BrandSourcePurchase)
                }

                //
                if(this.client.status_original   ==  'visible') {

                    formData.append("start_adding_date"                     ,   this.start_adding_date)
                    formData.append("finish_adding_date"                    ,   moment(new Date()).format())
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

        async sendData() {
            // 1. Validation
            const validation = this.validationQuestion();
            if (!validation) {
                this.$showErrors("Error !", ["Veuillez répondre en respectant les conditions des questions avant de valider !"]);
                return;
            }

            this.$showLoadingPage();

            // 2. Refresh Names
            if (this.willayas.length > 0) this.client.DistrictNameE = this.getDistrictNameE(this.client.DistrictNo);
            if (this.cites.length > 0) this.client.CityNameE = this.getCityNameE(this.client.CityNo);

            // 3. Prepare Payload based on Status
            let payload = {};
            const status = this.client.OpenCustomer; // Ouvert, Ferme, Refus, Introuvable

            // --- A. Base Fields (Common to almost all) ---
            const baseFields = {
                NewCustomer: this.client.NewCustomer,
                OpenCustomer: this.client.OpenCustomer,
                CustomerIdentifier: this.client.CustomerIdentifier,
                CustomerNameA: this.client.CustomerNameA,
                Latitude: this.client.Latitude,
                Longitude: this.client.Longitude,
                Address: this.client.Address,
                Neighborhood: this.client.Neighborhood,
                Landmark: this.client.Landmark,
                DistrictNo: this.client.DistrictNo,
                DistrictNameE: this.client.DistrictNameE,
                CityNo: this.client.CityNo,
                CityNameE: this.client.CityNameE,
                CustomerType: this.client.CustomerType,
                // Default Images Logic
                facade_image: this.client.facade_image,
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
                    AvailableBrands: JSON.stringify(this.client.AvailableBrands), // Specific serialization
                    
                    // Images
                    CustomerBarCode_image_updated: this.client.CustomerBarCode_image_updated,
                    in_store_image_updated: this.client.in_store_image_updated,
                    CustomerBarCode_image: this.client.CustomerBarCode_image,
                    in_store_image: this.client.in_store_image,
                    CustomerBarCode_image_original_name: this.client.CustomerBarCode_image_original_name,
                    in_store_image_original_name: this.client.in_store_image_original_name,
                    
                    status: this.client.status,
                    nonvalidated_details: this.client.nonvalidated_details
                };
            } 
            else if (status === 'Ferme' || status === 'refus') {
                payload = {
                    ...baseFields,
                    // Empty/Default overrides
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

                    // Images (Clear others, keep facade)
                    CustomerBarCode_image: '',
                    in_store_image: '',
                    CustomerBarCode_image_original_name: '',
                    in_store_image_original_name: '',
                    
                    // Specific Updates for Refus/Ferme
                    CustomerBarCodeExiste_image_updated: true, // Note: You had this in 'refus'
                    CustomerBarCode_image_updated: true,
                    in_store_image_updated: true,
                    
                    status: 'pending',
                    nonvalidated_details: ''
                };
            }
            else if (status === 'Introuvable') {
                 payload = {
                    ...baseFields,
                    CustomerBarCodeExiste: '',
                    CustomerCode: '',
                    CustomerNameE: '',
                    RvrsGeoAddress: this.client.RvrsGeoAddress, // Specific to Introuvable
                    Tel: this.client.Tel,
                    tel_status: 'nonvalidated',
                    tel_comment: '',
                    NbrVitrines: '',
                    NbrAutomaticCheckouts: '',
                    SuperficieMagasin: this.client.SuperficieMagasin,
                    BrandAvailability: this.client.BrandAvailability,
                    BrandSourcePurchase: this.client.BrandSourcePurchase,
                    
                    // Images logic (Clear all except facade)
                    CustomerBarCodeExiste_image_updated: true,
                    CustomerBarCode_image_updated: true,
                    in_store_image_updated: true,
                    CustomerBarCodeExiste_image: '',
                    CustomerBarCode_image: '',
                    in_store_image: '',
                    
                    JPlan: this.client.JPlan,
                    Journee: this.client.Journee,
                    
                    status: 'pending',
                    nonvalidated_details: ''
                 };
            }

            // 4. Timer Logic
            if (this.client.status_original === 'visible') {
                payload.start_adding_date = this.start_adding_date;
                payload.finish_adding_date = moment(new Date()).format();
            }

            // 5. Build FormData
            const formData = new FormData();
            for (const key in payload) {
                let value = payload[key];
                if (value === null || value === undefined) value = '';
                formData.append(key, value);
            }

            // 6. Send Request
            try {
                const res = await this.$callApi("post", `/route_import/${this.$route.params.id_route_import}/clients/${this.client.id}/update`, formData);

                if (res.status === 200) {
                    this.$hideLoadingPage();
                    this.$feedbackSuccess(res.data["header"], res.data["message"]);
                    this.$goBack();
                } else {
                    this.$hideLoadingPage();
                    this.$showErrors("Error !", res.data.errors);
                }
            } catch (error) {
                this.$hideLoadingPage();
                this.$showErrors("System Error", ["An unexpected error occurred."]);
                console.error(error);
            }
        },

        //

        async getData() {
            this.$showLoadingPage();

            try {
                // 1. Get Route Details
                const res = await this.$callApi("post", `/route/obs/route_import/${this.$route.params.id_route_import}/details`, null);
                if(res.data && res.data.route_import) {
                    this.all_clients = res.data.route_import.clients;
                }

                // 2. Get Client & Combo Data
                await Promise.all([
                    this.getClientData(),
                    this.getComboData()
                ]);

            } catch (error) {
                console.error("Error fetching data", error);
                this.$showErrors("Error", ["Failed to load data."]);
            } finally {
                this.$hideLoadingPage();
            }
        },

        async getClientData_old() {
            const res = await this.$callApi("post", `/route_import/${this.$route.params.id_route_import}/clients/${this.$route.params.id_client}/show`, null);
            const apiData = res.data;

            // 1. Merge API data into client object (replaces 50 lines of manual assignment)
            this.client = {
                ...this.client,
                ...apiData,
                // Handle specific field transformations immediately
                AvailableBrands: apiData.AvailableBrands_array_formatted || []
            };

            // 2. Handle Image Previews
            // Note: In your HTML, use <img :src="client.CustomerBarCode_image_currentObjectURL">
            this.setImagePreview('CustomerBarCode', apiData.id, apiData.CustomerBarCode_image, apiData.CustomerBarCode_image_original_name);
            this.setImagePreview('facade', apiData.id, apiData.facade_image, apiData.facade_image_original_name);
            this.setImagePreview('in_store', apiData.id, apiData.in_store_image, apiData.in_store_image_original_name);

            // 3. Set timer if visible
            if (this.client.status === "visible") {
                this.start_adding_date = moment(new Date()).format();
            }

            this.checkClients(); // Existing logic check
        },

        setImagePreview(prefix, clientId, imageName, originalName) {
            if (originalName && imageName) {
                const url = `/uploads/clients/${clientId}/${imageName}`;
                this.client[`${prefix}_image_currentObjectURL`] = url;
                
                // If you are using a file creation utility
                this.$createFile(originalName, `${prefix}_image_update`);
            }
        },

        async getComboData_old() {
            const res = await this.$callApi("post", `/route_import/${this.$route.params.id_route_import}/districts`, null);
            this.willayas = res.data;
        },

        async getCites_old() {
            if(!this.client.DistrictNo) return;
            
            this.$showLoadingPage();
            const res = await this.$callApi("post", `/rtm_willayas/${this.client.DistrictNo}/rtm_cites`, null);
            this.cites = res.data;
            this.client.CityNo = ""; // Reset city selection
            this.$hideLoadingPage();
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

            const input     =   document.getElementById("CustomerBarCode_image_update");
            const display   =   document.getElementById("CustomerBarCode_image_display_update");
            const file      =   input.files[0];

            // Clear previous resources
            if (this.client.CustomerBarCode_image_currentObjectURL) {

                URL.revokeObjectURL(this.client.CustomerBarCode_image_currentObjectURL);
                this.client.CustomerBarCode_image_currentObjectURL  =   null;
            }

            if (!file) {

                this.client.CustomerBarCode_image_original_name     =   "";
                this.client.CustomerBarCode_image                   =   "";

                this.client.CustomerBarCode_image_updated           =   true;

                display.src                                         =   "";

                return;
            }

            try {
                // 1. Use lighter compression
                const compressedFile                                =   await this.$compressImage(file);
                
                // 2. Use object URL instead of base64
                const objectUrl                                     =   URL.createObjectURL(compressedFile);
                this.client.CustomerBarCode_image_currentObjectURL  =   objectUrl;
                display.src                                         =   objectUrl;

                // 3. Store compressed file instead of base64 string
                this.client.CustomerBarCode_image_updated           =   true;
                this.client.CustomerBarCode_image_original_name     =   file.name;
                this.client.CustomerBarCode_image                   =   compressedFile;
            } 

            catch (error) {

                console.error("Image processing failed:", error);

                this.client.CustomerBarCode_image_original_name     =   "";
                this.client.CustomerBarCode_image                   =   "";
                this.client.CustomerBarCode_image_updated           =   true;

                display.src = "";
            }
        },

        async facadeImage() {

            const input     =   document.getElementById("facade_image_update");
            const display   =   document.getElementById("facade_image_display_update");
            const file      =   input.files[0];

            // Clear previous resources
            if (this.client.facade_image_currentObjectURL) {

                URL.revokeObjectURL(this.client.facade_image_currentObjectURL);
                this.client.facade_image_currentObjectURL   =   null;
            }

            if (!file) {

                this.client.facade_image_original_name      =   "";
                this.client.facade_image                    =   "";

                this.client.facade_image_updated            =   true;

                display.src                                 =   "";

                return;
            }

            try {
                // 1. Use lighter compression
                const compressedFile                                =   await this.$compressImage(file);
                
                // 2. Use object URL instead of base64
                const objectUrl                                     =   URL.createObjectURL(compressedFile);
                this.client.facade_image_currentObjectURL           =   objectUrl;
                display.src                                         =   objectUrl;

                // 3. Store compressed file instead of base64 string
                this.client.facade_image_updated                    =   true;
                this.client.facade_image_original_name              =   file.name;
                this.client.facade_image                            =   compressedFile;
            } 

            catch (error) {

                console.error("Image processing failed:", error);

                this.client.facade_image_original_name      =   "";
                this.client.facade_image                    =   "";
                this.client.facade_image_updated            =   true;

                display.src = "";
            }
        },

        async inStoreImage() {

            const input     =   document.getElementById("in_store_image_update");
            const display   =   document.getElementById("in_store_image_display_update");
            const file      =   input.files[0];

            // Clear previous resources
            if (this.client.in_store_image_currentObjectURL) {

                URL.revokeObjectURL(this.client.in_store_image_currentObjectURL);
                this.client.in_store_image_currentObjectURL =   null;
            }

            if (!file) {

                this.client.in_store_image_original_name    =   "";
                this.client.in_store_image                  =   "";

                this.client.in_store_image_updated          =   true;

                display.src                                 =   "";

                return;
            }

            try {
                // 1. Use lighter compression
                const compressedFile                                =   await this.$compressImage(file);
                
                // 2. Use object URL instead of base64
                const objectUrl                                     =   URL.createObjectURL(compressedFile);
                this.client.in_store_image_currentObjectURL         =   objectUrl;
                display.src                                         =   objectUrl;

                // 3. Store compressed file instead of base64 string
                this.client.in_store_image_updated                  =   true;
                this.client.in_store_image_original_name            =   file.name;
                this.client.in_store_image                          =   compressedFile;
            } 

            catch (error) {

                console.error("Image processing failed:", error);

                this.client.in_store_image_original_name    =   "";
                this.client.in_store_image                  =   "";
                this.client.in_store_image_updated          =   true;

                display.src = "";
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

                    // --- NEW: Get Address from LocationIQ ---
                    // We await this so the address is ready before you save/check clients
                    // const address = await this.$getAddressFromLocationIQ(this.client.Latitude, this.client.Longitude);
                    
                    // Assuming 'this.client.Address' is where you want to store it
                    // if(address) {
                    //     this.client.RvrsGeoAddress  =   address;
                    //     console.log("Address found:", this.client.RvrsGeoAddress);
                    // }
                    // ----------------------------------------
                    // await this.$nextTick()

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
            this.total_questions = document.getElementsByClassName("mySlides").length;
        },

        setStatus() {

            if(this.client.OpenCustomer ==  "Ferme") {
                this.client.status     =   "ferme"
            }

            else {
                this.client.status     =   "pending"
            }

            this.setTotalQuestions()
        },

        //  //  //  //  //  //  //
        //  //  //  //  //  //  //
        //  //  //  //  //  //  //

        // --- Navigation ---
        changeSlide(step) {
            const nextIndex = this.slideIndex + step;
            
            // Logic for "Next"
            if (step > 0) {
                if (!this.validationQuestion()) {
                    this.$showErrors("Erreur !", ["Veuillez remplir les champs obligatoires correctement."]);
                    return;
                }
                
                // If moving TO the map slide (last slide)
                if (nextIndex === this.total_questions) {
                    this.point_is_inside_user_polygons = false;
                    setTimeout(() => {
                        this.showPositionOnMap('show_map'); 
                    }, 200); // Small delay to ensure DIV is rendered via v-show
                }
            }

            // Logic for "Previous"
            if (nextIndex < 1) return;
            if (nextIndex > this.total_questions) return;

            this.slideIndex = nextIndex;
        },

        async handleImageUpload(event, fieldKey) {
            const file = event.target.files[0];
            if (!file) return;

            // Revoke old URL to free memory
            this.revokeImage(fieldKey);

            try {
                const compressedFile = await this.$compressImage(file);
                const objectUrl = URL.createObjectURL(compressedFile);

                // Update non-reactive storage
                this._rawFiles[fieldKey] = compressedFile;

                // Update Vue state with metadata only
                this.client[`${fieldKey}_currentObjectURL`] = objectUrl;
                this.client[`${fieldKey}_original_name`] = file.name;
                this.client[`${fieldKey}_updated`] = true;

                // Clear input so same file can be re-selected if needed
                event.target.value = ''; 
            } catch (e) {
                console.error("Upload Error:", e);
            }
        },

        revokeImage(fieldKey) {
            if (this.client[`${fieldKey}_currentObjectURL`]?.startsWith('blob:')) {
                URL.revokeObjectURL(this.client[`${fieldKey}_currentObjectURL`]);
            }
        },

        async getClientData() {
            const res = await this.$callApi("post", `/route_import/${this.$route.params.id_route_import}/clients/${this.$route.params.id_client}/show`);
            const data = res.data;

            // Bulk assign properties
            Object.assign(this.client, data);
            
            // Format specific fields
            this.client.AvailableBrands = data.AvailableBrands_array_formatted || [];
            
            // Set Initial Previews from Server
            const imgFields = ['CustomerBarCode_image', 'facade_image', 'in_store_image'];
            imgFields.forEach(field => {
                if (data[field]) {
                    this.client[`${field}_currentObjectURL`] = `/uploads/clients/${data.id}/${data[field]}`;
                }
            });

            if (this.client.status === "visible") {
                this.start_adding_date = moment().format();
            }
            
            if (this.client.DistrictNo) this.getCites();
        },

        async getComboData() {
            const res = await this.$callApi("post", `/route_import/${this.$route.params.id_route_import}/districts`);
            this.willayas = res.data;
        },

        async getCites() {
            const res = await this.$callApi("post", `/rtm_willayas/${this.client.DistrictNo}/rtm_cites`);
            this.cites = res.data;
        },

        async sendData() {
            this.$showLoadingPage();
            
            const formData = new FormData();
            
            // Logic to determine what to send based on OpenCustomer status
            const isOuvert = this.client.OpenCustomer === 'Ouvert';

            // 1. Build Payload Object
            const payload = {
                ...this.client,
                AvailableBrands: JSON.stringify(this.client.AvailableBrands),
                finish_adding_date: moment().format(),
                start_adding_date: this.start_adding_date,
                // Override status if not open
                status: isOuvert ? this.client.status : 'pending'
            };

            // 2. Append text fields
            Object.keys(payload).forEach(key => {
                if (typeof payload[key] !== 'object' || Array.isArray(payload[key])) {
                    formData.append(key, payload[key] ?? '');
                }
            });

            // 3. Append Heavy Files from non-reactive storage
            Object.keys(this._rawFiles).forEach(key => {
                if (this._rawFiles[key]) {
                    formData.append(key, this._rawFiles[key]);
                }
            });

            try {
                const res = await this.$callApi("post", `/route_import/${this.$route.params.id_route_import}/clients/${this.client.id}/update`, formData);
                this.$feedbackSuccess(res.data.header, res.data.message);
                this.$goBack();
            } catch (err) {
                this.$showErrors("Error", ["Update failed"]);
            } finally {
                this.$hideLoadingPage();
            }
        },

        cleanupScanner() {
             if (this.scanner) {
                 this.scanner.clear().catch(e => {});
             }
        },

        refreshGPS() {
            this.check_gps_clicked = true;
            this.showPositionOnMap('show_map');
            setTimeout(() => { this.check_gps_clicked = false; }, 2000);
        },
    }
};

</script>

<style scoped>
.page-shell {
  height: calc(100dvh - 60px);
  min-height: 0;    /* ALLOWS children to shrink and let overflow work correctly */
  display: flex;
  flex-direction: column;
}

/* the scrollable area must allow overflow and shrink correctly */
.page-content {
  flex: 1 1 auto;
  min-height: 0;    /* important for overflow:auto inside flex columns */
  overflow: auto;
}

/* optional: make footer visually distinct and ensure it's on top if needed */
.page-footer {
  flex: 0 0 auto;
  z-index: 10;
  background: #fff;
}

.progress .progress-bar {
    background-color: #157347;
}

</style>