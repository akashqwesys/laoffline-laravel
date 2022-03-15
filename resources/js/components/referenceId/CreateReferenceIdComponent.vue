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
                                                        <input type="date" class="form-control" id="fv-Reference-date" v-model="form.Date_Time">
                                                    </div>
                                                </div>
                                            </div>
                                                <div class="col-md-4">
                                                    <div class="form-group code-block">
                                                        <label class="form-label" for="fv-company">Company</label>
                                                        <button type="button" class="btn btn-sm clipboard-init" data-toggle="modal" data-target="#addCompany" title="Company Inserts"><span class="clipboard-text">Add New</span></button>
                                                        <div>
                                                            <multiselect v-model="form.companyName" :options="company" placeholder="Select one" label="company_name" track-by="id" @input="getFromName"></multiselect>
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
                                                                <!-- <multiselect v-model="form.from_name" :options="fromNames" placeholder="Select one" label="contact_person_name" track-by="contact_person_name"></multiselect> -->
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
                                                                <!-- <multiselect v-model="form.from_name" :options="fromNames" placeholder="Select one" label="contact_person_name" track-by="contact_person_name"></multiselect> -->
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
                                                                <!-- <multiselect v-model="form.from_name" :options="fromNames" placeholder="Select one" label="contact_person_name" track-by="contact_person_name"></multiselect> -->
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
                                                                <input type="datetime-local" class="form-control" id="fv-received_date_time" v-model="form.received_date_time" placeholder="Enter Received Date and Time">
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
                                                                <!-- <multiselect v-model="form.from_name" :options="fromNames" placeholder="Select one" label="contact_person_name" track-by="contact_person_name"></multiselect> -->
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
                                                        <label class="form-label" for="fv-courier-recepit-no">Courier Recepit No</label>
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
                                                                <input type="datetime-local" class="form-control" id="fv-received_date_time" v-model="form.received_date_time" placeholder="Enter Received Date and Time">
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
import Multiselect from 'vue-multiselect';
import AddCompany from './modal/AddNewCompanyModelComponent';
import AddPerson from './modal/AddNewPersonModelComponent';
var Receiver = [];
var fromName = [];
export default{
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
            { name: 'Hand'},
            { name: 'Email'},
            { name: 'Whatsapp'},
            { name: 'Message'},
            { name: 'Call'},
            { name: 'Courier'}
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
        const min = String(date.getMinutes());
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
                console.log(this.courier   = response.data);
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
                    console.log(event);
                    axios.get('/reference/from-name/'+event.id)
                    .then(response => {
                        fromName = response.data;
                        console.log(fromName.contact_person_name);
                        this.form.from_name = fromName.contact_person_name;
                        this.fromNames = fromName;
                    });
                }
            },
        register() {
                    this.form.post('/reference/create-reference/create')
                        .then(( response ) => {
                            window.location.href = '/reference/create-reference';
                    });
                },
        },
    mounted(){
        console.log("This is about Component");
    },
}

</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<style>
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


    .multiselect {
        height: calc(2.125rem + 2px);
        font-family: Roboto,sans-serif;
        font-size: 13px;
        font-weight: 400;
        background-color: #fff;
        border: none;
        border-radius: 4px;
        box-shadow: none;
        transition: all 0.3s;
        min-height: 36px;
        display: inline-flex;
        flex-wrap: wrap;
    }
    .multiselect__tag-icon:after {
        color: #526484;
    }
    .multiselect__tag {
        color: #526484;
        background: #ebeef2;
        font-size: 13px;
        font-family: Roboto,sans-serif;
    }
    .multiselect__tags {
        padding: 7px 16px;
        font-size: 13px;
        min-height: 36px;
        border: 1px solid #dbdfea;
        width: 100%;
    }
    .multiselect__placeholder {
        margin-bottom: 0;
        padding-top: 0;
    }
    .multiselect__select {
        height: calc(2.125rem + 2px);
        position: absolute;
        top: 0;
        right: 0;
        width: calc(2.125rem + 2px);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .multiselect__select:before {
        display: none;
    }
    .multiselect .multiselect__select:after {
        font-family: "Nioicon";
        content: "";
        line-height: 1;
    }
    .multiselect.multiselect--active .multiselect__input, .multiselect__single {
        font-size: 13px;
        padding: 0;
        margin-bottom: 0;
        width: 98% !important;
    }
    .multiselect__content-wrapper {
        border-top: 1px solid #dbdfea;
        padding: 6px;
        top: 36px;
    }
    .multiselect__option--highlight {
        background: #ebeef2;
        border-radius: 4px;
        color: #526484;
    }
    .multiselect__element {
        margin-bottom: 0.125rem;
    }
    .multiselect__option--highlight:after, .multiselect__option:after {
        display: none;
    }
    .multiselect__option--selected.multiselect__option--highlight {
        background: #f3f3f3;
        color: #35495e;
    }
    .multiselect__option--selected {
        font-weight: 500;
    }
    .multiselect__tags-wrap {
        display: inline-flex;
    }
    .multiselect--above .multiselect__content-wrapper {
        border: 1px solid #e8e8e8;
        position: unset;
    }
    .multiselect__tag-icon:focus, .multiselect__tag-icon:hover {
        background: #ebeef2;
    }
    .multiselect__tag-icon:focus:after, .multiselect__tag-icon:hover:after {
        color: #526484;
    }
</style>
