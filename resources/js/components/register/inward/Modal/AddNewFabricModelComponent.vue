<template>
    <div class="modal fade" id="AddFabric">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Fabric</h5>
                    <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                        <em class="icon ni ni-cross"></em>
                    </a>
                </div>
                <div class="modal-body">
                    <form action="#" @submit.prevent="register()">
                        <div class="preview-block">
                            <div class="preview-block">
                                <div class="row gy-4">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label" for="fv-name">Name</label>
                                            <div class="form-control-wrap">
                                                <input type="text" class="form-control" id="fv-name" v-model="FabricsData.name" placeholder="Enter Name" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label" for="fv-first-name">Main Category</label>
                                            <div class="form-control-wrap">
                                                <multiselect v-model="FabricsData.mainCategory" :options="mainCategories" placeholder="Select one" label="name" track-by="id"></multiselect>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr class="preview-hr">
                            <div class="row gy-4">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <a href="#" data-dismiss="modal" aria-label="Close" class="btn btn-dim btn-secondary">Cancel</a>
                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Multiselect from 'vue-multiselect';
    export default {
        name: 'Fabric',
        props: {
            type: Number,
        },
        components: {
            Multiselect,
        },
        data() {
            return {
                inwardType: '',
                mainCategories:[],
                form: new Form({
                    name:'',
                    mainCategory:'',
                }),
            }
        },
        created() {
            console.log(this.inwardType);
            axios.get('/register/main-categories')
            .then(response => {
                this.mainCategories = response.data;
            });
        },
        methods: {            
            register() {
                this.form.post('/register/inward/call/add-fabrics-details')
                    .then(( response ) => {
                        window.location.href = '/register/inward/call';
                })
            }
        },
        mounted() {
            console.log(this.type);
            if(this.type == 1) {
                this.inwardType = 'call';
            } else if(this.type == 2) {
                this.inwardType = 'message';
            } else if(this.type == 3) {
                this.inwardType = 'whatsapp';
            } else if(this.type == 4) {
                this.inwardType = 'letter';
            } else if(this.type == 5) {
                this.inwardType = 'sample';
            } else if(this.type == 6) {
                this.inwardType = 'email';
            }
        },
    };
</script>
<style>
    #AddPerson .modal-dialog {
        max-width: 1000px;
    }
</style>
<style>
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
