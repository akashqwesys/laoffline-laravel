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
                                                    <th>Meter / Pieces</th>
                                                    <th class="text-center">Rate</th>
                                                    <th>Amount</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(product,index) in products" :key="index">
                                                    <input type="hidden" class="form-control" v-model="product.id">
                                                    
                                                    <td>{{ product.name }}<input type="hidden" class="form-control" v-model="product.pieces_meter"><input type="hidden" class="form-control" v-model="product.product_or_fabric_id"></td>
                                                    <td v-if="product.pieces_meter == 0 || product.pieces_meter == 2"><input type="text" class="form-control" v-model="product.pieces" @change="piecechange"><input type="text" class="form-control" v-model="product.meter" @change="piecechange(index, $event)"><input type="text" class="form-control" v-model="product.pieces" @change="piecechange(index, $event)"><input type="hidden" class="form-control" v-model="product.meter"></td>
                                                    <td v-else><input type="text" class="form-control" v-model="product.meter" @change="piecechange"><input type="hidden" class="form-control" v-model="product.pieces"></td>
                                                    <td class="text-center">{{ product.rate }}</td>
                                                    <td><input type="text" class="form-control" v-model="product.amount"></td>
                                                    <td><a class="btn btn-primary" @click="removeProduct">x</a></td>
                                                </tr>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    
                                                    <td></td>
                                                    <td><input type="hidden" class="form-control" v-model="form.meter"><input type="hidden" class="form-control" v-model="form.pieces"></td>
                                                    <td class="text-center"><b>Total Amount :</b></td> 
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
                    let totalMeter = 0;
                    let totalAmount = 0;
                    let totalPm = 0;
                    let amount = 0;
                    this.products.forEach((value1,index1) => {
                    let pieces = value1.pieces;
                    let meter = value1.meter;
                    let amount = value1.amount;
                    let pieces_meter = value1.pieces_meter;
                    
                    if (!pieces) {
                        pieces = 0;
                    }

                    if (!meter) {
                        meter = 0;
                    }
                    
                    if (!pieces_meter) {
                        pieces_meter = 0;
                    }

                    if (pieces_meter == 1) {
                        amount = meter * value1.rate;
                    } else if (pieces_meter == 2) {
                        amount = pieces * value1.rate;
                    }
                    
                    setTimeout(() => {
                        this.products[index1].pieces = pieces;
                        this.products[index1].meter = meter;
                        this.products[index1].amount = amount;
                        this.products[index1].pieces_meter = pieces_meter;
                    }, 500);

                    totalPieces += parseInt(pieces);
                    totalAmount += parseInt(amount);
                    totalMeter += parseInt(meter);
                    totalPm += parseInt(pieces_meter);
                });
                
                setTimeout(() => {
                    this.form.pieces = totalPieces;
                    this.form.meter = totalMeter;
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
                    let totalMeter = 0;
                this.products.forEach((value1,index1) => {
                    let pieces = value1.pieces;
                    let amount = 0;
                    let meter = value1.meter;
                    let pieces_meter = value1.pieces_meter; 

                    if (!pieces) {
                        pieces = 0;
                    }
                    if (!meter) {
                        meter = 0;
                    }

                    if (pieces_meter == 1) {
                        amount = meter * value1.rate;
                    } else if (pieces_meter == 2) {
                        amount = pieces * value1.rate;
                    }
                    setTimeout(() => {
                        this.products[index1].pieces = pieces;
                        this.products[index1].meter = meter;
                        this.products[index1].amount = amount;
                    }, 500);
                    totalMeter += parseInt(meter);
                    totalPieces += parseInt(pieces);
                    totalAmount += parseInt(amount);
                });
                
                setTimeout(() => {
                    this.form.pieces = totalPieces;
                    this.form.totamount = totalAmount;
                    this.form.meter = totalMeter;
                }, 1000);
            },
            piecechange (event) {
                let index1 = event.target.parentElement.parentElement.rowIndex;
                console.log(index1);
                let pieces = this.products[index1-1].pieces;
                let rate = this.products[index1-1].rate;
                let meter = this.products[index1-1].meter;
                let pieces_meter = this.products[index1-1].pieces_meter;

                let amount = 0;
                if (pieces_meter  == 1) {
                    amount = meter * rate;
                } else if (pieces_meter == 2) {
                    amount = pieces * rate;
                }
               
                this.products[index1-1].amount = amount;

                    let totalMeter = 0;
                    let totalPieces = 0;
                    let totalAmount = 0;
                    this.products.forEach((value1,index1) => {
                    let pieces = value1.pieces;
                    let amount = value1.amount;
                    let meter = value1.meter;
                    if (!meter){
                        meter = 0;
                    }
                    if (!pieces) {
                        pieces = 0;
                    }
                    if (!amount){
                        amount = 0;
                    }
                    setTimeout(() => {
                        this.products[index1].pieces = pieces;
                        this.products[index1].meter = meter;
                        this.products[index1].amount = amount;
                    }, 500);
                    totalPieces += parseInt(pieces);
                    totalAmount += parseInt(amount);
                    totalMeter += parseInt(meter);
                });
                
                setTimeout(() => {
                    this.form.pieces = totalPieces;
                    this.form.meter = totalMeter;
                    this.form.totamount = totalAmount;
                }, 1000);
            },
            register () {
                    console.log(this.attechment);
                    let data = new FormData();
                    data.append('salebills', JSON.stringify(this.form));
                    data.append('products', JSON.stringify(this.products));
                    data.append('grattechment', this.attechment);

                    // this.attechment.forEach((contact,index)=>{
                    // if(contact){
                    //     data.append(`grattechment[${index}]`, contact);
                    // }else{
                    //     data.append(`grattechment[${index}]`, null);
                    // }
                //})
                    axios.post('/payments/updategoodreturn', data)
                        .then(() => {
                            window.location.href = '/payments';
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

<style scoped>
    .form-control-wrap img {
        position: absolute;
        width: 45px;
    }
    .form-control-wrap .custom-file.profilePic {
        width: 85%;
        float: right;
    }
    </style>
