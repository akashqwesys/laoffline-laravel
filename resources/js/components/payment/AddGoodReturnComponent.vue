<template>
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block">
                        <div class="card card-bordered">
                            <div class="card-header">
            
                                <h6 class="nk-block-title page-title">Add Good Return</h6>
                            </div>
                            <div class="card-inner">
                                <form action="#" class="form-validate" @submit.prevent="register()">
                                <input type="hidden" v-if="scope == 'edit'" v-model="form.id">
                                <div class="my-2" v-for="(salebill,index) in salebills" :key="index">
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
                                                    <td>{{ salebill.sr_no }}</td>
                                                    <td>{{ salebill.supplier_invoice_no }}</td>
                                                    <td>{{ salebill.amount }}</td>
                                                    <td>{{ salebill.goods_return }}</td>
                                                    <td><div class="form-control-wrap">
                                                            <div class="custom-file">
                                                                <input type="file" name="attechment"  accept="image/*" class="custom-file-input" @change="uploadAttechment(index, $event)">
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
                                                    <th>Meter</th>
                                                    <th>Pieces_Meter</th>
                                                    <th>Pieces</th>
                                                    <th class="text-center">Rate</th>
                                                    <th>Amount</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(product,index1) in salebills[index].products" :key="index1">
                                                    <input type="hidden" class="form-control" v-model="product.id">
                                        
                                                    <td>{{ product.name }}<input type="hidden" class="form-control" v-model="product.product_or_fabric_id"></td>
                                                    <td><input type="text" class="form-control" v-model="product.meter" @change="piecechange(index, $event)"></td>
                                                    <td><input :readonly="true" type="text" class="form-control" v-model="product.pieces_meter"></td>
                                                    <td><input type="text" class="form-control" v-model="product.pieces" @change="piecechange(index, $event)"></td>
                                                    <td class="text-center">{{ product.rate }}</td>
                                                    <td><input type="text" class="form-control" v-model="product.amount"></td>
                                                    <td><a class="btn btn-primary" @click="removeProduct(index, $event)">x</a></td>
                                                </tr>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    
                                                    <td>Total:</td>
                                                    <td><input type="text" class="form-control" v-model="salebill.meter"></td>
                                                    <td><input type="text" class="form-control" v-model="salebill.pieces_meter"></td>
                                                    <td><input type="text" class="form-control" v-model="salebill.pieces"></td>
                                                    <td class="text-center">total Amount :</td>
                                                    <td><input type="text" class="form-control" v-model="salebill.totamount"></td>
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
                                            <button type="submit" class="btn btn-primary">Save changes</button>
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
        name: 'addGoodReturn',
        props: {
            scope: String,
            id: Number,
        },
        data() {
            return {
                cancel_url: '/payments',
                userGroups: [],
                salebills: [{
                    sr_no: '',
                    sup_inv: '',
                    amount: '',
                    goodreturn: '',
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
                    meter: '',
                    pieces_meter: '',
                    pieces: '',
                    totamount: '',
                }],
                attechment: [],
                form: new Form({
                    id : '',
                })
            }
        },
        created() {
            axios.get('/payments/getsalebillwithproduct')
            .then(response => {
                
                this.salebills = response.data;
                this.salebills.forEach((value,index)=>{
                    
                    let totalPieces = 0;
                    let totalAmount = 0;
                    let totalMeter = 0;
                    let totalpm = 0;
                    this.salebills[index].products.forEach((value1,index1) => {
                    let meter = value1.meter;
                    let pieces = value1.pieces;
                    let amount = value1.amount;
                    let pm = value1.pieces_meter;
                    
                    if (!pieces) {
                        pieces = 0;
                    }
                    if (!amount){
                        amount = 0;
                    }
                    if (!meter){
                        meter = 0;
                    }
                    if (!pm) {
                        pm = 0;
                    }
                    setTimeout(() => {
                        this.salebills[index].products[index1].pieces = pieces;
                        this.salebills[index].products[index1].amount = amount;
                        this.salebills[index].products[index1].meter = meter;
                        this.salebills[index].products[index1].pieces_meter = pm;
                    }, 500);
                    totalPieces += parseInt(pieces);
                    totalAmount += parseInt(amount);
                    totalMeter += parseInt(meter);
                    totalpm += parseInt(pm);
                });
                
                setTimeout(() => {
                    this.salebills[index].pieces = totalPieces;
                    this.salebills[index].totamount = totalAmount;
                    this.salebills[index].meter = totalMeter;
                    this.salebills[index].pieces_meter = totalpm;
                }, 1000);
                });  
            });
                 
        },
        methods: {
            uploadAttechment (index, event) {
                this.attechment[index] = event.target.files[0];
                console.log(this.attechment);
            },
            removeProduct (index, event) {
                let index1 = event.target.parentElement.parentElement.rowIndex;
                this.salebills[index].products.splice(index1-1, 1);
                
                this.salebills.forEach((value,index)=>{
                    let totalPieces = 0;
                    let totalAmount = 0;
                    let totalMeter = 0;
                    
                    this.salebills[index].products.forEach((value1,index1) => {
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
                        this.salebills[index].products[index1].pieces = pieces;
                        this.salebills[index].products[index1].meter = meter;
                        this.salebills[index].products[index1].amount = amount;
                    }, 500);
                    totalPieces += parseInt(pieces);
                    totalAmount += parseInt(amount);
                    totalMeter += parseInt(meter);
                });
                
                setTimeout(() => {
                    this.salebills[index].pieces = totalPieces;
                    this.salebills[index].totamount = totalAmount;
                    this.salebills[index].meter = totalMeter;
                }, 1000);
                }); 
            },
            piecechange (index, event) {
                
                let index1 = event.target.parentElement.parentElement.rowIndex;
                let pieces = this.salebills[index].products[index1-1].pieces;
                let meter = this.salebills[index].products[index1-1].meter;
                let pieces_meter = this.salebills[index].products[index1-1].pieces_meter;
                let rate = this.salebills[index].products[index1-1].rate;
                let amount = 0;
                if (pieces_meter  == 1) {
                    amount = meter * rate;
                } else if (pieces_meter == 2) {
                    amount = pieces * rate;
                }
                
               
                this.salebills[index].products[index1-1].amount = amount;

                this.salebills.forEach((value,index)=>{
                    let totalPieces = 0;
                    let totalAmount = 0;
                    let totalMeter = 0;
                    this.salebills[index].products.forEach((value1,index1) => {
                    let pieces = value1.pieces;
                    let amount = value1.amount;
                    let meter = value1.meter;
                    
                    if (!pieces) {
                        pieces = 0;
                    }
                    if (!amount){
                        amount = 0;
                    }
                    if (!meter){
                        meter = 0;
                    }
                    setTimeout(() => {
                        this.salebills[index].products[index1].pieces = pieces;
                        this.salebills[index].products[index1].meter = meter;
                        this.salebills[index].products[index1].amount = amount;
                    }, 500);
                    totalPieces += parseInt(pieces);
                    totalMeter += parseInt(meter);
                    totalAmount += parseInt(amount);
                });
                
                setTimeout(() => {
                    this.salebills[index].pieces = totalPieces;
                    this.salebills[index].meter = totalMeter;
                    this.salebills[index].totamount = totalAmount;
                }, 1000);
                });
            },
            register () {
                    console.log(this.attechment);
                    let data = new FormData();
                    data.append('salebills', JSON.stringify(this.salebills));
                    //data.append('attechment', this.attechment);

                    this.attechment.forEach((contact,index)=>{
                    if(contact){
                        data.append(`grattechment[${index}]`, contact);
                    }else{
                        data.append(`grattechment[${index}]`, null);
                    }
                })
                    axios.post('/payments/insertgoodreturn', data)
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
