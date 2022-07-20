<template>
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">Commission Invoice Right of Report</h3>
                                <div class="nk-block-des text-soft"> </div>
                            </div><!-- .nk-block-head-content -->
                            <div class="nk-block-head-content">
                                <div class="toggle-wrap nk-block-tools-toggle">
                                    <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1"
                                        data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                    <div class="toggle-expand-content" data-content="pageMenu">
                                        <button class="btn btn-light mr-2" @click="clearData()">Clear All</button>
                                        <button class="btn btn-primary mr-2" @click="exportSheet()">Export
                                            Sheet</button>
                                        <button class="btn btn-primary" @click="exportPDF()">Export PDF</button>
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
                                <div class="mb-5">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th width="15%">Start Date</th>
                                                <th width="15%">End Date</th>
                                                <th width="20%">Customer</th>
                                                <th width="20%">Supplier</th>
                                                <th width="15%">Sorting</th>
                                                <th width="15%">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <input type="date" v-model="start_date" id="start_date"
                                                        class="form-control" autocomplete="off"
                                                        onfocus="this.showPicker();" :max="max_date">
                                                </td>
                                                <td>
                                                    <input type="date" v-model="end_date" id="end_date"
                                                        class="form-control" autocomplete="off"
                                                        onfocus="this.showPicker();" :max="max_date">
                                                </td>
                                                <td>
                                                    <multiselect v-model="customer" :options="customer_options"
                                                        placeholder="Select One" label="name" track-by="id" id="">
                                                    </multiselect>
                                                </td>
                                                <td>
                                                    <multiselect v-model="supplier" :options="supplier_options"
                                                        placeholder="Select One" label="name" track-by="id" id="">
                                                    </multiselect>
                                                </td>
                                                <td>
                                                    <select v-model="sort_by" class="form-control">
                                                        <option value="1" >Supplier A -&gt; Z</option>
                                                        <option value="2">Supplier Z -&gt; A</option>
                                                        <option value="3">Customer A -&gt; Z</option>
                                                        <option value="4">Customer Z -&gt; A</option>
                                                        <option value="5">Date L -&gt; H</option>
                                                        <option value="6">Date H -&gt; L</option>
                                                    </select>
                                                </td>
                                                <td class="">
                                                    <button class="btn btn-primary btn-round btn-sm mr-1"
                                                        @click="getData()">Go</button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="table-responsive" id="invoiceDT-div">
                                    <table id="invoiceDT" class="table table-hover table-bordered-">
                                        <thead>
                                            <tr class="">
                                                <th>ID</th>
                                                <th>Supplier</th>
                                                <th>Customer</th>
                                                <th>Date</th>
                                                <th>Mode</th>
                                                <th>Dep. Bank</th>
                                                <th>Chq. Date</th>
                                                <th>Chq/DD No</th>
                                                <th>Chq/DD Bank</th>
                                                <th class="text-right">Rec. Amount</th>
                                                <th class="text-right">Tot. Amount</th>
                                                <th class="text-right">Right of Amt</th>
                                                <th>Right of Remark</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div><!-- .card -->
                        </div>
                    </div><!-- .nk-block -->
                </div>
            </div>
        </div>
    </div>
    <!-- <ViewCompanyDetails ref="company"></ViewCompanyDetails> -->
</template>

<script>
// import ViewCompanyDetails from '../databank/companyComponents/modal/ViewCompanyDetailsModelComponent.vue';

import $ from 'jquery';
import 'datatables.net-responsive-bs4/js/responsive.bootstrap4';
import "datatables.net-buttons-bs5/js/buttons.bootstrap5";
import Multiselect from 'vue-multiselect';

