<template>
    <div style="position : fixed; z-index: 99;" class="p-1 w-100">
        <div class="row">
            <div class="col-3 d-flex justify-content-center align-items-center">
                <img :src="'/images/wifi-connection-offline-icon-error.png'" style="height : 30px ; width : auto"   @load="errorImageLoaded()"/>
            </div>

            <div class="col-9">
                <h6>No Internet Network !</h6>
                <span class="text-small">Please check your network connection</span>
            </div>
        </div>
    </div>
</template>

<script>

    export default {
        
        data() {

            return {

                show_error_card :   false
            }
        },

        methods : {

            errorImageLoaded() {

                this.show_error_card    =   true
            }
        }
    }

</script>


