<template>
<div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">{{ companyDetails.company_name }}</h3>
                            </div><!-- .nk-block-head-content -->
                            <div class="nk-block-head-content">
                                <div class="toggle-wrap nk-block-tools-toggle">
                                    <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                    <div class="toggle-expand-content" data-content="pageMenu">
                                        <ul class="nk-block-tools g-3">
                                            <li class="nk-block-tools-opt">
                                                <a v-bind:href="go_back" class="btn btn-wider btn-primary"><em class="icon ni ni-arrow-left"></em><span>Go Back</span></a>
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
                                <ul class="nav nav-tabs mt-n3">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-toggle="tab" href="#tabItem1">Company Details</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#tabItem2">Addresses</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#tabItem3">Contact Details</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#tabItem4">Emails</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#tabItem5">SWOT Details</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#tabItem6">Packaging Details</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#tabItem7">References</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#tabItem8">Bank Details</a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tabItem1">
                                        <div class="nk-data data-list">
                                            <div class="data-head">
                                                <h6 class="overline-title">Basics</h6>
                                            </div>
                                            <div class="data-item">
                                                <div class="data-col">
                                                    <span class="data-label">Company Name</span>
                                                    <span class="data-value">{{ companyDetails.company_name }}</span>
                                                </div>
                                            </div><!-- data-item -->
                                            <div class="data-item">
                                                <div class="data-col">
                                                    <span class="data-label">Company Type</span>
                                                    <span class="data-value">{{ companyDetails.company_type }}</span>
                                                </div>
                                            </div><!-- data-item -->
                                            <div class="data-item">
                                                <div class="data-col">
                                                    <span class="data-label">Website</span>
                                                    <span v-if="companyDetails.company_website != ''" class="data-value">{{ companyDetails.company_website }}</span>
                                                    <span v-else class="data-value text-soft">Not add yet</span>
                                                </div>
                                            </div><!-- data-item -->
                                            <div class="data-item">
                                                <div class="data-col">
                                                    <span class="data-label">Landline Number</span>
                                                    <span v-if="companyDetails.company_landline != ''" class="data-value">{{ companyDetails.company_landline }}</span>
                                                    <span v-else class="data-value text-soft">Not add yet</span>
                                                </div>
                                            </div><!-- data-item -->
                                            <div class="data-item">
                                                <div class="data-col">
                                                    <span class="data-label">Mobile Number</span>
                                                    <span v-if="companyDetails.company_mobile != ''" class="data-value">{{ companyDetails.company_mobile }}</span>
                                                    <span v-else class="data-value text-soft">Not add yet</span>
                                                </div>
                                            </div><!-- data-item -->
                                            <div class="data-item" data-tab-target="#address">
                                                <div class="data-col">
                                                    <span class="data-label">About Company</span>
                                                    <span v-if="companyDetails.company_about != ''" class="data-value">{{ companyDetails.company_about }}</span>
                                                    <span v-else class="data-value text-soft">Not add yet</span>
                                                </div>
                                            </div><!-- data-item -->
                                        </div><!-- data-list -->
                                        <div class="nk-data data-list">
                                            <div class="data-head">
                                                <h6 class="overline-title">Remark Watchout</h6>
                                            </div>
                                            <div class="data-item">
                                                <div class="data-col">
                                                    <span class="data-label">Watchout</span>
                                                    <span v-if="companyDetails.company_watchout == 1" class="data-value">Yes</span>
                                                    <span v-else-if="companyDetails.company_watchout == 0" class="data-value">No</span>
                                                    <span v-else class="data-value text-soft">Not add yet</span>
                                                </div>
                                            </div><!-- data-item -->
                                            <div class="data-item">
                                                <div class="data-col">
                                                    <span class="data-label">Remark Watchout</span>
                                                    <span v-if="companyDetails.company_remark_watchout != ''" class="data-value">{{ companyDetails.company_remark_watchout }}</span>
                                                    <span v-else class="data-value text-soft">Not add yet</span>
                                                </div>
                                            </div><!-- data-item -->
                                        </div><!-- data-list -->
                                    </div>
                                    <div class="tab-pane" id="tabItem2">
                                        <div id="accordion" class="accordion">
                                            <div class="accordion-item" v-for="(address, index) in multipleAddress" :key="index">
                                                <a href="#" class="accordion-head" data-toggle="collapse" :data-target="'#accordion-item-'+index">
                                                    <h6 class="title">Address Type - "{{ address.address_type_name }}"</h6>
                                                    <span class="accordion-icon"></span>
                                                </a>
                                                <div v-bind:class="(index == 0)?'accordion-body collapse show':'accordion-body collapse'" :id="'accordion-item-'+index" data-parent="#accordion">
                                                    <div class="accordion-inner">
                                                        <div class="data-head">
                                                            <h6 class="overline-title">Address</h6>
                                                        </div>
                                                        <div class="data-item">
                                                            <div class="data-col">
                                                                <span class="data-value">{{ address.address }}</span>
                                                            </div>
                                                        </div><!-- data-item -->
                                                        <div class="data-head">
                                                            <h6 class="overline-title">Contacts</h6>
                                                        </div>
                                                        <div class="data-item">
                                                            <div class="data-col">
                                                                <table class=" nowrap table">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>No</th>
                                                                            <th>Name</th>
                                                                            <th>Designation</th>
                                                                            <th>Mobile</th>
                                                                            <th>Email</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr v-for="(owner, key) in address.multipleAddressesOwners" :key="key">
                                                                            <td>{{ key + 1 }}</td>
                                                                            <td>
                                                                                <div class="user-card">
                                                                                    <div class="user-avatar user-avatar-sm bg-warning">
                                                                                        <img v-if="owner.contact_profile_pic != ''" v-bind:src="getProfilePic(owner.contact_profile_pic)" alt="">
                                                                                        <span v-if="owner.contact_profile_pic == ''">{{ owner.name.charAt(0) }}</span>
                                                                                    </div>
                                                                                    <div class="user-name"><span class="tb-lead">{{ owner.name }}</span></div>
                                                                                </div>
                                                                            </td>
                                                                            <td>{{ owner.designation_name }}</td>
                                                                            <td>{{ owner.mobile }}</td>
                                                                            <td>{{ owner.mail }}</td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div><!-- data-item -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="tabItem3">
                                        <div class="data-head">
                                            <h6 class="overline-title">Contacts</h6>
                                        </div>
                                        <div class="data-item">
                                            <div class="data-col">
                                                <table class=" nowrap table">
                                                    <thead>
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Name</th>
                                                            <th>Designation</th>
                                                            <th>Mobile</th>
                                                            <th>Email</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="(contactDetail, key) in contactDetails" :key="key">
                                                            <td>{{ key + 1 }}</td>
                                                            <td>
                                                                <div class="user-card">
                                                                    <div class="user-avatar user-avatar-sm bg-warning">
                                                                        <img v-if="contactDetail.contact_person_profile_pic != ''" v-bind:src="getProfilePic(contactDetail.contact_person_profile_pic)" alt="">
                                                                        <span v-if="contactDetail.contact_person_profile_pic == ''">{{ contactDetail.contact_person_name.charAt(0) }}</span>
                                                                    </div>
                                                                    <div class="user-name"><span class="tb-lead">{{ contactDetail.contact_person_name }}</span></div>
                                                                </div>
                                                            </td>
                                                            <td>{{ contactDetail.contact_person_designation_name }}</td>
                                                            <td>{{ contactDetail.contact_person_mobile }}</td>
                                                            <td>{{ contactDetail.contact_person_mail }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div><!-- data-item -->
                                    </div>
                                    <div class="tab-pane" id="tabItem4">
                                        <div class="nk-data data-list">
                                            <div class="data-head">
                                                <h6 class="overline-title">Emails</h6>
                                            </div>
                                            <div class="data-item" v-for="(email, key) in multipleEmail" :key="key">
                                                <div class="data-col">
                                                    <span class="data-value">{{ email.email_id }}</span>
                                                </div>
                                            </div><!-- data-item -->
                                        </div><!-- data-list -->
                                    </div>
                                    <div class="tab-pane" id="tabItem5">
                                        <div class="nk-data data-list">
                                            <div class="data-item">
                                                <div class="data-col">
                                                    <span class="data-label">Strength</span>
                                                    <span v-if="swotDetail.strength != ''" class="data-value">{{ swotDetail.strength }}</span>
                                                    <span v-else class="data-value text-soft">Not add yet</span>
                                                </div>
                                            </div><!-- data-item -->
                                            <div class="data-item">
                                                <div class="data-col">
                                                    <span class="data-label">Weakness</span>
                                                    <span v-if="swotDetail.weakness != ''" class="data-value">{{ swotDetail.weakness }}</span>
                                                    <span v-else class="data-value text-soft">Not add yet</span>
                                                </div>
                                            </div><!-- data-item -->
                                            <div class="data-item">
                                                <div class="data-col">
                                                    <span class="data-label">Opportunity</span>
                                                    <span v-if="swotDetail.opportunity != ''" class="data-value">{{ swotDetail.opportunity }}</span>
                                                    <span v-else class="data-value text-soft">Not add yet</span>
                                                </div>
                                            </div><!-- data-item -->
                                            <div class="data-item">
                                                <div class="data-col">
                                                    <span class="data-label">Threat</span>
                                                    <span v-if="swotDetail.threat != ''" class="data-value">{{ swotDetail.threat }}</span>
                                                    <span v-else class="data-value text-soft">Not add yet</span>
                                                </div>
                                            </div><!-- data-item -->
                                        </div><!-- data-list -->
                                    </div>
                                    <div class="tab-pane" id="tabItem6">
                                        <div class="nk-data data-list">
                                            <div class="data-item">
                                                <div class="data-col">
                                                    <span class="data-label">GST No.</span>
                                                    <span v-if="packagingDetail.gst_no != ''" class="data-value">{{ packagingDetail.gst_no }}</span>
                                                    <span v-else class="data-value text-soft">Not add yet</span>
                                                </div>
                                            </div><!-- data-item -->
                                            <div class="data-item">
                                                <div class="data-col">
                                                    <span class="data-label">CST No.</span>
                                                    <span v-if="packagingDetail.cst_no != ''" class="data-value">{{ packagingDetail.cst_no }}</span>
                                                    <span v-else class="data-value text-soft">Not add yet</span>
                                                </div>
                                            </div><!-- data-item -->
                                            <div class="data-item">
                                                <div class="data-col">
                                                    <span class="data-label">TIN No.</span>
                                                    <span v-if="packagingDetail.tin_no != ''" class="data-value">{{ packagingDetail.tin_no }}</span>
                                                    <span v-else class="data-value text-soft">Not add yet</span>
                                                </div>
                                            </div><!-- data-item -->
                                            <div class="data-item">
                                                <div class="data-col">
                                                    <span class="data-label">VAT No.</span>
                                                    <span v-if="packagingDetail.vat_no != ''" class="data-value">{{ packagingDetail.vat_no }}</span>
                                                    <span v-else class="data-value text-soft">Not add yet</span>
                                                </div>
                                            </div><!-- data-item -->
                                        </div><!-- data-list -->
                                    </div>
                                    <div class="tab-pane" id="tabItem7">
                                        <div class="nk-data data-list">
                                            <div class="data-item">
                                                <div class="data-col">
                                                    <span class="data-label">Name</span>
                                                    <span v-if="referenceDetail.ref_person_name != ''" class="data-value">{{ referenceDetail.ref_person_name }}</span>
                                                    <span v-else class="data-value text-soft">Not add yet</span>
                                                </div>
                                            </div><!-- data-item -->
                                            <div class="data-item">
                                                <div class="data-col">
                                                    <span class="data-label">Mobile No.</span>
                                                    <span v-if="referenceDetail.ref_person_mobile != ''" class="data-value">{{ referenceDetail.ref_person_mobile }}</span>
                                                    <span v-else class="data-value text-soft">Not add yet</span>
                                                </div>
                                            </div><!-- data-item -->
                                            <div class="data-item">
                                                <div class="data-col">
                                                    <span class="data-label">Company Name</span>
                                                    <span v-if="referenceDetail.ref_person_company != ''" class="data-value">{{ referenceDetail.ref_person_company }}</span>
                                                    <span v-else class="data-value text-soft">Not add yet</span>
                                                </div>
                                            </div><!-- data-item -->
                                            <div class="data-item">
                                                <div class="data-col">
                                                    <span class="data-label">Address</span>
                                                    <span v-if="referenceDetail.ref_person_address != ''" class="data-value">{{ referenceDetail.ref_person_address }}</span>
                                                    <span v-else class="data-value text-soft">Not add yet</span>
                                                </div>
                                            </div><!-- data-item -->
                                        </div><!-- data-list -->
                                    </div>
                                    <div class="tab-pane" id="tabItem8">
                                        <div class="nk-data data-list">
                                            <div class="data-item">
                                                <div class="data-col">
                                                    <span class="data-label">Bank Name</span>
                                                    <span v-if="bankDetail.bank_name != ''" class="data-value">{{ bankDetail.bank_name }}</span>
                                                    <span v-else class="data-value text-soft">Not add yet</span>
                                                </div>
                                            </div><!-- data-item -->
                                            <div class="data-item">
                                                <div class="data-col">
                                                    <span class="data-label">Account Holder Name</span>
                                                    <span v-if="bankDetail.account_holder_name != ''" class="data-value">{{ bankDetail.account_holder_name }}</span>
                                                    <span v-else class="data-value text-soft">Not add yet</span>
                                                </div>
                                            </div><!-- data-item -->
                                            <div class="data-item">
                                                <div class="data-col">
                                                    <span class="data-label">Account No.</span>
                                                    <span v-if="bankDetail.account_no != ''" class="data-value">{{ bankDetail.account_no }}</span>
                                                    <span v-else class="data-value text-soft">Not add yet</span>
                                                </div>
                                            </div><!-- data-item -->
                                            <div class="data-item">
                                                <div class="data-col">
                                                    <span class="data-label">Branch Name</span>
                                                    <span v-if="bankDetail.branch_name != ''" class="data-value">{{ bankDetail.branch_name }}</span>
                                                    <span v-else class="data-value text-soft">Not add yet</span>
                                                </div>
                                            </div><!-- data-item -->
                                            <div class="data-item">
                                                <div class="data-col">
                                                    <span class="data-label">IFSC Code</span>
                                                    <span v-if="bankDetail.ifsc_code != ''" class="data-value">{{ bankDetail.ifsc_code }}</span>
                                                    <span v-else class="data-value text-soft">Not add yet</span>
                                                </div>
                                            </div><!-- data-item -->
                                        </div><!-- data-list -->
                                    </div>
                                </div>
                            </div><!-- .card -->
                        </div>
                    </div><!-- .nk-block -->
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    var companies = [];
    export default {
        name: 'createCompany',
        props: {
            id: Number,
        },
        data() {
            return {
                go_back: '/reference',
                companyDetails : [],
                contactDetails : [],
                multipleAddress : [],
                multipleEmail : [],
                swotDetail : [],
                bankDetail : [],
                packagingDetail : [],
                referenceDetail : [],
            }
        },
        methods: {
            getProfilePic(name){
                return '/upload/company/multipleAddressProfilePic/' + name;
            },
        },
        mounted() {
            axios.get(`/databank/companies/fetch-company/${this.id}`)
            .then(response => {
                companies = response.data;

                this.companyDetails = companies.company;
                this.contactDetails = companies.contact_details;
                this.multipleAddress = companies.multiple_address;
                this.multipleEmail = companies.multiple_emails;
                this.swotDetail = companies.swot_details;
                this.bankDetail = companies.bank_details;
                this.packagingDetail = companies.packaging_details;
                this.referenceDetail = companies.references_details;
            });
        },
    };
</script>
