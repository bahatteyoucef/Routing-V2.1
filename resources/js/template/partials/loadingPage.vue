<template>
    <div id="loading_screen" class="justify-content-center align-items-center" style="display:none;">

        <div class="circular-progress center-content">
            <span class="progress-value">{{ progress_integer }} %</span>
        </div>

    </div>
</template>

<script>
export default {
    
    data() {

        return {

            loading_screen      :   null    ,   
            circularProgress    :   null    ,

            displayValue        :   'none'  ,

            progress            :   0       ,
            progress_integer    :   0       ,
            increment           :   0.1     , // smaller increment for smoother transition
            progress_animation  :   null
        }
    },

    mounted() {

        //
        this.observeDisplayChange();
    },

    methods : {

        updateProgress() {

            let progressStartValue  = 0    
            let progressEndValue    = 100    
            let speed               = 125

            let step                = 0.5

            let first_part          = true
            let second_part         = false
            let third_part          = false
            let fourth_part         = false

            this.progress_animation = setInterval(() => {

                progressStartValue                      =   progressStartValue  +   step

                this.progress                           =   progressStartValue
                this.progress_integer                   =   parseInt(this.progress)

                this.circularProgress.style.background  =   `conic-gradient(#7d2ae8 ${progressStartValue * 3.6}deg, #ededed 0deg)`

                if((this.progress   >=   55)&&(first_part)) {

                    step            =   step/2

                    first_part      =   false
                    second_part     =   true
                }

                if((this.progress   >=   55)&&(this.progress    <=   75)&&(second_part)) {

                    step            =   step/2

                    second_part     =   false
                    third_part      =   true
                }

                if((this.progress   >=   85)&&(this.progress    <=   95)&&(third_part)) {

                    step            =   step/2

                    third_part      =   false
                    fourth_part     =   true
                }

                if((this.progress    >=   95)&&(this.progress    <=   100)&&(fourth_part)) {

                    step            =   step/2

                    fourth_part     =   false
                }

                //
                if(progressStartValue >= progressEndValue){

                    clearInterval(this.progress_animation);
                }    
            }, speed);        
        },

        //

        observeDisplayChange() {

            this.loading_screen     =   document.getElementById("loading_screen")
            this.circularProgress   =   document.querySelector(".circular-progress")   

            const observer = new MutationObserver((mutations) => {

                mutations.forEach((mutation) => {

                    if (mutation.attributeName === 'style') {

                        const display       =   window.getComputedStyle(this.loading_screen).getPropertyValue('display');
                        this.displayValue   =   display;
                    }
                });
            });

            observer.observe(this.loading_screen, { attributes: true });
        },
    },

    watch : {

        displayValue(new_value, old_value) {

            if(new_value    ==  "flex") {

                this.updateProgress()
            }

            else {

                clearInterval(this.progress_animation);

                this.progress               =   0
                this.progress_integer       =   0
                this.progress_animation     =   null
            }
        }
    }
}

</script>

<style scoped>


</style>