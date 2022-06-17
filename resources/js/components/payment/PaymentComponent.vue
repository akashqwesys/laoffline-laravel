<template>
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">

                    </div><!-- .nk-block-head -->
                    <div class="nk-block">
                        <div class="card card-bordered card-stretch">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <h3 class="nk-block-title page-title">Payment List</h3>
                                    </div>
                                    <div class="col-md-8 text-right">
				                    	<a href="/payments/status/1" class="btn btn-success mx-1">
				                            Completed
				                        </a>
				                        <a href="/payments/status/0" class="btn btn-danger mx-1">
				                            Incomplete
				                        </a>

										<a href="/payments/goods_returns" class="btn btn-primary mx-1">
				                            Goods Return
				                        </a>
										<button @click="clearallfilter" class="btn btn-dark mx-1">
				                            Clear All
				                        </button>
                                        <a v-bind:href="create_payment" class="dropdown-toggle btn btn-icon btn-primary"><em class="icon ni ni-plus"></em></a>
				                    </div>
                                </div>

                            </div>
                            <div class="card-inner">
                                <div class="table-responsive">
                                <table id="payment" class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th></th>
				                            <th>IUID</th>
				                            <th>OUID</th>
				                            <th>Ref. ID</th>
				                            <th>Date Added</th>
				                            <th>Payment Date</th>
                                            <th>Customer</th>
                                            <th>Supplier</th>
				                            <th>Voucher No</th>
				                            <th>Paid Amount</th>
				                            <th>Supplier Commission Status</th>
				                            <th>Customer Commission Status</th>
				                            <th>Outward Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                </table>
                                </div>
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
    import ViewCompanyDetails from '../databank/companyComponents/modal/ViewCompanyDetailsModelComponent.vue';

    import 'jquery/dist/jquery.min.js';
    import 'datatables.net-bs5/js/dataTables.bootstrap5';
    import 'datatables.net-responsive-bs4/js/responsive.bootstrap4';
    import "datatables.net-buttons-bs5/js/buttons.bootstrap5";
    import "datatables.net-buttons/js/buttons.flash.js";
    import "datatables.net-buttons/js/buttons.html5.js";
    import "datatables.net-buttons/js/buttons.print.js";
    import $ from 'jquery';

    export default {
        name: 'payment',
        props: {
            excelAccess: Number,
        },
        components: {
            ViewCompanyDetails
        },
        data() {
            return {
                create_payment: '/payments/create-payment',
            }
        },
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
            edit_data(id){
                window.location.href = './payments/edit-payment/'+id;
            },
            delete_data(id){
                axios.delete('./payments/delete/'+id)
                .then(response => {
                    location.reload();
                });
            },
            clearallfilter(event){
                $("#payment_filter").find('input[type=search]').val("");
                $('#payment').DataTable().clear().draw();
            }
        },
        mounted() {
            const self = this;
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
                dt_table = $('#payment').DataTable({
                    processing: true,
                    serverSide: true,
                    lengthChange: true,
                    ajax: {
                        url: "/payments/list",
                        data: function (data) {
                            if ($('#payment_no').val() == '') {
                                data.columns[0].search.value = '';
                            } else {
                                data.columns[0].search.value = $('#payment_no').val();
                            }
                            if ($('#iuid').val() == '') {
                                data.columns[1].search.value = '';
                            } else {
                                data.columns[1].search.value = $('#iuid').val();
                            }
                            if ($('#ouid').val() == '') {
                                data.columns[2].search.value = '';
                            } else {
                                data.columns[2].search.value = $('#ouid').val();
                            }
                            if ($('#ref_no').val() == '') {
                                data.columns[3].search.value = '';
                            } else {
                                data.columns[3].search.value = $('#ref_no').val();
                            }
                            if ($('#date_added').val() == '') {
                                data.columns[4].search.value = '';
                            } else {
                                data.columns[4].search.value = $('#date_added').val();
                            }
                            if ($('#paymentdate').val() == '') {
                                data.columns[5].search.value = '';
                            } else {
                                data.columns[5].search.value = $('#paymentdate').val();
                            }
                            if ($('#customer').val() == '') {
                                data.columns[6].search.value = '';
                            } else {
                                data.columns[6].search.value = $('#customer').val();
                            }
                            if ($('#supplier').val() == '') {
                                data.columns[7].search.value = '';
                            } else {
                                data.columns[7].search.value = $('#supplier').val();
                            }
                            if ($('#voucher').val() == '') {
                                data.columns[8].search.value = '';
                            } else {
                                data.columns[8].search.value = $('#voucher').val();
                            }
                            if ($('#paidamount').val() == '') {
                                data.columns[9].search.value = '';
                            } else {
                                data.columns[9].search.value = $('#paidamount').val();
                            }
                        },
                        complete: function (data) { }
                    },
                    pagingType: 'full_numbers',
                    dom: "Blrtip",
                    order: [[ 0, "desc" ]],
                    columns: [
                        { data: 'id' },
                        { data: 'sign', orderable: false},
                        { data: 'iuid' },
                        { data: 'ouid' },
                        { data: 'reference_id' },
                        { data: 'created_at' },
                        { data: 'date' },
                        { data: 'customer', orderable: false },
                        { data: 'supplier', orderable: false },
                        { data: 'payment_id' },
                        { data: 'tot_adjust_amount' },
                        { data: 'suppiler_commission_status', orderable: false },
                        { data: 'customer_commission_status', orderable: false },
                        { data: 'outward_status', orderable: false },
                        { data: 'action', orderable: false },
                    ],
                    search: {
                        return: true
                    },
                    buttons: buttons,
                    "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
                        switch(aData['color_flag_id']){
                            case 1:
                                $('td', nRow).css('background-color', '#FFFFC8')
                                break;
                            case 3:
                                $('td', nRow).css('background-color', '#C4FFC4')
                                break;
                        }
                    }
                })
                .on( 'init.dt', function () {
                    $('<div class="dataTables_filter mt-2" id="payment_filter"><input type="search" id="payment_no" class="form-control form-control-sm" placeholder="Payment Id"><input type="search" id="iuid" class="form-control form-control-sm" placeholder="iuid"><input type="search" id="ouid" class="form-control form-control-sm" placeholder="ouid"><input type="search" id="ref_no" class="form-control form-control-sm" placeholder="Reference No"><input type="search" id="date_added" class="form-control form-control-sm" placeholder="Date Added"><input type="search" id="paymentdate" class="form-control form-control-sm" placeholder="Payment Date"><input type="search" id="customer" class="form-control form-control-sm" placeholder="Customer"><input type="search" id="supplier" class="form-control form-control-sm" placeholder="Supplier Name"><input type="search" id="voucher" class="form-control form-control-sm" placeholder="Voucher No"><input type="search" id="paidamount" class="form-control form-control-sm" placeholder="Amount"></div>').insertAfter('.dataTables_length');
                });
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
            $(document).on('keyup', '#payment_filter input', function(e) {
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
<style >
    .user-avatar img{
        width: 100%;
    }
    .dataTables_filter {
        display: flex;
        margin-bottom: 15px;
    }
    .dataTables_filter input {
        padding: 17px;
        margin: 0 10px;
        width: 25%;
    }
    .dt-buttons {
        position: relative;
        display: inline-flex;
        vertical-align: middle;
        flex-wrap: wrap;
        float: right;
    }
    .dt-buttons .dt-button {
        position: relative;
        flex: 1 1 auto;
        display: inline-flex;
        font-family: Nunito, sans-serif;
        font-weight: 700;
        color: #526484;
        text-align: center;
        vertical-align: middle;
        user-select: none;
        background-color: transparent;
        border: 1px solid #dbdfea;
        padding: 0.4375rem 0;
        font-size: 0.8125rem;
        line-height: 1.25rem;
        border-radius: 4px;
        transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }
    .dt-buttons .dt-button::before {
        font-size: 1.125rem;
        font-weight: normal;
        font-style: normal;
        width: 2.125rem;
        font-family: "Nioicon";
    }
    /* .dt-buttons .dt-button span {
        display: none;
    } */
    .dataTables_paginate {
        display: flex;
        padding-left: 0;
        list-style: none;
        border-radius: 4px;
        margin: 2px 0;
        justify-content: flex-end;
    }
    .dataTables_paginate .paginate_button.disabled,
    .dataTables_paginate .paginate_button.disabled {
        color: #dbdfea;
        pointer-events: none;
        background-color: #fff;
        border-color: #e5e9f2;
    }
    .dataTables_paginate .paginate_button.first,
    .dataTables_paginate .paginate_button.previous,
    .dataTables_paginate .paginate_button.next,
    .dataTables_paginate .paginate_button.last {
        margin-left: 0;
        border-top-left-radius: 4px;
        border-bottom-left-radius: 4px;
    }
    .dataTables_paginate .paginate_button {
        font-size: 0.8125rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: calc(1rem + 1.125rem + 2px);
        position: relative;
        padding: 0.5625rem 0.625rem;
        line-height: 1rem;
        border: 1px solid #e5e9f2;
        cursor: pointer;
    }
    .dataTables_paginate .paginate_button.current {
        z-index: 3;
        color: #fff;
        background-color: #6576ff;
        border-color: #6576ff;
    }
</style>
