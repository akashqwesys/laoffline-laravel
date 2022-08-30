<template>
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">Percentage Evaluate Turnover Report</h3>
                                <div class="nk-block-des text-soft"> </div>
                            </div><!-- .nk-block-head-content -->
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    <div class="nk-block">
                        <div class="card card-bordered card-stretch">
                            <div class="card-inner">
                                <div class="mb-5">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th width="25%">Start Date</th>
                                                <th width="25%">End Date</th>
                                                <th width="25%">Sort</th>
                                                <th width="25%">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <input type="date" v-model="start_date" id="start_date"
                                                        class="form-control" autocomplete="off"
                                                        onfocus="this.showPicker();" :max="max_date">
                                                </td>
                                                <td>
                                                    <input type="date" v-model="end_date" id="end_date"
                                                        class="form-control" autocomplete="off"
                                                        onfocus="this.showPicker();" :max="max_date">
                                                </td>
                                                <td>
                                                    <select v-model="sort_by" class="form-control">
                                                        <option value="1">Commission Percentage: low -&gt; high</option>
                                                        <option value="2">Commission Percentage: high -&gt; low</option>
                                                        <option value="3">Amount: low -&gt; high</option>
                                                        <option value="4">Amount: high -&gt; low</option>
                                                    </select>
                                                </td>
                                                <td class="">
                                                    <button class="btn btn-primary btn-round btn-sm mr-1"
                                                        @click="getData()">Go</button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="table-responsive" id="percentage-div">
                                    <table id="percentageDT" class="table table-hover table-bordered-">
                                        <thead>
                                            <tr class="">
                                                <th> Commission Percentage</th>
                                                <th class="text-right">Amount(Turnover)</th>
                                                <th class="text-right">In Percentage(Round) </th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div><!-- .card -->
                        </div>
                    </div><!-- .nk-block -->
                </div>
            </div>
        </div>
    </div>
    <!-- <ViewCompanyDetails ref="company"></ViewCompanyDetails> -->
</template>

<script>
// import ViewCompanyDetails from '../databank/companyComponents/modal/ViewCompanyDetailsModelComponent.vue';

import $ from 'jquery';
// import Multiselect from 'vue-multiselect';

export default {
    name: 'PercentageEvaluateTurnover',
    props: {
    },
    components: {
        // Multiselect,
        // ViewCompanyDetails
    },
    data() {
        return {
            start_date: '',
            end_date: '',
            sort_by: '1',
            max_date: '2022-01-01',
            dt_table: null,
        }
    },
    created() {
        const date = new Date();
        const m = String(date.getMonth() + 1).padStart(2, '0');
        const d = String(date.getDate()).padStart(2, '0');
        const y = String(date.getFullYear());
        this.max_date = this.end_date = [y, m, d].join('-');
        this.start_date = '2015-04-01';
    },
    methods: {
        clearData() {
            this.start_date = this.end_date = this.company_type = this.agent = this.due_days = this.sort_by = '';
        },
        getData() {
            if (this.start_date == '' || this.end_date == '') {
                alert('Please Select Both Start Date & End Date');
                return false;
            }
            axios.post('/reports/list-percentage-evaluate-turnover-data', {
                start_date: this.start_date,
                end_date: this.end_date,
                sort_by: this.sort_by
            })
            .then(response => {
                if (response.data.data.length > 0) {
                    const toINR = new Intl.NumberFormat('en-IN', {});
                    var html = '';
                    response.data.data.forEach((k, i) => {
                        html += `<tr>
                    <td > ${k.commission_percentage}% </td>
                    <td class="text-right"> ${toINR.format(k.total_amount)} </td>
                    <td class="text-right"> ${((100 * parseFloat(k.total_amount)) / parseFloat(response.data.total)).toFixed(2)}% </td>
                </tr>`;
                    });
                    html += `<tr>
                    <th> Total</th>
                    <th class="text-right"> ${toINR.format(response.data.total)} </th>
                    <th class="text-right"> 100%</th>
                </tr>`;
                    $('#percentageDT tbody').html(html);
                } else {
                    $('#percentageDT tbody').html('<tr><td colspan="3" class="text-center">No Records Found</td></tr>');
                }
            });
        },
    },
    mounted() {
        const self = this;
        /* const toINR = new Intl.NumberFormat('en-IN', {
            style: 'currency',
            currency: 'INR',
            // minimumFractionDigits: 2
        }); */

        $(document).on('click', '.view-details', function (e) {
            self.showModal($(this).attr('data-id'));
        });

    },
};
</script>
