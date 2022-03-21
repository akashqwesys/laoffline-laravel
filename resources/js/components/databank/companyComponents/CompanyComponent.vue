<template>
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">Company Lists</h3>
                                <div class="nk-block-des text-soft">
                                    <!-- <p>You have total {{companies.length}} company.</p> -->
                                </div>
                            </div><!-- .nk-block-head-content -->
                            <div class="nk-block-head-content">
                                <div class="toggle-wrap nk-block-tools-toggle">
                                    <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                    <div class="toggle-expand-content" data-content="pageMenu">
                                        <ul class="nk-block-tools g-3">
                                            <li class="nk-block-tools-opt">
                                                <a href="#" class="btn btn-wider btn-primary" @click="getEssentialCompany">Essential Companies</a>
                                            </li>
                                            <li class="nk-block-tools-opt">
                                                <a v-bind:href="create_company" class="dropdown-toggle btn btn-icon btn-primary"><em class="icon ni ni-plus"></em></a>
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
                                <table id="companies" class="table table-hover table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th></th>
                                            <th></th>
                                            <th>Name</th>
                                            <th>Office No.</th>
                                            <th>Company Type</th>
                                            <th>Company Category</th>
                                            <th>City</th>
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
        <ViewCompanyDetails></ViewCompanyDetails>
    </div>
</template>

<script>
    import ViewCompanyDetails from './modal/ViewCompanyDetailsModelComponent';

    import $ from 'jquery';
    import 'datatables.net-responsive-bs4/js/responsive.bootstrap4';
    import "datatables.net-buttons-bs5/js/buttons.bootstrap5";
    import 'pdfmake/build/pdfmake';
    import "datatables.net-buttons/js/buttons.html5";
    import "datatables.net-buttons/js/buttons.print";

    export default {
        name: 'company',
        props: {
            excelAccess: Number,
        },
        components: {
            ViewCompanyDetails
        },
        data() {
            return {
                create_company: 'companies/create-company',
                categoryName: '',
            }
        },
        methods: {
            showModal(id) {
                $("#viewCompany1").modal('show');
                $('<div class="modal-backdrop fade show"></div>').appendTo(document.body);
                $('body').addClass('modal-open');
                $('body').css('overflow', 'hidden');
                $('body').css('padding-right', '17px');
            },
            closeModel() {
                $('#viewCompany1').modal('hide');
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open');
                $('body').removeAttr('style');
            },
            getEssentialCompany() {
                window.location.href = './companies/essential/';
            },
            isFavorite: function(id) {
                axios.post('./companies/favorite/'+id)
                .then(response => {
                    location.reload();
                });
            },
            isUnFavorite: function(id) {
                axios.post('./companies/unFavorite/'+id)
                .then(response => {
                    location.reload();
                });
            },
            isVerified: function(id) {
                axios.post('./companies/verify/'+id)
                .then(response => {
                    location.reload();
                });
            },
            view_data(id){
                window.location.href = './companies/view-company/'+id;
            },
            edit_data(id){
                window.location.href = './companies/edit-company/'+id;
            },
            delete_data(id){
                axios.delete('./companies/delete/'+id)
                .then(response => {
                    location.reload();
                });
            },
            getProfilePic(name){
                return '/upload/company/multipleAddressProfilePic/' + name;
            },
        },
        mounted() {
            var buttons = [];
            var dt_table = null;
            if(this.excelAccess == 1) {
                buttons = ['excel', 'pdf', 'print'];
            }
            function init_dt_table () {
                dt_table = $('#companies').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    ajax: {
                        url: "./companies/list",
                        data: function (data) {
                            if ($('#dt_name').val() == '') {
                                data.columns[2].search.value = '';
                            } else {
                                data.columns[2].search.value = $('#dt_name').val();
                            }
                            if ($('#dt_email').val() == '') {
                                data.columns[3].search.value = '';
                            } else {
                                data.columns[3].search.value = $('#dt_email').val();
                            }
                            if ($('#dt_mobile').val() == '') {
                                data.columns[4].search.value = '';
                            } else {
                                data.columns[4].search.value = $('#dt_mobile').val();
                            }
                            if ($('#dt_user_group').val() == '') {
                                data.columns[5].search.value = '';
                            } else {
                                data.columns[5].search.value = $('#dt_user_group').val();
                            }
                        },
                        complete: function (data) { }
                    },
                    pagingType: 'full_numbers',
                    dom: 'Brtip',
                    columns: [
                        { data: 'id' },
                        { data: 'flag' },
                        { data: 'verified' },
                        { data: 'name' },
                        { data: 'office_no' },
                        { data: 'company_type' },
                        { data: 'company_category' },
                        { data: 'city' },
                        { data: 'action' },
                    ],
                    search: {
                        return: true
                    },
                    buttons: buttons
                })
                .on( 'init.dt', function () {
                    $('<div class="dataTables_filter" id="company_filter"><input type="search" id="dt_name" class="form-control form-control-sm" placeholder="Name"><input type="search" id="dt_office_no" class="form-control form-control-sm" placeholder="Office Number"><input type="search" id="dt_company_type" class="form-control form-control-sm" placeholder="Company Type"><input type="search" id="dt_company_category" class="form-control form-control-sm" placeholder="Company Category"><input type="search" id="dt_city" class="form-control form-control-sm" placeholder="City"></div>').insertAfter('.dt-buttons.btn-group');
                } );
                dt_table.on( 'responsive-resize', function ( e, datatable, columns ) {
                    var count = columns.reduce( function (a,b) {
                        return b === false ? a+1 : a;
                    }, 0 );
                } );
            }
            init_dt_table();
            var draw = 1;
            $(document).on('keyup', '#company_filter input', function(e) {
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
        },
    };

