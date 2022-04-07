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
                                                    <label class="form-label" for="fv-customer">Cuatomer</label>
                                                    <div>
                                                        <multiselect v-model="form.customer" :options="customer" placeholder="Select one" label="company_name" track-by="company_name"></multiselect>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-seller">Seller</label>
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
                                                        <a v-bind:href="cancel_url" class="btn btn-dim btn-secondary">Cancel</a>
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
                                            <th>Bill Amount</th>
                                            <th>Overdue</th>
				                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="itm in items" :key="itm.sallbillid" class="text-center">
                                            <td><input type="checkbox" class="d-block" v-model="selected" :id="itm.sallbillid" :value="itm.sallbillid"  required></td>
				                            <td>{{ itm.sallbillid}}</td>
				                            <td>{{ itm.financialyear }}</td>
				                            <td>{{ itm.invoiceid}}</td>
				                            <td>{{ itm.date}}</td>
				                            <td>{{ itm.supplier }}</td>
                                            <td>{{ itm.amount }}</td>
                                            <td>{{ itm.overdue }}</td>
				                            <td><em class="icon ni ni-eye"></em></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <button class="btn btn-primary generatepayment float-right disabled" @click="generatePayment($event)">Generate Payment</button>

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

    var items = [];
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
                cancel_url: '/payments/',
                seller: [],
                customer: [],
                selected: [],
                items: [
                    { sallbillid: 101, financialyear: "2020-2021", invoiceid: "201", date: '2021-03-15', supplier: "Dadan Soft", amount:"200", overdue: "15" },
                    { sallbillid: 103, financialyear: "2020-2021", invoiceid: "202", date: '2021-03-15', supplier: "Dadan Soft", amount:"250", overdue: "15" },
                    { sallbillid: 104, financialyear: "2020-2021", invoiceid: "203", date: '2021-03-15', supplier: "Dadan Soft", amount:"252", overdue: "15" },
                    { sallbillid: 106, financialyear: "2020-2021", invoiceid: "204", date: '2021-03-15', supplier: "Dadan Soft", amount:"285", overdue: "15" },
                    ],
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
            generatePayment(event){
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
                axios.post('/payments/searchsalebill', {
                    customer: this.form.customer.id,
                    seller: this.form.seller.id
                })
                .then(function (response) {
                    $(".salebill").removeClass("d-none");
                    $(".generatepayment").removeClass('disabled');
                })
                .catch(function (error) {
                });
            },
        },
    };
</script>

<style scoped>
    .multiselect {
        height: calc(2.125rem + 2px);
        font-family: Roboto,sans-serif;
        font-size: 13px;
        font-weight: 400;
        background-color: #fff;
        border: none;
        border-radius: 4px;
        box-shadow: none;
        transition: all 0.3s;
        min-height: 36px;
        display: inline-flex;
        flex-wrap: wrap;
    }
    .multiselect__tag-icon:after {
        color: #526484;
    }
    .multiselect__tag {
        color: #526484;
        background: #ebeef2;
        font-size: 13px;
        font-family: Roboto,sans-serif;
    }
    .multiselect__tags {
        padding: 7px 16px;
        font-size: 13px;
        min-height: 36px;
        border: 1px solid #dbdfea;
        width: 100%;
    }
    .multiselect__placeholder {
        margin-bottom: 0;
        padding-top: 0;
    }
    .multiselect__select {
        height: calc(2.125rem + 2px);
        position: absolute;
        top: 0;
        right: 0;
        width: calc(2.125rem + 2px);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .multiselect__select:before {
        display: none;
    }
    .multiselect .multiselect__select:after {
        font-family: "Nioicon";
        content: "";
        line-height: 1;
    }
    .multiselect.multiselect--active .multiselect__input, .multiselect__single {
        font-size: 13px;
        padding: 0;
        margin-bottom: 0;
        width: 98% !important;
    }
    .multiselect__content-wrapper {
        border-top: 1px solid #dbdfea;
        padding: 6px;
        top: 36px;
    }
    .multiselect__option--highlight {
        background: #ebeef2;
        border-radius: 4px;
        color: #526484;
    }
    .multiselect__element {
        margin-bottom: 0.125rem;
    }
    .multiselect__option--highlight:after, .multiselect__option:after {
        display: none;
    }
    .multiselect__option--selected.multiselect__option--highlight {
        background: #f3f3f3;
        color: #35495e;
    }
    .multiselect__option--selected {
        font-weight: 500;
    }
    .multiselect__tags-wrap {
        display: inline-flex;
    }
    .multiselect--above .multiselect__content-wrapper {
        border: 1px solid #e8e8e8;
    }
    .multiselect__tag-icon:focus, .multiselect__tag-icon:hover {
        background: #ebeef2;
    }
    .multiselect__tag-icon:focus:after, .multiselect__tag-icon:hover:after {
        color: #526484;
    }
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
