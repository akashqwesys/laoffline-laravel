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
                                        <h3 class="nk-block-title page-title">Commission List</h3>
                                    </div>
                                    <div class="col-md-8 text-right">
                                        <a v-bind:href="commissionivoicelink" class="dropdown-toggle btn btn-icon- btn-primary px-2">Invoice List</a>
                                        <a href="/account/commission/invoice/create-invoice" class="dropdown-toggle btn btn-icon- btn-success mx-2 px-2"><em class="icon ni ni-plus"></em> Add Invoice</a>
                                        <a v-bind:href="create_commission" class="dropdown-toggle btn btn-- btn-success mr-2"><em class="icon ni ni-plus"></em> Add Commission</a>
                                        <button @click="clearallfilter" class="btn btn-dark px-2">Clear</button>
				                    </div>
                                </div>

                            </div>
                            <div class="card-inner">
                                <div class="table-responsive">
                                <table id="commission" class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Sr No</th>
				                            <th>IUID</th>
				                            <th>Ref. ID</th>
                                            <th>Date Add</th>
                                            <th>Company</th>
				                            <th>Recive Comm. Amount</th>
				                            <th>Completed</th>
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
    import ViewCompanyDetails from '../../databank/companyComponents/modal/ViewCompanyDetailsModelComponent.vue';
    import 'jquery/dist/jquery.min.js';
    import 'datatables.net-bs5/js/dataTables.bootstrap5';
    import 'datatables.net-responsive-bs4/js/responsive.bootstrap4';
    import "datatables.net-buttons-bs5/js/buttons.bootstrap5";
    import "datatables.net-buttons/js/buttons.flash.js";
    import "datatables.net-buttons/js/buttons.html5.js";
    import "datatables.net-buttons/js/buttons.print.js";
    import $ from 'jquery';

    export default {
        name: 'commission',
        props: {
            excelAccess: Number,
        },
        components: {
            ViewCompanyDetails
        },
        data() {
            return {
                create_commission: '/commission/create-commission',
                commissionivoicelink: '/account/commission/invoice',
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
                window.location.href = './commission/edit-commission/'+id;
            },
            delete_data(id){
                axios.delete('./commission/delete/'+id)
                .then(response => {
                    location.reload();
                });
            },
            clearallfilter(event) {
                $("#commission_filter").find('input, select').val("");
                $('#commission').DataTable().clear().draw();
            },
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
                dt_table = $('#commission').DataTable({
                    processing: true,
                    serverSide: true,
                    lengthChange: true,
                    ajax: {
                        url: "/commission/list",
                        data: function (data) {
                            if ($('#commission_no').val() == '') {
                                data.columns[0].search.value = '';
                            } else {
                                data.columns[0].search.value = $('#commission_no').val();
                            }
                            if ($('#iuid').val() == '') {
                                data.columns[1].search.value = '';
                            } else {
                                data.columns[1].search.value = $('#iuid').val();
                            }
                            if ($('#ref_no').val() == '') {
                                data.columns[2].search.value = '';
                            } else {
                                data.columns[2].search.value = $('#ref_no').val();
                            }
                            if ($('#date_added').val() == '') {
                                data.columns[3].search.value = '';
                            } else {
                                data.columns[3].search.value = $('#date_added').val();
                            }
                            if ($('#company_name').val() == '') {
                                data.columns[4].search.value = '';
                            } else {
                                data.columns[4].search.value = $('#company_name').val();
                            }
                            if ($('#recivedcommamount').val() == '') {
                                data.columns[5].search.value = '';
                            } else {
                                data.columns[5].search.value = $('#recivedcommamount').val();
                            }
                        },
                        complete: function (data) { }
                    },
                    pagingType: 'full_numbers',
                    dom: "Blrtip",
                    order: [[3, "desc"]],
                    columns: [
                        { data: 'id' },
                        { data: 'iuid' },
                        { data: 'reference_id' },
                        { data: 'created_at' },
                        { data: 'company', orderable: false },
                        { data: 'commission_payment_amount', render: $.fn.dataTable.render.number(',', '.', 0, '₹')},
                        { data: 'completed', orderable: false },
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
                }).
                on( 'init.dt', function () {
                    $('<div class="dataTables_filter mt-2" id="commission_filter"><input type="search" id="commission_no" class="form-control form-control-sm w-10" placeholder="Commission ID"><input type="search" id="iuid" class="form-control form-control-sm w-10" placeholder="iuid"><input type="search" id="ref_no" class="form-control form-control-sm w-15" placeholder="Reference No"><input type="date" id="date_added" class="form-control form-control-sm w-15" placeholder="Date Added" onclick="this.showPicker();"><div class="input-group"><input type="text" id="company_name" class="form-control form-control-sm w-20" placeholder="Company"></div><input type="search" id="recivedcommamount" class="form-control form-control-sm w-15" placeholder="Amount"></div>').insertAfter('.dataTables_length');
                    axios.get('/common/list-all-companies')
                        .then(response => {
                            new Autocomplete(document.getElementById('company_name'), {
                                threshold: 2,
                                data: response.data,
                                maximumItems: 5,
                                label: 'name',
                                value: 'id',
                                onSelectItem: ({ label, value }) => { }
                            });
                        });
                    setTimeout(() => {
                        $('#company_name').siblings('div.dropdown-menu').on('click', '.dropdown-item', function (e) {
                            dt_table.clear().draw();
                        });
                    }, 1000);
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
            $(document).on('keyup', '#commission_filter input', function(e) {
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
            /* window.addEventListener('load', function () {
                axios.get('/common/list-all-companies')
                .then(response => {
                    new Autocomplete(document.getElementById('company_name'), {
                        threshold: 2,
                        data: response.data,
                        maximumItems: 5,
                        label: 'name',
                        value: 'id',
                        onSelectItem: ({ label, value }) => { }
                    });
                });
                setTimeout(() => {
                    $('#company_name').siblings('div.dropdown-menu').on('click', '.dropdown-item', function (e) {
                        dt_table.clear().draw();
                    });
                }, 1000);
            }, false); */

            $(document).on('click', '.view-details', function(e) {
                self.showModal($(this).attr('data-id'));
            });
            document.getElementById('viewCompany1').addEventListener('hidden.bs.modal', function (event) {
                $('.modal-backdrop').remove();
            });

            $(document).on('click', '.delete-commission', function(e) {
                if (confirm('Are you sure you want to delete?')) {
                    location.href = '/commission/delete/' + $(this).attr('data-id') + '/' + $(this).attr('data-fid');
                }
                return;
            });
        },
    };
</script>
<style scoped>
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
