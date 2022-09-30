<template>
    <div class="nk-content">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">Outward Sample Detail</h3>
                            </div><!-- .nk-block-head-content -->
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    <div class="nk-block">
                        <div class="mt-2 card card-bordered card-stretch">
                            <div class="class-inner">
                                <table class="table" id="sampleoutwarddata">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Reference Id</th>
                                            <th>OUID</th>
                                            <th>Sample Via</th>
                                            <th>Customer</th>
                                            <th>Courier Name</th>
                                            <th>Courrier Receipt No</th>
                                            <th>Out. Date Time</th>
                                            <th>Weight Of Parcel</th>
                                            <th>Delivery By</th>
                                            <th>Qty / Meter</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(outward,index) in sample_outward" :key="index">
                                            <td></td>
                                            <td><a :href="'/reference/view-reference/'+outward.reference_id">{{ outward.reference_id }}</a></td>
                                            <td>{{ outward.ouid }}</td>
                                            <td>{{ outward.sample_via }}</td>
                                            <td>{{ outward.company_name }}</td>
                                            <td>{{ outward.courier }}</td>
                                            <td>{{ outward.courier_receipt_no }}</td>
                                            <td>{{ outward.courier_received_time }}</td>
                                            <td>{{ outward.weight_of_parcel }}</td>
                                            <td>{{ outward.delivery_by }}</td>
                                            <td>{{ outward.new_qty }}/ {{ outward.new_meters }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div><!-- .nk-block -->
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import 'jquery/dist/jquery.min.js';

import $ from 'jquery';

var gData = [];
export default {
    name: 'payment',
    props: {
        id: Number,
    },

    data() {
        return {

            sample_outward: [],



        }
    },
    created() {
        axios.get(`/register/fetch-sampleoutward/${this.id}`)
            .then(response => {
                gData = response.data;
                this.sample_outward = gData.outward;
            });
    },
    methods: {

    },
    mounted() {

    },
};
</script>
<style scoped>
.user-avatar img {
    width: 100%;
}

.dataTables_filter {
    display: flex;
    margin-bottom: 15px;
}

.dataTables_filter input {
    padding: 17px;
    margin: 0 10px;
    width: 25%;
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

/* .dt-buttons .dt-button span {
        display: none;
    } */
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
