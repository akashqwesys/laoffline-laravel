<template>
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                       <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3  class="nk-block-title page-title">View Payment Detail</h3>
                            </div><!-- .nk-block-head-content -->
                        </div><!-- .nk-block-between --> 
                    </div><!-- .nk-block-head -->
                    <div class="nk-block">
                        <div class="card card-bordered card-stretch">
                            <div class="card-inner">
                            <div class="print_area">
                                <div class="form-group">
							        <div class="col-sm-12 text-center">
								        <img src="https://laoffline.com/img/logo_report.png" style="width: 150px;margin-bottom:20px">
							        </div>
						        </div>
                                <div style="float:left; width:100%; border:1px solid">
							        <table class="table table-striped m-b-none">
								        <tbody>
                                            <tr>
									            <th colspan="5" class="text-center">PAYMENT VOUCHER</th>
								            </tr>
											<tr>
									            <th colspan="5" class="text-center">{{ customer.company_name }}</th>
            								</tr>
											<tr>
				            					<th colspan="5" class="text-center">FOR {{ supplier.company_name }}</th>
							            	</tr>
								            <tr>
                                                <td><b>VOUCHER NO: </b>{{ this.id }}</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td class="text-right"><b>DATE: </b>{{ paymentData.date }}</td>
								            </tr>
            								<tr>
			            						<td colspan="5"><b>REF NO: </b>{{ paymentData.reference_id }}</td>
						            		</tr>
							            </tbody>
                                </table>

							    <table class="table table-striped m-b-none" id="div_table">
								    <tbody>
                                        <tr>
                                            <td><b>Bill No</b></td>
                                            <td><b>Bill Date</b></td>
                                            <td><b>Bill Amount</b></td>
                                            <td v-if="paymentData.tot_discount != 0"><b>Discount</b></td>
									        <td v-if="paymentData.tot_vatav != 0"><b>Vatav</b></td>
									        <td v-if="paymentData.tot_agent_commission != 0"><b>Agent Commission</b></td>
									        <td v-if="paymentData.tot_bank_cpmmission != 0"><b>Bank Commission</b></td>
									        <td v-if="paymentData.tot_claim != 0"><b>Claim</b></td>
									        <td v-if="paymentData.tot_good_returns != 0"><b>Goods Return</b></td>
									        <td v-if="paymentData.tot_short != 0"><b>Short</b></td>
									        <td v-if="paymentData.tot_interest != 0"><b>Interest</b></td>
									        <td v-if="paymentData.tot_rate_difference != 0"><b>Rate Difference</b></td>
                                            <td><b>Net Amount</b></td>
		        							<td><b>Days</b></td>
				        				</tr>
                                        <tr v-for="(salebill,index) in salebills" :key="index">
                                            <td>{{ salebill.sr_no }}</td>
                                            <td>{{ paymentData.date }}</td>
                                            <td>{{ salebill.amount }}</td>
                                            <td v-if="paymentData.tot_discount != 0">{{ salebill.discount_amount }}</td>
									        <td v-if="paymentData.tot_vatav != 0">{{ salebill.vatav }}</td>
									        <td v-if="paymentData.tot_agent_commission != 0">{{ salebill.agent_commission }}</td>
									        <td v-if="paymentData.tot_bank_cpmmission != 0">{{ salebill.bank_commission }}</td>
									        <td v-if="paymentData.tot_claim != 0">{{ salebill.claim }}</td>
									        <td v-if="paymentData.tot_good_returns != 0">{{ salebill.goods_return }}</td>
									        <td v-if="paymentData.tot_short != 0">{{ salebill.short }}</td>
									        <td v-if="paymentData.tot_interest != 0">{{ salebill.interest }}</td>
									        <td v-if="paymentData.tot_rate_difference != 0">{{ salebill.rate_difference }}</td>
        									<td>{{ salebill.amount - salebill.discount_amount - salebill.vatav - salebill.goods_return - salebill.agent_commission - salebill.bank_commission - salebill.claim - salebill.short - salebill.interest - salebill.rate_difference }}</td>
		        							<td>{{ salebill.day }}</td>
				        				</tr>
                                        <tr>
                                            <td><b>Total</b></td>
                                            <td></td>
                                            <td><b>{{ paymentData.total_amount }}</b></td>
                                            <td v-if="paymentData.tot_discount != 0"><b>{{ paymentData.tot_discount }}</b></td>
                                            <td v-if="paymentData.tot_vatav != 0"><b>{{ paymentData.tot_vatav }}</b></td>
									        <td v-if="paymentData.tot_agent_commission != 0"><b>{{ paymentData.tot_agent_commission }}</b></td>
									        <td v-if="paymentData.tot_bank_cpmmission != 0"><b>{{ paymentData.tot_bank_cpmmission }}</b></td>
									        <td v-if="paymentData.tot_claim != 0"><b>{{ paymentData.tot_claim }}</b></td>
                                            <td v-if="paymentData.tot_good_returns != 0"><b>{{ paymentData.tot_good_returns }}</b></td>
									        <td v-if="paymentData.tot_short != 0"><b>{{ paymentData.tot_short }}</b></td>
									        <td v-if="paymentData.tot_interest != 0"><b>{{ paymentData.tot_interest }}</b></td>
									        <td v-if="paymentData.tot_rate_difference != 0"><b>{{ paymentData.tot_rate_difference }}</b></td>
        									<td><b>{{ paymentData.total_amount - paymentData.tot_discount - paymentData.tot_vatav - paymentData.tot_good_returns - paymentData.tot_agent_commission - paymentData.tot_bank_cpmmission - paymentData.tot_claim - paymentData.tot_short - paymentData.tot_interest - paymentData.tot_rate_difference }}</b></td>
		        							<td></td>
                                        </tr>
							        </tbody>
                                    
                                </table>
							    <table class="cheque table table-striped m-b-none">
		    						<tbody>
                                        <tr>
								        	<th colspan="1" style="border-bottom: 3px double;">CHEQUE DETAILS</th>
									        <td colspan="4"></td>
								        </tr>
								        <tr>
									        <th width="15%">BANK</th>
									        <td>{{ bank }}</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
								        </tr>
        								<tr>
                                            <th>CHQ NO</th>
                                            <td>{{ paymentData.cheque_dd_no}}</td>
                                            <td></td>
                                            <td>Received by: </td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <th>CHQ DATE</th>
                                            <td>{{ paymentData.cheque_date }}</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <th>CHQ AMOUNT</th>
                                            <td>557,700</td>
                                            <td></td>
                                            <td>Approved by: </td>
                                            <td></td>
                                        </tr>
        							</tbody>
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
                bank: '', 
            }
        },
        created() {
             axios.get(`/payments/fetch-voucher/${this.id}`)
                .then(response => {
                        
                        gData = response.data;
                        if (gData.paymentData.reciept_mode != 'cheque') {
                            $('.cheque').addClass('d-none');
                        }
                        this.paymentData = gData.paymentData;
                        this.salebills = gData.salebill;
                        this.customer = gData.customer;
                        this.supplier = gData.supplier;
                        this.created_at = gData.created_at;
                        this.bank = gData.bank;
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
