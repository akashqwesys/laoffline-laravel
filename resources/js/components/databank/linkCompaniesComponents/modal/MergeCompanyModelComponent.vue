<template>
    <div class="modal fade" :id="'mergeCompany1'">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Merge Company</h5>
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
                                        <label class="form-label" for="fv-main-company">Main Company</label>
                                        <div>
                                            <multiselect v-model="form.company_id" :options="companies" placeholder="Select one" label="company_name" track-by="company_name"></multiselect>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label" for="fv-link-companies">Link Companies</label>
                                        <div>
                                            <multiselect v-model="form.link_company_id" :options="companies" placeholder="Select one" label="company_name" track-by="company_name"></multiselect>
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
        name: 'mergeCompanies',
        components: {
            Multiselect,
        },
        data() {
            return {
                cancel_url: '/databank/link-company',
                companies: [],
                linkCompanies: [],
                linkId: 1,
                form: new Form({
                    id: '',
                    company_id: '',
                    link_compny_id: '',
                })
            }
        },
        created() {
            axios.get('./link-company/list')
            .then(response => {
                this.linkCompanies = response.data;
            });
        },
        methods: {
            register () {
                this.form.post('/databank/link-company/create')
                    .then(( response ) => {
                        window.location.href = '/databank/link-company';
                })
            },
        },
        mounted() {

        },
    };
</script>

