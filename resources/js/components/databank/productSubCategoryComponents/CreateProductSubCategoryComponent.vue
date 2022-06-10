<template>
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 v-if="scope == 'edit'" class="nk-block-title page-title">Edit Product Sub Category</h3>
                                <h3 v-else class="nk-block-title page-title">Add Product Sub Category</h3>
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
                                    <input type="hidden" v-if="scope == 'edit'" id="fv-group-id" v-model="form.id">
                                    <div class="preview-block">
                                        <div class="row gy-4">
                                            <div class="col-md-2">
                                                <div class="preview-block">
                                                    <label class="form-label">For Multiple Company</label>
                                                    <ul class="custom-control-group g-3 align-center">
                                                        <li>
                                                            <div class="custom-control custom-radio checked">
                                                                <input type="radio" class="custom-control-input" checked name="is_active" @click="multipleCompanies = true" v-model="form.multiple_company" id="fv-active-yes" value="1" >
                                                                <label class="custom-control-label" for="fv-active-yes">Yes</label>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" class="custom-control-input" name="is_active" @click="multipleCompanies = false" v-model="form.multiple_company" id="fv-active-no" value="0" >
                                                                <label class="custom-control-label" for="fv-active-no">No</label>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <hr class="preview-hr">
                                        <div v-if="multipleCompanies">
                                            <div class="row gy-4">
                                                <div :class="fabric == false ? 'col-md-6' : 'col-md-4'">
                                                    <div>
                                                        <label class="form-label" for="fv-main_category">Main Category</label>
                                                        <div>
                                                            <multiselect v-model="form.main_category" :options="productCategories" placeholder="Select one" label="category_name" track-by="category_name" @select="getFiber"></multiselect>
                                                            <span v-if="errors.mainCategory" class="invalid">{{errors.mainCategory}}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div :class="fabric == false ? 'col-md-6' : 'col-md-4'">
                                                    <div>
                                                        <label class="form-label" for="fv-company">Company</label>
                                                        <div>
                                                            <multiselect v-model="form.company" tag-placeholder="Select Company" placeholder="Search Company" label="company_name" track-by="id" :options="companies" :multiple="true" :taggable="true"></multiselect>
                                                            <span v-if="errors.companies" class="invalid">{{errors.companies}}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div v-if="fabric == true" class="col-md-4">
                                                    <div>
                                                        <label class="form-label" for="fv-fabricGroup">Fabric Group</label>
                                                        <div>
                                                            <multiselect v-model="form.fabric_group" tag-placeholder="Select Fabric group" placeholder="Search Fabric Group" label="name" track-by="id" :options="fabricGroup"></multiselect>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label" for="fv-sub-category-name">Sub Category Name</label>
                                                        <div class="form-control-wrap">
                                                            <input type="text" class="form-control" id="fv-sub-category-name" v-model="form.sub_category_name">
                                                            <span v-if="errors.subCategoryName" class="invalid">{{errors.subCategoryName}}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label" for="fv-sort-order">Sort Order</label>
                                                        <div class="form-control-wrap">
                                                            <input type="number" class="form-control" id="fv-sort-order" v-model="form.sort_order">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div v-else>
                                            <div class="row gy-4">
                                                <div class="col-md-12">
                                                    <div>
                                                        <label class="form-label" for="fv-company">Company</label>
                                                        <div>
                                                            <multiselect v-model="form.singleCompany" :options="companies" placeholder="Select one" label="company_name" track-by="company_name"></multiselect>
                                                            <span v-if="errors.singleCompany" class="invalid">{{errors.singleCompany}}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr class="preview-hr">
                                            <div class="row gy-4">
                                                <div class="col-md-12 d-flex align-items-center">
                                                    <span class="preview-title-lg overline-title d-inline-block w-100">Product Sub Category</span>
                                                    <li class="dropdown-toggle btn btn-icon btn-primary" @click="addProductSubCategoryRow"><em class="icon ni ni-plus"></em></li>
                                                </div>
                                            </div>
                                            <div class="row gy-4" v-for="(productSubCategory, index) in productSubCategories" :key="index">
                                                <div :class="(productSubCategory.hasOwnProperty('mfabric_group') && mfabric == true) ? 'col-md-2' : 'col-md-3'">
                                                    <div>
                                                        <label class="form-label" for="fv-mainCategory">Main Category</label>
                                                        <div>
                                                            <multiselect v-model="productSubCategory.mainCategory" :options="productCategories" placeholder="Select one" label="category_name" track-by="category_name" @select="getMFiber($event, index)"></multiselect>
                                                            <span v-if="errors.mainCate" class="invalid">{{errors.mainCate}}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div v-if="productSubCategory.hasOwnProperty('mfabric_group') && mfabric == true" class="col-md-2">
                                                    <div>
                                                        <label class="form-label" for="fv-company">Fabric Group</label>
                                                        <div>
                                                            <multiselect v-model="productSubCategory.mfabric_group" tag-placeholder="Select Fabric group" placeholder="Search Fabric Group" label="name" track-by="id" :options="fabricGroup"></multiselect>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div :class="(productSubCategory.hasOwnProperty('mfabric_group') && mfabric == true) ? 'col-md-2' : 'col-md-3'">
                                                    <div>
                                                        <label class="form-label" for="fv-company">Sub Category Name</label>
                                                        <div class="form-control-wrap">
                                                            <input type="text" class="form-control" id="fv-category-name" v-model="productSubCategory.sub_category_name">
                                                            <span v-if="errors.subCategoryName" class="invalid">{{errors.subCategoryName}}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div :class="(productSubCategory.hasOwnProperty('mfabric_group') && mfabric == true) ? 'col-md-2' : 'col-md-3'">
                                                    <div>
                                                        <label class="form-label" for="fv-company">Rate</label>
                                                        <div class="form-control-wrap">
                                                            <input type="number" class="form-control" id="fv-category-name" v-model="productSubCategory.rate">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div>
                                                        <label class="form-label" for="fv-company">Sort Order</label>
                                                        <div class="form-control-wrap">
                                                            <input type="number" class="form-control" id="fv-category-name" v-model="productSubCategory.sort_order">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div :class="(productSubCategory.hasOwnProperty('mfabric_group') && mfabric == true) ? 'col-md-2 d-flex align-items-center flex-row-reverse' : 'col-md-1 d-flex align-items-center flex-row-reverse'">
                                                    <li class="dropdown-toggle btn btn-icon btn-primary" @click="deleteProductSubCategoryRow(contactDetail)"><em class="icon ni ni-cross"></em></li>
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
    import Multiselect from 'vue-multiselect';
    import Form from 'vform';

    var productSubCategories = [];
    var i = 0;
    export default {
        name: 'createProductCategory',
        props: {
            scope: String,
            id: Number,
        },
        components: {
            Multiselect
        },
        data() {
            return {
                cancel_url: '/databank/productsub-category',
                multipleCompanies: true,
                productCategories: [],
                companies: [],
                fabric: false,
                mfabric: false,
                fabricGroup: [],
                productSubCategories : [{
                    mainCategory: '',
                    mfabric_group: '',
                    sub_category_name: '',
                    rate: 0,
                    sort_order: 0,
                }],
                errors: {
                    mainCategory: '',
                    companies: '',
                    subCategoryName: '',
                    mainCate: '',
                    singleCompany: '',
                    subCateName: '',
                },
                form: new Form({
                    id: '',
                    multiple_company: 1,
                    main_category: '',
                    fabric_group: '',
                    company: [],
                    singleCompany: [],
                    sub_category_name: '',
                    sort_order: 0,
                    productSubCategory: '',
                })
            }
        },
        created() {
            axios.get('/databank/product-category/list-category')
            .then(response => {
                this.productCategories = response.data;
            });
            axios.get('/databank/productsub-category/listCompanies')
            .then(response => {
                this.companies = response.data;
            });
            axios.get('/databank/productsub-category/listProductFabricGroup')
            .then(response => {
                this.fabricGroup = response.data;
            });
        },
        methods: {
            getFiber: function(event) {
                if (event.product_default_category_id == 1) {
                    this.fabric = true;
                } else {
                    this.fabric = false;
                }
            },
            getMFiber: function(event, index) {
                if (event.product_default_category_id == 1) {
                    this.mfabric = true;
                    if (index >= 1) {
                        this.productSubCategories[index]['mfabric_group'] = '';
                    }
                } else {
                    this.mfabric = false;
                }
            },
            addProductSubCategoryRow: function() {
                this.productSubCategories.push({
                    mainCategory: '',
                    sub_category_name: '',
                    rate: '',
                    sort_order: '',
                });
            },
            deleteProductSubCategoryRow: function(row) {
                this.productSubCategories.pop(row);
            },
            register () {
                this.form.productSubCategory = this.productSubCategories;

                if (this.scope == 'edit') {
                    this.form.post('/databank/productsub-category/update')
                        .then(( response ) => {
                            window.location.href = '/databank/productsub-category';
                    })
                } else {
                    this.form.post('/databank/productsub-category/create')
                        .then(( response ) => {
                            window.location.href = '/databank/productsub-category';
                    })
                    .catch((error) => {
                        var validationError = error.response.data.errors;

                        if(validationError.main_category) {
                            this.errors.mainCategory = validationError.main_category[0];
                        }
                        if(validationError.company) {
                            this.errors.companies = validationError.company[0];
                        }
                        if(validationError.sub_category_name) {
                            this.errors.subCategoryName = validationError.sub_category_name[0];
                        }
                        if(validationError.mainCategory) {
                            this.errors.mainCate = validationError.mainCategory[0];
                        }
                        if(validationError.singleCompany) {
                            this.errors.singleCompany = validationError.singleCompany[0];
                        }
                        if(validationError.sub_category_name) {
                            this.errors.subCateName = validationError.sub_category_name[0];
                        }
                    })
                }
            },
        },
        mounted() {
            switch (this.scope) {
                case 'edit' :
                    axios.get(`/databank/productsub-category/fetch-productsub-category/${this.id}`)
                    .then(response => {
                        productSubCategories = response.data;

                        this.form.multiple_company = productSubCategories.multiple_company;
                        this.form.id = productSubCategories.id;

                        if (productSubCategories.multiple_company == 1) {
                            this.form.id = productSubCategories.id;
                            this.form.main_category = productSubCategories.main_category;
                            this.form.company = productSubCategories.company;
                            this.form.sub_category_name = productSubCategories.sub_category_name;
                            this.form.fabric_group = productSubCategories.fabric_group;
                            this.form.sort_order = productSubCategories.sort_order;
                            if (this.form.fabric_group) {
                                this.fabric = true;
                            } else {
                                this.fabric = false;
                            }

                        } else if (productSubCategories.multiple_company == 0) {
                            this.multipleCompanies = false;
                            this.form.singleCompany = productSubCategories.company;
                            this.productSubCategories = productSubCategories.subCategory;
                            this.productSubCategories.forEach((category,index)=>{
                                if (category.mfabric_group != null) {
                                    this.mfabric = true;
                                } else {
                                    this.mfabric = false;
                                }
                            });
                        }
                    });
                    break;
                default:
                    break;
            }
        },
    };
</script>

