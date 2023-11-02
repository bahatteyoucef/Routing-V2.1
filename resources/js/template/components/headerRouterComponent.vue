<template>
    <div>
        <div
            class="row d-sm-flex justify-content-between align-items-start"
        >
            <div class="col-sm-8">
            </div>

            <div class="row col-sm-4 justify-content-end">
                <div v-if="((produits_button) && (produits_button.length    >   0))"        class="col-sm-3 pr-1 pl-1">
                    <button
                        button type="button" class="btn primary w-100"
                        v-on:click="produitsPage()"
                    >
                        Products
                    </button>
                </div>

                <div v-if="((marques_button) && (marques_button.length    >   0))"          class="col-sm-3 pr-1 pl-1">
                    <button
                        button type="button" class="btn primary w-100"
                        v-on:click="marquesPage()"
                    >
                        Brands
                    </button>
                </div>

                <div v-if="((categories_button) && (categories_button.length    >   0))"    class="col-sm-3 pr-1 pl-1">
                    <button
                        button type="button" class="btn primary w-100"
                        v-on:click="categoriesPage()"
                    >
                        Categories
                    </button>
                </div>

                <div v-if="((types_button) && (types_button.length    >   0))"              class="col-sm-3 pr-1 pl-1">
                    <button
                        button type="button" class="btn primary w-100"
                        v-on:click="typesPage()"
                    >
                        Types
                    </button>
                </div>

            </div>
        </div>

        <br />

    </div>
</template>

<script>

    export default {

        props : ["produits_button", "marques_button", "categories_button", "types_button"],

        data() {

            return {

                can_do  :   false
            }
        },

        methods : {

            produitsPage() {

                this.$router.push("/produits")
            },

            marquesPage() {

                this.$router.push("/marques")
            },

            categoriesPage() {

                this.$router.push("/categories")
            },

            typesPage() {

                this.$router.push("/types")
            },
        }
    }

</script>
