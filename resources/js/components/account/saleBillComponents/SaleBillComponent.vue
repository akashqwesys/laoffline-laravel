<template>
    <VueLoader></VueLoader>
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">Sale Bills</h3>
                                <div class="nk-block-des text-soft">
                                </div>
                            </div><!-- .nk-block-head-content -->
                            <div class="nk-block-head-content">
                                <div class="toggle-wrap nk-block-tools-toggle">
                                    <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                    <div class="toggle-expand-content" data-content="pageMenu">
                                        <ul class="nk-block-tools g-3">
                                            <li class="nk-block-tools-opt">
                                                <a v-bind:href="create_sale_bill" class="dropdown-toggle btn btn-icon btn-primary"><em class="icon ni ni-plus"></em></a>
                                            </li>
                                        </ul>
                                    </div>
                                </div><!-- .toggle-wrap -->
                            </div><!-- .nk-block-head-content -->
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    <div class="nk-block">
                        <div class="card card-bordered card-stretch">
                            <div class="card-inner">
                                <table id="companies" class="table table-hover table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th></th>
                                            <th></th>
                                            <th>Name</th>
                                            <th>Office No.</th>
                                            <th>Company Type</th>
                                            <th>Company Category</th>
                                            <th>City</th>
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
    import VueLoader from './../../../VueLoader.vue';

    import $ from 'jquery';
    import 'datatables.net-responsive-bs4/js/responsive.bootstrap4';
    import "datatables.net-buttons-bs5/js/buttons.bootstrap5";
    import 'pdfmake/build/pdfmake';
    import "datatables.net-buttons/js/buttons.html5";
    import "datatables.net-buttons/js/buttons.print";

    export default {
        name: 'saleBill',
        props: {
            excelAccess: Number,
        },
        components: {
            VueLoader
        },
        data() {
            return {
                create_sale_bill: 'sale-bill/create-sale-bill',
            }
        },
        methods: {
        },
        mounted() {
            const self = this;
            var buttons = [];
            var dt_table = null;
            function init_dt_table () {
                dt_table = $('#companies').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    ajax: {
                        url: "./companies/list",
                        data: function (data) {
                            if ($('#dt_name').val() == '') {
                                data.columns[3].search.value = '';
                            } else {
                                data.columns[3].search.value = $('#dt_name').val();
                            }
                            if ($('#dt_office_no').val() == '') {
                                data.columns[4].search.value = '';
                            } else {
                                data.columns[4].search.value = $('#dt_office_no').val();
                            }
                            if ($('#dt_company_type').val() == '') {
                                data.columns[5].search.value = '';
                            } else {
                                data.columns[5].search.value = $('#dt_company_type').val();
                            }
                            if ($('#dt_company_category').val() == '') {
                                data.columns[6].search.value = '';
                            } else {
                                data.columns[6].search.value = $('#dt_company_category').val();
                            }
                            if ($('#dt_city').val() == '') {
                                data.columns[7].search.value = '';
                            } else {
                                data.columns[7].search.value = $('#dt_city').val();
                            }
                        },
                        complete: function (data) { }
                    },
                    pagingType: 'full_numbers',
                    dom: 'Blrtip',
                    columns: [
                        { data: 'id' },
                        { data: 'flag', orderable: false },
                        { data: 'verified', orderable: false },
                        { data: 'company_name' },
                        { data: 'office_no', orderable: false },
                        { data: 'company_type' },
                        { data: 'company_category' },
                        { data: 'company_city' },
                        { data: 'action', orderable: false },
                    ],
                    search: {
                        return: true
                    },
                    buttons: [{
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
                    'print']
                })
                .on( 'init.dt', function () {
                    $('<div class="dataTables_filter mt-2" id="company_filter"><input type="search" id="dt_name" class="form-control form-control-sm" placeholder="Name"><input type="search" id="dt_office_no" class="form-control form-control-sm" placeholder="Office Number"><input type="search" id="dt_company_type" class="form-control form-control-sm" placeholder="Company Type"><input type="search" id="dt_company_category" class="form-control form-control-sm" placeholder="Company Category"><input type="search" id="dt_city" class="form-control form-control-sm" placeholder="City"></div>').insertAfter('.dataTables_length');
                } );
                dt_table.on( 'responsive-resize', function ( e, datatable, columns ) {
                    var count = columns.reduce( function (a,b) {
                        return b === false ? a+1 : a;
                    }, 0 );
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
            $(document).on('keyup', '#company_filter input', function(e) {
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

            $(document).on('click', '.view-details', function(e) {
                self.showModal($(this).attr('data-id'));
            });

            document.getElementById('viewCompany1').addEventListener('hidden.bs.modal', function (event) {
                $('.modal-backdrop').remove();
            });
        },
    };
</script>

<style scoped>
    .icon.ni.ni-star, .icon.ni.ni-star-fill,
    .icon.ni.ni-alert-fill, .icon.ni.ni-check-thick {
        font-size: 20px;

    }
    .icon.ni.ni-star, .icon.ni.ni-star-fill {
        cursor: pointer;
    }
</style>
