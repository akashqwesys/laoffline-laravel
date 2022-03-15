<template>    
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">Link Companies</h3>
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
                                    <div class="preview-block">
                                        <span class="preview-title-lg overline-title">Link Up Companies</span>
                                        <div class="row gy-4">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-company_id">Company</label>
                                                    <div>
                                                        <multiselect v-model="form.company_id" :options="companies" placeholder="Select one" label="company_name" track-by="company_name" @input="getCompanyDetails"></multiselect>
                                                    </div>
                                                </div>
                                            </div>                                            
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-link_companies_id">Link Companies</label>
                                                    <div v-if="linkCompanies == 1">
                                                        <multiselect v-model="form.link_companies_id" :options="companies" placeholder="Select one" label="company_name" track-by="company_name"></multiselect>
                                                    </div>
                                                    <div v-if="linkedCompaniesList == 1">
                                                        <span class="form-note" v-for="(linkCompany, index) in linkedCompaniesLists" :key="index">{{ linkCompany.linkedCompanies.company_name }}</span>
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
    import Multiselect from 'vue-multiselect';

    export default {
        name: 'createUserGroup',
        components: { 
            Multiselect
        },
        data() {
            return {
                cancel_url: '/databank/link-company',
                linkCompanies: 0,
                companyDetails: '',
                linkedCompaniesList: 0,
                linkedCompaniesLists: [],
                companies: [],
                form: new Form({
                    id: '',
                    company_id: '',
                    link_companies_id: '',
                })
            }
        },
        created() {
            axios.get('/databank/link-company/listCompanies')
            .then(response => {
                this.companies = response.data;
            });
        },
        methods: {
            getCompanyDetails: function(event) {
                if(event != null) {
                    axios.get(`/databank/link-company/getComapnyById/${event.id}`)
                    .then(response => { 
                        this.companyDetails = response.data;
                        if(this.companyDetails.length != 0) {
                            this.linkCompanies = 1;
                            axios.get(`/databank/link-company/getLinkedComapnyById/${event.id}`)
                            .then(result => { 
                                this.linkedCompaniesLists = result.data;
                                console.log(this.linkedCompaniesLists);
                                if(this.linkedCompaniesLists.length != 0) {
                                    this.linkedCompaniesList = 1;
                                }
                            });
                        }
                    });
                }
            },
            register () {
                this.form.post('/databank/link-company/create')
                    .then(() => {
                        window.location.href = '/databank/link-company';
                })
            },
        },
        mounted() {
        },
    };
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<style>
    span.form-note {
        margin-top: 10px;
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
    }
    .multiselect__tag-icon:focus, .multiselect__tag-icon:hover {
        background: #ebeef2;
    }
    .multiselect__tag-icon:focus:after, .multiselect__tag-icon:hover:after {
        color: #526484;
    }
</style>