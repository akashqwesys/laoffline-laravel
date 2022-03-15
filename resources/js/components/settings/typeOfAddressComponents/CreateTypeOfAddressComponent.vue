<template>
    
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 v-if="scope == 'edit'" class="nk-block-title page-title">Edit Type Of Address</h3>
                                <h3 v-else class="nk-block-title page-title">Add Type Of Address</h3>
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
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-name">Name</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="fv-name" v-model="form.name" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-sort-order">Sort Order</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="fv-sort-order" v-model="form.sort_order" required>
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
        name: 'createTypeOfAddresses',
        props: {
            scope: String,
            id: Number,
        },
        data() {
            return {
                cancel_url: '/settings/type-of-address',
                form: new Form({
                    id: '',
                    category_name: '',
                    sort_order: '',
                })
            }
        },
        methods: {
            register () {
                if (this.scope == 'edit') {
                    this.form.post('/settings/type-of-address/update')
                        .then(( response ) => {
                            window.location.href = '/settings/type-of-address';
                    })
                } else {
                    this.form.post('/settings/type-of-address/create')
                        .then(( response ) => {
                            window.location.href = '/settings/type-of-address';
                    })
                }
            },
        },
        mounted() {
            switch (this.scope) {
                case 'edit' :
                    axios.get(`/settings/type-of-address/fetch-type-of-address/${this.id}`)
                    .then(response => {
                        companyCategories = response.data;

                        this.form.id = companyCategories.id;
                        this.form.name = companyCategories.name;
                        this.form.sort_order = companyCategories.sort_order;
                    });
                    break;
                default:
                    break;
            }
        },
    };
</script>