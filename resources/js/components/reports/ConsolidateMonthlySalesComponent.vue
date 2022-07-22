<template>
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">Consolidate Monthly Sales Report</h3>
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
                                                <th width="11%">Start Date</th>
                                                <th width="11%">End Date</th>
                                                <th width="12%">Agent</th>
                                                <th width="15%">Customer</th>
                                                <th width="15%">Supplier</th>
                                                <th width="10%">Category</th>
                                                <th width="10%">City</th>
                                                <th width="12%">Action</th>
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
                                                        placeholder="Select One" label="name" track-by="id" id="">
                                                    </multiselect>
                                                </td>
                                                <td>
                                                    <multiselect v-model="supplier" :options="supplier_options"
                                                        placeholder="Select One" label="name" track-by="id" id="">
                                                    </multiselect>
                                                </td>
                                                <td>
                                                    <multiselect v-model="category" :options="category_options"
                                                        label="category_name" track-by="id" id="category"></multiselect>
                                                </td>
                                                <td class="text-center">
                                                    <multiselect v-model="city" :options="city_options" label="name"
                                                        track-by="id" id="city"></multiselect>
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
                                            <tr>
                                                <th colspan="4" class="text-center">{{ customer ? customer.name : 'All Parties' }}</th>
                                            </tr>
                                            <tr>
                                                <th colspan="4" class="text-center">{{ agent ? agent.name : 'All Agents' }}</th>
                                            </tr>
                                            <tr>
                                                <th>Month</th>
                                                <th class="text-right">Gross Sales (Amount)</th>
                                                <th class="text-right">Net Sales (Amount)</th>
                                                <th class="text-right">Gross Pending (Amount)</th>
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
    import Multiselect from 'vue-multiselect';

    export default {
        name: 'ConsolidateMonthlySales',
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
                category_options: [],
                city_options: [],
                agent: { id: 0, name: 'All Agents' },
                start_date: '',
                end_date: '',
                customer: '',
                supplier: '',
                city: '',
                category: '',
                max_date: '2022-01-01',
                export_sheet: 0,
                export_pdf: 0,
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
            axios.get('/reports/list-cities')
            .then(response => {
                this.city_options = response.data;
            });
            axios.get('/account/sale-bill/list-sale-bill-agents')
            .then(response => {
                this.agent_options.push({id: 0, name: 'All Agents'});
                response.data.forEach((k, i) => {
                    this.agent_options.push(k);
                });
            });
            axios.get('/databank/product-category/list-category')
            .then(response => {
                response.data.forEach((k, i) => {
                    if (k.product_default_category_id == 1) {
                        k.category_name = k.category_name + ' (Product)';
                    } else {
                        k.category_name = k.category_name + ' (Fabric)';
                    }
                    this.category_options.push(k);
                });
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
                this.start_date = this.end_date = this.customer = this.supplier = this.category = this.city = '';
                this.agent = { id: 0, name: 'All Agents' };
            },
            getData() {
                axios.post('/reports/list-consolidate-monthly-sales-data', {
                    start_date: this.start_date,
                    end_date: this.end_date,
                    customer: this.customer,
                    supplier: this.supplier,
                    agent: this.agent,
                    category: this.category,
                    city: this.city,
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
                        const toINR = new Intl.NumberFormat('en-IN', { });
                        var html = '';
                        var total_payment = 0, total_received = 0, total_pending = 0;
                        response.data.forEach((k, i) => {
                            total_payment += parseFloat(k.total_payment);
                            total_received += parseFloat(k.total_received);
                            total_pending += parseFloat(k.total_pending);
                            html += `<tr>
                                <td class=""> <a href="#" class="viewMonthlySalesCompanyReport"> ${k.month_year} </a></td>
                                <td class="text-right"> ${toINR.format(k.total_payment)} </td>
                                <td class="text-right"> ${toINR.format(k.total_received)} </td>
                                <td class="text-right"> ${toINR.format(k.total_pending)} </td>
                            </tr>`;
                        });
                        html += `<tr>
                                <th class=""> <a href="#" class="viewMonthlySalesCompanyReport total"> Total </a></th>
                                <th class="text-right"> ${toINR.format(total_payment)} </th>
                                <th class="text-right"> ${toINR.format(total_received)} </th>
                                <th class="text-right"> ${toINR.format(total_pending)} </th>
                            </tr>`;
                        $('#salesRegister tbody').html(html);
                    } else {
                        $('#salesRegister tbody').html('<tr><td colspan="4" class="text-center">No Records Found</td></tr>');
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

            $(document).on('click', '.viewMonthlySalesCompanyReport', function(e) {
                var param = '';
                if ($(this).hasClass('total')) {
                    param = '?total=total';
                }
                window.open('consolidate-monthly-sales-report/monthly-company/' + self.start_date + '/' + self.end_date + '/' + (self.agent ? self.agent.id : 0) + '/' + (self.customer ? self.customer.id : 0) + '/' + (self.supplier ? self.supplier.id : 0) + param, "", "width=1200,height=800,scrollbars=yes");
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

