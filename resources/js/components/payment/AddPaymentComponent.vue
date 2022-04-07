<template>

    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 v-if="scope == 'edit'" class="nk-block-title page-title">Edit Financial Year</h3>
                                <h3 v-else class="nk-block-title page-title">Add Financial Year</h3>
                                <div class="nk-block-des text-soft">
                                    <p>Please fill the all details.</p>
                                </div>
                            </div><!-- .nk-block-head-content -->
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    <form action="#" class="form-validate" @submit.prevent="register()">
                    <div class="nk-block">
                        <div class="card card-bordered">
                           <div class="card-header">
                                <h5>Reference Details</h5>
                            </div>
                            <div class="card-inner">
                                <div class="row gy-4">
                                    <div class="col-sm-2 text-right">
                                        <label class="form-label" for="fv-refrencevia">Refrence via</label>
                                    </div>
                                    <div class="col-sm-4">
                                        <multiselect v-model="form.refrencevia" :options="referncevia" placeholder="Select one" label="name" track-by="name" @select="getRefenceForm"></multiselect>
                                    </div>
                                </div>
                                <div class="row gy-4">
                                    <div class="col-sm-2 text-right">
                                        <label class="form-label" for="fv-refrence">Refrence</label>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-control-wrap">
                                            <ul class="custom-control-group g-3 align-center">
                                                <li>
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" class="custom-control-input" name="refrence" v-model="form.refrence" id="fv-old" value="old" @change="onRefrenceChange($event)">
                                                        <label class="custom-control-label" for="fv-old">Old</label>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" class="custom-control-input" name="refrence" v-model="form.refrence" id="fv-new" value="new" @change="onRefrenceChange($event)">
                                                        <label class="custom-control-label" for="fv-new">New</label>
                                                    </div>
                                                </li>
                                            </ul>
                                            <span v-if="errors.refrence" class="invalid">{{errors.refrence}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="new">
                                <div class="row gy-4">
                                    <div class="col-sm-2 text-right">
                                        <label class="form-label" for="fv-fromname">From Name</label>
                                    </div>
                                    <div class="col-sm-4">
                                       <input type="text" class="form-control" id="fv-fromname" v-model="form.fromname" >
                                        <span v-if="errors.fromname" class="invalid">{{errors.fromname}}</span> 
                                    </div>
                                </div>
                                <div class="courier_hand">
                                <div class="row gy-4">
                                    <div class="col-sm-2 text-right">
                                        <label class="form-label" for="fv-deliveyby">Delivery By</label>
                                    </div>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" id="fv-delivery" v-model="form.delivery" >
                                        <span v-if="errors.delivery" class="invalid">{{errors.delivery}}</span>
                                    </div>
                                </div>
                                <div class="row gy-4">
                                    <div class="col-sm-2 text-right">
                                        <label class="form-label" for="fv-weight">Weight Of Parcel</label>
                                    </div>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" id="fv-weight" v-model="form.weight" >
                                        <span v-if="errors.weight" class="invalid">{{errors.weight}}</span>
                                    </div>
                                </div>
                                <div class="row gy-4">
                                    <div class="col-sm-2 text-right">
                                        <label class="form-label" for="fv-recivedate">Received Date Time</label>
                                    </div>
                                    <div class="col-sm-4">
                                        <input type="date" class="form-control" id="fv-recivedate" v-model="form.recivedate" >
                                        <span v-if="errors.recivedate" class="invalid">{{errors.recivedate}}</span>
                                    </div>
                                </div>
                                </div>
                                <div class="courier">
                                <div class="row gy-4">
                                    <div class="col-sm-2 text-right">
                                        <label class="form-label" for="fv-courrier">Courrier Name</label>
                                    </div>
                                    <div class="col-sm-4">
                                        <multiselect v-model="form.courrier" :options="courier" placeholder="Select one" label="name" track-by="name"></multiselect>
                                    </div>
                                </div>
                                <div class="row gy-4">
                                    <div class="col-sm-2 text-right">
                                        <label class="form-label" for="fv-reciptno">Courrier Receipt No</label>
                                    </div>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" id="fv-reciptno" v-model="form.reciptno" >
                                        <span v-if="errors.reciptno" class="invalid">{{errors.reciptno}}</span>
                                    </div>
                                </div>
                                </div>
                                <div class="email d-none">
                                    <div class="row gy-4">
                                        <div class="col-sm-2 text-right">
                                            <label class="form-label" for="fv-emailfrom">From Email Id</label>
                                         </div>
                                        <div class="col-sm-4">
                                            <input type="email" class="form-control" id="fv-emailfrom" v-model="form.emailfrom" >
                                            <span v-if="errors.emailfrom" class="invalid">{{errors.emailfrom}}</span>
                                        </div>
                                    </div>
                                </div>
                                </div>
                                <div class="old d-none">
                                    <div class="row gy-4 text-center">
                                        <div class="col-sm-2 text-right">
                                        </div>
                                        <div class="col-sm-10">
                                            <table id="salebills" class="table mb-2 table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Select</th>
                                                        <th>Ref. no</th>
                                                        <th>Generated By</th>
                                                        <th>Date</th>
                                                        <th>Time</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="itm in items" :key="itm.id" class="text-center">
                                                        <td><div class="custom-control custom-radio">
                                                                <input type="radio" class="custom-control-input" name="refrence_type" v-model="form.refrence_type" :id="itm.id" :value="itm.id" >
                                                                <label class="custom-control-label" :for="itm.id"></label>
                                                            </div></td>
                                                        <td>{{itm.refno}}</td>
                                                        <td>{{itm.generateby}}</td>
                                                        <td>{{itm.date}}</td>
                                                        <td>{{itm.time}}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div> 
                        </div>
                        <div class="card card-bordered">
                            <div class="card-header">
                                    <h5>Insert Payment Details</h5>
                            </div>
                            <div class="card-inner">
                                    <input type="hidden" v-if="scope == 'edit'" id="fv-group-id" v-model="form.id">
                                    <input type="hidden" id="user_group_id" v-model="form.user_group">
                                    <div class="preview-block">
                                        <div class="row gy-4">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-reciptmode">Recipt mode</label>
                                                    <div class="form-control-wrap">
                                                        <ul class="custom-control-group g-3 align-center">
                                                        <li>
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" class="custom-control-input" name="recipt_mode" @change="typePayment($event)" v-model="form.recipt_mode" id="fv-cash" value="cash" >
                                                                <label class="custom-control-label" for="fv-cash">Cash</label>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" class="custom-control-input" name="recipt_mode" @change="typePayment($event)" v-model="form.recipt_mode" id="fv-cheque" value="cheque" >
                                                                <label class="custom-control-label" for="fv-cheque">Cheque</label>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" class="custom-control-input" name="recipt_mode" @change="typePayment($event)" v-model="form.recipt_mode" id="fv-full-return" value="fullreturn" >
                                                                <label class="custom-control-label" for="fv-full-return">Full Return</label>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" class="custom-control-input" name="recipt_mode" @change="typePayment($event)" v-model="form.recipt_mode" id="fv-part-return" value="partreturn" >
                                                                <label class="custom-control-label" for="fv-part-return">Part  Return</label>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                    <span v-if="errors.recipt_mode" class="invalid">{{errors.recipt_mode}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-recive-date">Recive Date</label>
                                                    <div class="form-control-wrap">
                                                        <input type="date" class="form-control" id="fv-recive-date" v-model="form.recivedate">
                                                        <span v-if="errors.recivedate" class="invalid">{{errors.recivedate}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-end-date">Recipt From</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" :readonly="true" class="form-control" id="fv-recipt-from" v-model="form.reciptfrom" >
                                                        <span v-if="errors.reciptfrom" class="invalid">{{errors.reciptfrom}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-end-date">Supplier</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" :readonly="true" class="form-control" id="fv-supplier" v-model="form.supplier" >
                                                        <span v-if="errors.supplier" class="invalid">{{errors.supplier}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-end-date">Cheque Attachment</label>
                                                    <div class="form-control-wrap">
                                                       <div class="custom-file">
                                                            <input type="file" :name="chequeattechment" :disabled="isSaving" accept="image/*" class="custom-file-input">
                                                            <label class="custom-file-label" for="fv-chequeattechment">Choose photo</label>
                                                            <span v-if="errors.chequeattechment" class="invalid">{{errors.chequeattechment}}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 cash">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-deposit-bank">Deposite Bank</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" :readonly="true" class="form-control" id="fv-deposit-bank" v-model="form.depositebank">
                                                        <span v-if="errors.depositebank" class="invalid">{{errors.depositebank}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 cheque">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-cheque-date">Cheque Date</label>
                                                    <div class="form-control-wrap">
                                                        <input type="date" class="form-control" id="fv-cheque-date" v-model="form.chequedate">
                                                        <span v-if="errors.chequedate" class="invalid">{{errors.chequedate}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 cheque">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-chequeno">Cheque / DD No</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="fv-chequeno" v-model="form.chequeno">
                                                        <span v-if="errors.chequeno" class="invalid">{{errors.chequeno}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 cheque">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-cheque-bank">Cheque / DD's Bank</label>
                                                    <div class="form-control-wrap">
                                                        <multiselect v-model="form.chequebank" :options="banks" placeholder="Select one" label="name" track-by="name"></multiselect>
                                                        <span v-if="errors.chequebank" class="invalid">{{errors.chequebank}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 cash">
                                                <div class="preview-block">
                                                    <label class="form-label">Term</label>
                                                    <ul class="custom-control-group g-3 align-center">
                                                        <li>
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" class="custom-control-input" name="term"  v-model="form.term" id="fv-yes" value="1" >
                                                                <label class="custom-control-label" for="fv-yes">Yes</label>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" class="custom-control-input" name="term"  v-model="form.term" id="fv-no" value="0" >
                                                                <label class="custom-control-label" for="fv-no">No</label>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                    <span v-if="errors.term" class="invalid">{{errors.term}}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-4 cash">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-end-date">Letter Attachment</label>
                                                    <div class="form-control-wrap">
                                                        <div class="custom-file">
                                                            <input type="file" :name="letterattechment" :disabled="isSaving" accept="image/*" class="custom-file-input">
                                                            <label class="custom-file-label" for="fv-letterattechment">Choose photo</label>
                                                            <span v-if="errors.letterattechment" class="invalid">{{errors.letterattechment}}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 cash">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-Recipt-amount">Recipt Amount</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="fv-recipt-amount" v-model="form.reciptamount">
                                                        <span v-if="errors.reciptamount" class="invalid">{{errors.reciptamount}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row py-1 my-2 salebilldetail">
                                            <div class="col-sm-9">
                                                <label class="form-label" for="fv-sale-bill-detail">Sale Bill Details</label>
                                            </div>
                                            <div class="col-sm-3 text-right">
                                                <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addSalebill" title="Add new company"><span class="clipboard-text">Add New</span></button>
                                            </div>
                                        </div>
                                        <div class="row py-1 my-2">
                                            <table class="table mb-2 table-hover table-responsive salebilltable">
                                                <thead>
                                                    <tr>
                                                        <th>S.No.</th>
                                                        <th>Sup Inv No</th>
                                                        <th>Amount</th>
                                                        <th>Adjust Amount / Good Return</th>
                                                        <th class="cash">Status</th>
                                                        <th class="cash">Discount(%)</th>
                                                        <th class="cash">Discount Amount</th>
                                                        <th class="cash">Goods Return</th>
                                                        <th class="cash">Rate Difference</th>
                                                        <th class="cash">Bank Commission</th>
                                                        <th class="cash">Vatav</th>
                                                        <th class="cash">Agent Commission</th>
                                                        <th class="cash">Claim</th>
                                                        <th class="cash">Short</th>
                                                        <th class="cash">Interest</th>
                                                        <th>Remark</th>
                                                        <th >Remove</th>
							        		        </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="salebill in salebills" :key="salebill.id">
                                                        <td><input type="text" :readonly="true" class="form-control" v-model="salebill.id"></td>
                                                        <td><input type="text" :readonly="true" class="form-control" v-model="salebill.sup_inv"></td>
                                                        <td><input type="text" :readonly="true" class="form-control" v-model="salebill.amount"></td>
                                                        <td><input type="text" class="form-control" v-model="salebill.adjustamount"></td>
                                                        <td class="cash"><multiselect v-model="salebill.status" :options="[{status: 'Complete', code: '1'},{status: 'Pending', code: '0'}]" placeholder="Select one" label="status" track-by="status"></multiselect></td>
                                                        <td class="cash"><input type="text" class="form-control" v-model="salebill.discount"></td>
                                                        <td class="cash"><input type="text" class="form-control" v-model="salebill.discountamount"></td>
                                                        <td class="cash"><input type="text" class="form-control" v-model="salebill.goodreturn"></td>
                                                        <td class="cash"><input type="text" class="form-control" v-model="salebill.ratedifference"></td>
                                                        <td class="cash"><input type="text" class="form-control" v-model="salebill.bankcommission"></td>
                                                        <td class="cash"><input type="text" class="form-control" v-model="salebill.vatav"></td>
                                                        <td class="cash"><input type="text" class="form-control" v-model="salebill.agentcommission"></td>
                                                        <td class="cash"><input type="text" class="form-control" v-model="salebill.claim"></td>
                                                        <td class="cash"><input type="text" class="form-control" v-model="salebill.short"></td>
                                                        <td class="cash"><input type="text" class="form-control" v-model="salebill.interest"></td>
                                                        <td><input type="text" class="form-control" v-model="salebill.remark" ></td>
                                                        <td><button class="btn btn-primary">x</button></td>
                                                    </tr>                                                
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="row gy-4">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <a v-bind:href="cancel_url" class="btn btn-dim btn-secondary">Cancel</a>
                                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div> 
                            </div>
                        </div><!-- .card -->
                    </div><!-- .nk-block -->
                    </form>
                </div>
            </div>
        </div>
        <addSalebill></addSalebill>
    </div>
</template>

<script>
    import Form from 'vform';
    import Multiselect from 'vue-multiselect';
    import addSalebill from './modal/AddSalebillModelComponent.vue';

    var items = [];
    var referncevia = [];
    var salebilldata = [];
    var salebill = [];
    var element = '';
    export default {
        name: 'addPayment',
        components: {
            Multiselect,addSalebill,
        },
        props: {
            scope: String,
            id: Number,
        },
        data() {
            return {
                cancel_url: '/payments/',
                userGroups: [],
                banks:[],
                salebilldata :[
                    { sallbillid: 101, financialyear: "2020-2021", invoiceid: "201", date: '2021-03-15', supplier: "Dadan Soft", amount:"200", overdue: "15" },
                    { sallbillid: 103, financialyear: "2020-2021", invoiceid: "202", date: '2021-03-15', supplier: "Dadan Soft", amount:"250", overdue: "15" },
                    { sallbillid: 104, financialyear: "2020-2021", invoiceid: "203", date: '2021-03-15', supplier: "Dadan Soft", amount:"252", overdue: "15" },
                    { sallbillid: 106, financialyear: "2020-2021", invoiceid: "204", date: '2021-03-15', supplier: "Dadan Soft", amount:"285", overdue: "15" },
                    ],
                // salebill :[
                //     {id: 101, sup_inv: 1025, amount: 5000},
                //     {id: 103, sup_inv: 1028, amount: 150000}
                // ],

                referncevia :[{name: 'Courier', code: '1'},{name: 'Hand', code: '2'},{name: 'Email', code: '3'}],
                items: [{id: "1", refno: "2321",generateby: "reception", date: "21-03-2020", time: "16:05"},
                        {id: "2", refno: "2328",generateby: "reception", date: "21-03-2020", time: "16:05"},
                        {id: "3", refno: "2330",generateby: "reception", date: "21-03-2020", time: "16:05"}
                        ],
                courier:[{name: "KOMAL ROADWAYS"},{name: "DELHI RAJASTHAN TRANSPORT"}, {name: "Dart Air"}],
                errors: {
                    name: ''
                },
                salebills: [{
                        id: '',
                        sup_inv: '',
                        amount: '',
                        status: '',
                        discount: '',
                        discountamount: '',
                        goodreturn: '',
                        ratedifference: '',
                        bankcommission: '',
                        vatav: '',
                        agentcommission: '',
                        claim: '',
                        short: '',
                        interest: '',
                        remark: '',
                }],
                form: new Form({
                    id: '',
                    refrencevia: '',
                    refrence: '',
                    fromname: '',
                    delivery: '',
                    weight: '',
                    recivedate: '',
                    courrier: '',
                    reciptno: '',
                    emailfrom: '',
                    refrence_type: '',
                    recipt_mode: '',
                    recivedate: '',
                    reciptfrom: '',
                    supplier: '',
                    depositebank: '',
                    chequedate: '',
                    chequeno: '',
                    chequebank: '',
                    term: '',
                    reciptamount: '',
              })
            }
        },
        created() {
            axios.get('/payments/list-bank')
            .then(response => {
                this.banks = response.data;
            });
            axios.get('/payments/getbasicdata')
            .then(responce => {
                this.salebills = responce.data.salebill;
                this.form.reciptfrom = responce.data.customer.company_name;
                this.form.supplier = responce.data.seller.company_name;
                this.form.depositebank = 'Cheque in Hand';
                
            })
            this.form.refrence = 'new';
            this.form.recipt_mode = 'cheque';
        },
        methods: {
            onRefrenceChange(event) {
                let ref = event.target.value;
                if (ref == 'old'){
                    $(".new").addClass("d-none");
                    $(".old").removeClass("d-none");
                } else {
                    $(".new").removeClass("d-none");
                    $(".old").addClass("d-none");
                }
            },
            typePayment(event) {
                let paymentType = event.target.value;
                if (paymentType == 'cash') {
                    $(".cash").removeClass("d-none");
                    $(".cheque").addClass("d-none");
                    $(".table-responsive").addClass("salebilltable");
                } else if(paymentType == 'cheque') {
                    $(".cash").removeClass("d-none");
                    $(".cheque").removeClass("d-none");
                    $(".table-responsive").addClass("salebilltable");
                } else if (paymentType == 'fullreturn' || paymentType == 'partreturn') {
                    $(".cheque").addClass("d-none");
                    $(".cash").addClass("d-none");
                    $(".table-responsive").removeClass("salebilltable");
                }
            },
            getRefenceForm(option, id) {
                let refernceby = option.name;
                if (refernceby == 'Hand') {
                    $(".courier_hand").removeClass("d-none");
                    $(".courier").addClass("d-none");
                    $(".email").addClass("d-none");
                } else if (refernceby == 'Email') {
                    $(".email").removeClass("d-none");
                    $(".courier").addClass("d-none");
                    $(".courier_hand").addClass("d-none");
                } else if(refernceby == 'Courier') {
                    $(".courier").removeClass("d-none");
                    $(".email").addClass("d-none");
                    $(".courier_hand").removeClass("d-none");
                }
            },
            register () {
                if (this.scope == 'edit') {
                    this.form.post('/payments/update')
                        .then(() => {
                            window.location.href = '/payments/';
                    })
                    .catch((error) => {
                        var validationError = error.response.data.errors;
                    })
                } else {
                    let billdata = this.salebills;
                    let formdata = this.form;
                    axios.post('/payments/create',{
                        billdata : billdata,
                        formdata : formdata
                    })
                        .then(() => {
                    })
                    .catch((error) => {
                        var validationError = error.response.data.errors;
                    })
                }
            },
        },
        mounted() {
            this.form.refrencevia = {name: 'Courier', code: '1'};
            switch (this.scope) {
                case 'edit' :
                    axios.get(`/payments/fetch-payment/${this.id}`)
                    .then(response => {
                        gData = response.data;

                        this.form.id = gData.id;
                    });
                    break;
                default:
                    break;
            }
        },
    };
</script>

<style scoped>
    .salebilltable >tbody >tr >td >input, .salebilltable >tbody >tr >td .multiselect{
        width:100px;
    }
    .form-control-wrap img {
        position: absolute;
        width: 45px;
    }
    .salebilldetail{
        border-top: 1px dashed  #dee5e7;
        border-bottom: 1px dashed  #dee5e7;
    }
    .form-control-wrap .custom-file.profilePic {
        width: 85%;
        float: right;
    }
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
</style>
