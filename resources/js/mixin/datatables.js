// 

export default {

    data() {
        return {

            datatable   :   null
        }
    },

    methods: {

        // Non Excel

        async $DataTableCreate(table) {

            await this.$setDataTable(table)

            this.$setSearchColumn(table)

            return this.datatable
        },
        
        $setDataTable(table) {

            return new Promise((resolve, reject) => {

                setTimeout(()   =>  {

                    this.datatable   =   $('#'+table).DataTable(
                    {
                        destroy     : true,
                        responsive  : true,

                        dom         : "Blfrtip",

                        buttons: [
                            { extend  : "excelHtml5"    , text    : "<i class='mdi mdi-microsoft-excel'></i>"   },
                            { extend  : "copyHtml5"     , text    : "<i class='mdi mdi-content-copy'></i>"      },
                            { extend  : "pdfHtml5"      , text    : "<i class='mdi mdi-file-outline'></i>"      },
                        ],

                        language    : {

                            lengthMenu      :   "_MENU_",
                            search          :   "<div class='search_input_text mt-1 pr-0'>Search : </div>",

                            zeroRecords     :   "Pas de resultats ...",
                            emptyTable      :   "Pas de resultats ...",

                            paginate    :   {
                                "first"     :   "Premier",
                                "last"      :   "Dernier",
                                "next"      :   "Suivant",
                                "previous"  :   "Precedent"
                            },
                        },

                        initComplete: () => {

                            this.$movePaginationCombo(table)

                            resolve(this.datatable);
                        }
                    });

                });
            });
        },

        $movePaginationCombo(table) {

            // Hide Search
            const search_input_text         =   document.getElementsByClassName("search_input_text")[0]
            search_input_text.parentElement.remove()

            //

            const dataTables_length         =   document.querySelector("#"+table+"_wrapper").querySelector(".dataTables_length")

            if(dataTables_length            !=  null)   {

                const paginate_numbers      =   document.querySelector("#"+table+"_wrapper").querySelector("#"+table+"_paginate")
                paginate_numbers.before(dataTables_length)
            }
        },

        $setSearchColumn(table) {

            var api = this.datatable;
 
            // For each column
            api
                .columns()
                .eq(0)
                .each(function (colIdx) {

                    var cursorPosition  =   null

                    // On every keypress in this input
                    $(
                        'input',
                        $('.'+table+'_filters th').eq($(api.column(colIdx).header()).index())
                    )
                        .off('keyup change')
                        .on('change', function (e) {

                            // Get the search value
                            $(this).attr('title', $(this).val());
                            var regexr = '({search})'; //$(this).parents('th').find('select').val();
 
                            cursorPosition  =   this.selectionStart;

                            // Search the column for that value
                            api
                                .column(colIdx)
                                .search(
                                    this.value != ''
                                        ? regexr.replace('{search}', '(((' + this.value + ')))')
                                        : '',
                                    this.value != '',
                                    this.value == ''
                                )
                                .draw();
                        })

                        .on('keyup', function (e) {
                            e.stopPropagation();
 
                            $(this).trigger('change');
                            $(this)
                                .focus()[0]
                                .setSelectionRange(cursorPosition, cursorPosition);
                        });
            });
        }

        // 

    },   
}