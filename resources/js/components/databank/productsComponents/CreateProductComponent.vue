<template>   
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 v-if="scope == 'edit'" class="nk-block-title page-title">Edit Product</h3>
                                <h3 v-else class="nk-block-title page-title">Add Product</h3>
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
                                    <input type="hidden" v-if="scope == 'edit'" id="fv-group-id" v-model="productData.id">
                                    <div class="preview-block">
                                        <span class="preview-title-lg overline-title">Product Details</span>
                                        <div class="row gy-4">
                                            <div class="col-md-3">
                                                <div>
                                                    <label class="form-label" for="fv-name">Name</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="fv-name" v-model="productData.product_name">
                                                        <span v-if="errors.product_name" class="invalid">{{errors.product_name}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-catalogue-name">Catalogue Name</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="fv-catalogue-name" v-model="productData.catalogue_name">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-brand-name">Brand Name</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="fv-brand-name" v-model="productData.brand_name">
                                                    </div>
                                                </div>
                                            </div> 
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-model-name">Model / Acctress Name</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="fv-model-name" v-model="productData.model">
                                                    </div>
                                                </div>
                                            </div> 
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-launch-date">Launch Date</label>
                                                    <div class="form-control-wrap">
                                                        <input type="date" class="form-control" data-date-format="yyyy-mm-dd" id="fv-launch-date" v-model="productData.launch_date">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group code-block">
                                                    <label class="form-label" for="fw-company">Company</label>
                                                    <button type="button" class="btn btn-sm clipboard-init" data-toggle="modal" data-target="#addCompany" title="Add new company"><span class="clipboard-text">Add New</span></button>
                                                    <div>
                                                        <multiselect v-model="productData.company" :options="companies" placeholder="Select one" label="company_name" track-by="company_name" @input="getProductCategory"></multiselect>
                                                        <span v-if="errors.company" class="invalid">{{errors.company}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group code-block">
                                                    <label class="form-label" for="fw-company">Category</label>
                                                    <div>
                                                        <multiselect v-model="productData.category" :options="productCategories" placeholder="Select one" label="name" track-by="name" @input="getProductSubCategory"></multiselect>
                                                        <span v-if="errors.category" class="invalid">{{errors.category}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group code-block">
                                                    <label class="form-label" for="fw-company">Sub Category</label>
                                                    <div>
                                                        <multiselect v-model="productData.sub_category" :options="productSubCategories" placeholder="Select one" label="name" track-by="name" :multiple="true" :taggable="true" @input="getFabricField"></multiselect>
                                                        <span v-if="errors.sub_category" class="invalid">{{errors.sub_category}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="default-06">Main Image</label>
                                                    <div class="form-control-wrap">
                                                        <div class="custom-file">
                                                            <input type="file" class="custom-file-input" name="main_image" id="fv-main_image" @change="uploadMainImage">
                                                            <label class="custom-file-label" for="fv-main_image">Choose photo</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="default-06">Price List Image</label>
                                                    <div class="form-control-wrap">
                                                        <div class="custom-file">
                                                            <input type="file" class="custom-file-input" name="price_list_image" id="fv-price_list_image" @change="uploadPriceListImage">
                                                            <label class="custom-file-label" for="fv-price_list_image">Choose photo</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-description">Description</label>
                                                    <div class="form-control-wrap">
                                                        <textarea class="form-control no-resize" id="fv-description" v-model="productData.description"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr class="preview-hr">
                                        <span class="preview-title-lg overline-title">Product Other Details</span>
                                        <div class="row gy-4">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-catalogue-price">Catalogue Price</label>
                                                    <div class="form-control-wrap">
                                                        <input type="number" class="form-control" id="fv-catalogue-price" v-model="productData.catalogue_price">
                                                    </div>
                                                </div>
                                            </div>                                            
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-average-price">Average Price</label>
                                                    <div class="form-control-wrap">
                                                        <input type="number" class="form-control" id="fv-average-price" v-model="productData.average_price">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-wholesale-discount">Wholesale Discount</label>
                                                    <div class="form-control-wrap">
                                                        <input type="number" class="form-control" id="fv-wholesale-discount" v-model="productData.wholesale_discount" @change="addTotalWholesaleDiscount">
                                                    </div>
                                                </div>
                                            </div>                                            
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-wholesale-brokerage">Wholesale Brokerage</label>
                                                    <div class="form-control-wrap">
                                                        <input type="number" class="form-control" id="fv-wholesale-brokerage" v-model="productData.wholesale_brokerage" @change="addTotalWholesaleBrokerage">
                                                    </div>
                                                </div>
                                            </div>                                            
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-total-wholesale-discount">Total Wholesale Discount</label>
                                                    <div class="form-control-wrap">
                                                        <input type="number" class="form-control" id="fv-total-wholesale-discount" v-model="productData.total_wholesale_discount" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-retail-discount">Retail Discount</label>
                                                    <div class="form-control-wrap">
                                                        <input type="number" class="form-control" id="fv-retail-discount" v-model="productData.retail_discount" @change="addTotalRetailDiscount">
                                                    </div>
                                                </div>
                                            </div>                                            
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-retail-brokerage">Retail Brokerage</label>
                                                    <div class="form-control-wrap">
                                                        <input type="number" class="form-control" id="fv-retail-brokerage" v-model="productData.retail_brokerage" @change="addTotalRetailBrokerage">
                                                    </div>
                                                </div>
                                            </div>                                            
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-total-retail-discount">Total Retail Discount</label>
                                                    <div class="form-control-wrap">
                                                        <input type="number" class="form-control" id="fv-total-retail-discount" v-model="productData.total_retail_discount" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr class="preview-hr">
                                        <div class="row gy-4">
                                            <div class="col-md-12 d-flex align-items-center">
                                                <span class="preview-title-lg overline-title d-inline-block w-100">Add Product Additional Images</span>
                                                <li class="dropdown-toggle btn btn-icon btn-primary" @click="addAdditionalImageRow"><em class="icon ni ni-plus"></em></li>
                                            </div>
                                        </div>
                                        <div class="row gy-4" v-for="(productAdditionalImage, index) in productAdditionalImages" :key="index">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-label" for="fw-supplier_code">Supplier Code</label>
                                                    <div class="form-control-wrap">
                                                        <input v-model="productAdditionalImage.supplier_code" type="text" class="form-control" id="fw-supplier_code" name="fw-supplier_code">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label" for="fw-product_code">Product Code</label>
                                                    <div>
                                                        <input v-model="productAdditionalImage.product_code" type="text" class="form-control" id="fw-product_code" name="fw-product_code">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label" :for="'fw-product_image'+index">Product Image</label>
                                                    <div class="form-control-wrap">
                                                        <div class="custom-file">
                                                            <input type="file" class="custom-file-input" :id="'fv-product_image'+index" @change="uploadProductImage(index, $event)">
                                                            <label class="custom-file-label" :for="'fv-product_image'+index">Choose photo</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label" for="fw-price">Price</label>
                                                    <div class="form-control-wrap">
                                                        <input v-model="productAdditionalImage.price" type="text" class="form-control" id="fw-price" name="fw-price">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label" for="fw-sort_order">Sort Order</label>
                                                    <div class="form-control-wrap">
                                                        <input v-model="productAdditionalImage.sort_order" type="number" class="form-control" id="fw-sort_order" name="fw-sort_order">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-1 d-flex align-items-center flex-row-reverse">
                                                <li class="dropdown-toggle btn btn-icon btn-primary" @click="deleteAdditionalImageRow(productAdditionalImage)"><em class="icon ni ni-cross"></em></li>
                                            </div>
                                        </div>
                                        <hr class="preview-hr">
                                        <span class="preview-title-lg overline-title">Product Fabric Details</span>
                                        <div class="row gy-4">
                                            <div class="col-md-6" v-if="fabrics.saree_view == 1">
                                                <div class="form-group">
                                                    <label class="form-label" for="fw-saree_fabric">Saree Fabric</label>
                                                    <div class="form-control-wrap">
                                                        <input v-model="fabricsData.saree_fabric" type="text" class="form-control" id="fw-saree_fabric" name="fw-saree_fabric">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6" v-if="fabrics.saree_view == 1">
                                                <div class="form-group">
                                                    <label class="form-label" for="fw-saree_cut">Saree Cut</label>
                                                    <div class="form-control-wrap">
                                                        <input v-model="fabricsData.saree_cut" type="number" class="form-control" id="fw-saree_cut" name="fw-saree_cut">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6" v-if="fabrics.blouse_view == 1">
                                                <div class="form-group">
                                                    <label class="form-label" for="fw-blouse_fabric">Blouse Fabric</label>
                                                    <div class="form-control-wrap">
                                                        <input v-model="fabricsData.blouse_fabric" type="text" class="form-control" id="fw-blouse_fabric" name="fw-blouse_fabric">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6" v-if="fabrics.blouse_view == 1">
                                                <div class="form-group">
                                                    <label class="form-label" for="fw-blouse_cut">Blouse Cut</label>
                                                    <div class="form-control-wrap">
                                                        <input v-model="fabricsData.blouse_cut" type="number" class="form-control" id="fw-blouse_cut" name="fw-blouse_cut">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6" v-if="fabrics.top_view == 1">
                                                <div class="form-group">
                                                    <label class="form-label" for="fw-top_fabric">Top Fabric</label>
                                                    <div class="form-control-wrap">
                                                        <input v-model="fabricsData.top_fabric" type="text" class="form-control" id="fw-top_fabric" name="fw-top_fabric">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6" v-if="fabrics.top_view == 1">
                                                <div class="form-group">
                                                    <label class="form-label" for="fw-top_cut">Top Cut</label>
                                                    <div class="form-control-wrap">
                                                        <input v-model="fabricsData.top_cut" type="number" class="form-control" id="fw-top_cut" name="fw-top_cut">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6" v-if="fabrics.bottom_view == 1">
                                                <div class="form-group">
                                                    <label class="form-label" for="fw-bottom_fabric">Bottom Fabric</label>
                                                    <div class="form-control-wrap">
                                                        <input v-model="fabricsData.bottom_fabric" type="text" class="form-control" id="fw-bottom_fabric" name="fw-bottom_fabric">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6" v-if="fabrics.bottom_view == 1">
                                                <div class="form-group">
                                                    <label class="form-label" for="fw-bottom_cut">Bottom Cut</label>
                                                    <div class="form-control-wrap">
                                                        <input v-model="fabricsData.bottom_cut" type="number" class="form-control" id="fw-bottom_cut" name="fw-bottom_cut">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6" v-if="fabrics.dupatta_view == 1">
                                                <div class="form-group">
                                                    <label class="form-label" for="fw-dupatta_fabric">Dupatta Fabric</label>
                                                    <div class="form-control-wrap">
                                                        <input v-model="fabricsData.dupatta_fabric" type="text" class="form-control" id="fw-dupatta_fabric" name="fw-dupatta_fabric">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6" v-if="fabrics.dupatta_view == 1">
                                                <div class="form-group">
                                                    <label class="form-label" for="fw-dupatta_cut">Dupatta Cut</label>
                                                    <div class="form-control-wrap">
                                                        <input v-model="fabricsData.dupatta_cut" type="number" class="form-control" id="fw-dupatta_cut" name="fw-dupatta_cut">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6" v-if="fabrics.inner_view == 1">
                                                <div class="form-group">
                                                    <label class="form-label" for="fw-inner_fabric">Inner Fabric</label>
                                                    <div class="form-control-wrap">
                                                        <input v-model="fabricsData.inner_fabric" type="text" class="form-control" id="fw-inner_fabric" name="fw-inner_fabric">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6" v-if="fabrics.inner_view == 1">
                                                <div class="form-group">
                                                    <label class="form-label" for="fw-inner_cut">Inner Cut</label>
                                                    <div class="form-control-wrap">
                                                        <input v-model="fabricsData.inner_cut" type="number" class="form-control" id="fw-inner_cut" name="fw-inner_cut">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6" v-if="fabrics.sleeves_view == 1">
                                                <div class="form-group">
                                                    <label class="form-label" for="fw-sleeves_fabric">Sleeves Fabric</label>
                                                    <div class="form-control-wrap">
                                                        <input v-model="fabricsData.sleeves_fabric" type="text" class="form-control" id="fw-sleeves_fabric" name="fw-sleeves_fabric">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6" v-if="fabrics.sleeves_view == 1">
                                                <div class="form-group">
                                                    <label class="form-label" for="fw-sleeves_cut">Sleeves Cut</label>
                                                    <div class="form-control-wrap">
                                                        <input v-model="fabricsData.sleeves_cut" type="number" class="form-control" id="fw-sleeves_cut" name="fw-sleeves_cut">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6" v-if="fabrics.choli_view == 1">
                                                <div class="form-group">
                                                    <label class="form-label" for="fw-choli_fabric">Choli Fabric</label>
                                                    <div class="form-control-wrap">
                                                        <input v-model="fabricsData.choli_fabric" type="text" class="form-control" id="fw-choli_fabric" name="fw-choli_fabric">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6" v-if="fabrics.choli_view == 1">
                                                <div class="form-group">
                                                    <label class="form-label" for="fw-choli_cut">Choli Cut</label>
                                                    <div class="form-control-wrap">
                                                        <input v-model="fabricsData.choli_cut" type="number" class="form-control" id="fw-choli_cut" name="fw-choli_cut">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6" v-if="fabrics.lehenga_view == 1">
                                                <div class="form-group">
                                                    <label class="form-label" for="fw-lehenga_fabric">Lehenga Fabric</label>
                                                    <div class="form-control-wrap">
                                                        <input v-model="fabricsData.lehenga_fabric" type="text" class="form-control" id="fw-lehenga_fabric" name="fw-lehenga_fabric">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6" v-if="fabrics.lehenga_view == 1">
                                                <div class="form-group">
                                                    <label class="form-label" for="fw-lehenga_cut">Lehenga Cut</label>
                                                    <div class="form-control-wrap">
                                                        <input v-model="fabricsData.lehenga_cut" type="number" class="form-control" id="fw-lehenga_cut" name="fw-lehenga_cut">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6" v-if="fabrics.lining_view == 1">
                                                <div class="form-group">
                                                    <label class="form-label" for="fw-lining_fabric">Lining Fabric</label>
                                                    <div class="form-control-wrap">
                                                        <input v-model="fabricsData.lining_fabric" type="text" class="form-control" id="fw-lining_fabric" name="fw-lining_fabric">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6" v-if="fabrics.lining_view == 1">
                                                <div class="form-group">
                                                    <label class="form-label" for="fw-lining_cut">Lining Cut</label>
                                                    <div class="form-control-wrap">
                                                        <input v-model="fabricsData.lining_cut" type="number" class="form-control" id="fw-lining_cut" name="fw-lining_cut">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6" v-if="fabrics.gown_view == 1">
                                                <div class="form-group">
                                                    <label class="form-label" for="fw-gown_fabric">Gown Fabric</label>
                                                    <div class="form-control-wrap">
                                                        <input v-model="fabricsData.gown_fabric" type="text" class="form-control" id="fw-gown_fabric" name="fw-gown_fabric">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6" v-if="fabrics.gown_view == 1">
                                                <div class="form-group">
                                                    <label class="form-label" for="fw-gown_cut">Gown Cut</label>
                                                    <div class="form-control-wrap">
                                                        <input v-model="fabricsData.gown_cut" type="number" class="form-control" id="fw-gown_cut" name="fw-gown_cut">
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
        <AddCompany></AddCompany>
    </div>
</template>

<script>
    import Multiselect from 'vue-multiselect';
    import AddCompany from './modal/AddNewCompanyModelComponent';

    var products = [];
    var fabric_fields = [];
    export default {
        name: 'createProduct',
        props: {
            scope: String,
            id: Number,
        },
        components: { 
            Multiselect,
            AddCompany
        },
        data() {
            return {
                cancel_url: '/databank/catalog',
                companies: [],
                productCategories: [],
                productSubCategories: [],
                productAdditionalImages : [{
                    supplier_code: '',
                    product_code: '',
                    product_pic: '',
                    price: '',
                    sort_order: 0,
                }],
                fabricFields : [],
                fabrics : {
                    saree_view: 0,
                    blouse_view: 0,
                    top_view: 0,
                    bottom_view: 0,
                    dupatta_view: 0,
                    inner_view: 0,
                    sleeves_view: 0,
                    choli_view: 0,
                    lehenga_view: 0,
                    lining_view: 0,
                    gown_view: 0,
                },
                fabricsData : {                    
                    saree_fabric: '',
                    saree_cut: 0,
                    blouse_fabric: '',
                    blouse_cut: 0,
                    top_fabric: '',
                    top_cut: 0,
                    bottom_fabric: '',
                    bottom_cut: 0,
                    dupatta_fabric: '',
                    dupatta_cut: 0,
                    inner_fabric: '',
                    inner_cut: 0,
                    sleeves_fabric: '',
                    sleeves_cut: 0,
                    choli_fabric: '',
                    choli_cut: 0,
                    lehenga_fabric: '',
                    lehenga_cut: 0,
                    lining_fabric: '',
                    lining_cut: 0,
                    gown_fabric: '',
                    gown_cut: 0,
                },
                fabricFieldData : [],
                mainImage: [],
                priceListImage: [],
                errors: {
                    product_name: '',
                    company: '',
                    category: '',
                    sub_category: '',
                },
                productData: {
                    id: '',
                    product_name: '',
                    catalogue_name: '',
                    brand_name: '',
                    model: '',
                    launch_date: '',
                    company: [],
                    category: [],
                    sub_category: [],
                    main_image: '',
                    price_list_image: '',
                    description: '',
                    catalogue_price: '',
                    average_price: '',
                    wholesale_discount: '',
                    wholesale_brokerage: '',
                    total_wholesale_discount: 0,
                    retail_discount: '',
                    retail_brokerage: '',
                    total_retail_discount: 0,
                    additionalImages: '',
                    fabricsData: [],
                }
            }
        },
        created() {
            axios.get('/databank/catalog/list-companies')
            .then(response => {
                this.companies = response.data;
            });
        },
        methods: {
            addAdditionalImageRow: function() {
                this.productAdditionalImages.push({
                    supplier_code: '',
                    product_code: '',
                    product_pic: '',
                    price: '',
                    sort_order: '',
                });
            },
            deleteAdditionalImageRow: function(row) {
                this.productAdditionalImages.pop(row);
            },
            uploadMainImage (event) {
                this.mainImage = event.target.files[0];        
            },
            uploadPriceListImage (event) {
                this.priceListImage = event.target.files[0];        
            },
            uploadProductImage (index, e) {
                this.productAdditionalImages[index]['product_pic'] = e.target.files[0];
            },
            getProductCategory: function(event) {
                if(event != null) {
                    axios.get('/databank/catalog/main-category/'+event.id)
                    .then(response => { 
                        this.productCategories = response.data;
                    });
                }
            },
            getProductSubCategory: function(event) {
                if(event != null) {
                    axios.get('/databank/catalog/sub-category/'+event.id+'/'+ this.productData.company.id)
                    .then(response => {
                        this.productSubCategories = response.data;
                    });
                }                
            },
            getFabricField: function(event) {
                if(event.length != 0) {
                    var fabricId = [];
                    event.forEach(function(e, index){
                        if(e.fabric_id != 0) {
                            fabricId.push(e.fabric_id);
                        }
                    });

                    axios.get(`/databank/catalog/fabric-field/${JSON.stringify(fabricId)}`)
                    .then(response => {
                        response.data.forEach(function(field, index){
                            fabric_fields[index] = field;
                        });

                        var i = 0;
                        for(i=0; i<fabric_fields.length; i++) {
                            this.fabrics[fabric_fields[i]] = 1;                        
                        }
                    });
                }

            },
            addTotalWholesaleDiscount: function() {
                this.productData.total_wholesale_discount = +this.productData.total_wholesale_discount + +this.productData.wholesale_discount;
            },
            addTotalWholesaleBrokerage: function() {
                this.productData.total_wholesale_discount = +this.productData.total_wholesale_discount + +this.productData.wholesale_brokerage;
            },
            addTotalRetailDiscount: function() {
                this.productData.total_retail_discount = +this.productData.total_retail_discount + +this.productData.retail_discount;
            },
            addTotalRetailBrokerage: function() {
                this.productData.total_retail_discount = +this.productData.total_retail_discount + +this.productData.retail_brokerage;
            },
            register () {
                var formData = new FormData();

                formData.append('product_data', JSON.stringify(this.productData));
                formData.append('additionalImages', JSON.stringify(this.productAdditionalImages));
                formData.append('fabricsData', JSON.stringify(this.fabricsData));
                formData.append('mainImage', this.mainImage);
                formData.append('priceListImage', this.priceListImage);

                this.productAdditionalImages.forEach((aproduct,index)=>{
                    if(aproduct.product_pic){
                        formData.append(`product_additional_images[${index}]`, aproduct.product_pic);
                    }else{
                        formData.append(`product_additional_images[${index}]`, null);
                    }
                })

                if (this.scope == 'edit') {
                    axios.post('/databank/catalog/update', formData)
                    .then(response => {
                        window.location.href = '/databank/catalog';
                    })
                    .catch((error) => {
                        var validationError = error.response.data.errors;

                        if(validationError.product_name) {
                            this.errors.product_name = validationError.product_name[0];
                        }
                        if(validationError.company) {
                            this.errors.company = validationError.company[0];
                        }
                        if(validationError.category) {
                            this.errors.category = validationError.category[0];
                        }
                        if(validationError.sub_category) {
                            this.errors.sub_category = validationError.sub_category[0];
                        }
                    });
                } else {
                    axios.post('/databank/catalog/create', formData)
                    .then(response => {
                        // window.location.href = '/databank/catalog';
                    })
                    .catch((error) => {
                        var validationError = error.response.data.errors;

                        if(validationError.product_name) {
                            this.errors.product_name = validationError.product_name[0];
                        }
                        if(validationError.company) {
                            this.errors.company = validationError.company[0];
                        }
                        if(validationError.category) {
                            this.errors.category = validationError.category[0];
                        }
                        if(validationError.sub_category) {
                            this.errors.sub_category = validationError.sub_category[0];
                        }
                    });
                }
            },
        },
        mounted() {
            switch (this.scope) {
                case 'edit' :
                    axios.get(`/databank/catalog/fetch-product/${this.id}`)
                    .then(response => {
                        products = response.data;
                
                        var fabricViews = [];

                        this.productData = products.productData;
                        this.productAdditionalImages = products.additionalImage;
                        this.fabricsData = products.fabricsData[0];

                        this.productData.total_wholesale_discount = +products.productData.wholesale_brokerage + +products.productData.wholesale_discount;
                        this.productData.total_retail_discount = +products.productData.retail_brokerage + +products.productData.retail_discount;

                        fabricViews = products.fabricsView;
                        
                        var i = 0;
                        for(i=0; i<fabricViews.length; i++) {
                            this.fabrics[fabricViews[i]] = 1;
                        }
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