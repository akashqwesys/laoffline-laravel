<template>
    <div class="nk-content ">
        <vue-loader v-if="showLoader"></vue-loader>
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">Link Companies Lists</h3>
                                <div class="nk-block-des text-soft">
                                    <p>You have total {{linkCompanies.length}} list of link companies.</p>
                                </div>
                            </div><!-- .nk-block-head-content -->
                            <div class="nk-block-head-content">
                                <div class="toggle-wrap nk-block-tools-toggle">
                                    <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                    <div class="toggle-expand-content" data-content="pageMenu">
                                        <ul class="nk-block-tools g-3">                                            
                                            <li class="nk-block-tools-opt">
                                                <a v-bind:href="create_employee" class="dropdown-toggle btn btn-icon btn-primary"><em class="icon ni ni-plus"></em></a>
                                            </li>
                                        </ul>
                                    </div>
                                </div><!-- .toggle-wrap -->
                            </div><!-- .nk-block-head-content -->
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    <div class="nk-block">
                        <div class="card card-bordered card-stretch">
                            <div class="card-inner">
                                <table id="linkCompanies" :class="excelAccess == 1 ? 'datatable-init-export table' : 'datatable-init table'" :data-export-title="excelAccess == 1 ? 'Export' : ''">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Company</th>
                                            <th>Link With Company</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(linkCompany, index) in linkCompanies" :key="index">
                                            <td>{{ index + 1 }}</td>
                                            <td>
                                                <a href="#" @click="view_data(linkCompany.company.id)">{{ linkCompany.company.company_name }}</a>
                                            </td>
                                            <td>
                                                <a href="#" @click="view_data(linkCompany.company.id)">{{ linkCompany.link_company.company_name }}</a>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-primary" data-toggle="modal" :data-target="getDataTarget(linkCompany.linkId)" title="Merge company"  v-on:click="showModel(linkCompany.linkId, linkCompany.company.id)">Merge</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div><!-- .card -->
                        </div>
                    </div><!-- .nk-block -->
                </div>
            </div>
        </div>

        <div class="modal fade" :id="modalId">
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
                                                <multiselect v-model="form.link_company_id" :options="companies" placeholder="Select one" label="company_name" track-by="company_name" :multiple="true" :taggable="true"></multiselect>
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
    </div>
</template>

<script>
    import Multiselect from 'vue-multiselect';
    import VueLoader from './../../../VueLoader';

    export default {
        name: 'linkCompany',
        props: {
            excelAccess: Number,
        },
        components: {
            Multiselect,
            VueLoader,
        },
        data() {
            return {
                create_employee: 'link-company/create-link-company',
                companies: [],
                linkCompanies: [],
                linkId: 0,
                modalId: '',
                showLoader:false,
                form: new Form({
                    company_id: '',
                    link_company_id: [],
                })
            }
        },
        created() {
            this.showLoader = true;
            axios.get('./link-company/list')
            .then(response => {
                this.linkCompanies = response.data;
                this.showLoader = false;
            });
        },
        methods: {
            getDataTarget(id) {
                return '#mergeCompany' + id;
            },
            showModel(id, companyId) {
                var mId = '#mergeCompany'+id;
                this.modalId = 'mergeCompany'+id;

                axios.get('./link-company/getComapnyById/'+companyId)
                .then(response => {
                    this.companies.push({
                        id: response.data.id,
                        company_name: response.data.company_name
                    });

                    axios.get('./link-company/getLinkedComapnyById/'+companyId)
                    .then(result => {
                        var linkCompany = [];
                        result.data.forEach(element => {
                            this.companies.push(element.linkedCompanies);
                        });
                    });
                });
                
                $(mId).modal('show');
            },
            edit_data(id){
                window.location.href = './link-company/edit-link-company/'+id;
            },
            view_data(id){
                window.location.href = './companies/view-company/'+id;
            },
            register () {
                console.log(this.form);
                this.form.post('/databank/link-company/merge')
                    .then(( response ) => {
                        // window.location.href = '/databank/link-company';
                })
            },
        },
        mounted() {
        },
    };
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
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