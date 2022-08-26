<template>
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">View Payment Detail</h3>
                            </div><!-- .nk-block-head-content -->
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    <div class="nk-block">
                        <div class="card card-bordered card-stretch">
                            <div class="card-inner">
                                <div class="print_area">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label class="control-label"><b>Reciept Mode : </b> {{
                                            paymentData.reciept_mode}} </label>
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="control-label"><b>IUID : </b> {{ paymentData.iuid}} </label>
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="control-label"><b>Generated Date : </b> {{ created_at }}
                                            </label>
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="control-label"><b>Voucher No : </b> {{
                                            paymentData.payment_id}} </label>
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="control-label"><b>Date : </b> {{ paymentData.date}} </label>
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="control-label"><b>Deposite Bank : </b> CHQ IN HAND </label>
                                        </div>
                                        <div class="col-sm-4 cheque">
                                            <label class="control-label"><b>Cheque Date : </b> {{
                                            paymentData.cheque_date}} </label>
                                        </div>
                                        <div class="col-sm-4 cheque">
                                            <label class="control-label"><b>Cheque / DD No : </b> {{
                                            paymentData.cheque_dd_no}} </label>
                                        </div>
                                        <div class="col-sm-4 cheque">
                                            <label class="control-label"><b>Cheque / DD's Bank : </b> {{
                                            paymentData.cheque_dd_bank}} </label>
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="control-label"><b>Receipt From : </b> {{ customer.company_name
                                            }} </label>
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="control-label"><b>Trns : </b> {{ paymentData.trns}} </label>
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="control-label"><b>Supplier : </b> {{ supplier.company_name}}
                                            </label>
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="control-label"><b>Receipt Amount : </b> {{
                                            paymentData.receipt_amount }} </label>
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="control-label"><b>Letter Attachment : </b> </label>
                                            <!-- <span v-if="paymentData.letter_attachment">
                                        <a :href="'/upload/payments/'+paymentData.letter_attachment" target="_blank">
                                            <img height="65" width="50" id="preview-img" src="/assets/images/icons/file-media.svg" style="opacity: 0.5; padding-top: 5px;">
                                        </a>
                                        </span> -->
                                            <!-- <span v-else> - </span> -->
                                            <ul>
                                                <li v-for="(attachment,index) in paymentData.letter_image"
                                                    :key="index">
                                                    <a v-if="attachment != ''" :href="'/upload/payments/'+attachment"
                                                        target="_blank">
                                                        <img height="65" width="50" id="preview-img"
                                                            src="/assets/images/icons/file-media.svg"
                                                            style="opacity: 0.5; padding-top: 5px;">
                                                    </a>
                                                    <span v-else>-</span>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="control-label"><b>Cheques Attachment : </b> </label>
                                            <ul>
                                                <li v-for="(attachment,index) in attachments" :key="index">
                                                    <a v-if="attachment != ''" :href="'/upload/payments/'+attachment"
                                                        target="_blank">
                                                        <img height="65" width="50" id="preview-img"
                                                            src="/assets/images/icons/file-media.svg"
                                                            style="opacity: 0.5; padding-top: 5px;">
                                                    </a>
                                                    <span v-else>-</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="row table-responsive">
                                        <table class="table mb-2 table table-striped m-b-none salebilltable">
                                            <thead>
                                                <tr>
                                                    <th>Bill Date</th>
                                                    <th>Day</th>
                                                    <th>S.No.</th>
                                                    <th>Sup Inv No</th>
                                                    <th>Amount</th>
                                                    <th>Adjust Amount</th>
                                                    <th>Status</th>
                                                    <th>Discount(%)</th>
                                                    <th>Discount Amount</th>
                                                    <th>Goods Return</th>
                                                    <th>Rate Difference</th>
                                                    <th>Bank Commission</th>
                                                    <th>Vatav</th>
                                                    <th>Agent Commission</th>
                                                    <th>Claim</th>
                                                    <th>Short</th>
                                                    <th>Interest</th>
                                                    <th>Remark</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr class="salebillrow" v-for="(salebill,index) in salebills"
                                                    :key="index">
                                                    <td>{{ salebill.bill_date}}</td>
                                                    <td>{{ salebill.day}}</td>
                                                    <td>{{ salebill.id}}</td>
                                                    <td><a
                                                            :href="'/account/sale-bill/view-sale-bill/'+ salebill.id+'/'+ salebill.fid">{{
                                                            salebill.sup_inv}}</a></td>
                                                    <td>{{ salebill.amount}}</td>
                                                    <td>{{ salebill.adjustamount}}</td>
                                                    <td>{{ salebill.status.status}}</td>
                                                    <td>{{ salebill.discount }}</td>
                                                    <td>{{ salebill.discountamount }}</td>
                                                    <td>{{ salebill.goodreturn }}</td>
                                                    <td>{{ salebill.ratedifference }}</td>
                                                    <td>{{ salebill.bankcommission }}</td>
                                                    <td>{{ salebill.vatav }}</td>
                                                    <td>{{ salebill.agentcommission }}</td>
                                                    <td>{{ salebill.claim }}</td>
                                                    <td>{{ salebill.short }}</td>
                                                    <td>{{ salebill.interest }}</td>
                                                    <td>{{ salebill.remark }}</td>
                                                    <td v-if="salebill.goodreturn != 0"><a
                                                            :href="'/payments/view-goodreturn/'+ salebill.goodreturndata.goods_return_id">View
                                                            GR</a></td>
                                                    <td v-else></td>
                                                </tr>
                                            </tbody>
                                            <tfoot class="total">
                                                <tr>
                                                    <td><b>Total</b></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td><b>{{ paymentData.total_amount }}</b></td>
                                                    <td><b>{{ paymentData.tot_adjust_amount }}</b></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td><b>{{ paymentData.tot_discount }}</b></td>
                                                    <td><b>{{ paymentData.tot_good_returns }}</b></td>
                                                    <td><b>{{ paymentData.tot_rate_difference }}</b></td>
                                                    <td><b>{{ paymentData.tot_bank_cpmmission }}</b></td>
                                                    <td><b>{{ paymentData.tot_vatav }}</b></td>
                                                    <td><b>{{ paymentData.tot_agent_commission }}</b></td>
                                                    <td><b>{{ paymentData.tot_claim }}</b></td>
                                                    <td><b>{{ paymentData.tot_short }}</b></td>
                                                    <td><b>{{ paymentData.tot_interest }}</b></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div><!-- .card -->
                        </div>
                    </div><!-- .nk-block -->
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import 'jquery/dist/jquery.min.js';
    import 'datatables.net-bs5/js/dataTables.bootstrap5';
    import 'datatables.net-responsive-bs4/js/responsive.bootstrap4';
    import "datatables.net-buttons-bs5/js/buttons.bootstrap5";
    import "datatables.net-buttons/js/buttons.flash.js";
    import "datatables.net-buttons/js/buttons.html5.js";
    import "datatables.net-buttons/js/buttons.print.js";
    import $ from 'jquery';

    var gData = [];
    export default {
        name: 'payment',
        props: {
            id: Number,
        },
        data() {
            return {
                paymentData: [],
                attachments: [],
                salebills: [],
                customer: [],
                supplier: [],
                created_at: '',
            }
        },
        created() {
             axios.get(`/payments/fetch-payment/${this.id}`)
                .then(response => {

                        gData = response.data;
                        if (gData.paymentData.reciept_mode == 'cash') {
                            $(".cash").removeClass("d-none");
                            $(".cheque").addClass("d-none");
                            $(".table-responsive").addClass("salebilltable");
                        } else if (gData.paymentData.reciept_mode == 'cheque') {
                            $(".cash").removeClass("d-none");
                            $(".cheque").removeClass("d-none");
                            $(".table-responsive").addClass("salebilltable");
                        } else if (gData.paymentData.reciept_mode == 'fullreturn' || gData.paymentData.reciept_mode == 'partreturn') {
                            $(".cheque").addClass("d-none");
                            $(".cash").addClass("d-none");
                            $(".table-responsive").removeClass("salebilltable");
                        }
                        this.paymentData = gData.paymentData;
                        this.salebills = gData.salebill;
                        this.customer = gData.customer;
                        this.supplier = gData.supplier;
                        this.created_at = gData.created_at;
                        this.attachments = gData.paymentData.cheque_image;
                });
        },
        methods: {

        },
        mounted() {

        },
    };
