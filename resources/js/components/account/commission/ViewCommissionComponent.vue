<template>
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">View Commission Detail</h3>
                            </div><!-- .nk-block-head-content -->
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    <div class="nk-block">
                        <div class="card card-bordered card-stretch">
                            <div class="card-inner">
                                <div class="print_area">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label class="control-label"><b>iuid : </b> {{ commission.iuid }} </label>
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="control-label"><b>Generated Date : </b> {{ created_at }}
                                            </label>
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="control-label" v-if="customer == null"><b>Supplier : </b> {{ supplier.company_name}}
                                            </label>
                                            <label class="control-label" v-else><b>Customer : </b> {{ customer.company_name}}
                                            </label>
                                        </div>

                                        <div class="col-sm-4">
                                            <label class="control-label"><b>Commission Date : </b> {{
                                                commission.commission_date}} </label>
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="control-label"><b>Commission Account : </b> {{
                                                commission.commissionaccount ? commission.commissionaccount.name : '' }}
                                            </label>
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="control-label"><b>Recipt Mode : </b> {{
                                                commission.commission_reciept_mode }} </label>
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="control-label"><b>Deposite Bank : </b> {{
                                                commission.depositebank ? commission.depositebank.name : '' }} </label>
                                        </div>
                                        <div class="col-sm-4 cheque">
                                            <label class="control-label"><b>Cheque Date : </b> {{
                                                commission.cheque_date}} </label>
                                        </div>
                                        <div class="col-sm-4 cheque">
                                            <label class="control-label"><b>Cheque No : </b> {{
                                                commission.cheque_dd_no}} </label>
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="control-label"><b>Amount : </b> {{
                                                commission.commission_payment_amount }} </label>
                                        </div>
                                        <div class="col-sm-4 cheque">
                                            <label class="control-label"><b>Cheque Bank : </b> {{
                                                commission.chequebank ? commission.chequebank.name : ''}} </label>
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="control-label"><b>Extra Attachment : </b> </label>
                                            <span v-if="attachments != ''">
                                                <a :href="'/upload/commission/'+attachments" target="_blank">
                                                    <img height="65" width="50" id="preview-img"
                                                        src="/assets/images/icons/file-media.svg"
                                                        style="opacity: 0.5; padding-top: 5px;">
                                                </a>
                                            </span>
                                            <span v-else> - </span>
                                        </div>

                                    </div>
                                    <div class="row table-responsive">
                                        <h6>Invoice Details</h6>
                                        <table class="table mb-2 table table-striped m-b-none salebilltable">
                                            <thead>
                                                <tr>
                                                    <th>Bill No</th>
                                                    <th>Date</th>
                                                    <th>Received Commission </th>
                                                    <th>Status</th>
                                                    <th>Remark</th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr class="salebillrow"
                                                    v-for="(commission_invoice,index) in commissioninvoice"
                                                    :key="index">
                                                    <td><a :href="'/account/commission/invoice/view-invoice/' + commission_invoice.commission_id + '?fid=' + commission_invoice.fid"
                                                            target="_blank"> {{ commission_invoice.invoiceno }}</a></td>
                                                    <td>{{ commission_invoice.date}}</td>
                                                    <td>{{ commission_invoice.recivedCommission.totalrecived}}</td>
                                                    <td>{{ commission_invoice.status.status }}</td>
                                                    <td>{{ commission_invoice.remark}}</td>
                                                </tr>
                                            </tbody>
                                            <tfoot class="total">
                                                <tr>
                                                    <td><b>Grand Total :</b></td>
                                                    <td></td>
                                                    <td><b>{{ totalrecivedcommission }}</b></td>
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
            fid: Number,
        },
        data() {
            return {
                commission: [],
                commissioninvoice: [],
                customer: [],
                supplier: [],
                attachments: '',
                created_at: '',
                totalrecivedcommission: '',
            }
        },
        created() {
            const toINR = new Intl.NumberFormat('en-IN', {
                style: 'currency',
                currency: 'INR',
                minimumFractionDigits: 0
            });
             axios.get(`/commission/fetch-commission/${this.id}/${this.fid}`)
                .then(response => {
                        gData = response.data;
                        let total = 0;
                        this.commission = gData.commission;
                        this.commissioninvoice = gData.commissioninvoice;
                        this.customer = gData.customer;
                        this.supplier = gData.supplier;
                        this.created_at = gData.created_at;
                        this.attachments = gData.commission.attachment;
                        this.commissioninvoice.forEach( value => {
                            total += value.amount;
                        });
                        this.totalrecivedcommission = total;
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
