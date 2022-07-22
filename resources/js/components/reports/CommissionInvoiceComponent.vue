<template>
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">Commission Invoice Report</h3>
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
                                                <th width="15%">Inv No</th>
                                                <th width="12%">Start Date</th>
                                                <th width="12%">End Date</th>
                                                <th width="20%">Company</th>
                                                <th width="12%">Agent</th>
                                                <th width="8%">Due</th>
                                                <th width="10%">Sort</th>
                                                <th width="20%">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <input type="text" v-model="invoice_no" id="invoice_no"
                                                        class="form-control">
                                                </td>
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
                                                    <multiselect v-model="company" :options="company_options"
                                                        placeholder="Select One" label="name" track-by="id" id="">
                                                    </multiselect>
                                                </td>
                                                <td>
                                                    <multiselect v-model="agent" :options="agent_options"
                                                        placeholder="Select One" label="name" track-by="id" id="">
                                                    </multiselect>
                                                </td>
                                                <td>
                                                    <input type="text" v-model="due_days" id="due_days"
                                                        class="form-control">
                                                </td>
                                                <td>
                                                    <select v-model="sort_by" class="form-control">
                                                        <option value="">SELECT</option>
                                                        <option value="1">Date: low &gt; high</option>
                                                        <option value="2">Date: high &gt; low</option>
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
                                                <th>Invoice No</th>
                                                <th>Invoice Date</th>
                                                <th>Company</th>
                                                <th>Agent</th>
                                                <th>State</th>
                                                <th class="text-right">Gross Amount</th>
                                                <th class="text-right">CGST</th>
                                                <th class="text-right">SGST</th>
                                                <th class="text-right">IGST</th>
                                                <th class="text-right">Total GST</th>
                                                <th class="text-right">TDS</th>
                                                <th class="text-right">Net Amount</th>
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
    name: 'outstandingInvoice',
    props: {
    },
    components: {
        Multiselect,
        // ViewCompanyDetails
    },
    data() {
        return {
            company_options: [],
            agent_options: [],
            invoice_no: '',
            start_date: '',
            end_date: '',
            company: '',
            agent: '',
            sort_by: '',
            due_days: '',
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
        this.max_date = this.end_date = [y, m, d].join('-');
        this.start_date = '2018-01-01';

        axios.get('/common/list-all-companies')
        .then(response => {
            this.company_options = response.data;
        });
        axios.get('/common/list-all-agents')
        .then(response => {
            this.agent_options = response.data;
        });
    },
    methods: {
        clearData() {
            this.start_date = this.end_date = this.company = this.agent = this.due_days = this.sort_by = '';
        },
        getData() {
            axios.post('/reports/list-commission-invoice-data', {
                start_date: this.start_date,
                end_date: this.end_date,
                company: this.company,
                invoice_no: this.invoice_no,
                agent: this.agent,
                sort_by: this.sort_by,
                due_days: this.due_days,
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
                    var gross_amount = 0, cgst = 0, sgst = 0, igst = 0, total_gst = 0, tds = 0, net_amount = 0, row_gst = 0;
                    response.data.forEach((k, i) => {
                        gross_amount += parseFloat(k.commission_amount);
                        sgst += parseFloat(k.sgst_amount);
                        cgst += parseFloat(k.cgst_amount);
                        igst += parseFloat(k.igst_amount);
                        row_gst = parseFloat(k.cgst_amount) + parseFloat(k.sgst_amount) + parseFloat(k.igst_amount);
                        total_gst += row_gst;
                        tds += parseFloat(k.tds_amount);
                        net_amount += parseFloat(k.final_amount);
                        html += `<tr>
                        <td class=""> <a href="/account/commission/invoice/view-invoice/${k.id}" target="_blank"> ${k.bill_no} </a></td>
                        <td> ${k.bill_date2} </td>
                        <td> ${k.supplier_name ? k.supplier_name : k.customer_name} </td>
                        <td> ${k.agent_name} </td>
                        <td> ${k.state_name} </td>
                        <td class="text-right"> ${toINR.format(k.commission_amount)} </td>
                        <td class="text-right"> ${toINR.format(k.cgst_amount)} </td>
                        <td class="text-right"> ${toINR.format(k.sgst_amount)} </td>
                        <td class="text-right"> ${toINR.format(k.igst_amount)} </td>
                        <td class="text-right"> ${toINR.format(row_gst)} </td>
                        <td class="text-right"> ${toINR.format(k.tds_amount)} </td>
                        <td class="text-right"> ${toINR.format(k.final_amount)} </td>
                    </tr>`;
                    });
                    html += `<tr>
                        <th colspan="5"> Total</th>
                        <th class="text-right"> ${toINR.format(gross_amount)} </th>
                        <th class="text-right"> ${toINR.format(cgst)} </th>
                        <th class="text-right"> ${toINR.format(sgst)} </th>
                        <th class="text-right"> ${toINR.format(igst)} </th>
                        <th class="text-right"> ${toINR.format(total_gst)} </th>
                        <th class="text-right"> ${toINR.format(tds)} </th>
                        <th class="text-right"> ${toINR.format(net_amount)} </th>
                    </tr>`;
                    $('#invoiceDT tbody').html(html);
                } else {
                    $('#invoiceDT tbody').html('<tr><td colspan="12" class="text-center">No Records Found</td></tr>');
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
