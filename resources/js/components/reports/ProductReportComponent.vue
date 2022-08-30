<template>
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">Commission Invoice Right of Report</h3>
                                <div class="nk-block-des text-soft"> </div>
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
                                                <th width="10%">Start Date</th>
                                                <th width="10%">End Date</th>
                                                <th width="10%">Product/Fabric</th>
                                                <th width="10%">Name</th>
                                                <th width="20%">Customer</th>
                                                <th width="20%">Supplier</th>
                                                <th width="10%">Sort</th>
                                                <th width="10%">Action</th>
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
                                                    <select v-model="sale_bill_for" class="form-control">
                                                        <option value="1">Product</option>
                                                        <option value="2">Fabric</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" v-model="product_name" class="form-control"
                                                        placeholder="Product/Fabric Name">
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
                                                    <select v-model="sort_by" class="form-control">
                                                        <option value="1">Amount: High to Low </option>
                                                        <option value="2">Amount: Low to High</option>
                                                        <option value="3">Meter: High to Low</option>
                                                        <option value="4">Meter: Low to High</option>
                                                        <option value="5">Piece: High to Low</option>
                                                        <option value="6">Piece: Low to High</option>
                                                    </select>
                                                </td>
                                                <td class="">
                                                    <button class="btn btn-primary btn-round btn-sm mr-1"
                                                        @click="getData()">Go</button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="row mb-3 ml-0 mr-0">
                                    <div class="col-md-4"> <b>Product/Fabric Name</b> </div>
                                    <div class="col-md-2 text-right"> <b>Meter</b> </div>
                                    <div class="col-md-2 text-right"> <b>Piece</b> </div>
                                    <div class="col-md-4 text-right"> <b>Amount</b> </div>
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
import Multiselect from 'vue-multiselect';

export default {
    name: 'productReport',
    props: {
    },
    components: {
        Multiselect,
        // ViewCompanyDetails
    },
    data() {
        return {
            customer_options: [],
            supplier_options: [],
            customer: '',
            supplier: '',
            product_name: '',
            sale_bill_for: '1',
            start_date: '',
            end_date: '',
            sort_by: '1',
            max_date: '2022-01-01',
        }
    },
    created() {
        const date = new Date();
        const m = String(date.getMonth() + 1).padStart(2, '0');
        const d = String(date.getDate()).padStart(2, '0');
        const y = String(date.getFullYear());
        this.max_date = this.end_date = [y, m, d].join('-');
        this.start_date = '2015-04-01';

        axios.get('/account/sale-bill/list-customers-and-suppliers')
        .then(response => {
            this.customer_options = response.data[0];
            this.supplier_options = response.data[1];
        });
    },
    methods: {
        getData() {
            axios.post('/reports/list-product-data', {
                start_date: this.start_date,
                end_date: this.end_date,
                sort_by: this.sort_by,
                sale_bill_for: this.sale_bill_for,
                product_name: this.product_name,
                customer: this.customer,
                supplier: this.supplier
            })
            .then(response => {
                if (response.data.length > 0) {
                    const toINR = new Intl.NumberFormat('en-IN', {});
                    var html = '', sub_html = '', meters = 0, pieces = 0, amount = 0;
                    response.data.forEach((k, i) => {
                        sub_html = '';
                        meters += parseFloat(k.meters);
                        pieces += parseFloat(k.pieces);
                        amount += parseFloat(k.amount);
                        k.sub.forEach((k2, i2) => {
                            sub_html += `<div class="col-md-4"> ${k2.company_name}</div>
                                        <div class="col-md-2 text-right">${toINR.format(k2.meters)}</div>
                                        <div class="col-md-2 text-right">${toINR.format(k2.pieces)}</div>
                                        <div class="col-md-4 text-right">${toINR.format(k2.amount)}</div>`;
                        });
                        html += `<div id="accordion-${i}" class="accordion accordion-s3">
                            <div class="accordion-item">
                                <a href="#" class="accordion-head collapsed" data-bs-toggle="collapse" data-bs-target="#accordion-item-2-${i}">
                                    <div class="title row">
                                        <div class="col-md-4"> ${k.name} </div>
                                        <div class="col-md-2 text-right">${toINR.format(k.meters)}</div>
                                        <div class="col-md-2 text-right">${toINR.format(k.pieces)}</div>
                                        <div class="col-md-4 text-right">${toINR.format(k.amount)}</div>
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
                                        <div class="col-md-4"><b>Total</b></div>
                                        <div class="col-md-2 text-right"><b>${toINR.format(meters)}</b></div>
                                        <div class="col-md-2 text-right"><b>${toINR.format(pieces)}</b></div>
                                        <div class="col-md-4 text-right"><b>${toINR.format(amount)}</b></div>
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

    },
};
</script>
