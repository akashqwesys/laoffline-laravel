<template>
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">Sales Bill Details Report</h3>
                                <div class="nk-block-des text-soft"> </div>
                            </div><!-- .nk-block-head-content -->
                            <div class="nk-block-head-content">
                                <div class="toggle-wrap nk-block-tools-toggle">
                                    <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1"
                                        data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                    <div class="toggle-expand-content" data-content="pageMenu">
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
                                        <tbody>
                                            <tr>
                                                <td width="15%">
                                                    <select v-model="search_type" class="form-control" @change="showHideOption">
                                                        <option value="day">Day Wise</option>
                                                        <option value="month">Month Wise</option>
                                                        <option value="year">Year Wise</option>
                                                    </select>
                                                </td>
                                                <td width="20%" id="selected_date_td">
                                                    <input type="date" v-model="selected_date" id="selected_date" class="form-control" autocomplete="off" onfocus="this.showPicker();" :max="max_date">
                                                </td>
                                                <td width="20%" id="selected_month_td" class="hidden">
                                                    <select v-model="selected_month" id="selected_month" class="form-control">
                                                        <option value="01" selected>January</option>
                                                        <option value="02">February</option>
                                                        <option value="03">March</option>
                                                        <option value="04">April</option>
                                                        <option value="05">May</option>
                                                        <option value="06">June</option>
                                                        <option value="07">July</option>
                                                        <option value="08">August</option>
                                                        <option value="09">September</option>
                                                        <option value="10">October</option>
                                                        <option value="11">November</option>
                                                        <option value="12">December</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <button class="btn btn-primary btn-round mr-1" @click="getData()">Go</button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="table-responsive" id="salesRegisterDT-div">
                                    <table id="salesRegister" class="table table-hover table-bordered-">
                                        <thead>
                                            <tr class="">
                                                <th width="8%">Date</th>
                                                <th>Sr</th>
                                                <th>Party</th>
                                                <th class="text-right">Pieces</th>
                                                <th class="text-right">Meters</th>
                                                <th class="text-right">Net Amt</th>
                                                <th>Agent</th>
                                                <th>Invoice</th>
                                                <th class="text-right">Gross Amt</th>
                                                <th>Transport</th>
                                                <th>City</th>
                                                <th>L.R.No.</th>
                                                <th>Purchase Party</th>
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
    <ViewCompanyDetails ref="company"></ViewCompanyDetails>
</template>

