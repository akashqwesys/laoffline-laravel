<template>

    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 v-if="scope == 'edit'" class="nk-block-title page-title">Edit User Group</h3>
                                <h3 v-else class="nk-block-title page-title">Add User Group</h3>
                                <div class="nk-block-des text-soft">
                                    <p>Please fill the name and select permissions.</p>
                                </div>
                            </div><!-- .nk-block-head-content -->
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    <div class="nk-block">
                        <div class="card card-bordered">
                            <div class="card-inner">
                                <form action="#" class="form-validate" @submit.prevent="register()">
                                    <input type="hidden" v-if="scope == 'edit'" id="fv-group-id" v-model="form.id">
                                    <div class="row g-gs">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label" for="fv-group-name">Group Name</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" id="fv-group-name" v-model="form.name">
                                                    <span v-if="errors.group_name" class="invalid">{{errors.group_name}}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">Access Permission</label>
                                                <ul class="custom-control-group g-3 align-center custom-access-permission">
                                                    <li v-for="aPermission in aPermissions"  :key="aPermission.id">
                                                        <input type="checkbox" v-model="form.access_permission" :value="aPermission.id" :id="aPermission.oldname" required>
                                                        <label :for="aPermission.oldname">{{ aPermission.name }}</label>
                                                    </li>
                                                </ul>
                                            </div>
                                            <span v-if="errors.apermission" class="invalid">{{errors.apermission}}</span>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">Modify Permission</label>
                                                <ul class="custom-control-group g-3 align-center custom-modify-permission">
                                                    <li v-for="mPermission in mPermissions"  :key="mPermission.id">
                                                        <input class="custam-val" type="checkbox" checked v-model="form.modify_permission" :value="mPermission.id" :id="mPermission.oldname" required>
                                                        <label :for="mPermission.oldname">{{ mPermission.name }}</label>
                                                    </li>
                                                </ul>
                                            </div>
                                            <span v-if="errors.apermission" class="invalid">{{errors.mpermission}}</span>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <a v-bind:href="cancel_url" class="btn btn-dim btn-secondary">Cancel</a>
                                                <button type="submit" class="btn btn-primary">Save changes</button>
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
    import Form from 'vform';

    var accessPermission = [];
    var modifyPermission = [];
    var gData = [];
    export default {
        name: 'createUserGroup',
        props: {
            scope: String,
            id: Number,
        },
        data() {
            return {
                cancel_url: '/databank/users-group',
                aPermissions: [],
                mPermissions: [],
                errors: {
                    group_name: '',
                    apermission: '',
                    mpermission: '',
                },
                form: new Form({
                    id: '',
                    name: '',
                    access_permission: [],
                    modify_permission: [],
                })
            }
        },
        created() {
            axios.get('/databank/users-group/getPermission')
            .then(response => {
                response.data.forEach(function(item) {
                    if (item.name.split('-')[0].localeCompare('access') == 0) {
                        item.oldname = item.name.replace('access-', "ap-");
                        var accessUserGroup = item.name.replace('access-', "");
                        var userGroupName = accessUserGroup.replace(/-/g, ' ');
                        item.name = userGroupName;

                        accessPermission.push(item);
                    } else if (item.name.split('-')[0].localeCompare('modify') == 0) {
                        item.oldname = item.name.replace('modify-', "mp-");
                        var modifyUserGroup = item.name.replace('modify-', "");
                        var userGroupName = modifyUserGroup.replace(/-/g, ' ');
                        item.name = userGroupName;

                        modifyPermission.push(item);
                    }
                });
                this.aPermissions = accessPermission;
                this.mPermissions = modifyPermission;
            });
        },
        methods: {
            register () {
                if (this.scope == 'edit') {
                    this.form.post('/databank/users-group/update')
                        .then(( response ) => {
                            window.location.href = '/databank/users-group';
                    })
                    .catch((error) => {
                        var validationError = error.response.data.errors;

                        if(validationError.name) {
                            this.errors.group_name = validationError.name[0];
                        }
                        if(validationError.access_permission) {
                            this.errors.apermission = validationError.access_permission[0];
                        }
                        if(validationError.modify_permission) {
                            this.errors.mpermission = validationError.modify_permission[0];
                        }
                    })
                } else {
                    this.form.post('create')
                        .then(( response ) => {
                            window.location.href = '/databank/users-group';
                    })
                    .catch((error) => {
                        var validationError = error.response.data.errors;

                        if(validationError.name) {
                            this.errors.group_name = validationError.name[0];
                        }
                        if(validationError.access_permission) {
                            this.errors.apermission = validationError.access_permission[0];
                        }
                        if(validationError.modify_permission) {
                            this.errors.mpermission = validationError.modify_permission[0];
                        }
                    })
                }
            },
        },
        mounted() {
            switch (this.scope) {
                case 'edit' :
                    axios.get(`/databank/users-group/fetch-user-group/${this.id}`)
                    .then(response => {
                        gData = response.data;

                        this.form.id = gData.id;
                        this.form.name = gData.name;
                        this.form.access_permission = JSON.parse(gData.access_permissions);
                        this.form.modify_permission = JSON.parse(gData.modify_permissions);
                    });
                    break;
                default:
                    break;
            }
        },
    };
</script>

<style>
    ul.custom-control-group.g-3.align-center.custom-access-permission,
    ul.custom-control-group.g-3.align-center.custom-modify-permission {
        display: block !important;
        column-count: 4;
        column-gap: 16px;
    }

    @media(min-width: 768px) and (max-width: 991px) {
        ul.custom-control-group.g-3.align-center.custom-access-permission,
        ul.custom-control-group.g-3.align-center.custom-modify-permission {
            column-count: 3;
        }
    }

    @media(min-width: 481px) and (max-width: 767px) {
        ul.custom-control-group.g-3.align-center.custom-access-permission,
        ul.custom-control-group.g-3.align-center.custom-modify-permission {
            column-count: 2;
        }
    }

    @media(max-width: 480px) {
        ul.custom-control-group.g-3.align-center.custom-access-permission,
        ul.custom-control-group.g-3.align-center.custom-modify-permission {
            column-count: 1;
        }
    }

    input[type=checkbox] + label {
        display: block;
        margin: 0.2em;
        cursor: pointer;
        padding: 0.2em;
        text-transform: capitalize;
    }

    input[type=checkbox] {
        display: none;
    }

    input[type=checkbox] + label:before {
        content: "\2714";
        border: 2px solid #dbdfea;
        border-radius: 0.2em;
        display: inline-block;
        width: 25px;
        height: 25px;
        padding-left: 5px;
        padding-bottom: 5px;
        margin-right: 15px;
        vertical-align: bottom;
        color: transparent;
        transition: .2s;
    }

    input[type=checkbox] + label:active:before {
        transform: scale(0);
    }

    input[type=checkbox]:checked + label:before {
        background-color: #6576ff;
        border-color: #6576ff;
        color: #fff;
    }
</style>
