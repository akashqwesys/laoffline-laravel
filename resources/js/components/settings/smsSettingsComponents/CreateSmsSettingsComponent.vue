<template>    
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">Edit SMS Settings</h3>
                                <div class="nk-block-des text-soft">
                                    <p>Please fill the all details.</p>
                                </div>
                            </div><!-- .nk-block-head-content -->                            
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    <div class="nk-block">
                        <div class="card card-bordered">
                            <div class="card-inner">
                                <form action="#" class="gy-3" @submit.prevent="register()">
                                    <input type="hidden" id="fv-group-id" v-model="form.id">
                                    <div class="preview-block">
                                        <div class="row g-3" style="padding-bottom: 0 !important;">
                                            <div class="col-lg-3" style="padding-bottom: 0 !important;">
                                                <div class="form-group">
                                                    <label style="margin-bottom: 0;" class="form-label" for="site-name"></label>
                                                </div>
                                            </div>
                                            <div class="col-lg-3" style="padding-bottom: 0 !important;">
                                                <div class="form-group">
                                                    <label style="margin-bottom: 0;" class="form-label" for="site-name">General / Byuer</label>
                                                </div>
                                            </div>
                                            <div class="col-lg-3" style="padding-bottom: 0 !important;">
                                                <div class="form-group">
                                                    <label style="margin-bottom: 0;" class="form-label" for="site-name">Supplier</label>
                                                </div>
                                            </div>
                                            <div class="col-lg-3" style="padding-bottom: 0 !important;">
                                                <div class="form-group">
                                                    <label style="margin-bottom: 0;" class="form-label" for="site-name">Footer Message</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row g-3 align-center">
                                            <div class="col-lg-2">
                                                <div class="form-group">
                                                    <label class="form-label">Enquiry</label>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <div>
                                                        <multiselect v-model="form.enquiry_general" tag-placeholder="Select Designation" placeholder="Search Designation" label="name" track-by="name" :options="designationList" :multiple="true" :taggable="true"></multiselect>                                                        
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <div>
                                                        <multiselect v-model="form.enquiry_supplier" tag-placeholder="Select Designation" placeholder="Search Designation" label="name" track-by="name" :options="designationList" :multiple="true" :taggable="true"></multiselect>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <div class="form-control-wrap">
                                                        <textarea class="form-control" style="min-height: 50px;" v-model="form.enquiry_footer_message"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row g-3 align-center">
                                            <div class="col-lg-2">
                                                <div class="form-group">
                                                    <label class="form-label">Enquiry Followup</label>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <div>
                                                        <multiselect v-model="form.enquiry_followup_general" tag-placeholder="Select Designation" placeholder="Search Designation" label="name" track-by="name" :options="designationList" :multiple="true" :taggable="true"></multiselect>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <div>
                                                        <multiselect v-model="form.enquiry_followup_supplier" tag-placeholder="Select Designation" placeholder="Search Designation" label="name" track-by="name" :options="designationList" :multiple="true" :taggable="true"></multiselect>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr class="preview-hr">
                                        <div class="row g-3 align-center">
                                            <div class="col-lg-2">
                                                <div class="form-group">
                                                    <label class="form-label">Order</label>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <div>
                                                        <multiselect v-model="form.order_general" tag-placeholder="Select Designation" placeholder="Search Designation" label="name" track-by="name" :options="designationList" :multiple="true" :taggable="true"></multiselect>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <div>
                                                        <multiselect v-model="form.order_supplier" tag-placeholder="Select Designation" placeholder="Search Designation" label="name" track-by="name" :options="designationList" :multiple="true" :taggable="true"></multiselect>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <div class="form-control-wrap">
                                                        <textarea class="form-control" style="min-height: 50px;" v-model="form.order_footer_message"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row g-3 align-center">
                                            <div class="col-lg-2">
                                                <div class="form-group">
                                                    <label class="form-label">Order Followup</label>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <div>
                                                        <multiselect v-model="form.order_followup_general" tag-placeholder="Select Designation" placeholder="Search Designation" label="name" track-by="name" :options="designationList" :multiple="true" :taggable="true"></multiselect>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <div>
                                                        <multiselect v-model="form.order_followup_supplier" tag-placeholder="Select Designation" placeholder="Search Designation" label="name" track-by="name" :options="designationList" :multiple="true" :taggable="true"></multiselect>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr class="preview-hr">
                                        <div class="row g-3 align-center">
                                            <div class="col-lg-2">
                                                <div class="form-group">
                                                    <label class="form-label">Complain</label>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <div>
                                                        <multiselect v-model="form.complain_general" tag-placeholder="Select Designation" placeholder="Search Designation" label="name" track-by="name" :options="designationList" :multiple="true" :taggable="true"></multiselect>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <div>
                                                        <multiselect v-model="form.complain_supplier" tag-placeholder="Select Designation" placeholder="Search Designation" label="name" track-by="name" :options="designationList" :multiple="true" :taggable="true"></multiselect>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <div class="form-control-wrap">
                                                        <textarea class="form-control" style="min-height: 50px;" v-model="form.complain_footer_message"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row g-3 align-center">
                                            <div class="col-lg-2">
                                                <div class="form-group">
                                                    <label class="form-label">Complain Followup</label>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <div>
                                                        <multiselect v-model="form.complain_followup_general" tag-placeholder="Select Designation" placeholder="Search Designation" label="name" track-by="name" :options="designationList" :multiple="true" :taggable="true"></multiselect>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <div>
                                                        <multiselect v-model="form.complain_followup_supplier" tag-placeholder="Select Designation" placeholder="Search Designation" label="name" track-by="name" :options="designationList" :multiple="true" :taggable="true"></multiselect>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr class="preview-hr">
                                        <div class="row g-3 align-center">
                                            <div class="col-lg-2">
                                                <div class="form-group">
                                                    <label class="form-label">General</label>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <div>
                                                        <multiselect v-model="form.general_general" tag-placeholder="Select Designation" placeholder="Search Designation" label="name" track-by="name" :options="designationList" :multiple="true" :taggable="true"></multiselect>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <div>
                                                        <multiselect v-model="form.general_supplier" tag-placeholder="Select Designation" placeholder="Search Designation" label="name" track-by="name" :options="designationList" :multiple="true" :taggable="true"></multiselect>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <div class="form-control-wrap">
                                                        <textarea class="form-control" style="min-height: 50px;" v-model="form.general_footer_message"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row g-3 align-center">
                                            <div class="col-lg-2">
                                                <div class="form-group">
                                                    <label class="form-label">General Followup</label>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <div>
                                                        <multiselect v-model="form.general_followup_general" tag-placeholder="Select Designation" placeholder="Search Designation" label="name" track-by="name" :options="designationList" :multiple="true" :taggable="true"></multiselect>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <div>
                                                        <multiselect v-model="form.general_followup_supplier" tag-placeholder="Select Designation" placeholder="Search Designation" label="name" track-by="name" :options="designationList" :multiple="true" :taggable="true"></multiselect>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr class="preview-hr">
                                        <div class="row g-3 align-center">
                                            <div class="col-lg-2">
                                                <div class="form-group">
                                                    <label class="form-label">Salebill Inward</label>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <div>
                                                        <multiselect v-model="form.salebill_inward_general" tag-placeholder="Select Designation" placeholder="Search Designation" label="name" track-by="name" :options="designationList" :multiple="true" :taggable="true"></multiselect>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <div>
                                                        <multiselect v-model="form.salebill_inward_supplier" tag-placeholder="Select Designation" placeholder="Search Designation" label="name" track-by="name" :options="designationList" :multiple="true" :taggable="true"></multiselect>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <div class="form-control-wrap">
                                                        <textarea class="form-control" style="min-height: 50px;" v-model="form.salebill_inward_footer_message"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row g-3 align-center">
                                            <div class="col-lg-2">
                                                <div class="form-group">
                                                    <label class="form-label">Salebill Outward</label>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <div>
                                                        <multiselect v-model="form.salebill_outward_followup_general" tag-placeholder="Select Designation" placeholder="Search Designation" label="name" track-by="name" :options="designationList" :multiple="true" :taggable="true"></multiselect>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <div>
                                                        <multiselect v-model="form.salebill_outward_followup_supplier" tag-placeholder="Select Designation" placeholder="Search Designation" label="name" track-by="name" :options="designationList" :multiple="true" :taggable="true"></multiselect>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <div class="form-control-wrap">
                                                        <textarea class="form-control" style="min-height: 50px;" v-model="form.salebill_outward_followup_footer_message"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row g-3 align-center">
                                            <div class="col-lg-2">
                                                <div class="form-group">
                                                    <label class="form-label">Salebill Followup</label>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <div>
                                                        <multiselect v-model="form.salebill_followup_general" tag-placeholder="Select Designation" placeholder="Search Designation" label="name" track-by="name" :options="designationList" :multiple="true" :taggable="true"></multiselect>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <div>
                                                        <multiselect v-model="form.salebill_followup_general" tag-placeholder="Select Designation" placeholder="Search Designation" label="name" track-by="name" :options="designationList" :multiple="true" :taggable="true"></multiselect>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr class="preview-hr">
                                        <div class="row g-3 align-center">
                                            <div class="col-lg-2">
                                                <div class="form-group">
                                                    <label class="form-label">Payment</label>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <div>
                                                        <multiselect v-model="form.payment_general" tag-placeholder="Select Designation" placeholder="Search Designation" label="name" track-by="name" :options="designationList" :multiple="true" :taggable="true"></multiselect>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <div>
                                                        <multiselect v-model="form.payment_supplier" tag-placeholder="Select Designation" placeholder="Search Designation" label="name" track-by="name" :options="designationList" :multiple="true" :taggable="true"></multiselect>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <div class="form-control-wrap">
                                                        <textarea class="form-control" style="min-height: 50px;" v-model="form.payment_footer_message"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row g-3 align-center">
                                            <div class="col-lg-2">
                                                <div class="form-group">
                                                    <label class="form-label">Payment Outward</label>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <div>
                                                        <multiselect v-model="form.payment_outward_followup_general" tag-placeholder="Select Designation" placeholder="Search Designation" label="name" track-by="name" :options="designationList" :multiple="true" :taggable="true"></multiselect>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <div>
                                                        <multiselect v-model="form.payment_outward_followup_supplier" tag-placeholder="Select Designation" placeholder="Search Designation" label="name" track-by="name" :options="designationList" :multiple="true" :taggable="true"></multiselect>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <div class="form-control-wrap">
                                                        <textarea class="form-control" style="min-height: 50px;" v-model="form.payment_outward_footer_message"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row g-3 align-center">
                                            <div class="col-lg-2">
                                                <div class="form-group">
                                                    <label class="form-label">Payment Followup</label>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <div>
                                                        <multiselect v-model="form.payment_outward_followup_general" tag-placeholder="Select Designation" placeholder="Search Designation" label="name" track-by="name" :options="designationList" :multiple="true" :taggable="true"></multiselect>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <div>
                                                        <multiselect v-model="form.payment_outward_followup_supplier" tag-placeholder="Select Designation" placeholder="Search Designation" label="name" track-by="name" :options="designationList" :multiple="true" :taggable="true"></multiselect>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr class="preview-hr">
                                        <div class="row g-3 align-center">
                                            <div class="col-lg-2">
                                                <div class="form-group">
                                                    <label class="form-label">Commission</label>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <div>
                                                        <multiselect v-model="form.commission_general" tag-placeholder="Select Designation" placeholder="Search Designation" label="name" track-by="name" :options="designationList" :multiple="true" :taggable="true"></multiselect>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <div>
                                                        <multiselect v-model="form.commission_supplier" tag-placeholder="Select Designation" placeholder="Search Designation" label="name" track-by="name" :options="designationList" :multiple="true" :taggable="true"></multiselect>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <div class="form-control-wrap">
                                                        <textarea class="form-control" style="min-height: 50px;" v-model="form.commission_footer_message"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row g-3 align-center">
                                            <div class="col-lg-2">
                                                <div class="form-group">
                                                    <label class="form-label">Commission Followup</label>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <div>
                                                        <multiselect v-model="form.commission_followup_general" tag-placeholder="Select Designation" placeholder="Search Designation" label="name" track-by="name" :options="designationList" :multiple="true" :taggable="true"></multiselect>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <div>
                                                        <multiselect v-model="form.commission_followup_supplier" tag-placeholder="Select Designation" placeholder="Search Designation" label="name" track-by="name" :options="designationList" :multiple="true" :taggable="true"></multiselect>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr class="preview-hr">
                                        <div class="row g-3 align-center">
                                            <div class="col-lg-2">
                                                <div class="form-group">
                                                    <label class="form-label">Automated Payment</label>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <div>
                                                        <multiselect v-model="form.automated_payment_general" tag-placeholder="Select Designation" placeholder="Search Designation" label="name" track-by="name" :options="designationList" :multiple="true" :taggable="true"></multiselect>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <div>
                                                        <multiselect v-model="form.automated_payment_supplier" tag-placeholder="Select Designation" placeholder="Search Designation" label="name" track-by="name" :options="designationList" :multiple="true" :taggable="true"></multiselect>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <div class="form-control-wrap">
                                                        <textarea class="form-control" style="min-height: 50px;" v-model="form.automated_payment_footer_message"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row g-3 align-center">
                                            <div class="col-lg-2">
                                                <div class="form-group">
                                                    <label class="form-label">Automated Commission</label>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <div>
                                                        <multiselect v-model="form.automated_commission_followup_general" tag-placeholder="Select Designation" placeholder="Search Designation" label="name" track-by="name" :options="designationList" :multiple="true" :taggable="true"></multiselect>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <div>
                                                        <multiselect v-model="form.automated_commission_followup_supplier" tag-placeholder="Select Designation" placeholder="Search Designation" label="name" track-by="name" :options="designationList" :multiple="true" :taggable="true"></multiselect>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <div class="form-control-wrap">
                                                        <textarea class="form-control" style="min-height: 50px;" v-model="form.automated_commission_followup_footer_message"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr class="preview-hr">
                                        <div class="row g-3 align-center">
                                            <div class="col-lg-2">
                                                <div class="form-group">
                                                    <label class="form-label"></label>
                                                </div>
                                            </div>
                                            <div class="col-lg-10">
                                                <div class="form-group">
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
    </div>
