<template>
    <nav v-if="$route.fullPath" id="template-header" class="header_navigation_div">
        <div class="row pt-1">
            <div v-if="logo_buzz_image" class="col-6 d-flex align-items-center">
                <img :src="logo_buzz_image"         style="height: 40px; width: auto"   @click="showIndex()" />
            </div>

            <div v-if="store_image" class="col-2 d-flex justify-content-center align-items-center">
                <img :src="store_image"             style="height: 25px; width: auto"   @click="addClient()" />
            </div>

            <div v-if="group_clients_image" class="col-2 d-flex justify-content-center align-items-center">
                <img :src="group_clients_image"     style="height: 25px; width: auto"   @click="showClientsByStatus()" />
            </div>

            <div v-if="map_marker_image" class="col-2 d-flex justify-content-center align-items-center">
                <img :src="map_marker_image"        style="height: 25px; width: auto"   @click="goToMap()" />
            </div>
        </div>
    </nav>
</template>

<script>

import {mapGetters, mapActions} from    "vuex"

export default {

    data() {
        return {
            showAddButton: false,
            showHomeButton: true,
            showSurveysButton: false,
            logo_buzz_image: '',
            store_image: '',
            map_marker_image: '',
            group_clients_image: ''
        };
    },

    computed : {

        ...mapGetters({
            getIsOnline                 :   'internet/getIsOnline'      ,
            getUser                     :   'authentification/getUser'
        }),
    },

    mounted() {
        console.log("header store")
        this.fetchImages();
    },

    methods: {

        async fetchImages() {

            const response_1    =   await fetch('/images/front_office_images/logo_buzz.png');
            const response_2    =   await fetch('/images/front_office_images/store.png');
            const response_3    =   await fetch('/images/front_office_images/map_marker.png');
            const response_4    =   await fetch('/images/front_office_images/group_clients.png');

            //  //  //

            if (!response_1.ok) {
                throw new Error(`Failed to fetch image: ${response_1.statusText}`);
            }

            if (!response_2.ok) {
                throw new Error(`Failed to fetch image: ${response_2.statusText}`);
            }

            if (!response_3.ok) {
                throw new Error(`Failed to fetch image: ${response_3.statusText}`);
            }

            if (!response_4.ok) {
                throw new Error(`Failed to fetch image: ${response_4.statusText}`);
            }

            //  //  //

            const blob_1    =   await response_1.blob();
            const reader_1  =   new FileReader();

            reader_1.onloadend  =   ()  =>  {
                this.logo_buzz_image   = reader_1.result; // This will be the base64 string
            };

            reader_1.readAsDataURL(blob_1);

            //

            const blob_2    =   await response_2.blob();
            const reader_2  =   new FileReader();

            reader_2.onloadend  =   ()  =>  {
                this.store_image    = reader_2.result; // This will be the base64 string
            };

            reader_2.readAsDataURL(blob_2);

            //
            const blob_3    =   await response_3.blob();
            const reader_3  =   new FileReader();

            reader_3.onloadend  =   ()  =>  {
                this.map_marker_image     = reader_3.result; // This will be the base64 string
            };

            reader_3.readAsDataURL(blob_3);

            //
            const blob_4    =   await response_4.blob();
            const reader_4  =   new FileReader();

            reader_4.onloadend  =   ()  =>  {
                this.group_clients_image    = reader_4.result; // This will be the base64 string
            };

            reader_4.readAsDataURL(blob_4);
        },

        //

        showIndex() {

            if(this.getIsOnline) {

                this.$router.push('/front-office')
            }
        },

        addClient() {

            if(this.getIsOnline) {

                this.$router.push('/route-imports/'+this.getUser.id_route_import+'/clients/add')
            }
        },

        goToMap() {

            if(this.getIsOnline) {

                this.$router.push('/route/frontoffice/obs/route-imports/'+this.getUser.id_route_import+'/details')
            }
        },

        showClientsByStatus() {

            this.$router.push('/route-imports/'+this.getUser.id_route_import+'/clients/by-status')
        },
    },

    watch: {
        '$route.path': {
            handler(newPath) {
                this.showAddButton = newPath !== "/front-office-role/surveys/add";
                this.showHomeButton = true;
                this.showSurveysButton = newPath !== "/front-office-role/surveys";
            },
            immediate: true
        }
    }
};

</script>

<style scoped>
.header_navigation_div {
    height: 50px;
    border-bottom: 2px solid #2372BA;
    padding-bottom: 50px;
    padding-left: 10px;
    padding-right: 10px;
}
</style>