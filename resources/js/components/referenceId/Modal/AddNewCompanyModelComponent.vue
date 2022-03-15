<template>
    <div class="modal fade" id="addCompany">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Company</h5>
                    <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                        <em class="icon ni ni-cross"></em>
                    </a>
                </div>
                <div class="modal-body">
                    <form action="#" @submit.prevent="register()">
                        <div class="preview-block">
                            <div class="row gy-4">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label" for="fv-name">Name</label>
                                        <div class="form-control-wrap">
                                            <input type="text" class="form-control" id="fv-name" v-model="form.name" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label" for="fv-first-name">Company Type</label>
                                            <div>
                                                <select v-model="form.type"  class="form-control" id="fv-company-type">
                                                    <option>Select Company Type</option>
                                                    <option value="General">General</option>
                                                    <option value="Customer">Customer</option>
                                                    <option value="Supplier">Supplier</option>
                                                </select>
                                            </div>
                                    </div>
                                </div>
                            </div>
                            <hr class="preview-hr">
                            <div class="preview-block">
                                <div class="row gy-4">
                                    <div class="col-md-12">
                                            <h5 class="modal-title">Contact Details</h5>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label" for="fv-name">Name</label>
                                            <div class="form-control-wrap">
                                                <input type="text" class="form-control" id="fv-name" v-model="form.Contactname" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label" for="fv-first-name">Designation</label>
                                            <div class="form-control-wrap">
                                                <multiselect v-model="form.designationList" :options="designationList" placeholder="Select one" label="name" track-by="id" :multiple="true" :taggable="true"></multiselect>
                                            </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label" for="fv-name">Mobile Number</label>
                                            <div class="form-control-wrap">
                                                <input type="text" class="form-control" id="fv-mobile-num" v-model="form.contact" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label" for="fv-name">Email</label>
                                            <div class="form-control-wrap">
                                                <input type="email" class="form-control" id="fv-email" v-model="form.email" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr class="preview-hr">
                             <div class="preview-block">
                                <div class="row gy-4">
                                    <div class="col-md-4">
                                        <div>
                                            <label class="form-label" for="fv-company">Country</label>
                                                <div>
                                                    <multiselect v-model="form.country" :options="country" placeholder="Select one" label="name" track-by="id"></multiselect>
                                                </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div>
                                            <label class="form-label" for="fv-company">State</label>
                                                <div>
                                                    <multiselect v-model="form.state" :options="state" placeholder="Select one" label="name" track-by="id"></multiselect>
                                                </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div>
                                            <label class="form-label" for="fv-company">City</label>
                                                <div>
                                                    <multiselect v-model="form.city" :options="city" placeholder="Select one" label="name" track-by="id"></multiselect>
                                                </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row gy-4">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label" for="fv-name">Landline Number (Please enter multiple landline no with comma seperated type. (Example : 123456, 456789))</label>
                                            <div class="form-control-wrap">
                                                <input type="text" class="form-control" id="fv-mobile-num" v-model="form.num" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label" for="fv-name">About Company</label>
                                            <div class="form-control-wrap">
                                                <textarea type="text" class="form-control" id="fv-mobile-num" v-model="form.company"> </textarea>
                                            </div>
                                        </div>
                                    </div>
                            </div>
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
    export default {
        name: 'country',
        components: {
            Multiselect,
        },
        data() {
            return {
                country:[],
                state:[],
                city:[],
                designationList:[],
                form: new Form({
                    name:'',
                    type:'',
                    Contactname:'',
                    email:'',
                    designationList:'',
                    contact:'',
                    country:'',
                    state:'',
                    city:'',
                    num:'',
                    company:'',
                }),
            }
        },
        created() {
            axios.get('/settings/cities/list-country')
            .then(response => {
                this.country = response.data;
            });
            axios.get('/settings/cities/list-state')
            .then(response => {
                this.state = response.data;
            });
            axios.get('/settings/cities/list-data')
            .then(response => {
                this.city = response.data;
            });
            axios.get('/settings/designation/list-data')
            .then(response => {
                this.designationList = response.data;
            });
        },
        methods: {
            register() {
                this.form.post('/reference/create-reference/createCompany')
                    .then(( response ) => {
                         window.location.href = '/reference/create-reference/';
                })
            },
        },
        mounted() {
        },
    };
</script>
<style>
    #addCompany .modal-dialog {
        max-width: 1000px;
    }
</style>
<style>
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
        position: unset;
    }
    .multiselect__tag-icon:focus, .multiselect__tag-icon:hover {
        background: #ebeef2;
    }
    .multiselect__tag-icon:focus:after, .multiselect__tag-icon:hover:after {
        color: #526484;
    }
</style>
