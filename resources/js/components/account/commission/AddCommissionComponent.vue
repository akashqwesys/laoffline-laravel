<template>

    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 v-if="scope == 'edit'" class="nk-block-title page-title">Edit Commission Detail</h3>
                                <h3 v-else class="nk-block-title page-title">Add Commission Detail</h3>
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
                        <div class="card card-bordered">
                           <div class="card-header">
                                <h5 v-if="scope == 'edit'">This Commission Current Reference Id is : {{ form.refrence_type }}</h5>
                                <h5 v-else >Reference Detail</h5>
                            </div>
                            <div class="card-inner">
                                <div class="row gy-4">
                                    <div class="col-sm-2 text-right">
                                        <label class="form-label" for="fv-company">Company</label>
                                    </div>
                                    <div class="col-sm-4">
                                        <input type="text" v-model="form.company" class="form-control"  placeholder="Company">
                                        <input type="hidden" v-model="form.companyid" class="form-control"  placeholder="Company">
                                    </div>
                                </div>
                                <div v-if="scope == 'edit'" class="row gy-4">
                                    <div class="col-sm-2 text-right">
                                        <label class="form-label" for="fv-refrence">Do you want to Change ??</label>
                                    </div>
                                    <div class="col-sm-4" style="z-index:0">
                                        <div class="preview-block">
                                            <ul class="custom-control-group g-3 align-center" id="validate-reference-div">
                                                <li class="w-25">
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" v-model="form.referecechange" class="custom-control-input"  id="fv-referencechange_yes" value="1" @change="referenceChange">
                                                        <label class="custom-control-label" for="fv-referencechange_yes">Yes</label>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" v-model="form.referecechange" class="custom-control-input"  id="fv-referencechange_no" value="0"  @change="referenceChange">
                                                        <label class="custom-control-label" for="fv-referencechange_no">NO</label>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="referncechange d-none">
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
                                        <label class="form-label" for="fv-refrence">Reference</label>
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
                                        <input type="datetime-local" class="form-control" id="fv-recivedate" v-model="form.recivedate" :min="min" :max="max" onfocus="this.showPicker()">
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
                                        <div id="error-for-emailfrom" class="mt-2 text-danger"></div>
                                    </div>
                                </div>
                                <div class="whatsapp d-none">
                                    <div class="row gy-4">
                                        <div class="col-sm-2 text-right">
                                            <label class="form-label" for="fv-whatsapp">From Whatsapp No</label>
                                         </div>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" id="fv-whatsapp" v-model="form.whatsapp" >
                                            <span v-if="errors.whatsapp" class="invalid">{{errors.whatsapp}}</span>
                                        </div>
                                        <div id="error-for-fromno" class="mt-2 text-danger"></div>
                                    </div>
                                    <div class="row gy-4">
                                        <div class="col-sm-2 text-right">
                                            <label class="form-label" for="fv-reciveno">Reciver No</label>
                                         </div>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" id="fv-reciveno" v-model="form.reciveno" >
                                            <span v-if="errors.reciveno" class="invalid">{{errors.reciveno}}</span>
                                        </div>
                                    </div>
                                </div>
                                </div>
                                </div>
                            </div>
                        </div>
                        <div class="card card-bordered">
                            <div class="card-header">
                                    <h5>Insert Commission Pay Details</h5>
                            </div>
                            <div class="card-inner">
                                    <input type="hidden" v-if="scope == 'edit'" id="fv-group-id" v-model="form.id">
                                    <input type="hidden" id="user_group_id" v-model="form.user_group">
                                    <div class="preview-block">
                                        <div class="row gy-6">
                                            <div class="col-md-6 my-1">
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
                                                        </ul>
                                                    <span v-if="errors.recipt_mode" class="invalid">{{errors.recipt_mode}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 my-1">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-commission-amount">Commission Payment Amount</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="fv-commission-amount" v-model="form.commissionamount">
                                                        <span v-if="errors.reciptamount" class="invalid">{{errors.reciptamount}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 my-1 cheque">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-deposit-bank">Deposite Bank</label>
                                                    <div class="form-control-wrap">
                                                        <multiselect v-model="form.depositebank" :options="banks" placeholder="Select one" label="name" track-by="name"></multiselect>
                                                        <span v-if="errors.depositebank" class="invalid">{{errors.depositebank}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 my-1 cheque">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-cheque-date">Cheque Date</label>
                                                    <div class="form-control-wrap">
                                                        <input type="date" class="form-control" id="fv-cheque-date" v-model="form.chequedate" onfocus="this.showPicker()">
                                                        <span v-if="errors.chequedate" class="invalid">{{errors.chequedate}}</span>
                                                    </div>
                                                    <div id="error-for-chequedate" class="mt-2 text-danger"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 my-1 cheque">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-chequeno">Cheque / DD No</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="fv-chequeno" v-model="form.chequeno">
                                                        <span v-if="errors.chequeno" class="invalid">{{errors.chequeno}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 my-1 cheque">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-cheque-bank">Cheque / DD's Bank</label>
                                                    <div class="form-control-wrap">
                                                        <multiselect v-model="form.chequebank" :options="banks" placeholder="Select one" label="name" track-by="name"></multiselect>
                                                        <span v-if="errors.chequebank" class="invalid">{{errors.chequebank}}</span>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    </div>
                                    </div>
                                    <div class="card card-bordered">
                                        <div class="card-header">
                                                <h5>Insert Commission Details</h5>
                                        </div>
                                        <div class="card-inner">

                                        <div class="preview-block">
                                        <div class="row py-1">
                                            <div class="col-md-6 my-1">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-commissiondate">Commission Date</label>
                                                    <div class="form-control-wrap">
                                                        <input type="date" class="form-control" id="fv-commissiondate" :min="min" :max="max" v-model="form.commissiondate" onfocus="this.showPicker()">
                                                        <span v-if="errors.commissiondate" class="invalid">{{errors.commissiondate}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 my-1">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-commissionaccount">Commission Account</label>
                                                    <div class="form-control-wrap">
                                                        <multiselect v-model="form.commissionacc" :options="agent" placeholder="Select one" label="name" track-by="name"></multiselect>
                                                        <span v-if="errors.commissionacc" class="invalid">{{errors.commissionacc}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-commissionaccount">Extra Attechment</label>
                                                    <div class="form-control-wrap">
                                                        <div class="custom-file">
                                                            <input type="file" name="extraattechment"  class="custom-file-input" @change="uploadExtraAttechment">
                                                            <label class="custom-file-label" for="fv-extraattechment">Choose photo</label>
                                                            <span v-if="errors.extraattechment" class="invalid">{{errors.extraattechment}}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="row py-1 my-2 salebilldetail">
                                            <table class="table mb-2 table-hover table-responsive commissioninvoicetable">
                                                <thead>
                                                    <tr>
                                                        <th>Bill.No.</th>
                                                        <th>Date</th>
                                                        <th>Total Commission</th>
                                                        <th>Received Commission</th>
                                                        <th>Status</th>
                                                        <th>Amount</th>
                                                        <th>Remark</th>
							        		        </tr>
                                                </thead>
                                                <tbody>
                                                    <tr class="commissioninvoicerow" v-for="(commissioninvoice,index) in commissioninvoices" :key="index">
                                                        <td><input type="hidden" :readonly="true" class="form-control" v-model="commissioninvoice.id"><input type="hidden" :readonly="true" class="form-control" v-model="commissioninvoice.fid"><input type="text" :readonly="true" class="form-control" v-model="commissioninvoice.invoiceno"></td>
                                                        <td><input type="text" :readonly="true" class="form-control" v-model="commissioninvoice.date"></td>
                                                        <td><input type="text" :readonly="true" class="form-control" v-model="commissioninvoice.totalCommission"></td>
                                                        <td><input type="text" :readonly="true" class="form-control" v-model="commissioninvoice.recivedCommission.totalrecived"></td>
                                                        <td><multiselect v-model="commissioninvoice.status" :options="[{status: 'Complete', code: '1'},{status: 'Pending', code: '0'}]" placeholder="Select one" label="status" track-by="status"></multiselect></td>
                                                        <td><input type="text" class="form-control" @change="changeAmount(index, event)" v-model="commissioninvoice.amount"></td>
                                                        <td><input type="text" class="form-control" v-model="commissioninvoice.remark"></td>
                                                    </tr>
                                                </tbody>
                                                <tfoot class="total">
                                                    <tr>
                                                        <td><strong>Total</strong></td>
                                                        <td></td>
                                                        <td><input type="text" :readonly="true" class="form-control" v-model="form.totalCommission"></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td><input type="text" :readonly="true" class="form-control" v-model="form.totalamount"></td>
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

    </div>
</template>

<script>
    import $ from 'jquery';
    import Form from 'vform';
    import Multiselect from 'vue-multiselect';

    var items = [];
    var referncevia = [];
    var salebilldata = [];
    var commissioninvoices = [];
    var gData = [];
    var element = '';
    export default {
        name: 'addPayment',
        components: {
            Multiselect,
        },
        props: {
            scope: String,
            id: Number,
            fid: Number,
        },
        data() {
            return {
                old_reference_data: '',
                cancel_url: '/commission/create-commission',
                userGroups: [],
                banks:[],
                agent:[],
                salebilldata :[],
                isValidate: false,


                referncevia :[{name: 'Courier'},{name: 'Hand'},{name: 'Email'},{name: 'Whatsapp'}],

                courier:[{name: "KOMAL ROADWAYS"},{name: "DELHI RAJASTHAN TRANSPORT"}, {name: "Dart Air"}],
                errors: {
                    name: ''
                },
                commissioninvoices: [{
                        id: '',
                        fid: '',
                        invoiceno: '',
                        date: '',
                        totalCommission: '',
                        recivedCommission: '',
                        status: '',
                        amount: '',
                        remark: '',
                }],
                min: '',
                max: '',
                extraimage: [],
                form: new Form({
                    id: '',
                    company: '',
                    companyid: '',
                    referecechange: '',
                    refrencevia: '',
                    refrence: '',
                    fromname: '',
                    delivery: '',
                    weight: '',
                    recivedate: '',
                    courrier: '',
                    reciptno: '',
                    emailfrom: '',
                    whatsapp: '',
                    receiveno: '',
                    refrence_type: '',
                    recipt_mode: '',
                    depositebank: '',
                    chequedate: '',
                    chequeno: '',
                    chequebank: '',
                    commissiondate: '',
                    commissionacc: '',
                    commissionamount:'',
                    totalCommission:'',
                    totalamount:''
              })
            }
        },
        created() {
            axios.get('/payments/list-bank')
            .then(response => {
                this.banks = response.data;
            });
            const date = new Date();
            const m = String(date.getMonth() + 1).padStart(2, '0');
            const d = String(date.getDate()).padStart(2, '0');
            const y = String(date.getFullYear());
            this.form.recivedate = [y, m, d].join('-') + ' 00:00:00';
            this.form.commissiondate = [y, m, d].join('-');
            if (this.scope != 'edit') {
            axios.get('/commission/getbasicdata')
            .then(responce => {
                this.form.company = responce.data.company.company_name;
                this.form.companyid = responce.data.company.id;
                this.commissioninvoices = responce.data.commissioninvoice;
                this.agent = responce.data.agent;
                let total = 0;
                this.commissioninvoices.forEach((value,index) => {
                    total += parseInt(value.totalCommission);
                });
                this.form.totalCommission = total;
                this.min = responce.data.financialyear.start_date;
                this.max= responce.data.financialyear.end_date;
            });
            }

        },
        methods: {
            changeAmount: function (index, event) {
                let amount = this.commissioninvoices[index].amount;
                let invoiceamount = this.commissioninvoices[index].totalCommission;
                let receiveamount = this.commissioninvoices[index].recivedCommission.totalrecived;
                
                if (!receiveamount){
                    receiveamount = 0;
                }
                if (parseInt(amount) > parseInt(invoiceamount)) {
                    if (this.scope == 'edit') {
                        alert('Amount is not more than :' + invoiceamount);
                        this.commissioninvoices[index].amount = invoiceamount;
                    } else {
                        alert('Amount Is Not More Than Invoice Amount');
                    }
                } else {
                    if (this.scope != 'edit'){
                        let diff = parseInt(invoiceamount) - parseInt(receiveamount);
                        if (diff > parseInt(invoiceamount)) {
                            this.commissioninvoices[index].amount = diff;
                            alert('Amount is not more than :' + diff);
                        }
                    } else {
                        if (parseInt(amount) > parseInt(invoiceamount)) {
                            alert('Amount is not more than :' + amount);
                        } 
                    }
                }
                let total = 0;
                this.commissioninvoices.forEach(value =>{
                    let amount = value.amount;
                    if (!amount) {
                        amount = 0;
                    }
                    total += parseInt(amount);
                })
                setTimeout(() => {
                    this.form.totalamount = total;
                }, 500);
                
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
                    axios.get('/commission/getReferenceForSaleBill?ref_via='+this.form.refrencevia.name)
                    .then(response => {
                        this.old_reference_data = response.data;
                        
                        $("#show-references").removeClass('d-none');
                        $('#show-references').slideDown();
                        setTimeout(() => {
                            $('#show-references tr input[type="radio"]').first().prop('checked', true);
                            this.form.refrence_type = $('#show-references tr input[type="radio"]').first().val();
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

            uploadExtraAttechment (event) {
                this.extraimage = event.target.files[0];
            },
            typePayment(event) {
                let paymentType = event.target.value;

                if (paymentType == 'cash') {
                    $(".cash").removeClass("d-none");
                    $(".cheque").addClass("d-none");
                } else if(paymentType == 'cheque') {
                    $(".cash").removeClass("d-none");
                    $(".cheque").removeClass("d-none");
                }
            },
            getRefenceForm(option, id) {
                let refernceby = option.name;
                if (refernceby == 'Hand') {
                    this.form.refrencevia = 'Hand';
                    $(".courier_hand").removeClass("d-none");
                    $(".courier").addClass("d-none");
                    $(".email").addClass("d-none");
                    $(".whatsapp").addClass("d-none");
                } else if (refernceby == 'Email') {
                    this.form.refrencevia = 'Email';
                    $(".email").removeClass("d-none");
                    $(".courier").addClass("d-none");
                    $(".courier_hand").addClass("d-none");
                    $(".whatsapp").addClass("d-none");
                } else if(refernceby == 'Courier') {
                    this.form.refrencevia = 'Courier';
                    $(".courier").removeClass("d-none");
                    $(".email").addClass("d-none");
                    $(".courier_hand").removeClass("d-none");
                    $(".whatsapp").addClass("d-none");
                } else if (refernceby == 'Whatsapp') {
                    this.form.refrencevia = 'Whatsapp';
                    $(".courier").addClass("d-none");
                    $(".email").addClass("d-none");
                    $(".courier_hand").addClass("d-none");
                    $(".whatsapp").removeClass("d-none");
                }
                setTimeout(() => {
                    if (this.form.refrence == '0') {
                        this.getOldReferences();
                    }
                }, 500);
            },
            referenceChange (event) {
                let referecechange = event.target.value;
                if (referecechange == '1') {
                    $(".referncechange").removeClass('d-none');
                } else if (referecechange == '0') {
                    $(".referncechange").addClass('d-none');
                }
            },
            register () {
                $("#error-for-couurier").text("");
                $("#error-for-reference").text("");
                $("#error-for-recivedate").text("");
                $("#error-for-chequedate").text("");
                $("#error-for-emailfrom").text("");
                $("#error-for-fromno").text("");
                var commissiondata = new FormData();
                if (this.scope == 'edit') {

                    commissiondata.append('invoicedata', JSON.stringify(this.commissioninvoices));
                    commissiondata.append('formdata', JSON.stringify(this.form));
                    commissiondata.append('extraimage', this.extraimage);

                   axios.post('/commission/update', commissiondata)
                    .then(() => {
                        window.location.href = '/commission';
                    })
                    .catch((error) => {
                        var validationError = error.response.data.errors;
                    })
                } else {

                    if (this.form.refrence == '') {
                        console.log('refernce');
                        $("#error-for-reference").text("Select Reference");
                        this.isValidate = false;
                    } else {
                        if (this.form.refrence == 1) {
                        if (this.form.refrencevia.name == 'Courier') {
                            
                            if (this.form.courrier == '') {
                                console.log('courrier name');
                                $("#error-for-couurier").text("Select Courier");
                                this.isValidate = false;
                            } else {
                                $("#error-for-couurier").text("");
                                this.isValidate = true;
                            }
                            if (this.form.recivedate == '') {
                                console.log('recive date');
                                $("#error-for-recivedate").text("Select Recive Date");
                                this.isValidate = false;
                            } else {
                                $("#error-for-recivedate").text("");
                                this.isValidate = true;
                            }
                        } else if (this.form.refrencevia.name == 'Hand') {
                           
                            if (this.form.recivedate == '') {
                                console.log('recive date');
                                $("#error-for-recivedate").text("Select Recive Date");
                                this.isValidate = false;
                            } else {
                                $("#error-for-recivedate").text("");
                                this.isValidate = true;
                            }
                        } else if (this.form.refrencevia.name == 'Email') {
                            if (this.form.emailfrom == '') {
                                
                                $("#error-for-emailfrom").text("Enter Email");
                                this.isValidate = false;
                            } else {
                                $("#error-for-emailfrom").text("");
                                this.isValidate = true;
                            }
                        } else if (this.form.refrencevia && this.form.refrencevia.name == 'Whatsapp') {
                            if (this.form.recivedate == '') {
                                $("#error-for-fromno").text("Enter From No");
                                this.isValidate = false;
                            } else {
                                $("#error-for-fromno").text("");
                                this.isValidate = true;
                            }
                        }
                        } else {
                            this.isValidate = true;
                        }
                    }

                    if (this.form.recipt_mode == 'cheque') {
                        
                        if (this.form.chequedate == '') {
                            console.log('chequedate');
                            $("#error-for-chequedate").text("Select Cheque Date");
                            this.isValidate = false;
                        } else {
                            $("#error-for-chequedate").text("");
                            this.isValidate = true;
                        }
                    }


                    commissiondata.append('invoicedata', JSON.stringify(this.commissioninvoices));
                    commissiondata.append('formdata', JSON.stringify(this.form));
                    commissiondata.append('extraimage', this.extraimage);
                    if (this.isValidate) {
                        axios.post('/commission/create', commissiondata)
                        .then((response2) => {
                            window.location.href = '/commission';
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
                    axios.get(`/commission/fetch-commission/${this.id}/${this.fid}`)
                    .then(response => {
                        let comm_status = '';
                        gData = response.data;
                        self.agent = gData.agent;
                        self.form.recipt_mode = gData.commission.commission_reciept_mode;
                        if (gData.commission.commission_reciept_mode == 'cash') {
                            $(".cash").removeClass("d-none");
                            $(".cheque").addClass("d-none");
                            $(".table-responsive").addClass("commissioninvoicetable");
                        } else if (gData.commission.commission_reciept_mode == 'cheque') {
                            self.form.chequedate = gData.commission.cheque_date;
                            self.form.chequeno = gData.commission.cheque_dd_no;
                            self.form.chequebank = gData.commission.cheque_dd_bank;
                            self.form.depositebank = gData.commission.deposite_bank;
                            $(".cash").removeClass("d-none");
                            $(".cheque").removeClass("d-none");
                            $(".table-responsive").addClass("commissioninvoicetable");
                        }

                        self.form.id = this.id;
                        if(gData.commission.supplier_id == '0') {
                            self.form.company = gData.customer.company_name;
                            self.form.companyid = '0';
                        } else {
                            self.form.company = gData.supplier.company_name;
                            self.form.companyid = gData.commission.supplier_id;
                        }

                        self.form.refrence_type = gData.commission.reference_id;
                        self.form.commissiondate = gData.commission.commission_date;
                        self.form.commissionacc = gData.commission.commissionaccount;
                        self.form.commissionamount = gData.commission.commission_payment_amount;
                        gData.commissioninvoice.forEach((value,index) => {
                                self.commissioninvoices[index].id = value.commission_id;
                                self.commissioninvoices[index].fid = value.fid;
                                self.commissioninvoices[index].invoiceno = value.invoiceno;
                                self.commissioninvoices[index].date = value.date;
                                self.commissioninvoices[index].totalCommission = value.totalCommission;
                                self.commissioninvoices[index].recivedCommission = value.recivedCommission;
                                
                                self.commissioninvoices[index].status = value.status;
                                self.commissioninvoices[index].amount = value.amount;
                                self.commissioninvoices[index].remark = value.remark;
                        });
                        let total = 0;
                        let totalamount = 0;
                        self.commissioninvoices.forEach((value,index) => {
                            total += parseInt(value.totalCommission);
                            totalamount += parseInt(value.amount);
                        });
                        self.form.totalCommission = total;
                        self.form.totalamount = totalamount;
                    });
                    break;
                default:
                    $(".referncechange").removeClass("d-none");
                    this.form.recipt_mode = 'cheque';
                    break;
            }
        },
    };
</script>

<style scoped>

    .commissioninvoicetable >tfoot >tr >td >input{
        border:0px;
    }
    .form-control-wrap img {
        position: absolute;
        width: 45px;
    }
    .salebilldetail{
        border-top: 1px dashed  #dee5e7;
    }
    .form-control-wrap .custom-file.profilePic {
        width: 85%;
        float: right;
    }

</style>
