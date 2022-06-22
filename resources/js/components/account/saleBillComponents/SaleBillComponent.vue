<template>
    <VueLoader></VueLoader>
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block">
                        <div class="card card-bordered card-stretch">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <h3 class="nk-block-title page-title">Sale Bills</h3>
                                    </div>
                                    <div class="col-md-8 text-right">
                                        <a v-bind:href="create_sale_bill" class="dropdown-toggle btn btn-icon btn-primary mr-2"><em class="icon ni ni-plus"></em></a>
                                        <button @click="clearallfilter" class="btn btn-dark px-2">Clear</button>
				                    </div>
                                </div>

                            </div>
                            <div class="card-inner">
                                <table id="saleBill" class="table table-hover table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>IUID</th>
                                            <th>Ref. ID</th>
                                            <th>Updated At</th>
                                            <th>Bill Date</th>
                                            <th>Customer</th>
                                            <th>Supplier</th>
                                            <th>Supplier Inv. No.</th>
                                            <th>Amount</th>
                                            <th>Payment Status</th>
                                            <th>Outward Status</th>
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
    <ViewCompanyDetails ref="company"></ViewCompanyDetails>
</template>

<script>
    import ViewCompanyDetails from '../../databank/companyComponents/modal/ViewCompanyDetailsModelComponent.vue';
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
            ViewCompanyDetails,
            VueLoader
        },
        data() {
            return {
                create_sale_bill: 'sale-bill/create-sale-bill',
            }
        },
        created () { },
        methods: {
            showModal: function(id) {
                window.$('#overlay').show();
                this.$refs.company.fetch_company(id)
                window.$("#viewCompany1").modal('show');
                $('<div class="modal-backdrop fade show"></div>').appendTo(document.body);
                $('body').addClass('modal-open').css('overflow', 'hidden').css('padding-right', '17px');
            },
            closeModal: function() {
                window.$('#viewCompany1').modal('hide');
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open').removeAttr('style');
            },
            clearallfilter: function() {
                $("#sale_bill_filter").find('input[type=search]').val("");
                $('#saleBill').DataTable().clear().draw();
            }
        },
        mounted() {
            const self = this;
            var dt_table = null;
            /* const toINR = new Intl.NumberFormat('en-IN', {
                style: 'currency',
                currency: 'INR',
                // minimumFractionDigits: 2
            }); */
            var today = new Date();
            var dt = today.toJSON().slice(0, 10);
            var nDate = dt.slice(0, 4) + '-' + dt.slice(5, 7) + '-' + dt.slice(8, 10);
            function init_dt_table () {
                dt_table = $('#saleBill').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    ajax: {
                        url: "./sale-bill/list-data",
                        data: function (data) {
                            if ($('#sale_bill_id').val() == '') {
                                data.columns[0].search.value = '';
                            } else {
                                data.columns[0].search.value = $('#sale_bill_id').val();
                            }
                            if ($('#reference_id').val() == '') {
                                data.columns[2].search.value = '';
                            } else {
                                data.columns[2].search.value = $('#reference_id').val();
                            }
                            if ($('#updated_at').val() == '') {
                                data.columns[3].search.value = '';
                            } else {
                                data.columns[3].search.value = $('#updated_at').val();
                            }
                            if ($('#bill_date').val() == '') {
                                data.columns[4].search.value = '';
                            } else {
                                data.columns[4].search.value = $('#bill_date').val();
                            }
                            if ($('#customer_name').val() == '') {
                                data.columns[5].search.value = '';
                            } else {
                                data.columns[5].search.value = $('#customer_name').val();
                            }
                            if ($('#supplier_name').val() == '') {
                                data.columns[6].search.value = '';
                            } else {
                                data.columns[6].search.value = $('#supplier_name').val();
                            }
                            if ($('#supplier_inv_no').val() == '') {
                                data.columns[7].search.value = '';
                            } else {
                                data.columns[7].search.value = $('#supplier_inv_no').val();
                            }
                        },
                        complete: function (data) { }
                    },
                    pagingType: 'full_numbers',
                    dom: 'Blrtip',
                    order: [[ 0, "desc" ]],
                    columns: [
                        { data: 'sale_bill_id' },
                        { data: 'iuid' },
                        { data: 'general_ref_id' },
                        { data: 'updated_at' },
                        { data: 'select_date' },
                        { data: 'customer', orderable: false },
                        { data: 'supplier', orderable: false },
                        { data: 'supplier_invoice_no' },
                        { data: 'total', render: $.fn.dataTable.render.number(',', '.', 0, '₹') },
                        { data: 'payment_status', orderable: false },
                        { data: 'outward_status', orderable: false },
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
                    'print'],
                    createdRow: function( row, data, dataIndex ) {
                        var color = $(row).find('.color-flag').attr('data-color_flag');
                        if (color && color == '#FFFFC8') {
                            $(row).style('background', '#FFFFC8');
                        } else if (color && color == '#F2DEDE') {
                            $(row).style('background', '#F2DEDE')
                        }
                    }
                })
                .on( 'init.dt', function () {
                    $('<div class="dataTables_filter mt-2" id="sale_bill_filter"><input type="search" id="sale_bill_id" class="form-control form-control-sm w-10" placeholder="Sale Bill ID"><input type="search" id="reference_id" class="form-control form-control-sm w-10" placeholder="Reference ID"><input type="date" id="updated_at" class="form-control form-control-sm w-10" placeholder="Updated At" max="'+nDate+'"><input type="date" id="bill_date" class="form-control form-control-sm w-10" placeholder="Bill Date" max="'+nDate+'"><div class="input-group w-20"><input type="text" id="customer_name" class="form-control form-control-sm" placeholder="Customer"></div><div class="input-group w-20"><input type="text" id="supplier_name" class="form-control form-control-sm" placeholder="Supplier"></div><input type="search" id="supplier_inv_no" class="form-control form-control-sm w-10" placeholder="Supplier Invoice No"></div>').insertAfter('.dataTables_length');
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
            window.addEventListener('load', function () {
                $('#customer_name, #supplier_name').siblings('div.dropdown-menu').on('click', '.dropdown-item', function (e) {
                    dt_table.clear().draw();
                });
            }, false);
            axios.get('/common/list-customers-and-suppliers')
                .then(response => {
                    new Autocomplete(document.getElementById('customer_name'), {
                        threshold: 2,
                        data: response.data[0],
                        maximumItems: 5,
                        label: 'name',
                        value: 'id',
                        onSelectItem: ({ label, value }) => { }
                    });
                    new Autocomplete(document.getElementById('supplier_name'), {
                        threshold: 2,
                        data: response.data[1],
                        maximumItems: 5,
                        label: 'name',
                        value: 'id',
                        onSelectItem: ({ label, value }) => { }
                    });
                });
            var draw = 1;
            $(document).on('keyup', '#sale_bill_filter input', function(e) {
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

            $(document).on('click', '.delete-salebill', function(e) {
                if (confirm('Are you sure you want to delete?')) {
                    location.href = '/account/sale-bill/delete/' + $(this).attr('data-id');
                }
                return;
            });
            $(document).on('click', '.copy-salebill', function(e) {
                if (confirm('Are you sure you want to copy?')) {
                    location.href = '/account/sale-bill/copy/' + $(this).attr('data-id');
                }
                return;
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
