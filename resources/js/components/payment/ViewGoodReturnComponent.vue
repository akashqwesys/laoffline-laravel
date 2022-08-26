<template>
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                       <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3  class="nk-block-title page-title">View Good Return Detail</h3>
                            </div><!-- .nk-block-head-content -->
                        </div><!-- .nk-block-between --> 
                    </div><!-- .nk-block-head -->
                    <div class="nk-block">
                        <div class="card card-bordered card-stretch">
                            <div class="card-inner">
                            <div class="print_area">
                                <div class="row" style="border-bottom: 1px dashed #f2f2f2; margin-bottom: 10px ;">
                                   
                                    <div class="col-sm-4">
				    			        <label class="control-label"><b>IUID : </b> {{ Goodreturn.iuid}} </label>
				    		        </div>
                                    <div class="col-sm-4">
				    			        <label class="control-label"><b>Reference Id : </b> {{ Goodreturn.reference_id }} </label>
				    		        </div>
                                    <div class="col-sm-4">
				    			        <label class="control-label"><b>Company  : </b> {{ customer.company_name }} </label>
				    		        </div>
                                    <div class="col-sm-4 cheque">
				    			        <label class="control-label"><b>Supplier : </b> {{ supplier.company_name }} </label>
				    		        </div>
                                    <div class="col-sm-4 cheque">
				    			        <label class="control-label"><b>Sale Bill Id : </b> {{ Goodreturn.sale_bill_id }} </label>
				    		        </div>
                                    <div class="col-sm-4 cheque">
				    			        <label class="control-label"><b>Supplier Invoice No : </b> {{ Goodreturn.supp_invoice_no }} </label>
				    		        </div>
                                    <div class="col-sm-4">
				    			        <label class="control-label"><b>Amount : </b> {{ Goodreturn.amount }} </label>
				    		        </div>
                                    <div class="col-sm-4">
				    			        <label class="control-label"><b>Goods Return : </b> {{ Goodreturn.goods_return }} </label>
				    		        </div>
                                    <div class="col-sm-4">
				    			        <label class="control-label"><b>Multiple Attachment : </b>  </label>
                                        <ul>
                                                <li v-for="(attachment,index) in Goodreturn.attachment" :key="index">
                                                    <a v-if="attachment != ''" :href="'/upload/goodreturn/'+attachment" target="_blank">
                                                        <img height="65" width="50" id="preview-img" src="/assets/images/icons/file-media.svg" style="opacity: 0.5; padding-top: 5px;">
                                                    </a>
                                                    <span v-else>-</span>
                                                </li>
                                            </ul>
				    		        </div>
                                    
                                </div>
                                <div class="row table-responsive">
                                    <h6>Item Details</h6>
                                    <table class="table mb-2 table table-striped m-b-none">
                                                <thead>
                                                    <tr>
                                                        <th>Name</th>
                                                        <th>Peices</th>
                                                        <th>Meter</th>
                                                        <th>Peices Meter</th>
                                                        <th>Rate</th>
                                                        <th>DISC %</th>
                                                        <th>DISC AMT</th>
                                                        <th>CGST %</th>
                                                        <th>CGST AMT</th>
                                                        <th>SGST %</th>
                                                        <th>SGST AMT</th>
                                                        <th>IGST %</th>
                                                        <th>IGST AMT</th>
                                                        <th>Amount</th>
                                                        <th></th>
						                            </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="(grp,index) in GoodretunProduct" :key="index">
                                                        <td>{{ grp.name }}</td>
                                                        <td>{{ grp.peices }}</td>
                                                        <td>{{ grp.meters }}</td>
                                                        <td>{{ grp.peices_meters }}</td>
                                                        <td>{{ grp.rate }}</td>
                                                        <td>{{ grp.discount_per }}</td>
                                                        <td>{{ grp.discount_amt }}</td>
                                                        <td>{{ grp.cgst_per }}</td>
                                                        <td>{{ grp.cgst_amt }}</td>
                                                        <td>{{ grp.sgst_per }}</td>
                                                        <td>{{ grp.sgst_amt }}</td>
                                                        <td>{{ grp.igst_per }}</td>
                                                        <td>{{ grp.igst_amt }}</td>
                                                        <td>{{ grp.amount }}</td>
                                                        <td></td>
                                                    </tr>
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td><b>Total</b></td>
                                                        <td>{{ Goodreturn.tot_peices }}</td>
                                                        <td>{{ Goodreturn.tot_meters }}</td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td>{{ Goodreturn.tot_amount }}</td>
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
                Goodreturn: [],
                GoodretunProduct: [],
                customer: [],
                supplier: [],
                
                 
            }
        },
        created() {
             axios.get(`/payments/getgoodreturnview/${this.id}`)
                .then(response => {
                        
                        gData = response.data;
                        
                        this.Goodreturn = gData.goodreturn;
                        this.GoodretunProduct = gData.item;
                        this.customer = gData.customer;
                        this.supplier = gData.supplier;
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
