<template>
    <div style="position: fixed;
                width: 90%;
                z-index: 99;
                top: 0;
                left: 50%;
                transform: translateX(-50%);" 
        class="p-1">
        <div class="card shadow-lg p-0">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-3 d-flex justify-content-center align-items-center">
                        <img 
                            :src="gps_off_icon_error_icon" 
                            style="height: 30px; width: auto;" 
                        />
                    </div>
                    <div class="col-9">
                        <h6>Erreur GPS !</h6>
                        <span class="text-small">Vérifiez si votre GPS est activée</span>
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
            gps_off_icon_error_icon: ''
        };
    },

    mounted() {
    
        this.fetchImages();
    },

    methods: {

        async fetchImages() {

            try {

                const response = await fetch('/images/front_office_images/gps-off-icon-error.png');

                if (!response.ok) {
                    throw new Error(`Failed to fetch image: ${response.statusText}`);
                }

                const blob = await response.blob();
                const reader = new FileReader();

                reader.onloadend = () => {
                    this.gps_off_icon_error_icon = reader.result;
                };

                reader.readAsDataURL(blob);
            } catch (error) {
                console.error(error);
            }
        }
    },
};
</script>
