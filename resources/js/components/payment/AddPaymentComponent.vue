<template>

    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 v-if="scope == 'edit'" class="nk-block-title page-title">Edit Payments Detail</h3>
                                <h3 v-else class="nk-block-title page-title">Add Payment Detail</h3>
                                <div class="nk-block-des text-soft">
                                    <p>Please fill the all details.</p>
                                </div>
                            </div><!-- .nk-block-head-content -->
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    <form action="#" class="form-validate" @submit.prevent="register()">
                    <input type="hidden" v-if="scope == 'edit'" id="fv-payment-id" v-model="form.id">
                    <input type="hidden" v-if="scope == 'edit'" id="fv-refrence-id" v-model="form.refrence_type">
                    <div id="allhiddenfield_div"></div>
                    <div class="nk-block">
                        <div v-if="scope != 'edit'" class="card card-bordered">
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
                                    <div class="col-sm-4" style="z-index:0">
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
                                <div class="col-md-12 text-center hidden" id="show-references" v-html="old_reference_data"></div>
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
                                        <input type="datetime-local" class="form-control" id="fv-recivedate" v-model="form.recivedate" >
                                        <span v-if="errors.recivedate" class="invalid">{{errors.recivedate}}</span>
                                    </div>
                                    <div id="error-for-recivedate" class="mt-2 text-danger"></div>
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
                                    <div id="error-for-couurier" class="mt-2 text-danger"></div>
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
                                                        <input type="date" class="form-control" id="fv-recive-date" v-model="form.reciptdate">
                                                        <span v-if="errors.reciptdate" class="invalid">{{errors.reciptdate}}</span>
                                                    </div>
                                                    <div id="error-for-reciptdate" class="mt-2 text-danger"></div>
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
                                                            <input type="file" name="chequeattechment" accept="image/*" class="custom-file-input" @change="uploadChequeImage">
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
                                                    <div id="error-for-chequedate" class="mt-2 text-danger"></div>
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
                                                            <input type="file" name="letterattechment"  accept="image/*" class="custom-file-input" @change="uploadLetterImage">
                                                            <label class="custom-file-label" for="fv-letterattechment">Choose photo</label>
                                                            <span v-if="errors.letterattechment" class="invalid">{{errors.letterattechment}}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
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
                                                        <th class="cash">Adjust Amount</th>
                                                        <th class="cash">Status</th>
                                                        <th class="cash">Discount(%)</th>
                                                        <th class="cash">Discount Amount</th>
                                                        <th>Goods Return</th>
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
                                                    <tr class="salebillrow" v-for="(salebill,index) in salebills" :key="index">
                                                        <td><input type="text" :readonly="true" class="form-control" v-model="salebill.id"></td>
                                                        <td><input type="text" :readonly="true" class="form-control" v-model="salebill.sup_inv"></td>
                                                        <td><input type="text" :readonly="true" class="form-control" v-model="salebill.amount"></td>
                                                        <td class="cash"><input type="text" class="form-control" v-model="salebill.adjustamount" @change="changeAdjAmount"></td>
                                                        <td class="cash"><multiselect v-model="salebill.status" :options="[{status: 'Complete', code: '1'},{status: 'Pending', code: '0'}]" placeholder="Select one" label="status" track-by="status"></multiselect></td>
                                                        <td class="cash"><input type="text" class="form-control" v-model="salebill.discount" @change="changeDiscount"></td>
                                                        <td class="cash"><input type="text" class="form-control" v-model="salebill.discountamount" @change="changeDiscountAmount"></td>
                                                        <td><input type="text" class="form-control" v-model="salebill.goodreturn" @change="changeGoodReturn"></td>
                                                        <td class="cash"><input type="text" class="form-control" v-model="salebill.ratedifference" @change="changeRateDiff"></td>
                                                        <td class="cash"><input type="text" class="form-control" v-model="salebill.bankcommission" @change="changeBankComm"></td>
                                                        <td class="cash"><input type="text" class="form-control" v-model="salebill.vatav" @change="changeVatav"></td>
                                                        <td class="cash"><input type="text" class="form-control" v-model="salebill.agentcommission" @change="changeAgentComm"></td>
                                                        <td class="cash"><input type="text" class="form-control" v-model="salebill.claim" @change="changeClaim"></td>
                                                        <td class="cash"><input type="text" class="form-control" v-model="salebill.short" @change="changeShort"></td>
                                                        <td class="cash"><input type="text" class="form-control" v-model="salebill.interest" @change="changeInterest"></td>
                                                        <td><input type="text" class="form-control" v-model="salebill.remark" ></td>
                                                        <td><button class="btn btn-primary" @click="removeSalebill">x</button></td>
                                                    </tr>
                                                </tbody>
                                                <tfoot class="total">
                                                    <tr>
                                                        <td><strong>Total</strong></td>
                                                        <td></td>
                                                        <td><input type="text" :readonly="true" class="form-control" v-model="form.totalamount"></td>
                                                        <td class="cash"><input type="text" class="form-control" v-model="form.totaladjustamount"></td>
                                                        <td class="cash"></td>
                                                        <td class="cash"></td>
                                                        <td class="cash"><input type="text" class="form-control" v-model="form.discountamount"></td>
                                                        <td><input type="text" class="form-control" v-model="form.goodreturn"></td>
                                                        <td class="cash"><input type="text" class="form-control" v-model="form.ratedifference"></td>
                                                        <td class="cash"><input type="text" class="form-control" v-model="form.bankcommission"></td>
                                                        <td class="cash"><input type="text" class="form-control" v-model="form.vatav"></td>
                                                        <td class="cash"><input type="text" class="form-control" v-model="form.agentcommission"></td>
                                                        <td class="cash"><input type="text" class="form-control" v-model="form.claim"></td>
                                                        <td class="cash"><input type="text" class="form-control" v-model="form.short"></td>
                                                        <td class="cash"><input type="text" class="form-control" v-model="form.interest"></td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                        <div class="row gy-4">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <a v-bind:href="cancel_url" class="mx-2 btn btn-dim btn-secondary">Cancel</a>
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
    import $ from 'jquery';
    import Form from 'vform';
    import Multiselect from 'vue-multiselect';
    import addSalebill from './modal/AddSalebillModelComponent.vue';

    var items = [];
    var referncevia = [];
    var salebilldata = [];
    var salebill = [];
    var gData = [];
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
                old_reference_data: '',
                cancel_url: '/payments/',
                userGroups: [],
                banks:[],
                salebilldata :[],
                isValidate: false,
                // salebill :[
                //     {id: 101, sup_inv: 1025, amount: 5000},
                //     {id: 103, sup_inv: 1028, amount: 150000}
                // ],

                referncevia :[{name: 'Courier'},{name: 'Hand'},{name: 'Email'}],

                courier:[{name: "KOMAL ROADWAYS"},{name: "DELHI RAJASTHAN TRANSPORT"}, {name: "Dart Air"}],
                errors: {
                    name: ''
                },
                salebills: [{
                        id: '',
                        sup_inv: '',
                        amount: '',
                        adjustamount: '',
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
                chequeimage: [],
                letterimage: [],
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
                    reciptdate: '',
                    reciptfrom: '',
                    supplier: '',
                    depositebank: '',
                    chequedate: '',
                    chequeno: '',
                    chequebank: '',
                    term: '',
                    reciptamount: '',
                    discountamount: '',
                    goodreturn: '',
                    ratedifference: '',
                    bankcommission: '',
                    vatav: '',
                    agentcommission: '',
                    claim: '',
                    short: '',
                    interest: '',
                    totalamount: '',
                    totaladjustamount: ''
              })
            }
        },
        created() {
            axios.get('/payments/list-bank')
            .then(response => {
                this.banks = response.data;
            });
            if (this.scope == 'edit') {
                var getbasicdata_url = '/payments/getbasicdata?payment_id=' + this.id;
            } else {
                var getbasicdata_url = '/payments/getbasicdata';
            }
            axios.get(getbasicdata_url)
            .then(responce => {
                this.salebills = responce.data.salebill;
                this.salebilldata = responce.data.salebilldata;
                if (this.scope != 'edit') {
                    this.form.reciptfrom = responce.data.customer.company_name;
                    this.form.supplier = responce.data.seller.company_name;
                }
                this.form.depositebank = 'Cheque in Hand';
                let totalamount = 0;
                let totalAdjustamount = 0;
                this.salebills.forEach(value => {
                    totalAdjustamount += parseInt(value.adjustamount);
                    totalamount += parseInt(value.amount);
                });
                this.form.totalamount = totalamount;
                this.form.totaladjustamount = totalAdjustamount;
            })
            //this.form.refrence = 'new';
            this.form.recipt_mode = 'cheque';
        },
        methods: {
            removeSalebill (event) {
                let index = event.target.parentElement.parentElement.rowIndex;
                this.salebills.splice(index-1, 1);
            },
            getOldReferences: function (event) {

                if (this.form.refrencevia == '') {
                    setTimeout(() => {
                        this.form.refrence = 1;
                        $('#error-validate-reference-div').text('Please select Reference Via');
                    }, 500);
                } else {
                    $("#error-for-reference").text("");
                    $('#error-validate-reference-div').text('');
                    $('#overlay').show();
                    $(".new").addClass("d-none");
                    axios.get('/payments/getReferenceForSaleBill?ref_via='+this.form.refrencevia.name)
                    .then(response => {
                        this.old_reference_data = response.data;
                        $("#show-references").removeClass('d-none');
                        $('#show-references').slideDown();
                        setTimeout(() => {
                            $('#show-references tr input[type="radio"]').first().prop('checked', true);
                        }, 500);
                        $('#overlay').hide();
                    })
                    .catch(function (error) {
                        $('#overlay').hide();
                    });
                }
            },
            newRefence: function (event) {
                if (this.form.refrencevia == null) {
                    setTimeout(() => {
                        this.form.refrence = 1;
                        $('#error-validate-reference-div').text('Please select Reference Via');
                    }, 500);
                } else {
                    $(".new").removeClass("d-none");
                    $("#show-references").addClass('d-none');
                    axios.get('/payments/getReferenceForSaleBill?ref_via='+this.form.refrencevia.name)
                    .then(response => {
                        this.old_reference_data = response.data;
                        $('#show-references').slideDown();
                        setTimeout(() => {
                            $('#show-references tr input[type="radio"]').first().prop('checked', true);
                        }, 500);
                        $('#overlay').hide();
                    })
                    .catch(function (error) {
                        $('#overlay').hide();
                    });
                }
            },
            changeInterest (event) {
                let totalInterst = 0;
                let totalRateDifference = 0;
                let index = event.target.parentElement.parentElement.rowIndex;
                let bankcommossion = this.salebills[index-1].bankcommission;
                let vatav = this.salebills[index-1].vatav;
                let agentComm = this.salebills[index-1].agentcommission;
                let short = this.salebills[index-1].short;
                let claim = this.salebills[index-1].claim;
                let interest = this.salebills[index-1].interest;
                let amount = this.salebills[index-1].amount;
                let ajdust_amount = this.salebills[index-1].adjustamount;
                let discount_amount = this.salebills[index-1].discountamount;

                let rateDifference = 0;
                let goodreturn = this.salebills[index-1].goodreturn;

                if (!goodreturn) {
                    goodreturn = 0 ;
                }
                if (!bankcommossion) {
                    bankcommossion = 0;
                }
                if (!rateDifference) {
                    rateDifference = 0;
                }
                if (!vatav) {
                    vatav = 0;
                }
                if (!agentComm) {
                    agentComm = 0;
                }
                if (!short) {
                    short = 0;
                }
                if (!interest) {
                    interest = 0;
                }
                if (!claim) {
                    claim = 0;
                }
                if (!discount_amount) {
                    discount_amount = 0;
                }
                let diff = parseInt(amount) - (parseInt(ajdust_amount) + parseInt(discount_amount) + parseInt(goodreturn) + parseInt(bankcommossion) + parseInt(vatav) + parseInt(agentComm) + parseInt(short) + parseInt(interest) + parseInt(claim));
                let rateDiff = rateDifference + diff;
                this.salebills[index-1].ratedifference = rateDiff;
                this.salebills.forEach((value,index) => {
                    let rate = value.ratedifference;
                    let interest = value.interest;
                    if (!rate) {
                        rate = 0;
                    }
                    if (!interest) {
                        interest = 0;
                    }
                    totalRateDifference += rate;
                    totalInterst += parseInt(interest);

                });
                setTimeout(() => {
                    this.form.interest = totalInterst;
                    this.form.ratedifference = totalRateDifference;
                },500);
            },
            changeShort (event) {
                let totalShort = 0;
                let totalRateDifference = 0;
                let index = event.target.parentElement.parentElement.rowIndex;
                let bankcommossion = this.salebills[index-1].bankcommission;
                let vatav = this.salebills[index-1].vatav;
                let agentComm = this.salebills[index-1].agentcommission;
                let short = this.salebills[index-1].short;
                let claim = this.salebills[index-1].claim;
                let interest = this.salebills[index-1].interest;
                let amount = this.salebills[index-1].amount;
                let ajdust_amount = this.salebills[index-1].adjustamount;
                let discount_amount = this.salebills[index-1].discountamount;
                let rateDifference = 0;
                let goodreturn = this.salebills[index-1].goodreturn;
                if (!goodreturn) {
                    goodreturn = 0 ;
                }
                if (!bankcommossion) {
                    bankcommossion = 0;
                }
                if (!rateDifference) {
                    rateDifference = 0;
                }
                if (!vatav) {
                    vatav = 0;
                }
                if (!agentComm) {
                    agentComm = 0;
                }
                if (!short) {
                    short = 0;
                }
                if (!interest) {
                    interest = 0;
                }
                if (!claim) {
                    claim = 0;
                }

                if (!discount_amount) {
                    discount_amount = 0;
                }
                let diff = parseInt(amount) - (parseInt(ajdust_amount) + parseInt(discount_amount) + parseInt(goodreturn) + parseInt(bankcommossion) + parseInt(vatav) + parseInt(agentComm) + parseInt(short) + parseInt(interest) + parseInt(claim));
                let rateDiff = rateDifference + diff;
                this.salebills[index-1].ratedifference = rateDiff;
                this.salebills.forEach((value,index) => {
                    let rate = value.ratedifference;
                    let short = value.short;
                    if (!rate) {
                        rate = 0;
                    }
                    if (!short) {
                        short = 0;
                    }
                    totalRateDifference += rate;
                    totalShort += parseInt(short);

                });
                setTimeout(() => {
                    this.form.short = totalShort;
                    this.form.ratedifference = totalRateDifference;
                },500);
            },
            changeClaim (event) {
                let totalClaim = 0;
                let totalRateDifference = 0;
                let index = event.target.parentElement.parentElement.rowIndex;
                let bankcommossion = this.salebills[index-1].bankcommission;
                let vatav = this.salebills[index-1].vatav;
                let agentComm = this.salebills[index-1].agentcommission;
                let short = this.salebills[index-1].short;
                let claim = this.salebills[index-1].claim;
                let interest = this.salebills[index-1].interest;
                 let amount = this.salebills[index-1].amount;
                let ajdust_amount = this.salebills[index-1].adjustamount;
                let discount_amount = this.salebills[index-1].discountamount;
                let rateDifference = 0;
                let goodreturn = this.salebills[index-1].goodreturn;
                if (!goodreturn) {
                    goodreturn = 0 ;
                }
                if (!bankcommossion) {
                    bankcommossion = 0;
                }
                if (!rateDifference) {
                    rateDifference = 0;
                }
                if (!vatav) {
                    vatav = 0;
                }
                if (!agentComm) {
                    agentComm = 0;
                }
                if (!short) {
                    short = 0;
                }
                if (!interest) {
                    interest = 0;
                }
                if (!claim) {
                    claim = 0;
                }
                if (!discount_amount) {
                    discount_amount = 0;
                }
                let diff = parseInt(amount) - (parseInt(ajdust_amount) + parseInt(discount_amount) + parseInt(goodreturn) + parseInt(bankcommossion) + parseInt(vatav) + parseInt(agentComm) + parseInt(short) + parseInt(interest) + parseInt(claim));
                let rateDiff = rateDifference + diff;
                this.salebills[index-1].ratedifference = rateDiff;
                this.salebills.forEach((value,index) => {
                    let rate = value.ratedifference;
                    let claim = value.claim;
                    if (!rate) {
                        rate = 0;
                    }
                    if (!claim) {
                        claim = 0;
                    }
                    totalRateDifference += rate;
                    totalClaim += parseInt(claim);

                });
                setTimeout(() => {
                    this.form.claim = totalClaim;
                    this.form.ratedifference = totalRateDifference;
                },500);
            },
            changeAgentComm (event) {
                let totalAgentComm = 0;
                let totalRateDifference = 0;
                let index = event.target.parentElement.parentElement.rowIndex;
                let bankcommossion = this.salebills[index-1].bankcommission;
                let vatav = this.salebills[index-1].vatav;
                let agentComm = this.salebills[index-1].agentcommission;
                let short = this.salebills[index-1].short;
                let claim = this.salebills[index-1].claim;
                let interest = this.salebills[index-1].interest;
                 let amount = this.salebills[index-1].amount;
                let ajdust_amount = this.salebills[index-1].adjustamount;
                let discount_amount = this.salebills[index-1].discountamount;
                let rateDifference = 0;
                let goodreturn = this.salebills[index-1].goodreturn;
                if (!goodreturn) {
                    goodreturn = 0 ;
                }
                if (!bankcommossion) {
                    bankcommossion = 0;
                }
                if (!rateDifference) {
                    rateDifference = 0;
                }
                if (!vatav) {
                    vatav = 0;
                }
                if (!agentComm) {
                    agentComm = 0;
                }
                if (!short) {
                    short = 0;
                }
                if (!interest) {
                    interest = 0;
                }
                if (!claim) {
                    claim = 0;
                }
                if (!discount_amount) {
                    discount_amount = 0;
                }
                let diff = parseInt(amount) - (parseInt(ajdust_amount) + parseInt(discount_amount) + parseInt(goodreturn) + parseInt(bankcommossion) + parseInt(vatav) + parseInt(agentComm) + parseInt(short) + parseInt(interest) + parseInt(claim));
                let rateDiff = rateDifference + diff;
                this.salebills[index-1].ratedifference = rateDiff;
                this.salebills.forEach((value,index) => {
                    let rate = value.ratedifference;
                    let agentcomm = value.agentcommission;
                    if (!rate) {
                        rate = 0;
                    }
                    if (!agentcomm) {
                        agentcomm = 0;
                    }
                    totalRateDifference += rate;
                    totalAgentComm += parseInt(agentcomm);

                });
                setTimeout(() => {
                    this.form.agentcommission = totalAgentComm;
                    this.form.ratedifference = totalRateDifference;
                },500);
            },
            changeVatav (event) {
                let totalVatav = 0;
                let totalRateDifference = 0;
                let index = event.target.parentElement.parentElement.rowIndex;
                let bankcommossion = this.salebills[index-1].bankcommission;
                let vatav = this.salebills[index-1].vatav;
                let agentComm = this.salebills[index-1].agentcommission;
                let short = this.salebills[index-1].short;
                let claim = this.salebills[index-1].claim;
                let interest = this.salebills[index-1].interest;
                 let amount = this.salebills[index-1].amount;
                let ajdust_amount = this.salebills[index-1].adjustamount;
                let discount_amount = this.salebills[index-1].discountamount;
                let rateDifference = 0;
                let goodreturn = this.salebills[index-1].goodreturn;
                if (!goodreturn) {
                    goodreturn = 0 ;
                }
                if (!bankcommossion) {
                    bankcommossion = 0;
                }
                if (!rateDifference) {
                    rateDifference = 0;
                }
                if (!vatav) {
                    vatav = 0;
                }
                if (!agentComm) {
                    agentComm = 0;
                }
                if (!short) {
                    short = 0;
                }
                if (!interest) {
                    interest = 0;
                }
                if (!claim) {
                    claim = 0;
                }
                if (!discount_amount) {
                    discount_amount = 0;
                }
                let diff = parseInt(amount) - (parseInt(ajdust_amount) + parseInt(discount_amount) + parseInt(goodreturn) + parseInt(bankcommossion) + parseInt(vatav) + parseInt(agentComm) + parseInt(short) + parseInt(interest) + parseInt(claim));
                let rateDiff = rateDifference + diff;
                this.salebills[index-1].ratedifference = rateDiff;

                this.salebills.forEach((value,index) => {
                    let rate = value.ratedifference;
                    let vatav = value.vatav;
                    if (!rate) {
                        rate = 0;
                    }
                    if (!vatav) {
                        vatav = 0;
                    }
                    totalRateDifference += rate;
                    totalVatav += parseInt(vatav);

                });
                setTimeout(() => {
                    this.form.vatav = totalVatav;
                    this.form.ratedifference = totalRateDifference;
                },500);
            },
            changeGoodReturn (event){
                let totalGoodReturn = 0;
                let totalRateDifference = 0;
                let index = event.target.parentElement.parentElement.rowIndex;
                let bankcommossion = this.salebills[index-1].bankcommission;
                let vatav = this.salebills[index-1].vatav;
                let agentComm = this.salebills[index-1].agentcommission;
                let short = this.salebills[index-1].short;
                let claim = this.salebills[index-1].claim;
                let interest = this.salebills[index-1].interest;
                 let amount = this.salebills[index-1].amount;
                let ajdust_amount = this.salebills[index-1].adjustamount;
                let discount_amount = this.salebills[index-1].discountamount;
                let rateDifference = 0;
                let goodreturn = this.salebills[index-1].goodreturn;
                if (!goodreturn) {
                    goodreturn = 0 ;
                }
                if (!bankcommossion) {
                    bankcommossion = 0;
                }
                if (!rateDifference) {
                    rateDifference = 0;
                }
                if (!vatav) {
                    vatav = 0;
                }
                if (!agentComm) {
                    agentComm = 0;
                }
                if (!short) {
                    short = 0;
                }
                if (!interest) {
                    interest = 0;
                }
                if (!claim) {
                    claim = 0;
                }
                if (!discount_amount) {
                    discount_amount = 0;
                }
                let diff = parseInt(amount) - (parseInt(ajdust_amount) + parseInt(discount_amount) + parseInt(goodreturn) + parseInt(bankcommossion) + parseInt(vatav) + parseInt(agentComm) + parseInt(short) + parseInt(interest) + parseInt(claim));
                let rateDiff = rateDifference + diff;
                this.salebills[index-1].ratedifference = rateDiff;

                this.salebills.forEach((value,index) => {
                    let rate = value.ratedifference;
                    let goodreturn = value.goodreturn;
                    if (!rate) {
                        rate = 0;
                    }
                    if (!goodreturn) {
                        goodreturn = 0;
                    }
                    totalRateDifference += rate;
                    totalGoodReturn += parseInt(goodreturn);
                });
                setTimeout(() => {
                    this.form.goodreturn = totalGoodReturn;
                    this.form.ratedifference = totalRateDifference;
                }, 500);
            },
            changeBankComm (event){
                let totalBankCommission = 0;
                let totalRateDifference = 0;
                let index = event.target.parentElement.parentElement.rowIndex;
                let bankcommossion = this.salebills[index-1].bankcommission;
                let vatav = this.salebills[index-1].vatav;
                let agentComm = this.salebills[index-1].agentcommission;
                let short = this.salebills[index-1].short;
                let claim = this.salebills[index-1].claim;
                let interest = this.salebills[index-1].interest;
                 let amount = this.salebills[index-1].amount;
                let ajdust_amount = this.salebills[index-1].adjustamount;
                let discount_amount = this.salebills[index-1].discountamount;
                let rateDifference = 0;
                let goodreturn = this.salebills[index-1].goodreturn;
                if (!goodreturn) {
                    goodreturn = 0 ;
                }
                if (!bankcommossion) {
                    bankcommossion = 0;
                }
                if (!rateDifference) {
                    rateDifference = 0;
                }
                if (!vatav) {
                    vatav = 0;
                }
                if (!agentComm) {
                    agentComm = 0;
                }
                if (!short) {
                    short = 0;
                }
                if (!interest) {
                    interest = 0;
                }
                if (!claim) {
                    claim = 0;
                }
                if (!discount_amount) {
                    discount_amount = 0;
                }
                let diff = parseInt(amount) - (parseInt(ajdust_amount) + parseInt(discount_amount) + parseInt(goodreturn) + parseInt(bankcommossion) + parseInt(vatav) + parseInt(agentComm) + parseInt(short) + parseInt(interest) + parseInt(claim));
                let rateDiff = rateDifference + diff;
                this.salebills[index-1].ratedifference = rateDiff;
                this.salebills.forEach((value,index) => {
                    let rate = value.ratedifference;
                    let bankcomm = value.bankcommission;
                    if (!rate) {
                        rate = 0;
                    }
                    if (!bankcomm) {
                        bankcomm = 0;
                    }
                    totalRateDifference += rate;
                    totalBankCommission += parseInt(bankcomm);
                });
                setTimeout(() => {
                    this.form.bankcommission = totalBankCommission;
                    this.form.ratedifference = totalRateDifference;
                },500);

            },
            changeDiscountAmount (event) {
                let totalamount = 0;
                let totalRateDifference = 0;
                let index = event.target.parentElement.parentElement.rowIndex;
                let bankcommossion = this.salebills[index-1].bankcommission;
                let vatav = this.salebills[index-1].vatav;
                let agentComm = this.salebills[index-1].agentcommission;
                let short = this.salebills[index-1].short;
                let claim = this.salebills[index-1].claim;
                let interest = this.salebills[index-1].interest;
                let amount = this.salebills[index-1].amount;
                let ajdust_amount = this.salebills[index-1].adjustamount;
                let discount_amount = this.salebills[index-1].discountamount;
                let rateDifference = 0;
                let goodreturn = this.salebills[index-1].goodreturn;
                if (!goodreturn) {
                    goodreturn = 0 ;
                }
                if (!bankcommossion) {
                    bankcommossion = 0;
                }
                if (!rateDifference) {
                    rateDifference = 0;
                }
                if (!vatav) {
                    vatav = 0;
                }
                if (!agentComm) {
                    agentComm = 0;
                }
                if (!short) {
                    short = 0;
                }
                if (!interest) {
                    interest = 0;
                }
                if (!claim) {
                    claim = 0;
                }
                if (!discount_amount) {
                    discount_amount = 0;
                }
                let diff = parseInt(amount) - (parseInt(ajdust_amount) + parseInt(discount_amount) + parseInt(goodreturn) + parseInt(bankcommossion) + parseInt(vatav) + parseInt(agentComm) + parseInt(short) + parseInt(interest) + parseInt(claim));
                let rateDiff = rateDifference + diff;
                this.salebills[index-1].ratedifference = rateDiff;
                let discount = parseInt(discount_amount) / parseInt(amount) * 100 ;
                this.salebills[index-1].discount = discount;

                this.salebills.forEach((value) => {
                    let disAmount = value.discountamount;
                    let rate = value.ratedifference;
                    if (!disAmount){
                        disAmount = 0;
                    }
                    if (!rate) {
                        rate = 0;
                    }
                    totalRateDifference += rate;
                    totalamount +=parseInt(disAmount);
                });
                this.form.discountamount = totalamount;
                this.form.ratedifference = totalRateDifference;
            },
            changeDiscount (event) {
                let totalRateDifference = 0;
                let totalamount = 0;
                let index = event.target.parentElement.parentElement.rowIndex;
                let bankcommossion = this.salebills[index-1].bankcommission;
                let vatav = this.salebills[index-1].vatav;
                let agentComm = this.salebills[index-1].agentcommission;
                let short = this.salebills[index-1].short;
                let claim = this.salebills[index-1].claim;
                let interest = this.salebills[index-1].interest;
                let amount = this.salebills[index-1].amount;
                let ajdust_amount = this.salebills[index-1].adjustamount;
                let discount = this.salebills[index-1].discount;

                let rateDifference = 0;
                let goodreturn = this.salebills[index-1].goodreturn;
                if (!goodreturn) {
                    goodreturn = 0 ;
                }
                if (!bankcommossion) {
                    bankcommossion = 0;
                }
                if (!rateDifference) {
                    rateDifference = 0;
                }
                if (!vatav) {
                    vatav = 0;
                }
                if (!agentComm) {
                    agentComm = 0;
                }
                if (!short) {
                    short = 0;
                }
                if (!interest) {
                    interest = 0;
                }
                if (!claim) {
                    claim = 0;
                }

                let discount_amount = parseInt(discount) * parseInt(amount) / 100 ;
                this.salebills[index-1].discountamount = discount_amount;

                let diff = parseInt(amount) - (parseInt(ajdust_amount) + parseInt(discount_amount) + parseInt(goodreturn) + parseInt(bankcommossion) + parseInt(vatav) + parseInt(agentComm) + parseInt(short) + parseInt(interest) + parseInt(claim));
                let rateDiff = rateDifference + diff;
                this.salebills[index-1].ratedifference = rateDiff;



                this.salebills.forEach((value) => {
                    let disAmount = value.discountamount;
                    let rate = value.ratedifference;
                    if (!disAmount){
                        disAmount = 0;
                    }
                    if (!rate) {
                        rate = 0;
                    }
                    totalRateDifference += rate;
                    totalamount +=parseInt(disAmount);
                });
                this.form.discountamount = totalamount;
                this.form.ratedifference = totalRateDifference;
            },
            changeAdjAmount (event) {
                let totalRateDifference = 0;
                let totalAdjustamount = 0,totaldiscount = 0 ;
                let diff,discount;
                let index = event.target.parentElement.parentElement.rowIndex;

                let bankcommossion = this.salebills[index-1].bankcommission;
                let vatav = this.salebills[index-1].vatav;
                let agentComm = this.salebills[index-1].agentcommission;
                let short = this.salebills[index-1].short;
                let claim = this.salebills[index-1].claim;
                let interest = this.salebills[index-1].interest;
                let amount = this.salebills[index-1].amount;
                let adjamount = this.salebills[index-1].adjustamount;


                let rateDifference = 0;
                let goodreturn = this.salebills[index-1].goodreturn;
                if (!goodreturn) {
                    goodreturn = 0 ;
                }
                if (!bankcommossion) {
                    bankcommossion = 0;
                }
                if (!rateDifference) {
                    rateDifference = 0;
                }
                if (!vatav) {
                    vatav = 0;
                }
                if (!agentComm) {
                    agentComm = 0;
                }
                if (!short) {
                    short = 0;
                }
                if (!interest) {
                    interest = 0;
                }
                if (!claim) {
                    claim = 0;
                }

                if (amount > adjamount) {
                    diff = amount - adjamount;
                    discount = diff / amount * 100;
                        this.salebills[index-1].discountamount = diff;
                        this.salebills[index-1].discount = discount;
                } else if (amount == adjamount) {
                    setTimeout(() => {
                        this.salebills[index-1].discount = 0;
                        this.salebills[index-1].discountamount = 0;
                    }, 500);
                }

                let diff1 = parseInt(goodreturn) + parseInt(bankcommossion) + parseInt(vatav) + parseInt(agentComm) + parseInt(short) + parseInt(interest) + parseInt(claim);
                let rateDiff = rateDifference - diff1;
                this.salebills[index-1].ratedifference = rateDiff;
                this.salebills.forEach((value,index) => {
                    let discountamount = value.discountamount;
                    let rate = value.ratedifference;
                    if(!discountamount){
                        discountamount = 0;
                    }
                    if (!rate) {
                        rate = 0;
                    }
                    totalRateDifference += rate;
                    totalAdjustamount += parseInt(value.adjustamount);
                    totaldiscount += parseInt(discountamount);
                });
                setTimeout(() => {
                     this.form.totaladjustamount = totalAdjustamount;
                     this.form.discountamount = totaldiscount;
                     this.form.ratedifference = totalRateDifference;
                }, 1000);

            },

            uploadChequeImage (event) {
                this.chequeimage = event.target.files[0];
               // console.log(this.chequeimage);
            },
            uploadLetterImage (event) {
                this.letterimage = event.target.files[0];
            },
            typePayment(event) {
                let paymentType = event.target.value;
                let goodreturn = 0;
                if (paymentType == 'cash') {

                    $(".cash").removeClass("d-none");
                    $(".cheque").addClass("d-none");
                    $(".table-responsive").addClass("salebilltable");
                    this.salebills.forEach((value,index) => {
                        this.salebills[index].goodreturn = goodreturn;
                    });

                } else if(paymentType == 'cheque') {

                    $(".cash").removeClass("d-none");
                    $(".cheque").removeClass("d-none");
                    $(".table-responsive").addClass("salebilltable");
                    this.salebills.forEach((value,index) => {
                        this.salebills[index].goodreturn = goodreturn;
                    });

                } else if (paymentType == 'fullreturn' || paymentType == 'partreturn') {

                    $(".cheque").addClass("d-none");
                    $(".cash").addClass("d-none");
                    $(".table-responsive").removeClass("salebilltable");
                    this.salebills.forEach((value,index) => {
                        this.salebills[index].goodreturn = this.salebills[index].amount;
                    });
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
                $("#error-for-couurier").text("");
                $("#error-for-reference").text("");
                $("#error-for-recivedate").text("");
                $("#error-for-chequedate").text("");
                var paymentdata = new FormData();
                if (this.scope == 'edit') {

                    paymentdata.append('billdata', JSON.stringify(this.salebills));
                    paymentdata.append('formdata', JSON.stringify(this.form));
                    paymentdata.append('chequeimage', this.chequeimage);
                    paymentdata.append('letterimage', this.letterimage);

                   axios.post('/payments/update',paymentdata)
                    .then(() => {
                    window.location.href = '/payments';
                    })
                    .catch((error) => {
                        var validationError = error.response.data.errors;
                    })
                } else {

                    if (this.form.refrence == '') {
                        $("#error-for-reference").text("Select Reference");
                        this.isValidate = false;
                    } else {
                        if (this.form.refrencevia && this.form.refrencevia.name == 'Courier') {

                            if (this.form.courrier == '') {
                                $("#error-for-couurier").text("Select Courier");
                                this.isValidate = false;
                            } else {
                                $("#error-for-couurier").text("");
                                this.isValidate = true;
                            }
                            if (this.form.recivedate == '') {
                                $("#error-for-recivedate").text("Select Recive Date");
                                this.isValidate = false;
                            } else {
                                $("#error-for-recivedate").text("");
                                this.isValidate = true;
                            }
                        } else if (this.form.refrencevia && this.form.refrencevia.name == 'Hand') {
                            if (this.form.recivedate == '') {
                                $("#error-for-recivedate").text("Select Recive Date");
                                this.isValidate = false;
                            } else {
                                $("#error-for-recivedate").text("");
                                this.isValidate = true;
                            }
                        }
                    }
                    if (this.form.reciptdate == '') {
                        $("#error-for-reciptdate").text("Select Receipt Date");
                        this.isValidate = false;
                    } else {
                        this.isValidate = true;
                        $("#error-for-reference").text("");
                    }
                    if (this.form.recipt_mode == 'cheque') {
                        console.log(this.form.chequedate);
                        if (this.form.chequedate == '') {
                            $("#error-for-chequedate").text("Select Cheque Date");
                            this.isValidate = false;
                        } else {
                            $("#error-for-chequedate").text("");
                            this.isValidate = true;
                        }
                    }


                    paymentdata.append('billdata', JSON.stringify(this.salebills));
                    paymentdata.append('formdata', JSON.stringify(this.form));
                    paymentdata.append('chequeimage', this.chequeimage);
                    paymentdata.append('letterimage', this.letterimage);
                    if (this.isValidate) {
                        axios.post('/payments/create', paymentdata)
                        .then((response2) => {
                            if (response2.data == ''){
                                window.location.href = '/payments';
                            } else {
                                window.location.href = response2.data;
                            }
                        })
                        .catch((error) => {
                            var validationError = error.response.data.errors;
                        })
                    } else {
                        alert('Please Fill required Field');
                    }
                }
            },
        },
        mounted() {
            const self = this;
            //this.form.refrencevia = {name: 'Courier', code: '1'};
            this.form.discountamount = 0;
            this.form.goodreturn = 0;
            this.form.ratedifference = 0;
            this.form.bankcommission = 0;
            this.form.vatav = 0;
            this.form.agentcommission = 0;
            this.form.claim = 0;
            this.form.short = 0;
            this.form.interest = 0;
            $(document).on('change', '.old-reference', function () {
                self.form.refrence_type = this.value;
                console.log(self.form);
            });
            $(document).on('click', '#sale_bill_ref_search_btn', function() {
                axios.get('/payments/getOldReferenceForSaleBill/'+$('#sale_bill_ref_search').val())
                .then(response2 => {
                    if (response2.data != '') {
                        $('#allhiddenfield_div').html(response2.data);
                        $('#sale_bill_ref_msg').html('<td><div class="custom-control custom-radio"><input class="custom-control-input" type="radio" name="reference_id_sale_bill" value="r-'+$('#hidden_reference_id_input').val()+'" id="r-'+$('#hidden_reference_id_input').val()+'"><label class="custom-control-label" for="r-'+$('#hidden_reference_id_input').val()+'"></label></div></td><td>'+$('#hidden_reference_id_input').val()+'</td><td>'+$('#hidden_ref_emp_name').val()+'</td><td>'+$('#hidden_ref_date_added').val()+'</td><td>'+$('#hidden_ref_time_added').val()+'</td>');
                        $('#show-references tr input[type="radio"]').last().prop('checked', true);
                        self.form.refrence_type = $('#hidden_reference_id_input').val();
                        console.log(self.form);
                        // $('#datepicker_transport').val($('#hidden_courier_received_time').val()).attr('readonly',true);
                    } else {
                        this.new_old_sale_bill = 1;
                        $('#sale_bill_ref_msg').html('<td colspan="5">This Reference Id is not generated by Email, Courier OR Hand.</td>');
                    }
                });

            });
            switch (this.scope) {
                case 'edit' :
                    axios.get(`/payments/fetch-payment/${this.id}`)
                    .then(response => {
                        gData = response.data;
                        self.form.recipt_mode = gData.paymentData.reciept_mode;
                        if (gData.paymentData.reciept_mode == 'cash') {
                            $(".cash").removeClass("d-none");
                            $(".cheque").addClass("d-none");
                            $(".table-responsive").addClass("salebilltable");
                        } else if (gData.paymentData.reciept_mode == 'cheque') {
                            self.form.chequedate = gData.paymentData.cheque_date;
                            self.form.chequeno = gData.paymentData.cheque_dd_no;
                            self.form.chequebank = gData.paymentData.cheque_dd_bank;
                            $(".cash").removeClass("d-none");
                            $(".cheque").removeClass("d-none");
                            $(".table-responsive").addClass("salebilltable");
                        } else if (gData.paymentData.reciept_mode == 'fullreturn' || gData.paymentData.reciept_mode == 'partreturn') {
                            $(".cheque").addClass("d-none");
                            $(".cash").addClass("d-none");
                            $(".table-responsive").removeClass("salebilltable");
                        }
                        setTimeout(() => {

                            self.form.id = this.id;
                            self.form.refrence_type = gData.paymentData.reference_id;
                            self.form.reciptdate = gData.paymentData.date;
                            self.form.reciptfrom = gData.customer.company_name;
                            self.form.supplier = gData.supplier.company_name;
                            self.form.depositebank = 'Cheque in Hand';
                            self.form.term = gData.paymentData.trns;
                            self.form.reciptamount = gData.paymentData.receipt_amount;
                            self.form.totalamount = gData.paymentData.total_amount;
                            self.form.totaladjustamount = gData.paymentData.tot_adjust_amount;
                            self.form.discountamount = gData.paymentData.tot_discount;
                            self.form.goodreturn = gData.paymentData.tot_good_returns;
                            self.form.ratedifference = gData.paymentData.tot_rate_difference;
                            self.form.bankcommission = gData.paymentData.tot_bank_cpmmission;
                            self.form.agentcommission = gData.paymentData.tot_agent_commission;
                            self.form.vatav = gData.paymentData.tot_vatav;
                            self.form.claim = gData.paymentData.tot_claim;
                            self.form.short = gData.paymentData.tot_short;
                            self.form.interest = gData.paymentData.tot_interest;
                            gData.salebill.forEach((value,index) => {
                                self.salebills[index].id = value.sr_no;
                                self.salebills[index].sup_inv = value.supplier_invoice_no;
                                self.salebills[index].amount = value.amount;
                                self.salebills[index].adjamount = value.adjust_amount;
                                self.salebills[index].discount = value.discount;
                                self.salebills[index].discountamount = value.discount_amount;
                                self.salebills[index].vatav = value.vatav;
                                self.salebills[index].bankcommission = value.bank_commission;
                                self.salebills[index].agentcommission = value.agent_commission;
                                self.salebills[index].claim = value.claim;
                                self.salebills[index].goodreturn = value.goods_return;
                                self.salebills[index].short = value.short;
                                self.salebills[index].interest = value.interest;
                                self.salebills[index].ratedifference = value.rate_difference;
                                self.salebills[index].remark = value.remark;
                            });
                        }, 500);

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
    .salebilltable >tfoot >tr >td >input{
        border:0px;
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

</style>
