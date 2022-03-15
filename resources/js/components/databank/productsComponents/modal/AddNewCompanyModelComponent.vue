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
                                        <label class="form-label" for="fv-company-type">Company Type</label>
                                        <div>
                                            <multiselect v-model="form.company_type" :options="companyTypes" placeholder="Select one" label="name" track-by="name"></multiselect>
                                        </div>
                                    </div>
                                </div> 
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label" for="fv-name">Name</label>
                                        <div class="form-control-wrap">
                                            <input type="text" class="form-control" id="fv-name" v-model="form.name" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr class="preview-hr">
                            <span class="preview-title-lg overline-title">Owner Details</span>
                            <div class="row gy-4">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label" for="fv-owner-name">Name</label>
                                        <div class="form-control-wrap">
                                            <input type="text" class="form-control" id="fv-owner-name" v-model="form.owner_name" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label" for="fv-owner-mobile">Mobile</label>
                                        <div class="form-control-wrap">
                                            <input type="text" class="form-control" id="fv-owner-mobile" v-model="form.owner_mobile" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label" for="fv-owner-email">Email</label>
                                        <div class="form-control-wrap">
                                            <input type="text" class="form-control" id="fv-owner-email" v-model="form.owner_email" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr class="preview-hr">
                            <div class="row gy-4">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label" for="fv-country">Country</label>
                                        <div>
                                            <multiselect v-model="form.country" :options="countries" placeholder="Select one" label="name" track-by="name"></multiselect>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label" for="fv-city">City</label>
                                        <div>
                                            <multiselect v-model="form.city" :options="cities" placeholder="Select one" label="name" track-by="name"></multiselect>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label" for="fv-company_about">About Company</label>
                                        <div class="form-control-wrap">
                                            <textarea class="form-control" id="fv-company_about" v-model="form.about_company"></textarea>
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

    export default {
        name: 'createCompany',
        props: {
            ctype: String,
            id: Number,
        },
        components: { 
            Multiselect
        },
        data() {
            return {
                countries: [],
                cities: [],
                companyTypes: [],
                form: new Form({
                    name: '',
                    company_type: '',
                    owner_name: '',
                    owner_mobile: '',
                    owner_email: '',
                    country: '',
                    city: '',
                    about_company: '',
                })
            }
        },
        created() {
            axios.get('/settings/companyType/list-data')
            .then(response => {
                console.log(this.ctype);
                var comType = [];
                response.data.forEach(element => {
                    if(this.ctype == '1-2') {
                        if (element.id != 3) {
                            comType.push(element);
                        }
                    } else if(this.ctype == '3') {
                        if (element.id == 3) {
                            comType.push(element);
                        }
                    } else if(this.ctype == '') {
                        comType.push(element);
                    }
                });
                this.companyTypes = comType;          
            });
            axios.get('/databank/catalog/list-country')
            .then(response => {
                this.countries = response.data;
            });
            axios.get('/databank/catalog/list-cities')
            .then(response => {
                this.cities = response.data;
            });
            axios.get('/databank/catalog/list-state')
            .then(response => {
                this.states = response.data;
            });
        },
        methods: {
            register () {
                this.form.post('/databank/catalog/create-company')
                    .then(( response ) => {
                        $('#addCompany').hide();
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
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<style>
    #addCompany .modal-dialog{
        max-width: 920px;
    }
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