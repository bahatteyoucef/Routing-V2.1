<template>
    <div v-if="getShowLoadingPage" id="loading_screen" role="status" aria-live="polite" aria-busy="true">
        <span class="sr-only">Loading…</span>

        <div class="loading_container">
            <div class="sk-cube-grid">
                <div class="sk-cube sk-cube1"></div>
                <div class="sk-cube sk-cube2"></div>
                <div class="sk-cube sk-cube3"></div>
                <div class="sk-cube sk-cube4"></div>
                <div class="sk-cube sk-cube5"></div>
                <div class="sk-cube sk-cube6"></div>
                <div class="sk-cube sk-cube7"></div>
                <div class="sk-cube sk-cube8"></div>
                <div class="sk-cube sk-cube9"></div>
            </div>
        </div>
    </div>
</template>

<script>

import { mapGetters }   from    "vuex"

export default {

    computed : {
        ...mapGetters({
            getShowLoadingPage  :   'loading_page/getShowLoadingPage'
        }),
    },
}

</script>

<style>

#loading_screen {
    display         :   flex;
    position        :   fixed;
    z-index         :   99999;
    height          :   100vh;
    width           :   100vw;
    display         :   flex;
    align-items     :   center; /* Center content vertically */
    justify-content :   center; /* Center content horizontally */
    background      :   rgba(255, 255, 255, 0.3);
    backdrop-filter :   blur(5px);
}

.loading_container {
    display         :   flex;
    align-items     :   center; /* Align items in the center vertically */
    margin-bottom   :   90px;
}

.loading_text {
    color           :   #2c78bd;
    margin          :   15px;
}

.sk-cube-grid {
    display                 :   grid;
    grid-template-columns   :   repeat(3, 1fr); /* Creates 3 equal columns */
    gap                     :   3px; /* Adjust the gap size as needed */
}

.sk-cube {
    width                   :   35px;         /*10px; /* Cube width */
    height                  :   35px;         /*10px; /* Cube height */
    background-color        : #2c78bd;      /* Cube color */
    animation               :   sk-cube-grid-scale 1.3s infinite ease-in-out;
}

/* Assign animation delays for each cube */
.sk-cube1 { animation-delay: 0.2s; }
.sk-cube2 { animation-delay: 0.3s; }
.sk-cube3 { animation-delay: 0.4s; }
.sk-cube4 { animation-delay: 0.1s; }
.sk-cube5 { animation-delay: 0.2s; }
.sk-cube6 { animation-delay: 0.3s; }
.sk-cube7 { animation-delay: 0.0s; }
.sk-cube8 { animation-delay: 0.1s; }
.sk-cube9 { animation-delay: 0.2s; }

@keyframes sk-cube-grid-scale {
    0%, 70%, 100%   {
        transform       :   scale3D(1, 1, 1);
    }
    35% {
        transform       :   scale3D(0, 0, 1);
    }
}

.loading_text {
    color               :   #2c78bd
}

</style>