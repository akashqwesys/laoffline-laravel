<template>
    
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">Edit Default Settings</h3>
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
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-cgst">CGST</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="fv-cgst" v-model="form.cgst" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-sgst">SGST</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="fv-sgst" v-model="form.sgst" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-igst">IGST</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="fv-igst" v-model="form.igst" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-tds">TDS</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="fv-tds" v-model="form.tds" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-service_tax_limit">Service Tax Limit</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="fv-service_tax_limit" v-model="form.service_tax_limit" required>
                                                    </div>
                                                </div>
                                            </div> 
                                        </div>
                                        <hr class="preview-hr">
                                        <div class="row gy-4">                                        
                                            <div class="col-md-12">
                                                <div class="form-group">
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
    var defaultSettings = [];
    export default {
        name: 'defaultSettings',
        props: {
            scope: String,
            id: Number,
        },
        data() {
            return {
                form: new Form({
                    id: '',
                    cgst: '',
                    sgst: '',
                    igst: '',
                    tds: '',
                    service_tax_limit: '',
                })
            }
        },
        methods: {
            register () {
                this.form.post('/settings/default-settings/update')
                    .then(( response ) => {
                        window.location.href = '/settings/default-settings';
                })
            },
        },
        mounted() {
            axios.get(`/settings/default-settings/fetch-default-settings/${this.id}`)
            .then(response => {
                defaultSettings = response.data;

                this.form.cgst = defaultSettings.cgst;
                this.form.sgst = defaultSettings.sgst;
                this.form.igst = defaultSettings.igst;
                this.form.tds = defaultSettings.tds;
                this.form.service_tax_limit = defaultSettings.service_tax_limit;
            });
        },
    };
</script>