</template>

<script>
    import Multiselect from 'vue-multiselect';

    var smsSettings = [];
    export default {
        name: 'create-sms-settings',
        props: {
            scope: String,
            id: Number,
        },
        components: { 
            Multiselect
        },
        data() {
            return {
                designationList: [],
                form: new Form({
                    id: '',
                    enquiry_general: [],
                    enquiry_supplier: [],
                    enquiry_footer_message: '',
                    enquiry_followup_general: [],
                    enquiry_followup_supplier: [],
                    order_general: [],
                    order_supplier: [],
                    order_footer_message: '',
                    order_followup_general: [],
                    order_followup_supplier: [],
                    complain_general: [],
                    complain_supplier: [],
                    complain_footer_message: '',
                    complain_followup_general: [],
                    complain_followup_supplier: [],
                    general_general: [],
                    general_supplier: [],
                    general_footer_message: '',
                    general_followup_general: [],
                    general_followup_supplier: [],
                    salebill_inward_general: [],
                    salebill_inward_supplier: [],
                    salebill_inward_footer_message: '',
                    salebill_outward_followup_general: [],
                    salebill_outward_followup_supplier: [],
                    salebill_outward_followup_footer_message: '',
                    salebill_followup_general: [],
                    salebill_followup_supplier: [],
                    payment_general: [],
                    payment_supplier: [],
                    payment_footer_message: '',
                    payment_outward_followup_general: [],
                    payment_outward_followup_supplier: [],
                    payment_outward_footer_message: '',
                    payment_followup_general: [],
                    payment_followup_supplier: [],
                    commission_general: [],
                    commission_supplier: [],
                    commission_footer_message: '',
                    commission_followup_general: [],
                    commission_followup_supplier: [],
                    automated_payment_general: [],
                    automated_payment_supplier: [],
                    automated_payment_footer_message: '',
                    automated_commission_followup_general: [],
                    automated_commission_followup_supplier: [],
                    automated_commission_followup_footer_message: '',
                })
            }
        },
        created() {
            axios.get('/settings/sms-settings/list-designation')
            .then(response => {
                this.designationList = response.data;
            });
        },
        methods: {
            register () {
                this.form.post('/settings/sms-settings/update')
                    .then(( response ) => {
                        window.location.href = '/settings/sms-settings';
                })
            },
        },
        mounted() {
            axios.get(`/settings/sms-settings/fetch-sms-settings/${this.id}`)
            .then(response => {
                smsSettings = response.data;
                console.log(smsSettings);
                this.form.id = this.id;
                this.form.enquiry_general = smsSettings.enquiry_general;
                this.form.enquiry_supplier = smsSettings.enquiry_supplier;
                this.form.enquiry_footer_message = smsSettings.enquiry_footer_message;
                this.form.enquiry_followup_general = smsSettings.enquiry_followup_general;
                this.form.enquiry_followup_supplier = smsSettings.enquiry_followup_supplier;
                this.form.order_general = smsSettings.order_general;
                this.form.order_supplier = smsSettings.order_supplier;
                this.form.order_footer_message = smsSettings.order_footer_message;
                this.form.order_followup_general = smsSettings.order_followup_general;
                this.form.order_followup_supplier = smsSettings.order_followup_supplier;
                this.form.complain_general = smsSettings.complain_general;
                this.form.complain_supplier = smsSettings.complain_supplier;
                this.form.complain_footer_message = smsSettings.complain_footer_message;
                this.form.complain_followup_general = smsSettings.complain_followup_general;
                this.form.complain_followup_supplier = smsSettings.complain_followup_supplier;
                this.form.general_general = smsSettings.general_general;
                this.form.general_supplier = smsSettings.general_supplier;
                this.form.general_footer_message = smsSettings.general_footer_message;
                this.form.general_followup_general = smsSettings.general_followup_general;
                this.form.general_followup_supplier = smsSettings.general_followup_supplier;
                this.form.salebill_inward_general = smsSettings.salebill_inward_general;
                this.form.salebill_inward_supplier = smsSettings.salebill_inward_supplier;
                this.form.salebill_inward_footer_message = smsSettings.salebill_inward_footer_message;
                this.form.salebill_outward_followup_general = smsSettings.salebill_outward_followup_general;
                this.form.salebill_outward_followup_supplier = smsSettings.salebill_outward_followup_supplier;
                this.form.salebill_outward_followup_footer_message = smsSettings.salebill_outward_followup_footer_message;
                this.form.salebill_followup_general = smsSettings.salebill_followup_general;
                this.form.salebill_followup_supplier = smsSettings.salebill_followup_supplier;
                this.form.payment_general = smsSettings.payment_general;
                this.form.payment_supplier = smsSettings.payment_supplier;
                this.form.payment_footer_message = smsSettings.payment_footer_message;
                this.form.payment_outward_followup_general = smsSettings.payment_outward_followup_general;
                this.form.payment_outward_followup_supplier = smsSettings.payment_outward_followup_supplier;
                this.form.payment_outward_footer_message = smsSettings.payment_outward_footer_message;
                this.form.payment_followup_general = smsSettings.payment_followup_general;
                this.form.payment_followup_supplier = smsSettings.payment_followup_supplier;
                this.form.commission_general = smsSettings.commission_general;
                this.form.commission_supplier = smsSettings.commission_supplier;
                this.form.commission_footer_message = smsSettings.commission_footer_message;
                this.form.commission_followup_general = smsSettings.commission_followup_general;
                this.form.commission_followup_supplier = smsSettings.commission_followup_supplier;
                this.form.automated_payment_general = smsSettings.automated_payment_general;
                this.form.automated_payment_supplier = smsSettings.automated_payment_supplier;
                this.form.automated_payment_footer_message = smsSettings.automated_payment_footer_message;
                this.form.automated_commission_followup_general = smsSettings.automated_commission_followup_general;
                this.form.automated_commission_followup_supplier = smsSettings.automated_commission_followup_supplier;
                this.form.automated_commission_followup_footer_message = smsSettings.automated_commission_followup_footer_message;
            });
        },
    };
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<style>
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
        position: unset;
    }
    .multiselect__tag-icon:focus, .multiselect__tag-icon:hover {
        background: #ebeef2;
    }
    .multiselect__tag-icon:focus:after, .multiselect__tag-icon:hover:after {
        color: #526484;
    }
</style>