<template>
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">Add Reference</h3>
                                <div class="nk-block-des text-soft">
                                    <p>Please fill the all details.</p>
                                </div>
                            </div><!-- .nk-block-head-content -->
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    <div class="nk-block">
                        <div class="card card-bordered">
                            <div class="card-inner">
                                <form action="#" @submit.prevent="register()">
                                    <div class="preview-block">
                                        <span class="preview-title-lg overline-title">Reference Details</span>
                                        <div class="row gy-4">
                                            <div class="col-md-4">
                                                <div class="preview-block">
                                                    <label class="form-label">Inward/Outward</label>
                                                    <ul class="custom-control-group g-3 align-center">
                                                        <li>
                                                            <div class="custom-control custom-radio">
                                                                <input v-model="form.inward_outward" type="radio" class="custom-control-input"  id="fv-reference_inward" value="1" @click="InwardOrOutward = true">
                                                                <label class="custom-control-label" for="fv-reference_inward">Inward</label>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="custom-control custom-radio">
                                                                <input v-model="form.inward_outward" type="radio" class="custom-control-input"  id="fv-reference_outward" value="0" @click="InwardOrOutward = false">
                                                                <label class="custom-control-label" for="fv-reference_outward">Outward</label>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row gy-4">
                                        <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-first-name">Reference Via</label>
                                                    <div>
                                                        <multiselect v-model="form.Reference_via" :options="options" placeholder="Select one" label="name" track-by="name"></multiselect>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-first-name">Date/Time</label>
                                                    <div class="form-control-wrap">
                                                        <input type="date" class="form-control" id="fv-Reference-date" v-model="form.Date_Time" @focus="showDatePicker()">
                                                    </div>
                                                </div>
                                            </div>
                                                <div class="col-md-4">
                                                    <div class="form-group code-block">
                                                        <label class="form-label" for="fv-company">Company</label>
                                                        <button type="button" class="btn btn-sm clipboard-init" data-toggle="modal" data-target="#addCompany" title="Company Inserts"><span class="clipboard-text">Add New</span></button>
                                                        <div>
                                                            <multiselect v-model="form.companyName" :options="company" placeholder="Select one" label="company_name" track-by="id" @select="getFromName"></multiselect>
                                                        </div>
                                                    </div>
                                                </div>
                                            <div class="col-md-4">
                                                <div v-if="form.Reference_via.name == 'Hand' || form.Reference_via.name == 'Courier'">
                                                    <input type="checkbox" id="checkbox" v-model="form.markssample" />
                                                    <label for="checkbox">Mark As Sample</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div v-if="form.Reference_via.name == 'Call' || form.Reference_via.name == 'Message' || form.Reference_via.name == 'Whatsapp'">
                                            <div class="row gy-4">
                                                <div class="col-md-4">
                                                    <div class="form-group code-block">
                                                        <label v-if="InwardOrOutward" class="form-label" for="fv-from-name">From Name</label>
                                                        <label v-else class="form-label" for="fv-from-name">To Name</label>
                                                        <button type="button" class="btn btn-sm clipboard-init" data-toggle="modal" data-target="#AddPerson" title="Person Detail"><span class="clipboard-text">Add New</span></button>
                                                        <div class="form-control-wrap">
                                                            <input type="text" class="form-control" id="fv-from_number" v-model="form.from_name" placeholder="Enter From Name">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label" for="fv-from-number">From Number</label>
                                                        <div class="form-control-wrap">
                                                            <input type="text" class="form-control" id="fv-from_number" v-model="form.from_number" placeholder="Enter From Number">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label v-if="InwardOrOutward" class="form-label" for="fv-receiver-number">Receiver Number</label>
                                                        <label v-else class="form-label" for="fv-receiver-number">Receiver Number</label>
                                                        <div class="form-control-wrap">
                                                            <input type="text" class="form-control" id="fv-receiver_number" v-model="form.receiver_number">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div v-else-if="form.Reference_via.name == 'Email'">
                                            <div class="row gy-4">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <form>
                                                        <label v-if="InwardOrOutward" class="form-label" for="fv-from-name">From Name</label>
                                                        <label v-else class="form-label" for="fv-from-name-email">To Name</label>
                                                            <div class="form-control-wrap">
                                                                <input type="text" class="form-control" id="fv-from_number" v-model="form.from_name" placeholder="Enter From Name">
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label" for="fv-from-email">From Email Id</label>
                                                        <div class="form-control-wrap">
                                                            <input type="text" class="form-control" id="fv-from_email" v-model="form.from_email" placeholder="Enter From email">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label" for="fv-receiver-email">Receiver Email Id</label>
                                                        <div class="form-control-wrap">
                                                            <input type="text" class="form-control" id="fv-receiver_email" v-model="form.receiver_email" placeholder="Enter Receiver email">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div v-else-if="form.Reference_via.name == 'Hand'">
                                            <div class="row gy-4">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label v-if="InwardOrOutward" class="form-label" for="fv-from-name">From Name</label>
                                                        <label v-else class="form-label" for="fv-from-name-hand">To Name</label>
                                                        <div class="form-control-wrap">
                                                            <input type="text" class="form-control" id="fv-from_number" v-model="form.from_name" placeholder="Enter From Name">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label" for="fv-parcel-weight-hand">Weight of Parcel</label>
                                                        <div class="form-control-wrap">
                                                            <input type="text" class="form-control" id="fv-parcel_weight" v-model="form.parcel_weight" placeholder="Enter Parcel Weight">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div v-if="InwardOrOutward" class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label" for="fv-received-date-time-hand">Received Date Time</label>
                                                        <div class="form-control-wrap">
                                                            <input type="datetime-local" class="form-control" id="fv-received_date_time" v-model="form.received_date_time" placeholder="Enter Received Date and Time" @focus="showDateTimePicker()">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label v-if="InwardOrOutward" class="form-label" for="fv-delivery-by-hand">Delivery By</label>
                                                        <label v-else class="form-label" for="fv-delivered-by-hand">Delivered By</label>
                                                        <div class="form-control-wrap">
                                                            <input type="text" class="form-control" id="fv-delivery_by_hand" v-model="form.delivery_by" placeholder="Enter Person Name">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div v-else-if="form.Reference_via.name == 'Courier'">
                                            <div class="row gy-4">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label v-if="InwardOrOutward" class="form-label" for="fv-from-name-courier">From Name</label>
                                                        <label v-else class="form-label" for="fv-from-name-courier">To Name</label>
                                                        <div class="form-control-wrap">
                                                            <input type="text" class="form-control" id="fv-from_number" v-model="form.from_name" placeholder="Enter From Name">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div>
                                                        <label class="form-label" for="fv-Courier-name">Courier Name</label>
                                                        <div>
                                                            <multiselect v-model="form.courier_company" :options="courier" placeholder="Select one" label="name" track-by="name"></multiselect>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label" for="fv-courier-recepit-no">Courier Receipt No</label>
                                                        <div class="form-control-wrap">
                                                            <input type="text" class="form-control" id="fv-courier_recepit_no" v-model="form.courier_recepit_no" placeholder="Enter From Courier Receipt No">
                                                        </div>
                                                </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label" for="fv-parcel-weight-courier">Weight of Parcel</label>
                                                        <div class="form-control-wrap">
                                                            <input type="text" class="form-control" id="fv-parcel_weight" v-model="form.parcel_weight" placeholder="Enter Parcel Weight">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div v-if="InwardOrOutward" class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label" for="fv-received-date-time">Received Date Time</label>
                                                        <div class="form-control-wrap">
                                                            <input type="datetime-local" class="form-control" id="fv-received_date_time" v-model="form.received_date_time" placeholder="Enter Received Date and Time" @focus="showDateTimePicker()">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label v-if="InwardOrOutward" class="form-label" for="fv-delivery-by-courier">Delivery By</label>
                                                        <label v-else class="form-label" for="fv-delivered-by">Delivered By</label>
                                                        <div class="form-control-wrap">
                                                            <input type="text" class="form-control" id="fv-delivery_by_courier" v-model="form.delivery_by" placeholder="Enter Person Name">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr class="preview-hr">
                                        <div class="row gy-4">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <a v-bind:href="cancel_url" class="btn btn-dim btn-secondary">Cancel</a>
                                                    <button type="submit" id="save_changes" class="btn btn-primary">Save changes</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div><!-- .card -->
                    </div><!-- .nk-block -->
                </div>
            </div>
        </div>
        <AddCompany></AddCompany>
        <AddPerson></AddPerson>
    </div>
