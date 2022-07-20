<template>
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">Daily Commission Report</h3>
                                <div class="nk-block-des text-soft"> </div>
                            </div><!-- .nk-block-head-content -->
                            <div class="nk-block-head-content">
                                <div class="toggle-wrap nk-block-tools-toggle">
                                    <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1"
                                        data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                    <div class="toggle-expand-content" data-content="pageMenu">
                                        <button class="btn btn-primary" @click="exportPDF()">Export PDF</button>
                                        <ul class="nk-block-tools g-3">
                                            <li class="nk-block-tools-opt">
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
                                <div class="mb-5">
                                    <table class="table table-striped">
                                        
                                        <tbody>
                                            <tr>
                                                <td class="w-20">
                                                    <input type="date" v-model="start_date" id="start_date"
                                                        class="form-control" autocomplete="off"
                                                        onfocus="this.showPicker();" :max="max_date">
                                                </td>
                                                
                                                <td class="">
                                                    <button class="btn btn-primary btn-round btn-sm mr-1"
                                                        @click="getData()">Go</button>
                                                    <button class="btn btn-light btn-round btn-sm"
                                                        @click="clearData()">Clear All</button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="table-responsive">
                                    <table id="salesRegister" class="table table-hover">
                                        
                                    </table>
                                </div>
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
    import "datatables.net-buttons/js/buttons.html5";
    import "datatables.net-buttons/js/buttons.print";
    import Multiselect from 'vue-multiselect';

    export default {
        name: 'salesRegister',
        props: {
        },
        components: {
            Multiselect,
        },
        data() {
            return {
                start_date: '',
                max_date: '2022-01-01',
                detailed_table: true,
                export_sheet: 0,
                export_pdf: 0,
            }
        },
        created() {
            const date = new Date();
            const m = String(date.getMonth() + 1).padStart(2, '0');
            const d = String(date.getDate()).padStart(2, '0');
            const y = String(date.getFullYear());
            this.max_date = [y, m, d].join('-');
            this.start_date = [y, m, d].join('-');
            this.getData();
        },
        methods: {
            
            clearData() {
                this.start_date = this.max_date;
                this.getData();
            },
            getData() {
                axios.post('/reports/list-daily-commission-report', {
                    start_date: this.start_date,
                    export_pdf: this.export_pdf
                })
                .then(response => {
                    if (this.export_pdf == 1) {
                        this.export_pdf = 0;
                        window.open(response.data.url, '_blank');
                        return;
                    }
                    $('#salesRegister').html(response.data.table);
                });
            },
           
            exportPDF() {
                this.export_pdf = 1;
                this.getData();
            },
        },
        mounted() {
            const self = this;
            var dt_table = null;
            /* const toINR = new Intl.NumberFormat('en-IN', {
                style: 'currency',
                currency: 'INR',
                // minimumFractionDigits: 2
            }); */

            $(document).on('click', '.view-details', function(e) {
                self.showModal($(this).attr('data-id'));
            });
            document.getElementById('viewCompany1').addEventListener('hidden.bs.modal', function (event) {
                $('.modal-backdrop').remove();
            });

        },
    };
</script>

