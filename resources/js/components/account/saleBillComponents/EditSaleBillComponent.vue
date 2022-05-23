<template>
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">Update Sale Bill</h3>
                            </div><!-- .nk-block-head-content -->
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    <div class="nk-block">
                        <div class="card card-bordered">
                            <div class="card-inner">
                                <form action="#" class="form-validate" @submit.prevent="submitForm()">
                                    <div class="preview-block">
                                        <div id="top-reference-change-section">
                                            <h5>This Sale Bill's Current Reference ID is {{ reference_id }}</h5>
                                            <hr>
                                            <div class="row gy-4">
                                                <div class="col-md-3">
                                                    <label class="form-label">Do You Want to Change?</label>
                                                </div>
                                                <div class="col-md-4">
                                                    <ul class="custom-control-group g-3 align-center" >
                                                        <li class="w-25">
                                                            <div class="custom-control custom-radio">
                                                                <input v-model="change_reference" type="radio" class="custom-control-input" id="fv-change_reference_yes" value="1" @click="change_reference_new = true" @change="showAllReferenceDetails">
                                                                <label class="custom-control-label" for="fv-change_reference_yes">Yes</label>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="custom-control custom-radio">
                                                                <input v-model="change_reference" type="radio" class="custom-control-input" id="fv-change_reference_no" value="0" @click="change_reference_new = false" @change="showAllReferenceDetails">
                                                                <label class="custom-control-label" for="fv-change_reference_no">No</label>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="hidden" id="show-all-reference-details">
                                            <div class="row gy-4" >
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label" for="fw-reference_via">Reference Via</label>
                                                        <div class="form-control-wrap">
                                                            <multiselect v-model="reference_via" :options="reference_options" placeholder="Select One" label="name" track-by="name" id="reference_via" @select="showHideName($event), refUpdateForSaleBill($event)"></multiselect>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="preview-block">
                                                        <label class="form-label">Reference</label>
                                                        <ul class="custom-control-group g-3 align-center" id="validate-reference-div">
                                                            <li class="w-25">
                                                                <div class="custom-control custom-radio">
                                                                    <input v-model="new_old_sale_bill" type="radio" class="custom-control-input" id="fv-reference_new" value="1" @click="reference_new = true" @change="resetSupplier($event), refUpdateForSaleBill($event)">
                                                                    <label class="custom-control-label" for="fv-reference_new">NEW</label>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="custom-control custom-radio">
                                                                    <input v-model="new_old_sale_bill" type="radio" class="custom-control-input"  id="fv-reference_old" value="0" @click="reference_new = false" @change="getOldReferences($event), refUpdateForSaleBill($event)">
                                                                    <label class="custom-control-label" for="fv-reference_old">OLD</label>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                        <div id="error-validate-reference-div" class="mt-2 text-danger"></div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 text-center hidden" id="show-references" v-html="old_reference_data"></div>
                                                <div id="new_reference_details_div" class="col-md-12">
                                                    <div class="row">
                                                        <div class="col-md-6" >
                                                            <div class="form-group">
                                                                <label class="form-label" for="from_name">From Name</label>
                                                                <div class="form-control-wrap">
                                                                    <input type="text" v-model="from_name" id="from_name" class="form-control" >
                                                                    <div v-if="v$.from_name.$error" class="invalid mt-1">Enter Name</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="hidden" id="from_email_section">
                                                                <div class="form-group">
                                                                    <label class="form-label" for="from_email">From Email ID</label>
                                                                    <div class="form-control-wrap">
                                                                        <input type="email" v-model="from_email" id="from_email" class="form-control">
                                                                        <div v-if="v$.from_email.$error" class="invalid mt-1">Enter Valid Email Address</div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="hidden" id="delivery_by_section">
                                                                <div class="form-group">
                                                                    <label class="form-label" for="delivery_by">Delivery By</label>
                                                                    <div class="form-control-wrap">
                                                                        <input type="text" v-model="delivery_by" id="delivery_by" class="form-control">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="pt-5 mt-5"></div>
                                    <div id="allhiddenfield_div"></div>
                                    <div class="preview-block">
                                        <h5 class="">Update Sale Bill Details</h5>
                                        <hr>
                                        <div class="row gy-4">
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
                                                        <multiselect v-model="product_category" :options="product_category_options" placeholder="Select One" label="name" track-by="id" id="product_category" @select="getProductSubCategory"></multiselect>
                                                        <div v-if="v$.product_category.$error" class="invalid mt-1">Select Product Category</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="reference_inward">Inward</label>
                                                    <div class="form-control-wrap">
                                                        <multiselect v-model="reference_inward" :options="reference_inward_options" placeholder="Select One" label="name" track-by="id" id="reference_inward" @change="getInwardCustomers" disabled></multiselect>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="customer">Customer</label>
                                                    <div class="form-control-wrap">
                                                        <multiselect v-model="customer" :options="customer_options" placeholder="Select One" label="name" track-by="id" id="customer" @select="getCustomerAddress" :disabled="isCustomerDisabled"></multiselect>
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
                                                    <button v-if="showUpdateSupplier" type="button" id="update_supplier" class="btn btn-primary float-right clipboard-init badge" title="Update Supplier" @click="updateSupplier" ><span class="clipboard-text">Update Supplier</span></button>
                                                    <div class="form-control-wrap">
                                                        <multiselect v-model="supplier" :options="supplier_options" placeholder="Select One" label="name" track-by="id" id="supplier" @close="getProductSubCategory(), checkSupplierInvoiceNo()" :disabled="isSupplierDisabled"></multiselect>
                                                        <div v-if="v$.supplier.$error" class="invalid mt-1">Select Supplier</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="agent">Agent</label>
                                                    <div class="form-control-wrap">
                                                        <multiselect v-model="agent" :options="agent_options" placeholder="Select One" label="name" track-by="id" id="agent" ></multiselect>
                                                        <div v-if="v$.agent.$error" class="invalid mt-1">Select Agent</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4" >
                                                <div class="form-group">
                                                    <label class="form-label" for="sale_bill_id">Sr. No.</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" v-model="sale_bill_id" id="sale_bill_id" class="form-control" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4" >
                                                <div class="form-group">
                                                    <label class="form-label" for="supplier_invoice_no">Supplier Invoice No.</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" v-model="supplier_invoice_no" id="supplier_invoice_no" class="form-control" @change="checkSupplierInvoiceNo">
                                                        <div v-if="v$.supplier_invoice_no.$error" class="invalid mt-1">Enter Supplier Invoice Number</div>
                                                        <div id="check-supplier-no-div" class="mt-2"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4" >
                                                <div class="form-group">
                                                    <label class="form-label" for="bill_date">Bill Date</label>
                                                    <div class="form-control-wrap">
                                                        <input type="date" v-model="bill_date" id="bill_date" class="form-control" @focus="showDatePicker()">
                                                        <div v-if="v$.bill_date.$error" class="invalid mt-1">Select Bill Date</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row col-md-4">
                                                <div class="col-md-9" >
                                                    <div class="form-group">
                                                        <label class="form-label" for="extra_attachment">Extra Attachment</label>
                                                        <div class="form-control-wrap">
                                                            <input type="file" @change="uploadAttachment" id="extra_attachment" class="form-control" accept="text/plain,image/png,image/jpeg,application/msword,application/pdf,audio/ogg,audio/mpeg">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3" v-if="exist_attachment">
                                                    <a :href="'/upload/sale_bill/'+exist_attachment" target="_blank">
                                                        <img height="65" width="50" id="preview-img" src="" style="opacity: 0.5; padding-top: 5px;">
                                                    </a>
                                                    <span class="remove-attachment" @click="removeAttachment()"><i class="ni ni-cross"></i></span>
                                                </div>
                                            </div>

                                        </div>
                                        <hr class="mt-5">
                                        <div id="product_sub_category_section_full" class="hidden">
                                            <span class="preview-title-lg overline-title">Product Sub Category</span>
                                            <div class="hidden" id="product_sub_category_text">
                                                <label class="form-label">Please Select Product Main Category And Supplier Field.</label>
                                            </div>
                                            <div class="form-group hidden" id="product_sub_category_section">
                                                <div class="form-control-wrap">
                                                    <multiselect v-model="product_sub_category" :options="product_sub_category_options" :multiple="true" placeholder="Select One" label="name" track-by="id" id="product_sub_category" @close="getProducts($event)" :disabled="isProductSubCategoryDisabled"></multiselect>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div id="item_details_div" class="hidden">
                                            <div class="form-group row">
                                                <label class="col-sm-10"><b>Item Details</b></label>
                                                <div class="col-sm-2 text-right">
                                                    <button type="button" class="btn btn-sm btn-primary hidden" data-toggle="modal" data-target="#myModalFabric" id="add_new_fabric" > <em class="icon ni ni-plus"></em> &nbsp;Add New Fabric</button>
                                                    <div class="modal fade" id="myModalFabric" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h4 class="modal-title" id="myModalLabel">Fabric Insert</h4>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="row">
                                                                        <label class="col-sm-2">Name</label>
                                                                        <div class="col-sm-10">
                                                                            <input type="text" v-model="add_fabric_name" id="add_fabric_name" class="form-control">
                                                                        </div>
                                                                    </div>
                                                                    <br>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-default" data-dismiss="modal" id="closeFabricModalBtn">Close</button>
                                                                    <button type="button" id="save_modal_data_fabric" class="btn btn-primary" @click="addUpdateFabricName" >Submit</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>&nbsp;
                                                    <button class="btn btn-sm btn-primary hidden" type="button" id="add_product_details" @click="addProductDetailsRow"><em class="icon ni ni-plus"></em></button>
                                                    <button class="btn btn-sm btn-primary hidden" type="button" id="add_fabric_details" @click="addFabricDetailsRow"><em class="icon ni ni-plus"></em></button>
                                                </div>
                                            </div>

                                            <div class="dynamic_items hidden">
                                                <div class="form-group row gy-4" v-for="(k, i) in productDetails" :key="i">
                                                    <div class="col-sm-2">
                                                        <label for="">Product</label>
                                                        <multiselect v-model="k.product_name" :options="product_options" placeholder="Select One" label="name" track-by="id" @close="getSubProducts(i, $event)"></multiselect>
                                                        <input type="text" class="form-control mt-2" v-model="k.hsn_code" placeholder="HSN Code">
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <label for="">Sub Product</label>
                                                        <multiselect v-model="k.sub_product_name" :options="sub_product_options[i]" placeholder="Select One" label="name" track-by="id" @close="getSubProductRate(i)"></multiselect>
                                                    </div>
                                                    <div class="col-sm-1">
                                                        <label for="">Pieces</label>
                                                        <input type="text" class="form-control" v-model="k.pieces" @change="calculateTotalProducts(i)">
                                                    </div>
                                                    <div class="col-sm-1">
                                                        <label for="">Rate</label>
                                                        <input type="text" class="form-control" v-model="k.rate" @change="calculateTotalProducts(i)">
                                                    </div>
                                                    <div class="col-sm-1">
                                                        <label for="">Discount</label>
                                                        <input type="text" class="form-control" v-model="k.discount" @change="calculateTotalProducts(i)">
                                                        <input type="text" class="form-control" v-model="k.discount_amount" disabled="true">
                                                    </div>
                                                    <div class="col-sm-1">
                                                        <label for="">CGST</label>
                                                        <input type="text" class="form-control" v-model="k.cgst" @change="calculateTotalProducts(i)">
                                                        <input type="text" class="form-control" v-model="k.cgst_amount" disabled="true">
                                                    </div>
                                                    <div class="col-sm-1">
                                                        <label for="">SGST</label>
                                                        <input type="text" class="form-control" v-model="k.sgst" @change="calculateTotalProducts(i)">
                                                        <input type="text" class="form-control" v-model="k.sgst_amount" disabled="true">
                                                    </div>
                                                    <div class="col-sm-1">
                                                        <label for="">IGST</label>
                                                        <input type="text" class="form-control" v-model="k.igst" @change="calculateTotalProducts(i)">
                                                        <input type="text" class="form-control" v-model="k.igst_amount" disabled="true">
                                                    </div>
                                                    <div class="col-sm-1">
                                                        <label for="">Amount</label>
                                                        <input type="text" class="form-control" v-model="k.amount" disabled="true">
                                                    </div>
                                                    <div class="col-sm-1 text-right">
                                                        <button class="btn btn-sm btn-primary" type="button" id="" @click="deleteProductDetailsRow"><em class="icon ni ni-cross"></em></button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="dynamic_items_fabrics hidden">
                                                <div class="form-group row gy-4" v-for="(k, i) in fabricDetails" :key="i">
                                                    <div class="col-sm-2">
                                                        <label for="">Fabric</label>
                                                        <multiselect v-model="k.fabric_name" :options="fabric_options" placeholder="Select One" label="name" track-by="id" ></multiselect>
                                                        <input type="text" class="form-control mt-2" v-model="k.hsn_code" placeholder="HSN Code">
                                                    </div>
                                                    <div class="col-sm-1">
                                                        <label for="">Pieces/Meters</label>
                                                        <multiselect v-model="k.pieces_or_meters" :options="pieces_meters_options" placeholder="Select One" label="name" track-by="id" ></multiselect>
                                                    </div>
                                                    <div class="col-sm-1">
                                                        <label for="">Pieces</label>
                                                        <input type="text" class="form-control" v-model="k.pieces" @change="calculateTotalFabrics(i)">
                                                    </div>
                                                    <div class="col-sm-1">
                                                        <label for="">Meters</label>
                                                        <input type="text" class="form-control" v-model="k.meters" @change="calculateTotalFabrics(i)">
                                                    </div>
                                                    <div class="col-sm-1">
                                                        <label for="">Rate</label>
                                                        <input type="text" class="form-control" v-model="k.rate" @change="calculateTotalFabrics(i)">
                                                    </div>
                                                    <div class="col-sm-1">
                                                        <label for="">Discount</label>
                                                        <input type="text" class="form-control" v-model="k.discount" @change="calculateTotalFabrics(i)">
                                                        <input type="text" class="form-control" v-model="k.discount_amount" disabled="true">
                                                    </div>
                                                    <div class="col-sm-1">
                                                        <label for="">CGST</label>
                                                        <input type="text" class="form-control" v-model="k.cgst" @change="calculateTotalFabrics(i)">
                                                        <input type="text" class="form-control" v-model="k.cgst_amount" disabled="true">
                                                    </div>
                                                    <div class="col-sm-1">
                                                        <label for="">SGST</label>
                                                        <input type="text" class="form-control" v-model="k.sgst" @change="calculateTotalFabrics(i)">
                                                        <input type="text" class="form-control" v-model="k.sgst_amount" disabled="true">
                                                    </div>
                                                    <div class="col-sm-1">
                                                        <label for="">IGST</label>
                                                        <input type="text" class="form-control" v-model="k.igst" @change="calculateTotalFabrics(i)">
                                                        <input type="text" class="form-control" v-model="k.igst_amount" disabled="true">
                                                    </div>
                                                    <div class="col-sm-1">
                                                        <label for="">Amount</label>
                                                        <input type="text" class="form-control" v-model="k.amount" disabled="true">
                                                    </div>
                                                    <div class="col-sm-1 text-right">
                                                        <button class="btn btn-sm btn-primary" type="button" id="" @click="deleteFabricDetailsRow"><em class="icon ni ni-cross"></em></button>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                            <div id="itemTotal_Div">
                                                <div class="line line-dashed b-b line-lg pull-in"></div>
                                                <div class="form-group row">
                                                    <label class="col-sm-10"><b>Item Total</b></label>
                                                    <div class="col-sm-2 text-right"> </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-sm-2" id="total_peices_div">
                                                        <label class="control-label">Total Pieces: </label>
                                                        <strong id="total_peices">&nbsp;{{ totals.pieces }}</strong>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <label class="control-label">Discount: </label>
                                                        <strong id="total_discount">&nbsp;{{ totals.discount }}</strong>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <label class="control-label">CGST: </label>
                                                        <strong id="total_cgst">&nbsp;{{ totals.cgst }}</strong>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <label class="control-label">SGST: </label>
                                                        <strong id="total_sgst">&nbsp;{{ totals.sgst }}</strong>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <label class="control-label">IGST: </label>
                                                        <strong id="total_igst">&nbsp;{{ totals.igst }}</strong>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <label class="control-label">Amount: </label>
                                                        <strong id="total_amount">&nbsp;{{ totals.amount }}</strong>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="transport_details">
                                            <!-- Modal -->
                                            <!-- <div class="modal fade" id="myModalTransport" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                        <h4 class="modal-title" id="myModalLabel">Transport Insert</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <label class="col-sm-2">Name</label>
                                                                <div class="col-sm-10">
                                                                    <input type="text" value="" name="add_transport_name" id="add_transport_name" class="form-control">
                                                                </div>
                                                            </div><br>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                            <button type="button" id="save_modal_data_transport" class="btn btn-primary" data-dismiss="modal">Submit</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> -->
                                            <label class=""><b>Transport Details</b></label>
                                            <div class="row gy-4">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label" for="transport">Transport</label>
                                                        <multiselect v-model="transport" :options="transport_options" placeholder="Select One" label="name" track-by="id" id="transport"></multiselect>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label" for="station">Station / To</label>
                                                        <multiselect v-model="station" :options="station_options" placeholder="Select One" label="name" track-by="id" id="station"></multiselect>
                                                        <div v-if="v$.station.$error" class="invalid mt-1">Select Station</div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4" >
                                                    <div class="form-group">
                                                        <label class="form-label" for="lr_mr_no">LR / MR No</label>
                                                        <div class="form-control-wrap">
                                                            <input type="text" v-model="lr_mr_no" id="lr_mr_no" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label" for="transport_date">LR / MR Date</label>
                                                        <div class="form-control-wrap">
                                                            <input type="date" v-model="transport_date" id="transport_date" class="form-control" @focus="showDatePicker()">
                                                            <div v-if="v$.transport_date.$error" class="invalid mt-1">Select Transport Date</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label" for="transport_cases">Cases</label>
                                                        <div class="form-control-wrap">
                                                            <input type="text" v-model="transport_cases" id="transport_cases" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label" for="courier_weight">Weight</label>
                                                        <div class="form-control-wrap">
                                                            <input type="text" v-model="courier_weight" id="courier_weight" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label" for="courier_freight">Freight</label>
                                                        <div class="form-control-wrap">
                                                            <input type="text" v-model="courier_freight" id="courier_freight" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="change_in">
                                            <label for="" class="form-label">Change In Amount</label>
                                            <div class="row gy-4">
                                                <div class="col-md-1">
                                                    <div class="form-group">
                                                        <multiselect v-model="change_in_sign" :options="change_in_sign_options" placeholder="Select One" label="name" track-by="name" id="change_in_sign" @close="getChangeAmount"></multiselect>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <input v-model="change_in_amount" id="change_in_amount" class="form-control" type="text" placeholder="Amount" @change="getChangeAmount">
                                                    </div>
                                                </div>
                                                <div class="col-md-9">
                                                    <div class="form-group">
                                                        <input v-model="transport_remark" id="transport_remark" class="form-control" type="text" placeholder="Remarks">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="text-right"><b>Total: <span id="final_total">{{ final_total }}</span></b></div>
                                        <hr class="preview-hr">
                                        <div class="row gy-4">
                                            <div class="col-md-12 text-center">
                                                <div class="form-group">
                                                    <a v-bind:href="cancel_url" class="btn btn-dim btn-secondary mr-3">Cancel</a>
                                                    <button type="submit" class="btn btn-primary" id="submit-form" :disabled="isSubmitDisabled">Save changes</button>
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
    import useVuelidate from '@vuelidate/core';
    import { required, email, requiredIf } from '@vuelidate/validators';

    export default {
        setup () {
            return { v$: useVuelidate() }
        },
        name: 'createCompany',
        components: {
            Multiselect,
            AddCompany
        },
        props: {
            scope: String,
            id: Number,
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
                change_reference: 0,
                change_reference_new: false,
                reference_via: { name: '' },
                new_old_sale_bill: 1,
                from_email: '',
                delivery_by: '',
                from_name: '',
                reference_new: true,
                reference_id: '',
                isSupplierDisabled: false,
                isCustomerDisabled: false,
                sale_bill_for: '',
                sale_bill_via: 3,
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
                exist_attachment: '',
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
                showUpdateSupplier: false,
                sale_bill_id: '',
                isProductSubCategoryDisabled: false,
                isSubmitDisabled: false,
                add_fabric_name: '',
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
                from_name: { requiredIf: requiredIf(this.is_from_name_required) },
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
            axios.get('/account/sale-bill/fetch-sale-bill/'+this.id)
            .then(response => {
                var data = response.data;
                this.reference_id = data.sale_bill.general_ref_id;

                if (data.sale_bill.sale_bill_flag == 1 && data.sale_bill.general_ref_id == 0) {
                    $('#top-reference-change-section').hide();
                    $('#show-all-reference-details').show().css({'padding': '1rem', 'background': '#ffcccc'});
                } else {
                    if (data.sale_bill.is_moved != 1) {
                        $('#top-reference-change-section').show();
                        $('#show-all-reference-details').hide();
                    }
                }

                if (data.sale_bill.sale_bill_for == 1) {
                    $('#product_sub_category_section_full, #product_sub_category_section').show();
                }

                this.sale_bill_options = data.product_default_categories;
                this.sale_bill_for = data.product_default_categories.find( _ => _.id == data.sale_bill.sale_bill_for );

                this.product_category_options = data.product_categories;
                this.product_category = data.product_categories.find( _ => _.id == data.sale_bill.product_default_category_id );

                if (data.sale_bill.inward_id == 1) {
                    this.reference_inward = { id: 0, name: 'Direct'};
                }

                if (data.companyFromInward.length > 0) {
                    this.customer_options = data.companyFromInward;
                }
                this.customer = this.customer_options.find( _ => _.id == data.sale_bill.company_id );
                this.customer_address = this.customer_address_options.find( _ => _.id == data.sale_bill.address );
                if (this.companyType == 2) { this.isCustomerDisabled = true; }

                if (data.sale_bill.is_moved == 0) {
                    this.isSupplierDisabled = true;
                } else {
                    this.supplier_options = data.supplier_group;
                    if (this.companyType == 3) {
                        this.isSupplierDisabled = true;
                        this.showUpdateSupplier = true;
                    }
                }
                this.supplier = this.supplier_options.find( _ => _.id == data.sale_bill.supplier_id );

                this.agent = this.agent_options.find( _ => _.id == data.sale_bill.agent_id );

                this.sale_bill_id = data.sale_bill.sale_bill_id;
                this.supplier_invoice_no = data.sale_bill.supplier_invoice_no;
                this.bill_date = data.sale_bill.select_date;

                if (data.sale_bill.attachment) {
                    this.exist_attachment = data.sale_bill.attachment;
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

                if (data.sale_bill.is_moved == 1) { isProductSubCategoryDisabled = true; }
                var subCat = JSON.parse(data.sale_bill.product_category_id);
                if (!(typeof(subCat) == 'number' || typeof(subCat) == 'string')) {
                    this.product_sub_category_options = data.subCategory;
                    subCat.forEach((k, i) => {
                        this.product_sub_category.push(data.subCategory.find( _ => _.id == k ));
                    });
                }
                // this.getProducts();

                if (data.sale_bill.sale_bill_for == 1 && subCat.length > 0) {
                    if (data.sale_bill_items.length > 0) {
                        this.product_options = data.product;
                        data.sale_bill_items.forEach((k, i) => {
                            var sub_prod = data.subProducts.find( _ => _.id == k.sub_product_id );
                            if (sub_prod == undefined) {
                                sub_prod = {id: 0, name: 'Full Catalogue'};
                            }
                            this.productDetails[i] = {
                                product_name: data.product.find( _ => _.id == k.product_or_fabric_id ),
                                sub_product_name: sub_prod,
                                hsn_code: k.hsn_code,
                                pieces: parseInt(k.pieces),
                                rate: parseInt(k.rate),
                                discount: parseFloat(k.discount),
                                discount_amount: parseFloat(k.discount_amount),
                                cgst: parseFloat(k.cgst),
                                cgst_amount: parseFloat(k.cgst_amount),
                                sgst: parseFloat(k.sgst),
                                sgst_amount: parseFloat(k.sgst_amount),
                                igst: parseFloat(k.igst),
                                igst_amount: parseFloat(k.igst_amount),
                                amount: parseFloat(k.amount)
                            }
                            this.sub_product_options[i] = [{id: 0, name: 'Full Catalogue'}];
                        });
                        this.calculateTotalProducts(0);
                        $('#item_details_div, .dynamic_items').slideDown();
                    }
                    $('#add_product_details').show();
                } else if (data.sale_bill.sale_bill_for == 2) {
                    if (data.sale_bill_items.length > 0) {
                        this.fabric_options = data.subCategory;
                        data.sale_bill_items.forEach((k, i) => {
                            this.fabricDetails[i] = {
                                fabric_name: data.subCategory.find( _ => _.id == k.product_or_fabric_id ),
                                hsn_code: k.hsn_code,
                                pieces_or_meters: k.pieces_meters == 1 ? ({ id: 1, name: 'Meters' }) : ({ id: 2, name: 'Pieces' }),
                                pieces: parseInt(k.pieces),
                                meters: parseFloat(k.meters),
                                rate: parseInt(k.rate),
                                discount: parseFloat(k.discount),
                                discount_amount: parseFloat(k.discount_amount),
                                cgst: parseFloat(k.cgst),
                                cgst_amount: parseFloat(k.cgst_amount),
                                sgst: parseFloat(k.sgst),
                                sgst_amount: parseFloat(k.sgst_amount),
                                igst: parseFloat(k.igst),
                                igst_amount: parseFloat(k.igst_amount),
                                amount: parseFloat(k.amount)
                            }
                        });
                        this.calculateTotalFabrics(0);
                        $('#add_fabric_details, .dynamic_items_fabrics').show();
                        $('#item_details_div').slideDown();
                    }
                    $('#add_new_fabric').show();
                }
                // this.getProductSubCategory();

                this.transport = this.transport_options.find( _ => _.id == data.sale_bill_transports.transport_id );
                this.lr_mr_no = data.sale_bill_transports.lr_mr_no;
                this.transport_date = data.sale_bill_transports.date;
                this.transport_cases = data.sale_bill_transports.cases;
                this.courier_weight = data.sale_bill_transports.weight;
                this.courier_freight = data.sale_bill_transports.freight;

                this.change_in_amount = data.sale_bill.change_in_amount;
                this.change_in_sign = data.sale_bill.sign_change == '+' ? { name: '+' } : { name: '-' };
                this.final_total = parseFloat(data.sale_bill.total);
                this.transport_remark = data.sale_bill.remark;

            });
        },
        watch: {
            customer (newValue, oldValue) {
                this.getCustomerAddress(newValue);
            },
        },
        methods: {
            showAllReferenceDetails (e) {
                if (this.change_reference == 1) {
                    $('#show-all-reference-details').slideDown();
                    this.is_from_name_required = this.is_reference_via_required = true;
                } else {
                    this.is_from_name_required = this.is_reference_via_required = false;
                    $('#show-all-reference-details').slideUp();
                }
            },
            getReferenceForSaleBillUpdate (e, url = '') {
                if(this.new_old_sale_bill == 0) {
                    $('#show-references').slideDown();
                    $('#new_reference_details_div').slideUp();
                    this.is_reference_via_required = this.is_from_name_required = false;
                    if (url == '') {
                        url = '/account/sale_bill/getReferenceForSaleBillUpdate/'+this.supplier.id+'?ref_via='+this.reference_via.name;
                    }
                    axios.get(url)
                    .then(response => {
                        this.old_reference_data = response.data;
                        $('#show-references tbody tr input[type="radio"]').first().prop('checked', true);
                        this.getUpdatedOldReferences();
                    });
                } else {
                    $('#new_reference_details_div').slideDown();
                    $('#show-references').slideUp();
                    this.bill_date = '';
                    // $('#bill_date').attr('disabled', false);
                }
            },
            resetSupplier (event) {
                $('#new_reference_details_div').show();
                $('#show-references').slideUp();
                this.old_reference_data = '';
                this.is_from_name_required = this.is_reference_via_required = true;
                this.bill_date = '';
                // $('#bill_date').attr('disabled', false);
                this.customer = '';
                $('#customer').attr('readonly', false);
                this.supplier = '';
                this.isSupplierDisabled = false;
                // $('#transport_date').attr('readonly', false);
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
                        if ($('#hidden_sale_bill_date').val() != '') {
                            this.bill_date = $('#hidden_sale_bill_date').val();
                            // $('#bill_date').attr('disabled', true);
                        }
                        this.reference_id = $('input[name="reference_id_sale_bill"]:checked').val();
                        this.supplier = { id: $('#hidden_cmp_id').val(), name: $('#hidden_cmp_name').val() };
                        this.isSupplierDisabled = true;
                        if (this.reference_via.name != "Email") {
                            // $('#transport_date').attr('readonly', true);
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
                $('#product_sub_category_section_full, #product_sub_category_text').show();
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
            getProductSubCategory (e) {
                if (e) {
                    this.product_category = e;
                }
                if (this.sale_bill_is_moved == 0) {
                    if (this.sale_bill_via == 3) {
                        $('#supplierChangeDivMsg').show();
                        this.getReferenceForSaleBillUpdate(e);
                    }
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
                } else if (this.sale_bill_is_moved == 1) {
                    $('#new_reference_details_div').slideDown();
                }
            },
            refUpdateForSaleBill (e) {
                if (this.reference_via) {
                    var url = '/account/sale_bill/getRefForSaleBillUpdate?supplier_id='+this.supplier.id+'&ref_via='+this.reference_via.name;
                    this.getReferenceForSaleBillUpdate(e, url);
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
                            this.station_options = response.data[0];
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
                        setTimeout(() => {
                            this.getOldReferences();
                        }, 100);
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
            addProductDetailsRow (row) {
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
                setTimeout(() => {
                    if (this.productDetails[i].product_name && this.productDetails[i].product_name.id) {
                        axios.get('/account/sale-bill/getSubProductFromProduct?product_id='+this.productDetails[i].product_name.id)
                        .then(response => {
                            this.sub_product_options[i] = response.data;
                        });
                    }
                }, 100);
            },
            getSubProductRate (i) {
                if (i && typeof(i) == 'number') {
                    this.productDetails[i].rate = this.productDetails[i].sub_product_name.price;
                }
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
                setTimeout(() => {
                    this.totals.discount = this.totals.discount.toFixed(2);
                    this.totals.cgst = this.totals.cgst.toFixed(2);
                    this.totals.sgst = this.totals.sgst.toFixed(2);
                    this.totals.igst = this.totals.igst.toFixed(2);
                }, 100);
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
                setTimeout(() => {
                    this.totals.discount = this.totals.discount.toFixed(2);
                    this.totals.cgst = this.totals.cgst.toFixed(2);
                    this.totals.sgst = this.totals.sgst.toFixed(2);
                    this.totals.igst = this.totals.igst.toFixed(2);
                }, 100);
            },
            getChangeAmount (e) {
                if (this.change_in_sign.name == '+') {
                    this.final_total = parseInt(this.totals.amount) + parseInt(this.change_in_amount);
                } else {
                    this.final_total = parseInt(this.totals.amount) - parseInt(this.change_in_amount);
                }
            },
            updateSupplier (e) {
                axios.get('/account/sale-bill/updateSupplier/'+this.supplier.id)
                .then(response => {
                    this.supplier_options = response.data;
                });
            },
            removeAttachment () {
                if (confirm('Are you sure you want to delete?')) {
                    axios.get('/account/sale-bill/removeAttachment/'+this.exist_attachment)
                    .then(response => {
                        if (response.data.flag == 1) {
                            $('.remove-attachment').parent().html('<div class="text-success">SUCCESS</div>');
                            setTimeout(() => {
                                $('.remove-attachment').parent().remove();
                            }, 4000);
                        }
                    });
                }
            },
            addUpdateFabricName () {
                axios.post('/account/sale-bill/addFabricsDetails', {
                    fabric_name: this.add_fabric_name,
                    supplier_id: this.supplier.id,
                    mainCategory_id: this.product_category.id
                })
                .then(response => {
                    $('#closeFabricModalBtn').trigger('click');
                    this.add_fabric_name = '';
                    if (response.data.refresh_data == 1) {
                        this.getProductSubCategory();
                    }
                });
            },
            showDatePicker () {
                // document.querySelector('input[type=date]').addEventListener("focus", () => {
                    document.querySelector('input[type=date]').showPicker();
                // });
            },
            showDateTimePicker () {
                // document.querySelector('input[type=datetime-local]').addEventListener("focus", () => {
                    document.querySelector('input[type=datetime-local]').showPicker();
                // });
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
                formData.append('exist_attachment', this.exist_attachment);
                formData.append('sale_bill_id', this.sale_bill_id);
                formData.append('change_reference', this.change_reference);

                axios.post('/account/sale-bill/update', formData)
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
                            // $('#bill_date').attr('disabled', true);
                        }
                        $('#sale_bill_ref_msg').html('<td><div class="custom-control custom-radio"><input class="custom-control-input" type="radio" name="reference_id_sale_bill" value="'+$('#hidden_reference_id_input').val()+'" id="r-'+$('#hidden_reference_id_input').val()+'"><label class="custom-control-label" for="r-'+$('#hidden_reference_id_input').val()+'"></label></div></td><td>'+$('#hidden_reference_id_input').val()+'</td><td>'+$('#hidden_ref_emp_name').val()+'</td><td>'+$('#hidden_ref_date_added').val()+'</td><td>'+$('#hidden_ref_time_added').val()+'</td>');
                        this.supplier = { id: $('#hidden_cmp_id').val(), name: $('#hidden_cmp_name').val() };
                        this.isSupplierDisabled = true;
                        $('#show-references tr input[type="radio"]').last().prop('checked', true);
                        // $('#transport_date').attr('readonly', true);
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
<!-- <style src="vue-multiselect/dist/vue-multiselect.css"></style> -->
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
    .remove-attachment {
        color: red;
        position: absolute;
        top: -5px;
        /* right: 35px; */
        cursor: pointer;
    }
    .ni-cross {
        font-weight: bold;
    }

</style>
