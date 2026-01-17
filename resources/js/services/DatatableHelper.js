
class DataTableHelper {

    constructor() {

        this.$tableId           =   ""  ;   // ID of the table element
        this.$tableColumns      =   []  ;   // Columns configuration for DataTables

        this.$setDatatableFn    =   null;
        this.$addRowFn          =   null;
        this.$editRowFn         =   null;   // Function to trigger on row edit
        this.$deleteRowFn       =   null;   // Function to trigger on row delete
        this.$selectRowFn       =   null;

        this.$tableTitle        =   ""  ;   //

        this.$datatable         =   null;   // DataTable instance

        this.$datatable_type    =   ""  ;
        this.$checkRowFn        =   null;
        this.$uncheckRowFn      =   null;

        //
        this.$selectedRow       =   null
        this.$selectedRowID     =   null
    }

    //  //  //  //  //
    //  //  //  //  //
    //  //  //  //  //

    // Initialize or edit the DataTable
    $DataTableCreate(tableId, tableData, tableColumns, setDatatableFn, addRowFn, editRowFn, deleteRowFn, selectRowFn, title, datatable_type = "simple", checkRowFn = () => {}, uncheckRowFn = () => {}) {

        //
        this.$tableId           =   tableId;        // ID of the table element
        this.$tableData         =   tableData;      // Laravel route name for fetching data
        this.$tableColumns      =   tableColumns;   // Columns configuration for DataTables

        this.$setDatatableFn    =   setDatatableFn;
        this.$addRowFn          =   addRowFn;       // Function to trigger on row click
        this.$editRowFn         =   editRowFn;      // Function to trigger on row click
        this.$deleteRowFn       =   deleteRowFn;
        this.$selectRowFn       =   selectRowFn;

        this.$tableTitle        =   title;
        this.$datatable_type    =   datatable_type;

        this.$checkRowFn        =   checkRowFn;
        this.$uncheckRowFn      =   uncheckRowFn;

        //
        if ($.fn.DataTable.isDataTable('#' + this.$tableId)) {
        
            // If the table already exists, edit its data
            this.$datatable.clear();                    // remove old data
            this.$datatable.rows.add(this.$tableData);    // add new data array
            this.$datatable.draw(false); 

            //
            this.$selectedRow       =   null
            this.$selectedRowID     =   null

            //
            if(this.$selectRowFn) this.$selectRowFn(null, null)
        }

        else {

            // Initialize a new DataTable
            this.$setDataTable();

            //
            this.$bindTableEvents()

            //
            this.$setSearchColumn();

            //
            this.$setCheckboxFunction()
        }

        //
        return this.$datatable;
    }

    $setDataTable() {

        this.$datatable  =  $('#' + this.$tableId).DataTable({

                                data            :   this.$tableData         ,
                                columns         :   this.$columnsConfig()   ,

                                order           :   [...(this.$datatable_type == "simple"  ? [[0, 'asc']] : [1, 'asc'])]    ,

                                processing      :   false                   ,
                                deferRender     :   true                    ,
                                responsive      :   true                    ,

                                orderCellsTop   :   true                    ,                   

                                scrollCollapse  :   true                    ,
                                scrollY         :   "400px"                 ,
                                scrollX         :   true                    ,

                                dom             :   'Blfrtip'               , 

                                buttons         :   {
                                                        dom :   {
                                                            button  :   {
                                                                className   :   ''   // strip out dt-button here
                                                            }
                                                        },

                                                        buttons     :   this.$buttonsConfig()
                                                    },

                                pagingType      :   'simple_numbers'            ,
                                language        :   this.$languageConfig()      ,

                                lengthMenu      :   [
                                                        // the values you get when you select an option
                                                        [50, 250, 1000, -1] ,

                                                        // the labels shown in the dropdown
                                                        [50, 250, 1000, "All"]
                                                    ],
                                
                                // optionally set the initial pageLength
                                pageLength      :   50,

                                createdRow      :   (row, data, dataIndex)  =>  this.$rowsConfig(row, data, dataIndex)  ,

                                initComplete: () => {
                                    this.$adjustTableBodyAndHeaderAlignement()
                                },
                            });
    }

    $columnsConfig() {
            
        return [

            ...(this.$datatable_type    ==  "checkbox"      ?   [   // Custom checkbox column
                                                                    {
                                                                        data: null,
                                                                        className: "text-center",
                                                                        orderable: false,
                                                                        width: "30px",
                                                                        render: (data, type, row) => {
                                                                            return `
                                                                                <input type="checkbox" 
                                                                                        class="row-checkbox" 
                                                                                        data-id="${row.id}"
                                                                                        ${row.selected ? 'checked' : ''}>
                                                                            `;
                                                                        }
                                                                    }
                                                                ] : []) ,

            // Index column
            {
                title: "#",
                data: null,
                className: "text-center",
                orderable: true,
                width: "50px",
                render: (data, type, row, meta) => meta.row + 1
            },

            // Spread in the rest of your columns (existing columns)
            ...this.$tableColumns
        ];
    }

