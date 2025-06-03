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

        $showWarnings(message,warnings)
        {
            let validation_warnings =   "<ul>";

            let list_items          =   ""

            Object.keys(warnings).forEach(element => {

                list_items              =   list_items   +   "<li>"  +   warnings[element] +   "</li>"
            });

            validation_warnings     =   list_items   +   "</ul>"
            
            this.$feedbackWarning(message   ,   validation_warnings)
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

        //

        $customMessages(title, text, icon, confirmButtonText, cancelButtonText) {

            return new Promise((resolve) => {
                this.$swal.fire({
                    title: title,
                    text: text,
                    icon: icon,
                    didRender: () => {
                        // Remove the `swal2-icon-content` element
                        const iconContent = document.querySelector('.swal2-icon-content');
                        if (iconContent) {
                            iconContent.remove();
                        }
                    },
                    showCancelButton: cancelButtonText !== "",
                    confirmButtonText: confirmButtonText,
                    cancelButtonText: cancelButtonText,
                }).then((result) => {
                    if (result.isConfirmed) {
                        resolve(true);
                    } else {
                        resolve(false);
                    }
                });
            });
        },
    },   
}