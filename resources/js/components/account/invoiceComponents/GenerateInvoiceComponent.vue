<template>
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">Commission Invoice</h3>
                            </div><!-- .nk-block-head-content -->
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    <div class="nk-block">
                        <div class="card card-bordered">
                            <div class="card-inner">
                                <div class="row gy-4">
                                    <div class="col-md-3"><b>Commission Invoice Details</b></div>
                                    <div class="col-md-1">
                                        <div class="custom-control custom-checkbox checked">
                                            <input type="checkbox" class="custom-control-input" v-model="comm_invoice_gst" id="comm_invoice_gst" true-value="1" false-value="0" checked @click="updateData">
                                            <label class="custom-control-label" for="comm_invoice_gst"> GST</label>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="custom-control custom-checkbox checked">
                                            <input type="checkbox" class="custom-control-input" v-model="comm_invoice_tds" id="comm_invoice_tds" true-value="1" false-value="0" checked @click="hideShowSelect_tax">
                                            <label class="custom-control-label" for="comm_invoice_tds"> TDS</label>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <select class="form-control" v-model="with_without_gst" id="with_without_gst" @change="changeGST(e)" required>
                                            <option value="1">With GST</option>
                                            <option value="2">Without GST</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <select class="form-control" v-model="courier_agent" id="courier_agent" required @change="changeAgentForInvoice(e)">
                                        <template v-for="(k, i) in agents" :key="i">
                                            <option :value="{ id: k.id, name: k.name, pan_no: k.pan_no, gst_no: k.gst_no, inv_prefix: k.inv_prefix }" > {{ k.name }} </option>
                                        </template>
                                        </select>
                                    </div>
                                    <div class="col-md-1">
                                        <input type="number" v-model="payment_comm" id="payment_comm" class="form-control" @change="onPercentageChange(e)" step="any" >
                                    </div>
                                    <div class="col-md-2">
                                        <select class="form-control" v-model="select_tax" id="select_tax" @change="everyLoadChange(e)">
                                            <option value="1">Amt - TDS</option>
                                            <option value="2">[Amt + GST] - TDS</option>
                                            <option value="3">[Amt - TDS] + GST</option>
                                        </select>
                                    </div>
                                </div>
                                <hr>

                                <div class="table-responsive" id="commission_invoice_print">
                                    <table style="border:2px solid #000;" class="table table-striped- table1">
                                        <thead>
                                            <tr style="visibility: collapse; border: solid 2pt;">
                                                <td width="10%"></td>
                                                <td width="50%"></td>
                                                <td width="25%"></td>
                                                <td width="15%"></td>
                                            </tr>
                                            <tr style="border: 0px none !important;">
                                                <td colspan="4" class="" style="border-bottom: 0px none! important;">
                                                    <div id="divlogo" class="">
                                                        <img height="60" alt="Logo" src="/assets/images/logo_report.png">
                                                    </div>
                                                    <div class="float-right">
                                                        <b><span id="invoice_pan_no"> {{ (courier_agent.pan_no) ? 'PAN: ' + courier_agent.pan_no : '' }} </span></b>
                                                        <br>
                                                        <b><span id="st_reg_no_tr"> {{ (courier_agent.gst_no) ? 'GST: ' + courier_agent.gst_no : '' }} </span></b>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" class="text-center" style="border-bottom: 0px none;border-top: 0px none; padding: 0; ">
                                                    <b><h4 class="mb-2">ACCOUNT NAME: <span class="agentLabel"> {{ courier_agent.name }} </span></h4></b>
                                                </td>
                                            </tr>
                                            <tr class="address_tr">
                                                <th colspan="4" class="text-center pb-1" style="border-top: 0px none; padding: 0;">Utsav House, 2/1399-1400, Hanuman Sheri, Sangrampura, Surat - 395002. PH.: 0261-6770770, Mob.: 9824114068.</th>
                                            </tr>
                                            <tr>
                                                <th colspan="4" class="text-center" ><h5>INVOICE</h5></th>
                                            </tr>
                                            <tr>
                                                <td colspan="2" style="border-right:0px; border-bottom:0px;">
                                                    {{ company.company_name }}
                                                    <br>
                                                    {{ company.address ?? '' }}
                                                    <br>
                                                    {{ company.gst_no ?? '' }}
                                                </td>
                                                <td colspan="2" style="border-left:0px;border-bottom:0px; padding: 0">
                                                    <table class="table">
                                                        <tbody>
                                                            <tr>
                                                                <td width='42%' style="border:0px"><b>Bill No : </b></td>
                                                                <td width='58%' id="billNo_td" colspan="3" style="border:0px"> {{ full_bill_no }} </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="border:0px"><b>Bill Period : </b></td>
                                                                <td colspan="3" style="border:0px">
                                                                    <input style="width: 80px;" type="text" v-model="bill_period_from" id="datepicker_transport" class="date-picker" data-date-format="dd-mm-yyyy">to &nbsp;&nbsp;
                                                                    <input style="width: 80px;" type="text" v-model="bill_period_to" id="order_datepicker" class="date-picker" data-date-format="dd-mm-yyyy">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="border:0px"><b>Bill Date : </b></td>
                                                                <td colspan="3" style="border:0px">
                                                                    <input style="width: 80px;" type="text" v-model="invoice_bill_date" id="datepicker" class="date-picker" data-date-format="dd-mm-yyyy">
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr class="right-none left-none" >
                                                <th width="10%" style="border-top:0px">S.No.</th>
                                                <th colspan="2" class="text-center" style="border-top:0px">Particulars</th>
                                                <th width="15%" class="text-right" style="border-top:0px">Amount (Rs.)</th>
                                            </tr>
                                            <tr class="right-nonetd left-nonetd">
                                                <td>1</td>
                                                <td colspan="2">
                                                    By Commission @ <span id="pay_percent"> {{ payment_comm }} </span>% on collection implemented by us during {{ bill_period_from }} To {{ bill_period_to }} of total amount Rs. <span id="div_total_amount"> {{ total_amount }} </span>/- as per details attached.
                                                </td>
                                                <td class="text-right">
                                                    <span id="commi_total_amount_td"> {{ comm_total_amount }} </span>
                                                </td>
                                            </tr>
                                            <template v-if="company.company_state == 12 && comm_invoice_gst == 1">
                                            <tr id="gujarat_cgst_tr" class="right-nonetd left-nonetd">
                                                <td colspan="2"></td>
                                                <td class="text-left"><b>CGST @ {{ cgst }}%</b></td>
                                                <td class="text-right" id="td_cgst"> {{ cgst_amount }} </td>
                                            </tr>
                                            <tr id="gujarat_sgst_tr" class="right-nonetd left-nonetd">
                                                <td colspan="2"></td>
                                                <td class="text-left"><b>SGST @ {{ sgst }} %</b></td>
                                                <td class="text-right" id="td_sgst"> {{ sgst_amount }} </td>
                                            </tr>
                                            </template>
                                            <tr id="other_gst_tr" class="right-nonetd left-nonetd" v-if="company.company_state != 12 && comm_invoice_gst == 1">
                                                <td colspan="2"></td>
                                                <td class="text-left"><b>IGST @ {{ igst }}%</b></td>
                                                <td class="text-right" id="td_igst"> {{ igst_amount }} </td>
                                            </tr>
                                            <tr class="right-nonetd left-nonetd">
                                                <td colspan=""></td>
                                                <td style="width:50%"></td>
                                                <td class="text-left"><b>Others : </b></td>
                                                <td class="text-right" >
                                                    <span id="others_td"></span>
                                                    <input style="width: 70px;" type="text" class="text-right" v-model="invoice_others" id="invoice_others" @blur="everyLoadChange()">
                                                </td>
                                                <input type="hidden" name="hidden_invoice_others" id="hidden_invoice_others">
                                            </tr>
                                            <tr class="right-nonetd left-nonetd">
                                                <td colspan="2"></td>
                                                <td colspan="" class="text-left"><b>Rounded Off : </b></td>
                                                <td class="text-right">
                                                    <span id="rounded_off_td"> {{ rounded_off }} </span>
                                                </td>
                                            </tr>
                                            <tr class="right-nonetd left-nonetd">
                                                <td colspan="2"></td>
                                                <td colspan="" class="text-left"><b>Total Commission</b></td>
                                                <td id="totalAmount_td" class="text-right"> {{ total_commission }} </td>
                                            </tr>
                                            <tr class="right-nonetd left-nonetd" id="tds_td_tr">
                                                <td colspan="2"></td>
                                                <td colspan="0" class="text-left"><b>Less : TDS Amount</b></td>
                                                <td class="text-right"><span id="tds_td" > {{ tds_amount }} </span></td>
                                            </tr>
                                            <tr class="right-nonetd left-nonetd">
                                                <td colspan="2" id="total_in_words"></td>
                                                <td><b>Net Commission Amount:</b></td>
                                                <td class="text-right"><b><span id="final_amount_td"> {{ final_amount }} </span></b></td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" style="border-bottom:0px; border-top:0px;" >
                                                    <div class="text-right"><b>For <span class="agentLabel">{{ courier_agent.name }}</span></b></div>
                                                </td>
                                            </tr>
                                            <tr class="right-nonetd left-nonetd">
                                                <td colspan="4" style="height:35px"></td>
                                            </tr>
                                            <tr class="right-none left-none">
                                                <td colspan="4" style="border-top:0px;" class="text-right">
                                                <b>Authorised Signatory</b></td>
                                            </tr>
                                        </thead>
                                    </table>
                                    <div class="mt-2 mb-2"><b>Payment Rec. Details</b></div>
                                    <table class="table table-striped mb-0" border="1" id="paymentTable" v-if="scope == 'edit'">
                                        <thead>
                                            <tr>
                                                <th>Sr. No.</th>
                                                <th>Date</th>
                                                <th>Party Name</th>
                                                <th class="text-right">Rec. Amount</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(k, i) in payments" :key="i">
                                                <td> {{ i+1 }} </td>
                                                <td> {{ k.payment_date }} </td>
                                                <td> {{ k.customer_name ? k.customer_name : k.supplier_name }} </td>
                                                <td class="text-right"> {{ k.received_amount }} </td>
                                                <td>
                                                    <template v-if="commission_details.length == 0">
                                                        <a @click="delete_invoice_payment_detail(k.id)" class="btn btn-primary btn-sm mr-2"><em class="icon ni ni-cross"></em></a>
						                				<button type="button" class="btn btn-primary btn-sm" @click="refresh_invoice_payment_detail(k.id, k.payment_id, k.financial_year_id)"><em class="icon ni ni-reload"></em></button>
                                                    </template>
                                                    <template v-else>
                                                        <a onclick="return alert('You can not delete this record?');" class="btn btn-primary">x</a>
                                                    </template>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td><b>Total Amount</b></td>
                                                <td><b> {{ total_amount }} </b></td>
                                                <td></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <table class="table table-striped mb-0" border="1" id="paymentTable" v-else>
                                        <thead>
                                            <tr>
                                                <th>Sr. No.</th>
                                                <th>Date</th>
                                                <th>Party Name</th>
                                                <th>Rec. Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(k, i) in payments" :key="i">
                                                <td> {{ i+1 }} </td>
                                                <td> {{ k.date }} </td>
                                                <td> {{ k.customer_name ? k.customer_name : k.supplier_name }} </td>
                                                <td> {{ k.receipt_amount }} </td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td><b>Total Amount</b></td>
                                                <td><b> {{ total_amount }} </b></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="table-responsive text-center mt-3">
                                    <template v-if="commission_details.length > 0">
                                        You can't update this invoice because commission is made on this invoice <a href="">Click here</a> to view detail.
                                        <br>
                                    </template>
                                    <a href="/account/commission/invoice" class="btn btn-dark mr-2">Cancel</a>
                                    <button class="btn btn-primary" @click="updateInvoice" v-if="scope == 'edit' && commission_details.length == 0">Update</button>
                                    <button class="btn btn-primary" @click="saveInvoice" v-else>Save</button>
                                </div>

                            </div>
                        </div><!-- .card -->
                    </div><!-- .nk-block -->
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import $ from "jquery";
    import Multiselect from 'vue-multiselect';

    export default {
        name: 'generateInvoice',
        components: {
            Multiselect
        },
        props: {
            scope: String,
            id: Number,
        },
        data() {
            return {
                invoice_url: '/account/commission/invoice/view-invoice/' + this.id,
                comm_invoice_gst: 1,
                comm_invoice_tds: 1,
                with_without_gst: 1,
                courier_agent: '',
                payment_comm: 2,
                select_tax: 1,
                agents: [],
                company: '',
                bill: '',
                full_bill_no: '',
                bill_no: '',
                bill_period_from: '',
                bill_period_to: '',
                invoice_bill_date: '',
                total_amount: 0,
                comm_total_amount: 0,
                cgst: 0,
                cgst_amount: 0,
                sgst: 0,
                sgst_amount: 0,
                igst: 0,
                igst_amount: 0,
                tds: 0,
                tds_amount: 0,
                payments: [],
                with_gst_amt: 0,
                without_gst_amt: 0,
                invoice_others: 0,
                rounded_off: 0,
                total_commission: 0,
                final_amount: 0,
                commission_details: [],
            }
        },
        created () {
            if (this.scope == 'edit') {
                axios.get('/account/commission/invoice/get-invoice-data/' + this.id)
                .then(response => {
                    var data = response.data;
                    this.agents = data.agents;
                    this.courier_agent = this.agents.find( _ => _.id == data.invoice_details.agent_id );
                    this.company = (data.invoice_details.supplier_id != 0) ? data.supplier : data.customer;
                    this.comm_invoice_gst = data.invoice_details.service_tax_flag;
                    this.comm_invoice_tds = data.invoice_details.tds_flag;
                    this.hideShowSelect_tax();
                    this.with_without_gst = data.invoice_details.with_without_gst;
                    this.payment_comm = data.invoice_details.commission_percent;
                    this.select_tax = data.invoice_details.tax_class;
                    this.full_bill_no = data.invoice_details.bill_no;
                    this.bill_period_from = data.bill_period_from;
                    this.bill_period_to = data.bill_period_to;
                    this.invoice_bill_date = data.invoice_bill_date;
                    this.total_amount = data.invoice_details.total_payment_received_amount;
                    this.with_gst_amt = data.with_gst_amt;
                    this.without_gst_amt = data.without_gst_amt;
                    this.comm_total_amount = data.invoice_details.commission_amount;
                    this.cgst = data.invoice_details.cgst != 0 ? data.invoice_details.cgst : data.cgst;
                    this.cgst_amount = data.invoice_details.cgst_amount;
                    this.sgst = data.invoice_details.sgst != 0 ? data.invoice_details.sgst : data.sgst;
                    this.sgst_amount = data.invoice_details.sgst_amount;
                    this.igst = data.invoice_details.igst != 0 ? data.invoice_details.igst : data.igst;
                    this.igst_amount = data.invoice_details.igst_amount;
                    this.invoice_others = data.invoice_details.other_amount != 0 ? data.invoice_details.other_amount : 0;
                    this.rounded_off = data.invoice_details.rounded_off;
                    this.total_commission = parseFloat(this.comm_total_amount) + parseFloat(this.cgst_amount) + parseFloat(this.sgst_amount) + parseFloat(this.igst_amount) + parseFloat(this.invoice_others) + parseFloat(this.rounded_off);
                    this.tds_amount = data.invoice_details.tds_amount;
                    this.final_amount = data.invoice_details.final_amount;
                    $('#total_in_words').html('<b>' + this.inWords(this.final_amount) + '</b>');
                    this.payments = data.payment_details;
                });
            } else {
                axios.get('/account/commission/invoice/get-data')
                .then(response => {
                    var data = response.data;
                    this.agents = data.agents;
                    this.courier_agent = data.agents[0];
                    this.company = data.company;
                    this.payments = data.payments;
                    this.bill = data.bill;
                    this.full_bill_no = data.agents[0].inv_prefix + '-' + data.fid_prefix + '-' + data.suffix_bill;
                    this.bill_no = data.suffix_bill;
                    this.bill_period_from = data.bill_period_from;
                    this.bill_period_to = data.bill_period_to;
                    this.invoice_bill_date = data.invoice_bill_date;
                    this.with_gst_amt = this.total_amount = data.total_amount;
                    this.without_gst_amt = this.without_gst_amt;
                    this.comm_total_amount = (data.total_amount * 2) / 100;
                    this.cgst = data.cgst;
                    this.sgst = data.sgst;
                    this.igst = data.igst;
                    this.tds = data.tds;
                    setTimeout(() => {
                        this.everyLoadChange();
                    }, 100);
                });
            }
        },
        methods: {
            everyLoadChange(e) {
                var state_id = this.company.company_state;
                var commission_amount = this.comm_total_amount;
                var other = this.invoice_others;
                var session_tds = this.tds;
                var cgst_percent = this.cgst;
                var sgst_percent = this.sgst;
                var igst_percent = this.igst;
                var result = 0, tds = 0, net_commission = 0, temp_result = 0;
                var select_tax = this.select_tax;
                if (select_tax == 1 || select_tax == 2) {
                    // gst check box condition checking
                    if (this.comm_invoice_gst == 1) {
                        if (state_id == 12) {
                            this.cgst_amount = ((cgst_percent * commission_amount) / 100).toFixed(2);
                            this.sgst_amount = ((sgst_percent * commission_amount) / 100).toFixed(2);
                            result = (+commission_amount) + (+this.cgst_amount) + (+this.sgst_amount) + (+other);
                        } else {
                            this.igst_amount = ((igst_percent * commission_amount) / 100).toFixed(2);
                            result = (+commission_amount) + (+this.igst_amount) + (+other);
                        }
                    } else {
                        this.cgst_amount = this.sgst_amount = this.igst_amount = 0;
                        result = (+commission_amount) + (+other);
                    }
                    var round_result = (result - Math.floor(result)).toFixed(2);
                    if (round_result >= 0.5) {
                        temp_result = Math.ceil(result);
                    } else {
                        temp_result = Math.floor(result);
                    }
                    this.rounded_off = (temp_result - result).toFixed(2);
                    result = temp_result;
                    var total_commission = Math.ceil(result);
                    this.total_commission = total_commission;
                    if (this.comm_invoice_tds == 1) {
                        $('#tds_td_tr').show();
                        if (select_tax == 1) {
                            tds = Math.round((commission_amount * session_tds) / 100);
                        } else if (select_tax == 2) {
                            tds = Math.round((total_commission * session_tds) / 100);
                        }
                        this.tds_amount = tds;
                    } else {
                        this.tds_amount = 0;
                        $('#tds_td_tr').hide();
                    }
                    net_commission = Math.ceil(total_commission - tds);
                    this.final_amount = net_commission;
                    var total_in_word = this.inWords(net_commission);
                    $('#total_in_words').html('<b>' + total_in_word + '</b>');
                } else {
                    tds = Math.round((commission_amount * session_tds) / 100);
                    var total_commission_amount = Math.round(commission_amount - tds);
                    this.tds_amount = tds;
                    this.total_commission = total_commission_amount;
                    if (state_id == 12) {
                        this.cgst_amount = ((cgst_percent * total_commission_amount) / 100).toFixed(2);
                        this.sgst_amount = ((sgst_percent * total_commission_amount) / 100).toFixed(2);
                        result = (+commission_amount) + (+this.cgst_amount) + (+this.sgst_amount) + (+other);
                        net_commission = Math.ceil((+total_commission_amount) + (+this.cgst_amount) + (+this.sgst_amount));

                    } else {
                        this.igst_amount = ((igst_percent * total_commission_amount) / 100).toFixed(2);
                        result = (+total_commission_amount) + (+this.igst_amount) + (+other);
                        net_commission = Math.ceil((+total_commission_amount) + (+this.igst_amount));
                    }
                    this.rounded_off = 0;
                    this.final_amount = net_commission;
                    var total_in_word = this.inWords(net_commission);
                    $('#total_in_words').html('<b>' + total_in_word + '</b>');
                }
            },
            onPercentageChange(e) {
                this.payment_comm = Math.abs(this.payment_comm);
                this.comm_total_amount = ((this.payment_comm * this.total_amount) / 100).toFixed(2);
                setTimeout(() => {
                    this.everyLoadChange();
                }, 100);
            },
            hideShowSelect_tax (e) {
                setTimeout(() => {
                    if (this.comm_invoice_gst == 1 && this.comm_invoice_tds == 1) {
                        $('#select_tax').show('slow/400/fast');
                    } else {
                        $('#select_tax').hide('slow/400/fast');
                    }
                }, 100);
            },
            inWords(num) {
                var a = ['', 'One ', 'Two ', 'Three ', 'Four ', 'Five ', 'Six ', 'Seven ', 'Eight ', 'Nine ', 'Ten ', 'Eleven ', 'Twelve ', 'Thirteen ', 'Fourteen ', 'Fifteen ', 'Sixteen ', 'Seventeen ', 'Eighteen ', 'Nineteen '];
                var b = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];
                if ((num = num.toString()).length > 9) return 'overflow';
                var n = ('000000000' + num).substr(-9).match(/^(\d{2})(\d{2})(\d{2})(\d{1})(\d{2})$/);
                if (!n) return;
                var str = '';
                str += (n[1] != 0) ? (a[Number(n[1])] || b[n[1][0]] + ' ' + a[n[1][1]]) + 'Crore ' : '';
                str += (n[2] != 0) ? (a[Number(n[2])] || b[n[2][0]] + ' ' + a[n[2][1]]) + 'Lakh ' : '';
                str += (n[3] != 0) ? (a[Number(n[3])] || b[n[3][0]] + ' ' + a[n[3][1]]) + 'Thousand ' : '';
                str += (n[4] != 0) ? (a[Number(n[4])] || b[n[4][0]] + ' ' + a[n[4][1]]) + 'Hundred ' : '';
                str += (n[5] != 0) ? ((str != '') ? 'And ' : '') + (a[Number(n[5])] || b[n[5][0]] + ' ' + a[n[5][1]]) + 'Only ' : '';
                return str;
            },
            updateData (e) {
                this.changeAgentForInvoice();
                this.hideShowSelect_tax();
                setTimeout(() => {
                    this.everyLoadChange();
                }, 100);
            },
            changeGST (e) {
                if (this.with_without_gst == 1) {
                    this.total_amount = this.with_gst_amt;
                } else {
                    this.total_amount = this.without_gst_amt;
                }
                this.onPercentageChange();
            },
            changeAgentForInvoice () {
                axios.post('/account/commission/invoice/getInvoiceBillNo/' + this.courier_agent.id,
                { gst: this.comm_invoice_gst })
                .then(response => {
                    this.full_bill_no = response.data;
                });
            },
            saveInvoice () {
                axios.post('/account/commission/invoice/save-invoice', {
                    company: this.company,
                    payments: this.payments,
                    comm_total_amount: this.comm_total_amount,
                    rounded_off: this.rounded_off,
                    final_amount: this.final_amount,
                    invoice_others: this.invoice_others,
                    payment_comm: this.payment_comm,
                    comm_invoice_gst: this.comm_invoice_gst,
                    cgst: this.cgst,
                    cgst_amount: this.cgst_amount,
                    sgst: this.sgst,
                    sgst_amount: this.sgst_amount,
                    igst: this.igst,
                    igst_amount: this.igst_amount,
                    comm_invoice_tds: this.comm_invoice_tds,
                    tds_amount: this.tds_amount,
                    full_bill_no: this.full_bill_no,
                    bill_period_to: this.bill_period_to,
                    bill_period_from: this.bill_period_from,
                    invoice_bill_date: this.invoice_bill_date,
                    with_without_gst: this.with_without_gst,
                    courier_agent: this.courier_agent,
                    total_amount: this.total_amount,
                })
                .then(response => {
                    if (response.data.success == 1) {
                        location.href = '/account/commission/invoice';
                    }
                });
            },
            updateInvoice () {
                axios.post('/account/commission/invoice/update-invoice', {
                    id: this.id,
                    company: this.company,
                    payments: this.payments,
                    comm_total_amount: this.comm_total_amount,
                    rounded_off: this.rounded_off,
                    final_amount: this.final_amount,
                    invoice_others: this.invoice_others,
                    payment_comm: this.payment_comm,
                    comm_invoice_gst: this.comm_invoice_gst,
                    cgst: this.cgst,
                    cgst_amount: this.cgst_amount,
                    sgst: this.sgst,
                    sgst_amount: this.sgst_amount,
                    igst: this.igst,
                    igst_amount: this.igst_amount,
                    comm_invoice_tds: this.comm_invoice_tds,
                    tds_amount: this.tds_amount,
                    full_bill_no: this.full_bill_no,
                    bill_period_to: this.bill_period_to,
                    bill_period_from: this.bill_period_from,
                    invoice_bill_date: this.invoice_bill_date,
                    with_without_gst: this.with_without_gst,
                    courier_agent: this.courier_agent,
                    total_amount: this.total_amount,
                    select_tax: this.select_tax
                })
                .then(response => {
                    if (response.data.success == 1) {
                        location.href = '/account/commission/invoice';
                    }
                });
            },
            delete_invoice_payment_detail(invoice_payment_detail_id) {
                if (confirm('Are you sure to delete?')) {
                    axios.post('/account/commission/invoice/delete-invoice-payment-detail', {
                        invoice_id: this.id,
                        invoice_payment_detail_id: invoice_payment_detail_id
                    })
                    .then(response => {
                        if (response.data.success == 1) {
                            location.reload();
                        }
                    });
                }
            },
            refresh_invoice_payment_detail(invoice_payment_detail_id, payment_id, financial_year_id) {
                axios.post('/account/commission/invoice/refresh-invoice-payment-detail', {
                    invoice_payment_detail_id: invoice_payment_detail_id, invoice_id: this.id, payment_id: payment_id, financial_year_id: financial_year_id, amount: this.total_amount
                })
                .then(response => {
                    if (response.data.success == 1) {
                        location.reload();
                    }
                });
            },
        },
        mounted() {
            // const self = this;

            $(document).on('click', '#paymentTable tbody tr', function(e) {
                // e.preventDefault();
            });
        },
    };
</script>
<style scoped>
    table {
        font-family: 'Roboto', 'Noto', sans-serif;
    }
    #commission_invoice_print input {
        border: 0;
        background: transparent;
    }
    .right-none th {
        border-right:0px !important;
    }
    .left-none th {
        border-left:0px !important;
    }
    .right-nonetd td {
        border-right:0px !important;
        border-bottom:0px !important;
        border-top:0px !important;
    }
    .left-nonetd td {
        border-left:0px !important;
        border-bottom:0px !important;
        border-top:0px !important;
    }
    #divlogo {
        float:left;
        margin-left: 46%;
    }
    th, td {
        color:#58666e;
        font-size: 13px;
    }
    .table td:first-child, .table th:first-child {
        padding-left: 0.5rem;
    }
</style>