export default {
    name: 'commissionInvoiceRightOf',
    props: {
    },
    components: {
        Multiselect,
        // ViewCompanyDetails
    },
    data() {
        return {
            customer_options: [],
            supplier_options: [],
            customer: '',
            supplier: '',
            start_date: '',
            end_date: '',
            sort_by: '1',
            max_date: '2022-01-01',
            export_sheet: 0,
            export_pdf: 0,
            dt_table: null,
        }
    },
    created() {
        const date = new Date();
        const m = String(date.getMonth() + 1).padStart(2, '0');
        const d = String(date.getDate()).padStart(2, '0');
        const y = String(date.getFullYear());
        this.max_date = [y, m, d].join('-');

        axios.get('/account/sale-bill/list-customers-and-suppliers')
        .then(response => {
            this.customer_options = response.data[0];
            this.supplier_options = response.data[1];
        });
    },
    methods: {
        clearData() {
            this.start_date = this.end_date = this.customer = this.supplier = '';
            this.sort_by = '1';
        },
        getData() {
            if (this.start_date == '' || this.end_date == '') {
                alert('Please Select Both Start Date & End Date');
                return false;
            }
            axios.post('/reports/list-commission-invoice-right-of-data', {
                start_date: this.start_date,
                end_date: this.end_date,
                customer: this.customer,
                supplier: this.supplier,
                sort_by: this.sort_by,
                export_sheet: this.export_sheet,
                export_pdf: this.export_pdf
            })
            .then(response => {
                if (this.export_sheet == 1 || this.export_pdf == 1) {
                    this.export_sheet = this.export_pdf = 0;
                    window.open(response.data.url, '_blank');
                    return;
                }
                if (response.data.length > 0) {
                    const toINR = new Intl.NumberFormat('en-IN', {});
                    var html = '';
                    var received_amount = 0, total_amount = 0, roa = 0;
                    response.data.forEach((k, i) => {
                        received_amount += parseFloat(k.receipt_amount);
                        total_amount += parseFloat(k.total_amount);
                        roa += parseFloat(k.right_of_amount);
                        html += `<tr>
                    <td class=""> <a href="/payments/view-payment/${k.payment_id}" target="_blank"> ${k.payment_id} </a></td>
                    <td> ${k.supplier_name} </td>
                    <td> ${k.customer_name} </td>
                    <td> ${k.date2} </td>
                    <td> ${k.reciept_mode} </td>
                    <td> ${k.reciept_mode != "full return" ? k.deposit_bank : ''} </td>
                    <td> ${k.reciept_mode != "full return" ? k.cheque_date2 : ''} </td>
                    <td> ${k.reciept_mode == "cheque" ? k.cheque_dd_no : ''} </td>
                    <td> ${k.bank_name} </td>
                    <td class="text-right"> ${k.reciept_mode != "full return" ? toINR.format(k.receipt_amount) : ''} </td>
                    <td class="text-right"> ${toINR.format(k.total_amount)} </td>
                    <td class="text-right"> ${toINR.format(k.right_of_amount)} </td>
                    <td> ${k.right_of_remark} </td>
                </tr>`;
                    });
                    html += `<tr>
                    <th colspan="9"> Total</th>
                    <th class="text-right"> ${toINR.format(received_amount)} </th>
                    <th class="text-right"> ${toINR.format(total_amount)} </th>
                    <th class="text-right"> ${toINR.format(roa)} </th>
                    <th > </th>
                </tr>`;
                    $('#invoiceDT tbody').html(html);
                } else {
                    $('#invoiceDT tbody').html('<tr><td colspan="13" class="text-center">No Records Found</td></tr>');
                }
            });
        },
        exportSheet() {
            this.export_sheet = 1;
            this.getData();
        },
        exportPDF() {
            this.export_pdf = 1;
            this.getData();
        },
    },
    mounted() {
        const self = this;
        /* const toINR = new Intl.NumberFormat('en-IN', {
            style: 'currency',
            currency: 'INR',
            // minimumFractionDigits: 2
        }); */

        $(document).on('click', '.view-details', function (e) {
            self.showModal($(this).attr('data-id'));
        });

    },
};
</script>
