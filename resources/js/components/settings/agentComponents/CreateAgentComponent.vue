<template>    
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 v-if="scope == 'edit'" class="nk-block-title page-title">Edit Agent</h3>
                                <h3 v-else class="nk-block-title page-title">Add Agent</h3>
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
                                                    <label class="form-label" for="fv-pan_no">Pan No</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="fv-pan_no" v-model="form.pan_no" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-gst_no">GST No</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="fv-gst_no" v-model="form.gst_no" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="preview-block">
                                                    <label class="form-label">Default</label>
                                                    <ul class="custom-control-group g-3 align-center">
                                                        <li>
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" class="custom-control-input" name="default" v-model="form.default" id="fv-yes" value="1" >
                                                                <label class="custom-control-label" for="fv-yes">Yes</label>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" class="custom-control-input" name="default" v-model="form.default" id="fv-no" value="0" >
                                                                <label class="custom-control-label" for="fv-no">No</label>
                                                            </div>
                                                        </li>
                                                    </ul>
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
    var agents = [];
    export default {
        name: 'createAgent',
        props: {
            scope: String,
            id: Number,
        },
        data() {
            return {
                cancel_url: '/settings/agent',
                form: new Form({
                    id: '',
                    name: '',
                    pan_no: '',
                    gst_no: '',
                    default: '',
                })
            }
        },
        methods: {
            register () {
                if (this.scope == 'edit') {
                    this.form.post('/settings/agent/update')
                        .then(( response ) => {
                            window.location.href = '/settings/agent';
                    })
                } else {
                    this.form.post('/settings/agent/create')
                        .then(( response ) => {
                            window.location.href = '/settings/agent';
                    })
                }
            },
        },
        mounted() {
            switch (this.scope) {
                case 'edit' :
                    axios.get(`/settings/agent/fetch-agent/${this.id}`)
                    .then(response => {
                        agents = response.data;

                        this.form.id = agents.id;
                        this.form.name = agents.name;
                        this.form.pan_no = agents.pan_no;
                        this.form.gst_no = agents.gst_no;
                        this.form.default = agents.default;
                    });
                    break;
                default:
                    break;
            }
        },
    };
</script>