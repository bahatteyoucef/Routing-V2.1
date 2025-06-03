<template>
    <div class="row h-equal p-0 pl-2 m-0">

        <!-- Card : Validated + Pending + Not Validated -->
        <div class="col p-1">
            <div class="card h-100 bg-gradient-success card-img-holder text-white p-3" v-if="number_clients_validated   !=  null">
            <div class="card-body p-1">
                <h4 class="font-weight-normal mb-3">Validated <i class="mdi mdi-bookmark-outline mdi-24px float-right"></i>
                </h4>
                <h2 class="mb-1 animate__animated animate__pulse">{{number_clients_validated}}</h2>
            </div>
            </div>
        </div>

        <div class="col p-1">
            <div class="card h-100 bg-gradient-secondary card-img-holder text-white p-3" v-if="number_clients_ferme !=  null">
            <div class="card-body p-1">
                <h4 class="font-weight-normal mb-3">Ferme <i class="mdi mdi-bookmark-outline mdi-24px float-right"></i>
                </h4>
                <h2 class="mb-1 animate__animated animate__pulse">{{number_clients_ferme}}</h2>
            </div>
            </div>
        </div>

        <div class="col p-1">
            <div class="card h-100 bg-gradient-warning card-img-holder text-white p-3" v-if="number_clients_pending     !=  null">
            <div class="card-body p-1">
                <h4 class="font-weight-normal mb-3">Pending <i class="mdi mdi-bookmark-outline mdi-24px float-right"></i>
                </h4>
                <h2 class="mb-1 animate__animated animate__pulse">{{number_clients_pending}}</h2>
            </div>
            </div>
        </div>

        <div class="col p-1">
            <div class="card h-100 bg-gradient-danger card-img-holder text-white p-3" v-if="number_clients_nonvalidated !=  null">
            <div class="card-body p-1">
                <h4 class="font-weight-normal mb-3">Non Validated <i class="mdi mdi-bookmark-outline mdi-24px float-right"></i>
                </h4>
                <h2 class="mb-1 animate__animated animate__pulse">{{number_clients_nonvalidated}}</h2>
            </div>
            </div>
        </div>

        <div class="col p-1">
            <div class="card h-100 bg-gradient-info card-img-holder text-white p-3" v-if="number_clients_visible !=  null">
            <div class="card-body p-1">
                <h4 class="font-weight-normal mb-3">Visible <i class="mdi mdi-bookmark-outline mdi-24px float-right"></i>
                </h4>
                <h2 class="mb-1 animate__animated animate__pulse">{{number_clients_visible}}</h2>
            </div>
            </div>
        </div>

        <div class="col p-1">
            <div class="card h-100 bg-gradient-primary card-img-holder text-white p-3" v-if="number_clients_total          !=  null">
            <div class="card-body p-1">
                <h4 class="font-weight-normal mb-3">Total <i class="mdi mdi-bookmark-outline mdi-24px float-right"></i>
                </h4>
                <h2 class="mb-1 animate__animated animate__pulse">{{number_clients_total}}</h2>
            </div>
            </div>
        </div>

        <div class="col p-1 h-100">
            <div class="card h-100 bg-gradient-primary card-img-holder text-white p-3" v-if="number_clients_expected       !=  null">
            <div class="card-body p-1">
                <h4 class="font-weight-normal mb-3">Expected <i class="mdi mdi-bookmark-outline mdi-24px float-right"></i>
                </h4>
                <h2 class="mb-1 animate__animated animate__pulse">{{number_clients_expected}}</h2>
            </div>
            </div>
        </div>

    </div>
</template>

<script>

export default {

    props : ["number_clients_validated", "number_clients_ferme", "number_clients_pending", "number_clients_nonvalidated", "number_clients_visible", "number_clients_total", "number_clients_expected"]
}

</script>