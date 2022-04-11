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
                                                    <ul class="custom-control-group g-3 align-center">
                                                        <li class="w-25">
                                                            <div class="custom-control custom-radio">
                                                                <input v-model="new_old_sale_bill" type="radio" class="custom-control-input"  id="fv-reference_new" value="1" @click="reference_new = true">
                                                                <label class="custom-control-label" for="fv-reference_new">NEW</label>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="custom-control custom-radio">
                                                                <input v-model="new_old_sale_bill" type="radio" class="custom-control-input"  id="fv-reference_old" value="0" @click="reference_new = false">
                                                                <label class="custom-control-label" for="fv-reference_old">OLD</label>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="col-md-4 hidden" id="from_email_section">
                                                <div class="form-group">
                                                    <label class="form-label" for="from_email">From Email ID</label>
                                                    <div class="form-control-wrap">
                                                        <input type="email" v-model="from_email" id="from_email" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4" id="delivery_by_section">
                                                <div class="form-group">
                                                    <label class="form-label" for="delivery_by">Delivery By</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" v-model="delivery_by" id="delivery_by" class="form-control">
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
                                                    <button type="button" class="btn btn-primary float-right clipboard-init badge" data-toggle="modal" data-target="#addCompany" title="Add New Supplier" @click="setSupplier"><span class="clipboard-text">Add New</span></button>
                                                    <div class="form-control-wrap">
                                                        <multiselect v-model="supplier" :options="supplier_options" placeholder="Select One" label="name" track-by="id" id="supplier" @close="getProductSubCategory(), checkSupplierInvoiceNo()" ></multiselect>
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
                                                <multiselect v-model="product_sub_category" :options="product_sub_category_options" :multiple="true" placeholder="Select One" label="name" track-by="id" id="product_sub_category" @select=""></multiselect>
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
                reference_via: '',
                new_old_sale_bill: 1,
                from_email: '',
                delivery_by: '',
                reference_new: true,
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

                    });
                }
            },



            addContactDetailsRow: function() {
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
            },

            addMultipleAddressesRow: function() {
                this.multipleAddresses.push({
                    address_type : '',
                    mobile : '',
                    address : '',
                    multipleAddressesOwners : [{
                        name : '',
                        designation : '',
                        mobile : '',
                        email : '',
                        profile_pic : '',
                    }],
                });
            },
            deleteMultipleAddressesRow: function(row) {
                this.multipleAddresses.pop(row);
            },



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
            axios.get('/account/sale-bill/list-customers-and-suppliers')
            .then(response => {
                this.customer_options = response.data[0];
                this.supplier_options = response.data[1];
            });
            document.getElementById('addCompany').addEventListener('shown.bs.modal', function (event) {
                console.log(event);
                console.log(123);
            });
        },
    };
</script>
<style src="vue-multiselect/dist/vue-multiselect.css"></style>
<style scoped>
    .hidden {
        display: none;
    }
</style>

