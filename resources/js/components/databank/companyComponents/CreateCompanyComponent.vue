<template>    
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 v-if="scope == 'edit'" class="nk-block-title page-title">Edit Company</h3>
                                <h3 v-else class="nk-block-title page-title">Add Company</h3>
                                <div class="nk-block-des text-soft">
                                    <p>Please fill the all details.</p>
                                </div>
                            </div><!-- .nk-block-head-content -->
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    <div class="nk-block">
                        <div class="card card-bordered">
                            <div class="card-inner">
                                <form action="#" class="form-validate" @submit.prevent="register()">
                                    <input type="hidden" v-if="scope == 'edit'" id="fv-group-id" v-model="company.id">
                                    <div class="preview-block">
                                        <span class="preview-title-lg overline-title">Company Details</span>
                                        <div class="row gy-4">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="fw-company_name">Company Name</label>
                                                    <div class="form-control-wrap">
                                                        <input v-model="company.company_name" type="text" class="form-control" id="fw-company_name">
                                                        <span v-if="errors.company_name" class="invalid">{{errors.company_name}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="fw-company_type">Company Type</label>
                                                    <div>
                                                        <multiselect v-model="company.company_type" :options="companyType" placeholder="Select one" label="name" track-by="name" @input="getStateList"></multiselect>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="fw-company_country">Country</label>
                                                    <div>
                                                        <multiselect v-model="company.company_country" :options="countryList" placeholder="Select one" label="name" track-by="name" @input="getStateList"></multiselect>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="fw-company_state">State</label>
                                                    <div>
                                                        <multiselect v-model="company.company_state" :options="stateList" placeholder="Select one" label="name" track-by="name" @input="getCityList"></multiselect>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group code-block">
                                                    <label class="form-label" for="fw-company_city">City</label>
                                                    <button type="button" class="btn btn-sm clipboard-init" data-toggle="modal" data-target="#addCity" title="Add new city"><span class="clipboard-text">Add New</span></button>
                                                    <div>
                                                        <multiselect v-model="company.company_city" :options="cityList" placeholder="Select one" label="name" track-by="name"></multiselect>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr class="preview-hr">
                                        <div class="row gy-4">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="form-label" for="fw-company_website">Website</label>
                                                    <div class="form-control-wrap">
                                                        <input v-model="company.company_website" type="text" class="form-control" id="fw-company_website">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="form-label" for="fw-company_landline">Landline</label>
                                                    <div class="form-control-wrap">
                                                        <input v-model="company.company_landline" type="text" class="form-control" id="fw-company_landline">
                                                        <span style="font-size: 11px; color: #6576ff;">Please enter multiple landline no with comma seperated type. (Example : 123456, 456789)</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="form-label" for="fw-company_mobile">Mobile Number</label>
                                                    <div class="form-control-wrap">
                                                        <input v-model="company.company_mobile" type="text" class="form-control" id="fw-company_mobile">
                                                        <span style="font-size: 11px; color: #6576ff;">Please enter multiple mobile no with comma seperated type. (Example : 123456, 456789)</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-remark_watchout">Remark Watchout</label>
                                                    <div class="form-control-wrap">
                                                        <textarea class="form-control" id="fv-remark_watchout" v-model="company.company_remark_watchout"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-company_about">About Company</label>
                                                    <div class="form-control-wrap">
                                                        <textarea class="form-control" id="fv-company_about" v-model="company.company_about"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr class="preview-hr">
                                        <div class="row gy-4">
                                            <div class="col-md-2">
                                                <div class="preview-block">
                                                    <label class="form-label">Watchout</label>
                                                    <ul class="custom-control-group g-3 align-center">
                                                        <li>
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" class="custom-control-input" v-model="company.company_watchout" id="fv-company_watchout_yes" value="1" >
                                                                <label class="custom-control-label" for="fv-company_watchout_yes">Yes</label>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" class="custom-control-input" v-model="company.company_watchout" id="fv-company_watchout_no" value="0" >
                                                                <label class="custom-control-label" for="fv-company_watchout_no">No</label>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label" for="fw-company_category">Company Category</label>
                                                    <div>
                                                        <multiselect v-model="company.company_category" :options="companyCategoryList" placeholder="Select one" label="category_name" track-by="category_name" :multiple="true" :taggable="true"></multiselect>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group code-block">
                                                    <label class="form-label" for="fw-company_transport">Transport</label>
                                                        <button type="button" class="btn btn-sm clipboard-init" data-toggle="modal" data-target="#addTransport" title="Add new transport"><span class="clipboard-text">Add New</span></button>
                                                    <div>
                                                        <multiselect v-model="company.company_transport" :options="transportList" placeholder="Select one" label="name" track-by="name"></multiselect>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label" for="fw-company_discount">Discount</label>
                                                    <div class="form-control-wrap">
                                                        <input v-model="company.company_discount" type="number" class="form-control" id="fw-company_discount">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label" for="fw-company_payment_terms_in_days">Payment Terms (Days)</label>
                                                    <div class="form-control-wrap">
                                                        <input v-model="company.company_payment_terms_in_days" type="number" class="form-control" id="fw-company_payment_terms_in_days">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label" for="fw-company_opening_balance">Opening Balance</label>
                                                    <div class="form-control-wrap">
                                                        <input v-model="company.company_opening_balance" type="number" class="form-control" id="fw-company_opening_balance">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr class="preview-hr">
                                        <div class="row gy-4">
                                            <div class="col-md-12 d-flex align-items-center">
                                                <span class="preview-title-lg overline-title d-inline-block w-100">Contact Details</span>
                                                <li class="dropdown-toggle btn btn-icon btn-primary" @click="addContactDetailsRow"><em class="icon ni ni-plus"></em></li>
                                            </div>
                                        </div>
                                        <div class="row gy-4" v-for="(contactDetail, index) in contactDetails" :key="index">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-label" for="fw-contact_person_name">Name</label>
                                                    <div class="form-control-wrap">
                                                        <input v-model="contactDetail.contact_person_name" type="text" class="form-control" id="fw-contact_person_name" name="fw-contact_person_name">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label" for="fw-contact_person_designation">Designation</label>
                                                    <div>
                                                        <multiselect v-model="contactDetail.contact_person_designation" :options="designationList" placeholder="Select one" label="name" track-by="name"></multiselect>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label" :for="'fw-contact_person_profile_pic'+index">Photo</label>
                                                    <div class="form-control-wrap">
                                                        <img v-if="scope == 'edit'" :src="getContactProfilePic(contactDetail.contact_person_profile_pic)">
                                                        <div :class="scope == 'edit' ? 'custom-file profilePic' : 'custom-file'">
                                                            <input type="file" class="custom-file-input" :id="'fv-contact_person_profile_pic'+index" @change="uploadContactPersonProfilePic(index, $event)">
                                                            <label class="custom-file-label" :for="'fv-contact_person_profile_pic'+index">Choose photo</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label" for="fw-contact_person_mobile">Mobile</label>
                                                    <div class="form-control-wrap">
                                                        <input v-model="contactDetail.contact_person_mobile" type="number" class="form-control" id="fw-contact_person_mobile" name="fw-contact_person_mobile">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label" for="fw-contact_person_email">Email</label>
                                                    <div class="form-control-wrap">
                                                        <input v-model="contactDetail.contact_person_email" type="text" class="form-control" id="fw-contact_person_email" name="fw-contact_person_email">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-1 d-flex align-items-center flex-row-reverse">
                                                <li class="dropdown-toggle btn btn-icon btn-primary" @click="deleteContactDetailsRow(contactDetail)"><em class="icon ni ni-cross"></em></li>
                                            </div>
                                        </div>
                                        <hr class="preview-hr">                                        
                                        <div class="row gy-4">
                                            <div class="col-md-12 d-flex align-items-center">
                                                <span class="preview-title-lg overline-title d-inline-block w-100">Multiple Addresses</span>
                                                <li class="dropdown-toggle btn btn-icon btn-primary" @click="addMultipleAddressesRow"><em class="icon ni ni-plus"></em></li>
                                            </div>
                                        </div>
                                        <div v-for="(multipleAddress, index) in multipleAddresses" :key="'A' + index">
                                            <div class="row gy-4">
                                                <div class="col-md-3">
                                                    <div class="form-group code-block">
                                                        <label class="form-label" for="fw-ma_address_type">Type of Address</label>
                                                        <button type="button" class="btn btn-sm clipboard-init" data-toggle="modal" data-target="#addTypeOffAddress" title="Add new type of address"><span class="clipboard-text">Add New</span></button>
                                                        <div>
                                                            <multiselect v-model="multipleAddress.address_type" :options="typeOfAddress" placeholder="Select one" label="name" track-by="name"></multiselect>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label" for="fw-ma_mobile">Mobile</label>
                                                        <div class="form-control-wrap" style="position: relative; width: 100%;">
                                                            <input v-model="multipleAddress.country_code" type="number" placeholder="+91" class="form-control" id="fv-company_country_code">
                                                            <input v-model="multipleAddress.mobile" type="number" class="form-control" id="fw-ma_mobile">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="form-group">
                                                        <label class="form-label" for="fv-ma_address">Address</label>
                                                        <div class="form-control-wrap">
                                                            <textarea class="form-control" style="min-height: 0" id="fv-ma_address" v-model="multipleAddress.address"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-1 d-flex align-items-center flex-row-reverse">
                                                    <li class="dropdown-toggle btn btn-icon btn-primary" @click="deleteMultipleAddressesRow(multipleAddress)"><em class="icon ni ni-cross"></em></li>
                                                </div>
                                            </div>
                                            <div class="row gy-4">
                                                <div class="col-md-12 d-flex align-items-center">
                                                    <span class="preview-title-lg overline-title d-inline-block w-100"></span>
                                                    <li class="dropdown-toggle btn btn-icon btn-primary" @click="addMultipleAddressOwnersRow(multipleAddress)"><em class="icon ni ni-plus"></em></li>
                                                </div>
                                            </div>
                                            <div class="row gy-4" v-for="(multipleAddressesOwner, key) in multipleAddress.multipleAddressesOwners" :key="'B' + key">
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="form-label" for="fw-mao_name">Name</label>
                                                        <div class="form-control-wrap">
                                                            <input v-model="multipleAddressesOwner.name" type="text" class="form-control" id="fw-mao_name">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label" for="fw-maodesignation">Designation</label>
                                                        <div>
                                                            <multiselect v-model="multipleAddressesOwner.designation" :options="designationList" placeholder="Select one" label="name" track-by="name" :multiple="true" :taggable="true"></multiselect>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="form-label" for="fw-mao_profile_pic">Photo</label>
                                                        <div class="form-control-wrap">
                                                        <img v-if="scope == 'edit'" :src="getOwnerProfilePic(multipleAddressesOwner.profile_pic)">
                                                        <div :class="scope == 'edit' ? 'custom-file profilePic' : 'custom-file'">
                                                                <input type="file" class="custom-file-input" :id="'fv-mao_profile_pic'+index" @change="uploadMultipleAddressOwnerPic(index, key, $event)">
                                                                <label class="custom-file-label" :for="'fv-mao_profile_pic'+index">Choose photo</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="form-label" for="fw-mao_mobile">Mobile</label>
                                                        <div class="form-control-wrap">
                                                            <input v-model="multipleAddressesOwner.mobile" type="number" class="form-control" id="fw-mao_mobile">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="form-label" for="fw-mao_email">Email</label>
                                                        <div class="form-control-wrap">
                                                            <input v-model="multipleAddressesOwner.email" type="text" class="form-control" id="fw-mao_email" name="fw-mao_email">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-1 d-flex align-items-center flex-row-reverse">
                                                    <li class="dropdown-toggle btn btn-icon btn-primary" @click="deleteMultipleAddressesOwnersRow(multipleAddress, multipleAddressesOwner)"><em class="icon ni ni-cross"></em></li>
                                                </div>
                                            </div>
                                        </div>
                                        <hr class="preview-hr">
                                        <div class="row gy-4">
                                            <div class="col-md-12 d-flex align-items-center">
                                                <span class="preview-title-lg overline-title d-inline-block w-100">Multiple Emails</span>
                                                <li class="dropdown-toggle btn btn-icon btn-primary" @click="addMultipleEmailsRow"><em class="icon ni ni-plus"></em></li>
                                            </div>
                                        </div>
                                        <div class="row gy-4" v-for="(multipleEmail, index) in multipleEmails" :key="'C'+index">
                                            <div class="col-md-11">
                                                <div class="form-group">
                                                    <label class="form-label" for="fw-company_person_name">Email Id</label>
                                                    <div class="form-control-wrap">
                                                        <input v-model="multipleEmail.email_id" type="text" class="form-control" id="fw-company_person_name" name="fw-company_person_name">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-1 d-flex align-items-center flex-row-reverse">
                                                <li class="dropdown-toggle btn btn-icon btn-primary" @click="deleteMultipleEmailsRow(multipleEmail)"><em class="icon ni ni-cross"></em></li>
                                            </div>
                                        </div>
                                        <hr class="preview-hr">
                                        <span class="preview-title-lg overline-title">SWOT Details</span>
                                        <div class="row gy-4">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-label" for="fw-strength">Strength</label>
                                                    <div class="form-control-wrap">
                                                        <input v-model="swot.strength" type="number" class="form-control" id="fw-strength" name="fw-strength">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-label" for="fw-weakness">Weakness</label>
                                                    <div class="form-control-wrap">
                                                        <input v-model="swot.weakness" type="number" class="form-control" id="fw-weakness" name="fw-weakness">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-label" for="fw-opportunity">Oppotunity</label>
                                                    <div class="form-control-wrap">
                                                        <input v-model="swot.opportunity" type="number" class="form-control" id="fw-opportunity" name="fw-opportunity">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-label" for="fw-threat">Threat</label>
                                                    <div class="form-control-wrap">
                                                        <input v-model="swot.threat" type="number" class="form-control" id="fw-threat" name="fw-threat">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr class="preview-hr">
                                        <span class="preview-title-lg overline-title">References Details</span>
                                        <div class="row gy-4">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-label" for="fw-ref_person_name">Reference Person Name</label>
                                                    <div class="form-control-wrap">
                                                        <input v-model="references.ref_person_name" type="text" class="form-control" id="fw-ref_person_name" name="fw-ref_person_name">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-label" for="fw-ref_person_mobile">Mobile No</label>
                                                    <div class="form-control-wrap">
                                                        <input v-model="references.ref_person_mobile" type="number" class="form-control" id="fw-ref_person_mobile" name="fw-ref_person_mobile">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-label" for="fw-ref_person_company">Company Name</label>
                                                    <div class="form-control-wrap">
                                                        <input v-model="references.ref_person_company" type="text" class="form-control" id="fw-ref_person_company" name="fw-ref_person_company">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-label" for="fw-ref_person_address">Address</label>
                                                    <div class="form-control-wrap">
                                                        <textarea v-model="references.ref_person_address" type="text" class="form-control" id="fw-ref_person_address" name="fw-ref_person_address" style="min-height: 0"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr class="preview-hr">
                                        <span class="preview-title-lg overline-title">Packaging Details</span>
                                        <div class="row gy-4">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-label" for="fw-gst_no">GST No</label>
                                                    <div class="form-control-wrap">
                                                        <input v-model="packaging.gst_no" type="text" class="form-control" id="fw-gst_no" name="fw-gst_no">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-label" for="fw-cst_no">CST No</label>
                                                    <div class="form-control-wrap">
                                                        <input v-model="packaging.cst_no" type="text" class="form-control" id="fw-cst_no" name="fw-cst_no">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-label" for="fw-tin_no">TIN No</label>
                                                    <div class="form-control-wrap">
                                                        <input v-model="packaging.tin_no" type="text" class="form-control" id="fw-tin_no" name="fw-tin_no">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-label" for="fw-vat_no">VAT No</label>
                                                    <div class="form-control-wrap">
                                                        <input v-model="packaging.vat_no" type="text" class="form-control" id="fw-vat_no" name="fw-vat_no">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr class="preview-hr">
                                        <span class="preview-title-lg overline-title">Bank Details</span>
                                        <div class="row gy-4">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-label" for="fw-bank_name">Bank Name</label>
                                                    <div class="form-control-wrap">
                                                        <input v-model="bank.bank_name" type="text" class="form-control" id="fw-bank_name" name="fw-bank_name">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-label" for="fw-account_holder_name">Account Holder Name</label>
                                                    <div class="form-control-wrap">
                                                        <input v-model="bank.account_holder_name" type="text" class="form-control" id="fw-account_holder_name" name="fw-account_holder_name">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label" for="fw-account_no">Account Number</label>
                                                    <div class="form-control-wrap">
                                                        <input v-model="bank.account_no" type="number" class="form-control" id="fw-account_no" name="fw-account_no">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label" for="fw-branch_name">Branch Name</label>
                                                    <div class="form-control-wrap">
                                                        <input v-model="bank.branch_name" type="text" class="form-control" id="fw-branch_name" name="fw-branch_name">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label" for="fw-ifsc_code">IFSC Code</label>
                                                    <div class="form-control-wrap">
                                                        <input v-model="bank.ifsc_code" type="text" class="form-control" id="fw-ifsc_code" name="fw-ifsc_code">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr class="preview-hr">
                                        <div class="row gy-4">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <a v-bind:href="cancel_url" class="btn btn-dim btn-secondary">Cancel</a>
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
        <AddCity></AddCity>
        <AddTransport></AddTransport>
        <AddTypeOfAddress></AddTypeOfAddress>
    </div>
</template>

<script>
    import Multiselect from 'vue-multiselect';
    import AddCity from './modal/AddNewCityModelComponent';
    import AddTransport from './modal/AddNewTransportModelComponent';
    import AddTypeOfAddress from './modal/AddNewTypeOfAddressModelComponent';

    var companies = [];
    export default {
        name: 'createCompany',
        components: {
            Multiselect,
            AddCity,
            AddTransport,
            AddTypeOfAddress
        },
        props: {
            scope: String,
            id: Number,
        },
        data() {
            return {
                cancel_url: '/databank/companies',
                profilePic : [],
                multipleAddressOwnerProfilePic : [],
                companyType: [],
                countryList: [],
                stateList: [],
                cityList: [],
                companyCategoryList: [],
                transportList: [],
                designationList: [],
                typeOfAddress: [],
                showAddCityModal: false,
                errors: {
                    company_name: ''
                },
                company : {
                    id: '',
                    company_name: '',
                    company_type: '',
                    company_country: '',
                    company_state: '',
                    company_city: '',
                    company_website: '',
                    company_landline: '',
                    company_mobile: '',
                    company_watchout: '',
                    company_remark_watchout: '',
                    company_about: '',
                    company_category: '',
                    company_transport: '',
                    company_discount: 0,
                    company_payment_terms_in_days: 0,
                    company_opening_balance: 0,
                },
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
                multipleEmails : [{
                    email_id : ''
                }],
                swot : {
                    strength : 0,
                    weakness : 0,
                    opportunity : 0,
                    threat : 0,
                },
                references : {
                    ref_person_name : '',
                    ref_person_mobile : '',
                    ref_person_company : '',
                    ref_person_address : '',
                },
                packaging : {
                    gst_no : '',
                    cst_no : '',
                    tin_no : '',
                    vat_no : '',
                },
                bank : {
                    bank_name : '',
                    account_holder_name : '',
                    account_no : '',
                    branch_name : '',
                    ifsc_code : '',
                },
            }
        },
        created() {
            axios.get('/settings/companyType/list-data')
            .then(response => {
                this.companyType = response.data;
            });
            axios.get('/settings/cities/list-country')
            .then(response => {
                this.countryList = response.data;
            });
            axios.get('/databank/companyCategory/list-data')
            .then(response => {
                this.companyCategoryList = response.data;
            });
            axios.get('/settings/transport-details/list-data')
            .then(response => {
                this.transportList = response.data;
            });
            axios.get('/settings/designation/list-data')
            .then(response => {
                this.designationList = response.data;
            });
            axios.get('/settings/type-of-address/list-data')
            .then(response => {
                this.typeOfAddress = response.data;
            });
        },
        methods: {
            getStateList: function(event) {
                if(event != null) {
                    console.log(event);                    
                    axios.get('/settings/cities/list-state-id/'+event.id)
                    .then(response => {
                        this.stateList = response.data;
                    });
                }
            },
            getCityList: function(event) {
                if(event != null) {
                    console.log(event);                    
                    axios.get('/settings/cities/list-city-id/'+event.id)
                    .then(response => {
                        this.cityList = response.data;
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

            addMultipleAddressOwnersRow: function(row) {
                row.multipleAddressesOwners.push({
                    name : '',
                    designation : '',
                    mobile : '',
                    email : '',
                    profile_pic : '',
                });
            },
            deleteMultipleAddressesOwnersRow: function(row, subrow) {
                row.multipleAddressesOwners.pop(subrow);
            },
                        
            addMultipleEmailsRow: function() {
                this.multipleEmails.push({
                    email_id: '',
                });
            },
            deleteMultipleEmailsRow: function(row) {
                this.multipleEmails.pop(row);
            },

            uploadContactPersonProfilePic (index, e) {
                this.contactDetails[index]['contact_person_profile_pic'] = e.target.files[0];
            },
            uploadMultipleAddressOwnerPic (index, key, e) {
                this.multipleAddresses[index].multipleAddressesOwners[key]['profile_pic'] = e.target.files[0];
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

                if (this.scope == 'edit') {
                    axios.post('/databank/companies/update', formData)
                    .then(response => {
                        window.location.href = '/databank/companies';
                    })
                    .catch((error) => {
                        var validationError = error.response.data.errors;

                        if(validationError.company_name) {
                            this.errors.company_name = validationError.company_name[0];
                        }
                    });
                } else {
                    axios.post('/databank/companies/create', formData)
                    .then(response => {
                        window.location.href = '/databank/companies';
                    })
                    .catch((error) => {
                        var validationError = error.response.data.errors;

                        if(validationError.company_name) {
                            this.errors.company_name = validationError.company_name[0];
                        }
                    });
                }
            },
            getContactProfilePic(name){
                var profile = '/upload/company/profilePic/' + name;
                
                return profile;
            },
            getOwnerProfilePic(name){
                var profile = '/upload/company/multipleAddressProfilePic/' + name;
                
                return profile;
            }
        },
        mounted() {
            switch (this.scope) {
                case 'edit' :
                    axios.get(`/databank/companies/fetch-company/${this.id}`)
                    .then(response => {
                        companies = response.data;

                        // company Data
                        this.company = companies.company;

                        // Contacts Data
                        this.contactDetails = companies.contact_details;
                        
                        // Multiple Address
                        this.multipleAddresses = companies.multiple_address;
                        
                        // Multiple Emails
                        this.multipleEmails = companies.multiple_emails;
                        
                        // SWOT
                        this.swot = companies.swot_details;
                        
                        // Packaging
                        this.packaging = companies.packaging_details;
                        
                        // References
                        this.references = companies.references_details;
                        
                        // Bank
                        this.bank = companies.bank_details;
                    });
                    break;
                default:
                    break;
            }
        },
    };
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<style>
    .form-control-wrap img {
        position: absolute;
        width: 45px;
    }
    .form-control-wrap .custom-file.profilePic {
        width: 75%;
        float: right;
    }
    .multiselect {
        height: calc(2.125rem + 2px);
        font-family: Roboto,sans-serif;
        font-size: 13px;
        font-weight: 400;
        background-color: #fff;
        border: none;
        border-radius: 4px;
        box-shadow: none;
        transition: all 0.3s;
        min-height: 36px;
        display: inline-flex;
        flex-wrap: wrap;
    }
    .multiselect__tag-icon:after {
        color: #526484;
    }
    .multiselect__tag {
        color: #526484;
        background: #ebeef2;
        font-size: 13px;
        font-family: Roboto,sans-serif;
    }
    .multiselect__tags {
        padding: 7px 16px;
        font-size: 13px;
        min-height: 36px;
        border: 1px solid #dbdfea;
        width: 100%;
    }
    .multiselect__placeholder {
        margin-bottom: 0;
        padding-top: 0;
    }
    .multiselect__select {
        height: calc(2.125rem + 2px);
        position: absolute;
        top: 0;
        right: 0;
        width: calc(2.125rem + 2px);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .multiselect__select:before {
        display: none;
    }
    .multiselect .multiselect__select:after {
        font-family: "Nioicon";
        content: "";
        line-height: 1;
    }
    .multiselect.multiselect--active .multiselect__input, .multiselect__single {
        font-size: 13px;
        padding: 0;
        margin-bottom: 0;
        width: 98% !important;
    }
    .multiselect__content-wrapper {
        border-top: 1px solid #dbdfea;
        padding: 6px;
        top: 36px;
    }
    .multiselect__option--highlight {
        background: #ebeef2;
        border-radius: 4px;
        color: #526484;        
    }
    .multiselect__element {
        margin-bottom: 0.125rem;
    }
    .multiselect__option--highlight:after, .multiselect__option:after {
        display: none;
    }
    .multiselect__option--selected.multiselect__option--highlight {
        background: #f3f3f3;
        color: #35495e;
    }
    .multiselect__option--selected {
        font-weight: 500;
    }
    .multiselect__tags-wrap {
        display: inline-flex;
    }
    .multiselect--above .multiselect__content-wrapper {
        border: 1px solid #e8e8e8;
    }
    .multiselect__tag-icon:focus, .multiselect__tag-icon:hover {
        background: #ebeef2;
    }
    .multiselect__tag-icon:focus:after, .multiselect__tag-icon:hover:after {
        color: #526484;
    }
    .form-group.code-block {
        border: none;
        padding: 0;
    }
    .form-group.code-block .clipboard-init {
        top: -5px;
        color: #6576ff;
    }
    .form-group.code-block .clipboard-init:hover {
        border-color: #6576ff;
    }
    #fv-company_country_code {
        position: absolute;
        width: 20%;
        padding-left: 10px;
    }
    #fw-ma_mobile {
        width: 77%;
        float: right;
    }
</style>