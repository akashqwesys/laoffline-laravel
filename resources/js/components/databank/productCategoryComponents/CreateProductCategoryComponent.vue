<template>
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 v-if="scope == 'edit'" class="nk-block-title page-title">Edit Product Category</h3>
                                <h3 v-else class="nk-block-title page-title">Add Product Category</h3>
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
                                            <div class="col-md-12">
                                                <div>
                                                    <label class="form-label" for="fv-default_category">Default Category</label>
                                                    <div>
                                                        <multiselect id="fv-default_category" v-model="form.default_category" :options="defaultCategories" placeholder="Select one" label="name" track-by="name"></multiselect>
                                                        <span v-if="errors.default_category" class="invalid">{{errors.default_category}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-category-name">Category Name</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="fv-category-name" v-model="form.name">
                                                        <span v-if="errors.category_name" class="invalid">{{errors.category_name}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-sort-order">Sort Order</label>
                                                    <div class="form-control-wrap">
                                                        <input type="number" class="form-control" id="fv-sort-order" v-model="form.sort_order" required>
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
    </div>
</template>

<script>
    import Multiselect from 'vue-multiselect';
    import Form from 'vform';

    var productCategories = [];
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
                cancel_url: '/databank/product-category',
                defaultCategories: [],
                errors: {
                    default_category: '',
                    category_name: ''
                },
                form: new Form({
                    id: '',
                    default_category: '',
                    name: '',
                    sort_order: 0,
                })
            }
        },
        created() {
            axios.get('/databank/product-category/list-default-category')
            .then(response => {
                this.defaultCategories = response.data;
            });
        },
        methods: {
            register () {
                if (this.scope == 'edit') {
                    this.form.post('/databank/product-category/update')
                        .then(( response ) => {
                            window.location.href = '/databank/product-category';
                    })
                    .catch((error) => {
                        var validationError = error.response.data.errors;

                        if(validationError.default_category) {
                            this.errors.default_category = validationError.default_category[0];
                        }
                        if(validationError.name) {
                            this.errors.category_name = validationError.name[0];
                        }
                    })
                } else {
                    this.form.post('/databank/product-category/create')
                        .then(( response ) => {
                            window.location.href = '/databank/product-category';
                    })
                    .catch((error) => {
                        var validationError = error.response.data.errors;

                        if(validationError.default_category) {
                            this.errors.default_category = validationError.default_category[0];
                        }
                        if(validationError.name) {
                            this.errors.category_name = validationError.name[0];
                        }
                    })
                }
            },
        },
        mounted() {
            switch (this.scope) {
                case 'edit' :
                    axios.get(`/databank/product-category/fetch-product-category/${this.id}`)
                    .then(response => {
                        productCategories = response.data;

                        this.form.id = productCategories.id;
                        this.form.default_category = productCategories.defaultCategory;
                        this.form.name = productCategories.name;
                        this.form.sort_order = productCategories.sort_order;
                    });
                    break;
                default:
                    break;
            }
        },
    };
</script>
<!-- <style src="vue-multiselect/dist/vue-multiselect.css"></style> -->
<style scoped>
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
