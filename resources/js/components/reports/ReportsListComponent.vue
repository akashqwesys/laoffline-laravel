<template>
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">All Reports</h3>
                                <div class="nk-block-des text-soft">
                                </div>
                            </div><!-- .nk-block-head-content -->
                            <div class="nk-block-head-content">
                                <div class="toggle-wrap nk-block-tools-toggle">
                                    <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1"
                                        data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                    <div class="toggle-expand-content" data-content="pageMenu">
                                        <ul class="nk-block-tools g-3">
                                            <li class="nk-block-tools-opt">
                                            </li>
                                        </ul>
                                    </div>
                                </div><!-- .toggle-wrap -->
                            </div><!-- .nk-block-head-content -->
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    <div class="nk-block">
                        <div class="card card-bordered card-stretch">
                            <div class="card-inner">
                                <div class="table-responsive-">
                                    <table id="salesRegister" class="table table-hover table-striped">
                                        <thead>
                                            <tr>
                                                <th width="12%">Sr. No.</th>
                                                <th>Reports</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td><a target="_blank" href="/reports/consolidate-monthly-sales-report">Consolidate Monthly Sales Report</a></td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td><a target="_blank" href="/reports/outstanding-payment-report">Outstanding Payment Report</a></td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td><a target="_blank" href="/reports/outstanding-payment-month-wise-summary-report">Outstanding Payment Month Wise Summary Report</a></td>
                                            </tr>
                                            <tr>
                                                <td>4</td>
                                                <td><a target="_blank" href="/reports/sales-register-report">Sales Register Report</a></td>
                                            </tr>
                                            <tr>
                                                <td>5</td>
                                                <td><a target="_blank" href="/reports/commission-register-report">Commission Register</a></td>
                                            </tr>
                                            <tr>
                                                <td>6</td>
                                                <td><a target="_blank" href="/sales_dmy_report">Sales Report Day Wise, Month &amp; Year Wise</a></td>
                                            </tr>
                                            <tr>
                                                <td>7</td>
                                                <td><a target="_blank" href="/sales_bill_details_report">Sale Report All Bill Details Item</a></td>
                                            </tr>
                                            <tr>
                                                <td>8</td>
                                                <td><a target="_blank" href="/reports/payment-register-report">Payment Register</a></td>
                                            </tr>
                                            <!-- <tr><td>9</td>	<td><a target="_blank" href="">Outstanding Commission Report</a></td></tr> -->
                                            <tr>
                                                <td>9</td>
                                                <td><b>Outstanding Commission Report</b></td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td>
                                                    <ul>
                                                        <li style="list-style: decimal;"><a href="/reports/outstanding-commission-register-report/supplier" target="_blank"> Supplier </a></li> 
                                                        <li style="list-style: decimal;"><a href="/reports/outstanding-commission-register-report/customer" target="_blank"> Customer </a></li>
                                                    </ul>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>10</td>
                                                <td><b>Outstanding Commission Month Wise Summary Report</b></td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td>
                                                    <ul>
                                                        <li style="list-style: decimal;"><a href="/reports/outstanding-commission-month-wise-summary-report/supplier" target="_blank">Supplier </a></li>
                                                        <li style="list-style: decimal;"><a href="/reports/outstanding-commission-month-wise-summary-report/customer" target="_blank">Customer </a></li>
                                                    </ul>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>11</td>
                                                <td><a target="_blank" href="/daily_commission_report">Daily Commission Report</a></td>
                                            </tr>
                                            <tr>
                                                <td>12</td>
                                                <td><a target="_blank" href="/supplier_duplicate_invoice_report">Duplicate Invoice Report</a></td>
                                            </tr> <!-- add -->
                                            <tr>
                                                <td>13</td>
                                                <td><a target="_blank" href="/commission_collection_report">Commission Collection Report</a></td>
                                            </tr>
                                            <tr>
                                                <td>14</td>
                                                <td><a target="_blank" href="/product_report">Product Report</a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>15</td>
                                                <td><a target="_blank" href="/outstanding_invoice_report">Outstanding Invoice Report</a></td>
                                            </tr>
                                            <tr>
                                                <td>16</td>
                                                <td><a target="_blank" href="/commission_invoice_report">Commission Invoice Report</a></td>
                                            </tr>
                                            <tr>
                                                <td>17</td>
                                                <td><a target="_blank" href="/payment_rightof_report">Commission Invoice Right Of Report</a></td>
                                            </tr>
                                            <tr>
                                                <td>18</td>
                                                <td><a target="_blank" href="/commission_rightof_report">Commission Right of Report</a></td>
                                            </tr>
                                            <tr>
                                                <td>19</td>
                                                <td><b>Buyer Reports</b></td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td>
                                                    <ul>
                                                        <li style="list-style: decimal;"><a href="/buyer_report/total_inward" target="_blank">Total Inwards </a></li>
                                                        <li style="list-style: decimal;"><a href="/buyer_report/total_outward" target="_blank">Total Outwards </a></li>
                                                    </ul>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>20</td>
                                                <td><a target="_blank" href="reports/avg_payment_days-report">Average Payment Days Report </a></td>
                                            </tr>
                                            <tr>
                                                <td>21</td>
                                                <td><a target="_blank" href="/avg_commission_days">Average Commission Days Report</a></td>
                                            </tr>
                                            <tr>
                                                <td>22</td>
                                                <td><a target="_blank" href="/company_wise_product_report">Company Wise Product Report</a></td>
                                            </tr>
                                            <tr>
                                                <td>23</td>
                                                <td><a target="_blank" href="/percentage_evaluate_report">Percentage Evaluate Report</a></td>
                                            </tr>
                                            <tr>
                                                <td>24</td>
                                                <td><a target="_blank" href="/percentage_evaluate_turnover_report"> Percentage Evaluate Report on Turnover</a></td>
                                            </tr>
                                        </tbody>
                                    </table>
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

    export default {
        name: 'allReports',
        props: {
        },
        components: {
        },
        data() {
            return {
            }
        },
        methods: {
        },
        mounted() { },
    };
</script>

