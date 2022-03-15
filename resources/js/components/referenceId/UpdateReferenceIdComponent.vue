<template>
<div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">Edit Reference</h3>
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
                                    <div class="preview-block">
                                        <span class="preview-title-lg overline-title">Reference Details</span>
                                    <input type="hidden" id="fv-id" v-model="form.id">
                                        <div v-if="form.Reference_via === 'Call' || form.Reference_via === 'Message' || form.Reference_via === 'Whatsapp'">
                                            <div class="row gy-4">
                                            <div class="col-md-4">
                                                <div class="preview-block">
                                                    <label class="form-label">Inward/Outward</label>
                                                    <ul class="custom-control-group g-3 align-center">
                                                        <li>
                                                            <div class="custom-control custom-radio">
                                                                <input v-model="form.inward_outward" type="radio" class="custom-control-input"  id="fv-reference_inward" value="1" >
                                                                <label class="custom-control-label" for="fv-reference_inward">Inward</label>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="custom-control custom-radio">
                                                                <input v-model="form.inward_outward" type="radio" class="custom-control-input"  id="fv-reference_outward" value="0" >
                                                                <label class="custom-control-label" for="fv-reference_outward">Outward</label>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                            <div class="row gy-4">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-first-name">Reference Via</label>
                                                    <div>
                                                        <select v-model="form.Reference_via"  class="form-control" id="fv-reference-via">
                                                            <option>Select Reference Via</option>
                                                            <option value="Call">Call</option>
                                                            <option value="Message">Message</option>
                                                            <option value="Whatsapp">Whatsapp</option>
                                                            <option value="Email">Email</option>
                                                            <option value="Courier">Courier</option>
                                                            <option value="Hand">Hand</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-first-name">Date/Time</label>
                                                    <div class="form-control-wrap">
                                                        <input type="date" class="form-control" id="fv-Reference-date" v-model="form.Date_Time">
                                                    </div>
                                                </div>
                                            </div>
                                                <div class="col-md-4">
                                                    <div class="form-group code-block">
                                                        <label class="form-label" for="fv-company">Company</label>
                                                        <div>
                                                            <multiselect v-model="form.companyName" :options="company" placeholder="Select one" label="company_name" track-by="id"></multiselect>
                                                        </div>
                                                    </div>
                                                </div>
                                            <!-- <div class="col-md-4">
                                                            <input type="checkbox" id="checkbox" v-model="form.markssample" />
                                                            <label for="checkbox">Mark As Sample</label>
                                            </div> -->
                                        </div>
                                            <div class="row gy-4">
                                                <div class="col-md-4">
                                                    <div class="form-group code-block">
                                                        <label v-if="form.inward_or_outward == '1'" class="form-label" for="fv-from-name">From Name</label>
                                                        <label v-else class="form-label" for="fv-from-name">To Name</label>
                                                            <div class="form-control-wrap">
                                                                <input type="text" class="form-control" id="fv-from_name" v-model="form.from_name" placeholder="Enter From Name">
                                                            </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label" for="fv-from-number">From Number</label>
                                                            <div class="form-control-wrap">
                                                                <input type="text" class="form-control" id="fv-from_number" v-model="form.from_number" placeholder="Enter From Number">
                                                            </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label v-if="form.inward_or_outward == '1'" class="form-label" for="fv-receiver-number">Receiver Number</label>
                                                        <label v-else class="form-label" for="fv-receiver-number">From Number</label>
                                                            <div class="form-control-wrap">
                                                                <input type="text" class="form-control" id="fv-receiver_number" v-model="form.receiver_number" placeholder="Enter Receiver Number">
                                                            </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div v-else-if="form.Reference_via === 'Email'">
                                            <div class="row gy-4">
                                            <div class="col-md-4">
                                                <div class="preview-block">
                                                    <label class="form-label">Inward/Outward</label>
                                                    <ul class="custom-control-group g-3 align-center" v-if="form.inward_or_outward === '1'">
                                                        <li>
                                                            <div class="custom-control custom-radio">
                                                                <input v-model="form.inward_outward" type="radio" class="custom-control-input"  id="fv-reference_inward" value="1" disabled>
                                                                <label class="custom-control-label" for="fv-reference_inward">Inward</label>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="custom-control custom-radio">
                                                                <input v-model="form.inward_outward" type="radio" class="custom-control-input"  id="fv-reference_outward" value="0" disabled>
                                                                <label class="custom-control-label" for="fv-reference_outward">Outward</label>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                    <ul class="custom-control-group g-3 align-center" v-else>
                                                        <li>
                                                            <div class="custom-control custom-radio">
                                                                <input v-model="form.inward_outward" type="radio" class="custom-control-input"  id="fv-reference_inward" value="1" disabled>
                                                                <label class="custom-control-label" for="fv-reference_inward">Inward</label>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="custom-control custom-radio">
                                                                <input v-model="form.inward_outward" type="radio" class="custom-control-input"  id="fv-reference_outward" value="0" disabled>
                                                                <label class="custom-control-label" for="fv-reference_outward">Outward</label>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row gy-4">
                                        <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-first-name">Reference Via</label>
                                                    <div>
                                                        <select v-model="form.Reference_via"  class="form-control" id="fv-reference-via" disabled>
                                                            <option>Select Reference Via</option>
                                                            <option value="Call">Call</option>
                                                            <option value="Message">Message</option>
                                                            <option value="Whatsapp">Whatsapp</option>
                                                            <option value="Email">Email</option>
                                                            <option value="Courier">Courier</option>
                                                            <option value="Hand">Hand</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-first-name">Date/Time</label>
                                                    <div class="form-control-wrap">
                                                        <input type="date" class="form-control" id="fv-Reference-date" v-model="form.Date_Time" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                                <div class="col-md-4">
                                                    <div class="form-group code-block">
                                                        <label class="form-label" for="fv-company">Company</label>
                                                        <div>
                                                            <multiselect v-model="form.companyName" :options="company" placeholder="Select one" label="company_name" track-by="id"></multiselect>
                                                        </div>
                                                    </div>
                                                </div>
                                        </div>
                                            <div class="row gy-4">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label v-if="form.inward_or_outward == '1'" class="form-label" for="fv-from-name">From Name</label>
                                                        <label v-else class="form-label" for="fv-from-name-email">To Name</label>
                                                            <div class="form-control-wrap">
                                                                <input type="text" class="form-control" id="fv-from_name_email" v-model="form.from_name" placeholder="Enter From Name">
                                                            </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label" for="fv-from-email">From Email Id</label>
                                                            <div class="form-control-wrap">
                                                                <input type="text" class="form-control" id="fv-from_email" v-model="form.from_email" placeholder="Enter From email">
                                                            </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label" for="fv-receiver-email">Receiver Email Id</label>
                                                            <div class="form-control-wrap">
                                                                <input type="text" class="form-control" id="fv-receiver_email" v-model="form.receiver_email" placeholder="Enter Receiver email">
                                                            </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div v-else-if="form.Reference_via === 'Hand'">
                                            <div class="row gy-4">
                                            <div class="col-md-4">
                                                <div class="preview-block">
                                                    <label class="form-label">Inward/Outward</label>
                                                    <ul class="custom-control-group g-3 align-center">
                                                        <li>
                                                            <div class="custom-control custom-radio">
                                                                <input v-model="form.inward_outward" type="radio" class="custom-control-input"  id="fv-reference_inward" value="1" disabled>
                                                                <label class="custom-control-label" for="fv-reference_inward">Inward</label>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="custom-control custom-radio">
                                                                <input v-model="form.inward_outward" type="radio" class="custom-control-input"  id="fv-reference_outward" value="0" disabled>
                                                                <label class="custom-control-label" for="fv-reference_outward">Outward</label>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row gy-4">
                                        <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-first-name">Reference Via</label>
                                                    <div>
                                                        <select v-model="form.Reference_via"  class="form-control" id="fv-reference-via" disabled>
                                                            <option>Select Reference Via</option>
                                                            <option value="Call">Call</option>
                                                            <option value="Message">Message</option>
                                                            <option value="Whatsapp">Whatsapp</option>
                                                            <option value="Email">Email</option>
                                                            <option value="Courier">Courier</option>
                                                            <option value="Hand">Hand</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-first-name">Date/Time</label>
                                                    <div class="form-control-wrap">
                                                        <input type="date" class="form-control" id="fv-Reference-date" v-model="form.Date_Time" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                                <div class="col-md-4">
                                                    <div class="form-group code-block">
                                                        <label class="form-label" for="fv-company">Company</label>
                                                        <div>
                                                            <multiselect v-model="form.companyName" :options="company" placeholder="Select one" label="company_name" track-by="id"></multiselect>
                                                        </div>
                                                    </div>
                                                </div>
                                            <div class="col-md-4">
                                                            <input type="checkbox" id="checkbox" v-model="form.markssample" />
                                                            <label for="checkbox">Mark As Sample</label>
                                            </div>
                                        </div>
                                            <div class="row gy-4">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label" for="fv-parcel-weight-hand">Weight of Parcel</label>
                                                            <div class="form-control-wrap">
                                                                <input type="text" class="form-control" id="fv-parcel_weight" v-model="form.parcel_weight" placeholder="Enter Parcel Weight" disabled>
                                                            </div>
                                                    </div>
                                                </div>
                                                <div v-if="form.inward_or_outward == '1'" class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label" for="fv-received-date-time-hand">Received Date Time</label>
                                                            <div class="form-control-wrap">
                                                                <input type="date" class="form-control" id="fv-received_date_time" v-model="form.received_date_time" placeholder="Enter Received Date and Time" disabled>
                                                            </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label v-if="form.inward_or_outward == '1'" class="form-label" for="fv-delivery-by-hand">Delivery By</label>
                                                        <label v-else class="form-label" for="fv-delivered-by-hand">Delivered By</label>
                                                            <div class="form-control-wrap">
                                                                <input type="text" class="form-control" id="fv-delivery_by_hand" v-model="form.delivery_by" placeholder="Enter Person Name" disabled>
                                                            </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div v-else-if="form.Reference_via === 'Courier'">
                                            <div class="row gy-4">
                                            <div class="col-md-4">
                                                <div class="preview-block">
                                                    <label class="form-label">Inward/Outward</label>
                                                    <ul class="custom-control-group g-3 align-center" v-if="form.inward_or_outward === '1'">
                                                        <li>
                                                            <div class="custom-control custom-radio">
                                                                <input v-model="form.inward_outward" type="radio" class="custom-control-input"  id="fv-reference_inward" value="1">
                                                                <label class="custom-control-label" for="fv-reference_inward">Inward</label>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="custom-control custom-radio">
                                                                <input v-model="form.inward_outward" type="radio" class="custom-control-input"  id="fv-reference_outward" value="0">
                                                                <label class="custom-control-label" for="fv-reference_outward">Outward</label>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                    <ul class="custom-control-group g-3 align-center" v-else>
                                                        <li>
                                                            <div class="custom-control custom-radio">
                                                                <input v-model="form.inward_outward" type="radio" class="custom-control-input"  id="fv-reference_inward" value="1" disabled>
                                                                <label class="custom-control-label" for="fv-reference_inward">Inward</label>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="custom-control custom-radio">
                                                                <input v-model="form.inward_outward" type="radio" class="custom-control-input"  id="fv-reference_outward" value="0" disabled>
                                                                <label class="custom-control-label" for="fv-reference_outward">Outward</label>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row gy-4">
                                        <div class="col-md-4" v-if="form.inward_or_outward === '1'">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-first-name">Reference Via</label>
                                                    <div>
                                                        <select v-model="form.Reference_via"  class="form-control" id="fv-reference-via">
                                                            <option>Select Reference Via</option>
                                                            <option value="Call">Call</option>
                                                            <option value="Message">Message</option>
                                                            <option value="Whatsapp">Whatsapp</option>
                                                            <option value="Email">Email</option>
                                                            <option value="Courier">Courier</option>
                                                            <option value="Hand">Hand</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4" v-else>
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-first-name">Reference Via</label>
                                                    <div>
                                                        <select v-model="form.Reference_via"  class="form-control" id="fv-reference-via" disabled>
                                                            <option>Select Reference Via</option>
                                                            <option value="Call">Call</option>
                                                            <option value="Message">Message</option>
                                                            <option value="Whatsapp">Whatsapp</option>
                                                            <option value="Email">Email</option>
                                                            <option value="Courier">Courier</option>
                                                            <option value="Hand">Hand</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4" v-if="form.inward_or_outward === '1'">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-first-name">Date/Time</label>
                                                    <div class="form-control-wrap">
                                                        <input type="date" class="form-control" id="fv-Reference-date" v-model="form.Date_Time">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4" v-else>
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-first-name">Date/Time</label>
                                                    <div class="form-control-wrap">
                                                        <input type="date" class="form-control" id="fv-Reference-date" v-model="form.Date_Time" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                                <div class="col-md-4">
                                                    <div class="form-group code-block">
                                                        <label class="form-label" for="fv-company">Company</label>
                                                        <div>
                                                            <multiselect v-model="form.companyName" :options="company" placeholder="Select one" label="company_name" track-by="id"></multiselect>
                                                        </div>
                                                    </div>
                                                </div>
                                            <div class="col-md-4">
                                                            <input type="checkbox" id="checkbox" v-model="form.markssample" />
                                                            <label for="checkbox">Mark As Sample</label>
                                            </div>
                                        </div>
                                            <div class="row gy-4" v-if="form.inward_or_outward === '1'">
                                                <div class="col-md-4">
                                                    <div>
                                                        <label class="form-label" for="fv-Courier-name">Courier Name</label>
                                                        <div>
                                                            <multiselect v-model="form.courier_company" :options="courier" placeholder="Select one" label="name" track-by="name"></multiselect>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label" for="fv-courier-recepit-no">Courier Recepit No</label>
                                                            <div class="form-control-wrap">
                                                                <input type="text" class="form-control" id="fv-courier_recepit_no" v-model="form.courier_recepit_no" placeholder="Enter From Courier Receipt No">
                                                            </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label" for="fv-parcel-weight-courier">Weight of Parcel</label>
                                                            <div class="form-control-wrap">
                                                                <input type="text" class="form-control" id="fv-parcel_weight" v-model="form.parcel_weight" placeholder="Enter Parcel Weight">
                                                            </div>
                                                    </div>
                                                </div>
                                                <div v-if="form.inward_or_outward == '1'" class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label" for="fv-received-date-time">Received Date Time</label>
                                                            <div class="form-control-wrap">
                                                                <input type="date" class="form-control" id="fv-received_date_time" v-model="form.received_date_time" placeholder="Enter Received Date and Time">
                                                            </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label v-if="form.inward_or_outward == '1'" class="form-label" for="fv-delivery-by-courier">Delivery By</label>
                                                        <label v-else class="form-label" for="fv-delivered-by">Delivered By</label>
                                                            <div class="form-control-wrap">
                                                                <input type="text" class="form-control" id="fv-delivery_by_courier" v-model="form.delivery_by" placeholder="Enter Person Name">
                                                            </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row gy-4" v-else>
                                                <div class="col-md-4">
                                                    <div>
                                                        <label class="form-label" for="fv-Courier-name">Courier Name</label>
                                                        <div>
                                                            <multiselect v-model="form.courier_company" :options="courier" placeholder="Select one" label="name" track-by="name" disabled></multiselect>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label" for="fv-courier-recepit-no">Courier Recepit No</label>
                                                            <div class="form-control-wrap">
                                                                <input type="text" class="form-control" id="fv-courier_recepit_no" v-model="form.courier_recepit_no" placeholder="Enter From Courier Receipt No" disabled>
                                                            </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label" for="fv-parcel-weight-courier">Weight of Parcel</label>
                                                            <div class="form-control-wrap">
                                                                <input type="text" class="form-control" id="fv-parcel_weight" v-model="form.parcel_weight" placeholder="Enter Parcel Weight" disabled>
                                                            </div>
                                                    </div>
                                                </div>
                                                <div v-if="form.inward_or_outward == '1'" class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label" for="fv-received-date-time">Received Date Time</label>
                                                            <div class="form-control-wrap">
                                                                <input type="date" class="form-control" id="fv-received_date_time" v-model="form.received_date_time" placeholder="Enter Received Date and Time" disabled>
                                                            </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label v-if="form.inward_or_outward == '1'" class="form-label" for="fv-delivery-by-courier">Delivery By</label>
                                                        <label v-else class="form-label" for="fv-delivered-by">Delivered By</label>
                                                            <div class="form-control-wrap">
                                                                <input type="text" class="form-control" id="fv-delivery_by_courier" v-model="form.delivery_by" placeholder="Enter Person Name" disabled>
                                                            </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <hr class="preview-hr">
                                        <div class="row gy-4">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <a v-bind:href="cancel_url" class="btn btn-dim btn-secondary">Cancel</a>
                                                    <button type="submit" id="save_changes" class="btn btn-primary">Save changes</button>
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
var references = [];
export default {
        name: 'updateCompany',
        components: {
            Multiselect,
        },
        props: {
            id: Number,
        },
        data() {
            return {
                Reference_via:[],
                // company:[],
                // courier:[],
                // referenceID: [],
                user: [],
                companies: [],
                cancel_url: '/reference',
            referenceSelected: true,
            InwardOrOutward: true,
                form: new Form({
                    id: '',
                    inward_outward: '',
                    Reference_via: '',
                    Date_Time:'',
                    companyName:'',
                    markssample:'',
                    from_name: '',
                    from_number:'',
                    receiver_number:'',
                    from_email:'',
                    receiver_email:'',
                    parcel_weight:'',
                    received_date_time:'',
                    delivery_by:'',
                    courier_company:'',
                    courier_recepit_no:'',
                })
            }
        },
        created(){
        axios.get('/reference/companysearch')
            .then(response => {
                this.company = response.data;
            });
        axios.get('/settings/transport-details/list')
            .then(response => {
                this.courier   = response.data;
            });
    },
        methods: {
            getProfilePic(name){
                return '/upload/company/multipleAddressProfilePic/' + name;
            },
            register() {
                    this.form.post('/reference/update')
                        .then(( response ) => {
                            window.location.href = '/reference';
                    });
                }
        },
        mounted() {
            // console.log(this.id);
            axios.get(`/reference/fetch-reference/${this.id}`)
            .then(response => {
                references = response.data;

                    this.form.id = references.Reference.reference_id;
                    this.form.inward_outward = references.Reference.inward_or_outward;
                    this.form.Reference_via = references.Reference.type_of_inward;
                    this.form.Date_Time = references.Reference.selection_date;
                    this.form.companyName = references.Reference.company_id;
                    this.form.markssample = references.Reference.mark_as_sample;
                    this.form.from_number = references.Reference.from_number;
                    this.form.receiver_number = references.Reference.receiver_number;
                    this.form.from_email = references.Reference.from_email_id;
                    this.form.receiver_email = references.Reference.receiver_email_id;
                    this.form.parcel_weight = references.Reference.weight_of_parcel;
                    this.form.received_date_time = references.Reference.courier_received_time;
                    this.form.delivery_by = references.Reference.delivery_by;
                    this.form.courier_company = references.Reference.courier_name;
                    this.form.courier_recepit_no = references.Reference.courier_receipt_no;
                    this.form.from_name = references.Reference.from_name;
            });
            console.log("This is about component");
        },
    };
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<style>
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
