<template>
    <div style="position : fixed; z-index: 99;" class="p-1">
        <!-- <div class="card p-0" v-show="show_error_card"> -->
        <div class="card p-0">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-3 d-flex justify-content-center align-items-center">
                        <img :src="'/images/wifi-connection-offline-icon-error.png'" style="height : 30px ; width : auto"   @load="errorImageLoaded()"/>
                    </div>

                    <div class="col-9">
                        <h5>No Internet Network !</h5>
                        <span>Please check your network connection</span>
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


