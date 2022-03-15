<template>
    
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 v-if="scope == 'edit'" class="nk-block-title page-title">Edit Company Category</h3>
                                <h3 v-else class="nk-block-title page-title">Add Company Category</h3>
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
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-category-name">Category Name</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="fv-category-name" v-model="form.category_name">
                                                        <span v-if="errors.category_name" class="invalid">{{errors.category_name}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-sort-order">Sort Order</label>
                                                    <div class="form-control-wrap">
                                                        <input type="number" class="form-control" id="fv-sort-order" v-model="form.sort_order">
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
    var companyCategories = [];
    export default {
        name: 'createCompanyCategory',
        props: {
            scope: String,
            id: Number,
        },
        data() {
            return {
                cancel_url: '/databank/companyCategory',
                errors: {
                    category_name: ''
                },
                form: new Form({
                    id: '',
                    category_name: '',
                    sort_order: 0,
                })
            }
        },
        methods: {
            register () {
                if (this.scope == 'edit') {
                    this.form.post('/databank/companyCategory/update')
                        .then(( response ) => {
                            window.location.href = '/databank/companyCategory';
                    })
                    .catch((error) => {
                        var validationError = error.response.data.errors;

                        if(validationError.category_name) {
                            this.errors.category_name = validationError.category_name[0];
                        }
                    })
                } else {
                    this.form.post('/databank/companyCategory/create')
                        .then(( response ) => {
                            window.location.href = '/databank/companyCategory';
                    })
                    .catch((error) => {
                        var validationError = error.response.data.errors;

                        if(validationError.category_name) {
                            this.errors.category_name = validationError.category_name[0];
                        }
                    })
                }
            },
        },
        mounted() {
            switch (this.scope) {
                case 'edit' :
                    axios.get(`/databank/companyCategory/fetch-company-category/${this.id}`)
                    .then(response => {
                        companyCategories = response.data;

                        this.form.id = companyCategories.id;
                        this.form.category_name = companyCategories.category_name;
                        this.form.sort_order = companyCategories.sort_order;
                    });
                    break;
                default:
                    break;
            }
        },
    };
</script>