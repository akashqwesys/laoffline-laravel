<template>    
    <div class="modal fade" id="addCity">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add City</h5>
                    <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                        <em class="icon ni ni-cross"></em>
                    </a>
                </div>
                <div class="modal-body">
                    <form action="#" @submit.prevent="register()">
                        <div class="preview-block">
                            <div class="row gy-4">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label" for="fv-country">Country</label>
                                        <div>
                                            <select v-model="form.country" class="form-control" id="fv-country" data-placeholder="Select a option" required>
                                                <option v-for="country in countries" :key="country.id" :value="country.id">{{country.name}}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label" for="fv-state">State</label>
                                        <div>
                                            <select v-model="form.state" class="form-control" id="fv-state" data-placeholder="Select a option" required>
                                                <option v-for="state in states" :key="state.id" :value="state.id">{{state.name}}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label" for="fv-city-name">Name</label>
                                        <div class="form-control-wrap">
                                            <input type="text" class="form-control" id="fv-city-name" v-model="form.name" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label" for="fv-std-code">STD Code</label>
                                        <div class="form-control-wrap">
                                            <input type="text" class="form-control" id="fv-std-code" v-model="form.std_code" required>
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
    var cities = [];
    export default {
        name: 'createCities',
        data() {
            return {
                countries: [],
                states: [],
                form: new Form({
                    id: '',
                    country: '',
                    state: '',
                    name: '',                    
                    std_code: '',
                })
            }
        },
        created() {
            axios.get('/settings/cities/list-country')
            .then(response => {
                this.countries = response.data;
            });
            axios.get('/settings/cities/list-state')
            .then(response => {
                this.states = response.data;
            });
        },
        methods: {
            register () {
                this.form.post('/settings/cities/create')
                    .then(( response ) => {
                        // window.location.href = '/databank/company/create-company';
                        $('#addCity').hide();
                        $('.modal-backdrop').remove();
                        $('body').removeClass('modal-open');
                        $('body').removeAttr('style');
                })
            },
        },
        mounted() {

        },
    };
</script>