<template>    
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <!-- <h3 v-if="scope == 'edit'" class="nk-block-title page-title">Edit Agent</h3> -->
                                <!-- <h3 v-else class="nk-block-title page-title">Add Agent</h3> -->
                                <h3 class="nk-block-title page-title">Add Inward</h3>
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
                                    <!-- <input type="hidden" v-if="scope == 'edit'" id="fv-group-id" v-model="form.id"> -->
                                    <div class="preview-block">
                                        <span class="preview-title-lg overline-title">Insert Inward Details</span>
                                        <div class="row gy-4">
                                            <div v-if="inwardType == 'call'" class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-name">Call By</label>
                                                    <div class="form-control-wrap">
                                                        <multiselect v-model="form.call_by" :options="CallBy" placeholder="Select one" label="name" track-by="name"></multiselect>
                                                    </div>
                                                </div>
                                            </div>
                                            <div v-if="inwardType != 'sample'" class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-name">Reference Via</label>
                                                    <div class="form-control-wrap">
                                                        <multiselect v-model="form.reference_via" :options="referenceVia" placeholder="Select one" label="name" track-by="name" @input="getReferenceData"></multiselect>
                                                    </div>
                                                </div>
                                            </div>
                                            <div v-if="inwardType == 'letter'" class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-name">By</label>
                                                    <div class="form-control-wrap">
                                                        <multiselect v-model="form.letter_by" :options="letterBy" placeholder="Select one" label="name" track-by="name"></multiselect>
                                                    </div>
                                                </div>
                                            </div>
                                            <div v-if="inwardType == 'sample'" class="col-md-12">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-sample_via">Sample Via</label>
                                                    <div class="form-control-wrap">
                                                        <multiselect v-model="form.sample_via" :options="letterBy" placeholder="Select one" label="name" track-by="name"></multiselect>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div v-if="inwardType == 'sample'" class="row gy-4">
                                            <div class="col-md-12">
                                                <table class="table table-hover table-bordered" id="agent">
                                                    <thead>
                                                        <tr>
                                                            <th></th>
                                                            <th>Ref. no</th>
                                                            <th>Generated By</th>
                                                            <th>Date</th>
                                                            <th>Time</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="(refsample, index) in referenceSampleData" :key="index">
                                                            <td>
                                                                <div class="custom-control custom-control-sm custom-radio notext">
                                                                    <input type="radio" class="custom-control-input" :id="'referenceSample'+index" :value="refsample.reference_id" v-model="form.reference_sample_data">
                                                                    <label class="custom-control-label" :for="'referenceSample'+index"></label>
                                                                </div>
                                                            </td>
                                                            <td>{{ refsample.reference_id }}</td>
                                                            <td>Rec.</td>
                                                            <td>{{ dateFormate(refsample.created_at) }}</td>
                                                            <td>{{ timeFormate(refsample.created_at) }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div v-if="inwardType == 'sample'" class="row gy-4">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <div class="form-control-wrap">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" placeholder="Enter Reference number" v-model="referenceNumber">
                                                            <div class="input-group-append">
                                                                <button type="button" class="btn btn-outline-primary btn-dim" @click="getReferenceDataViaId(referenceNumber)">Go</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div v-if="referenceSampleError == 1" class="col-md-12">
                                                <div class="form-group">
                                                    <div class="form-control-wrap">
                                                        <span style="color: rgb(101, 118, 255);">This Reference Id is not generated by logged in User.</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row gy-4">
                                            <div v-if="inwardType != 'sample'" class="col-md-4">
                                                <div class="preview-block">
                                                    <label class="form-label">Reference</label>
                                                    <ul class="custom-control-group g-3 align-center">
                                                        <li>
                                                            <div class="custom-control custom-radio checked">
                                                                <input type="radio" class="custom-control-input" name="is_active" v-model="form.inward_reference" :checked="form.inward_reference == '1'" id="fv-active-yes" value="1" @click="showReferenceDetails(1)">
                                                                <label class="custom-control-label" for="fv-active-yes">New Reference</label>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" class="custom-control-input" name="is_active" v-model="form.inward_reference" id="fv-active-no" value="0" @click="showReferenceDetails(0)">
                                                                <label class="custom-control-label" for="fv-active-no">Old Reference</label>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div v-if="this.oldReference == 1" class="col-md-12">
                                                <div class="card card-preview">
                                                    <div class="card-inner">
                                                        <ul class="nav nav-tabs mt-n3">
                                                            <li class="nav-item">
                                                                <a class="nav-link active" data-toggle="tab" href="#own">Own</a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link" data-toggle="tab" href="#related">Related</a>
                                                            </li>
                                                        </ul>
                                                        <div class="tab-content">
                                                            <div class="tab-pane active" id="own">
                                                                <div class="row gy-4">
                                                                    <div class="col-md-12">
                                                                        <table class="table table-hover table-bordered" id="agent">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th></th>
                                                                                    <th>Ref. no</th>
                                                                                    <th>Date</th>
                                                                                    <th>Time</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td>
                                                                                        <div class="custom-control custom-control-sm custom-radio notext">
                                                                                            <input type="radio" class="custom-control-input" id="uid1">
                                                                                            <label class="custom-control-label" for="uid1"></label>
                                                                                        </div>
                                                                                    </td>
                                                                                    <td>Ref. no</td>
                                                                                    <td>Date</td>
                                                                                    <td>Time</td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                                <div class="row gy-4">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <div class="form-control-wrap">
                                                                                <div class="input-group">
                                                                                    <input type="text" class="form-control" placeholder="Enter Reference number" v-model="referenceNumber">
                                                                                    <div class="input-group-append">
                                                                                        <button class="btn btn-outline-primary btn-dim">Go</button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <div class="form-control-wrap">
                                                                                <span style="color: rgb(101, 118, 255);">This Reference Id is not generated by logged in User.</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="tab-pane" id="related">
                                                                <div class="row gy-4">
                                                                    <div class="col-md-12">
                                                                        <table class="table table-hover table-bordered" id="agent">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th></th>
                                                                                    <th>Ref. no</th>
                                                                                    <th>Date</th>
                                                                                    <th>Time</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td>
                                                                                        <div class="custom-control custom-control-sm custom-radio notext">
                                                                                            <input type="radio" class="custom-control-input" id="uid1">
                                                                                            <label class="custom-control-label" for="uid1"></label>
                                                                                        </div>
                                                                                    </td>
                                                                                    <td>Ref. no</td>
                                                                                    <td>Date</td>
                                                                                    <td>Time</td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                                <div class="row gy-4">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <div class="form-control-wrap">
                                                                                <div class="input-group">
                                                                                    <input type="text" class="form-control" placeholder="Enter Reference number" v-model="referenceNumber">
                                                                                    <div class="input-group-append">
                                                                                        <button class="btn btn-outline-primary btn-dim">Go</button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <div class="form-control-wrap">
                                                                                <span style="color: rgb(101, 118, 255);">This Reference Id is not generated by logged in User.</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-first-name">Date/Time</label>
                                                    <div class="form-control-wrap">
                                                        <input type="date" class="form-control" id="fv-Reference-date" v-model="form.dateTime">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group code-block">
                                                    <label class="form-label" for="fw-company">Company</label>
                                                    <button type="button" class="btn btn-sm clipboard-init" @click="assignCompanyType('1-2')"><span class="clipboard-text">Add New</span></button>
                                                    <div>
                                                        <multiselect v-model="form.company" :options="companies" placeholder="Select one" label="company_name" track-by="company_name" @input="getFromName"></multiselect>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4" v-if="suppliersVal == 0 && inwardType != 'sample'">
                                                <div class="form-group code-block">
                                                    <label class="form-label" for="fw-supplier">Supplier</label>
                                                    <button type="button" class="btn btn-sm clipboard-init" @click="assignCompanyType('3')"><span class="clipboard-text">Add New</span></button>
                                                    <div>
                                                        <multiselect v-model="form.supplier" :options="suppliers" placeholder="Select one" label="company_name" track-by="company_name" @input="getSupplierFromName"></multiselect>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group code-block">
                                                    <label class="form-label" for="fv-from_name">From Name</label>
                                                    <button type="button" class="btn btn-sm clipboard-init" data-toggle="modal" data-target="#AddPerson" title="Person Detail"><span class="clipboard-text">Add New</span></button>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="fv-from_name" v-model="form.from_name" placeholder="Enter From Name">
                                                    </div>
                                                </div>
                                            </div>
                                            <div v-if="inwardType == 'call' && inwardType == 'message' && inwardType == 'whatsapp'" class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-from_number">From Number</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="fv-from_number" v-model="form.from_number" placeholder="Enter From Number">
                                                    </div>
                                                </div>
                                            </div>
                                            <div v-if="inwardType == 'call' && inwardType == 'message' && inwardType == 'whatsapp'" class="col-md-4">
                                                <div class="form-group code-block">
                                                    <label class="form-label" for="fv-to_name">To Name</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="fv-to_name" v-model="form.to_name" placeholder="Enter To Name">
                                                    </div>
                                                </div>
                                            </div>
                                            <div v-if="inwardType == 'call' && inwardType == 'message' && inwardType == 'whatsapp'" class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-receiver_number">Receiver Number</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="fv-receiver_number" placeholder="Enter Receiver number" v-model="form.receiver_number">
                                                    </div>
                                                </div>
                                            </div>
                                            <div v-if="inwardType == 'email'" class="col-md-4">
                                                <div class="form-group code-block">
                                                    <label class="form-label" for="fv-from_email_id">From Email Id</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="fv-from_email_id" v-model="form.from_email_id" placeholder="Enter To Name">
                                                    </div>
                                                </div>
                                            </div>
                                            <div v-if="inwardType == 'email'" class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-receiver_email_id">Receiver Email Id</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="fv-receiver_email_id" placeholder="Enter Receiver number" v-model="form.receiver_email_id">
                                                    </div>
                                                </div>
                                            </div>
                                            <div v-if="inwardType != 'email' &&inwardType != 'letter' && inwardType != 'sample'" class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-delivery_period">Delivery Period</label>
                                                    <div class="form-control-wrap">
                                                        <input type="number" class="form-control" id="fv-delivery_period" placeholder="Enter Receiver number" v-model="form.delivery_period">
                                                    </div>
                                                </div>
                                            </div>
                                            <div v-if="(inwardType == 'letter' && form.letter_by.id == 2) || (inwardType == 'sample' && form.sample_via.id == 2)" class="col-md-4">
                                                <div class="form-group code-block">
                                                    <label class="form-label" for="fv-courier_name">Courrier Name</label>
                                                    <div class="form-control-wrap">
                                                        <multiselect v-model="form.courier_name" :options="companies" placeholder="Select one" label="company_name" track-by="company_name"></multiselect>
                                                    </div>
                                                </div>
                                            </div>
                                            <div v-if="(inwardType == 'letter' && form.letter_by.id == 2) || (inwardType == 'sample' && form.sample_via.id == 2)" class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-courier_receipt_number">Courrier Receipt No</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="fv-courier_receipt_number" v-model="form.courier_receipt_number" placeholder="Enter From Number">
                                                    </div>
                                                </div>
                                            </div>
                                            <div v-if="inwardType == 'letter' || inwardType == 'sample'" class="col-md-4">
                                                <div class="form-group code-block">
                                                    <label class="form-label" for="fv-weight_of_parcel">Weight Of Parcel</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="fv-weight_of_parcel" v-model="form.weight_of_parcel" placeholder="Enter To Name">
                                                    </div>
                                                </div>
                                            </div>
                                            <div v-if="inwardType == 'letter' || inwardType == 'sample'" class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-received_date_time">Received Date Time</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="fv-received_date_time" placeholder="Enter Receiver number" v-model="form.received_date_time">
                                                    </div>
                                                </div>
                                            </div>
                                            <div v-if="inwardType == 'letter' || inwardType == 'sample'" class="col-md-4">
                                                <div class="form-group code-block">
                                                    <label class="form-label" for="fv-delivery_by">Delivery By</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="fv-delivery_by" v-model="form.delivery_by" placeholder="Enter To Name">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label">Multiple Attachments</label>
                                                    <div class="form-control-wrap">
                                                        <div class="custom-file">
                                                            <input type="file" multiple class="custom-file-input" id="customMultipleFiles">
                                                            <label class="custom-file-label" for="customMultipleFiles">Choose files</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-remark">Remark</label>
                                                    <div class="form-control-wrap">
                                                        <textarea class="form-control no-resize" id="fv-remark" v-model="form.remark"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr class="preview-hr">
                                        <span v-if="inwardType != 'sample'" class="preview-title-lg overline-title">Required Followup</span>
                                        <div v-if="inwardType != 'sample'" class="row gy-4">
                                            <div class="col-md-12">
                                                <div class="preview-block">
                                                    <ul class="custom-control-group g-3 align-center">
                                                        <li>
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" class="custom-control-input" name="required_followup" v-model="form.required_followup" id="fv-yes" value="1" >
                                                                <label class="custom-control-label" for="fv-yes">Yes</label>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" class="custom-control-input" name="required_followup" v-model="form.required_followup" id="fv-no" value="0" >
                                                                <label class="custom-control-label" for="fv-no">No</label>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <span v-if="inwardType == 'sample'" class="preview-title-lg overline-title">Sample For</span>
                                        <div v-if="inwardType == 'sample'" class="row gy-4">
                                            <div class="col-md-12">
                                                <div class="form-group code-block">
                                                    <label class="form-label" for="fw-link_with">Sample For</label>
                                                    <div>
                                                        <multiselect v-model="form.sample_for" :options="sampleFor" placeholder="Select one" label="name" track-by="name" @input="getLinkWithData"></multiselect>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 d-flex align-items-center">
                                                <label v-if="form.sample_for.id == 1" class="form-label d-inline-block w-100">Products</label>
                                                <label v-if="form.sample_for.id == 2" class="form-label d-inline-block w-100">Fabric</label>
                                                <label v-if="form.sample_for.id == 3" class="form-label d-inline-block w-100">Unit</label>
                                                <li class="dropdown-toggle btn btn-icon btn-primary" @click="addSampleDataRow"><em class="icon ni ni-plus"></em></li>
                                            </div>
                                            <div class="row gy-4" style="padding-left: 15px;" v-for="(sample, index) in sampleData" :key="index">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label" for="fw-contact_person_name">Name</label>
                                                        <div class="form-control-wrap">
                                                            <input v-model="sample.contact_person_name" type="text" class="form-control" id="fw-contact_person_name" name="fw-contact_person_name">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label" :for="'fw-image'+index">Photo</label>
                                                        <div class="form-control-wrap">
                                                            <div>
                                                                <input type="file" class="custom-file-input" :id="'fv-image'+index" @change="uploadImage(index, $event)">
                                                                <label class="custom-file-label" :for="'fv-image'+index">Choose photo</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label" for="fw-price">Price</label>
                                                        <div class="form-control-wrap">
                                                            <input v-model="sample.price" type="number" class="form-control" id="fw-price" name="fw-price">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="form-label" for="fw-quantity">Quantity</label>
                                                        <div class="form-control-wrap">
                                                            <input v-model="sample.quantity" type="text" class="form-control" id="fw-quantity" name="fw-quantity">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-1 d-flex align-items-center flex-row-reverse">
                                                    <li class="dropdown-toggle btn btn-icon btn-primary" @click="deleteSampleDataRow(sample)"><em class="icon ni ni-cross"></em></li>
                                                </div>
                                            </div>
                                        </div>
                                        <hr class="preview-hr">
                                        <span class="preview-title-lg overline-title">Notification Settings & Products</span>
                                        <div class="row gy-4">
                                            <div class="col-md-12">
                                                <div class="preview-block">
                                                    <ul class="custom-control-group g-3 align-center">
                                                        <li>
                                                            <div class="custom-control custom-checkbox">
                                                                <input class="custam-val" type="checkbox" id="fv-notify_client" v-model="form.notify_client" @click="showClientComment($event)">
                                                                <label class="custom-control-label" for="fv-notify_client">Notify Client</label>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="custom-control custom-checkbox">
                                                                <input class="custam-val" type="checkbox" id="fv-notify_md" v-model="form.notify_md" @click="showClientComment($event)">
                                                                <label class="custom-control-label" for="fv-notify_md">Notify MD</label>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div v-if="isClientComments == 1" class="col-md-12">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-client_comments">Comment for clients</label>
                                                    <div class="form-control-wrap">
                                                        <textarea class="form-control no-resize" id="fv-client_comments" v-model="form.client_comments"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div v-if="inwardType != 'sample'" class="col-md-12">
                                                <div class="form-group code-block">
                                                    <label class="form-label" for="fw-link_with">Link With</label>
                                                    <div>
                                                        <multiselect v-model="form.link_with" :options="inwardLinkWith" placeholder="Select one" label="name" track-by="name" @input="getLinkWithData"></multiselect>
                                                    </div>
                                                </div>
                                            </div>
                                            <div v-if="inwardType != 'sample' && enquiryComplain == 1" class="col-md-12">
                                                <div class="form-group code-block">
                                                    <label class="form-label" for="fw-enquiry_complain_for">{{ enquiryComplainForLabel }}</label>
                                                    <div>
                                                        <multiselect v-model="form.enquiry_complain_for" :options="enquiryComplainFor" placeholder="Select one" label="name" track-by="name" @input="getEnquiryComplainForData"></multiselect>
                                                    </div>
                                                </div>
                                            </div>
                                            <div v-if="inwardType != 'sample' && inwardProduct == 1" class="col-md-12">
                                                <div class="form-group code-block">
                                                    <label class="form-label" for="fw-products">Products</label>
                                                    <button type="button" class="btn btn-sm clipboard-init" data-toggle="modal" data-target="#AddFabric" title="Fabric Details"><span class="clipboard-text">Add New Fabric</span></button>
                                                    <div v-if="productWithSuppliers != ''">
                                                        <multiselect v-model="form.product_with_suppliers" :options="productWithSuppliers" placeholder="Select one" label="name" track-by="name" :multiple="true" :taggable="true" @input="getSubProduct"></multiselect>                                                    
                                                    </div>
                                                </div>
                                            </div>
                                            <div v-if="inwardType != 'sample' && inwardProduct == 1 && subProducts != ''" class="col-md-12">
                                                <div class="form-group code-block">
                                                    <label class="form-label" for="fw-products">Sub Products</label>
                                                    <div v-if="subProducts != ''">
                                                        <multiselect v-model="form.sub_products" :options="subProducts" placeholder="Select one" label="supplier_code" track-by="supplier_code" :multiple="true" :taggable="true"></multiselect>                                                    
                                                    </div>
                                                </div>
                                            </div>
                                            <div v-if="inwardType != 'sample'" class="col-md-12">
                                                <span style="color: #e85347;">{{ inwardProductError }}</span><br>
                                                <span style="color: rgb(101, 118, 255);">Subject : {{ subjectLinkWith + subjectProduct + subjectCompany + ' or ' + subjectPerson }}.</span>
                                            </div>
                                        </div>
                                        <hr v-if="(form.required_followup == 1 && inwardType != 'sample') || inwardType == 'sample'" class="preview-hr">
                                        <span v-if="(inwardType != 'sample' && form.required_followup == 1) || inwardType == 'sample'" class="preview-title-lg overline-title">Assign To</span>
                                        <div v-if="(inwardType != 'sample' && form.required_followup == 1) || inwardType == 'sample'" class="row gy-4">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-assignToDateTime">Date/Time</label>
                                                    <div class="form-control-wrap">
                                                        <input type="date" class="form-control" id="fv-assignToDateTime" v-model="form.assignToDateTime">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group code-block">
                                                    <label class="form-label" for="fw-products">Assign To</label>
                                                    <div>
                                                        <multiselect v-model="form.assign_to" :options="assignTo" placeholder="Select one" label="name" track-by="name"></multiselect>                                                    
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-instruction">Instruction</label>
                                                    <div class="form-control-wrap">
                                                        <textarea class="form-control no-resize" id="fv-instruction" v-model="form.instruction"></textarea>
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
        <AddPerson></AddPerson>
        <div class="modal fade" id="addCompany">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Company</h5>
                        <a href="#" class="close" data-dismiss="modal" aria-label="Close" @click="closeModel()">
                            <em class="icon ni ni-cross"></em>
                        </a>
                    </div>
                    <div class="modal-body">
                        <form action="#" @submit.prevent="registerCompany()">
                            <div class="preview-block">
                                <div class="row gy-4">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="fv-company-type">Company Type</label>
                                            <div>
                                                <multiselect v-model="form.company_type" :options="companyTypes" placeholder="Select one" label="name" track-by="name"></multiselect>
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="fv-name">Name</label>
                                            <div class="form-control-wrap">
                                                <input type="text" class="form-control" id="fv-name" v-model="form.name" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr class="preview-hr">
                                <span class="preview-title-lg overline-title">Owner Details</span>
                                <div class="row gy-4">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label" for="fv-owner-name">Name</label>
                                            <div class="form-control-wrap">
                                                <input type="text" class="form-control" id="fv-owner-name" v-model="form.owner_name" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label" for="fv-owner-mobile">Mobile</label>
                                            <div class="form-control-wrap">
                                                <input type="text" class="form-control" id="fv-owner-mobile" v-model="form.owner_mobile" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label" for="fv-owner-email">Email</label>
                                            <div class="form-control-wrap">
                                                <input type="text" class="form-control" id="fv-owner-email" v-model="form.owner_email" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr class="preview-hr">
                                <div class="row gy-4">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="fv-country">Country</label>
                                            <div>
                                                <multiselect v-model="form.country" :options="countries" placeholder="Select one" label="name" track-by="name"></multiselect>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="fv-city">City</label>
                                            <div>
                                                <multiselect v-model="form.city" :options="cities" placeholder="Select one" label="name" track-by="name"></multiselect>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label" for="fv-company_about">About Company</label>
                                            <div class="form-control-wrap">
                                                <textarea class="form-control" id="fv-company_about" v-model="form.about_company"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr class="preview-hr">
                                <div class="row gy-4">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <a href="#" data-dismiss="modal" aria-label="Close" class="btn btn-dim btn-secondary" @click="closeModel()">Cancel</a>
                                            <button type="submit" class="btn btn-primary">Save changes</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="AddFabric">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Fabric</h5>
                        <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                            <em class="icon ni ni-cross"></em>
                        </a>
                    </div>
                    <div class="modal-body">
                        <form action="#" @submit.prevent="registerFabrics()">
                            <div class="preview-block">
                                <div class="preview-block">
                                    <div class="row gy-4">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label" for="fv-name">Name</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" id="fv-name" v-model="FabricsData.name" placeholder="Enter Name" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label" for="fv-first-name">Main Category</label>
                                                <div class="form-control-wrap">
                                                    <multiselect v-model="FabricsData.mainCategory" :options="mainCategories" placeholder="Select one" label="name" track-by="id"></multiselect>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr class="preview-hr">
                                <div class="row gy-4">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <a href="#" data-dismiss="modal" aria-label="Close" class="btn btn-dim btn-secondary">Cancel</a>
                                            <button type="submit" class="btn btn-primary">Save changes</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Multiselect from 'vue-multiselect';
    import AddPerson from './modal/AddNewPersonModelComponent';

    export default {
        name: 'inserInward',
        props: {
            type: Number,
        },
        components: { 
            Multiselect,
            AddPerson,
        },
        data() {
            return {
                cancel_url: '/settings/agent',
                subjectLinkWith: '"link with" for ',
                subjectProduct: '"product" with ',
                subjectCompany: '"company name"',
                subjectPerson: '"person name"',
                referenceNumber: '',
                oldReference: 0,
                referenceSampleError: 0,
                isClientComments: 0,
                countries: [],
                referenceSampleData: [],
                cities: [],
                companyTypes: [],
                companies: [],
                suppliers: [],
                suppliersVal: 0,
                inwardLinkWith: [],
                mainCategories: [],
                subProducts: [],
                assignTo: [],
                companyType: '',
                inwardType: '',
                enquiryComplainForLabel: '',
                enquiryComplain: 0,
                inwardProduct: 0,
                inwardProductError: '',
                productWithSuppliers: '',
                CallBy:[
                    {
                        id: '1',
                        name: 'Own / Related',
                    },
                    {
                        id: '2',
                        name: 'Laveshbhai',
                    },
                    {
                        id: '3',
                        name: 'Narayanbhai',
                    }
                ],
                letterBy:[
                    {
                        id: '1',
                        name: 'Hand',
                    },
                    {
                        id: '2',
                        name: 'Courier',
                    },
                ],
                sampleFor:[
                    {
                        id: '1',
                        name: 'Product',
                    },
                    {
                        id: '2',
                        name: 'Fabric',
                    },
                    {
                        id: '3',
                        name: 'Unit',
                    },
                ],
                enquiryComplainFor:[
                    {
                        id: '1',
                        name: 'Product / Fabric',
                    },
                    {
                        id: '2',
                        name: 'Service',
                    },
                    {
                        id: '3',
                        name: 'Ledger',
                    }
                ],
                sampleData : [{
                    name: '',
                    image: '',
                    price: '',
                    quantity: '',
                }],
                referenceVia: [],
                mainCategories:[],
                FabricsData: new Form({
                    name:'',
                    mainCategory:'',
                    supplierId:'',
                }),
                companyData: new Form({
                    name: '',
                    company_type: '',
                    owner_name: '',
                    owner_mobile: '',
                    owner_email: '',
                    country: '',
                    city: '',
                    about_company: '',
                }),
                form: new Form({
                    id: '',
                    call_by: [
                        {
                            id: '1',
                            name: 'Own / Related',
                        }
                    ],
                    reference_via: '',
                    inward_reference: 1,
                    company: '',
                    supplier: '',
                    dateTime: '',
                    from_name: '',
                    from_number: '',
                    to_name: '',
                    receiver_number: '',
                    delivery_period: '',
                    multiple_attachment: [],
                    remark: '',
                    required_followup: 1,
                    notify_client: 0,
                    notify_md: 0,
                    client_comments: '',
                    link_with: '',
                    enquiry_complain_for: '',
                    assignToDateTime: '',
                    assign_to: '',
                    instruction: '',
                    product_with_suppliers: '',
                    sub_products: '',
                    receiver_number: '',
                    receiver_email_id: '',
                    letter_by: {
                        id: '2',
                        name: 'Courier',
                    },
                    sample_via: {
                        id: '2',
                        name: 'Courier',
                    },
                    sample_for: {
                        id: '1',
                        name: 'Product',
                    },
                    courier_name: '',
                    courier_receipt_number: '',
                    weight_of_parcel: '',
                    received_date_time: '',
                    delivery_by: '',
                    reference_sample_data: '',
                })
            }
        },
        created() {
            axios.get('/register/getReferenceSampleData')
            .then(response => {
                this.referenceSampleData = response.data;

                this.form.reference_sample_data = this.referenceSampleData[0].reference_id;
            });
            axios.get('/register/main-categories')
            .then(response => {
                this.mainCategories = response.data;
            });
            axios.get('/register/receiverDetails')
            .then(response=>{
                this.form.receiver_number = response.data.mobile;
                this.form.receiver_email = response.data.email_id;
            });
            axios.get('/databank/catalog/list-country')
            .then(response => {
                this.countries = response.data;
            });
            axios.get('/databank/catalog/list-cities')
            .then(response => {
                this.cities = response.data;
            });
            axios.get('/databank/catalog/list-state')
            .then(response => {
                this.states = response.data;
            });
            axios.get('/settings/companyType/list-data')
            .then(response => {
                var referenceData = [];
                if(this.inwardType != 'sample') {
                    this.referenceVia = response.data;
                    response.data.forEach(element => {
                        if(element.id == 2) {
                            this.form.reference_via = element;
                        }
                    });
                } else {
                    response.data.forEach(element => {
                        if(element.name != 'General') {
                            referenceData.push(element);
                        }
                        if(element.id == 2) {
                            this.form.reference_via = element;
                        }
                    });
                    this.referenceVia = referenceData;
                }
            });
            axios.get('/databank/catalog/list-companies')
            .then(response => {
                this.companies = response.data;
            });
            axios.get('/register/list-suppliers')
            .then(response => {
                this.suppliers = response.data;
            });
            axios.get('/register/list-inwardLinkWith')
            .then(response => {
                this.inwardLinkWith = response.data;
            });
            axios.get('/register/list-employees')
            .then(response => {
                this.assignTo = response.data;
            });
        },
        methods: {
            dateFormate(createdDate) {
                var mydate = new Date(createdDate);
                var month = ["January", "February", "March", "April", "May", "June",
                "July", "August", "September", "October", "November", "December"][mydate.getMonth()];
                return mydate.getDate() + '-' + month + '-' + mydate.getFullYear() + " " + mydate.getHours() + ":" + mydate.getMinutes() + ":" + mydate.getSeconds();            
            },
            getReferenceDataViaId(referenceId) {
                if (referenceId != '') {
                    if (this.inwardType == 'sample') {
                        axios.get('/register/getOldReferenceDetails/'+referenceId+'/'+this.form.sample_via.name+'/'+this.inwardType)
                        .then(response => {
                            
                        });
                    }
                }
            },
            timeFormate(createdDate) {
                var mydate = new Date(createdDate);
                var hours = mydate.getHours();
                var minutes = mydate.getMinutes();
                var ampm = hours >= 12 ? 'pm' : 'am';
                hours = hours % 12;
                hours = hours ? hours : 12; // the hour '0' should be '12'
                minutes = minutes < 10 ? '0'+minutes : minutes;
                var strTime = hours + ':' + minutes + ' ' + ampm;

                return strTime;
            },
            addSampleDataRow: function() {
                this.sampleData.push({
                    name: '',
                    image: '',
                    price: '',
                    quantity: '',
                });
            },                            
            deleteSampleDataRow: function(row) {
                this.sampleData.pop(row);
            },
            getSubProduct: function(event) {
                var productName = '';
                var productId = '';
                if(event != null) {
                    var i = 1;
                    event.forEach((index)=>{
                        var proOrFabId = index.productOrFabricId.split('-');

                        if (proOrFabId[0] == 'p') {
                            productId += proOrFabId[1] + '-';
                        }

                        if (i == event.length) {
                            productName = productName + index.name + ' by ';
                        } else {
                            productName = productName + index.name + ', ';
                        }
                        i++;
                    });
                    
                    this.subjectProduct = productName;
                    if (productId != '') {
                        axios.get('/register/getSubProducts/'+productId)
                        .then(response => {
                            this.subProducts = response.data;
                        });
                    }
                }
            },
            getLinkWithData: function(event) {
                if (event.id == 1) {
                    this.enquiryComplain = 1;
                    this.enquiryComplainForLabel = 'Enquiry For';
                    this.subjectLinkWith = 'Enquiry for ';
                } else if(event.id == 2) {
                    this.enquiryComplain = 0;
                    this.subjectLinkWith = 'Order for ';
                } else if(event.id == 3) {
                    this.enquiryComplain = 1;
                    this.enquiryComplainForLabel = 'Complain For';
                    this.subjectLinkWith = 'Complain for ';
                } else if(event.id == 6) {
                    this.suppliersVal = 1;
                    this.enquiryComplain = 0;
                    this.subjectLinkWith = 'General By ';
                    this.subjectProduct = '';
                } else {
                    this.enquiryComplain = 0;
                    this.subjectLinkWith = '"link with" for ';
                }
            },
            getEnquiryComplainForData: function(event) {
                if(event != null) {
                    if (event.id == 1) {
                        this.inwardProduct = 1;
                        if(this.form.supplier) {
                            this.inwardProductError = '';

                            axios.get('/register/getProductWithSupplier/'+this.form.supplier.id)
                            .then(response => {
                                this.productWithSuppliers = response.data;
                            });
                        } else {
                            this.inwardProduct = 0;
                            this.enquiryComplain = 0;
                            this.form.link_with = '';
                            this.form.enquiry_complain_for = '';
                            this.subjectLinkWith = '"link with" for ';
                            this.inwardProductError = 'Please Select Supplier First.';
                        }
                    } else {

                    }
                }

            },
            showClientComment: function(event) {
                if (event.target.checked == true) {
                    this.isClientComments = 1;
                } else {
                    this.isClientComments = 0;
                }
            },
            getReferenceData: function(event) {
                if(event != null) {
                    if(event.name == "General"){
                        this.suppliersVal = 1;
                        this.form.link_with = [
                            {
                                id: '6',
                                name: 'General',
                            }
                        ];
                        this.subjectLinkWith = 'General ';
                        this.subjectProduct = 'by ';
                    } else {
                        this.suppliersVal = 0;
                        this.form.link_with = '';
                        this.subjectLinkWith = '"link with" for ';
                        this.subjectProduct = '"product" with ';
                    }
                }
            },
            getSupplierFromName: function(event) {
                this.inwardProductError = '';
                if(event != null) {
                    if (this.form.reference_via.name == 'Supplier') {                        
                        this.subjectCompany = event.company_name;
                        axios.get('/register/from-name/'+event.id)
                        .then(response => {         
                            this.form.from_name = response.data.contact_person_name;
                            this.form.from_number = response.data.contact_person_mobile;
                            this.subjectPerson = response.data.contact_person_name;
                        });
                    } else {                        
                        this.subjectCompany = '"company name"';
                        this.subjectPerson = '"person name"';
                    }
                }
            },
            getFromName: function(event) {
                if(event != null) {
                    this.subjectCompany = event.company_name;
                    axios.get('/register/from-name/'+event.id)
                    .then(response => {         
                        this.form.from_name = response.data.contact_person_name;
                        this.form.from_number = response.data.contact_person_mobile;
                        this.subjectPerson = response.data.contact_person_name;
                    });
                } else {
                    this.subjectCompany = '"company name"';
                    this.subjectPerson = '"person name"';
                }
            },
            showReferenceDetails(referenceType) {
                var referenceUrl = this.inwardType + '/1/' + this.form.reference_via.id;

                if(referenceType == 0) {
                    this.oldReference = 1;
                    axios.get('/register/get-reference-details/'+referenceUrl)
                    .then(response => {
                        console.log(response.data);
                    });
                } else {
                    this.oldReference = 0;
                }
            },
            assignCompanyType(val) {
                this.companyType = val;

                $("#addCompany").modal('show');
                $('<div class="modal-backdrop fade show"></div>').appendTo(document.body);
                $('body').addClass('modal-open');
                $('body').css('overflow', 'hidden');
                $('body').css('padding-right', '17px');
                axios.get('/settings/companyType/list-data')
                .then(response => {
                    var comType = [];
                    response.data.forEach(element => {
                        if(this.companyType == '1-2') {
                            if (element.id != 3) {
                                comType.push(element);
                            }
                        } else if(this.companyType == '3') {
                            if (element.id == 3) {
                                comType.push(element);
                            }
                        } else if(this.companyType == '') {
                            comType.push(element);
                        }
                    });
                    this.companyTypes = comType;          
                });
            },
            closeModel() {
                $('#addCompany').modal('hide');
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open');
                $('body').removeAttr('style');
            },
            registerFabrics() {
                this.FabricsData.supplierId = this.form.supplier;
                console.log(this.FabricsData);
                this.FabricsData.post('/register/inward/'+this.inwardType+'/add-fabrics-details')
                    .then(( response ) => {
                        window.location.href = '/register/inward/'+this.inwardType;
                })
            },
            registerCompany () {
                this.companyData.post('/databank/catalog/create-company')
                    .then(( response ) => {
                        $('#addCompany').modal('hide');
                        $('.modal-backdrop').remove();
                        $('body').removeClass('modal-open');
                        $('body').remove('style');
                })
            },
            register () {
                this.form.post('/settings/agent/create')
                    .then(( response ) => {
                        window.location.href = '/settings/agent';
                })
            },
        },
        mounted() {
            if(this.type == 1) {
                this.inwardType = 'call';
            } else if(this.type == 2) {
                this.inwardType = 'message';
            } else if(this.type == 3) {
                this.inwardType = 'whatsapp';
            } else if(this.type == 4) {
                this.inwardType = 'letter';
            } else if(this.type == 5) {
                this.inwardType = 'sample';
            } else if(this.type == 6) {
                this.inwardType = 'email';
            }

            if(this.inwardType != 'sample') {
                this.referenceVia.forEach(element => {
                    if(element.id == '2') {
                        this.form.reference_via = element;
                    }
                });
            }

            this.form.dateTime = new Date().toLocaleDateString('en-CA');
            this.form.assignToDateTime = new Date().toLocaleDateString('en-CA');
        },
    };
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<style>
    .invalid {
        color: #e85347;
        font-size: 11px;
        font-style: italic;
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
</style>