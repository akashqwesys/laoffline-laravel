<template>
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">Payment Register Report</h3>
                                <div class="nk-block-des text-soft"> </div>
                            </div><!-- .nk-block-head-content -->
                            <div class="nk-block-head-content">
                                <div class="toggle-wrap nk-block-tools-toggle">
                                    <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1"
                                        data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                    <div class="toggle-expand-content" data-content="pageMenu">
                                        <button class="btn btn-primary mr-2" @click="exportSheet()">Export Sheet</button>
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
                                                <th width="15%">Sorting</th>
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
                                                    <multiselect v-model="sorting"
                                                        :options="sorting_options" label="name" track-by="id"
                                                        id="sorting"></multiselect>
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
                                <div class="table-responsive">
                                    <table id="salesRegister" class="table table-hover table-bordered-">
                                        <thead>
                                            <tr v-if="detailed_table == true">
                                                <th>Id</th>
                                                <th>Supplier</th>
                                                <th>Customer</th>
                                                <th class="text-right">Date</th>
                                                <th class="text-right">Mode</th>
                                                <th class="text-right">Dep.Bank</th>
                                                <th class="text-right">Chq.Date</th>
                                                <th class="text-right">Chq/DD No</th>
                                                <th>Chq/DD Bank</th>
                                                <th>Rec.Amt</th>
                                                <th class="text-right">Tot.Amt</th>
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
    import 'pdfmake/build/pdfmake';
    import "datatables.net-buttons/js/buttons.html5";
    import "datatables.net-buttons/js/buttons.print";
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
                sorting_options: [{id: 1, name: 'Supplier A -> Z'}, {id: 2, name: 'Supplier Z -> A'}, {id: 3, name: 'Customer A -> Z'}, {id: 4, name: 'Customer Z -> A'}, {id: 5, name: 'Date L -> H'}, {id: 6, name: 'Date H -> L'},],
                start_date: '',
                end_date: '',
                customer: '',
                supplier: '',
                sorting: {id: 5, name: 'Date L -> H'},
                max_date: '2022-01-01',
                detailed_table: true,
                export_sheet: 0,
                export_pdf: 0,
            }
        },
        created() {
            const date = new Date();
            const m = String(date.getMonth() + 1).padStart(2, '0');
            const d = String(date.getDate()).padStart(2, '0');
            const y = String(date.getFullYear());
            this.max_date = [y, m, d].join('-');

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
            },
            getData() {
                
                axios.post('/reports/list-payment-register-data', {
                    start_date: this.start_date,
                    end_date: this.end_date,
                    customer: this.customer,
                    supplier: this.supplier,
                    sorting: this.payment_status,
                    export_sheet: this.export_sheet,
                    export_pdf: this.export_pdf
                })
                .then(response => {
                    if (this.export_sheet == 1 || this.export_pdf == 1) {
                        this.export_sheet = this.export_pdf = 0;
                        window.open(response.data.url, '_blank');
                        return;
                    }
                    if (response.data.length > 0) {
                        const toINR = new Intl.NumberFormat('en-IN', {
                            // style: 'currency',
                            // currency: 'INR',
                            // minimumFractionDigits: 0
                        });
                        var html = '';
                        var total_pieces = 0, total_meters = 0, net_total = 0, received_total = 0, gross_total = 0;
                        var gross_amount = 0;
                        response.data.forEach((k, i) => {
                                total_pieces += parseFloat(k.tot_pieces);
                                total_meters += parseFloat(k.tot_meters);
                                net_total += parseFloat(k.total);
                                received_total += parseFloat(k.received_payment);
                                if (k.sign_change == '+') {
                                    gross_amount = (parseFloat(k.total) - parseFloat(k.change_in_amount));
                                } else {
                                    gross_amount = (parseFloat(k.total) + parseFloat(k.change_in_amount));
                                }
                                gross_total += gross_amount;
                                html += `<tr>
                                    <td class=""> ${k.select_date} </td>
                                    <td class=""> <a href="/account/sale-bill/view-sale-bill/${k.sale_bill_id}/${k.financial_year_id}" class="" data-toggle="tooltip" data-placement="top" title="View"> ${k.sale_bill_id} </a></td>
                                    <td class=""> <a href="#" class="view-details" data-id="${k.company_id}"> ${k.customer_name} </a> </td>
                                    <td class="text-right"> ${k.tot_pieces} </td>
                                    <td class="text-right"> ${k.tot_meters} </td>
                                    <td class="text-right"> ${toINR.format(k.total)} </td>
                                    <td class="text-right"> ${toINR.format(k.received_payment)} </td>
                                    <td class="text-right"> ${toINR.format(k.total_gst)} </td>
                                    <td class=""> ${k.agent_name} </td>
                                    <td class=""> ${k.supplier_invoice_no} </td>
                                    <td class="text-right"> ${toINR.format(gross_amount)} </td>
                                    <td class=""> ${k.transport_name} </td>
                                    <td class=""> ${k.city_name} </td>
                                    <td class=""> ${k.lr_mr_no} </td>
                                    <td class=""> <a href="#" class="view-details" data-id="${k.company_id}"> ${k.supplier_name} </a> </td>
                                </tr>`;
                            });
                            html += `<tr>
                                    <th class=""> Total</th>
                                    <th class=""> </th>
                                    <th class=""> </th>
                                    <th class="text-right"> ${total_pieces} </th>
                                    <th class="text-right"> ${total_meters} </th>
                                    <th class="text-right"> ${toINR.format(net_total)} </th>
                                    <th class="text-right"> ${toINR.format(received_total)} </th>
                                    <th class=""> </th>
                                    <th class=""> </th>
                                    <th class=""> </th>
                                    <th class="text-right"> ${toINR.format(gross_total)} </th>
                                    <th class=""> </th>
                                    <th class=""> </th>
                                    <th class=""> </th>
                                    <th class=""> </th>
                                </tr>`;
                        
                        $('#salesRegister tbody').html(html);
                    } else {
                        $('#salesRegister tbody').html('<tr><td colspan="15" class="text-center">No Records Found</td></tr>');
                    }
                });
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
            var dt_table = null;
            /* const toINR = new Intl.NumberFormat('en-IN', {
                style: 'currency',
                currency: 'INR',
                // minimumFractionDigits: 2
            }); */

            $(document).on('click', '.view-details', function(e) {
                self.showModal($(this).attr('data-id'));
            });
            document.getElementById('viewCompany1').addEventListener('hidden.bs.modal', function (event) {
                $('.modal-backdrop').remove();
            });

        },
    };
</script>

