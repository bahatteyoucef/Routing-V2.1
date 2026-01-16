<template>
    <div>
        <loading-page></loading-page>

        <InternetErrorPage v-if="!show_internet_error_page"></InternetErrorPage>

        <div v-if="mobile_device && $isRole('FrontOffice')" v-show="show_install_button" id="BlockInstall" class="w-100 p-3">
            <div class="row">
                <div @click="closeInstallBanner()" class="col-2 p-0 d-flex justify-content-center align-items-center">
                    <i class="mdi mdi-close font-weight-bold text-light" style="font-size: 30px;"></i>
                </div>
                <div class="col-7 text-light p-0">
                    <div><h5>Routing V2.1 App</h5></div>
                    <div><span>Get our app for better experience.</span></div>
                </div>
                <div class="col-3 p-0 d-flex justify-content-center align-items-center">
                    <button id="BlockInstallButton" class="btn btn-light"><b>Install</b></button>
                </div>
            </div>
        </div>

        <router-view v-if="appReady"></router-view>
    </div>
</template>

<script>
import { mapGetters, mapActions } from "vuex"
import InternetErrorPage from "./partials/InternetErrorPage.vue"

export default {
    components: {
        InternetErrorPage
    },

    data() {
        return {
            appReady: false, // Controls when to mount the router
            
            // UI State
            show_install_button: false,
            mobile_device: false,
            show_internet_error_page: true // Default to true (online)
        }
    },

    computed: {
        ...mapGetters({
            getUser: 'authentification/getUser',
            getIsOnline: 'internet/getIsOnline'
        })
    },

    watch: {
        getIsOnline(newValue) {
            this.show_internet_error_page = newValue
        }
    },

    async mounted() {
        console.log("App Mounting...")

        // --- 1. Internet Connection Listeners ---
        const updateOnlineStatus = (status) => {
            this.setIsOnlineAction(status)
            this.show_internet_error_page = status
        }
        
        updateOnlineStatus(window.navigator.onLine)
        window.addEventListener('online', () => updateOnlineStatus(true))
        window.addEventListener('offline', () => updateOnlineStatus(false))


        // --- 2. Authentification Check ---
        const isAuthenticated = await this.checkIfUserIsAuthentificated()
        
        if (isAuthenticated) {
            // Store is valid, user is logged in
            // The Router Guard will handle sending them to /dashboard
        } else {
            // Invalid token
            this.setUserAction({})
            this.setAccessTokenAction("")
            this.setIsAuthentificatedAction(false)
            // The Router Guard will handle sending them to /login
        }

        // --- 3. Device Check ---
        await this.$nextTick()
        if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
            this.mobile_device = true
        }

        // --- 4. PWA Setup ---
        await this.$nextTick()
        this.pwaFunction()

        // --- 5. Application Ready ---
        // Now we let the router take over.
        this.appReady = true
    },

    methods: {
        ...mapActions("authentification", [
            "setUserAction",
            "setAccessTokenAction",
            "setIsAuthentificatedAction"
        ]),
        ...mapActions("internet", [
            "setIsOnlineAction"
        ]),

        async checkIfUserIsAuthentificated() {
            try {
                // Assuming $callApi handles headers automatically
                const res = await this.$callApi("post", "/users/is-authentificated", null)
                
                // If API returns empty string or error, invalid
                if (!res || res.data === "") {
                    localStorage.removeItem("vuex")
                    return false
                }
                
                this.setIsAuthentificatedAction(true)
                return true
            } catch (e) {
                console.error("Auth check failed", e)
                return false
            }
        },

        // --- PWA Logic ---
        pwaFunction() {
            let deferredPrompt;

            window.addEventListener('beforeinstallprompt', (e) => {
                e.preventDefault();
                deferredPrompt = e;
                this.show_install_button = true;
            });

            // We use a global click listener delegate or ensure the button exists in DOM
            // Since the banner is in the template now, we can use @click on the button directly ideally,
            // but keeping your logic similar to before:
            
            this.$nextTick(() => {
                const btn = document.getElementById('BlockInstallButton')
                if(btn) {
                    btn.addEventListener('click', async () => {
                        await this.$showLoadingPage()
                        if (deferredPrompt) {
                            deferredPrompt.prompt();
                            const { outcome } = await deferredPrompt.userChoice;
                            if (outcome === 'accepted') {
                                deferredPrompt = null;
                                this.show_install_button = false;
                            }
                        }
                        await this.$hideLoadingPage()
                    })
                }
            })
        },

        closeInstallBanner() {
            this.show_install_button = false
        }
    }
}
</script>

<style>
/* Your Root Styles */
:root {
    --popper-theme-background-color: #333333;
    --popper-theme-background-color-hover: #333333;
    --popper-theme-text-color: #ffffff;
    --popper-theme-border-width: 0px;
    --popper-theme-border-style: solid;
    --popper-theme-border-radius: 6px;
    --popper-theme-padding: 7px;
    --popper-theme-box-shadow: 0 6px 30px -6px rgba(0, 0, 0, 0.25);
}
</style>
<style src="@vueform/multiselect/themes/default.css"></style>