<template>
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">Product Lists</h3>
                                <div class="nk-block-des text-soft">
                                    <!-- <p>You have total {{products.length}} product.</p> -->
                                </div>
                            </div><!-- .nk-block-head-content -->
                            <div class="nk-block-head-content">
                                <div class="toggle-wrap nk-block-tools-toggle">
                                    <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                    <div class="toggle-expand-content" data-content="pageMenu">
                                        <ul class="nk-block-tools g-3">
                                            <li class="nk-block-tools-opt">
                                                <a v-bind:href="create_product_category" class="dropdown-toggle btn btn-icon btn-primary"><em class="icon ni ni-plus"></em></a>
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
                                <table id="product" class="table table-hover table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th></th>
                                            <th>Image</th>
                                            <th>Name</th>
                                            <th>Catalogue Name</th>
                                            <th>Brand Name</th>
                                            <th>Model Name</th>
                                            <th>Company</th>
                                            <th>Category</th>
                                            <th>Price</th>
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
    import $ from 'jquery';
    import 'datatables.net-responsive-bs4/js/responsive.bootstrap4';
    import "datatables.net-buttons-bs5/js/buttons.bootstrap5";
    import 'pdfmake/build/pdfmake';
    import "datatables.net-buttons/js/buttons.html5";
    import "datatables.net-buttons/js/buttons.print";

    export default {
        name: 'product',
        props: {
            excelAccess: Number,
        },
        data() {
            return {
                create_product_category: 'catalog/create-products',
            }
        },
        methods: {
            edit_data(id){
                window.location.href = './catalog/edit-products/'+id;
            },
            view_data(id){
                // window.location.href = './catalog/edit-products/'+id;
            },
            delete_data(id){
                axios.delete('./catalog/delete/'+id)
                .then(response => {
                    location.reload();
                });
            }
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
                dt_table = $('#product').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    ajax: {
                        url: "./catalog/list",
                        data: function (data) {
                            if ($('#dt_name').val() == '') {
                                data.columns[2].search.value = '';
                            } else {
                                data.columns[2].search.value = $('#dt_name').val();
                            }
                            if ($('#dt_company').val() == '') {
                                data.columns[3].search.value = '';
                            } else {
                                data.columns[3].search.value = $('#dt_company').val();
                            }
                            if ($('#dt_category').val() == '') {
                                data.columns[4].search.value = '';
                            } else {
                                data.columns[4].search.value = $('#dt_category').val();
                            }
                        },
                        complete: function (data) { }
                    },
                    pagingType: 'full_numbers',
                    dom: 'Blrtip',
                    order: [[0, "desc"]],
                    columns: [
                        { data: 'id' },
                        { data: 'flag', orderable: false },
                        { data: 'image', orderable: false },
                        { data: 'product_name' },
                        { data: 'catalogue_name' },
                        { data: 'brand_name' },
                        { data: 'model' },
                        { data: 'company_name' },
                        { data: 'category_name' },
                        { data: 'catalogue_price' },
                        { data: 'action', orderable: false },
                    ],
                    buttons: buttons
                })
                .on( 'init.dt', function () {
                    $('<div class="dataTables_filter mt-2" id="product_filter"><input type="search" id="dt_name" class="form-control form-control-sm" placeholder="Name/Catalogue/Brand/Model"><input type="search" id="dt_company" class="form-control form-control-sm" placeholder="Company"><input type="search" id="dt_category" class="form-control form-control-sm" placeholder="Category"></div>').insertAfter('.dataTables_length');
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
        },
    };
</script>
<style scoped>
    .user-avatar.user-avatar-sm.product {
        border-radius: 0;
        height: 50px;
        width: 50px;
    }
    .user-avatar.user-avatar-sm.product span {
        text-transform: capitalize;
        font-size: 20px;
    }
    .user-avatar img, [class^="user-avatar"]:not([class*="-group"]) img.product_image {
        border-radius: 0;
        height: 100%;
    }
</style>
