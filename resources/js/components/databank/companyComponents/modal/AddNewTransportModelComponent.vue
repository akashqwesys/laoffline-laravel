<template>    
    <div class="modal fade" id="addTransport">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Transport</h5>
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
                                        <label class="form-label" for="fv-gstin">GSTIN</label>
                                        <div class="form-control-wrap">
                                            <input type="text" class="form-control" id="fv-gstin" v-model="form.gstin" required>
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
    export default {
        name: 'createTransport',
        data() {
            return {
                form: new Form({
                    id: '',
                    name: '',
                    gstin: '',
                })
            }
        },
        methods: {
            register () {
                this.form.post('/settings/transport-details/create')
                    .then(( response ) => {
                        $('#addTransport').hide();
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