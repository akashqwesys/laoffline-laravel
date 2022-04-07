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
				                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="itm in items" :key="itm.sallbillid" class="text-center">
                                            <td><input type="checkbox" class="d-block" v-model="selected" :id="itm.sallbillid" :value="itm.sallbillid"  required></td>
				                            <td>{{ itm.sallbillid}}</td>
				                            <td>{{ itm.financialyear }}</td>
				                            <td>{{ itm.invoiceid}}</td>
				                            <td>{{ itm.date}}</td>
				                            <td>{{ itm.supplier }}</td>
                                            <td>{{ itm.amount }}</td>
                                            <td>{{ itm.overdue }}</td>
				                            <td><em class="icon ni ni-eye"></em></td>
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
                items :[
                    { sallbillid: 101, financialyear: "2020-2021", invoiceid: "201", date: '2021-03-15', supplier: "Dadan Soft", amount:"200", overdue: "15" },
                    { sallbillid: 103, financialyear: "2020-2021", invoiceid: "202", date: '2021-03-15', supplier: "Dadan Soft", amount:"250", overdue: "15" },
                    { sallbillid: 104, financialyear: "2020-2021", invoiceid: "203", date: '2021-03-15', supplier: "Dadan Soft", amount:"252", overdue: "15" },
                    { sallbillid: 106, financialyear: "2020-2021", invoiceid: "204", date: '2021-03-15', supplier: "Dadan Soft", amount:"285", overdue: "15" },
                    ],
                selected: [],
            }
        },
        methods: {
            selectSalebill(event){
                alert(this.selected);
               axios.post('/payments/selectsalebills', {
                    salebill: this.selected
                })
                .then(responce => {
                    //window.location.href = '/payments/addpayment';
                })
            }
        },
    };
</script>
<style>
    #addSalebill .modal-dialog{
        max-width: 920px;
    }
</style>
