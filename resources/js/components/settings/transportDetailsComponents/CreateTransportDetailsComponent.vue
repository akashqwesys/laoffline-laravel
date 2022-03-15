<template>    
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 v-if="scope == 'edit'" class="nk-block-title page-title">Edit Transport Details</h3>
                                <h3 v-else class="nk-block-title page-title">Add Transport Details</h3>
                                <div class="nk-block-des text-soft">
                                    <p>Please fill the all details.</p>
                                </div>
                            </div><!-- .nk-block-head-content -->                            
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    <div class="nk-block">
                        <div class="card card-bordered">
                            <div class="card-inner">
                                <form action="#" @submit.prevent="register()">
                                    <input type="hidden" v-if="scope == 'edit'" id="fv-group-id" v-model="form.id">
                                    <div class="preview-block">
                                        <div class="row gy-4">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-name">Name</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="fv-name" v-model="form.name" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-gstin">GSTIN</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="fv-gstin" v-model="form.gstin" required>
                                                    </div>
                                                </div>
                                            </div> 
                                        </div>
                                        <hr class="preview-hr">                                        
                                        <div class="row gy-4">
                                            <div class="col-md-12 d-flex align-items-center">
                                                <span class="preview-title-lg overline-title d-inline-block w-100">Multiple Addresses</span>
                                                <li class="dropdown-toggle btn btn-icon btn-primary" @click="addMultipleAddressesRow"><em class="icon ni ni-plus"></em></li>
                                            </div>
                                        </div>
                                        <div v-for="(multipleAddress, index) in multipleAddresses" :key="index">
                                            <div class="row gy-4">
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="form-label" for="fw-contact_person_name">Contact Person Name</label>
                                                        <div class="form-control-wrap">
                                                            <input v-model="multipleAddress.contact_person_name" type="text" class="form-control" id="fw-contact_person_name">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="form-label" for="fw-email">Email</label>
                                                        <div class="form-control-wrap">
                                                            <input v-model="multipleAddress.contact_person_email" type="text" class="form-control" id="fw-email">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="form-label" for="fw-office_no">Office No</label>
                                                        <div class="form-control-wrap">
                                                            <input v-model="multipleAddress.contact_person_office_no" type="text" class="form-control" id="fw-office_no">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="form-group">
                                                        <label class="form-label" for="fv-ma_address">Address</label>
                                                        <div class="form-control-wrap">
                                                            <textarea class="form-control" style="min-height: 0" id="fv-ma_address" v-model="multipleAddress.contact_person_address"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-1 d-flex align-items-center flex-row-reverse">
                                                    <li class="dropdown-toggle btn btn-icon btn-primary" @click="deleteMultipleAddressesRow(multipleAddress)"><em class="icon ni ni-cross"></em></li>
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
    </div>
</template>

<script>
    var transportDetails = [];
    export default {
        name: 'createCompanyCategory',
        props: {
            scope: String,
            id: Number,
        },
        data() {
            return {
                cancel_url: '/settings/transport-details',
                multipleAddresses : [{
                    contact_person_name : '',
                    contact_person_address : '',
                    contact_person_office_no : '',
                    contact_person_email: '',
                }],
                form: new Form({
                    id: '',
                    name: '',
                    gstin: '',
                    multiple_address: [],
                })
            }
        },
        methods: {
            addMultipleAddressesRow: function() {
                this.multipleAddresses.push({
                    contact_person_name : '',
                    contact_person_address : '',
                    contact_person_office_no : '',
                    contact_person_email: '',
                });
            },
            deleteMultipleAddressesRow: function(row) {
                this.multipleAddresses.pop(row);
            },
            register () {
                this.form.multiple_address = this.multipleAddresses;
                if (this.scope == 'edit') {
                    this.form.post('/settings/transport-details/update')
                        .then(( response ) => {
                            window.location.href = '/settings/transport-details';
                    })
                } else {
                    this.form.post('/settings/transport-details/create')
                        .then(( response ) => {
                            window.location.href = '/settings/transport-details';
                    })
                }
            },
        },
        mounted() {
            switch (this.scope) {
                case 'edit' :
                    axios.get(`/settings/transport-details/fetch-transport-details/${this.id}`)
                    .then(response => {
                        transportDetails = response.data;
                        console.log(transportDetails);
                        this.form.id = transportDetails.id;
                        this.form.name = transportDetails.name;
                        this.form.gstin = transportDetails.gstin;
                        this.multipleAddresses = transportDetails.multiple_address;

                    });
                    break;
                default:
                    break;
            }
        },
    };
</script>