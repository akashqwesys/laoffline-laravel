<template>
    
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 v-if="scope == 'edit'" class="nk-block-title page-title">Edit Fabric Group</h3>
                                <h3 v-else class="nk-block-title page-title">Add Fabric Group</h3>
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
    var fabricGroups = [];
    export default {
        name: 'createFabricGroup',
        props: {
            scope: String,
            id: Number,
        },
        data() {
            return {
                cancel_url: '/settings/fabricGroup',
                form: new Form({
                    id: '',
                    name: '',
                })
            }
        },
        methods: {
            register () {
                if (this.scope == 'edit') {
                    this.form.post('/settings/fabricGroup/update')
                        .then(( response ) => {
                            window.location.href = '/settings/fabricGroup';
                    })
                } else {
                    this.form.post('/settings/fabricGroup/create')
                        .then(( response ) => {
                            window.location.href = '/settings/fabricGroup';
                    })
                }
            },
        },
        mounted() {
            switch (this.scope) {
                case 'edit' :
                    axios.get(`/settings/fabricGroup/fetch-fabricGroup/${this.id}`)
                    .then(response => {
                        fabricGroups = response.data;

                        this.form.id = fabricGroups.id;
                        this.form.name = fabricGroups.name;
                    });
                    break;
                default:
                    break;
            }
        },
    };
</script>