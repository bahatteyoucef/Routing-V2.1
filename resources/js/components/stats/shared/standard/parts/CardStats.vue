<template>
    <div class="row h-equal p-0 pl-2 m-0">

        <!-- Card : Validated + Pending + Not Validated -->
        <div class="col p-1">
            <div v-if="number_clients_tel_status_validated   !=  null" class="card h-100 card-img-holder text-white p-3"       style="background-color: #0F9D58">
                <div class="card-body p-1">
                    <h4 class="font-weight-normal mb-3">Tel Validated <i class="mdi mdi-bookmark-outline mdi-24px float-right"></i></h4>
                    <h2 class="mb-1 animate__animated animate__pulse">{{number_clients_tel_status_validated}}</h2>
                </div>
            </div>
        </div>

        <div class="col p-1">
            <div v-if="number_clients_confirmed   !=  null" class="card h-100 card-img-holder text-white p-3"       style="background-color: #0F9D58">
                <div class="card-body p-1">
                    <h4 class="font-weight-normal mb-3">Confirmed <i class="mdi mdi-bookmark-outline mdi-24px float-right"></i></h4>
                    <h2 class="mb-1 animate__animated animate__pulse">{{number_clients_confirmed}}</h2>
                </div>
            </div>
        </div>

        <div class="col p-1">
            <div v-if="number_clients_validated   !=  null" class="card h-100 card-img-holder text-white p-3"       style="background-color: #0F9D58">
                <div class="card-body p-1">
                    <h4 class="font-weight-normal mb-3">Validated <i class="mdi mdi-bookmark-outline mdi-24px float-right"></i></h4>
                    <h2 class="mb-1 animate__animated animate__pulse">{{number_clients_validated}}</h2>
                </div>
            </div>
        </div>

        <!-- <div class="col p-1">
            <div v-if="number_clients_ferme   !=  null" class="card h-100 card-img-holder text-white p-3"           style="background-color: #0288D1;">
                <div class="card-body p-1">
                    <h4 class="font-weight-normal mb-3">Ferme <i class="mdi mdi-bookmark-outline mdi-24px float-right"></i></h4>
                    <h2 class="mb-1 animate__animated animate__pulse">{{number_clients_ferme}}</h2>
                </div>
            </div>
        </div> -->

        <!-- <div class="col p-1">
            <div v-if="number_clients_introuvable   !=  null" class="card h-100 card-img-holder text-white p-3"     style="background-color: #000000;">
                <div class="card-body p-1">
                    <h4 class="font-weight-normal mb-3">Introuvable <i class="mdi mdi-bookmark-outline mdi-24px float-right"></i></h4>
                    <h2 class="mb-1 animate__animated animate__pulse">{{number_clients_introuvable}}</h2>
                </div>
            </div>
        </div> -->

        <div class="col p-1">
            <div v-if="number_clients_pending !=  null" class="card h-100 card-img-holder text-white p-3"           style="background-color: #F57C00;">
                <div class="card-body p-1">
                    <h4 class="font-weight-normal mb-3">Pending <i class="mdi mdi-bookmark-outline mdi-24px float-right"></i></h4>
                    <h2 class="mb-1 animate__animated animate__pulse">{{number_clients_pending}}</h2>
                </div>
            </div>
        </div>

        <div class="col p-1">
            <div v-if="number_clients_nonvalidated    !=  null" class="card h-100 card-img-holder text-white p-3"   style="background-color: #F70000;">
                <div class="card-body p-1">
                    <h4 class="font-weight-normal mb-3">Non Validated <i class="mdi mdi-bookmark-outline mdi-24px float-right"></i></h4>
                    <h2 class="mb-1 animate__animated animate__pulse">{{number_clients_nonvalidated}}</h2>
                </div>
            </div>
        </div>

        <div class="col p-1">
            <div v-if="number_clients_visible !=  null" class="card h-100 card-img-holder text-white p-3"           style="background-color: #3949AB;">
                <div class="card-body p-1">
                    <h4 class="font-weight-normal mb-3">Visible <i class="mdi mdi-bookmark-outline mdi-24px float-right"></i></h4>
                    <h2 class="mb-1 animate__animated animate__pulse">{{number_clients_visible}}</h2>
                </div>
            </div>
        </div>

        <div class="col p-1">
            <div v-if="number_clients_non_visible   !=  null"     class="card h-100 bg-primary card-img-holder text-white p-3">
                <div class="card-body p-1">
                    <h4 class="font-weight-normal mb-3">Total <i class="mdi mdi-bookmark-outline mdi-24px float-right"></i></h4>
                    <h2 class="mb-1 animate__animated animate__pulse">{{number_clients_non_visible}}</h2>
                </div>
            </div>
        </div>

        <div class="col p-1 h-100">
            <div v-if="number_clients_expected    !=  null" class="card h-100 bg-primary card-img-holder text-white p-3">
                <div class="card-body p-1">
                    <h4 class="font-weight-normal mb-3">Expected <i class="mdi mdi-bookmark-outline mdi-24px float-right"></i></h4>
                    <h2 class="mb-1 animate__animated animate__pulse">{{number_clients_expected}}</h2>
                </div>
            </div>
        </div>

    </div>
</template>

<script>

export default {

    props : ["number_clients_tel_status_validated", "number_clients_confirmed", "number_clients_validated", "number_clients_ferme", "number_clients_refus", "number_clients_introuvable", "number_clients_pending", "number_clients_nonvalidated", "number_clients_visible", "number_clients_non_visible", "number_clients_total", "number_clients_expected"]
}

</script>