    $languageConfig() {

        return  {
                    lengthMenu:  '_MENU_',
                    search:      '',
                    zeroRecords: 'No Data ...',
                    emptyTable:  'No Data ...',
                    paginate: {
                        first:    'First',
                        previous: 'Previous',
                        // next:     'Next',
                        // last:     'Last',

                        next:     '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                        previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>'
                    }
                };
    }

    $rowsConfig(row, data, dataIndex) {

        $(row).attr('id', this.$tableId + '_' + (dataIndex + 1));
        $(row).attr('role', 'button');
    }

    $bindTableEvents() {
        // Attach ONE listener to the tbody
        $('#' + this.$tableId + ' tbody').on('click', 'tr', (event) => {
            const tr = event.currentTarget;
            
            // Logic copied from your previous code
            if (tr.classList.contains('active_row')) {
                this.$selectedRow = null;
                this.$selectedRowID = null;
                tr.classList.remove('active_row');
                if (this.$selectRowFn) this.$selectRowFn(null, null);
            } else {
                const rowData = this.$datatable.row(tr).data();
                const rowId = $(tr).attr('id');

                this.$selectedRow = rowData;
                this.$selectedRowID = rowId;

                if (this.$selectRowFn) this.$selectRowFn(this.$selectedRow, this.$selectedRowID);

                $('#' + this.$tableId + ' tbody tr').removeClass('active_row');
                $(tr).addClass('active_row');
            }
        });
    }

    //  //  //  //  //
    //  //  //  //  //
    //  //  //  //  //

    $buttonsConfig() {

        return  [
                    ...(this.$setDatatableFn    ? [ this.$setDatatableConfig()     ] : [])  ,
                    ...(this.$addRowFn          ? [ this.$addButtonConfig()        ] : [])  ,
                    ...(this.$editRowFn         ? [ this.$editButtonConfig()       ] : [])  ,
                    ...(this.$deleteRowFn       ? [ this.$deleteButtonConfig()     ] : [])  ,
                    this.$excelButtonConfig()                                               ,
                    this.$pdfButtonConfig()
                ]
    }

    $setDatatableConfig() {

        return  {
                    text        :   "<i class='mdi mdi-refresh'></i>",
                    title       :   null,
                    className   :   'btn btn-sm btn-primary p-0', // inject Bootstrap classes
                    name        :   'add', // Assign a unique name to the button
                    action      :   ()  =>  {
                        this.$setDatatableFn()
                    }
                }
    }

    $addButtonConfig() {

        return  {
                    text        :   "<i class='mdi mdi-plus-circle'></i>",
                    title       :   null,
                    className   :   'btn btn-sm btn-primary p-0', // inject Bootstrap classes
                    name        :   'add', // Assign a unique name to the button
                    action      :   ()  =>  {
                        this.$addRowFn()
                    }
                }
    }

    $editButtonConfig() {

        return  {
                    text        :   "<i class='mdi mdi-pen'></i>",
                    title       :   null,
                    className   :   'btn btn-sm btn-primary p-0', // inject Bootstrap classes
                    name        :   'edit', // Assign a unique name to the button,
                    action      :   ()  =>  {
                        this.$editRowFn(this.$selectedRow)
                    }
                }
    }

    $deleteButtonConfig() {

        return  {
                    text        :   "<i class='mdi mdi-delete'></i>",
                    title       :   null,
                    className   :   'btn btn-sm btn-primary p-0', // inject Bootstrap classes
                    name        :   'delete' // Assign a unique name to the button
                }
    }

    $excelButtonConfig() {

        return  {
                    extend      :   'excelHtml5',
                    text        :   '<i class="mdi mdi-file-excel"></i>',
                    title       :   null,
                    className   :   'btn btn-sm btn-primary p-0',
                    filename    :   this.$tableTitle,   // This explicitly sets the filename to "this.$tableTitle".
                }
    }

    $pdfButtonConfig() {

        return  { 
                    extend      :   "pdfHtml5",
                    text        :   "<i class='mdi mdi-file-pdf-box'></i>",
                    title       :   null,
                    className   :   'btn btn-sm btn-primary p-0'
                }
    }

    //  //  //  //  //
    //  //  //  //  //
    //  //  //  //  //

    // Move pagination combo
    $movePaginationCombo() {

        //
        const dt_buttons    =   document.getElementsByClassName('dt-buttons');
        for (let index = 0; index < dt_buttons.length; index++) {
            dt_buttons[index].classList.add('mt-0');
        }

        //
        const dataTables_length =   document.querySelector('#' + this.$tableId + '_wrapper').querySelector('.dataTables_length');

        if (dataTables_length   !=  null) {
            const paginate_numbers  =   document.querySelector('#' + this.$tableId + '_wrapper').querySelector('#' + this.$tableId + '_paginate');
            paginate_numbers.before(dataTables_length);
        }
    }

