<template>
    <VueLoader></VueLoader>
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
                                <!-- <div class="table-responsive"></div> -->
                                <table id="referenceTable" class="table table-hover table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Ref No</th>
                                            <th>Date Added</th>
                                            <th>Receive Date</th>
                                            <th>Ref. Via</th>
                                            <th>Company</th>
                                            <!-- <th v-if="employeeId != 15">Gen. By</th> -->
                                            <th>Gen. By</th>
                                            <th>In/Out</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div><!-- .card -->
                        </div>
                    </div><!-- .nk-block -->
                </div>
            </div>
        </div>
        <ViewCompanyDetails ref="company"></ViewCompanyDetails>
    </div>
</template>

<script>
    import ViewCompanyDetails from '../databank/companyComponents/modal/ViewCompanyDetailsModelComponent.vue';
    import VueLoader from './../../VueLoader.vue';

    import $ from 'jquery';
    import 'datatables.net-responsive-bs4/js/responsive.bootstrap4';
    import "datatables.net-buttons-bs5/js/buttons.bootstrap5";
    import 'pdfmake/build/pdfmake';
    import "datatables.net-buttons/js/buttons.html5";
    import "datatables.net-buttons/js/buttons.print";

    export default {
        name: 'referenceId',
        components: {
            ViewCompanyDetails,
            VueLoader
        },
        props: {
            employeeId: Number,
        },
        data() {
            return {
                referenceId: [],
                company: [],
                create_reference: 'reference/create-reference',
            }
        },
        created() {
            // axios.get('./reference/list')
            // .then(response => {
            //     this.referenceId = response.data;
            // });
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
            showModal: function(id) {
                window.$('#overlay').show();
                this.$refs.company.fetch_company(id)
                window.$("#viewCompany1").modal('show');
                $('<div class="modal-backdrop fade show"></div>').appendTo(document.body);
                $('body').addClass('modal-open').css('overflow', 'hidden').css('padding-right', '17px');
            },
            closeModal: function() {
                window.$('#viewCompany1').modal('hide');
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open').removeAttr('style');
            }
        },
        mounted() {
            const self = this;
            var dt_table = null;
            function init_dt_table () {
                dt_table = $('#referenceTable').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    ajax: {
                        url: "./reference/list",
                        data: function (data) {
                            if ($('#ref_no').val() == '') {
                                data.columns[0].search.value = '';
                            } else {
                                data.columns[0].search.value = $('#ref_no').val();
                            }
                            if ($('#date_added').val() == '') {
                                data.columns[1].search.value = '';
                            } else {
                                data.columns[1].search.value = $('#date_added').val();
                            }
                            if ($('#receive_date').val() == '') {
                                data.columns[2].search.value = '';
                            } else {
                                data.columns[2].search.value = $('#receive_date').val();
                            }
                            if ($('#ref_via').val() == '') {
                                data.columns[3].search.value = '';
                            } else {
                                data.columns[3].search.value = $('#ref_via').val();
                            }
                            if ($('#dt_company').val() == '') {
                                data.columns[4].search.value = '';
                            } else {
                                data.columns[4].search.value = $('#dt_company').val();
                            }
                            if ($('#gen_by').val() == '') {
                                data.columns[5].search.value = '';
                            } else {
                                data.columns[5].search.value = $('#gen_by').val();
                            }
                            if ($('#in_out').val() == '') {
                                data.columns[6].search.value = '';
                            } else {
                                data.columns[6].search.value = $('#in_out').val();
                            }
                        },
                        complete: function (data) { }
                    },
                    pagingType: 'full_numbers',
                    dom: 'Blrtip',
                    order: [[ 0, "desc" ]],
                    columns: [
                        { data: 'reference_id' },
                        { data: 'created_at' },
                        { data: 'selection_date' },
                        { data: 'type_of_inward' },
                        { data: 'company_name' },
                        { data: 'firstname' },
                        { data: 'inward_or_outward' },
                        { data: 'action', orderable: false },
                    ],
                    search: {
                        return: true
                    },
                    buttons: [{
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
                        'print']
                })
                .on( 'init.dt', function () {
                    $('<div class="dataTables_filter mt-2" id="reference_filter"><input type="search" id="ref_no" class="form-control form-control-sm" placeholder="Reference No"><input type="date" id="date_added" class="form-control form-control-sm" placeholder="Date Added" onfocus="this.showPicker()"><input type="date" id="receive_date" class="form-control form-control-sm" placeholder="Received Date" onfocus="this.showPicker()"> <input type="search" id="ref_via" class="form-control form-control-sm" placeholder="Reference Via"><input type="search" id="dt_company" class="form-control form-control-sm" placeholder="Company Name"><input type="search" id="gen_by" class="form-control form-control-sm" placeholder="Generated By"><input type="search" id="in_out" class="form-control form-control-sm" placeholder="In/Out"></div>').insertAfter('.dataTables_length');
                } );
                /* dt_table.on( 'responsive-resize', function ( e, datatable, columns ) {
                    var count = columns.reduce( function (a,b) {
                        return b === false ? a+1 : a;
                    }, 0 );
                } ); */
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
            $(document).on('keyup', '#reference_filter input', function(e) {
                if ($(this).val() == '') {
                    if (draw == 0) {
                        dt_table.clear().draw();
                        draw = 1;
                    }
                } else {
                    if (e.keyCode == 13) {
                        dt_table.clear().draw();
                    }
                    draw = 0;
                }
            });

            $(document).on('click', '.view-details', function(e) {
                self.showModal($(this).attr('data-id'));
            });
            document.getElementById('viewCompany1').addEventListener('hidden.bs.modal', function (event) {
                $('.modal-backdrop').remove();
            });
        },
    };

</script>
<style>
    #companydetail .modal-dialog {
        max-width: 1000px;
    }

</style>
