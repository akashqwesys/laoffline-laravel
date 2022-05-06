<template>
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                       <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3  class="nk-block-title page-title">View Commission Detail</h3>
                            </div><!-- .nk-block-head-content -->
                        </div><!-- .nk-block-between --> 
                    </div><!-- .nk-block-head -->
                    <div class="nk-block">
                        <div class="card card-bordered card-stretch">
                            <div class="card-inner">
                            <div class="print_area">
                                <div class="row">
                                    <div class="col-sm-4">
				    			        <label class="control-label"><b>iuid : </b> {{ paymentData.reciept_mode}} </label>
				    		        </div>
                                    <div class="col-sm-4">
				    			        <label class="control-label"><b>Generated Date : </b> {{ created_at }} </label>
				    		        </div>
                                    <div class="col-sm-4">
				    			        <label class="control-label"><b>Supplier : </b> {{ supplier.company_name}} </label>
				    		        </div>
                                    
                                    <div class="col-sm-4">
				    			        <label class="control-label"><b>Commission Date : </b> {{ paymentData.date}} </label>
				    		        </div>
                                    <div class="col-sm-4">
				    			        <label class="control-label"><b>Commission Account : </b> {{ paymentData.date}} </label>
				    		        </div>
                                    <div class="col-sm-4">
				    			        <label class="control-label"><b>Recipt Mode : </b> {{ paymentData.date}} </label>
				    		        </div>
                                    <div class="col-sm-4">
				    			        <label class="control-label"><b>Deposite Bank : </b>  </label>
				    		        </div>
                                    <div class="col-sm-4 cheque">
				    			        <label class="control-label"><b>Cheque Date : </b> {{ paymentData.cheque_date}} </label>
				    		        </div>
                                    <div class="col-sm-4 cheque">
				    			        <label class="control-label"><b>Cheque No : </b> {{ paymentData.cheque_dd_no}} </label>
				    		        </div>
                                    <div class="col-sm-4">
				    			        <label class="control-label"><b>Amount : </b> {{ paymentData.receipt_amount }} </label>
				    		        </div>
                                    <div class="col-sm-4 cheque">
				    			        <label class="control-label"><b>Cheque Bank : </b> {{ paymentData.cheque_dd_bank}} </label>
				    		        </div>
                                    <div class="col-sm-4">
				    			        <label class="control-label"><b>Extra Attachment : </b>  </label>
				    		        </div>
                                    
                                </div>
                                <div class="row table-responsive">
                                    <h6>Invoice Details</h6>
                                    <table class="table mb-2 table table-striped m-b-none salebilltable">
                                                <thead>
                                                    <tr>
                                                        <th>Bill No</th>
                                                        <th>Date</th>
                                                        <th>Received Commission	</th>
                                                        <th>Status</th>
                                                        <th>Remark</th>
                                                        <th>Action</th>
							        		        </tr>
                                                </thead>
                                                <tbody>
                                                    <tr class="salebillrow" v-for="(salebill,index) in salebills" :key="index">
                                                        <td>{{ salebill.sr_no}}</td>
                                                        <td>{{ salebill.supplier_invoice_no}}</td>
                                                        <td>{{ salebill.amount}}</td>
                                                        <td>{{ salebill.adjust_amount}}</td>
                                                        <td v-if="salebill.status = '1'">Completed</td><td v-else>Pending</td>
                                                        <td>Action</td>
                                                    </tr>
                                                </tbody>
                                                <tfoot class="total">
                                                    <tr>
                                                        <td><b>Grand Total :</b></td>
                                                        <td></td>
                                                        <td><b>{{ paymentData.total_amount }}</b></td>
                                                        <td></td>
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
                salebills: [],
                customer: [],
                supplier: [],
                created_at: '', 
            }
        },
        created() {
             axios.get(`/commission/fetch-commission/${this.id}`)
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
                });    
        },
        methods: {
        
        },
        mounted() {
            
        },
    };
</script>
<style >
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