<script>
    import ViewCompanyDetails from '../databank/companyComponents/modal/ViewCompanyDetailsModelComponent.vue';

    import $ from 'jquery';
    import 'datatables.net-responsive-bs4/js/responsive.bootstrap4';
    import "datatables.net-buttons-bs5/js/buttons.bootstrap5";
    import Multiselect from 'vue-multiselect';

    export default {
        name: 'salebillDetails',
        props: {
        },
        components: {
            Multiselect,
            ViewCompanyDetails
        },
        data() {
            return {
                search_type: 'day',
                selected_date: '',
                selected_month: '01',
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
            this.max_date = this.selected_date = [y, m, d].join('-');
        },
        methods: {
            showModal: function(id) {
                window.$('#overlay').show();
                this.$refs.company.fetch_company(id)
                window.$("#viewCompany1").modal('show');
                $('<div class="modal-backdrop fade show"></div>').appendTo(document.body);
                $('body').addClass('modal-open').css('overflow', 'hidden').css('padding-right', '17px');
            },
            closeModal: function() {
                window.$('#viewCompany1').modal('hide');
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open').removeAttr('style');
            },
            showHideOption (e) {
                if(e.target.value == "day") {
                    $('#selected_date_td').show();
                    $('#selected_month_td').hide();
                } else if(e.target.value == "month") {
                    $('#selected_date_td').hide();
                    $('#selected_month_td').show();
                } else if(e.target.value == "year") {
                    $('#selected_date_td').hide();
                    $('#selected_month_td').hide();
                }
            },
            getData() {
                axios.post('/reports/list-salebill-details-data', {
                    search_type: this.search_type,
                    selected_date: this.selected_date,
                    selected_month: this.selected_month,
                    export_sheet: this.export_sheet,
                    export_pdf: this.export_pdf
                })
                .then(response => {
                    if (this.export_sheet == 1 || this.export_pdf == 1) {
                        this.export_sheet = this.export_pdf = 0;
                        window.open(response.data.url, '_blank');
                        return;
                    }
                    var data = response.data;
                    if (data.length > 0) {
                        const toINR = new Intl.NumberFormat('en-IN', { });
                        var html = '', sub_html = '';
                        var total_pieces = 0, total_meters = 0, net_total = 0, gross_total = 0, gross_amount = 0;
                        var sub_total_pieces = 0, sub_total_meters = 0, sub_total_amount = 0;

                        data.forEach((k, i) => {
                            if (i < (data.length - 1) && k.sale_bill_id == data[i+1].sale_bill_id) {
                                sub_html += `<tr>
                                    <td>${k.product_name}</td>
                                    <td class="text-right">${k.pieces}</td>
                                    <td class="text-right">${k.meters}</td>
                                    <td class="text-right">${k.rate}</td>
                                    <td class="text-right">${toINR.format(k.amount)}</td>
                                </tr>`;
                                sub_total_pieces += parseFloat(k.pieces);
                                sub_total_meters += parseFloat(k.meters)
                                sub_total_amount += parseFloat(k.amount);
                                return;
                            } else {
                                sub_total_pieces += parseFloat(k.pieces);
                                sub_total_meters += parseFloat(k.meters)
                                sub_total_amount += parseFloat(k.amount);
                                sub_html += `<tr>
                                    <td>${k.product_name}</td>
                                    <td class="text-right">${k.pieces}</td>
                                    <td class="text-right">${k.meters}</td>
                                    <td class="text-right">${k.rate}</td>
                                    <td class="text-right">${toINR.format(k.amount)}</td>
                                </tr>`;
                            }
                            total_pieces += parseFloat(k.pieces);
                            total_meters += parseFloat(k.meters);
                            net_total += parseFloat(k.total);
                            if (k.sign_change == '+') {
                                gross_amount = (parseFloat(k.total) - parseFloat(k.change_in_amount));
                            } else {
                                gross_amount = (parseFloat(k.total) + parseFloat(k.change_in_amount));
                            }
                            gross_total += gross_amount;

                            html += `<tr>
                                <td class=""> ${k.select_date} </td>
                                <td class=""> <a href="/account/sale-bill/view-sale-bill/${k.sale_bill_id}/${k.financial_year_id}" class="" data-toggle="tooltip" data-placement="top" title="View"> ${k.sale_bill_id} </a></td>
                                <td class=""> <a href="#" class="view-details" data-id="${k.company_id}"> ${k.customer_name} </a> </td>
                                <td class="text-right"> ${sub_total_pieces} </td>
                                <td class="text-right"> ${sub_total_meters} </td>
                                <td class="text-right"> ${toINR.format(k.total)} </td>
                                <td class=""> ${k.agent_name} </td>
                                <td class=""> ${k.supplier_invoice_no} </td>
                                <td class="text-right"> ${toINR.format(gross_amount)} </td>
                                <td class=""> ${k.transport_name} </td>
                                <td class=""> ${k.city_name} </td>
                                <td class=""> ${k.lr_mr_no} </td>
                                <td class=""> <a href="#" class="view-details" data-id="${k.company_id}"> ${k.supplier_name} </a> </td>
                            </tr>
                            <tr>
                                <td colspan="13">
                                    <table class="table table-bordered w-80" align="center">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th class="text-right">Pieces</th>
                                                <th class="text-right">Meters</th>
                                                <th class="text-right">Rate</th>
                                                <th class="text-right">Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody> ${sub_html}
                                            <tr>
                                                <th>Total</th>
                                                <th class="text-right">${sub_total_pieces}</th>
                                                <th class="text-right">${sub_total_meters}</th>
                                                <th class="text-right">Amount</th>
                                                <th class="text-right">${toINR.format(sub_total_amount)}</th>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr><td colspan="13"><br> </td></tr>`;
                            sub_html = '';
                            sub_total_pieces = 0, sub_total_meters = 0, sub_total_amount = 0;
                        });
                        html += `<tr class="position-sticky-bottom bg-light">
                            <th class=""> Grand Total</th>
                            <th class=""> </th>
                            <th class=""> </th>
                            <th class="text-right"> ${total_pieces} </th>
                            <th class="text-right"> ${total_meters.toFixed(2)} </th>
                            <th class="text-right"> ${toINR.format(net_total)} </th>
                            <th class=""> </th>
                            <th class=""> </th>
                            <th class="text-right"> ${toINR.format(gross_total)} </th>
                            <th class=""> </th>
                            <th class=""> </th>
                            <th class=""> </th>
                            <th class=""> </th>
                        </tr>`;
                        $('#salesRegister tbody').html(html);
                    } else {
                        if (this.show_detail == 1) {
                            $('#salesRegister tbody').html('<tr><td colspan="4" class="text-center">No Records Found</td></tr>');
                        } else {
                            $('#salesRegister tbody').html('<tr><td colspan="15" class="text-center">No Records Found</td></tr>');
                        }
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
            // this.init_dt_table();

            $(document).on('click', '.view-details', function(e) {
                self.showModal($(this).attr('data-id'));
            });
            document.getElementById('viewCompany1').addEventListener('hidden.bs.modal', function (event) {
                $('.modal-backdrop').remove();
            });

        },
    };
</script>