function showModal(id) {
    // console.log("MODAL-ID:- ", id);
}
</script>

<style>
    .icon.ni.ni-star, .icon.ni.ni-star-fill,
    .icon.ni.ni-alert-fill, .icon.ni.ni-check-thick {
        font-size: 20px;

    }
    .icon.ni.ni-star, .icon.ni.ni-star-fill {
        cursor: pointer;
    }
    .dataTables_filter {
        padding: 10px;
    }
    .dataTables_filter input {
        margin-left: 10px;
    }
    .dt-buttons {
        position: relative;
        display: inline-flex;
        vertical-align: middle;
        flex-wrap: wrap;
        float: right;
    }
    .dt-buttons .dt-button {
        position: relative;
        flex: 1 1 auto;
        display: inline-flex;
        font-family: Nunito, sans-serif;
        font-weight: 700;
        color: #526484;
        text-align: center;
        vertical-align: middle;
        user-select: none;
        background-color: transparent;
        border: 1px solid #dbdfea;
        padding: 0.4375rem 0;
        font-size: 0.8125rem;
        line-height: 1.25rem;
        border-radius: 4px;
        transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }
    .dt-buttons .dt-button::before {
        font-size: 1.125rem;
        font-weight: normal;
        font-style: normal;
        width: 2.125rem;
        font-family: "Nioicon";
    }
    .dt-buttons .dt-button span {
        display: none;
    }
    .dataTables_paginate {
        display: flex;
        padding-left: 0;
        list-style: none;
        border-radius: 4px;
        margin: 2px 0;
        justify-content: flex-end;
    }
    .dataTables_paginate .paginate_button.disabled,
    .dataTables_paginate .paginate_button.disabled {
        color: #dbdfea;
        pointer-events: none;
        background-color: #fff;
        border-color: #e5e9f2;
    }
    .dataTables_paginate .paginate_button.first,
    .dataTables_paginate .paginate_button.previous,
    .dataTables_paginate .paginate_button.next,
    .dataTables_paginate .paginate_button.last {
        margin-left: 0;
        border-top-left-radius: 4px;
        border-bottom-left-radius: 4px;
    }
    .dataTables_paginate .paginate_button {
        font-size: 0.8125rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: calc(1rem + 1.125rem + 2px);
        position: relative;
        padding: 0.5625rem 0.625rem;
        line-height: 1rem;
        border: 1px solid #e5e9f2;
        cursor: pointer;
    }
    .dataTables_paginate .paginate_button.current {
        z-index: 3;
        color: #fff;
        background-color: #6576ff;
        border-color: #6576ff;
    }
    .viewCompany .modal-dialog{
        max-width: 1550px;
    }
</style>
