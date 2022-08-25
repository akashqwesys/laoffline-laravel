<template>
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                       <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 v-if="outwardtype == 1" class="nk-block-title page-title">Outward Sale Bill Detail <a class="btn btn-primary ml-2" href="/register/outward/salebill" >Back</a></h3>
                                <h3 v-if="outwardtype == 2" class="nk-block-title page-title">Outward Payments Detail</h3>
                                <h3 v-if="outwardtype == 3" class="nk-block-title page-title">Outward Commission Detail</h3>
                                <h3 v-if="outwardtype == 4" class="nk-block-title page-title">Outward Commission Invoice Detail</h3>
                            </div><!-- .nk-block-head-content -->
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    <div class="nk-block">
                        <div v-if="outwardtype == 1" class="card card-bordered card-stretch">
                            <div class="card-header">
                                <span><strong>Ourward Salebill Detail</strong></span>
                            </div>
                            <div class="card-inner">
                            <div id="print_area1">
                                <div style="float:left; width:100%; border:1px solid; padding: 0px 5px;">
							        <table class="table m-b-none">
								        <tbody>
                                            <tr>
                                                <td width="16%"></td>
                                                <th width="66%" class="text-center"><img class="mb-1" src="https://laoffline.com/img/logo_report.png" style="width: 100px;">
                                                <br><span class="mt-2">Kind Attention to {{ Outward.personname }}</span></th>
                                                <th width="16%" class="text-right"><span>{{ Outward.todaydate }}</span></th>
                                            </tr>
                                            <tr>
									            <td colspan="3" class="text-left">MS</td>
								            </tr>
                                            <tr>
									            <td colspan="3" class="text-center">{{ Outward.company }}</td>
								            </tr>
											<tr>
									            <td colspan="3" class="text-center">Greetings From {{ agent }}</td>
            								</tr>
											<tr>
				            					<td colspan="3">Please Find The Details Below</td>
							            	</tr>

							            </tbody>
                                </table>

							    <table class="table table-striped m-b-none">
								    <tbody>
                                        <tr>
                                            <td><b>LR No.</b></td>
                                            <td><b>Date</b></td>
                                            <td><b>Supplier</b></td>
        									<td><b>No of parcel</b></td>
		        							<td><b>Transport</b></td>
				        				</tr>
                                        <tr v-for="(salebill,index) in salebills" :key="index">
                                            <td>{{ salebill.transport ? salebill.transport.lr_mr_no : '' }}</td>
                                            <td>{{ salebill.salebilldetail ? salebill.salebilldetail.select_date : ''}}</td>
                                            <td>{{ salebill.company_name}}</td>
        									<td>{{ salebill.transport ? salebill.transport.cases : '' }}</td>
		        							<td>{{ salebill.transport ? salebill.transport.name : '' }}</td>
				        				</tr>

    						        </tbody>
                                    <tfoot>
						                  	<tr>
						                  		<td colspan="3" class="text-right">Total Parcel Quantity : </td>
						                  		<td colspan="2">{{ totalParcel }}</td>
						                  	</tr>
						                  	<tr>
						                      	<td colspan="5" class="text-right">Thank you<br><br></td>
						                  	</tr>
						                  	<tr>
						                      	<td colspan="5" class="text-right">(Signature)</td>
						                  	</tr>
						                </tfoot>
                                </table>

						    </div>
                            </div>
                            </div><!-- .card -->
                        </div>
                        <div v-if="outwardtype == 2" class="card card-bordered card-stretch">
                            <div class="card-header">
                                <span><strong>Ourward Payment Detail</strong></span>
                            </div>
                            <div class="card-inner">
                            <div id="print_area1">
                                <div style="float:left; width:100%; border:1px solid;padding: 0px 5px;">
							        <table class="table m-b-none">
								        <tbody>
                                            <tr>
                                                <td width="16%"></td>
                                                <th width="66%" class="text-center"><img class="mb-2" src="https://laoffline.com/img/logo_report.png" style="width: 100px;">
                                                <br><span>Payment Outward Slip</span></th>
                                                <th width="16%" class="text-right"><span>{{ Outward.todaydate }}</span></th>
                                            </tr>
                                            <tr>
									            <td colspan="3" class="text-left">{{ Outward.company }}</td>
								            </tr>

											<tr>
				            					<td colspan="3">Please Find The Details Below</td>
							            	</tr>

							            </tbody>
                                </table>

							    <table class="table table-striped m-b-none">
								    <tbody>
                                        <tr>
                                            <td><b>Voucher No.</b></td>
                                            <td><b>CHQ No</b></td>
                                            <td><b>CHQ Date</b></td>
        									<td><b>Customer</b></td>
		        							<td><b>Amount</b></td>
				        				</tr>
                                        <tr v-for="(salebill,index) in salebills" :key="index">
                                            <td>{{ salebill.paymentdetail ? salebill.paymentdetail.payment_id : '' }}</td>
                                            <td>{{ salebill.paymentdetail ? salebill.paymentdetail.cheque_dd_no : ''}}</td>
                                            <td>{{ salebill.paymentdetail ? salebill.paymentdetail.cheque_date : ''}}</td>
        									<td>{{ salebill.company_name }}</td>
		        							<td>{{ salebill.paymentdetail ? salebill.paymentdetail.tot_adjust_amount : '' }}</td>
				        				</tr>

    						        </tbody>
                                    <tfoot>
						                  	<tr>
						                      	<td colspan="5" class="text-right">Thank you<br><br></td>
						                  	</tr>
						                  	<tr>
						                      	<td colspan="5" class="text-right">(Signature)</td>
						                  	</tr>
						                </tfoot>
                                </table>

						    </div>
                            </div>
                            </div><!-- .card -->
                        </div>
                        <div v-if="outwardtype == 3" class="card card-bordered card-stretch">
                            <div class="card-header">
                                <span><strong>Ourward Commission Detail</strong></span>
                            </div>
                            <div class="card-inner">
                            <div id="print_area1">
                                <div style="float:left; width:100%; border:1px solid; padding: 0px 5px;">
							        <table class="table m-b-none">
								        <tbody>
                                            <tr>
                                                <td width="16%"></td>
                                                <th width="66%" class="text-center"><img class="mb-2" src="https://laoffline.com/img/logo_report.png" style="width: 100px;">
                                                <br><span>{{ Outward.company }}</span></th>
                                                <th width="16%" class="text-right"><span>{{ Outward.todaydate }}</span></th>
                                            </tr>
                                            <tr>
				            					<td colspan="3">Please Find The Details Below</td>
							            	</tr>

							            </tbody>
                                </table>

							    <table class="table table-striped m-b-none" id="div_table">
								    <tbody>
                                        <tr>
                                            <td><b>Commssion Id</b></td>
                                            <td><b>Date</b></td>
                                            <td><b>Cheque No</b></td>
        									<td><b>Amount</b></td>
		        							<td><b>Bank</b></td>
                                            <td><b>Account Name</b></td>
				        				</tr>
                                        <tr v-for="(salebill,index) in salebills" :key="index">
                                            <td>{{ salebill.commissionDetail ? salebill.commissionDetail.commission_id : '' }}</td>
                                            <td>{{ salebill.commissionDetail ? salebill.commissionDetail.commission_date : ''}}</td>
                                            <td>{{ salebill.commissionDetail ? salebill.commissionDetail.cheque_dd_no : ''}}</td>
        									<td>{{ salebill.commissionDetail ? salebill.commissionDetail.commission_payment_amount : '' }}</td>
		        							<td>{{ salebill.bank }}</td>
                                            <td>{{ salebill.account }}</td>
				        				</tr>

    						        </tbody>
                                    <tfoot>
						                  	<tr>
						                      	<td colspan="3" class="text-left">Prepared by<br><br></td>
                                                <td colspan="3" class="text-right">Delivery by</td>
                                              </tr>
						                </tfoot>
                                </table>

						    </div>
                            </div>
                            </div><!-- .card -->
                        </div>
                        <div v-if="outwardtype == 4" class="card card-bordered card-stretch">
                            <div class="card-header">
                                <span><strong>Ourward Commission Invoice Detail</strong></span>
                            </div>
                            <div class="card-inner">
                            <div id="print_area1">
                                <div style="float:left; width:100%; border:1px solid; padding:0px 5px">
							        <table class="table m-b-none">
								        <tbody>
                                            <tr>
                                                <td width="16%"></td>
                                                <th width="66%" class="text-center"><img class="mb-2" src="https://laoffline.com/img/logo_report.png" style="width: 100px;">
                                                <br><span>{{ Outward.company }}</span></th>
                                                <th width="16%" class="text-right"><span>{{ Outward.todaydate }}</span></th>
                                            </tr>
                                            <tr>
				            					<td colspan="3">Please Find The Details Below</td>
							            	</tr>

							            </tbody>
                                </table>

							    <table class="table table-striped m-b-none" id="div_table">
								    <tbody>
                                        <tr>
                                            <td><b>Invoice No</b></td>
                                            <td><b>Bill No</b></td>
                                            <td><b>Date Added</b></td>
        									<td><b>Bill Date</b></td>
				        				</tr>
                                        <tr v-for="(salebill,index) in salebills" :key="index">
                                            <td>{{ salebill.invoicedetail ? salebill.invoicedetail.id : '' }}</td>
                                            <td>{{ salebill.invoicedetail ? salebill.invoicedetail.bill_no : ''}}</td>
                                            <td>{{ salebill.date_add }}</td>
        									<td>{{ salebill.billdate}}</td>

				        				</tr>

    						        </tbody>
                                    <tfoot>
						                  	<tr>
						                      	<td colspan="2" class="text-left">Prepared by<br><br></td>
                                                <td colspan="2" class="text-right">Delivery by</td>
                                              </tr>
						                </tfoot>
                                </table>

						    </div>
                            </div>
                            </div><!-- .card -->
                        </div>

                        <div class="text-center mt-2">
                            <button class="btn btn-dark mr-2" @click="printOutward">Print</button>
                            <a class="btn btn-primary" href="/register/outward/salebill" >Back</a>
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
                Outward: [],
                salebills: [],
                agent: '',
                outwardtype: '',
                totalParcel: 0,
            }
        },
        created() {
             axios.get(`/register/fetch-outward/${this.id}`)
                .then(response => {

                        gData = response.data;
                        let total = 0;
                        this.Outward = gData.outward;
                        this.salebills = gData.salebill;
                        this.agent = gData.outward.courier_agent.name

                        this.outwardtype = gData.outward_type;
                        if (this.outwardtype == 1) {
                            this.salebills.forEach((value)=>{
                                total += parseInt(value.transport ? value.transport.cases : '');
                            });
                        }
                        this.totalParcel = total;
                });
        },
        methods: {
            printOutward (event) {
                var printContents = document.getElementById("print_area1").innerHTML;
                var originalContents = document.body.innerHTML;
                var popupWin = window.open('', '_blank', 'width=1000,height=1000');
                popupWin.document.open()
                popupWin.document.write('<html><head><link rel="stylesheet" href="https://laoffline.com/bower_components/bootstrap/dist/css/bootstrap.css" type="text/css" /><link rel="stylesheet" href="https://laoffline.com/css/app.css" type="text/css" /></head><body onload="window.print()">' + printContents + '</html>');
                popupWin.document.close();
            }
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