</script>
<style scoped>
    .user-avatar img{
        width: 100%;
    }
    .dataTables_filter {
        display: flex;
        margin-bottom: 15px;
    }
    .dataTables_filter input {
        padding: 17px;
        margin: 0 10px;
        width: 25%;
    }
    .dt-buttons {
        position: relative;
        display: inline-flex;
        vertical-align: middle;
        flex-wrap: wrap;
        float: right;
    }
    .dt-buttons .dt-button {
        position: relative;
        flex: 1 1 auto;
        display: inline-flex;
        font-family: Nunito, sans-serif;
        font-weight: 700;
        color: #526484;
        text-align: center;
        vertical-align: middle;
        user-select: none;
        background-color: transparent;
        border: 1px solid #dbdfea;
        padding: 0.4375rem 0;
        font-size: 0.8125rem;
        line-height: 1.25rem;
        border-radius: 4px;
        transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }
    .dt-buttons .dt-button::before {
        font-size: 1.125rem;
        font-weight: normal;
        font-style: normal;
        width: 2.125rem;
        font-family: "Nioicon";
    }
    /* .dt-buttons .dt-button span {
        display: none;
    } */
    .dataTables_paginate {
        display: flex;
        padding-left: 0;
        list-style: none;
        border-radius: 4px;
        margin: 2px 0;
        justify-content: flex-end;
    }
    .dataTables_paginate .paginate_button.disabled,
    .dataTables_paginate .paginate_button.disabled {
        color: #dbdfea;
        pointer-events: none;
        background-color: #fff;
        border-color: #e5e9f2;
    }
    .dataTables_paginate .paginate_button.first,
    .dataTables_paginate .paginate_button.previous,
    .dataTables_paginate .paginate_button.next,
    .dataTables_paginate .paginate_button.last {
        margin-left: 0;
        border-top-left-radius: 4px;
        border-bottom-left-radius: 4px;
    }
    .dataTables_paginate .paginate_button {
        font-size: 0.8125rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: calc(1rem + 1.125rem + 2px);
        position: relative;
        padding: 0.5625rem 0.625rem;
        line-height: 1rem;
        border: 1px solid #e5e9f2;
        cursor: pointer;
    }
    .dataTables_paginate .paginate_button.current {
        z-index: 3;
        color: #fff;
        background-color: #6576ff;
        border-color: #6576ff;
    }
</style>
