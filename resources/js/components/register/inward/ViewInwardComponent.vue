<template>
    <div class="nk-content">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">Inward Detail</h3>
                            </div><!-- .nk-block-head-content -->
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    <div class="nk-block">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="card card-bordered card-stretch">
                                    <div class="card-header">
                                        <h6>Inward Action Details</h6>
                                    </div>
                                    <div class="card-inner">
                                        <div class="row">
                                            <label class="form-label">Subject :</label>&nbsp; {{ Inward.subject }}
                                        </div>
                                        <div class="row">
                                            <label class="form-label">Iuid :</label>&nbsp; {{ Inward.iuid }}
                                        </div>
                                        <div class="row">
                                            <label class="form-label">Ref Id :</label>&nbsp; {{
                                            Inward.general_input_ref_id }}
                                        </div>
                                        <div class="row">
                                            <label class="form-label">Generated Date : </label>&nbsp; {{
                                            Inward.generatedate
                                            }}
                                        </div>
                                        <div class="row">
                                            <label class="form-label">Generated By : </label>&nbsp; {{ Inward.generateby
                                            }}
                                        </div>
                                        <div class="row">
                                            <label class="form-label">Date / Time : </label>&nbsp; {{ Inward.inward_date
                                            }}
                                        </div>
                                        <div class="row">
                                            <label class="form-label">Inward Type : </label>&nbsp; {{
                                            Inward.type_of_inward }}
                                        </div>
                                        <div class="row">
                                            <label class="form-label">By : </label>&nbsp; {{ Inward.sample_via }}
                                        </div>
                                        <div class="row">
                                            <label class="form-label">Courier Name : </label>&nbsp;{{ Inward.courier ?
                                            Inward.courier.name : '' }}
                                        </div>
                                        <div class="row">
                                            <label class="form-label">Weight Of Parcel : </label>&nbsp;{{
                                            Inward.weight_of_parcel }}
                                        </div>
                                        <div class="row">
                                            <label class="form-label">Courier Receipt No : </label> &nbsp;{{
                                            Inward.courier_receipt_no }}
                                        </div>
                                        <div class="row">
                                            <label class="form-label">Received Date Time : </label>&nbsp;{{
                                            Inward.courier_received_time }}
                                        </div>
                                        <div class="row">
                                            <label class="form-label">Company : </label> &nbsp;{{
                                            Inward.company ? Inward.company.company_name : ''
                                            }}
                                        </div>
                                        <div class="row">
                                            <label class="form-label">From Name : </label>&nbsp; {{ Inward.from_name }}
                                        </div>
                                        <div class="row">
                                            <label class="form-label">To Name : </label>&nbsp; {{ Inward.to_name }}
                                        </div>
                                        <div class="row">
                                            <label class="form-label">Delivery By : </label>&nbsp;{{ Inward.delivery_by
                                            }}
                                        </div>
                                    </div><!-- .card -->
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="card card-bordered card-stretch">
                                    <div class="card-header">
                                        <h6>Sample For</h6>
                                    </div>
                                    <div class="card-inner">
                                        <div class="row">
                                            <label class="form-label">Sample For : </label> {{ Inward.samplefor }}
                                        </div>
                                        <div class="row">
                                            <label class="form-label">Assign For : </label> {{ assignname }}
                                        </div>
                                    </div>
                                </div>
                                <div class="card card-bordered card-stretch">
                                    <div class="card-header">
                                        <h6>Notification Settings, Attachments & Products</h6>
                                    </div>
                                    <div class="card-inner">
                                        <div class="row">
                                            <label class="form-label">Multiple Attachments  :  </label>
                                            <ul>
                                                <li v-for="(attachment,index) in attachments" :key="index">
                                                    <a v-if="attachment != ''" :href="'/upload/Inwards/'+attachment" target="_blank">
                                                        <img height="65" width="50" id="preview-img" src="/assets/images/icons/file-media.svg" style="opacity: 0.5; padding-top: 5px;">
                                                    </a>
                                                    <span v-else>-</span>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="row">
                                            <label class="form-label">Remark : </label>
                                            {{ Inward.remarks }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-2 card card-bordered card-stretch">
                                <div class="card-header">
                                    <h6>Sample Details</h6>
                                </div>
                                <div class="class-inner">
                                    <table class="table" id="sampledata">
                                        <thead>
                                            <tr>
                                                <th>Select</th>
                                                <th>Sr no</th>
	    								        <th>Name</th>
		    									<th>Attachment</th>
			    			        			<th>Price</th>
                                                <th v-if="Inward.sample_for == 1 || Inward.sample_for == 3">Main Qty</th>
                                                <th v-else>Meter</th>
                                                <th v-if="Inward.sample_for == 1 || Inward.sample_for == 3">Remaining Qty</th>
                                                <th v-else>Remaining Meter</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(sample,index) in samples" :key="index">
                                                <td><input type="checkbox" class="d-block" v-model="selected" @change="sample_form_check"
                                                    :value="{'id':sample.sample_id}" required></td>
                                                <td>{{ ++index }}</td>
                                                <td>{{ sample.name }}</td>
                                                <td><a :href="'/upload/InwardSample/'+sample.image" target="_blank">
                                                        <img height="65" width="50" id="preview-img" src="/assets/images/icons/file-media.svg" style="opacity: 0.5; padding-top: 5px;">
                                                    </a></td>
                                                <td>{{ sample.price }}</td>
                                                <td v-if="Inward.sample_for == 1 || Inward.sample_for == 3">{{ sample.quantity }}</td>
                                                <td v-else>{{ sample.meters }}</td>
                                                <td v-if="Inward.sample_for == 1 || Inward.sample_for == 3"><input type="number" class="form-control" :readonly="true" v-model=sample.rem_qty></td>
                                                <td v-else><input type="number" :readonly="true" class="form-control" v-model=sample.rem_meter></td>
                                                <td><a :href="'/register/inward/sampleoutward/'+sample.sample_id" class="btn btn-trigger btn-icon" data-toggle="tooltip"
                                                    data-placement="top" title="show" target="_blank"><em class="icon ni ni-eye"></em></a></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                        </div>
                        <div class="card card-bordered sample-outward d-none">
                            <div class="card-header">
                                <h6>Insert Courier Details</h6>
                            </div>
                            <form action="#" class="form-validate" @submit.prevent="register()">
                                <div class="card-inner salebilldata">
                                    <div class="row gy-4">
                                        <div class="col-sm-2 text-right">
                                            <label class="form-label" for="fv-company">Company</label>
                                        </div>
                                        <div class="col-sm-4">
                                        <multiselect v-model="form.company" :options="company" placeholder="Select Comapny" label="company_name" track-by="company_name">
                                        </multiselect>

                                        </div>
                                    </div>
                                    <div class="row gy-4">
                                        <div class="col-sm-2 text-right">
                                            <label class="form-label" for="fv-refrencevia">Sample Via</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <multiselect v-model="form.referncevia" :options="referncevia" placeholder="Select Sample Via" label="name"
                                                track-by="name" @select="getRefencevia"></multiselect>
                                        </div>
                                        <div id="error-for-referencevia" class="mt-2 text-danger"></div>
                                    </div>
                                    <div class="row gy-4">
                                        <div class="col-sm-2 text-right">
                                            <label class="form-label" for="fv-datetime">Outward Date Time</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="date" class="form-control" id="fv-datetime" v-model="form.datetime"
                                                onfocus="this.showPicker()">
                                        </div>
                                    </div>

                                    <div class="row gy-4 courrier">
                                        <div class="col-sm-2 text-right">
                                            <label class="form-label" for="fv-courrier">Courrier Name</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <multiselect v-model="form.courrier" :options="courier" placeholder="Select Courrier" label="name"
                                                track-by="name"></multiselect>
                                        </div>
                                        <div id="error-for-courrier" class="mt-2 text-danger"></div>
                                    </div>

                                    <div class="row gy-4 courrier">
                                        <div class="col-sm-2 text-right">
                                            <label class="form-label" for="fv-reciptno">Courrier Receipt No</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" id="fv-reciptno" v-model="form.reciptno">

                                        </div>
                                    </div>
                                    <div class="row gy-4">
                                        <div class="col-sm-2 text-right">
                                            <label class="form-label" for="fv-weightparcel">Weight Of Parcel</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" id="fv-weightparcel" v-model="form.weightparcel">
                                        </div>
                                    </div>

                                    <div class="row gy-4">
                                        <div class="col-sm-2 text-right">
                                            <label class="form-label" for="fv-delivery">Delivery By</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" id="fv-delivery" v-model="form.delivery">

                                        </div>
                                    </div>

                                    <button class="btn btn-primary generatepayment mb-2 float-right">Save Changes</button>
                                </div>
                            </form>
                        </div><!-- .card -->
                        <div class="mt-2 card card-bordered card-stretch">
                            <div class="card-header">
                                <h6>OutWard Details</h6>
                            </div>
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

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(outward,index) in sample_outward" :key="index">
                                            <td><i v-if="outward.color_flag_id = 3" class="icon ni ni-check-thick"></i></td>
                                            <td><a :href="'/reference/view-reference/'+outward.reference_id">{{ outward.reference_id }}</a></td>
                                            <td>{{ outward.ouid }}</td>
                                            <td>{{ outward.sample_via }}</td>
                                            <td>{{ outward.company_name }}</td>
                                            <td>{{ outward.courier }}</td>
                                            <td>{{ outward.courier_receipt_no }}</td>
                                            <td>{{ outward.courier_received_time }}</td>
                                            <td>{{ outward.weight_of_parcel }}</td>
                                            <td>{{ outward.delivery_by }}</td>

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
    import Multiselect from 'vue-multiselect';
    import 'jquery/dist/jquery.min.js';
    import 'datatables.net-bs5/js/dataTables.bootstrap5';
    import 'datatables.net-responsive-bs4/js/responsive.bootstrap4';
    import "datatables.net-buttons-bs5/js/buttons.bootstrap5";
    import "datatables.net-buttons/js/buttons.flash.js";
    import "datatables.net-buttons/js/buttons.html5.js";
    import "datatables.net-buttons/js/buttons.print.js";
    import Form from 'vform';

    import $ from 'jquery';

    var gData = [];
    export default {
        name: 'payment',
        props: {
            id: Number,
        },
        components: {
            Multiselect,
        },
        data() {
            return {
                Inward: [],
                attachments: [],
                samples: [],
                sample_outward:[],
                company:[],
                selected: [],
                isValidate: 0,
                courier: [],
                assignname: '',
                referncevia: [{ name: 'Courier' }, { name: 'Hand' }],
                form: new Form({
                    datetime: '',
                    company: '',
                    referncevia: '',
                    courrier: '',
                    reciptno: '',
                    weightparcel: '',
                    delivery: '',
                })
            }
        },
        created() {
            const date = new Date();
            const m = String(date.getMonth() + 1).padStart(2, '0');
            const d = String(date.getDate()).padStart(2, '0');
            const y = String(date.getFullYear());
            this.form.datetime = [y, m, d].join('-');
             axios.get(`/register/fetch-inward/${this.id}`)
                .then(response => {
                        gData = response.data;
                        let total = 0;
                        this.Inward = gData.inward;
                        this.assignname = gData.inward.assignemp.firstname + ' ' + gData.inward.assignemp.lastname;
                        this.attachments = gData.inward.attachment;
                        this.samples = gData.sample;
                        this.sample_outward = gData.sample_outward;
                });
            axios.get('/commission/list-company')
                .then(response => {
                    this.company = response.data;
                });
            axios.get('/register/list-agentcourier')
                .then(response => {
                    this.courier = response.data.courier;
                });
        },
        methods: {
            getRefencevia(option, id) {
                let refernceby = option.name;
                if (refernceby == 'Hand') {
                    $(".courrier").addClass("d-none");
                } else if (refernceby == 'Courier') {
                    $(".courrier").removeClass("d-none");
                }
            },
            sample_form_check(event){
                var index = event.target.parentElement.parentElement.rowIndex;
                if(this.selected.length > 0){
                    $(".sample-outward").removeClass("d-none");
                } else {
                    $(".sample-outward").addClass("d-none");
                }
                var status = $('#sampledata tbody tr').eq(index - 1).find('td').find('input[type=checkbox]').prop('checked');
                if (status) {
                    $('#sampledata tbody tr').eq(index - 1).find('td').find('input[type=number]').removeAttr('readonly');
                } else {
                    $('#sampledata tbody tr').eq(index - 1).find('td').find('input[type=number]').attr('readonly', 'true');
                }


            },
            register(event){

                $("#error-for-referencevia").text("");
                $("#error-for-courrier").text("");
                if (this.form.referncevia == '') {
                    $("#error-for-referencevia").text("Select Reference Via");
                    this.isValidate = 0;
                } else {
                    $("#error-for-referencevia").text("");
                    if (this.form.referncevia.name == 'Courier') {
                        if (this.form.courrier == '') {
                            $("#error-for-courrier").text("Select Courier");
                            this.isValidate = 0;
                        } else {
                            $("#error-for-courrier").text("");
                            this.isValidate = 1;
                        }
                    } else {
                        this.isValidate = 1;
                    }
                }
                var formdata = new FormData();
                formdata.append("outward_sample", JSON.stringify(this.form));
                formdata.append("sample_ids", JSON.stringify(this.selected));
                formdata.append("inward_data", JSON.stringify(this.Inward));
                formdata.append("samples", JSON.stringify(this.samples));
                formdata.append("inward_id", this.id);
                // console.log(JSON.stringify(this.selected));
                // console.log(JSON.stringify(this.form));
                if (this.isValidate == 1) {
                    axios.post('/register/insertsampleoutward', formdata)
                        .then(function (responce) {
                            window.location.href = `/register/outward/view-inward/${this.id}`;
                        }).catch(function (error) {

                        });
                } else {
                    alert('Please Fill Required Field');
                    return false;
                }
            }
        },
        mounted() {

        },
    };
</script>
<style scoped>
    .user-avatar img{
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
