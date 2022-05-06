<template>
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">Create Invoice</h3>
                                <div class="nk-block-des text-soft">
                                    <p>Please fill the details.</p>
                                </div>
                            </div><!-- .nk-block-head-content -->
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    <div class="nk-block">
                        <div class="card card-bordered">
                            <div class="card-inner">

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
    import Multiselect from 'vue-multiselect';
    import useVuelidate from '@vuelidate/core';
    import { required, email, requiredIf } from '@vuelidate/validators';

    export default {
        setup () {
            return { v$: useVuelidate() }
        },
        name: 'createCompany',
        components: {
            Multiselect
        },
        props: {
            scope: String,
            id: Number,
        },
        data() {
            return {
                cancel_url: '/account/commission/invoice',
                old_reference_data: '',
                reference_options:[
                    // { name: 'Call'},
                    // { name: 'Whatsapp'},
                    // { name: 'Message'},
                    // { name: 'Letter'},
                    { name: 'Email'},
                    { name: 'Courier'},
                    { name: 'Hand'}
                ],
                sale_bill_options: [
                    { id: 1, name: 'Product'},
                    { id: 2, name: 'Fabric'}
                ],
                product_category_options: [],
                product_sub_category_options: [],
                product_options: [],
                sub_product_options: [[]],
                reference_inward_options: [ { id: 0, name: 'Direct'} ],
                customer_options: [],
                customer_address_options: [],
                supplier_options: [],
                agent_options: [],
                transport_options: [],
                station_options: [],
                change_in_sign_options: [{ name: '+' }, { name: '-' }],
                fabric_options: [],
                pieces_meters_options: [{ id: 1, name: 'Meters' }, { id: 2, name: 'Pieces' }],
                sale_bill_is_moved: 0,
                reference_via: { name: '' },
                new_old_sale_bill: 1,
                from_email: '',
                delivery_by: '',
                reference_new: true,
                reference_id: '',
                isSupplierDisabled: false,
                sale_bill_for: '',
                product_category: '',
                product_sub_category: [],
                reference_inward: { id: 0, name: 'Direct'},
                customer: '',
                customer_address: '',
                supplier: '',
                agent: '',
                supplier_invoice_no: '',
                bill_date: '',
                extra_attachment: '',
                productDetails : [{
                    product_name: '',
                    sub_product_name: '',
                    hsn_code: '',
                    pieces: 0,
                    rate: 0,
                    discount: 0,
                    discount_amount: 0,
                    cgst: 0,
                    cgst_amount: 0,
                    sgst: 0,
                    sgst_amount: 0,
                    igst: 0,
                    igst_amount: 0,
                    amount: 0
                }],
                fabricDetails : [{
                    fabric_name: '',
                    pieces_or_meters: '',
                    hsn_code: '',
                    meters: 0,
                    pieces: 0,
                    rate: 0,
                    discount: 0,
                    discount_amount: 0,
                    cgst: 0,
                    cgst_amount: 0,
                    sgst: 0,
                    sgst_amount: 0,
                    igst: 0,
                    igst_amount: 0,
                    amount: 0
                }],
                totals: {
                    meters: 0,
                    pieces: 0,
                    discount: 0,
                    cgst: 0,
                    sgst: 0,
                    igst: 0,
                    amount: 0
                },
                final_total: 0,
                transport: '',
                station: '',
                lr_mr_no: '',
                transport_date: '',
                transport_cases: '',
                courier_weight: '',
                courier_freight: '',
                change_in_sign: { name: '+' },
                change_in_amount: 0,
                transport_remark: '',
                is_from_name_required: false,
                is_from_email_required: false,
                is_delivery_by_required: false,
                is_reference_via_required: false,
                isSubmitDisabled: false,
            }
        },
        validations () {
            const localRules = {
                supplier: { required },
                product_category: { required },
                agent: { required },
                from_email: { /* required */ },
                delivery_by: { /* required */ },
                reference_via: { requiredIf: requiredIf(this.is_reference_via_required) },
                supplier_invoice_no: { required },
                bill_date: { required },
                station: { required },
                transport_date: { required }
            };
            if (this.reference_via) {
                if (this.reference_via.name == "Email" && this.new_old_sale_bill == 1) {
                    localRules.from_email = { required };
                } else if ((this.reference_via.name == "Courier" || this.reference_via.name == "Hand") && this.new_old_sale_bill == 1) {
                    localRules.delivery_by = { required };
                }
            }
            return localRules;
        },
        created () {
            axios.get('/account/sale-bill/list-customers-and-suppliers')
            .then(response => {
                this.customer_options = response.data[0];
                this.supplier_options = response.data[1];
            });
            axios.get('/account/sale-bill/list-transport')
            .then(response => {
                this.transport_options = response.data;
            });
            axios.get('/account/sale-bill/list-sale-bill-agents')
            .then(response => {
                this.agent = '';
                this.agent_options = response.data;
            });
        },
        methods: {
            resetSupplier (event) {
                $('#new_reference_details_div').show();
                $('#show-references').slideUp();
                this.old_reference_data = '';
                // $('#reference_via').attr('required', true);
                this.bill_date = '';
                $('#bill_date').attr('disabled', false);
                this.customer = '';
                $('#customer').attr('readonly', false);
                this.supplier = '';
                this.isSupplierDisabled = false;
                $('#transport_date').attr('readonly', false);
                this.transport_date = '';
            },
            getOldReferences (event) {
                if (this.reference_via.name == '') {
                    setTimeout(() => {
                        this.new_old_sale_bill = 1;
                        $('#error-validate-reference-div').text('Please select Reference Via');
                        $('#show-references').hide().html('');
                    }, 500);
                    this.isSupplierDisabled = false;
                } else {
                    $('#overlay').show();
                    $('#new_reference_details_div').hide();
                    this.isSupplierDisabled = true;
                    axios.get('/account/sale-bill/getReferenceForSaleBill?sale_bill_via=3&ref_via='+this.reference_via.name)
                    .then(response => {
                        this.old_reference_data = response.data;
                        $('#show-references').slideDown();
                        setTimeout(() => {
                            $('#show-references tr input[type="radio"]').first().prop('checked', true);
                            this.getUpdatedOldReferences();
                        }, 500);
                        $('#overlay').hide();
                    })
                    .catch(function (error) {
                        $('#overlay').hide();
                    });
                }
            },
            getUpdatedOldReferences (event) {
                axios.get('/account/sale-bill/getOldReferenceForSaleBill/'+$('input[name="reference_id_sale_bill"]:checked').val()+'?sale_bill_via=3')
                .then(response2 => {
                    if (response2.data != '') {
                        $('#allhiddenfield_div').html(response2.data);
                        if($('#hidden_sale_bill_date').val() != '') {
                            this.bill_date = $('#hidden_sale_bill_date').val();
                            $('#bill_date').attr('disabled', true);
                        }
                        this.reference_id = $('input[name="reference_id_sale_bill"]:checked').val();
                        this.supplier = { id: $('#hidden_cmp_id').val(), name: $('#hidden_cmp_name').val() };
                        this.isSupplierDisabled = true;
                        if (this.reference_via.name != "Email") {
                            $('#transport_date').attr('readonly', true);
                            this.transport_date = $('#hidden_courier_received_time').val();
                        }
                    } else {
                        this.new_old_sale_bill = 1;
                    }
                });
            },
            getProductMainCategory (event) {
                if (event != null) {
                    axios.get('/account/sale-bill/list-product-main-category/'+event.id)
                    .then(response => {
                        this.product_category = '';
                        this.product_category_options = response.data;
                    });
                    if (event.id == 2) {
                        $('#add_new_fabric').show();
                    }
                }
                $('#product_sub_category_section, #item_details_div').hide();
                $('#product_sub_category_text').show();
                this.resetProductAndFabrics();
            },
            resetProductAndFabrics () {
                this.productDetails = [{
                    product_name: '',
                    sub_product_name: '',
                    hsn_code: '',
                    pieces: 0,
                    rate: 0,
                    discount: 0,
                    discount_amount: 0,
                    cgst: 0,
                    cgst_amount: 0,
                    sgst: 0,
                    sgst_amount: 0,
                    igst: 0,
                    igst_amount: 0,
                    amount: 0
                }];
                this.fabricDetails = [{
                    fabric_name: '',
                    pieces_or_meters: '',
                    hsn_code: '',
                    meters: 0,
                    pieces: 0,
                    rate: 0,
                    discount: 0,
                    discount_amount: 0,
                    cgst: 0,
                    cgst_amount: 0,
                    sgst: 0,
                    sgst_amount: 0,
                    igst: 0,
                    igst_amount: 0,
                    amount: 0
                }];
                this.totals = {
                    meters: 0,
                    pieces: 0,
                    discount: 0,
                    cgst: 0,
                    sgst: 0,
                    igst: 0,
                    amount: 0
                };
                this.final_total = 0;
            },
            getProductSubCategory (event) {
                this.product_category = event;
                if (this.sale_bill_is_moved == 0) {
                    if (this.product_category && this.supplier) {
                        axios.get('/account/sale-bill/list-product-sub-category/'+this.product_category.id+'/'+this.supplier.id)
                        .then(response => {
                            if (response.data.length > 0) {
                                $('#product_sub_category_text').hide();
                                if (this.sale_bill_for.id == 1) {
                                    $('#product_sub_category_section_full, #product_sub_category_section').show();
                                    this.product_sub_category = [];
                                    this.product_sub_category_options = response.data;
                                    $('#add_fabric_details, .dynamic_items_fabrics').hide();
                                    $('#add_new_fabric').hide();
                                }
                                else if (this.sale_bill_for.id == 2) {
                                    $('#product_sub_category_section_full').hide();
                                    this.fabric = [];
                                    this.fabric_options = response.data;
                                    $('#add_product_details, .dynamic_items').hide();
                                    $('#add_fabric_details, .dynamic_items_fabrics').show();
                                    $('#add_new_fabric').show();
                                    $('#item_details_div').slideDown();
                                }
                            } else {
                                $('#product_sub_category_text label').text('Category Related Records Not Found');
                                $('#product_sub_category_section_full, #product_sub_category_text').show();
                                $('#product_sub_category_section').hide();
                            }
                        });
                    }
                }
            },
            getInwardCustomers (event) {
                if (event != null) {
                    axios.get('/account/sale-bill/list-inward-customers/'+event.id)
                    .then(response => {
                        this.reference_inward = '';
                        this.reference_inward_options = response.data;
                    });
                }
            },
            getCustomerAddress (event) {
                if (event != null) {
                    axios.get('/account/sale-bill/list-customer-address/'+event.id)
                    .then(response => {
                        this.customer_address_options = response.data;
                        this.customer_address = response.data[0];

                        axios.get('/account/sale-bill/list-stations/'+this.customer.id)
                        .then(response => {
                            this.stations_options = response.data[0];
                            this.station = response.data[1];

                        });
                    });
                }
            },
            showHideName (event) {
                if (event) {
                    $('#error-validate-reference-div').text('');
                    if (event.name == 'Email') {
                        $('#delivery_by_section').hide();
                        $('#from_email_section').show();
                    } else {
                        $('#delivery_by_section').show();
                        $('#from_email_section').hide();
                    }
                    if (this.new_old_sale_bill == 0) {
                        this.getOldReferences();
                    }
                }
            },
            setCustomer (e) {
                this.$refs.company.isDisabled = true;
                this.$refs.company.form.company_type = {id: 2, name: 'Customer'};
            },
            setSupplier (e) {
                this.$refs.company.isDisabled = true;
                this.$refs.company.form.company_type = {id: 3, name: 'Supplier'};
            },
            uploadAttachment (e) {
                this.extra_attachment = e.target.files[0];
            },
            checkSupplierInvoiceNo (e) {
                if (this.supplier_invoice_no != '' && this.invoice_no != '') {
                    axios.post('/account/sale-bill/check-supplier-invoice', {
                        supplier_id: this.supplier,
                        invoice_no: this.supplier_invoice_no,
                        type: 'insert'
                    })
                    .then (response => {
                        $('#check-supplier-no-div').html(response.data);
                    });
                }
            },
            addProductDetailsRow () {
                this.sub_product_options[this.productDetails.length] = [];
                this.productDetails.push({
                    product_name: '',
                    sub_product_name: '',
                    pieces: 0,
                    rate: 0,
                    discount: 0,
                    discount_amount: 0,
                    cgst: 0,
                    cgst_amount: 0,
                    sgst: 0,
                    sgst_amount: 0,
                    igst: 0,
                    igst_amount: 0,
                    amount: 0
                });
            },
            deleteProductDetailsRow (row) {
                this.productDetails.pop(row);
            },
            addFabricDetailsRow () {
                this.fabricDetails.push({
                    fabric_name: '',
                    pieces_or_meters: '',
                    hsn_code: '',
                    meters: 0,
                    pieces: 0,
                    rate: 0,
                    discount: 0,
                    discount_amount: 0,
                    cgst: 0,
                    cgst_amount: 0,
                    sgst: 0,
                    sgst_amount: 0,
                    igst: 0,
                    igst_amount: 0,
                    amount: 0
                });
            },
            deleteFabricDetailsRow (row) {
                this.fabricDetails.pop(row);
            },
            getProducts (e) {
                var subcategory = '';
                this.product_sub_category.forEach((k, i) => {
                    subcategory += k.id + ',';
                })
                subcategory = subcategory.slice(0, -1);
                axios.get('/account/sale-bill/getProductsFromSubCategory?subcategory='+subcategory+'&maincategory='+this.product_category.id+'&supplier_id='+this.supplier.id)
                .then(response => {
                    this.product = '';
                    if (response.data.length > 0) {
                        this.product_options = response.data;
                        $('#add_product_details, .dynamic_items').slideDown();
                        $('#item_details_div').slideDown();
                    } else {
                        this.product_options = [];
                    }
                });
            },
            getSubProducts (i) {
                if (this.productDetails[i].product_name.id) {
                    axios.get('/account/sale-bill/getSubProductFromProduct?product_id='+this.productDetails[i].product_name.id)
                    .then(response => {
                        this.sub_product_options[i] = response.data;
                    });
                }
            },
            getSubProductRate (i) {
                this.productDetails[i].rate = this.productDetails[i].sub_product_name.price;
            },
            calculateTotalProducts (i) {
                var pd = this.productDetails[i];
                pd.amount = parseFloat(pd.pieces * pd.rate);
                pd.discount_amount = parseFloat((pd.discount > 0 ? (pd.amount * pd.discount / 100) : 0).toFixed(2));
                pd.amount = parseFloat(pd.amount - pd.discount_amount);
                pd.cgst_amount = parseFloat((pd.cgst > 0 ? (pd.amount * pd.cgst / 100) : 0).toFixed(2));
                pd.sgst_amount = parseFloat((pd.sgst > 0 ? (pd.amount * pd.sgst / 100) : 0).toFixed(2));
                pd.igst_amount = parseFloat((pd.igst > 0 ? (pd.amount * pd.igst / 100) : 0).toFixed(2));
                pd.amount = Math.round(parseFloat(pd.amount + pd.cgst_amount + pd.sgst_amount + pd.igst_amount));
                this.totals.pieces = this.totals.discount = this.totals.cgst = this.totals.sgst = this.totals.igst = this.totals.amount = 0;
                this.productDetails.forEach((k, i) => {
                    this.totals.pieces = parseInt(this.totals.pieces) + parseInt(k.pieces);
                    this.totals.discount = parseFloat(this.totals.discount) + parseFloat(k.discount_amount.toFixed(2));
                    this.totals.cgst = parseFloat(this.totals.cgst) + parseFloat(k.cgst_amount);
                    this.totals.sgst = parseFloat(this.totals.sgst) + parseFloat(k.sgst_amount);
                    this.totals.igst = parseFloat(this.totals.igst) + parseFloat(k.igst_amount);
                    this.totals.amount = parseInt(this.totals.amount) + parseInt(k.amount);
                });
                this.getChangeAmount();
            },
            calculateTotalFabrics (i) {
                var pd = this.fabricDetails[i];
                if (pd.pieces_or_meters.id == 1) {
                    pd.amount = parseFloat(pd.meters * pd.rate);
                } else {
                    pd.amount = parseFloat(pd.pieces * pd.rate);
                }
                pd.discount_amount = parseFloat((pd.discount > 0 ? (pd.amount * pd.discount / 100) : 0).toFixed(2));
                pd.amount = parseFloat(pd.amount - pd.discount_amount);
                pd.cgst_amount = parseFloat((pd.cgst > 0 ? (pd.amount * pd.cgst / 100) : 0).toFixed(2));
                pd.sgst_amount = parseFloat((pd.sgst > 0 ? (pd.amount * pd.sgst / 100) : 0).toFixed(2));
                pd.igst_amount = parseFloat((pd.igst > 0 ? (pd.amount * pd.igst / 100) : 0).toFixed(2));
                pd.amount = Math.round(parseFloat(pd.amount + pd.cgst_amount + pd.sgst_amount + pd.igst_amount));
                this.totals.meters = this.totals.pieces = this.totals.discount = this.totals.cgst = this.totals.sgst = this.totals.igst = this.totals.amount = 0;
                this.fabricDetails.forEach((k, i) => {
                    this.totals.meters = parseFloat(this.totals.meters) + parseFloat(k.meters);
                    this.totals.pieces = parseInt(this.totals.pieces) + parseInt(k.pieces);
                    this.totals.discount = parseFloat(this.totals.discount) + parseFloat(k.discount_amount.toFixed(2));
                    this.totals.cgst = parseFloat(this.totals.cgst) + parseFloat(k.cgst_amount);
                    this.totals.sgst = parseFloat(this.totals.sgst) + parseFloat(k.sgst_amount);
                    this.totals.igst = parseFloat(this.totals.igst) + parseFloat(k.igst_amount);
                    this.totals.amount = parseInt(this.totals.amount) + parseInt(k.amount);
                });
                this.getChangeAmount();
            },
            getChangeAmount (e) {
                if (this.change_in_sign.name == '+') {
                    this.final_total = parseInt(this.totals.amount) + parseInt(this.change_in_amount);
                } else {
                    this.final_total = parseInt(this.totals.amount) - parseInt(this.change_in_amount);
                }
            },

            register () {
                var formData = new FormData();
                var transportDetails = {
                    transport: this.transport,
                    station: this.station,
                    lr_mr_no: this.lr_mr_no,
                    transport_date: this.transport_date,
                    transport_cases: this.transport_cases,
                    courier_weight: this.courier_weight,
                    courier_freight: this.courier_freight,
                };
                var referenceDetails = {
                    reference_via: this.reference_via,
                    new_old_sale_bill: this.new_old_sale_bill,
                    from_email: this.from_email,
                    delivery_by: this.delivery_by,
                    reference_new: this.reference_new,
                    reference_id: this.reference_id,
                    sale_bill_for: this.sale_bill_for,
                    product_category: this.product_category,
                    product_sub_category: this.product_sub_category,
                    reference_inward: this.reference_inward,
                    customer: this.customer,
                    customer_address: this.customer_address,
                    supplier: this.supplier,
                    agent: this.agent,
                    supplier_invoice_no: this.supplier_invoice_no,
                    bill_date: this.bill_date,
                };
                var changeAmount = {
                    change_in_sign: this.change_in_sign,
                    change_in_amount: this.change_in_amount,
                    transport_remark: this.transport_remark
                };
                formData.append('referenceDetails', JSON.stringify(referenceDetails));
                formData.append('productDetails', JSON.stringify(this.productDetails));
                formData.append('fabricDetails', JSON.stringify(this.fabricDetails));
                formData.append('totals', JSON.stringify(this.totals));
                formData.append('transportDetails', JSON.stringify(transportDetails));
                formData.append('changeAmount', JSON.stringify(changeAmount));
                formData.append('final_total', this.final_total);
                formData.append('extra_attachment', this.extra_attachment);

                axios.post('/account/sale-bill/create-sale-bill/create', formData)
                .then(response => {
                    window.location.href = '/account/sale-bill';
                })
                .catch((error) => {
                    /* var validationError = error.response.data.errors;
                    if(validationError) {
                        $('#supplier').focus();
                        this.errors.supplier = validationError;
                    } */
                });
            },
            async submitForm () {
                const isFormCorrect = await this.v$.$validate();
                console.log(isFormCorrect);
                // you can show some extra alert to the user or just leave the each field to show it's `$errors`.
                if (!isFormCorrect) return;
                // actually submit form
                this.register();
            }
        },
        mounted() {
            const self = this;

            $(document).on('change', 'input[name=reference_id_sale_bill]', function () {
                self.getUpdatedOldReferences();
            });

            $(document).on('click', '#sale_bill_ref_search_btn', function() {
                axios.get('/account/sale-bill/getOldReferenceForSaleBill/'+$('#sale_bill_ref_search').val()+'?sale_bill_via=3')
                .then(response2 => {
                    if (response2.data != '') {
                        $('#allhiddenfield_div').html(response2.data);
                        if($('#hidden_sale_bill_date').val() != '') {
                            this.bill_date = $('#hidden_sale_bill_date').val();
                            $('#bill_date').attr('disabled', true);
                        }
                        $('#sale_bill_ref_msg').html('<td><div class="custom-control custom-radio"><input class="custom-control-input" type="radio" name="reference_id_sale_bill" value="'+$('#hidden_reference_id_input').val()+'" id="r-'+$('#hidden_reference_id_input').val()+'"><label class="custom-control-label" for="r-'+$('#hidden_reference_id_input').val()+'"></label></div></td><td>'+$('#hidden_reference_id_input').val()+'</td><td>'+$('#hidden_ref_emp_name').val()+'</td><td>'+$('#hidden_ref_date_added').val()+'</td><td>'+$('#hidden_ref_time_added').val()+'</td>');
                        this.supplier = { id: $('#hidden_cmp_id').val(), name: $('#hidden_cmp_name').val() };
                        this.isSupplierDisabled = true;
                        $('#show-references tr input[type="radio"]').last().prop('checked', true);
                        $('#transport_date').attr('readonly', true);
                        this.transport_date = $('#hidden_courier_received_time').val();
                        this.reference_id = $('input[name="reference_id_sale_bill"]:checked').val();
                    } else {
                        this.new_old_sale_bill = 1;
                        $('#sale_bill_ref_msg').html('<td colspan="5">This Reference Id is not generated by Email, Courier OR Hand.</td>');
                    }
                });

            });

        },
    };
</script>
<style scoped>
    .hidden {
        display: none;
    }
    #show-references {
        padding-top: 0 !important;
        padding-bottom: 0 !important;
    }
    .item_details_div label {
        margin-bottom: 5px;
    }
    .dynamic_items .col-sm-1, .dynamic_items_fabrics .col-sm-1 {
        text-align: center;
    }
    .dynamic_items .form-control, .dynamic_items_fabrics .form-control {
        padding: 0.4375rem 0.6rem;
    }

</style>

