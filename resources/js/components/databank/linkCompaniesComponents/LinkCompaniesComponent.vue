<template>
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block">
                        <div class="card card-bordered card-stretch">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <h3 class="nk-block-title page-title">Link Companies Lists</h3>
                                        <div class="nk-block-des text-soft">
                                            <p>You have total <span id="totalLinkCompanies"></span> list of link companies.</p>
                                        </div>
                                    </div>
                                    <div class="col-md-8 text-right">
                                        <a v-bind:href="create_employee" class="dropdown-toggle btn btn-icon btn-primary mx-2"><em class="icon ni ni-plus"></em></a>
                                        <button @click="clearallfilter" class="btn btn-dark px-2">Clear</button>
				                    </div>
                                </div>

                            </div>
                            <div class="card-inner">
                                    <table id="linkCompanies" class="table table-hover table-bordered">
                                    <thead>
                                        <tr>
                                            <!-- <th>No</th> -->
                                            <th style="width: 30%;" >Company</th>
                                            <th>Link With Company</th>
                                            <!-- <th>Action</th> -->
                                        </tr>
                                    </thead>
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
    <ViewCompanyDetails ref="company"></ViewCompanyDetails>
</template>

<script>
    import 'jquery/dist/jquery.min.js';
    import 'datatables.net-bs5/js/dataTables.bootstrap5';
    import 'datatables.net-responsive-bs4/js/responsive.bootstrap4';
    import "datatables.net-buttons-bs5/js/buttons.bootstrap5";
    import "datatables.net-buttons/js/buttons.flash.js";
    import "datatables.net-buttons/js/buttons.html5.js";
    import "datatables.net-buttons/js/buttons.print.js";
    import $ from 'jquery';
    import Form from 'vform';
    import Multiselect from 'vue-multiselect';
    import ViewCompanyDetails from '../companyComponents/modal/ViewCompanyDetailsModelComponent.vue';

    export default {
        name: 'linkCompany',
        props: {
            excelAccess: Number,
        },
        components: {
            Multiselect,
            ViewCompanyDetails,
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
        methods: {
            getDataTarget(id) {
                return '#mergeCompany' + id;
            },
            showModel(id, companyId) {
                var mId = '#mergeCompany'+id;
                this.modalId = 'mergeCompany'+id;
                this.companies = [];
                axios.get('./link-company/getCompanyById/'+companyId)
                .then(response => {
                    this.companies.push({
                        id: response.data.id,
                        company_name: response.data.company_name
                    });

                    axios.get('./link-company/getLinkedCompanyById/'+companyId)
                    .then(result => {
                        var linkCompany = [];
                        result.data.forEach(element => {
                            this.companies.push(element.linkedCompanies);
                        });
                    });
                });

                window.$(mId).modal('show');
            },
            edit_data(id){
                window.location.href = './link-company/edit-link-company/'+id;
            },
            view_data(id){
                window.location.href = './companies/view-company/'+id;
            },
            register () {
                this.form.post('/databank/link-company/merge')
                .then(( response ) => {
                    // window.location.href = '/databank/link-company';
                })
            },
            showModal: function (id) {
                window.$('#overlay').show();
                this.$refs.company.fetch_company(id)
                window.$("#viewCompany1").modal('show');
                $('<div class="modal-backdrop fade show"></div>').appendTo(document.body);
                $('body').addClass('modal-open').css('overflow', 'hidden').css('padding-right', '17px');
            },
            closeModal: function () {
                window.$('#viewCompany1').modal('hide');
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open').removeAttr('style');
            },
            clearallfilter(event) {
                $('input[type=search]').val('');
                $('#linkCompanies').DataTable().search("").draw();
            },
        },
        mounted() {
            const self = this;
            var buttons = [];
            var dt_table = null;
            if(this.excelAccess == 1) {
                buttons = [{
                    extend: 'excelHtml5',
                    action: exportAllRecords,
                    exportOptions: {
                        columns: ':not(:last-child)'
                    }
                }, {
                    extend: 'pdfHtml5',
                    action: exportAllRecords,
                    exportOptions: {
                        columns: ':not(:last-child)'
                    }
                },
                'print'];
            }
            function init_dt_table () {
                dt_table = $('#linkCompanies').DataTable({
                    // responsive: true,
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "./link-company/list-js",
                        data: function (data) {
                            if ($('#linkCompanies_filter input').val() == '') {
                                data.search.value = '';
                            }
                        },
                        complete: function (data) {
                            $('#totalLinkCompanies').text(data.responseJSON.iTotalRecords);
                        }
                    },
                    // pagingType: 'full_numbers',
                    // pageLength: 25,
                    paging: false,
                    dom: 'Bfrtip',
                    dom: "<'row'<'col-sm-12 col-md-4'l><'col-sm-12 col-md-4'f><'col-sm-12 col-md-4'B>>" +
                        "<'row'<'col-sm-12'tr>>" +
                        "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                    columns: [
                        // { data: 'id' },
                        { data: 'company_id' },
                        { data: 'link_companies_id' },
                        // { data: 'action', orderable: false },
                    ],
                    search: {
                        return: true
                    },
                    buttons: buttons
                });
                dt_table.on( 'responsive-resize', function ( e, datatable, columns ) {
                    var count = columns.reduce( function (a,b) {
                        return b === false ? a+1 : a;
                    }, 0 );
                } );
            }
            init_dt_table();
            function exportAllRecords(e, dt, button, config) {
                var self = this;
                var oldStart = dt.settings()[0]._iDisplayStart;
                dt.one('preXhr', function (e, s, data) {
                    // Just this once, load all data from the server...
                    data.start = 0;
                    data.length = 'all';
                    dt.one('preDraw', function (e, settings) {
                        // Call the original action function
                        if (button[0].className.indexOf('buttons-excel') >= 0) {
                            $.fn.dataTable.ext.buttons.excelHtml5.available(dt, config) ?
                                $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config) :
                                $.fn.dataTable.ext.buttons.excelFlash.action.call(self, e, dt, button, config);
                        } else if (button[0].className.indexOf('buttons-pdf') >= 0) {
                                $.fn.dataTable.ext.buttons.pdfHtml5.available(dt, config) ?
                                $.fn.dataTable.ext.buttons.pdfHtml5.action.call(self, e, dt, button, config) :
                                $.fn.dataTable.ext.buttons.pdfFlash.action.call(self, e, dt, button, config);
                        }
                        dt.one('preXhr', function (e, s, data) {
                            // DataTables thinks the first item displayed is index 0, but we're not drawing that.
                            // Set the property to what it was before exporting.
                            settings._iDisplayStart = oldStart;
                            data.start = oldStart;
                        });
                        // Reload the grid with the original page. Otherwise, API functions like table.cell(this) don't work properly.
                        setTimeout(dt.ajax.reload, 10);
                        // Prevent rendering of the full data to the DOM
                        return false;
                    });
                });
                // Requery the server with the new one-time export settings
                dt.ajax.reload();
            }
            var draw = 1;
            $(document).on('keyup', '#linkCompanies_filter input', function(e) {
                if ($(this).val() == '') {
                    if (draw == 0) {
                        dt_table.clear().draw();
                        draw = 1;
                    }
                } else {
                    draw = 0;
                }
            });

            $(document).on('click', '.showModal', function(e) {
                self.showModel($(this).attr('data-id'), $(this).attr('data-company'));
            });

            $(document).on('click', '.view-details', function (e) {
                self.showModal($(this).attr('data-id'));
            });

            document.getElementById('viewCompany1').addEventListener('hidden.bs.modal', function (event) {
                $('.modal-backdrop').remove();
            });
        },
    };
</script>

