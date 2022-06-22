<template>
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">Edit Outward</h3>
                            </div><!-- .nk-block-head-content -->
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->

                    <div class="nk-block">
                        <div class="card card-bordered">
                        <div class="card card-bordered salebill">
                            <div class="card-header">
                                <h6>Edit Outward Detail</h6>
                            </div>
                            <form action="#" class="form-validate" @submit.prevent="register()">
                            <div class="card-inner salebilldata">
                                <input type="hidden" v-model="form.id" class="form-control">
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
                                        <label class="form-label" for="fv-fromname">From Name</label>
                                    </div>
                                    <div class="col-sm-4">
                                       <input type="text" class="form-control" id="fv-fromname" v-model="form.fromname">
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
                                       <input type="text" class="form-control" id="fv-reciptno" v-model="form.reciptno">

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
                                        <label class="form-label" for="fv-weightparcel">Weight Of Parcel</label>
                                    </div>
                                    <div class="col-sm-4">
                                       <input type="text" class="form-control" id="fv-weightparcel" v-model="form.weightparcel">
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
                                <div class="row gy-4">
                                    <div class="col-sm-2 text-right">
                                        <label class="form-label" for="fv-delivery">Agent</label>
                                    </div>
                                    <div class="col-sm-4">
                                       <multiselect v-model="form.agent" :options="agents" placeholder="Select one" label="name" track-by="name"></multiselect>

                                    </div>
                                </div>
                                <button class="btn btn-primary generatepayment mb-2 float-right">Update</button>
                                <a v-bind:href="cancel_url" class="mx-2 btn btn-dim float-right btn-secondary">Cancel</a>
                            </div>
                            </form>
                        </div><!-- .card -->

                    </div><!-- .nk-block -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Multiselect from 'vue-multiselect';
    import Form from 'vform';

    var gData = [];
    export default {
        name: 'editOutward',
        components: {
            Multiselect,
        },
        props: {
            id: Number,
        },
        data() {
            return {
                cancel_url: '/register',
                buyer: [],
                agents: [],
                courier: [],
                referncevia :[{name: 'Courier'},{name: 'Hand'}],
                form: new Form({
                    id: '',
                    refrence: 1,
                    datetime: '',
                    company: '',
                    companyid: '',
                    referncevia: '',
                    courrier: '',
                    reciptno: '',
                    recivetime: '',
                    fromname: '',
                    weightparcel: '',
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
                axios.post('/register/updateoutward',formdata)
                .then(function (responce) {

                }).catch(function (error) {
                });
            },
        },
        mounted() {
            axios.get(`/register/fetch-outward/${this.id}`).
            then(response => {
                gData = response.data;
                this.form.id = this.id;
                this.form.refrence = 1;
                this.form.datetime = gData.outward.outward_date;
                if(gData.outward.company_id) {
                    this.form.companyid = gData.outward.company_id;
                } else {
                    this.form.companyid = gData.outward.supplier_id;
                }
               
                this.form.company = gData.outward.company;
                if (gData.outward.type_of_outward == 'Hand') {
                    this.form.referncevia = { name:'Hand' };
                    $(".courrier").addClass("d-none");
                } else {
                    this.form.referncevia = { name:'Courier'};
                    $(".courrier").removeClass("d-none");
                }
                this.form.courrier = gData.outward.courier;
                this.form.reciptno = gData.outward.courier_receipt_no;
                this.form.recivetime = gData.outward.recivedate;
                this.form.delivery = gData.outward.delivery_by;
                this.form.fromname = gData.outward.from_name;
                this.form.weightparcel = gData.outward.weight_of_parcel;
                this.form.agent = gData.outward.courier_agent;

            });
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
