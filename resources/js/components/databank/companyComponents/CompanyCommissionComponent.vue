<template>
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block">
                        <div class="card card-bordered card-stretch">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <h3 class="nk-block-title page-title">Commission Details ({{ companyName }})
                                        </h3>
                                    </div>
                                    <div class="col-md-8 text-right"> </div>
                                </div>
                            </div>
                            <div class="card-inner">
                                <div class="row mb-5">
                                    <div class="col-md-4">
                                        <div class="form-group w-100" v-if="companyType == 3">
                                            <multiselect v-model="customer" :options="company_options" placeholder="Select Customer" label="name" track-by="id" ></multiselect>
                                        </div>
                                        <div class="form-group w-100" v-else>
                                            <multiselect v-model="supplier" :options="company_options" placeholder="Select Supplier" label="name" track-by="id" ></multiselect>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="text" v-model="commission" class="form-control"
                                            placeholder="Commission">
                                    </div>
                                    <div class="col-md-4">
                                        <button class="btn btn-primary" @click="addCommission"> ADD</button>
                                    </div>
                                </div>
                                <hr>
                                <div class="">
                                    <div class="row" id="commission-data">

                                    </div>
                                </div>
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
    import Multiselect from 'vue-multiselect';

    export default {
        name: 'companyCommission',
        props: {
            companyType: Number,
            company: Number,
            companyName: String,
        },
        components: {
            Multiselect
        },
        data() {
            return {
                customer: '',
                supplier: '',
                commission: '',
                company_options: [],
            }
        },
        created () {
        },
        methods: {
            fetchData () {
                axios.post('/databank/companies/fetch-company-commission', {
                    company: this.company,
                    companyType: this.companyType
                })
                    .then(response => {
                        var html = '';
                        response.data.forEach((k, i) => {
                            html += `<div class="col-md-6 row form-group" >
                                <div class="col-md-2 m-auto">${++i}</div>
                                <div class="col-md-8 m-auto">${k.company_name}</div>
                                <div class="col-md-2 ">
                                    <input type="text" class="form-control update-data" value="${k.commission_percentage}" data-id="${k.id}" data-company="${k.company_id}" data-name="${k.company_name}">
                                </div>
                            </div>`;
                        });
                        $('#commission-data').html(html);
                    });
            },
            addCommission (event) {
                if (!((this.customer || this.supplier) && this.commission)) {
                    alert('Please enter company and commission...!');
                    return false;
                }
                if (this.companyType == 3) {
                    var customer = this.customer.id;
                    var supplier = this.company;
                } else {
                    var supplier = this.supplier.id;
                    var customer = this.company;
                }
                axios.post('/databank/companies/add-commission', {
                    companyType: this.companyType,
                    customer: customer,
                    supplier: supplier,
                    commission: this.commission
                })
                .then(response => {
                    this.customer = this.supplier = this.commission = '';
                    this.fetchData();
                });
            },
        },
        mounted() {
            const self = this;
            window.addEventListener('load', function () {
                axios.get('/common/list-customers-and-suppliers')
                    .then(response => {
                        if (self.companyType == 3) {
                            self.company_options = response.data[0];
                        } else {
                            self.company_options = response.data[1];
                        }
                    });
            }, false);

            this.fetchData();

            $(document).on('change', '.update-data', function () {
                axios.post('/databank/companies/update-commission', {
                    id: $(this).attr('data-id'),
                    name_1: self.companyName,
                    name_2: $(this).attr('data-name'),
                    commission: $(this).val(),
                    companyType: self.companyType,
                    company: self.company
                })
                .then(response => {
                    if (response.data == 'deleted') {
                        self.fetchData();
                    }
                });
            });
        },
    };
</script>

