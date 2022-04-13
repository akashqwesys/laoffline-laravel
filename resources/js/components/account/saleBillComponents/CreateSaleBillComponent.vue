<template>
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">Create Sale Bill</h3>
                                <div class="nk-block-des text-soft">
                                    <p>Please fill the details.</p>
                                </div>
                            </div><!-- .nk-block-head-content -->
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    <div class="nk-block">
                        <div class="card card-bordered">
                            <div class="card-inner">
                                <form action="#" class="form-validate" @submit.prevent="register()">
                                    <div id="allhiddenfield_div"></div>
                                    <div class="preview-block">
                                        <span class="preview-title-lg overline-title">Reference Details</span>
                                        <div class="row gy-4">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="fw-reference_via">Reference Via</label>
                                                    <div class="form-control-wrap">
                                                        <multiselect v-model="reference_via" :options="reference_options" placeholder="Select One" label="name" track-by="name" id="reference_via" @select="showHideName"></multiselect>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="preview-block">
                                                    <label class="form-label">Reference</label>
                                                    <ul class="custom-control-group g-3 align-center" id="validate-reference-div">
                                                        <li class="w-25">
                                                            <div class="custom-control custom-radio">
                                                                <input v-model="new_old_sale_bill" type="radio" class="custom-control-input"  id="fv-reference_new" value="1" @click="reference_new = true" @change="resetSupplier">
                                                                <label class="custom-control-label" for="fv-reference_new">NEW</label>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="custom-control custom-radio">
                                                                <input v-model="new_old_sale_bill" type="radio" class="custom-control-input"  id="fv-reference_old" value="0" @click="reference_new = false" @change="getOldReferences">
                                                                <label class="custom-control-label" for="fv-reference_old">OLD</label>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                    <div id="error-validate-reference-div" class="mt-2 text-danger"></div>
                                                </div>
                                            </div>
                                            <div id="new_reference_details_div" class="col-md-4">
                                                <div class="hidden" id="from_email_section">
                                                    <div class="form-group">
                                                        <label class="form-label" for="from_email">From Email ID</label>
                                                        <div class="form-control-wrap">
                                                            <input type="email" v-model="from_email" id="from_email" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="" id="delivery_by_section">
                                                    <div class="form-group">
                                                        <label class="form-label" for="delivery_by">Delivery By</label>
                                                        <div class="form-control-wrap">
                                                            <input type="text" v-model="delivery_by" id="delivery_by" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="sale_bill_for">Sale Bill For</label>
                                                    <div class="form-control-wrap">
                                                        <multiselect v-model="sale_bill_for" :options="sale_bill_options" placeholder="Select One" label="name" track-by="id" id="sale_bill_for" @select="getProductMainCategory"></multiselect>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 text-center hidden" id="show-references" v-html="old_reference_data"></div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="product_category">Product Main Category</label>
                                                    <div class="form-control-wrap">
                                                        <multiselect v-model="product_category" :options="product_category_options" placeholder="Select One" label="name" track-by="id" id="product_category" @close="getProductSubCategory"></multiselect>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="reference_inward">Inward</label>
                                                    <div class="form-control-wrap">
                                                        <multiselect v-model="reference_inward" :options="reference_inward_options" placeholder="Select One" label="name" track-by="id" id="reference_inward" @change="getInwardCustomers"></multiselect>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="customer">Customer</label>
                                                    <button type="button" class="btn btn-primary float-right clipboard-init badge" data-toggle="modal" data-target="#addCompany" title="Add New Customer" @click="setCustomer"><span class="clipboard-text">Add New</span></button>
                                                    <div class="form-control-wrap">
                                                        <multiselect v-model="customer" :options="customer_options" placeholder="Select One" label="name" track-by="id" id="customer" @select="getCustomerAddress"></multiselect>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="customer_address">Customer Address</label>
                                                    <div class="form-control-wrap">
                                                        <multiselect v-model="customer_address" :options="customer_address_options" placeholder="Select One" label="name" track-by="id" id="customer_address" ></multiselect>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="supplier">Supplier</label>
                                                    <button type="button" class="btn btn-primary float-right clipboard-init badge" data-toggle="modal" data-target="#addCompany" title="Add New Supplier" @click="setSupplier" :disabled="isSupplierDisabled"><span class="clipboard-text">Add New</span></button>
                                                    <div class="form-control-wrap">
                                                        <multiselect v-model="supplier" :options="supplier_options" placeholder="Select One" label="name" track-by="id" id="supplier" @close="getProductSubCategory(), checkSupplierInvoiceNo()" :disabled="isSupplierDisabled"></multiselect>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="agent">Agent</label>
                                                    <div class="form-control-wrap">
                                                        <multiselect v-model="agent" :options="agent_options" placeholder="Select One" label="name" track-by="id" id="agent" ></multiselect>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4" >
                                                <div class="form-group">
                                                    <label class="form-label" for="supplier_invoice_no">Supplier Invoice No.</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" v-model="supplier_invoice_no" id="supplier_invoice_no" class="form-control" @change="checkSupplierInvoiceNo">
                                                        <div id="check-supplier-no-div"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4" >
                                                <div class="form-group">
                                                    <label class="form-label" for="bill_date">Bill Date</label>
                                                    <div class="form-control-wrap">
                                                        <input type="date" v-model="bill_date" id="bill_date" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4" >
                                                <div class="form-group">
                                                    <label class="form-label" for="extra_attachment">Extra Attachment</label>
                                                    <div class="form-control-wrap">
                                                        <input type="file" @change="uploadAttachment" id="extra_attachment" class="form-control">
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <hr class="mt-5">
                                        <span class="preview-title-lg overline-title">Product Sub Category</span>
                                        <div class="" id="product_sub_category_text">
                                            <label class="form-label">Please Select Product Main Category And Supplier Field.</label>
                                        </div>
                                        <div class="form-group hidden" id="product_sub_category_section">
                                            <div class="form-control-wrap">
                                                <multiselect v-model="product_sub_category" :options="product_sub_category_options" :multiple="true" placeholder="Select One" label="name" track-by="id" id="product_sub_category" @select="showItemSection"></multiselect>
                                            </div>
                                        </div>
                                        <hr>
                                        <div id="item_details_div" class="hidden">
                                            <div class="form-group">
                                                <label class="col-sm-10"><b>Item Details</b></label>
                                                <div class="col-sm-2 text-right">
                                                    <button type="button" class="btn btn-sm btn-primary hidden" data-toggle="modal" data-target="#myModalFabric" id="add_new_fabric" > <em class="icon ni ni-plus"></em> &nbsp;Add New Fabric</button>
                                                    <div class="modal fade" id="myModalFabric" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                    <h4 class="modal-title" id="myModalLabel">Fabric Insert</h4>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="row">
                                                                        <label class="col-sm-2">Name</label>
                                                                        <div class="col-sm-10">
                                                                            <input type="text" value="" name="add_fabric_name" id="add_fabric_name" class="form-control">
                                                                        </div>
                                                                    </div>
                                                                    <br>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                    <button type="button" id="save_modal_data_fabric" class="btn btn-primary">Submit</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>&nbsp;
                                                    <button class="btn btn-sm btn-primary" type="button" id="add_item_details" @click="addItems"><em class="icon ni ni-plus"></em></button>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="itemTotal_Div">
                                            <div class="line line-dashed b-b line-lg pull-in"></div>
                                            <div class="form-group row">
                                                <label class="col-sm-10"><b>Item Total</b></label>
                                                <div class="col-sm-2 text-right"> </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-2" id="total_peices_div">
                                                    <label class="control-label">Total Pieces: </label>
                                                    <strong id="total_peices"></strong>
                                                </div>
                                                <div class="col-sm-2">
                                                    <label class="control-label">Discount: </label>
                                                    <strong id="total_discount"></strong>
                                                </div>
                                                <div class="col-sm-2">
                                                    <label class="control-label">CGST: </label>
                                                    <strong id="total_cgst"></strong>
                                                </div>
                                                <div class="col-sm-2">
                                                    <label class="control-label">SGST: </label>
                                                    <strong id="total_sgst"></strong>
                                                </div>
                                                <div class="col-sm-1">
                                                    <label class="control-label">IGST: </label>
                                                    <strong id="total_igst"></strong>
                                                </div>
                                                <div class="col-sm-2">
                                                    <label class="control-label">Amount: </label>
                                                    <strong id="total_amount"></strong>
                                                </div>
                                            </div>
                                        </div>


                                        <hr class="preview-hr">
                                        <div class="row gy-4">
                                            <div class="col-md-12 text-center">
                                                <div class="form-group">
                                                    <a v-bind:href="cancel_url" class="btn btn-dim btn-secondary mr-3">Cancel</a>
                                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div><!-- .card -->
                    </div><!-- .nk-block -->
                </div>
            </div>
        </div>
        <AddCompany ref="company"></AddCompany>
    </div>
