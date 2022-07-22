<template>
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">Outstanding Invoice Report</h3>
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
                                                <th width="12%">Inv No</th>
                                                <th width="12%">Start Date</th>
                                                <th width="12%">End Date</th>
                                                <th width="20%">Company</th>
                                                <th width="12%">Agent</th>
                                                <th width="10%">Comm. Status</th>
                                                <th width="5%">Due</th>
                                                <th width="10%">Sort</th>
                                                <th width="15%">Action</th>
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
                                                    <select v-model="comm_status" class="form-control">
                                                        <option value="all">All</option>
                                                        <option value="none">None</option>
                                                        <option value="pending">Pending</option>
                                                    </select>
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
                                                        <option value="3">Commission Amt: low &gt; high</option>
                                                        <option value="4">Commission Amt: high &gt; low</option>
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
                                                <th class="text-right">Amount</th>
                                                <th>Status</th>
                                                <th>Due</th>
                                                <th>Generated By</th>
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
                comm_status: 'all',
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
                this.comm_status = 'all';
            },
            getData() {
                axios.post('/reports/list-outstanding-invoice-data', {
                    start_date: this.start_date,
                    end_date: this.end_date,
                    company: this.company,
                    invoice_no: this.invoice_no,
                    agent: this.agent,
                    sort_by: this.sort_by,
                    due_days: this.due_days,
                    comm_status: this.comm_status,
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
                        var total_amount = 0;
                        var icon = '';
                        response.data.forEach((k, i) => {
                            if (k.commission_status == 0) {
                                icon = '<em class="icon ni ni-cross"></em>';
                            } else if(k.commission_status == 1) {
                                icon = '<em class="icon ni ni-check-thick"></em>';
                            } else { // pending
                                icon = '<em class="icon ni ni-more-h" title="Pending"></em>';
                            }
                            total_amount += parseFloat(k.pending_commission);
                            html += `<tr>
                            <td class=""> <a href="/account/commission/invoice/view-invoice/${k.id}" class=""> ${k.bill_no} </a></td>
                            <td> ${k.bill_date2} </td>
                            <td> ${k.supplier_name ? k.supplier_name : k.customer_name} </td>
                            <td> ${k.agent_name} </td>
                            <td class="text-right"> ${toINR.format(k.pending_commission)} </td>
                            <td> ${icon} </td>
                            <td> ${k.due_days} </td>
                            <td> ${k.employee_name} </td>
                        </tr>`;
                        });
                        html += `<tr>
                            <th colspan="4"> Total</th>
                            <th class="text-right"> ${toINR.format(total_amount)} </th>
                            <th colspan="3"> </th>
                        </tr>`;
                        $('#invoiceDT tbody').html(html);
                    } else {
                        $('#invoiceDT tbody').html('<tr><td colspan="8" class="text-center">No Records Found</td></tr>');
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

            $(document).on('click', '.view-details', function(e) {
                self.showModal($(this).attr('data-id'));
            });

        },
    };
</script>
