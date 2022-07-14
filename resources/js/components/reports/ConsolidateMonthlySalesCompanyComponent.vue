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
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    <div class="text-center mb-2"> <h5>Salebill of {{ current_month }}</h5> </div>
                    <div class="nk-block">
                        <div class="card card-bordered card-stretch">
                            <div class="card-inner">
                                <div class="mb-5">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th width="25%">Company Type</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-center">
                                                    <select v-model="company_type" class="form-control">
                                                        <option value="" selected disabled>Select Company Type</option>
                                                        <option value="2">Customer</option>
                                                        <option value="3">Supplier</option>
                                                    </select>
                                                </td>
                                                <td class="">
                                                    <button class="btn btn-primary btn-round btn-sm mr-1" @click="getData()">Go</button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="row mb-3 ml-0 mr-0">
                                    <div class="col-md-5"> <b>Company Name</b> </div>
                                    <div class="col-md-4 text-right"> <b>Transaction Amount</b> </div>
                                    <div class="col-md-3 text-right"> <b>Percentage</b> </div>
                                </div>
                                <div id="fill-data" class=""></div>
                            </div><!-- .card -->
                        </div>
                    </div><!-- .nk-block -->
                </div>
            </div>
        </div>
    </div>
    <!-- <ViewCompanyDetails ref="company"></ViewCompanyDetails> -->
</template>

<script>
    // import ViewCompanyDetails from '../databank/companyComponents/modal/ViewCompanyDetailsModelComponent.vue';

    import $ from 'jquery';

    export default {
        name: 'ConsolidateMonthlySalesCompany',
        props: {
        },
        components: {
            // ViewCompanyDetails
        },
        data() {
            return {
                company_type: '',
                current_month: '',
            }
        },
        created() {
        },
        methods: {
            getData() {
                axios.post('/reports/list-consolidate-monthly-sales-company-data', {
                    company_type: this.company_type,
                    start_date: this.$route.params.start_date,
                    end_date: this.$route.params.end_date,
                    agent: this.$route.params.agent,
                    customer: this.$route.params.customer,
                    supplier: this.$route.params.supplier
                })
                .then(response => {
                    if (response.data.data.length > 0) {
                        const toINR = new Intl.NumberFormat('en-IN', { });
                        var html = '', sub_html = '';
                        response.data.data.forEach((k, i) => {
                            sub_html = '';
                            k.sub_companies.forEach((k2, i2) => {
                                sub_html += `<div class="col-md-5">${i2 + 1}. ${k2.company_name}</div>
                                            <div class="col-md-4 text-right">${toINR.format(k2.monthly_total)}</div>
                                            <div class="col-md-3 text-right">${(parseFloat(k2.monthly_total) * 100 / parseFloat(k.party_total)).toFixed(2)}%</div>`;
                            });
                            html += `<div id="accordion-${i}" class="accordion accordion-s3">
                                <div class="accordion-item">
                                    <a href="#" class="accordion-head collapsed" data-bs-toggle="collapse" data-bs-target="#accordion-item-2-${i}">
                                        <div class="title row">
                                            <div class="col-md-5">
                                                <span class="view-monthly-salebill" data-sql="${k.sql}">${k.display_name}</span>
                                            </div>
                                            <div class="col-md-4 text-right">${toINR.format(k.party_total)}</div>
                                            <div class="col-md-3 text-right">${k.percentage}</div>
                                        </div>
                                        <span class="accordion-icon"></span>
                                    </a>
                                    <div class="accordion-body collapse" id="accordion-item-2-${i}" data-bs-parent="#accordion-${i}">
                                        <div class="accordion-inner row"> ${sub_html} </div>
                                    </div>
                                </div>
                            </div>`;
                        });
                        html += `<div id="accordion-" class="accordion accordion-s3">
                                <div class="accordion-item">
                                    <a href="#" class="accordion-head collapsed" data-bs-toggle="collapse" data-bs-target="#accordion-item-2-">
                                        <div class="title row">
                                            <div class="col-md-5"><b>Total</b></div>
                                            <div class="col-md-4 text-right">${toINR.format(response.data.total)}</div>
                                            <div class="col-md-3 text-right"><b>100%</b></div>
                                        </div>
                                    </a>
                                </div>
                            </div>`;
                        $('#fill-data').html(html);
                    } else {
                        $('#fill-data').html('<div class="text-center"><b>No Records Found</b></div>');
                    }
                });
            },
        },
        mounted() {
            const self = this;
            setTimeout(() => {
                const date = new Date(self.$route.params.start_date);
                const months = [ "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December" ];
                self.current_month = months[date.getMonth()];
            }, 500);

            $(document).on('click', '.view-monthly-salebill', function(e) {
                var data = $(this).attr('data-sql').split('~');
                var company_id = data[0].split(',').join('-');
                var supplier_id = data[1].split(',').join('-');

                window.open('/reports/consolidate-monthly-sales-report/monthly-salebill/' + self.$route.params.start_date + '/' + self.$route.params.end_date + '/' + self.$route.params.agent + '/' + company_id + '/' + supplier_id, "", "width=1000,height=800,scrollbars=yes");
            });

        },
    };
</script>

