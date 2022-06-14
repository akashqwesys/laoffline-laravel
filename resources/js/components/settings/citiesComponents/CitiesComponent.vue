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
                                        <h3 class="nk-block-title page-title">City Lists</h3>
                                    </div>
                                    <div class="col-md-8 text-right">
                                        <a v-bind:href="create_cities" class="dropdown-toggle btn btn-icon btn-primary mx-2"><em class="icon ni ni-plus"></em></a>
                                        <button @click="clearallfilter" class="btn btn-dark px-2">Clear</button>                                        
				                    </div>
                                </div>
                                
                            </div>
                            <div class="card-inner">
                                <table id="cities" class="table table-hover table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>City Name</th>
                                            <th>State</th>
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
    </div>
</template>

<script>
    import $ from 'jquery';
    import 'datatables.net-responsive-bs4/js/responsive.bootstrap4';
    import "datatables.net-buttons-bs5/js/buttons.bootstrap5";
    import 'pdfmake/build/pdfmake';
    import "datatables.net-buttons/js/buttons.html5.js";
    import "datatables.net-buttons/js/buttons.print.js";

    export default {
        name: 'city',
        props: {
            excelAccess: Number,
        },
        components: {
        },
        data() {
            return {
                create_cities: 'cities/create-cities',
            }
        },
        created() {
        },
        methods: {
            edit_data(id){
                window.location.href = './cities/edit-cities/'+id;
            },
            delete_data(id){
                axios.delete('./cities/delete/'+id)
                .then(response => {
                    location.reload();
                });
            },
            clearallfilter(event) {
                $('input[type=search]').val("");
                $('#cities').DataTable().search("").draw();
            },
        },
        mounted() {
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
                dt_table = $('#cities').DataTable({
                    processing: true,
                    serverSide: true,
                    lengthChange: true,
                    ajax: {
                        url: "./cities/list",
                        data: function (data) {
                            if ($('#cities_filter input').val() == '') {
                                data.search.value = '';
                            }
                        },
                        complete: function (data) { }
                    },
                    pagingType: 'full_numbers',
                    dom: "<'row'<'col-sm-12 col-md-4'l><'col-sm-12 col-md-4'f><'col-sm-12 col-md-4'B>>" +
                        "<'row'<'col-sm-12'tr>>" +
                        "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                    columns: [
                       { data: 'id' },
                        { data: 'name' },
                        { data: 'state'},
                        { data: 'action', orderable: false },
                    ],
                    search: {
                        return: true
                    },
                    buttons: buttons
                });
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
            $(document).on('keyup', '#cities_filter input', function(e) {
                if ($(this).val() == '') {
                    if (draw == 0) {
                        dt_table.clear().draw();
                        draw = 1;
                    }
                } else {
                    draw = 0;
                }
            });
        },
    };
</script>
<style>
</style>
