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
                                                <th>Start Date</th>
                                                <th>End Date</th>
                                                <th>Agent</th>
                                                <th>Customer</th>
                                                <th>Supplier</th>
                                                <th>City</th>
                                                <th>Day</th>
                                                <th>Sorting</th>
                                                <th>Group</th>
                                                <th>Hide</th>
                                                <th>Action</th>
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
                                                    <multiselect v-model="agent" :options="agent_options"
                                                        placeholder="Select One" label="name" track-by="id" id="">
                                                    </multiselect>
                                                </td>
                                                <td>
                                                    <multiselect v-model="customer" :options="customer_options"
                                                        placeholder="" label="name" track-by="id" id="">
                                                    </multiselect>
                                                </td>
                                                <td>
                                                    <multiselect v-model="supplier" :options="supplier_options"
                                                        placeholder="" label="name" track-by="id" id="">
                                                    </multiselect>
                                                </td>
                                                <td>
                                                    <multiselect v-model="city" :options="city_options"
                                                        placeholder="" label="name" track-by="id" id="">
                                                    </multiselect>
                                                </td>
                                                <td>
                                                    <multiselect v-model="day" :options="day_options"
                                                        placeholder="" label="name" track-by="id" id="">
                                                    </multiselect>
                                                </td>
                                                <td>
                                                    <multiselect v-model="sorting"
                                                        :options="sorting_options" label="name" track-by="id"
                                                        id="sorting"></multiselect>
                                                </td>
                                                <td class="text-center" width="5%" style="vertical-align: middle;">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input"
                                                            v-model="group" id="group" value="1"
                                                            autocomplete="off"> <!-- onchange="hide_detail()" -->
                                                        <label class="custom-control-label" for="group"></label>
                                                    </div>
                                                </td>
                                                <td class="text-center" width="5%" style="vertical-align: middle;">
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
                                <div class="table-responsive">
                                    <table id="salesRegister" class="table table-hover table-bordered-">
                                        <thead>
                                            <tr v-if="detailed_table == true">
                                                <th>Id</th>
                                                <th>Supplier</th>
                                                <th>Customer</th>
                                                <th>Date</th>
                                                <th>Mode</th>
                                                <th>Dep.Bank</th>
                                                <th>Chq.Date</th>
                                                <th>Chq/DD No</th>
                                                <th>Chq/DD Bank</th>
                                                <th>Rec.Amt</th>
                                                <th>Tot.Amt</th>
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
                agent_options: [],
                city_options: [],
                day_options: [{id:1, name: 'All'}, {id:2, name: 'above 30'}, {id:3, name: 'above 60'}, {id:4, name: 'above 90'}, {id:5, name: 'above 120'}, {id:1, name: 'above 150'}, {id:1, name: 'above 180'}],
                sorting_options: [{id: 1, name: 'Supplier A -> Z'}, {id: 2, name: 'Supplier Z -> A'}, {id: 3, name: 'Customer A -> Z'}, {id: 4, name: 'Customer Z -> A'}, {id: 5, name: 'Date L -> H'}, {id: 6, name: 'Date H -> L'}, {id:7, name: 'Bill Amt L -> H'}, {id:8, name: 'Bill Amt H -> L'} ],
                start_date: '',
                end_date: '',
                customer: '',
                supplier: '',
                city: '',
                agent: '',
                group: 0,
                day : {id:1, name: 'All'},
                sorting: {id: 5, name: 'Date L -> H'},
                max_date: '2022-01-01',
                show_detail: 0,
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
            axios.get('/reports/list-agents')
            .then(response => {
                this.agent_options = response.data;
            });
            axios.get('/reports/list-cities')
            .then(response => {
                this.city_options = response.data;
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
                this.start_date = this.end_date = this.customer = this.agent = this.supplier = '';
                this.sorting = {id: 5, name: 'Date L -> H'};
                this.day = {id: 1, name:'All'},
                this.getData();
            },
            getData() {
                axios.post('/reports/list-outstanding-payment-data', {
                    start_date: this.start_date,
                    end_date: this.end_date,
                    customer: this.customer,
                    supplier: this.supplier,
                    agent: this.agent,
                    day: this.day,
                    sorting: this.sorting,
                    group: this.group,
                    city: this.city,
                    show_detail: this.show_detail,
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
                        var receipt_amount = 0, total_amount = 0;
                        response.data.forEach((k, i) => {
                                receipt_amount += parseFloat(k.receipt_amount);
                                total_amount += parseFloat(k.total_amount);
                        });
                        var html = '';
                        var morethan = '';
                        if (this.day != '' && this.day != 0) {
                            morethan = "( More then "+ this.day.name +" Days)";
                        } else {
                            morethan = "";
                        }
                        var sup="";
                        if (this.customer != '') {
                            sup = "Customer: " + cus_disp_name + "<br>";
                        }

                        if (this.supplier != '') {
                            if(isset($sup_disp_name)) {
					            sup += "Supplier: "+ sup_disp_name + "<br>";
		    				} else {
					        	sup += "Supplier: " + report_supplier_name + "<br>";
	        				}
                        }

                        if(this.customer.company_name == '' && this.supplier.company_name == '') {
					        sup += "All Parties";
	        			}
                        sup += morethan
                        html += `<tr>
                                    <td align="left"></td>
                                    <td align="left"></td>
                                    <td align="left"></td>
                                    <td align="left"></td>
                                    <td align="left"></td>
                                    <td align="left"></td>
                                    <td align="left"></td>
                                    <td align="left"></td>
                                    <td align="left">Total</td>
                                    <td align="left">${receipt_amount}</td>
                                    <td align="right">${total_amount}</td>
                                </tr>`;
                        
                        var cheque_date = '', cheque_dd_no = '', cheque_bank = '';
                        response.data.forEach((k, i) => {
                                receipt_amount += parseFloat(k.receipt_amount);
                                total_amount += parseFloat(k.total_amount);
                                if (k.reciept_mode != 'cheque') {
                                    cheque_date = '-';
                                    cheque_dd_no = '-';
                                    cheque_bank = '-';
                                } else {
                                    cheque_date = k.cheque_date;
                                    cheque_dd_no = k.cheque_dd_no;
                                    cheque_bank = k.cheque_bank;
                                }
                                html += `<tr>
                                    <td align="left">${k.payment_id}</td>
                                    <td align="left">${k.supplier_name}</td>
                                    <td align="left">${k.customer_name}</td>
                                    <td align="left">${k.date}</td>
                                    <td align="left">${k.reciept_mode}</td>
                                    <td align="left">${k.bank_name}</td>
                                    <td align="left">${cheque_date}</td>
                                    <td align="left">${cheque_dd_no}</td>
                                    <td align="left">${cheque_bank}</td>
                                    <td align="left">${k.receipt_amount}</td>
                                    <td align="right">${k.total_amount}</td>
                                    
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

