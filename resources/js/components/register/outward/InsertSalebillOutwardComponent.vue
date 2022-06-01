<template>
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">Insert Sale Bill Outward</h3>
                            </div><!-- .nk-block-head-content -->
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->

                    <div class="nk-block">
                        <div class="card card-bordered">
                            <div class="card-header">
                                <h6>Insert Sale bill Outward</h6>
                            </div>
                            <div class="card-inner">
                                    <input type="hidden" v-if="scope == 'edit'" id="fv-group-id" v-model="form.id">
                                    <div class="preview-block">
                                        <div class="row gy-4">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-customer">Buyer</label>
                                                    <div>
                                                        <multiselect v-model="form.buyer" :options="buyer" placeholder="Select one" label="company_name" track-by="company_name"></multiselect>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-from">From Date</label>
                                                    <div>
                                                        <input type="date" v-model="form.fromdate" class="form-control" onfocus="this.showPicker()">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-to">To Date</label>
                                                    <div>
                                                        <input type="date" v-model="form.todate" class="form-control" onfocus="this.showPicker()">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-seller"></label>
                                                    <div>
                                                        <button class="btn btn-primary" @click="searchSalebills()">Search</button>
                                                        <a v-bind:href="cancel_url" class="mx-2 btn btn-dim btn-secondary">Cancel</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        </div><!-- .card -->
                        <div class="card card-bordered salebill d-none">
                            <div class="card-header">
                                <h6>Insert Courier Details</h6>
                            </div>
                            <form action="#" class="form-validate" @submit.prevent="register()">
                            <div class="card-inner salebilldata">
                                <div class="row gy-4">
                                    <div class="col-sm-2 text-right">
                                        <label class="form-label" for="fv-refrence">Reference</label>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="preview-block">
                                            <ul class="custom-control-group g-3 align-center" id="validate-reference-div">
                                                <li class="w-25">
                                                    <div class="custom-control custom-radio">
                                                        <input v-model="form.refrence" type="radio" class="custom-control-input"  id="fv-reference_new" value="1" @click="reference_new = true" @change="newRefence">
                                                        <label class="custom-control-label" for="fv-reference_new">NEW</label>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="custom-control custom-radio">
                                                        <input v-model="form.refrence" type="radio" class="custom-control-input"  id="fv-reference_old" value="0" @click="reference_new = false" @change="getOldReferences">
                                                        <label class="custom-control-label" for="fv-reference_old">OLD</label>
                                                    </div>
                                                </li>
                                            </ul>
                                            <div id="error-for-reference" class="mt-2 text-danger"></div>
                                            <div id="error-validate-reference-div" class="mt-2 text-danger"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row gy-4">
                                    <div class="col-sm-2 text-right">
                                        <label class="form-label" for="fv-datetime">Date / Time</label>
                                    </div>
                                    <div class="col-sm-4">
                                       <input type="date" class="form-control" id="fv-datetime" v-model="form.datetime" onfocus="this.showPicker()">
                                    </div>
                                </div>
                                <div class="row gy-4">
                                    <div class="col-sm-2 text-right">
                                        <label class="form-label" for="fv-company">Company</label>
                                    </div>
                                    <div class="col-sm-4">
                                       <input type="text" class="form-control" id="fv-company" v-model="form.company">
                                       <input type="hidden" class="form-control" v-model="form.companyid" >

                                    </div>
                                </div>
                                <div class="row gy-4">
                                    <div class="col-sm-2 text-right">
                                        <label class="form-label" for="fv-refrencevia">By</label>
                                    </div>
                                    <div class="col-sm-4">
                                        <multiselect v-model="form.referncevia" :options="referncevia" placeholder="Select one" label="name" track-by="name" @select="getRefencevia"></multiselect>
                                    </div>
                                </div>
                                <div class="row gy-4 courrier">
                                    <div class="col-sm-2 text-right">
                                        <label class="form-label" for="fv-courrier">Courier Name</label>
                                    </div>
                                    <div class="col-sm-4">
                                        <multiselect v-model="form.courrier" :options="courier" placeholder="Select one" label="name" track-by="name"></multiselect>
                                    </div>
                                </div>
                                <div class="row gy-4 courrier">
                                    <div class="col-sm-2 text-right">
                                        <label class="form-label" for="fv-reciptno">Courier Receipt No</label>
                                    </div>
                                    <div class="col-sm-4">
                                       <input type="text" class="form-control" id="fv-reciptno" v-model="form.reciptno" >

                                    </div>
                                </div>
                                <div class="row gy-4">
                                    <div class="col-sm-2 text-right">
                                        <label class="form-label" for="fv-recivetime">Receive Date time</label>
                                    </div>
                                    <div class="col-sm-4">
                                       <input type="date" class="form-control" id="fv-recivetime" v-model="form.recivetime" onfocus="this.showPicker()">
                                    </div>
                                </div>
                                <div class="row gy-4">
                                    <div class="col-sm-2 text-right">
                                        <label class="form-label" for="fv-delivery">Delivery By</label>
                                    </div>
                                    <div class="col-sm-4">
                                       <input type="text" class="form-control" id="fv-delivery" v-model="form.delivery" >

                                    </div>
                                </div>
                                <div class="my-3">
                                    <h6>Sale bill detail</h6>
                                </div>
                                <table id="salebills" class="table mb-2 table-hover">
                                    <thead>
                                        <tr class="text-center">
                                            <th></th>
				                            <th>Sall Bill Id</th>
                                            <th>IUID</th>
                                            <th>Refernce No</th>
				                            <th>Supplier Invoice No</th>
				                            <th>Date</th>
				                            <th>Supplier</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="itm in salebill" :key="itm.sale_bill_id" class="text-center">
                                            <td><input type="checkbox" class="d-block" v-model="selected" :id="itm.sale_bill_id" :value="itm.sale_bill_id"  required></td>
				                            <td>{{ itm.sale_bill_id }}</td>
                                            <td>{{ itm.iuid }}</td>
                                            <td>{{ itm.general_ref_id }}</td>
				                            <td>{{ itm.supplier_invoice_no}}</td>
				                            <td>{{ itm.select_date}}</td>
				                            <td>{{ itm.company_name }}</td>

                                        </tr>
                                    </tbody>
                                </table>
                                <div class="row gy-4">
                                    <div class="col-sm-2 text-right">
                                        <label class="form-label" for="fv-delivery">Agent</label>
                                    </div>
                                    <div class="col-sm-4">
                                       <multiselect v-model="form.agent" :options="agents" placeholder="Select one" label="name" track-by="name"></multiselect>

                                    </div>
                                </div>
                                <button class="btn btn-primary generatepayment mb-2 float-right disabled">Save Changes</button>
                                <a v-bind:href="cancel_url" class="mx-2 btn btn-dim float-right btn-secondary">Cancel</a>
                            </div>
                            </form>
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
        name: 'createSalebillOutward',
        components: {
            Multiselect,
        },
        props: {
            scope: String,
            id: Number,
        },
        data() {
            return {
                cancel_url: '/register/',
                buyer: [],
                selected: [],
                salebill: [],
                agents: [],
                courier: [],
                referncevia :[{name: 'Courier'},{name: 'Hand'}],
                form: new Form({
                    buyer: '',
                    fromdate: '',
                    todate: '',
                    refrence: 1,
                    datetime: '',
                    company: '',
                    companyid: '',
                    referncevia: '',
                    courrier: '',
                    reciptno: '',
                    recivetime: '',
                    delivery: '',
                    agent: '',
                })
            }
        },
        created() {
            axios.get('/register/list-buyer')
            .then(response => {
                this.buyer = response.data.buyer;
            });
            axios.get('/register/list-agentcourier')
            .then(response => {
                this.agents = response.data.agent;
                this.courier = response.data.courier;
            });
        },
        methods: {
            getOldReferences: function (event) {
                this.form.refrence = 1;
            },
            getRefencevia (option, id) {
                let refernceby = option.name;
                if (refernceby == 'Hand') {
                     $(".courrier").addClass("d-none");
                } else if (refernceby == 'Courier') {
                    $(".courrier").removeClass("d-none");
                }
            },
            register (event) {
                var formdata = new FormData();
                formdata.append("refenceform", JSON.stringify(this.form));
                formdata.append("salebill", JSON.stringify(this.selected));
                axios.post('/register/insertcourier/',formdata)
                .then(function (responce) {

                }).catch(function (error) {
                });
            },
            searchSalebills(event) {
                const self = this;

                axios.post('/register/searchsalebill', {
                    buyer: self.form.buyer.id,
                    fromdate: self.form.fromdate,
                    todate: self.form.todate
                })
                .then(function (response) {
                    $(".salebill").removeClass("d-none");
                    $(".generatepayment").removeClass('disabled');
                    setTimeout(() => {
                        self.salebill = response.data.salebill;
                        self.form.company = response.data.company.company_name;
                        self.form.companyid = response.data.company.id;
                    }, 500);

                })
                .catch(function (error) {
                });
            },
        },
    };
</script>

<style>

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
