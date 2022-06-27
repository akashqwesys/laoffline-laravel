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
                                        <h3 class="nk-block-title page-title">Commission Details ({{ companyName }})</h3>
                                    </div>
                                    <div class="col-md-8 text-right"> </div>
                                </div>
                            </div>
                            <div class="card-inner">
                                <div class="row mb-5">
                                    <div class="col-md-4">
                                        <div class="form-group w-100" v-if="companyType == 3">
                                            <input type="text" v-model="customer" class="form-control" placeholder="Customer" id="customer_name">
                                        </div>
                                        <div class="form-group w-100" v-else>
                                            <input type="text" v-model="supplier" class="form-control" placeholder="Supplier" id="supplier_name">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="text" v-model="commission" class="form-control" placeholder="Commission">
                                    </div>
                                    <div class="col-md-4">
                                        <button class="btn btn-primary" @click="addCommission"> ADD</button>
                                    </div>
                                </div>
                                <hr>
                                <table id="companies" class="table table-hover table-bordered">
                                    <tbody>
                                        <tr>
                                        </tr>
                                    </tbody>
                                </table>
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

    export default {
        name: 'companyCommission',
        props: {
            companyType: Number,
            company: Number,
            companyName: String,
        },
        components: {
        },
        data() {
            return {
                customer: '',
                supplier: '',
                commission: '',
            }
        },
        methods: {
            isVerified: function(id) {
                axios.post('/companies/update-commission', {
                    customer: this.customer,
                    supplier: this.supplier,
                    commission: this.commission,
                    flag: 1
                })
                .then(response => {
                    location.reload();
                });
            },
            addCommission () {
                axios.post('/companies/add-commission', {
                    customer: this.customer,
                    supplier: this.supplier,
                    commission: this.commission
                })
                .then(response => {
                    location.reload();
                });
            },
        },
        mounted() {
            const self = this;
            window.addEventListener('load', function () {
                axios.get('/common/list-customers-and-suppliers')
                    .then(response => {
                        if (self.companyType == 3) {
                            new Autocomplete(document.getElementById('customer_name'), {
                                threshold: 2,
                                data: response.data[0],
                                maximumItems: 5,
                                label: 'name',
                                value: 'id',
                                onSelectItem: ({ label, value }) => { }
                            });
                        } else {
                            new Autocomplete(document.getElementById('supplier_name'), {
                                threshold: 2,
                                data: response.data[1],
                                maximumItems: 5,
                                label: 'name',
                                value: 'id',
                                onSelectItem: ({ label, value }) => { }
                            });
                        }
                    });
                setTimeout(() => {
                    $('#customer_name, #supplier_name').siblings('div.dropdown-menu').on('click', '.dropdown-item', function (e) {
                        dt_table.clear().draw();
                    });
                }, 1000);
            }, false);
        },
    };
</script>

