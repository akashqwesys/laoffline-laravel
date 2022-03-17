<template>

    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 v-if="scope == 'edit'" class="nk-block-title page-title">Edit Financial Year</h3>
                                <h3 v-else class="nk-block-title page-title">Add Financial Year</h3>
                                <div class="nk-block-des text-soft">
                                    <p>Please fill the all details.</p>
                                </div>
                            </div><!-- .nk-block-head-content -->
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    <div class="nk-block">
                        <div class="card card-bordered">
                            <div class="card-inner">
                                <form action="#" class="form-validate" @submit.prevent="register()">
                                    <input type="hidden" v-if="scope == 'edit'" id="fv-group-id" v-model="form.id">
                                    <input type="hidden" id="user_group_id" v-model="form.user_group">
                                    <div class="preview-block">
                                        <div class="row gy-4">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-first-name">Name</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="fv-first-name" v-model="form.name">
                                                        <span v-if="errors.name" class="invalid">{{errors.name}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-start-date">Start Date</label>
                                                    <div class="form-control-wrap">
                                                        <input type="date" class="form-control" id="fv-start-date" v-on:input="checkStartdate($event.target.value)" v-model="form.startdate">
                                                        <span v-if="errors.startdate" class="invalid">{{errors.startdate}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-end-date">End Date</label>
                                                    <div class="form-control-wrap">
                                                        <input type="date" class="form-control" id="fv-end-date" v-model="form.enddate" v-on:input="checkEnddate($event.target.value)" >
                                                    <span v-if="errors.enddate" class="invalid">{{errors.enddate}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-inv-prefix">Invoice Prefix</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="fv-inv-prefix" v-model="form.invprefix">
                                                        <span v-if="errors.invprefix" class="invalid">{{errors.invprefix}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="preview-block">
                                                    <label class="form-label">Current Year</label>
                                                    <ul class="custom-control-group g-3 align-center">
                                                        <li>
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" class="custom-control-input" name="current_year" @change="onChange($event)" v-model="form.current_year" id="fv-yes" value="1" >
                                                                <label class="custom-control-label" for="fv-yes">Yes</label>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" class="custom-control-input" name="current_year" @change="onChange($event)" v-model="form.current_year" id="fv-no" value="0" >
                                                                <label class="custom-control-label" for="fv-no">No</label>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                    <span v-if="errors.current_year" class="invalid">{{errors.current_year}}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row gy-4">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <a v-bind:href="cancel_url" class="btn btn-dim btn-secondary">Cancel</a>
                                                    <button type="submit" class="btn btn-primary">Save changes</button>
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
    </div>
</template>

<script>
    import Form from 'vform';

    var userGroup = [];
    var gData = [];
    var user_group = '';
    var element = '';
    export default {
        name: 'createFinancialYear',
        props: {
            scope: String,
            id: Number,
        },
        data() {
            return {
                cancel_url: '/financialyear',
                userGroups: [],
                errors: {
                    name: '',
                    startdate: '',
                    enddate: '',
                    current_year: '',
                    inv_prefix: '',
                },
                form: new Form({
                    id: '',
                    name: '',
                    startdate: '',
                    enddate: '',
                    current_year: '',
                    inv_prefix: '',
                })
            }
        },
        created() {
            axios.get('/financialyear/list-data')
            .then(response => {
                userGroup = response.data;
                this.userGroups = userGroup;
            });
        },
        methods: {
            onChange(event) {
              var data = event.target.value;
              if(data == '1'){
                  let text = "Are you ok? All other Finacial year will done off";
                    if (confirm(text) == true) {
                        this.form.current_year = '1';
                    } else {
                        this.form.current_year = '0';
                    }
              }
            },
            checkEnddate(value) {
                let startdate = this.form.startdate;
                let enddate = value;
                if (!startdate) {
                    alert('Select Start Date First');
                    this.form.enddate = null;
                } else {
                    if (enddate > startdate) {
                        console.log('yes');
                    } else {
                        alert('End Date must be greter than Start Date');
                        this.form.enddate = null;
                    }
                }
            },
            checkStartdate(value) {
                let startdate = value;
                let enddate = this.form.enddate;
                if (enddate) {
                    if (startdate > enddate) {
                        alert('Start Date must be Less than End Date');
                        this.form.startdate = null;
                    }
                }
            },
            register () {
                if (this.scope == 'edit') {
                    this.form.post('/financialyear/update')
                        .then(() => {
                            window.location.href = '/financialyear';
                    })
                    .catch((error) => {
                        var validationError = error.response.data.errors;

                        if(validationError.name) {
                            this.errors.name = validationError.name[0];
                        }
                        if(validationError.startdate) {
                            this.errors.startdate = validationError.startdate[0];
                        }
                        if(validationError.enddate) {
                            this.errors.enddate = validationError.end_date[0];
                        }
                        if(validationError.current_year) {
                            this.errors.current_year = validationError.current_year[0];
                        }
                        if(validationError.invprefix) {
                            this.errors.invprefix = validationError.invprefix[0];
                        }
                    })
                } else {
                    this.form.post('/financialyear/create')
                        .then(() => {
                            window.location.href = '/financialyear';
                    })
                    .catch((error) => {
                        var validationError = error.response.data.errors;

                        if(validationError.name) {
                            this.errors.name = validationError.name[0];
                        }
                        if(validationError.startdate) {
                            this.errors.startdate = validationError.startdate[0];
                        }
                        if(validationError.enddate) {
                            this.errors.enddate = validationError.end_date[0];
                        }
                        if(validationError.current_year) {
                            this.errors.current_year = validationError.current_year[0];
                        }
                        if(validationError.invprefix) {
                            this.errors.invprefix = validationError.invprefix[0];
                        }
                    })
                }
            },
        },
        mounted() {
            switch (this.scope) {
                case 'edit' :
                    axios.get(`/financialyear/fetch-year/${this.id}`)
                    .then(response => {
                        gData = response.data;

                        this.form.id = gData.id;
                        this.form.name = gData.name;
                        this.form.startdate = gData.start_date;
                        this.form.enddate = gData.end_date;
                        this.form.current_year = gData.current_year_flag;
                        this.form.invprefix = gData.inv_prefix;
                    });
                    break;
                default:
                    break;
            }
        },
    };
</script>
<style src="vue-multiselect/dist/vue-multiselect.css"></style>
<style scoped>
    .form-control-wrap img {
        position: absolute;
        width: 45px;
    }
    .form-control-wrap .custom-file.profilePic {
        width: 85%;
        float: right;
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
    }
    .multiselect__tag-icon:focus, .multiselect__tag-icon:hover {
        background: #ebeef2;
    }
    .multiselect__tag-icon:focus:after, .multiselect__tag-icon:hover:after {
        color: #526484;
    }
</style>
