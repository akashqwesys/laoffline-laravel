<template>
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">View Sale Bill</h3>
                            </div><!-- .nk-block-head-content -->
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    <div class="nk-block">
                        <div class="text-right">
                            <b> Generated By: </b>
                            <span> {{ generated_by }} </span>
                            <b class="ml-4"> Date Added: </b>
                            <span class="mr-4"> {{ date_added }} </span>
                            <span v-if="date_updated" class="">
                                <b> Updated By: </b>
                                <span> {{ updated_by }} </span>
                                <b class="ml-4"> Date Updated: </b>
                                <span class="mr-3"> {{ date_updated }} </span>
                            </span>
                        </div>
                        <div class="card card-bordered" id="printable-content">
                            <div class="card-inner">
                                <div class="preview-block">
                                    <div class="form-group">
                                        <p><b>Subject:</b> {{ subject }} </p>
                                    </div>
                                    <div class="row gy-4">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <p><b>IUID:</b> {{ iuid }} </p>
                                            </div>
                                            <div class="form-group">
                                                <p><b>Customer:</b> <span v-html="customer"></span> </p>
                                            </div>
                                            <div class="form-group">
                                                <p><b>Address:</b> {{ customer_address }} </p>
                                            </div>
                                            <div class="form-group">
                                                <p><b>Sale Bill For:</b> {{ sale_bill_for }} </p>
                                            </div>
                                            <div class="form-group">
                                                <p>
                                                    <b>Extra Attachment:</b>
                                                    <span v-if="extra_attachment">
                                                        <a :href="'/upload/sale_bill/'+extra_attachment" target="_blank">
                                                            <img height="65" width="50" id="preview-img" src="" style="opacity: 0.5; padding-top: 5px;">
                                                        </a>
                                                    </span>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <p><b>Generated Date:</b> {{ generated_date }} </p>
                                            </div>
                                            <div class="form-group">
                                                <p><b>Sr No:</b> {{ sr_no }} </p>
                                            </div>
                                            <div class="form-group">
                                                <p><b>Supplier Invoice No:</b> {{ supplier_invoice_no }} </p>
                                            </div>
                                            <div class="form-group">
                                                <p><b>Product Main Category:</b> {{ product_category }} </p>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <p><b>Supplier:</b> <span v-html="supplier"></span> </p>
                                            </div>
                                            <div class="form-group">
                                                <p><b>Bill Date:</b> {{ bill_date }} </p>
                                            </div>
                                            <div class="form-group">
                                                <p><b>Agent:</b> {{ agent }} </p>
                                            </div>
                                            <div class="form-group" v-if="product_sub_category">
                                                <p><b>Product Sub Category:</b> {{ product_sub_category }} </p>
                                            </div>
                                        </div>
                                    </div>
                                    <hr class="mt-5 mb-5">
                                    <div>
                                        <h6 class="mb-2">Item Details</h6>
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>HSN</th>
                                                    <th class="text-right">Pieces</th>
                                                    <th class="meters_cols text-right">Meters</th>
                                                    <th class="text-right">Rate</th>
                                                    <th class="text-right">Discount %</th>
                                                    <th class="text-right">Discount Amt</th>
                                                    <th class="text-right">CGST %</th>
                                                    <th class="text-right">CGST Amt</th>
                                                    <th class="text-right">SGST %</th>
                                                    <th class="text-right">SGST Amt</th>
                                                    <th class="text-right">IGST %</th>
                                                    <th class="text-right">IGST Amt</th>
                                                    <th class="text-right">Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(k, i) in itemDetails" :key="i">
                                                    <td> {{ k.item_name }} </td>
                                                    <td> {{ k.hsn }} </td>
                                                    <td class="text-right"> {{ k.pieces }} </td>
                                                    <td class="meters_cols text-right"> {{ k.meters }} </td>
                                                    <td class="text-right"> {{ k.rate }} </td>
                                                    <td class="text-right"> {{ k.discount }} </td>
                                                    <td class="text-right"> {{ k.discount_amount }} </td>
                                                    <td class="text-right"> {{ k.cgst }} </td>
                                                    <td class="text-right"> {{ k.cgst_amount }} </td>
                                                    <td class="text-right"> {{ k.sgst }} </td>
                                                    <td class="text-right"> {{ k.sgst_amount }} </td>
                                                    <td class="text-right"> {{ k.igst }} </td>
                                                    <td class="text-right"> {{ k.igst_amount }} </td>
                                                    <td class="text-right"> {{ k.amount }} </td>
                                                </tr>
                                            </tbody>
                                            <tfoot style="border-top: 1px solid lightgray;">
                                                <tr>
                                                    <th colspan="2">Total</th>
                                                    <th class="text-right">{{ total_peices }}</th>
                                                    <th class="meters_cols text-right">{{ total_meters }}</th>
                                                    <th class="text-right"></th>
                                                    <th class="text-right"></th>
                                                    <th class="text-right">{{ total_discount }}</th>
                                                    <th class="text-right"></th>
                                                    <th class="text-right">{{ total_cgst }}</th>
                                                    <th class="text-right"></th>
                                                    <th class="text-right">{{ total_sgst }}</th>
                                                    <th class="text-right"></th>
                                                    <th class="text-right">{{ total_igst }}</th>
                                                    <th class="text-right pr-2">{{ total_amount }}</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    <hr class="mt-5 mb-5">
                                    <div><h6 class="mb-3">Transport Details</h6></div>
                                    <div class="row gy-4">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <p><b>Transport:</b> {{ transport }} </p>
                                            </div>
                                            <div class="form-group">
                                                <p><b>LR/MR Date:</b> {{ lr_mr_date }} </p>
                                            </div>
                                            <div class="form-group">
                                                <p><b>Freight:</b> {{ freight }} </p>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <p><b>Station/To:</b> {{ station }} </p>
                                            </div>
                                            <div class="form-group">
                                                <p><b>Cases:</b> {{ cases }} </p>
                                            </div>
                                            <div class="form-group">
                                                <p><b>Change in Amount:</b> {{ change_in_amount }} </p>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <p><b>LR/MR No:</b> {{ lr_mr_no }} </p>
                                            </div>
                                            <div class="form-group">
                                                <p><b>Weight:</b> {{ weight }} </p>
                                            </div>
                                        </div>
                                    </div>
                                    <hr class="mt-3 mb-3">
                                    <div class="text-right form-group"><b>Total: {{ total_amount }}/- </b></div>
                                    <div class="text-right form-group"><b>Total (in words): {{ total_amount_words }}/- </b></div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center mt-3"><button class="btn btn-primary" id="print-bill">PRINT</button></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <ViewCompanyDetails ref="company"></ViewCompanyDetails>
