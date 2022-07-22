<template>
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">Sales Register Report</h3>
                                <div class="nk-block-des text-soft"> </div>
                            </div><!-- .nk-block-head-content -->
                            <div class="nk-block-head-content">
                                <div class="toggle-wrap nk-block-tools-toggle">
                                    <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1"
                                        data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                    <div class="toggle-expand-content" data-content="pageMenu">
                                        <button class="btn btn-primary mr-2" @click="exportSheet()">Export
                                            Sheet</button>
                                        <button class="btn btn-primary" @click="exportPDF()">Export PDF</button>
                                        <ul class="nk-block-tools g-3">
                                            <li class="nk-block-tools-opt">
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
                                <div class="mb-5">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th width="15%">Start Date</th>
                                                <th width="15%">End Date</th>
                                                <th width="20%">Customer</th>
                                                <th width="20%">Supplier</th>
                                                <th width="10%">Status</th>
                                                <th width="5%">Hide</th>
                                                <th width="15%">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <input type="date" v-model="start_date" id="start_date"
                                                        class="form-control" autocomplete="off"
                                                        onfocus="this.showPicker();" :max="max_date">
                                                </td>
                                                <td>
                                                    <input type="date" v-model="end_date" id="end_date"
                                                        class="form-control" autocomplete="off"
                                                        onfocus="this.showPicker();" :max="max_date">
                                                </td>
                                                <td>
                                                    <multiselect v-model="customer" :options="customer_options"
                                                        placeholder="Select One" label="name" track-by="id" id="">
                                                    </multiselect>
                                                </td>
                                                <td>
                                                    <multiselect v-model="supplier" :options="supplier_options"
                                                        placeholder="Select One" label="name" track-by="id" id="">
                                                    </multiselect>
                                                </td>
                                                <td>
                                                    <multiselect v-model="payment_status"
                                                        :options="payment_status_options" label="name" track-by="id"
                                                        id="payment_status"></multiselect>
                                                </td>
                                                <td class="text-center" style="vertical-align: middle;">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input"
                                                            v-model="show_detail" id="show_detail" value="1"
                                                            autocomplete="off"> <!-- onchange="hide_detail()" -->
                                                        <label class="custom-control-label" for="show_detail"></label>
                                                    </div>
                                                </td>
                                                <td class="">
                                                    <button class="btn btn-primary btn-round btn-sm mr-1"
                                                        @click="getData()">Go</button>
                                                    <button class="btn btn-light btn-round btn-sm"
                                                        @click="clearData()">Clear All</button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="table-responsive" id="salesRegisterDT-div">
                                    <table id="salesRegisterDT" class="table table-hover table-bordered-">
                                        <thead>
                                            <tr class="">
                                                <th width="8%">Date</th>
                                                <th>Sr</th>
                                                <th>Party</th>
                                                <th class="text-right">Pieces</th>
                                                <th class="text-right">Meters</th>
                                                <th class="text-right">Net Amt</th>
                                                <th class="text-right">Rec. Amt</th>
                                                <th class="text-right">GST</th>
                                                <th>Agent</th>
                                                <th>Invoice</th>
                                                <th class="text-right">Gross Amt</th>
                                                <th>Transport</th>
                                                <th>City</th>
                                                <th>L.R.No.</th>
                                                <th>Purchase Party</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                                <div class="table-responsive" id="salesRegister-div">
                                    <table id="salesRegister" class="table table-hover table-bordered-">
                                        <thead>
                                            <tr class="">
                                                <th>No</th>
                                                <th>Party</th>
                                                <th class="text-right">Net Amt</th>
                                                <th class="text-right">Rec. Amt</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
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

    import $ from 'jquery';
    import 'datatables.net-responsive-bs4/js/responsive.bootstrap4';
    import "datatables.net-buttons-bs5/js/buttons.bootstrap5";
    import Multiselect from 'vue-multiselect';

    export default {
        name: 'salesRegister',
        props: {
        },
        components: {
            Multiselect,
            ViewCompanyDetails
        },
        data() {
            return {
                customer_options: [],
                supplier_options: [],
                payment_status_options: [{id: 0, name: 'All'}, {id: 1, name: 'Pending'}, {id: 2, name: 'Complete'}],
                start_date: '',
                end_date: '',
                customer: '',
                supplier: '',
                payment_status: { id: 0, name: 'All' },
                show_detail: 0,
                max_date: '2022-01-01',
                detailed_table: true,
                export_sheet: 0,
                export_pdf: 0,
                dt_table: null,
            }
        },
        created() {
            const date = new Date();
            const m = String(date.getMonth() + 1).padStart(2, '0');
            const d = String(date.getDate()).padStart(2, '0');
            const y = String(date.getFullYear());
            this.max_date = this.end_date = [y, m, d].join('-');
            this.start_date = '2018-01-01';

            axios.get('/account/sale-bill/list-customers-and-suppliers')
            .then(response => {
                this.customer_options = response.data[0];
                this.supplier_options = response.data[1];
            });
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
            clearData() {
                this.start_date = this.end_date = this.customer = this.supplier = '';
                this.payment_status = { id: 0, name: 'All' };
                this.show_detail = 0;
                // this.detailed_table = true;
                $('#salesRegisterDT-div').show();
                $('#salesRegister-div').hide();
                this.dt_table.ajax.reload();
            },
            init_dt_table () {
                var self = this;
                this.dt_table = $('#salesRegisterDT').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    ordering: false,
                    ajax: {
                        url: "./list-sales-register-data",
                        data: function (data) {
                            data.start_date = self.start_date;
                            data.end_date = self.end_date;
                            data.customer = self.customer;
                            data.supplier = self.supplier;
                            data.payment_status = self.payment_status;
                            data.show_detail = self.show_detail;
                            data.export_sheet = self.export_sheet;
                            data.export_pdf = self.export_pdf;
                        },
                        complete: function (data) { }
                    },
                    pagingType: 'full_numbers',
                    dom: 'lrtip',
                    // order: [[ 0, "desc" ]],
                    columns: [
                        { data: 'select_date' },
                        { data: 'sale_bill_id' },
                        { data: 'customer_name' },
                        { data: 'tot_pieces', class: 'text-right' },
                        { data: 'tot_meters', render: $.fn.dataTable.render.number(',', '.', 2, ''), class: 'text-right' },
                        { data: 'total', render: $.fn.dataTable.render.number(',', '.', 0, ''), class: 'text-right' },
                        { data: 'received_payment', render: $.fn.dataTable.render.number(',', '.', 0, ''), class: 'text-right' },
                        { data: 'total_gst', render: $.fn.dataTable.render.number(',', '.', 0, ''), class: 'text-right' },
                        { data: 'agent_name' },
                        { data: 'supplier_invoice_no' },
                        { data: 'gross_amount', orderable: false, render: $.fn.dataTable.render.number(',', '.', 0, ''), class: 'text-right' },
                        { data: 'transport_name' },
                        { data: 'city_name' },
                        { data: 'lr_mr_no' },
                        { data: 'supplier_name' },
                    ],
                    search: { return: false },
                    // createdRow: function( row, data, dataIndex ) { }
                })
                // .on( 'init.dt', function () { } );
            },
            getData() {
                if (this.start_date == '' || this.end_date == '') {
                    alert('Please Select Both Start Date & End Date');
                    return false;
                }
                if (this.export_sheet == 1 || this.export_pdf == 1 || this.show_detail == 1) {
                    axios.get('/reports/list-sales-register-data', {
                        params: {
                            start_date: this.start_date,
                            end_date: this.end_date,
                            customer: this.customer,
                            supplier: this.supplier,
                            payment_status: this.payment_status,
                            show_detail: this.show_detail == true ? 1 : 0,
                            export_sheet: this.export_sheet,
                            export_pdf: this.export_pdf
                        }
                    })
                    .then(response => {
                        if (this.show_detail == 1) {
                            // this.detailed_table = false;
                            $('#salesRegisterDT-div').hide();
                            $('#salesRegister-div').show();
                            if (response.data.length > 0) {
                                const toINR = new Intl.NumberFormat('en-IN', {});
                                var net_total = 0, received_total = 0, html = '';
                                response.data.forEach((k, i) => {
                                    net_total += parseFloat(k.total);
                                    received_total += parseFloat(k.received_payment);
                                    html += `<tr>
                                        <td class=""> ${i + 1} </td>
                                        <td class=""> <a href="#" class="view-details" data-id="${k.company_id}"> ${k.company_name} </a> </td>
                                        <td class="text-right"> ${toINR.format(k.total)} </td>
                                        <td class="text-right"> ${toINR.format(k.received_payment)} </td>
                                    </tr>`;
                                });
                                html += `<tr>
                                        <th class=""> Total</th>
                                        <th class=""> </th>
                                        <th class="text-right"> ${toINR.format(net_total)} </th>
                                        <th class="text-right"> ${toINR.format(received_total)} </th>
                                    </tr>`;
                                $('#salesRegister tbody').html(html);
                            } else {
                                $('#salesRegister tbody').html('<tr><td colspan="4" class="text-center">No Records Found</td></tr>');
                            }
                        } else {
                            this.export_sheet = this.export_pdf = 0;
                            window.open(response.data.url, '_blank');
                        }
                    });
                } else {
                    // this.detailed_table = true;
                    $('#salesRegisterDT-div').show();
                    $('#salesRegister-div').hide();
                    this.dt_table.ajax.reload();
                }
            },
            exportSheet() {
                this.export_sheet = 1;
                this.getData();
            },
            exportPDF() {
                this.export_pdf = 1;
                this.getData();
            },
        },
        mounted() {
            const self = this;
            /* const toINR = new Intl.NumberFormat('en-IN', {
                style: 'currency',
                currency: 'INR',
                // minimumFractionDigits: 2
            }); */
            this.init_dt_table();

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
    .h-600 {
        height: 600px;
    }
</style>
