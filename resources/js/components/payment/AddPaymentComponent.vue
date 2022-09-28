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
                    <!-- <form action="#" class="form-validate" @submit.prevent="register()"> -->
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
                                        <label class="form-label" for="fv-refrencevia">Reference via</label>
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
                                                    <label class="form-label" for="fv-reciptmode">Receipt mode</label>
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
                                                                <input type="radio" class="custom-control-input retun_option" name="recipt_mode" @change="typePayment($event)" v-model="form.recipt_mode" id="fv-full-return" value="fullreturn" >
                                                                <label class="custom-control-label" for="fv-full-return">Full Return</label>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" class="custom-control-input retun_option" name="recipt_mode" @change="typePayment($event)" v-model="form.recipt_mode" id="fv-part-return" value="partreturn" >
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
                                                    <label class="form-label" for="fv-recive-date">Receive Date</label>
                                                    <div class="form-control-wrap">
                                                        <input type="date" class="form-control" id="fv-recive-date" v-model="form.reciptdate" :min="min" :max="max" onfocus="this.showPicker()">
                                                        <span v-if="errors.reciptdate" class="invalid">{{errors.reciptdate}}</span>
                                                    </div>
                                                    <div id="error-for-reciptdate" class="mt-2 text-danger"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-end-date">Receipt From</label>
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
                                                            <input type="file" name="chequeattechment" multiple class="custom-file-input" @change="uploadChequeImage">
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
                                                        <input type="date" class="form-control" id="fv-cheque-date" v-model="form.chequedate" onfocus="this.showPicker()">
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
                                                            <input type="file" name="letterattechment"  class="custom-file-input" @change="uploadLetterImage">
                                                            <label class="custom-file-label" for="fv-letterattechment">Choose photo</label>
                                                            <span v-if="errors.letterattechment" class="invalid">{{errors.letterattechment}}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 goodreturn">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-Recipt-amount">Receipt Amount</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="fv-recipt-amount" v-model="form.reciptamount" @change="changeReciptAmount">
                                                        <span v-if="errors.reciptamount" class="invalid">{{errors.reciptamount}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row py-1 my-2 salebilldetail">
                                            <div class="col-sm-9">
                                                <label class="form-label" for="fv-sale-bill-detail">Sale Bill Details</label>
                                            </div>
                                            <div v-if="scope == 'edit'" class="col-sm-3 text-right"></div>
                                            <div v-else class="col-sm-3 text-right">
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
                                                        <td><input type="text" :readonly="true" class="form-control" v-model="salebill.id"><input type="hidden" class="form-control" v-model="salebill.fid"></td>
                                                        <td><input type="text" :readonly="true" class="form-control" v-model="salebill.sup_inv"></td>
                                                        <td><input type="text" :readonly="true" class="form-control" v-model="salebill.amount"></td>
                                                        <td class="cash"><input type="text" class="form-control" v-model="salebill.adjustamount" @change="changeAdjAmount"></td>
                                                        <td class="cash"><multiselect v-model="salebill.status" :options="[{status: 'Complete', code: '1'},{status: 'Pending', code: '0'}]" placeholder="Select one" label="status" track-by="status"></multiselect></td>
                                                        <td class="cash"><input type="text" class="form-control" v-model="salebill.discount" @change="changeDiscount"></td>
                                                        <td class="cash"><input type="text" class="form-control" v-model="salebill.discountamount" @change="changeDiscountAmount"></td>
                                                        <td><input type="text" class="form-control good_return" v-model="salebill.goodreturn" @change="changeGoodReturn"></td>
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
                                                        <td class="cash"><input :readonly="true" type="text" class="form-control" v-model="form.totaladjustamount"></td>
                                                        <td class="cash"></td>
                                                        <td class="cash"></td>
                                                        <td class="cash"><input :readonly="true" type="text" class="form-control" v-model="form.discountamount"></td>
                                                        <td><input type="text" :readonly="true" class="form-control" v-model="form.goodreturn"></td>
                                                        <td class="cash"><input :readonly="true" type="text" class="form-control" v-model="form.ratedifference"></td>
                                                        <td class="cash"><input :readonly="true" type="text" class="form-control" v-model="form.bankcommission"></td>
                                                        <td class="cash"><input :readonly="true" type="text" class="form-control" v-model="form.vatav"></td>
                                                        <td class="cash"><input :readonly="true" type="text" class="form-control" v-model="form.agentcommission"></td>
                                                        <td class="cash"><input :readonly="true" type="text" class="form-control" v-model="form.claim"></td>
                                                        <td class="cash"><input :readonly="true" type="text" class="form-control" v-model="form.short"></td>
                                                        <td class="cash"><input :readonly="true" type="text" class="form-control" v-model="form.interest"></td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                        <div class="row gy-4 text-center goodreturn">
                                            <label class="form-label" for="fv-extraamount" style="margin:0px auto">Extra Amount : {{ extraAmount }}</label>
                                        </div>
                                        <div class="row gy-4">
                                            <div class="col-md-12">
                                            <form action="#" class="form-validate" @submit.prevent="register()">
                                                <div class="form-group">
                                                    <a v-bind:href="cancel_url" class="mx-2 btn btn-dim btn-secondary">Cancel</a>
                                                    <button type="submit" id="paymentsave" class="btn btn-primary">Save changes</button>
                                                </div>
                                            </form>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        </div><!-- .card -->
                    </div><!-- .nk-block -->
                    <!-- </form> -->
                </div>
            </div>
        </div>
        <div class="modal fade" id="addSalebill">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Salebills</h5>
                    <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                        <em class="icon ni ni-cross"></em>
                    </a>
                </div>
                <div class="modal-body">
                        <div class="preview-block">
                                <table id="salebills" class="table mb-2 table-hover">
                                    <thead>
                                        <tr>
                                            <th>Select</th>
				                            <th>Sall Bill Id</th>
				                            <th>Financial Year</th>
				                            <th>Supplier Invoice No</th>
				                            <th>Date</th>
				                            <th>Supplier</th>
                                            <th>Pending</th>
                                            <th>Bill Amount</th>
                                            <th>Overdue</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="itm in items" :key="itm.sallbillid" :class="itm.overdue > 90 ? 'text-danger' : ''" class="text-center">
                                            <td><input type="checkbox" class="d-block" v-model="selected" :id="itm.sallbillid" :value="{'id':itm.sallbillid, 'fid': itm.financialyear.id}"  required></td>
				                            <td>{{ itm.sallbillid}}</td>
				                            <td>{{ itm.financialyear.name }}</td>
				                            <td>{{ itm.invoiceid}}</td>
				                            <td>{{ itm.date}}</td>
				                            <td>{{ itm.supplier }}</td>
                                            <td>{{ itm.pending }}</td>
                                            <td>{{ itm.amount }}</td>
                                            <td>{{ itm.overdue }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <button class="btn btn-primary generatepayment float-right" @click="selectSalebill($event)">Select Salebill</button>
                        </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</template>

<script>
    import $ from 'jquery';
    import Form from 'vform';
    import Multiselect from 'vue-multiselect';
    //import addSalebill from './modal/AddSalebillModelComponent.vue';

    var referncevia = [];
    var salebilldata = [];
    var salebill = [];
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
        },
        data() {
            return {
                old_reference_data: '',
                cancel_url: '/payments',
                userGroups: [],
                banks:[],
                items :[],
                selected: [],
                extraAmount: '',
                salebilldata :[],
                isValidate: false,
                referncevia :[{name: 'Courier'},{name: 'Hand'},{name: 'Email'},{name: 'Whatsapp'}],

                courier:[],
                errors: {
                    name: ''
                },
                min: '',
                max: '',
                salebills: [{
                        id: '',
                        fid: '',
                        sup_inv: '',
                        amount: '',
                        adjustamount: '',
                        status: {status: 'Complete', code: '1'},
                        discount: 0,
                        discountamount: 0,
                        goodreturn: 0,
                        ratedifference: 0,
                        bankcommission: 0,
                        vatav: 0,
                        agentcommission: 0,
                        claim: 0,
                        short: 0,
                        interest: 0,
                        remark: 0,
                }],
                salebilladjust : [],
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
                    whatsapp: '',
                    receiveno: '',
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
                    reciptamount: 0,
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
            const date = new Date();
            const m = String(date.getMonth() + 1).padStart(2, '0');
            const d = String(date.getDate()).padStart(2, '0');
            const y = String(date.getFullYear());
            this.form.recivedate = [y, m, d].join('-') + ' 00:00:00';
            this.form.reciptdate = [y, m, d].join('-');
            var getbasicdata_url = '/payments/getbasicdata';
            if (this.scope != 'edit') {
                axios.get(getbasicdata_url)
            .then(responce => {
                this.salebills = responce.data.salebill;
                this.salebilldata = responce.data.salebilldata;
                this.courier = responce.data.courier;
                if (this.scope != 'edit') {
                    this.form.reciptfrom = responce.data.customer.company_name;
                    this.form.supplier = responce.data.seller.company_name;
                }
                this.form.depositebank = 'Cheque in Hand';
                let totalamount = 0;
                let totalAdjustamount = 0;
                this.salebills.forEach((value,index) => {
                    totalAdjustamount += parseInt(value.adjustamount);
                    totalamount += parseInt(value.amount);
                    setTimeout(() => {
                        this.salebilladjust[index] = value.adjustamount;
                    }, 1000);
                });
                this.form.totalamount = totalamount;
                this.form.totaladjustamount = totalAdjustamount;
                this.min = responce.data.financial_year_start_date;
                this.max = responce.data.financial_year_end_date;
            })
            }
            //this.form.refrence = 'new';
            this.form.recipt_mode = 'cheque';
        },
        methods: {
            selectSalebill(event){
                this.selected.forEach(value => {
                for(var i = 0; i < this.items.length; i++) {
                    if (this.items[i].sallbillid && this.items[i].sallbillid === value.id) {
                        this.items.splice(i, 1);
                        break;
                    }
                }
                });

                axios.post('/payments/selectsalebills', {
                    salebill: this.selected
                })
                .then(responce => {
                    $.merge(this.salebills,responce.data.salebill);
                    let totalamount = 0;
                    let totalAdjustamount = 0;
                    this.salebills.forEach(value => {
                        totalAdjustamount += parseInt(value.adjustamount);
                        totalamount += parseInt(value.amount);
                    });
                    this.form.totalamount = totalamount;
                    this.form.totaladjustamount = totalAdjustamount;
                    this.extraAmount = parseInt(this.form.reciptamount) - parseInt(this.form.totaladjustamount);
                    $('#addSalebill').hide();
                    $('.modal-backdrop').remove();
                    this.selected = [];
                    //window.location.href = '/payments/addpayment';
                })
            },

            removeSalebill (event) {
                event.preventDefault();
                let index = event.target.parentElement.parentElement.rowIndex;
                let salebillid = this.salebills[index-1].id;
                let fid = this.salebills[index-1].fid;
                this.salebills.splice(index-1, 1);

                let totalamount = 0;
                let totalAdjustamount = 0;
                this.salebills.forEach(value => {
                    totalAdjustamount += parseInt(value.adjustamount);
                    totalamount += parseInt(value.amount);
                });

                this.form.totalamount = totalamount;
                this.form.totaladjustamount = totalAdjustamount;
                this.extraAmount = parseInt(this.form.reciptamount) - totalAdjustamount;
                axios.post('/payments/removesalebill', {
                    salebill : [{'id' : salebillid, 'fid' : fid}]
                }).then(responce =>{
                    this.items = responce.data.salebilldata;
                })
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

            changeReciptAmount (event) {
                setTimeout(() => {
                    this.extraAmount = parseInt(this.form.reciptamount) - parseInt(this.form.totaladjustamount);
                }, 1000);

            },
            changeRateDiff (event) {

                let totalRateDifference = 0;
                this.salebills.forEach((value,index) => {
                    let rate = value.ratedifference;

                    if (!rate) {
                        rate = 0;
                    }

                    totalRateDifference += parseInt(rate);
                });
                setTimeout(() => {
                    this.form.ratedifference = totalRateDifference;
                },500);
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
                let diff = parseInt(amount) - (parseInt(ajdust_amount) + parseInt(discount_amount) + parseInt(goodreturn) + parseInt(bankcommossion) + parseInt(vatav) + parseInt(agentComm) + parseInt(short) - parseInt(interest) + parseInt(claim));
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
                    totalRateDifference += parseInt(rate);;
                    totalInterst += parseInt(interest);

                });
                setTimeout(() => {
                    this.form.interest = totalInterst;
                    this.form.ratedifference = totalRateDifference;
                    this.extraAmount = parseInt(this.form.reciptamount) - parseInt(this.form.totaladjustamount) - totalInterst;
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
                let diff = parseInt(amount) - (parseInt(ajdust_amount) + parseInt(discount_amount) + parseInt(goodreturn) + parseInt(bankcommossion) + parseInt(vatav) + parseInt(agentComm) + parseInt(short) - parseInt(interest) + parseInt(claim));
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
                    totalRateDifference += parseInt(rate);;
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
                let diff = parseInt(amount) - (parseInt(ajdust_amount) + parseInt(discount_amount) + parseInt(goodreturn) + parseInt(bankcommossion) + parseInt(vatav) + parseInt(agentComm) + parseInt(short) - parseInt(interest) + parseInt(claim));
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
                    totalRateDifference += parseInt(rate);;
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
                let diff = parseInt(amount) - (parseInt(ajdust_amount) + parseInt(discount_amount) + parseInt(goodreturn) + parseInt(bankcommossion) + parseInt(vatav) + parseInt(agentComm) + parseInt(short) - parseInt(interest) + parseInt(claim));
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
                    totalRateDifference += parseInt(rate);;
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
                let diff = parseInt(amount) - (parseInt(ajdust_amount) + parseInt(discount_amount) + parseInt(goodreturn) + parseInt(bankcommossion) + parseInt(vatav) + parseInt(agentComm) + parseInt(short) - parseInt(interest) + parseInt(claim));
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
                    totalRateDifference += parseInt(rate);;
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
                let diff = parseInt(amount) - (parseInt(ajdust_amount) + parseInt(discount_amount) + parseInt(goodreturn) + parseInt(bankcommossion) + parseInt(vatav) + parseInt(agentComm) + parseInt(short) - parseInt(interest) + parseInt(claim));
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
                    totalRateDifference += parseInt(rate);;
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
                let diff = parseInt(amount) - (parseInt(ajdust_amount) + parseInt(discount_amount) + parseInt(goodreturn) + parseInt(bankcommossion) + parseInt(vatav) + parseInt(agentComm) + parseInt(short) - parseInt(interest) + parseInt(claim));
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
                    totalRateDifference += parseInt(rate);
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
                let diff = parseInt(amount) - (parseInt(ajdust_amount) + parseInt(discount_amount) + parseInt(goodreturn) + parseInt(bankcommossion) + parseInt(vatav) + parseInt(agentComm) + parseInt(short) - parseInt(interest) + parseInt(claim));
                let rateDiff = rateDifference + diff;
                this.salebills[index-1].ratedifference = rateDiff;
                let discount = parseInt(discount_amount) / parseInt(amount) * 100 ;
                this.salebills[index-1].discount = parseFloat(discount).toFixed(2);

                this.salebills.forEach((value) => {
                    let disAmount = value.discountamount;
                    let rate = value.ratedifference;
                    if (!disAmount){
                        disAmount = 0;
                    }
                    if (!rate) {
                        rate = 0;
                    }
                    totalRateDifference += parseInt(rate);
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

                let discount_amount = Math.round(parseInt(discount) * parseInt(amount) / 100) ;
                this.salebills[index-1].discountamount = discount_amount;

                let diff = parseInt(amount) - (parseInt(ajdust_amount) + parseInt(discount_amount) + parseInt(goodreturn) + parseInt(bankcommossion) + parseInt(vatav) + parseInt(agentComm) + parseInt(short) - parseInt(interest) + parseInt(claim));
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
                    totalRateDifference += parseInt(rate);
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


                let amount = this.salebills[index-1].amount;
                let adjamount = this.salebills[index-1].adjustamount;

                if (this.scope == 'edit') {
                    let salebilladj = this.salebilladjust[index-1];
                    let salebillid = this.salebills[index-1].id;
                    let fid = this.salebills[index-1].fid;
                    axios.post('/payments/checkpendingpayment',{
                        old_amount : salebilladj,
                        new_amount : adjamount,
                        salebillid : salebillid,
                        fid : fid,
                    }).then(response =>{
                        let newamount = response.data.amount;
                        if (parseInt(amount) < parseInt(adjamount)) {
                            alert ('Adjust Amount is not more than bill Amount & Also Pending amount : ' + newamount);
                            this.salebills[index-1].adjustamount = newamount;
                            return false;
                        } else if (parseInt(amount) > parseInt(adjamount)) {
                            if (parseInt(newamount)) {
                                alert ('Adjust Amount is not more than Amount : ' + newamount);
                                this.salebills[index-1].adjustamount = newamount;
                                return false;
                            }
                        }
                        let newadjamount = this.salebills[index-1].adjustamount;
                        diff = amount - newadjamount;
                        discount = diff / amount * 100;
                        this.salebills[index-1].discountamount = Math.round(diff);
                        this.salebills[index-1].discount = parseFloat(discount).toFixed(2);
                    })
                }
                if (this.scope != 'edit'){
                if (parseInt(amount) > parseInt(adjamount)) {
                    diff = amount - adjamount;
                    discount = diff / amount * 100;
                        this.salebills[index-1].discountamount = Math.round(diff);
                        this.salebills[index-1].discount = parseFloat(discount).toFixed(2);
                } else if (parseInt(amount) == parseInt(adjamount)) {
                    setTimeout(() => {
                        this.salebills[index-1].discount = 0;
                        this.salebills[index-1].discountamount = 0;
                    }, 500);
                } else if (parseInt(amount) < parseInt(adjamount)) {
                        alert ('Adjust Amount is not more than bill Amount');
                        this.salebills[index-1].adjustamount = amount;
                        return false;
                    }
                }
                let bankcommossion = this.salebills[index-1].bankcommission;
                let vatav = this.salebills[index-1].vatav;
                let agentComm = this.salebills[index-1].agentcommission;
                let short = this.salebills[index-1].short;
                let claim = this.salebills[index-1].claim;
                let interest = this.salebills[index-1].interest;



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
                let diff1 = parseInt(goodreturn) + parseInt(bankcommossion) + parseInt(vatav) + parseInt(agentComm) + parseInt(short) - parseInt(interest) + parseInt(claim);
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
                    setTimeout(() => {
                        totalRateDifference += parseInt(rate);
                        totalAdjustamount += parseInt(value.adjustamount);
                        totaldiscount += parseInt(discountamount);
                    }, 500);

                });
                setTimeout(() => {
                     this.form.totaladjustamount = totalAdjustamount;
                     this.form.discountamount = totaldiscount;
                     this.form.ratedifference = totalRateDifference;
                     this.extraAmount = parseInt(this.form.reciptamount) - parseInt(this.form.totaladjustamount);
                }, 1000);
            },

            uploadChequeImage (event) {
                this.chequeimage = event.target.files;
            },
            uploadLetterImage (event) {
                this.letterimage = event.target.files[0];
            },
            typePayment(event) {

                let paymentType = event.target.value;
                let goodreturn = 0;
                let total = 0;
                if (paymentType == 'cash') {

                    $(".cash").removeClass("d-none");
                    $(".cheque").addClass("d-none");
                    $(".goodreturn").removeClass("d-none");
                    $(".good_return").removeAttr("readonly");
                    $(".table-responsive").addClass("salebilltable");
                    this.salebills.forEach((value,index) => {
                        this.salebills[index].goodreturn = goodreturn;
                        total += parseInt(goodreturn);
                    });
                    this.form.goodreturn = total;

                } else if(paymentType == 'cheque') {

                    $(".cash").removeClass("d-none");
                    $(".cheque").removeClass("d-none");
                    $(".goodreturn").removeClass("d-none");
                    $(".good_return").removeAttr("readonly");
                    $(".table-responsive").addClass("salebilltable");
                    this.salebills.forEach((value,index) => {
                        this.salebills[index].goodreturn = goodreturn;
                        total += parseInt(goodreturn);
                    });
                    this.form.goodreturn = total;

                } else if (paymentType == 'fullreturn') {

                    $(".cheque").addClass("d-none");
                    $(".cash").addClass("d-none");
                    $(".goodreturn").addClass("d-none");
                    $(".good_return").attr("readonly", true);
                    $(".table-responsive").removeClass("salebilltable");
                    this.salebills.forEach((value,index) => {
                        this.salebills[index].goodreturn = this.salebills[index].amount;
                        total += parseInt(this.salebills[index].goodreturn);
                    });
                    this.form.goodreturn = total;
                } else if (paymentType == 'partreturn') {

                    $(".cheque").addClass("d-none");
                    $(".cash").addClass("d-none");
                    $(".goodreturn").addClass("d-none");
                    $(".good_return").removeAttr("readonly");
                    $(".table-responsive").removeClass("salebilltable");
                    this.salebills.forEach((value,index) => {
                        this.salebills[index].goodreturn = this.salebills[index].amount;
                        total += parseInt(this.salebills[index].goodreturn);
                    });
                    this.form.goodreturn = total;
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
            register () {
                $("#error-for-couurier").text("");
                $("#error-for-reference").text("");
                $("#error-for-recivedate").text("");
                $("#error-for-chequedate").text("");
                $("#error-for-emailfrom").text("");
                $("#error-for-fromno").text("");
                var paymentdata = new FormData();
                setTimeout(() => {
                    if (this.scope == 'edit') {
                        paymentdata.append('billdata', JSON.stringify(this.salebills));
                        paymentdata.append('formdata', JSON.stringify(this.form));
                        //paymentdata.append('chequeimage', this.chequeimage);
                        paymentdata.append('letterimage', this.letterimage);
                        this.chequeimage.forEach((contact,index)=>{
                        if(contact){
                            paymentdata.append(`chequeimage[${index}]`, contact);
                        }});
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
                            if (this.form.refrence == 1) {
                            if (this.form.refrencevia && this.form.refrencevia.name == 'Courier') {

                                if (this.form.courrier == '') {
                                    $("#error-for-couurier").text("Select Courier");
                                    this.isValidate = false;
                                } else {
                                    $("#error-for-couurier").text("");
                                    this.isValidate = true;
                                }
                                if (this.form.recivedate == '') {
                                    $("#error-for-recivedate").text("Select Receive Date");
                                    this.isValidate = false;
                                } else {
                                    $("#error-for-recivedate").text("");
                                    this.isValidate = true;
                                }
                            } else if (this.form.refrencevia && this.form.refrencevia.name == 'Hand') {
                                if (this.form.recivedate == '') {
                                    $("#error-for-recivedate").text("Select Receive Date");
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
                        if (this.form.reciptdate == '') {
                            $("#error-for-reciptdate").text("Select Receipt Date");
                            this.isValidate = false;
                        } else {
                            this.isValidate = true;
                            $("#error-for-reference").text("");
                        }
                        if (this.form.recipt_mode == 'cheque') {
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
                        this.chequeimage.forEach((contact,index)=>{
                        if(contact){
                            paymentdata.append(`chequeimage[${index}]`, contact);
                        }});
                        if (this.isValidate) {
                            axios.post('/payments/create', paymentdata)
                            .then((response2) => {
                                if (response2.data.redirect_url == ''){
                                    window.location.href = '/payments';
                                } else {
                                    window.location.href = response2.data.redirect_url;
                                }
                            })
                            .catch((error) => {
                                var validationError = error.response.data.errors;
                            })
                        } else {
                            alert('Please Fill required Field');
                        }
                    }
                }, 2000);
            },
        },
        mounted() {
            $(document).keypress(function(event) {
            if (event.key === "Enter") {
                $("#paymentsave").click();
            }
            });
            var main_url = location.href.split('/');
            if (main_url[main_url.length - 2] != 'edit-payment') {
                var getsalbillforadd_url = '/payments/getsalbillforadd';
                axios.get(getsalbillforadd_url)
                .then(responce => {
                    this.items = responce.data.salebilldata;
                });
            }

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
            if (this.scope == 'edit') {
                if (this.form.recipt_mode == 'cash' || this.form.recipt_mode == 'cheque'){
                    $(".retun_option").attr("disabled",true);
                }
            }
            $(document).on('change', '.old-reference', function () {
                self.form.refrence_type = this.value;
            });
            $(document).on('click', '#sale_bill_ref_search_btn', function() {
                axios.get('/payments/getOldReferenceForSaleBill/'+$('#sale_bill_ref_search').val())
                .then(response2 => {
                    if (response2.data != '') {
                        $('#allhiddenfield_div').html(response2.data);
                        $('#sale_bill_ref_msg').html('<td><div class="custom-control custom-radio"><input class="custom-control-input" type="radio" name="reference_id_sale_bill" value="r-'+$('#hidden_reference_id_input').val()+'" id="r-'+$('#hidden_reference_id_input').val()+'"><label class="custom-control-label" for="r-'+$('#hidden_reference_id_input').val()+'"></label></div></td><td>'+$('#hidden_reference_id_input').val()+'</td><td>'+$('#hidden_ref_emp_name').val()+'</td><td>'+$('#hidden_ref_date_added').val()+'</td><td>'+$('#hidden_ref_time_added').val()+'</td>');
                        $('#show-references tr input[type="radio"]').last().prop('checked', true);
                        self.form.refrence_type = $('#hidden_reference_id_input').val();
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
                            self.salebills = gData.salebill;
                            gData.salebill.forEach((value, index) => {
                                self.salebilladjust[index] = value.adjustamount;
                            });
                        }, 1000);

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
    #addSalebill .modal-dialog{
        max-width: 920px;
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
