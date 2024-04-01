import axios from 'axios'

export default {

    methods: {

        //

        $feedbackSuccess(header,html)
        {
            this.$swal({
                icon                : 'success',
                title               : header,
                html                : html,
                timer               : 3000,

                showCancelButton    : false,
                showConfirmButton   : false,

                customClass : {
                    container: 'position-absolute'
                },
                toast       : true,
                position    : 'top-right'
            })
        },

        $feedbackWarning(header,html)
        {
            this.$swal({
                icon                : 'warning',
                title               : header,
                html                : html,
                timer               : 3000,

                showCancelButton    : false,
                showConfirmButton   : false,

                customClass : {
                    container: 'position-absolute'
                },
                toast       : true,
                position    : 'top-right'
            })
        },

        $feedbackError(header,html)
        {
            this.$swal({
                icon                : 'error',
                title               : header,
                html                : html,
                timer               : 3000,

                showCancelButton    : false,
                showConfirmButton   : false,

                customClass : {
                    container: 'position-absolute'
                },
                toast       : true,
                position    : 'top-right'
            })
        },

        $showErrors(message,errors)
        {
            let validation_errors   =   "<ul>";

            let list_items          =   ""

            Object.keys(errors).forEach(element => {
                list_items          =   list_items   +   "<li>"  +   errors[element] +   "</li>"
            });

            validation_errors       =   list_items   +   "</ul>"
            
            this.$feedbackError(message   ,   validation_errors)
        },
    },   
}