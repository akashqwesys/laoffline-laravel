<template>
    <div class="modal fade" id="AddPerson">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Person Details</h5>
                    <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                        <em class="icon ni ni-cross"></em>
                    </a>
                </div>
                <div class="modal-body">
                    <form action="#" @submit.prevent="register()">
                        <div class="preview-block">
                            <div class="preview-block">
                                <div class="row gy-4">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label" for="fv-name">Name</label>
                                            <div class="form-control-wrap">
                                                <input type="text" class="form-control" id="fv-name" v-model="form.Contactname" placeholder="Enter Name" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label" for="fv-name">Mobile Number</label>
                                            <div class="form-control-wrap">
                                                <input type="text" class="form-control" id="fv-mobile-num" v-model="form.contact" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label" for="fv-name">Email</label>
                                            <div class="form-control-wrap">
                                                <input type="email" class="form-control" id="fv-email" v-model="form.email" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="fv-first-name">Company</label>
                                            <div class="form-control-wrap">
                                                <multiselect v-model="form.companyList" :options="company" placeholder="Select one" label="company_name" track-by="id"></multiselect>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="fv-first-name">Designation</label>
                                            <div class="form-control-wrap">
                                                <multiselect v-model="form.designationList" :options="designationList" placeholder="Select one" label="name" track-by="id" :multiple="true" :taggable="true"></multiselect>
                                            </div>
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
import Multiselect from 'vue-multiselect';
import Form from 'vform';
    export default {
        name: 'Country',
        components: {
            Multiselect,
        },
        data() {
            return {
                designationList:[],
                company:[],
                form: new Form({
                    Contactname:'',
                    companyList:'',
                    designationList:[],
                    contact:'',
                    email:'',
                }),
            }
        },
        created() {
            axios.get('/reference/companysearch')
            .then(response => {
                this.company = response.data;
            });
            axios.get('/reference/designation')
            .then(response => {
                this.designationList = response.data;
            });
        },
        methods: {
            register() {
                this.form.post('/reference/create-reference/createPerson')
                    .then(( response ) => {
                        window.location.href = '/reference/create-reference';
                })
            }
        },
        mounted() {
        },
    };
</script>
<style>
    #AddPerson .modal-dialog {
        max-width: 1000px;
    }
</style>
<style>
</style>
