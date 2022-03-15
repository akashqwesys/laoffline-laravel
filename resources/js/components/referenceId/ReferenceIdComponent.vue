<template>
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">Reference Details</h3>
                            </div><!-- .nk-block-head-content -->
                            <div class="nk-block-head-content">
                                <div class="toggle-wrap nk-block-tools-toggle">
                                    <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                    <div class="toggle-expand-content" data-content="pageMenu">
                                        <ul class="nk-block-tools g-3">
                                            <li class="nk-block-tools-opt">
                                                <a v-bind:href="create_reference" class="dropdown-toggle btn btn-icon btn-primary"><em class="icon ni ni-plus"></em></a>
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
                                <table class="datatable-init-export nowrap table" data-export-title="Export">
                                    <thead>
                                        <tr>
                                            <th>Ref No</th>
                                            <th>Date Added</th>
                                            <th>Receive Date</th>
                                            <th>Ref. Via</th>
                                            <th>Company</th>
                                            <th>Gen. by</th>
                                            <th>In/Out</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(referenceId, index) in referenceId" :key="index">
                                            <td>{{ index + 1 }}</td>
                                            <td>{{ referenceId.date_added }}</td>
                                            <td>{{ referenceId.selection_date }}</td>
                                            <td v-if="referenceId.type_of_inward == 'Hand'" title="Hand"><em class="icon ni ni-thumbs-up"></em></td>
                                            <td v-else-if="referenceId.type_of_inward == 'Call'" title="Call"><em class="icon ni ni-call"></em></td>
                                            <td v-else-if="referenceId.type_of_inward == 'Message'" title="Message"><em class="icon ni ni-emails"></em></td>
                                            <td v-else-if="referenceId.type_of_inward == 'Whatsapp'" title="Whatsapp"><em class="icon ni ni-whatsapp"></em></td>
                                            <td v-else-if="referenceId.type_of_inward == 'Email'" title="Email"><em class="icon ni ni-emails-fill"></em></td>
                                            <td v-else-if="referenceId.type_of_inward == 'Courier'" title="Courier"><em class="icon ni ni-mail-fill"></em></td>
                                            <!-- <td><a href="#" @click="companydetails(referenceId.company_id)">{{ referenceId.company_name }} </a></td> -->
                                            <td><a v-bind:href="'#'+ referenceId.company_name" data-toggle="modal">{{ referenceId.company_name }} </a></td>
                                            <td>{{ referenceId.firstname }}</td>
                                            <td v-if="referenceId.inward_or_outward == '1'">Inward</td>
                                            <td v-else>Outward</td>
                                            <td>
                                                <div class="dropdown">
                                                    <a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-xs">
                                                        <ul class="link-list-plain">
                                                            <li><a href="#" @click="view_data(referenceId.reference_id)"><em class="icon ni ni-eye"></em><span>View</span></a></li>
                                                            <li><a href="#" @click="edit_data(referenceId.reference_id)"><em class="icon ni ni-edit-alt"></em><span>update</span></a></li>
                                                            <li><a href="#" @click="delete_data(referenceId.reference_id)"><em class="icon ni ni-trash"></em><span>Remove</span></a></li>
                                                        </ul>
                                                    </div>
                                                </div>
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
        <ViewCompany></ViewCompany>
    </div>
</template>


<script>
import ViewCompany from './modal/viewCompanyComponent';
    export default {
        name: 'referenceId',
        components: {
            ViewCompany,
            // CompanyDetail,
        },
        data() {
            return {
                referenceId: [],
                company: [],
                create_reference: 'reference/create-reference',
            }
        },
        created() {
            axios.get('./reference/list')
            .then(response => {
                this.referenceId = response.data;
            });
        },
         methods: {
            view_data(id){
                window.location.href = './reference/view-reference/'+id;
            },
            edit_data(id){
                window.location.href = './reference/edit-reference/'+id;
            },
            delete_data(id){
                axios.delete('./reference/referenceId/delete/'+id)
                .then(response => {
                    location.reload();
                });
            },
            companydetails(id){
                window.location.href = '/reference/companydata/'+ id;
            },
        },
        mounted() {
        },
    };

</script>
<style>
    #companydetail .modal-dialog {
        max-width: 1000px;
    }
</style>