</template>

<script>
import $ from 'jquery';
import Form from 'vform';
import Multiselect from 'vue-multiselect';
import AddCompany from './modal/AddNewCompanyModelComponent';
import AddPerson from './modal/AddNewPersonModelComponent';
var Receiver = [];
var fromName = [];
export default {
    name: 'createReferenceId',
    components: {
            AddCompany,
            Multiselect,
            AddPerson,
        },
    data(){
        return{
            keyword:null,
            from_names:[],
            from_data:[],
            Reference_via:[],
            company:[],
            from_name:[],
            courier:[],
            fromNames:[],
            options:[
                { name: 'Call'},
                { name: 'Whatsapp'},
                { name: 'Message'},
                { name: 'Email'},
                { name: 'Letter'},
                { name: 'Courier'},
                { name: 'Hand'}
            ],
            received_date_time:'',
            cancel_url: '/reference',
            referenceSelected: true,
            InwardOrOutward: true,
            form: new Form({
                inward_outward: 1,
                Reference_via: '',
                Date_Time:'',
                companyName:'',
                markssample:'',
                from_name: '',
                from_number:'',
                receiver_number:'',
                from_email:'',
                receiver_email:'',
                parcel_weight:'',
                received_date_time:'',
                delivery_by:'',
                courier_company:'',
                courier_recepit_no:'',
            })
        }
    },
    watch:{
    },
    created(){
        const date = new Date()
        const m = String(date.getMonth() + 1).padStart(2, '0')
        const d = String(date.getDate()).padStart(2, '0')
        const y = String(date.getFullYear())
        const h = String(date.getHours());
        const min = String((date.getMinutes() < 10 ? '0' : '') + date.getMinutes());
        const formatedDate = [y, m, d].join('-');
        const formatedTime = [h,min].join(':');
        this.form.Date_Time = formatedDate;
        this.form.received_date_time = formatedDate+'T'+formatedTime;
        axios.get('/reference/companysearch')
            .then(response => {
                this.company = response.data;
            });
        axios.get('/reference/list-transport')
            .then(response => {
                this.courier   = response.data;
            });
        axios.get('/reference/receiverDetails')
        .then(response=>{
            Receiver = response.data;
            this.form.receiver_number = Receiver.receiverDetails.mobile;
            this.form.receiver_email = Receiver.receiverDetails.email_id;
        });
    },
    methods:{
        getFromName: function(event) {
            if(event != null) {
                axios.get('/reference/from-name/'+event.id)
                .then(response => {
                    fromName = response.data;
                    this.form.from_name = fromName.contact_person_name;
                    this.fromNames = fromName;
                });
            }
        },
        register() {
            $('#save_changes').attr('disabled', true);
            this.form.post('/reference/create-reference/create')
            .then(( response ) => {
                window.location.href = '/reference';
            }).catch(function (error) {
                $('#save_changes').attr('disabled', false);
            });
        },
        showDatePicker () {
            // document.querySelector('input[type=date]').addEventListener("focus", () => {
                document.querySelector('input[type=date]').showPicker();
            // });
        },
        showDateTimePicker () {
            // document.querySelector('input[type=datetime-local]').addEventListener("focus", () => {
                document.querySelector('input[type=datetime-local]').showPicker();
            // });
        },
    },
    mounted(){
    },
}
</script>

<!-- <style src="vue-multiselect/dist/vue-multiselect.css"></style> -->
<style scoped>
    .form-group.code-block {
        border: none;
        padding: 0;
    }
    .form-group.code-block .clipboard-init {
        top: -5px;
        color: #6576ff;
    }
    .form-group.code-block .clipboard-init:hover {
        border-color: #6576ff;
    }
</style>