</template>

<script>
    import $ from "jquery";
    import Multiselect from 'vue-multiselect';
    import AddCompany from '../../databank/productsComponents/modal/AddNewCompanyModelComponent.vue';

    export default {
        name: 'createCompany',
        components: {
            Multiselect,
            AddCompany
        },
        props: {
        },
        data() {
            return {
                cancel_url: '/account/sale-bill',
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
                reference_inward_options: [
                    { id: 0, name: 'Direct'}
                ],
                customer_options: [],
                customer_address_options: [],
                supplier_options: [],
                supplier_options: [],
                agent_options: [],
                reference_via: null,
                new_old_sale_bill: 1,
                from_email: '',
                delivery_by: '',
                reference_new: true,
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



                contactDetails : [{
                    contact_person_name: '',
                    contact_person_designation: '',
                    contact_person_mobile: '',
                    contact_person_email: '',
                    contact_person_profile_pic: '',
                }],
                multipleAddresses : [{
                    address_type : '',
                    mobile : '',
                    address : '',
                    country_code: '+91',
                    multipleAddressesOwners : [{
                        name : '',
                        designation : '',
                        mobile : '',
                        email : '',
                        profile_pic : '',
                    }],
                }],
            }
        },
        created() {
            // axios.get('/settings/companyType/list-data')
            // .then(response => {
            //     this.companyType = response.data;
            // });
        },
        methods: {
            resetSupplier: function (event) {
                $('#new_reference_details_div').show();
                $('#show-references').slideUp();
                this.old_reference_data = '';
                if(this.reference_via == "Email") {
                    $('#from_email').attr('required', true);
                }
                if(this.reference_via == "Courier" || this.reference_via == "Hand") {
                    $('#delivery_by').attr('required', true);
                }
                // $('#reference_via').attr('required', true);
                this.bill_date = '';
                $('#bill_date').attr('disabled', false);
                this.customer = '';
                $('#customer').attr('readonly', false);
                this.supplier = '';
                this.isSupplierDisabled = false;
                // $('#datepicker_transport').val('').attr('readonly',false);
            },
            getOldReferences: function (event) {
                if (this.reference_via == null) {
                    setTimeout(() => {
                        this.new_old_sale_bill = 1;
                        $('#error-validate-reference-div').text('Please select Reference Via');
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
            getUpdatedOldReferences: function (event) {
                axios.get('/account/sale-bill/getOldReferenceForSaleBill/'+$('input[name="reference_id_sale_bill"]:checked').val()+'?sale_bill_via=3')
                .then(response2 => {
                    if (response2.data != '') {
                        $('#allhiddenfield_div').html(response2.data);
                        if($('#hidden_sale_bill_date').val() != '') {
                            this.bill_date = $('#hidden_sale_bill_date').val();
                            $('#bill_date').attr('disabled', true);
                        }
                        this.supplier = { id: $('#hidden_cmp_id').val(), name: $('#hidden_cmp_name').val() };
                        this.isSupplierDisabled = true;
                        // $('#datepicker_transport').val($('#hidden_courier_received_time').val()).attr('readonly',true);
                    } else {
                        this.new_old_sale_bill = 1;
                    }
                });
            },
            getProductMainCategory: function(event) {
                if (event != null) {
                    axios.get('/account/sale-bill/list-product-main-category/'+event.id)
                    .then(response => {
                        this.product_category = '';
                        this.product_category_options = response.data;
                    });
                }
            },
            getProductSubCategory: function(event) {
                if (this.product_category != '' && this.supplier != '') {
                    axios.get('/account/sale-bill/list-product-sub-category/'+this.product_category.id+'/'+this.supplier.id)
                    .then(response => {
                        if (response.data.length > 0) {
                            this.product_sub_category = [];
                            this.product_sub_category_options = response.data;
                            $('#product_sub_category_text').hide();
                            $('#product_sub_category_section').show();
                        } else {
                            $('#product_sub_category_text').show();
                            $('#product_sub_category_section').hide();
                        }
                    });
                }
            },
            getInwardCustomers: function(event) {
                if (event != null) {
                    axios.get('/account/sale-bill/list-inward-customers/'+event.id)
                    .then(response => {
                        this.reference_inward = '';
                        this.reference_inward_options = response.data;
                    });
                }
            },
            getCustomerAddress: function(event) {
                if (event != null) {
                    axios.get('/account/sale-bill/list-customer-address/'+event.id)
                    .then(response => {
                        this.customer_address = '';
                        this.customer_address_options = response.data;
                    });
                }
            },
            getAgents: function(event) {
                if (event != null) {
                    axios.get('/account/sale-bill/list-customer-address/'+event.id)
                    .then(response => {
                        this.customer_address = '';
                        this.customer_address_options = response.data;
                    });
                }
            },
            showHideName: function (event) {
                $('#error-validate-reference-div').text('');
                if (event.name == 'Email') {
                    $('#delivery_by_section').hide();
                    $('#from_email_section').show();
                } else {
                    $('#delivery_by_section').show();
                    $('#from_email_section').hide();
                }
            },
            setCustomer: function (e) {
                this.$refs.company.isDisabled = true;
                this.$refs.company.form.company_type = {id: 2, name: 'Customer'};
            },
            setSupplier: function (e) {
                this.$refs.company.isDisabled = true;
                this.$refs.company.form.company_type = {id: 3, name: 'Supplier'};
            },
            uploadAttachment: function (e) {
                this.extra_attachment = e.target.files[0];
            },
            checkSupplierInvoiceNo: function (e) {
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
            showItemSection: function (e) {
                if (this.product_sub_category != '') {
                    $('#item_details_div').slideDown();
                }
            },
            addItems: function (e) {

            },


            /* addContactDetailsRow: function() {
                this.contactDetails.push({
                    contact_person_name: '',
                    contact_person_designation: '',
                    contact_person_mobile: '',
                    contact_person_email: '',
                    contact_person_profile_pic: '',
                });
            },
            deleteContactDetailsRow: function(row) {
                this.contactDetails.pop(row);
            }, */

            register () {
                var formData = new FormData();
                formData.append('company_data', JSON.stringify(this.company));
                formData.append('contact_details', JSON.stringify(this.contactDetails));
                formData.append('multiple_addresses', JSON.stringify(this.multipleAddresses));
                formData.append('multiple_emails', JSON.stringify(this.multipleEmails));
                formData.append('swot_details', JSON.stringify(this.swot));
                formData.append('references_details', JSON.stringify(this.references));
                formData.append('packaging_details', JSON.stringify(this.packaging));
                formData.append('bank_details', JSON.stringify(this.bank));

                this.contactDetails.forEach((contact,index)=>{
                    if(contact.contact_person_profile_pic){
                        formData.append(`contact_details_profile_pic[${index}]`, contact.contact_person_profile_pic);
                    }else{
                        formData.append(`contact_details_profile_pic[${index}]`, null);
                    }
                })

                this.multipleAddresses.forEach((multipleAdd,key)=>{
                    multipleAdd.multipleAddressesOwners.forEach((contact,index)=>{
                        if(contact.profile_pic){
                            formData.append(`multiple_address_profile_pic[${key}][ownerImage][${index}]`, contact.profile_pic);
                        }else{
                            formData.append(`multiple_address_profile_pic[${key}][ownerImage][${index}]`, null);
                        }
                    })
                })

                axios.post('/databank/companies/create', formData)
                .then(response => {
                    window.location.href = '/databank/companies';
                })
                .catch((error) => {
                    var validationError = error.response.data.errors;

                    if(validationError) {
                        $('#fw-company_name').focus();
                        this.errors.company_name = validationError;
                    }
                });

            },
            getContactProfilePic(name){
                return name ? '/upload/company/profilePic/' + name : '';
            },
            getOwnerProfilePic(name){
                return name ? '/upload/company/multipleAddressProfilePic/' + name : '';
            }
        },
        mounted() {
            const self = this;
            axios.get('/account/sale-bill/list-customers-and-suppliers')
            .then(response => {
                this.customer_options = response.data[0];
                this.supplier_options = response.data[1];
            });

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
                        $('#sale_bill_ref_msg').html('<td><div class="custom-control custom-radio"><input class="custom-control-input" type="radio" name="reference_id_sale_bill" value="r-'+$('#hidden_reference_id_input').val()+'" id="r-'+$('#hidden_reference_id_input').val()+'"><label class="custom-control-label" for="r-'+$('#hidden_reference_id_input').val()+'"></label></div></td><td>'+$('#hidden_reference_id_input').val()+'</td><td>'+$('#hidden_ref_emp_name').val()+'</td><td>'+$('#hidden_ref_date_added').val()+'</td><td>'+$('#hidden_ref_time_added').val()+'</td>');
                        this.supplier = { id: $('#hidden_cmp_id').val(), name: $('#hidden_cmp_name').val() };
                        this.isSupplierDisabled = true;
                        $('#show-references tr input[type="radio"]').last().prop('checked', true);
                        // $('#datepicker_transport').val($('#hidden_courier_received_time').val()).attr('readonly',true);
                    } else {
                        this.new_old_sale_bill = 1;
                        $('#sale_bill_ref_msg').html('<td colspan="5">This Reference Id is not generated by Email, Courier OR Hand.</td>');
                    }
                });

            });
        },
    };
</script>
<style src="vue-multiselect/dist/vue-multiselect.css"></style>
<style scoped>
    .hidden {
        display: none;
    }
    #show-references {
        padding-top: 0 !important;
        padding-bottom: 0 !important;
    }
</style>

