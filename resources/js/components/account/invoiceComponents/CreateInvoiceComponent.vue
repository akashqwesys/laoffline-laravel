<template>
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">Create Invoice</h3>
                            </div><!-- .nk-block-head-content -->
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    <div class="nk-block">
                        <div class="card card-bordered">
                            <div class="card-inner">
                                <h5>Search Payment Details</h5>
                                <hr>
                                <div class="row gy-4">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label" for="company">Company</label>
                                            <div class="form-control-wrap">
                                                <multiselect v-model="company" :options="company_options" placeholder="Select One" label="name" track-by="id" id="company" @select="search_btn_disabled = false"></multiselect>
                                                <!-- <div v-if="v$.company.$error" class="invalid mt-1">Select Company</div> -->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label" for=""> &nbsp;</label>
                                            <div class="form-control-wrap">
                                                <button class="btn btn-primary mr-2" @click="getPaymentDetails(e)" :disabled="search_btn_disabled">SEARCH</button>
                                                <a class="btn btn-light" :href="cancel_url">CANCEL</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr class="mb-5 mt-5">
                                <div id="payment-data" class="hidden">
                                    <h5>Payment Details</h5>
                                    <hr>
                                    <div class="table-responsive" >
                                        <table class="table table-striped" id="paymentTable">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>Payment ID</th>
                                                    <th>Financial Year</th>
                                                    <th>Date</th>
                                                    <th>Supplier</th>
                                                    <th>Customer</th>
                                                    <th>Amount</th>
                                                    <th>Overdue</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td></td>
                                                    <td>
                                                        <input type="text" class="form-control" id="p_id" @keyup="searchColumn('p_id', 1)">
                                                    </td>
                                                    <td></td>
                                                    <td><input type="date" class="form-control" id="p_date" @change="searchColumn('p_date', 3)"></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td><input type="text" class="form-control"  id="p_amount" @keyup="searchColumn('p_amount', 6)"></td>
                                                    <td></td>
                                                </tr>
                                                <template v-for="(k, i) in paymentData" :key="i">
                                                <tr :class="k.overdue > 90 ? 'text-danger' : ''">
                                                    <td>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" :value="k.payment_id + '-' + k.financial_year_id + '-' + k.amount" :id="k.payment_id + '_'">
                                                            <label class="custom-control-label" :for="k.payment_id + '_'"></label>
                                                        </div>
                                                    </td>
                                                    <td> {{ k.payment_id }} </td>
                                                    <td> {{ k.financial_year }} </td>
                                                    <td> {{ k.date }} </td>
                                                    <td> {{ k.supplier }} </td>
                                                    <td> {{ k.customer }} </td>
                                                    <td> {{ k.amount }} </td>
                                                    <td> {{ k.overdue }} </td>
                                                </tr>
                                                </template>
                                                <tr>
                                                    <td colspan="2">
                                                        <b>Total Selected: </b>
                                                    </td>
                                                    <td><b id="total_selected">0</b></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td><b>Total Payment Amount:</b></td>
                                                    <td><b id="total_amount">0</b></td>
                                                    <td></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="mt-3">
                                        <button class="btn btn-dark mr-2" disabled="true" @click="generateInvoice" id="generate-invoice-btn">Generate Invoice</button>

                                        <button v-if="employeeName == 'vrinda'" class="btn btn-primary" @click="showPaymentRemark">Right Of Amount</button>
                                    </div>
                                    <div class="hidden" id="payment-remark-div">
                                        <hr>
                                        <textarea v-model="payment_remark" class="form-control" ></textarea>
                                        <button class="btn btn-success mt-2" @click="updatePaymentRemark">ADD</button>
                                    </div>
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
    import Multiselect from 'vue-multiselect';;

    export default {
        name: 'createInvoice',
        components: {
            Multiselect
        },
        props: {
            scope: String,
            id: Number,
            employeeName: String
        },
        data() {
            return {
                cancel_url: '/account/commission/invoice',
                company: '',
                company_options: [],
                search_btn_disabled: true,
                paymentData: [{
                    payment_id: '',
                    financial_year: '',
                    financial_year_id: '',
                    date: '',
                    supplier: '',
                    customer: '',
                    amount: '',
                    overdue: '',
                }],
                payment_remark: '',
            }
        },
        created () {
            axios.get('/account/commission/invoice/list-company')
            .then(response => {
                this.company_options = response.data;
            });
        },
        methods: {
            searchColumn(id, index) {
                var input, filter, table, tr, td, i, txtValue;
                input = document.getElementById(id);
                filter = input.value.toUpperCase();
                table = document.getElementById("paymentTable");
                tr = table.getElementsByTagName("tr");
                for (i = 0; i < tr.length; i++) {
                    if (i == 1) { continue; }
                    td = tr[i].getElementsByTagName("td")[index];
                    if (td) {
                        txtValue = td.textContent || td.innerText;
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            tr[i].style.display = "";
                        } else {
                            tr[i].style.display = "none";
                        }
                    }
                }
            },
            getPaymentDetails (e) {
                if (this.company) {
                    axios.get('/account/commission/invoice/get-payments?company='+this.company.id+'&type='+this.company.company_type)
                    .then(response => {
                        this.paymentData = [];
                        var data = response.data;
                        if (data.length > 0) {
                            data.forEach((k, i) => {
                                this.paymentData[i] = {
                                    payment_id: k.payment_id,
                                    financial_year: k.financial_year,
                                    financial_year_id: k.financial_year_id,
                                    date: k.date,
                                    supplier: k.supplier,
                                    customer: k.customer,
                                    amount: k.amount,
                                    overdue: k.due_days
                                }
                            });
                            $('#payment-data').slideDown();
                        } else {
                            $('#payment-data').slideUp();
                        }
                    });
                }
            },
            showPaymentRemark() {
                $('#payment-remark-div').slideDown();
            },
            updatePaymentRemark (e) {
                // var checked = [...document.querySelectorAll('.custom-control-input:checked')].map(e => e.value);
                const self = this;
                var payments = [];
                $(".custom-control-input").each(function(index, el) {
                    if($(this).prop('checked') == true) {
                        payments.push($(this).val());
                    }
                });
                if (payments.length > 0) {
                    axios.post('/account/commission/invoice/update-payments-remarks', {
                        right_of_comment: self.payment_remark,
                        payments: payments
                    })
                    .then(response => {
                        if (response.data.success == 1) {
                            self.payment_remark = '';
                            $(".custom-control-input").each(function(index, el) {
                                if ($(this).prop('checked') == true) {
                                    // $(this).closest('tr').remove();
                                    self.paymentData.splice(index, 1);
                                }
                            });
                            $(".custom-control-input").prop('checked', false);
                            $('#payment-remark-div').slideUp();
                            $('#total_amount, #total_selected').text(0);
                            $('#generate-invoice-btn').attr('disabled', true);
                        }
                    });
                }
            },
            generateInvoice (e) {
                var checked = [...document.querySelectorAll('.custom-control-input:checked')].map(e => e.value);
                var payment_ids = JSON.stringify(checked);
                axios.post('/account/commission/invoice/setSessionForPaymentDetails', {
                        supplier: this.company,
                        payment_ids: payment_ids
                    })
                    .then(response => {
                        if (response.data.success == 1) {
                            location.href = '/account/commission/invoice/generate-invoice';
                        }
                    });
            }
        },
        mounted() {
            const self = this;
            var tot = 0;

            $(document).on('click', '#paymentTable tbody tr', function(e) {
                // e.preventDefault();
                var checked = $('.custom-control-input:checked');
                $('#total_selected').text(checked.length);
                if (checked.length > 0) {
                    $('#generate-invoice-btn').attr('disabled', false);
                } else {
                    $('#generate-invoice-btn').attr('disabled', true);
                }
                if ($(e.target).hasClass('custom-control-label')) {
                    var amount = parseFloat($(e.target).closest('tr').find('td').eq(6).text());
                    if ($(e.target).siblings('.custom-control-input').prop('checked') == true) {
                        tot -= amount;
                    } else {
                        tot += amount;
                    }
                    $('#total_amount').text(tot);
                }
            });
        },
    };
</script>
<style>
</style>

