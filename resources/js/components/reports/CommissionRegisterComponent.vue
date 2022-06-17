<template>
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">Commission Register Report</h3>
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
                                                <th width="8%">Mode</th>
                                                <th width="15%">Account</th>
                                                <th width="15%">Company</th>
                                                <th width="12%">Sorting</th>
                                                <th width="5%">Hide</th>
                                                <th width="15%">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td width="15%">
                                                    <input type="date" v-model="start_date" id="start_date"
                                                        class="form-control" autocomplete="off"
                                                        onfocus="this.showPicker();" :max="max_date">
                                                </td>
                                                <td width="15%">
                                                    <input type="date" v-model="end_date" id="end_date"
                                                        class="form-control" autocomplete="off"
                                                        onfocus="this.showPicker();" :max="max_date">
                                                </td>
                                                <td width="8%">
                                                    <multiselect v-model="mode" :options="mode_option"
                                                        placeholder="Select One" label="name" track-by="id" id="">
                                                    </multiselect>
                                                </td>
                                                <td width="15%">
                                                    <multiselect v-model="agent" :options="agent_options"
                                                        placeholder="Select One" label="name" track-by="id" id="">
                                                    </multiselect>
                                                </td>
                                                <td width="15%">
                                                    <multiselect v-model="company" :options="company_options"
                                                        placeholder="Select One" label="company_name" track-by="id" id="">
                                                    </multiselect>
                                                </td>
                                                <td width="12%">
                                                    <multiselect v-model="sorting"
                                                        :options="sorting_options" label="name" track-by="id"
                                                        id="sorting"></multiselect>
                                                </td>
                                                <td class="text-center" width="5%" style="vertical-align: middle;">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input"
                                                            v-model="show_detail" id="show_detail" value="1"
                                                            autocomplete="off"> <!-- onchange="hide_detail()" -->
                                                        <label class="custom-control-label" for="show_detail"></label>
                                                    </div>
                                                </td>
                                                <td width="15%">
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
                                                <th>Company</th>
                                                <th>Date</th>
                                                <th>Account</th>
                                                <th>Mode</th>
                                                <th>Dep.Bank</th>
                                                <th>Chq.Date</th>
                                                <th>Chq/DD No</th>
                                                <th>Chq/DD Bank</th>
                                                <th>Amount</th>
                                                <th>TDS</th>
                                                <th>S.T</th>
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
                company_options: [],
                agent_options: [],
                mode_option: [{id: 0, name: 'All'}, {id: 1, name: 'cash'}, {id: 2, name: 'cheque'},],
                mode: {id: 0, name: 'All'},
                sorting_options: [{id: 1, name: 'Supplier A -> Z'}, {id: 2, name: 'Supplier Z -> A'}, {id: 3, name: 'Date L -> H'}, {id: 4, name: 'Date H -> L'},],
                start_date: '',
                end_date: '',
                company: '',
                agent: '',
                show_detail: 0,
                sorting: {id: 3, name: 'Date L -> H'},
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

            axios.get('/commission/list-company')
            .then(response => {
                this.company_options = response.data;
            });
            axios.get('/reports/list-agents')
            .then(response => {
                this.agent_options = response.data;
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
                this.sorting = {id: 5, name: 'Date L -> H'};
                this.getData();
            },
            getData() {
                axios.post('/reports/list-commission-register-data', {
                    start_date: this.start_date,
                    end_date: this.end_date,
                    mode: this.mode,
                    company: this.company,
                    agent: this.agent,
                    show_detail: this.show_detail,
                    sorting: this.sorting,
                    export_sheet: this.export_sheet,
                    export_pdf: this.export_pdf
                })
                .then(response => {
                    if (this.export_sheet == 1 || this.export_pdf == 1) {
                        // this.export_sheet = this.export_pdf = 0;
                        // window.open(response.data.url, '_blank');
                        // return;
                    }
                    if (response.data.length > 0) {
                        const toINR = new Intl.NumberFormat('en-IN', {
                            // style: 'currency',
                            // currency: 'INR',
                            // minimumFractionDigits: 0
                        });
                        var tcommission_payment_amount = 0, ttds = 0, tservice_tax= 0;
                        response.data.forEach((k, i) => {
                                let commission_payment_amount = k.commission_payment_amount;
                                if (!commission_payment_amount) {
                                    commission_payment_amount = 0;
                                }

                                let tds = k.tds;
                                if (!tds) {
                                    tds = 0;
                                }

                                let service_tax = k.service_tax;
                                if (!service_tax) {
                                    service_tax = 0;
                                }
                                tcommission_payment_amount += parseFloat(commission_payment_amount);
                                ttds += parseFloat(tds);
                                tservice_tax += parseFloat(service_tax);
                        });
                        var html = '';
                        html += `<tr>
                                    <td align="left"></td>
                                    <td align="left"></td>
                                    <td align="left"></td>
                                    <td align="left"></td>
                                    <td align="left"></td>
                                    <td align="left"></td>
                                    <td align="left"></td>
                                    <td align="left"></td>
                                    <td align="left">Grand Total</td>
                                    <td align="left">${tcommission_payment_amount}</td>
                                    <td align="right">${ttds}</td>
                                    <td align="right">${tservice_tax}</td>
                                </tr>`;
                        
                        var cheque_date = '',company = '', bank_name= '', cheque_dd_no = '', cheque_bank = '';
                        response.data.forEach((k, i) => {
                                let commission_payment_amount = k.commission_payment_amount;
                                if (!commission_payment_amount) {
                                    commission_payment_amount = 0;
                                }

                                let tds = k.tds;
                                if (!tds) {
                                    tds = 0;
                                }

                                let service_tax = k.service_tax;
                                if (!service_tax) {
                                    service_tax = 0;
                                }
                                if (k.commission_reciept_mode != 'cheque') {
                                    cheque_date = '-';
                                    cheque_dd_no = '-';
                                    cheque_bank = '-';
                                    bank_name = '-'
                                } else {
                                    cheque_date = k.commission_cheque_date;
                                    cheque_dd_no = k.commission_cheque_dd_no;
                                    cheque_bank = k.commission_cheque_dd_bank;
                                    bank_name = k.bank_name;
                                }
                                if (k.customer_id = 0) {
                                    company = k.supplier_name;
                                } else if (k.supplier_id == 0) {
                                    company = k.customer_name;
                                }

                                html += `<tr>
                                    <td align="left">${k.commission_id}</td>
                                    <td align="left">${company}</td>
                                    <td align="left">${k.commission_date}</td>
                                    <td align="left">${k.agent}</td>
                                    <td align="left">${k.commission_reciept_mode}</td>
                                    <td align="left">${bank_name}</td>
                                    <td align="left">${cheque_date}</td>
                                    <td align="left">${cheque_dd_no}</td>
                                    <td align="left">${cheque_bank}</td>
                                    <td align="left">${commission_payment_amount}</td>
                                    <td align="right">${tds}</td>
                                    <td align="right">${service_tax}</td>
                                    
                                </tr>`;
                            });
                            
                        
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

