<template>
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block">
                        <div class="card card-bordered card-stretch">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <h3 class="nk-block-title page-title">Product Sub Category Lists</h3>
                                    </div>
                                    <div class="col-md-8 text-right">
                                        <a v-bind:href="create_product_sub_category" class="dropdown-toggle btn btn-icon btn-primary mx-2"><em class="icon ni ni-plus"></em></a>
                                        <button @click="clearallfilter" class="btn btn-dark px-2">Clear</button>
				                    </div>
                                </div>

                            </div>
                            <div class="card-inner">
                                <table id="productSubCategory" class="table table-hover table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Name</th>
                                            <th>Main Category</th>
                                            <th>Fabric Group</th>
                                            <th width="50%">Company</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div><!-- .card -->
                        </div>
                    </div><!-- .nk-block -->
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import 'jquery/dist/jquery.min.js';
    // import 'datatables.net-bs5';
    import 'datatables.net-bs5/js/dataTables.bootstrap5';
    import 'datatables.net-responsive-bs4/js/responsive.bootstrap4';
    import "datatables.net-buttons-bs5/js/buttons.bootstrap5";
    import "datatables.net-buttons/js/buttons.flash.js";
    import "datatables.net-buttons/js/buttons.html5.js";
    import "datatables.net-buttons/js/buttons.print.js";
    import $ from 'jquery';

    export default {
        name: 'productSubCategory',
        props: {
            excelAccess: Number,
        },
        data() {
            return {
                create_product_sub_category: 'productsub-category/create-productsub-category',
            }
        },
        created () {
            axios.get('/common/list-all-companies')
                .then(response => {
                    new Autocomplete(document.getElementById('dt_company'), {
                        threshold: 2,
                        data: response.data,
                        maximumItems: 5,
                        label: 'name',
                        value: 'id',
                        onSelectItem: ({ label, value }) => { }
                    });
                });
        },
        methods: {
            getCompanyName(id){
                axios.get('./productsub-category/company-name/'+id)
                .then(response => {
                    var companyName = response.data;
                    return companyName;
                });
            },
            edit_data(id){
                window.location.href = './productsub-category/edit-productsub-category/'+id;
            },
            delete_data(id){
                axios.delete('./productsub-category/delete/'+id)
                .then(response => {
                    location.reload();
                });
            },
            clearallfilter(event) {
                $('#product_filter').find("input[type=search]").val('');
                $('#productSubCategory').DataTable().clear().draw();
            },
        },
        mounted() {
            var buttons = [];
            var dt_table = null;
            if(this.excelAccess == 1) {
                buttons = [{
                    extend: 'excelHtml5',
                    action: exportAllRecords,
                    exportOptions: {
                        columns: ':not(:last-child)'
                    }
                }, {
                    extend: 'pdfHtml5',
                    action: exportAllRecords,
                    exportOptions: {
                        columns: ':not(:last-child)'
                    }
                },
                'print'];
            }
            function init_dt_table () {
                dt_table = $('#productSubCategory').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    ajax: {
                        url: "./productsub-category/list",
                        data: function (data) {
                            if ($('#dt_name').val() == '') {
                                data.columns[2].search.value = '';
                            } else {
                                data.columns[2].search.value = $('#dt_name').val();
                            }
                            if ($('#dt_category').val() == '') {
                                data.columns[3].search.value = '';
                            } else {
                                data.columns[3].search.value = $('#dt_category').val();
                            }
                            if ($('#fabric_group').val() == '') {
                                data.columns[4].search.value = '';
                            } else {
                                data.columns[4].search.value = $('#fabric_group').val();
                            }
                            if ($('#dt_company').val() == '') {
                                data.columns[5].search.value = '';
                            } else {
                                data.columns[5].search.value = $('#dt_company').val();
                            }
                        },
                        complete: function (data) { }
                    },
                    pagingType: 'full_numbers',
                    dom: 'Blrtip',
                    order: [[ 0, "desc" ]],
                    columns: [
                        { data: 'id' },
                        { data: 'name' },
                        { data: 'main_category' },
                        { data: 'fabric_group', orderable: false },
                        { data: 'company', orderable: false },
                        { data: 'action', orderable: false },
                    ],
                    buttons: buttons
                })
                .on( 'init.dt', function () {
                    $('<div class="dataTables_filter mt-2" id="product_filter"><input type="search" id="dt_name" class="form-control form-control-sm" placeholder="Name"><input type="search" id="dt_category" class="form-control form-control-sm" placeholder="Main Category"><input type="search" id="fabric_group" class="form-control form-control-sm" placeholder="Fabric Group"><div class="input-group"><input type="text" id="dt_company" class="form-control form-control-sm" placeholder="Company"></div></div>').insertAfter('.dataTables_length');
                } );
            }
            init_dt_table();
            function exportAllRecords(e, dt, button, config) {
                var self = this;
                var oldStart = dt.settings()[0]._iDisplayStart;
                dt.one('preXhr', function (e, s, data) {
                    // Just this once, load all data from the server...
                    data.start = 0;
                    data.length = 'all';
                    dt.one('preDraw', function (e, settings) {
                        // Call the original action function
                        if (button[0].className.indexOf('buttons-excel') >= 0) {
                            $.fn.dataTable.ext.buttons.excelHtml5.available(dt, config) ?
                                $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config) :
                                $.fn.dataTable.ext.buttons.excelFlash.action.call(self, e, dt, button, config);
                        } else if (button[0].className.indexOf('buttons-pdf') >= 0) {
                                $.fn.dataTable.ext.buttons.pdfHtml5.available(dt, config) ?
                                $.fn.dataTable.ext.buttons.pdfHtml5.action.call(self, e, dt, button, config) :
                                $.fn.dataTable.ext.buttons.pdfFlash.action.call(self, e, dt, button, config);
                        }
                        dt.one('preXhr', function (e, s, data) {
                            // DataTables thinks the first item displayed is index 0, but we're not drawing that.
                            // Set the property to what it was before exporting.
                            settings._iDisplayStart = oldStart;
                            data.start = oldStart;
                        });
                        // Reload the grid with the original page. Otherwise, API functions like table.cell(this) don't work properly.
                        setTimeout(dt.ajax.reload, 10);
                        // Prevent rendering of the full data to the DOM
                        return false;
                    });
                });
                // Requery the server with the new one-time export settings
                dt.ajax.reload();
            }
            var draw = 1;
            var old_company_value = null;
            $(document).on('keyup', '#product_filter input', function(e) {
                if ($(this).val() == '') {
                    if (draw == 0) {
                        dt_table.clear().draw();
                        draw = 1;
                    }
                } else {
                    if (e.keyCode == 13) {
                        dt_table.clear().draw();
                    }
                    draw = 0;
                }
            });
            window.addEventListener('load', function () {
                $('#dt_company').siblings('div.dropdown-menu').on('click', '.dropdown-item', function (e) {
                    dt_table.clear().draw();
                });
            }, false);
        },
    };
</script>
<style>
</style>
