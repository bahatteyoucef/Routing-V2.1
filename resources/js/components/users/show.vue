<template>
    <div class="p-3" style="height: 90%;">
        <div class="card w-100 shadow-lg">
            <div class="card-img-container" style="height: 45%; width: 100%; overflow: hidden"> 
                <img id="profile_image_display"     src="/images/front_office_images/profile_page_image.png"   class="card-img-top">
            </div>

            <div v-if="user" class="card-body" style="overflow-y: auto;">
                <div>
                    <div class="d-flex flex-row justify-content-center gap-2">
                        <span class="fw-bold fst-italic">Username :</span>
                        <span>{{ user.username }}</span>
                    </div>

                    <div class="d-flex flex-row justify-content-center gap-2">
                        <span class="fw-bold fst-italic">Email :</span>
                        <span>{{ user.email }}</span>
                    </div>

                    <div class="d-flex flex-row justify-content-center gap-2">
                        <span class="fw-bold fst-italic">First Name :</span>
                        <span>{{ user.first_name }}</span>
                    </div>

                    <div class="d-flex flex-row justify-content-center gap-2">
                        <span class="fw-bold fst-italic">Last Name :</span>
                        <span>{{ user.last_name }}</span>
                    </div>

                    <div class="d-flex flex-row justify-content-center gap-2">
                        <span class="fw-bold fst-italic">Tel :</span>
                        <span>{{ user.tel }}</span>
                    </div>

                    <div class="d-flex flex-row justify-content-center gap-2">
                        <span class="fw-bold fst-italic">Company :</span>
                        <span>{{ user.company }}</span>
                    </div>

                    <div class="d-flex flex-row justify-content-center gap-2">
                        <span class="fw-bold fst-italic">Type User :</span>
                        <span>{{ user.type_user }}</span>
                    </div>

                    <div class="d-flex flex-row justify-content-center gap-2">
                        <span class="fw-bold fst-italic">Route Imports :</span>
                        <span>{{ user.liste_route_import_string }}</span>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>
 
<script>

export default {

    data() {
        return {
            user            :   {
                username_original           :   '',

                id                          :   '',
                username                    :   '',
                first_name                  :   '',
                last_name                   :   '',

                email                       :   '',
                tel                         :   '',
                company                     :   '',
                type_user                   :   '',

                max_route_import            :   0 ,

                liste_route_import          :   [],
                liste_route_import_string   :   ""
            },
        }
    },

    async mounted() {
        await this.getData()
    },  

    methods : {

        async getData(user) {

            // Show Loading Page
            await this.$showLoadingPage()

            await this.getUserData()  

            this.setListeRouteImport()

            // Hide Loading Page
            await this.$hideLoadingPage()
        },

        async getUserData() {

            const res                   =   await this.$callApi("post"  ,   "/users/"+this.$route.params.id_user+"/show"    ,   null)
            console.log(res)

            this.user.username_original     =   res.data.user.username

            this.user.id                    =   res.data.user.id                 
            this.user.username              =   res.data.user.username                 
            this.user.first_name            =   res.data.user.first_name                 
            this.user.last_name             =   res.data.user.last_name                 

            this.user.email                 =   res.data.user.email   
            this.user.tel                   =   res.data.user.tel                 
            this.user.company               =   res.data.user.company    
            this.user.type_user             =   res.data.user.type_user        

            this.user.max_route_import      =   res.data.user.max_route_import        

            this.user.liste_route_import    =   res.data.user.liste_route_import   
        },

        //

        setListeRouteImport() {   

            for (let i = 0; i < this.user.liste_route_import.length; i++) {

                this.user.liste_route_import_string     =   this.user.liste_route_import_string     +   " - "   +   this.user.liste_route_import[i].libelle
            }
        },
    }
};
</script>

<style scoped>

/* Ensure the card takes full height of the parent container */
.card {
    height: 100% !important; 
    border: none; /* Optional: removes border to look cleaner */
}

/* 1. Image Section: 35% */
.card-img-container {
    height: 35%;
    overflow: hidden;
}

/* Make sure the image inside fills the container properly */
.card-img-container img {
    width: 100%;
    height: 100%;
    object-fit: contain; /* Key property: prevents image distortion */
}

/* 2. Body Section: 35% */
.card-body {
    height: 35%;
    overflow-y: auto; /* Enables scroll ONLY here */
}

/* 3. Footer Section: 30% */
.card-footer {
    height: 30%;
    overflow-y: hidden; /* Prevents scroll in footer */
}

</style>