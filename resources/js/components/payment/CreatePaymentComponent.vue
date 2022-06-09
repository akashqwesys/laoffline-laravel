<template>

    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                    </div><!-- .nk-block-head -->
                    <div class="nk-block">
                        <div class="card card-bordered">
                            <div class="card-header">
                                <h6>Search Sale bills</h6>
                            </div>
                            <div class="card-inner">
                                    <input type="hidden" v-if="scope == 'edit'" id="fv-group-id" v-model="form.id">
                                    <div class="preview-block">
                                        <div class="row gy-4">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-customer">Customer</label>
                                                    <div>
                                                        <multiselect v-model="form.customer" :options="customer" placeholder="Select one" label="company_name" track-by="company_name"></multiselect>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-seller">Supplier</label>
                                                    <div>
                                                        <multiselect v-model="form.seller" :options="seller" placeholder="Select one" label="company_name" track-by="company_name"></multiselect>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-seller"></label>
                                                    <div>
                                                        <button class="btn btn-primary" @click="searchSalebills($event)">Search</button>
                                                        <button class="mx-2 btn btn-dim btn-secondary" @click="clearallfilter">Cancel</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        </div><!-- .card -->
                    </div><!-- .nk-block -->
                    <div class="nk-block">
                        <div class="card card-bordered salebill d-none">
                            <div class="card-header">
                                <h6>Sale bill Details</h6>
                            </div>
                            <div class="card-inner salebilldata">
                                <table id="salebills" class="table mb-2 table-hover">
                                    <thead>
                                        <tr>
                                            <th>Select</th>
				                            <th>Sall Bill Id</th>
				                            <th>Financial Year</th>
				                            <th>Supplier Invoice No</th>
				                            <th>Date</th>
				                            <th>Supplier</th>
                                            <th>Pending Payment</th>
                                            <th>Bill Amount</th>
                                            <th>Overdue</th>
				                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="itm in salebill" :key="itm.sallbillid" class="text-center">
                                            <td><input type="checkbox" class="d-block" v-model="selected" @change="salebillselect" :id="itm.sallbillid"  :value="{'id':itm.sallbillid, 'fid': itm.financialyear.id}"  required></td>
				                            <td>{{ itm.sallbillid }}</td>
				                            <td>{{ itm.financialyear.name }}</td>
				                            <td>{{ itm.invoiceid}}</td>
				                            <td>{{ itm.date}}</td>
				                            <td>{{ itm.supplier }}</td>
                                            <td>{{ itm.pending_payment }}</td>
                                            <td>{{ itm.amount }}</td>
                                            <td v-if="itm.overdue > 90" class="text-danger">{{ itm.overdue }}</td><td v-else>{{ itm.overdue }}</td>
				                            <td><a :href="'/account/sale-bill/view-sale-bill/'+ itm.sallbillid + '/' + itm.financialyear.id"><em class="icon ni ni-eye"></em></a></td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr class="text-center">
                                            <td><b>Total Salebill</b></td>
                                            <td>{{ slectedsalebill }}</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td><b>Total Bill Amount</b></td>
                                            <td>{{ totalamount}}</td>
                                            <td>{{ totalbillamont }}</td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                                <button class="btn btn-primary generatepayment float-right" @click="generatePayment($event)">Generate Payment</button>

                            </div>
                        </div><!-- .card -->
                    </div><!-- .nk-block -->
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Multiselect from 'vue-multiselect';
    import Form from 'vform';

    export default {
        name: 'createPayment',
        components: {
            Multiselect,
        },
        props: {
            scope: String,
            id: Number,
        },
        data() {
            return {
                cancel_url: '/payments',
                seller: [],
                customer: [],
                selected: [],
                salebill: [],
                slectedsalebill: 0,
                totalamount: '',
                totalbillamont: '',
                form: new Form({
                    id: '',
                    customer: '',
                    seller: '',
                })
            }
        },
        created() {
            axios.get('/payments/list-seller')
            .then(response => {
                this.seller = response.data;
            });
            axios.get('/payments/list-customer')
            .then(response => {
                this.customer = response.data;
            });
        },
        methods: {
            salebillselect(event) {
                let total = 0;
                let totalbill = 0;
                this.slectedsalebill = this.selected.length;
                this.selected.forEach(value => {
                this.salebill.forEach(value1 => {
                    if (value.id == value1.sallbillid) {
                        totalbill += parseInt(value1.amount);
                        total += parseInt(value1.pending_payment);
                    }
                });
            });
            this.totalbillamont = totalbill;
            this.totalamount = total;
            },
            generatePayment(event){
               if (this.selected.length == 0) {
                    alert('please select Sale Bill');
                    return false;
                }
               console.log(this.selected);
               axios.post('/payments/generatepayment', {
                    salebill: this.selected,
                    customer: this.form.customer.id,
                    seller: this.form.seller.id
                })
                .then(responce => {
                    window.location.href = '/payments/addpayment';
                })
            },
            searchSalebills(event) {
                const self = this;

                axios.post('/payments/searchsalebill', {
                    customer: self.form.customer.id,
                    seller: self.form.seller.id
                })
                .then(function (response) {
                    $(".salebill").removeClass("d-none");
                    setTimeout(() => {
                        self.salebill = response.data.salebill;
                    }, 500);

                })
                .catch(function (error) {
                });
            },
            clearallfilter(event){
               this.form.customer = "";
               this.form.seller = "";
               $(".salebill").addClass("d-none");
            },
        },
        mounted(){

        }
    };
</script>

<style scoped>

    input[type=checkbox] + label {
        display: block;
        margin: 0.2em;
        cursor: pointer;
        padding: 0.2em;
        text-transform: capitalize;
    }

    input[type=checkbox] {
        display: none;
    }

    input[type=checkbox] + label:before {
        content: "\2714";
        border: 2px solid #dbdfea;
        border-radius: 0.2em;
        display: inline-block;
        width: 25px;
        height: 25px;
        padding-left: 5px;
        padding-bottom: 5px;
        margin-right: 15px;
        vertical-align: bottom;
        color: transparent;
        transition: .2s;
    }

    input[type=checkbox] + label:active:before {
        transform: scale(0);
    }

    input[type=checkbox]:checked + label:before {
        background-color: #6576ff;
        border-color: #6576ff;
        color: #fff;
    }
</style>
