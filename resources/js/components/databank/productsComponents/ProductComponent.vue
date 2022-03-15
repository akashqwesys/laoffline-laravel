<template>
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">Product Lists</h3>
                                <div class="nk-block-des text-soft">
                                    <!-- <p>You have total {{products.length}} product.</p> -->
                                </div>
                            </div><!-- .nk-block-head-content -->
                            <div class="nk-block-head-content">
                                <div class="toggle-wrap nk-block-tools-toggle">
                                    <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                    <div class="toggle-expand-content" data-content="pageMenu">
                                        <ul class="nk-block-tools g-3">                                            
                                            <li class="nk-block-tools-opt">
                                                <a v-bind:href="create_product_category" class="dropdown-toggle btn btn-icon btn-primary"><em class="icon ni ni-plus"></em></a>
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
                                <table id="product" class="table table-hover table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th></th>
                                            <th></th>
                                            <th>Name</th>
                                            <th>Catalogue Name</th>
                                            <th>Brand Name</th>
                                            <th>Model Name</th>
                                            <th>Company</th>
                                            <th>Category</th>
                                            <th>Price</th>
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
    import 'jquery/dist/jquery.min.js';
    import "datatables.net-dt/js/dataTables.dataTables";
    import "datatables.net-buttons/js/dataTables.buttons.js";
    import "datatables.net-buttons/js/buttons.colVis.js";
    import "datatables.net-buttons/js/buttons.flash.js";
    import "datatables.net-buttons/js/buttons.html5.js";
    import "datatables.net-buttons/js/buttons.print.js";
    import $ from 'jquery';

    export default {
        name: 'product',
        props: {
            excelAccess: Number,
        },
        data() {
            return {
                create_product_category: 'catalog/create-products',
            }
        },
        methods: {
            edit_data(id){
                window.location.href = './catalog/edit-products/'+id;
            },
            view_data(id){
                // window.location.href = './catalog/edit-products/'+id;
            },
            delete_data(id){
                axios.delete('./catalog/delete/'+id)
                .then(response => {                    
                    location.reload();
                });
            }
        },
        mounted() {
            if(this.excelAccess == 1) {
                $('#product').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "./catalog/list",
                    pagingType: 'full_numbers',
                    dom: 'Bfrtip',
                    columns: [
                        { data: 'id' },
                        { data: 'flag' },
                        { data: 'image' },
                        { data: 'name' },
                        { data: 'catalogue_name' },
                        { data: 'brand_name' },
                        { data: 'model' },
                        { data: 'company_name' },
                        { data: 'category_name' },
                        { data: 'catalogue_price' },
                        { data: 'action' },
                    ],
                    buttons: ['copy', 'csv', 'excel', 'print']
                });
            } else {
                $('#product').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "./catalog/list",
                    pagingType: 'full_numbers',
                    dom: 'Bfrtip',
                    columns: [
                        { data: 'id' },
                        { data: 'flag' },
                        { data: 'image' },
                        { data: 'name' },
                        { data: 'catalogue_name' },
                        { data: 'brand_name' },
                        { data: 'model' },
                        { data: 'company_name' },
                        { data: 'category_name' },
                        { data: 'catalogue_price' },
                        { data: 'action' },
                    ],
                    buttons: []
                });
            }
        },
    };
</script>
<style>
    .user-avatar.user-avatar-sm.product {
        border-radius: 0;
        height: 50px;
        width: 50px;
    }
    .user-avatar.user-avatar-sm.product span {
        text-transform: capitalize;
        font-size: 20px;
    }
    .user-avatar img, [class^="user-avatar"]:not([class*="-group"]) img.product_image {
        border-radius: 0;
        height: 100%;
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
</style>