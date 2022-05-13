<template>
    <div id="overlay" class="loader-wrap">
        <div>
            <div>
                <img src="/assets/images/loader_3.gif" class=""  alt="">
            </div>
        </div>
    </div>
    <!-- Each sheet element should have the class "sheet" -->
    <!-- "padding-**mm" is optional: you can set 10, 15, 20 or 25 -->
    <section class="sheet">
        <!-- Write HTML just like a web page -->
        <article>
            <div class="logo">
                <img src="/assets/images/logo_report.png" alt="Logo">
            </div>
            <div class="header-box">
                <div class="section-left">
                    <h5 class="sp-title">Supplier</h5>
                    <table class="tb-ct">
                        <thead>
                            <tr>
                                <th colspan="2">{{ courier_agent.name }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="courier_agent.pan_no">
                                <td>PAN</td>
                                <td>{{ courier_agent.pan_no }}</td>
                            </tr>
                            <tr v-if="courier_agent.gst_no && invoice_details.service_tax_amount == 0 && invoice_details.service_tax_flag == 1">
                                <td>GSTIN</td>
                                <td>{{ courier_agent.gst_no }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="section-right">
                    <h2 class="tax_title">{{ (invoice_details.service_tax_flag == 1) ? 'TAX INVOICE' : 'INVOICE' }} </h2>
                </div>
            </div>
            <div class="clearfix"></div>
            <hr>
            <div class="header-box">
                <div class="section-left section-title">
                    <h5 style="text-decoration: underline;">Recipient</h5>
                    <h5>{{ company.company_name }}</h5>
                    <p>
                        {{ company.address }} <br> State: {{ company.state_name }}
                    </p>
                        <p class="gst-box" v-if="invoice_details.service_tax_flag == 1 && invoice_details.service_tax_amount == 0">GSTIN &nbsp;&nbsp; {{ company.gst_no }}</p>
                </div>
                <div class="section-right">
                    <table style="float: right;" class="tb-ct tb-ct-bill">
                        <tbody>
                            <tr>
                                <td>BILL NO</td>
                                <td> {{ invoice_details.bill_no }} </td>
                            </tr>
                            <tr>
                                <td>BILL PERIOD</td>
                                <td> {{ bill_period_from }} TO {{ bill_period_to }} </td>
                            </tr>
                            <tr>
                                <td>BILL DATE</td>
                                <td> {{ invoice_bill_date }} </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="clearfix"></div>
            <table class="invoice-table">
                <thead>
                    <tr>
                        <th style="border-right: 0">SR NO.</th>
                        <th style="border-left: 0">PARTICULARS</th>
                        <th>AMOUNT</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Commission @ {{ invoice_details.commission_percent }}% on collection implemented by us during {{ bill_period_from }} to {{ bill_period_to }} of total amount Rs. {{ invoice_details.total_payment_received_amount }}/-
                        <template v-if="invoice_details.service_tax_flag == 1 && invoice_details.service_tax_amount == 0">
                        <br>
                        <p class="sac">SAC &nbsp;&nbsp; 9961</p>
                        </template>
                        </td>
                        <td>₹&nbsp;{{ toINR.format(invoice_details.commission_amount) }}</td>
                    </tr>
                    <tr v-if="invoice_details.service_tax != 0 && invoice_details.service_tax_flag != 0">
                        <td></td>
                        <td class="text-right">Service Tax</td>
                        <td>₹&nbsp;{{ toINR.format(invoice_details.service_tax_amount) }} </td>
                    </tr>
                    <tr v-if="invoice_details.cgst != '' && invoice_details.cgst != 0">
                        <td></td>
                        <td class="text-right">CGST @ {{ invoice_details.cgst }}%</td>
                        <td>₹&nbsp;{{ toINR.format(invoice_details.cgst_amount) }} </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td class="text-right">SGST @ {{ invoice_details.sgst }}%</td>
                        <td>₹&nbsp;{{ toINR.format(invoice_details.sgst_amount) }} </td>
                    </tr>
                    <tr v-if="invoice_details.igst != '' && invoice_details.igst != 0">
                        <td></td>
                        <td class="text-right">IGST @ {{ invoice_details.igst }}%</td>
                        <td>₹&nbsp;{{ toINR.format(invoice_details.igst_amount) }} </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td class="text-right">Others</td>
                        <td>₹&nbsp;{{ toINR.format(invoice_details.other_amount) }} </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td class="text-right">Rounded Off</td>
                        <td>₹&nbsp;{{ invoice_details.rounded_off }}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td class="text-right">Total Commission</td>
                        <td>₹
                            <template v-if="invoice_details.tax_class == 3">
                                {{ toINR.format(Math.round(parseFloat(invoice_details.commission_amount) - parseFloat(invoice_details.tds_amount))) }}
                            </template>
                            <template v-else>
                                {{
                                    toINR.format(
                                        Math.round(
                                            parseFloat(invoice_details.commission_amount) +
                                            parseFloat(invoice_details.cgst_amount) +
                                            parseFloat(invoice_details.sgst_amount) +
                                            parseFloat(invoice_details.igst_amount) +
                                            parseFloat(invoice_details.other_amount == '' ? 0 : invoice_details.other_amount) +
                                            parseFloat(invoice_details.rounded_off)
                                        )
                                    )
                                }}
                            </template>
                        </td>
                    </tr>
                    <tr v-if="invoice_details.tds_flag == 1">
                        <td></td>
                        <td class="text-right">Less TDS</td>
                        <td>₹&nbsp;{{ toINR.format(invoice_details.tds_amount) }} </td>
                    </tr>
                    <tr>
                        <td></td><td></td><td></td>
                    </tr>
                    <tr>
                        <td></td><td></td><td></td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr v-if="invoice_details.service_tax_flag == 1">
                        <td style="border-top: 1px solid #222"></td>
                        <td class="tax-pay" style="border-top: 1px solid #222">Tax payable on Reverse Charge: <span>Yes</span>/No. </td>
                        <td style="border-top: 0"></td>
                    </tr>
                    <tr>
                        <td style="border-top: 1px solid #222"></td>
                        <td style="border-top: 1px solid #222" id="total_in_words"></td>
                        <td>₹ <span> {{ toINR.format(invoice_details.final_amount) }} </span> </td>
                    </tr>
                </tfoot>
            </table>
            <div class="clearfix"></div>
            <div class="header-box text-center" style="padding-top: 10px">
                <div class="section-left title-sign">
                    <h5>Checked By.</h5>
                </div>
                <div class="section-right title-sign">
                    <h5>For {{ courier_agent.name }} <br> <span>( Authorized Signature )</span></h5>
                </div>
            </div>
            <footer>
              <p>© LLAVESH AGARWAL TEXTILE AGENCY</p>
              <p>info@llaveshagarwal.com &nbsp; | &nbsp; 0261-6770770 &nbsp; | &nbsp; www.llaveshagarwal.com</p>
              <p>Utsav House, 2/1399-1400, Hanuman Sheri, Sagrampura, Surat 395002, GUJARAT.</p>
            </footer>
        </article>
    </section>
    <section class="sheet">
        <!-- Write HTML just like a web page -->
        <article style="letter-spacing: 0px;">
            <table width="80%" style="margin: auto;">
                <caption><b>PAYMENT RECEIVED DETAILS</b><br><br></caption>
                <thead>
                    <tr style="border-top: 1px dotted gray; border-bottom: 1px dotted gray;">
                        <th width="10%" style="border-right: 0">SR NO.</th>
                        <th width="20%" style="border-left: 0">Date</th>
                        <th width="50%" style="border-left: 0" align="left">Party Name</th>
                        <th width="20%" style="border-left: 0" align="right">Rec. Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <template v-for="(k, i) in payments" :key="i">
                        <template v-if="i % 25 == 0" >
                        <template v-html="append_end_start"></template>
                        </template>
                        <tr>
                            <td> {{ i+1 }} </td>
                            <td> {{ k.date }} </td>
                            <td align="left"> {{ k.customer_name ? k.customer_name : k.supplier_name }} </td>
                            <td align="right"> {{ toINR.format(k.received_amount) }} </td>
                        </tr>
                    </template>
                </tbody>
                <tfoot>
                    <tr style="border-top: 1px dotted gray;">
                        <td></td>
                        <td></td>
                        <td align="right"><b>Total: </b></td>
                        <td align="right"><b></b> {{ toINR.format(with_gst_amt) }} </td>
                    </tr>
                </tfoot>
            </table>
            <div class="clearfix"></div>
        </article>
    </section>

</template>

<script>
    import $ from "jquery";

    export default {
        name: 'printInvoice',
        components: {
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
                service_tax_amount: 0,
                invoice_details: [],
                toINR: new Intl.NumberFormat('en-IN', {
                    // style: 'currency',
                    // currency: 'INR',
                    // minimumFractionDigits: 0
                }),
                append_end_start: `</tbody>
                    </table>
                    </article>
                    </section>

                    <section class="sheet">
                        <!-- Write HTML just like a web page -->
                        <article style="letter-spacing: 0px;">
                                <table width="80%" style="margin: auto;">
                                    <caption><b>PAYMENT RECEIVED DETAILS</b><br><br></caption>
                                    <thead>
                                        <tr style="border-top: 1px dotted gray; border-bottom: 1px dotted gray;">
                                            <th width="10%" style="border-right: 0">SR NO.</th>
                                            <th width="20%" style="border-left: 0">Date</th>
                                            <th width="50%" style="border-left: 0" align="left">Party Name</th>
                                            <th width="20%" style="border-left: 0" align="right">Rec.Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>`,
            }
        },
        created () {
            axios.get('/account/commission/invoice/get-invoice-data/' + this.id)
            .then(response => {
                var data = response.data;
                this.agents = data.agents;
                this.courier_agent = this.agents.find( _ => _.id == data.invoice_details.agent_id );
                this.company = (data.invoice_details.supplier_id != 0) ? data.supplier : data.customer;
                this.invoice_details = data.invoice_details;
                this.comm_invoice_gst = data.invoice_details.service_tax_flag;
                this.service_tax_amount = data.invoice_details.service_tax_amount;
                this.comm_invoice_tds = data.invoice_details.tds_flag;
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
                this.total_commission = this.comm_total_amount + this.cgst_amount + this.sgst_amount + this.igst_amount + this.invoice_others + this.rounded_off;
                this.tds_amount = data.invoice_details.tds_amount;
                this.final_amount = data.invoice_details.final_amount;
                $('#total_in_words').html('<b>' + this.inWords(this.final_amount) + '</b>');
                this.payments = data.payment_details;
            });
        },
        methods: {
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
        },
        mounted() {
            // const self = this;

            $(document).on('click', '#paymentTable tbody tr', function(e) {
                // e.preventDefault();
            });
        },
    };
</script>
