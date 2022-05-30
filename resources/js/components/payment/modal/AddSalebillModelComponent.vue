<template>
    <div class="modal fade" id="addSalebill">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Salebills</h5>
                    <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                        <em class="icon ni ni-cross"></em>
                    </a>
                </div>
                <div class="modal-body">
                        <div class="preview-block">
                                <table id="salebills" class="table mb-2 table-hover">
                                    <thead>
                                        <tr>
                                            <th>Select</th>
				                            <th>Sall Bill Id</th>
				                            <th>Financial Year</th>
				                            <th>Supplier Invoice No</th>
				                            <th>Date</th>
				                            <th>Supplier</th>
                                            <th>Bill Amount</th>
                                            <th>Overdue</th>
				                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="itm in items" :key="itm.sallbillid" class="text-center">
                                            <td><input type="checkbox" class="d-block" v-model="selected" :id="itm.sallbillid" :value="itm.sallbillid"  required></td>
				                            <td>{{ itm.sallbillid}}</td>
				                            <td>{{ itm.financialyear.name }}</td>
				                            <td>{{ itm.invoiceid}}</td>
				                            <td>{{ itm.date}}</td>
				                            <td>{{ itm.supplier }}</td>
                                            <td>{{ itm.amount }}</td>
                                            <td>{{ itm.overdue }}</td>
				                            
                                        </tr>
                                    </tbody>
                                </table>
                                <button class="btn btn-primary generatepayment float-right" @click="selectSalebill($event)">Select Salebill</button>
                        </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    var selected = [];
    var items = [];
    export default {
        name: 'addSalebill',
        data() {
            return {
                items :[],
                selected: [],
            }
        },
        created() {
            // var main_url = location.href.split('/');
            // if (main_url[main_url.length - 2] == 'edit-payment') {
            //     var getsalbillforadd_url = '/payments/getsalbillforadd?payment_id=' + this.$parent.id ;
            // } else {
            //     var getsalbillforadd_url = '/payments/getsalbillforadd';
            // }
            // axios.get(getsalbillforadd_url)
            // .then(responce => {
            //     this.items = responce.data.salebilldata;
            // });
        },
        methods: {
            selectSalebill(event){
                this.selected.forEach(value => {
                for(var i = 0; i < this.items.length; i++) {
                    if (this.items[i].sallbillid && this.items[i].sallbillid === value) { 
                        this.items.splice(i, 1);
                        break;
                }
                }
                });
                
                axios.post('/payments/selectsalebills', {
                    salebill: this.selected
                })
                .then(responce => {
                    $.merge(this.$parent.salebills,responce.data.salebill);
                    let totalamount = 0;
                    let totalAdjustamount = 0;
                    this.$parent.salebills.forEach(value => {
                        totalAdjustamount += parseInt(value.adjustamount);
                        totalamount += parseInt(value.amount);
                    });
                    this.$parent.form.totalamount = totalamount;
                    this.$parent.form.totaladjustamount = totalAdjustamount;
                    
                    $('#addSalebill').hide();
                    $('.modal-backdrop').remove();
                    this.selected = [];
                    //window.location.href = '/payments/addpayment';
                })
            }
        },
        mounted() {
            var main_url = location.href.split('/');
            if (main_url[main_url.length - 2] == 'edit-payment') {
                var getsalbillforadd_url = '/payments/getsalbillforadd?payment_id=' + this.$parent.id ;
            } else {
                var getsalbillforadd_url = '/payments/getsalbillforadd';
            }
            axios.get(getsalbillforadd_url)
            .then(responce => {
                this.items = responce.data.salebilldata;
            });
        }
    };
</script>
<style>
    #addSalebill .modal-dialog{
        max-width: 920px;
    }
</style>