    // Bind column filters
    $setSearchColumn() {
        
        const api = this.$datatable;              // DataTable API instance
        const tableId = this.$tableId;            // capture table ID
        const headerRow = $('#' + tableId + '_filters');

        // Utility to escape regex metacharacters
        function escapeRegex(text) {
            return text.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
        }

        // Build a search string or regex based on type
        function buildSearch(type, value) {
            const v = escapeRegex(value);
            switch (type) {
            case 'contains':
                return value;                                       // plain text search
            case 'not_contains':
                return '^(?!.*' + v + ').*$';                       // negative lookahead :contentReference[oaicite:4]{index=4}
            case 'exact':
                return '^' + v + '$';                               // exact match :contentReference[oaicite:5]{index=5}
            case 'starts_with':
                return '^' + v;                                     // begins with :contentReference[oaicite:6]{index=6}
            case 'ends_with':
                return v + '$';                                     // ends with
            default:
                return value;
            }
        }

        // Iterate over each <th> in the filter row
        headerRow.find('th').each((colIdx, th) => {
            const $th = $(th);
            const $group = $th.find('.filter_group');
            const $input = $group.find('.filter_input');
            const $type  = $group.find('.filter_type');

            // Unbind any previous handlers
            $input.off('keyup change');
            $type.off('change');

            // When the input changes...
            $input.on('keyup change', () => {
            const val  = $input.val() || '';
            const typ  = $type.val();
            const query = buildSearch(typ, val);

            api.column(colIdx)
                .search(query, true, false)        // regex = true, smart = false :contentReference[oaicite:7]{index=7}
                .draw();
            });

            // When the select changes, re-trigger input handler
            $type.on('change', () => {
            $input.trigger('change');
            });
        });
    }

    //
    $adjustTableBodyAndHeaderAlignement() {

        const tableContainer = document.getElementById(this.$tableId + '_container');
        
        // Debounce function to limit execution rate
        let timeoutId;
        const debouncedAdjust = () => {
            clearTimeout(timeoutId);
            timeoutId = setTimeout(() => {
                // Only adjust columns after 100ms of silence (animation finished)
                if (this.$datatable) {
                    this.$datatable.columns.adjust();
                }
            }, 100);
        };

        const resizeObserver = new ResizeObserver(() => {
            debouncedAdjust();
        });

        if (tableContainer) {
            resizeObserver.observe(tableContainer);
        }
    }

    // Adjust Styling
    $adjustStylingElements() {

        //  Show Elements because they are hidden by default

        const dataTables_length     =   $('#' + this.$tableId + '_length')
        const dataTables_paginate   =   $('#' + this.$tableId + '_paginate')

        if(dataTables_length) {
            dataTables_length.css("display", "block")
        }

        if(dataTables_paginate) {
            dataTables_paginate.css("display", "block")
        }

        //  //  //  //  //  //        
    }

    //
    $setCheckboxFunction() {

        if(this.$datatable_type ==  "checkbox") {
            $('#' + this.$tableId).on('change', '.row-checkbox', (e) => {  // Use arrow function
                
                const checkbox  =   $(e.target);
                const row       =   this.$datatable.row(checkbox.closest('tr'));  // Get the row object
                const rowData   =   row.data();  // Get full row data
                
                if (checkbox.prop('checked'))   this.$checkRowFn(rowData);
                else                            this.$uncheckRowFn(rowData);
            })
        }
    }

    //  //  //  //  //  //  //  //  //  //  //  //  //  //  //  //  //
    //  //  //  //  //  Datatable Added Functions   //  //  //  //  //
    //  //  //  //  //  //  //  //  //  //  //  //  //  //  //  //  //

    $getCheckedRows(tableId) {

        const selectedRows  =   [];
        
        // Iterate through all checkboxes
        $('#' + tableId + ' .row-checkbox:checked').each((index, checkbox) => {

            const row = this.$datatable.row($(checkbox).closest('tr'));

            if (row) {

                const rowData       =   row.data();
                selectedRows.push(rowData);
            }
        });

        return selectedRows;
    }

    $checkUncheckAllRows(tableId, datatable, checked) {

        let checkbox    =   null;
        let row         =   null;
        let rowData     =   null;

        // Iterate through all checkboxes
        if(checked) {
            $('#' + tableId + ' .row-checkbox').each((index, checkbox_element) => {

                checkbox_element.checked    =   true

                checkbox                    =   $(checkbox_element);
                row                         =   this.$datatable.row(checkbox.closest('tr'));  // Get the row object
                rowData                     =   row.data();  // Get full row data
                
                this.$checkRowFn(rowData);
            });
        }

        else {
            $('#' + tableId + ' .row-checkbox').each((index, checkbox_element) => {

                checkbox_element.checked    =   false

                checkbox                    =   $(checkbox_element);
                row                         =   this.$datatable.row(checkbox.closest('tr'));  // Get the row object
                rowData                     =   row.data();  // Get full row 

                this.$uncheckRowFn(rowData);
            });
        }
    }
}

// Make it globally accessible
export default DataTableHelper;