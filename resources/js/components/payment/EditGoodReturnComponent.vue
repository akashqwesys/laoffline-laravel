<template>
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block">
                        <div class="card card-bordered">
                            <div class="card-header">
            
                                <h6 class="nk-block-title page-title">Edit Good Return</h6>
                            </div>
                            <div class="card-inner">
                                <form action="#" class="form-validate" @submit.prevent="register()">
                                <input type="hidden" v-model="form.id">
                                <div class="my-2">
                                    <div class="row">
                                        <table class="table table-striped m-b-none">
                                            <thead>
                                                <tr>
                                                    <th>S.No.</th>
                                                    <th>Sup Inv No</th>
                                                    <th>Amount</th>
                                                    <th>Goods Return</th>
                                                    <th>Attachment</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>{{ form.sr_no}}</td>
                                                    <td>{{ form.sup_inv}}</td>
                                                    <td>{{ form.amount}}</td>
                                                    <td>{{ form.goodreturn}}</td>
                                                    <td><div class="form-control-wrap">
                                                            <div class="custom-file">
                                                                <input type="file" name="attechment"  accept="image/*" class="custom-file-input" @change="uploadAttechment">
                                                                <label class="custom-file-label" for="fv-attechment">Choose photo</label>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="row my-2">
                                        <h6>Sale Bill Item Details</h6>
                                        <table class="table table-striped m-b-none">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>meter</th>
                                                    <th>Pieces_Meter</th>
                                                    <th>Pieces</th>
                                                    <th class="text-center">Rate</th>
                                                    <th>Amount</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(product,index) in products" :key="index">
                                                    <input type="hidden" class="form-control" v-model="product.id">
                                                    
                                                    <td>{{ product.name }}<input type="hidden" class="form-control" v-model="product.product_or_fabric_id"></td>
                                                    <td><input type="text" class="form-control" v-model="product.meter"></td>
                                                    <td><input type="text" class="form-control" v-model="product.pieces_meter"></td>
                                                    <td><input type="text" class="form-control" v-model="product.pieces" @change="pricechange"></td>
                                                    <td class="text-center">{{ product.rate }}</td>
                                                    <td><input type="text" class="form-control" v-model="product.amount"></td>
                                                    <td><a class="btn btn-primary" @click="removeProduct">x</a></td>
                                                </tr>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    
                                                    <td>Total:</td>
                                                    <td><input type="text" class="form-control" v-model="form.meter"></td>
                                                    <td><input type="text" class="form-control" v-model="form.pieces_meter"></td>
                                                    <td><input type="text" class="form-control" v-model="form.pieces"></td>
                                                    <td class="text-center">total Amount :</td> 
                                                    <td><input type="text" class="form-control" v-model="form.totamount"></td>
                                                    <td></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                                <div class="row gy-4">
                                    <div class="col-md-12">
                                        <div class="form-group text-right">
                                            <a v-bind:href="cancel_url" class="mx-2 btn btn-dim btn-secondary">Cancel</a>
                                            <button type="submit" class="btn btn-primary">Update</button>
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

    var gData = [];
    export default {
        name: 'editGoodReturn',
        props: {
            scope: String,
            id: Number,
        },
        data() {
            return {
                cancel_url: '/payments',
                userGroups: [],
                products: [{
                    id:'',
                    product_or_fabric_id: '',
                    name: '',
                    meter: '',
                    pieces_meter: '',
                    pieces: '',
                    rate: '',
                    amount: '',
                }],
                attechment: [],
                form: new Form({
                    id : '',
                    sr_no: '',
                    sup_inv: '',
                    amount: '',
                    goodreturn: '',
                    pieces: '',
                    meter: '',
                    pieces_meter: '',
                    totamount: '',
                })
            }
        },
        created() {
            axios.get(`/payments/fetch-goodreturn/${this.id}`)
            .then(response => {
                    this.form.id = this.id;
                    this.form.sr_no = response.data.sale_bill_id;
                    this.form.sup_inv = response.data.supp_invoice_no;
                    this.form.amount = response.data.amount;
                    this.form.goodreturn = response.data.goods_return;
                    this.form.pieces = response.data.tot_pieces;
                    this.form.meter = response.data.tot_meters;
                    this.form.totamount = response.data.amount;
                    this.products = response.data.products;

                    let totalPieces = 0;
                    let totalAmount = 0;
                    let totalPm = 0;
                    let amount = 0;
                    this.products.forEach((value1,index1) => {
                    let pieces = value1.pieces;
                    let amount = value1.amount;
                    let pieces_meter = value1.pieces_meter;
                    
                    if (!pieces) {
                        pieces = 0;
                    }
                    
                    if (!pieces_meter) {
                        pieces_meter = 0;
                    }
                    amount = pieces * value1.rate;
                    setTimeout(() => {
                        this.products[index1].pieces = pieces;
                        this.products[index1].amount = amount;
                        this.products[index1].pieces_meter = pieces_meter;
                    }, 500);
                    totalPieces += parseInt(pieces);
                    totalAmount += parseInt(amount);
                    totalPm += parseInt(pieces_meter);
                });
                
                setTimeout(() => {
                    this.form.pieces = totalPieces;
                    this.form.totamount = totalAmount;
                    this.form.pieces_meter = totalPm;
                }, 1000);
            
            });
                 
        },
        methods: {
            uploadAttechment (event) {
                this.attechment = event.target.files[0];
            },
            removeProduct (event) {
                let index1 = event.target.parentElement.parentElement.rowIndex;
                this.products.splice(index1-1, 1);
                
                    let totalPieces = 0;
                    let totalAmount = 0;
                    this.products.forEach((value1,index1) => {
                    let pieces = value1.pieces;
                    let amount = value1.amount;
                    
                    if (!pieces) {
                        pieces = 0;
                    }
                    if (!amount){
                        amount = 0;
                    }
                    setTimeout(() => {
                        this.products[index1].pieces = pieces;
                        this.products[index1].amount = amount;
                    }, 500);
                    totalPieces += parseInt(pieces);
                    totalAmount += parseInt(amount);
                });
                
                setTimeout(() => {
                    this.form.pieces = totalPieces;
                    this.form.totamount = totalAmount;
                }, 1000);
            },
            pricechange (event) {
                let index1 = event.target.parentElement.parentElement.rowIndex;
                console.log(index1);
                let pieces = this.products[index1-1].pieces;
                let rate = this.products[index1-1].rate;
                let amount = pieces * rate;
               
                this.products[index1-1].amount = amount;

                
                    let totalPieces = 0;
                    let totalAmount = 0;
                    this.products.forEach((value1,index1) => {
                    let pieces = value1.pieces;
                    let amount = value1.amount;
                    
                    if (!pieces) {
                        pieces = 0;
                    }
                    if (!amount){
                        amount = 0;
                    }
                    setTimeout(() => {
                        this.products[index1].pieces = pieces;
                        this.products[index1].amount = amount;
                    }, 500);
                    totalPieces += parseInt(pieces);
                    totalAmount += parseInt(amount);
                });
                
                setTimeout(() => {
                    this.form.pieces = totalPieces;
                    this.form.totamount = totalAmount;
                }, 1000);
            },
            register () {
                    console.log(this.attechment);
                    let data = new FormData();
                    data.append('salebills', JSON.stringify(this.form));
                    data.append('products', JSON.stringify(this.products));
                    //data.append('attechment', this.attechment);

                    this.attechment.forEach((contact,index)=>{
                    if(contact){
                        data.append(`grattechment[${index}]`, contact);
                    }else{
                        data.append(`grattechment[${index}]`, null);
                    }
                })
                    axios.post('/payments/updategoodreturn', data)
                        .then(() => {
                            //window.location.href = '/payments';
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
                
            },
        },
        mounted() {
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