</template>
<script>
    import ViewCompanyDetails from '../../databank/companyComponents/modal/ViewCompanyDetailsModelComponent.vue';
    import $ from 'jquery';

    export default {
        name: 'viewSaleBill',
        props: {
            sale_bill_id: Number,
            fid: Number
        },
        components: {
            ViewCompanyDetails,
        },
        data() {
            return {
                generated_by: '',
                date_added: '',
                updated_by: '',
                date_updated: '',
                subject: '',
                iuid: '',
                customer: '',
                customer_address: '',
                supplier: '',
                sale_bill_for: '',
                extra_attachment: '',
                generated_date: '',
                sr_no: '',
                supplier_invoice_no: '',
                product_category: '',
                product_sub_category: '',
                bill_date: '',
                agent: '',
                transport: '',
                lr_mr_date: '',
                lr_mr_no: '',
                freight: '',
                weight: '',
                station: '',
                cases: '',
                change_in_amount: '',
                itemDetails: [{
                    item_name: '',
                    hsn: '',
                    pieces: 0,
                    meters: 0,
                    rate: 0,
                    discount: 0,
                    discount_amount: 0,
                    cgst: 0,
                    cgst_amount: 0,
                    sgst: 0,
                    sgst_amount: 0,
                    igst: 0,
                    igst_amount: 0,
                    amount: 0,
                }],
                total_meters: 0,
                total_peices: 0,
                total_discount: 0,
                total_cgst: 0,
                total_sgst: 0,
                total_igst: 0,
                total_amount: 0,
                total_amount_words: '',
            }
        },
        created () {
            const toINR = new Intl.NumberFormat('en-IN', {
                style: 'currency',
                currency: 'INR',
                minimumFractionDigits: 0
            });
            axios.get('/account/sale-bill/view-sale-bill-details/'+this.sale_bill_id+'/'+this.fid)
            .then(response => {
                var data = response.data;
                this.generated_by = data.generated_by ? (data.generated_by.firstname + ' - ' + data.generated_by.lastname) : '';
                this.date_added = data.generated_at;
                this.updated_by = data.updated_by ? (data.updated_by.firstname + ' - ' + data.updated_by.lastname) : '';
                this.date_updated = data.updated_at;
                this.subject = data.subject;
                this.iuid = data.sale_bill.iuid;
                this.customer = data.customer;
                this.customer_address = data.address.address ?? '- - -';
                this.sale_bill_for = data.sale_bill.sale_bill_for == 1 ? 'Product' : 'Fabric';
                if (data.sale_bill.attachment) {
                    this.extra_attachment = data.sale_bill.attachment;
                    var ext = data.sale_bill.attachment.split('.');
                    setTimeout(() => {
                        if (ext.includes('mp3') || ext.includes('amr')) {
                            $('#preview-img').attr('src', '/assets/images/icons/mp3.jpg');
                        } else if (ext.includes('pdf') || ext.includes('docx')) {
                            $('#preview-img').attr('src', '/assets/images/icons/pdf.png');
                        } else if (ext.includes('txt')) {
                            $('#preview-img').attr('src', '/assets/images/icons/txt.jpg');
                        } else {
                            $('#preview-img').attr('src', '/assets/images/icons/txt.jpg');
                        }
                    }, 500);
                }
                this.generated_date = data.generated_at;
                this.sr_no = data.sale_bill.sale_bill_id;
                this.supplier_invoice_no = data.sale_bill.supplier_invoice_no;
                this.supplier = data.supplier;
                this.bill_date = data.bill_date;
                this.agent = data.sale_bill.agent_name;
                this.product_category = data.product_main;
                this.product_sub_category = data.product_sub;
                this.change_in_amount = '(' + data.sale_bill.sign_change + ') ' + data.sale_bill.change_in_amount;
                this.transport = data.sale_bill_transports ? data.sale_bill_transports.name : '';
                this.station = data.station;
                this.lr_mr_date = data.lr_mr_date;
                this.lr_mr_no = data.sale_bill_transports ? data.sale_bill_transports.lr_mr_no : '';
                this.cases = data.sale_bill_transports ? data.sale_bill_transports.cases : '';
                this.weight = data.sale_bill_transports ? data.sale_bill_transports.weight : '';
                this.freight = data.sale_bill_transports ? data.sale_bill_transports.freight : '';
                this.total_meters = data.sale_bill.total_meters;
                this.total_peices = data.sale_bill.total_peices;
                this.total_amount = toINR.format(data.sale_bill.total);
                this.total_amount_words = data.total_amount_words + ' Only';

                data.sale_bill_items.forEach((k, i) => {
                    this.itemDetails[i] = {
                        item_name: k.product_name,
                        hsn: k.hsn_code,
                        pieces: k.pieces,
                        meters: k.meters,
                        rate: k.rate,
                        discount: k.discount,
                        discount_amount: parseFloat(k.discount_amount),
                        cgst: k.cgst,
                        cgst_amount: parseFloat(k.cgst_amount),
                        sgst: k.sgst,
                        sgst_amount: parseFloat(k.sgst_amount),
                        igst: k.igst,
                        igst_amount: parseFloat(k.igst_amount),
                        amount: k.amount
                    };
                    this.total_discount += parseFloat(k.discount_amount);
                    this.total_cgst += parseFloat(k.cgst_amount);
                    this.total_sgst += parseFloat(k.sgst_amount);
                    this.total_igst += parseFloat(k.igst_amount);
                });
                setTimeout(() => {
                    this.total_discount = this.total_discount.toFixed(2);
                    this.total_cgst = this.total_cgst.toFixed(2);
                    this.total_sgst = this.total_sgst.toFixed(2);
                    this.total_igst = this.total_igst.toFixed(2);
                    if (data.sale_bill.sale_bill_for == 1) {
                        $('.meters_cols').hide();
                    }
                }, 500);
            });
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
            }
        },
        mounted() {
            const self = this;
            // var today = new Date();
            // var dt = today.toJSON().slice(0, 10);
            // var nDate = dt.slice(0, 4) + '-' + dt.slice(5, 7) + '-' + dt.slice(8, 10);
            /* if (this.sale_bill_for == 1) {
                $('.meters_cols').hide();
            } */
            $(document).on('click', '#print-bill', function(e) {
                var printContents = document.getElementById('printable-content').innerHTML;
                var popupWin = window.open('', '_blank', 'width=900,height=900');
                popupWin.document.open();
                popupWin.document.write('<html><head><link rel="stylesheet" href="/assets/css/dashlite.css"><link rel="stylesheet" href="/assets/css/custom.css"></head><body onload="window.print()"><div class="text-center mt-2"><h5><img src="/assets/images/favicon.png" class="pb-1 mr-2" width="35"> LAOFFLINE Sale Bill: '+self.sr_no+'</h5></div>' + printContents + '</html>');
                popupWin.document.close();
            });

            $(document).on('click', '.view-details', function(e) {
                self.showModal($(this).attr('data-id'));
            });
            document.getElementById('viewCompany1').addEventListener('hidden.bs.modal', function (event) {
                $('.modal-backdrop').remove();
            });
        },
    };
</script>

