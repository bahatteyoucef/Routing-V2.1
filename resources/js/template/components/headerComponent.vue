<template>
    <div>
        <div
            class="row d-sm-flex justify-content-between align-items-start"
        >
            <div class="col-sm-10">
                <h4 class="ml-1 card-title"> {{title}} <button class="ml-3 remove_button_style" @click="setDataTable()"><i class="mdi mdi-reload"></i></button></h4>
            </div>

            <div    class="row col-sm-2 justify-content-end">
                <div v-if="((update_button) && (update_button.length    >   0))"   class="col-sm-4 pr-1 pl-1">
                    <button
                        button type="button" data-bs-toggle="modal" :data-bs-target="'#'+update_modal" class="btn primary w-100"
                        v-on:click="updateElement()"
                    >
                        <i class="mdi mdi-pencil-box-outline"></i>
                    </button>
                </div>

                <div v-if="((add_button) && (add_button.length    >   0))"      class="col-sm-4 pr-1 pl-1">
                    <button
                        button type="button" data-bs-toggle="modal" :data-bs-target="'#'+add_modal" class="btn primary w-100"
                        v-on:click="addElement()"
                    >
                        <i class="mdi mdi-plus-box-outline"></i>
                    </button>
                </div>
            </div>
        </div>

        <br />

    </div>
</template>

<script>

    export default {

        props : ["title" , "add_button", "update_button", "add_modal",  "update_modal"],

        data() {

            return {

                can_do  :   false
            }
        },

        methods : {

            addElement() {

                this.$parent.addElement()
            },

            updateElement() {

                this.$parent.updateElement()
            },

            async setDataTable() {

                await this.$parent.setDataTable()
            },
        }
    }

</script>
