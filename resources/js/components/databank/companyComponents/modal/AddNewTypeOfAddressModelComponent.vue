<template>    
    <div class="modal fade" id="addTypeOffAddress">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Type Of Address</h5>
                    <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                        <em class="icon ni ni-cross"></em>
                    </a>
                </div>
                <div class="modal-body">
                    <form action="#" @submit.prevent="register()">
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
</template>

<script>
    var cities = [];
    export default {
        name: 'createCities',
        data() {
            return {
                form: new Form({
                    id: '',
                    category_name: '',
                    sort_order: '',
                })
            }
        },
        methods: {
            register () {
                this.form.post('/settings/type-of-address/create')
                    .then(( response ) => {
                        $('#addTypeOffAddress').hide();
                        $('.modal-backdrop').remove();
                        $('body').removeClass('modal-open');
                        $('body').removeAttr('style');
                })
            },
        },
        mounted() {

        },
    };
</script>