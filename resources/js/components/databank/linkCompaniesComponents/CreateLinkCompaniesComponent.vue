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
                                                        <multiselect v-model="form.company_id" :options="companies" placeholder="Select one" label="company_name" track-by="company_name" @select="getCompanyDetails"></multiselect>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-link_companies_id">Link Companies</label>
                                                    <div v-if="linkCompanies == 1">
                                                        <multiselect v-model="form.link_companies_id" :options="left_companies" placeholder="Select one" label="company_name" track-by="company_name"></multiselect>
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
    import Form from 'vform';

    export default {
        name: 'createLinkCompany',
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
                left_companies: [],
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
                    axios.get(`/databank/link-company/getCompanyById/${event.id}`)
                    .then(response => {
                        this.companyDetails = response.data;
                        if(this.companyDetails.length != 0) {
                            this.linkCompanies = 1;
                            axios.get(`/databank/link-company/getLinkedCompanyById/${event.id}`)
                            .then(result => {
                                this.linkedCompaniesLists = result.data[0];
                                this.left_companies = result.data[1];
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
<style scoped>
    span.form-note {
        margin-top: 10px;
    }
</style>
