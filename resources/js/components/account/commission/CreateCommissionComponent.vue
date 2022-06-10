<template>

    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                    </div><!-- .nk-block-head -->
                    <div class="nk-block">
                        <div class="card card-bordered">
                            <div class="card-header">
                                <h6>Search Commission Invoice</h6>
                            </div>
                            <div class="card-inner">

                                    <div class="preview-block">
                                        <div class="row gy-4">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-customer">Company</label>
                                                    <div>
                                                        <multiselect v-model="form.company" :options="company" placeholder="Select one" label="company_name" track-by="company_name"></multiselect>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-seller"></label>
                                                    <div>
                                                        <button class="btn btn-primary" @click="searchCommissionInvoices($event)">Search</button>
                                                        <a v-bind:href="cancel_url" class="mx-2 btn btn-dim btn-secondary">Cancel</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        </div><!-- .card -->
                    </div><!-- .nk-block -->
                    <div class="nk-block">
                        <div class="card card-bordered commissioninvoice d-none">
                            <div class="card-header">
                                <h6>Commission Invoice Details</h6>
                            </div>
                            <div class="card-inner commissioninvoicedata">
                                <table id="commissioninvoice" class="table mb-2 table-hover">
                                    <thead>
                                        <tr class="text-center">
                                            <th class="text-left">Select</th>
				                            <th>Invoice No</th>
				                            <th>Financial Year</th>
				                            <th>Date</th>
                                            <th>Bill Amount</th>
                                            <th>Overdue</th>
				                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td></td>
                                            <td><input type="text" v-model="invoice_no" class="form-control" id="inv_no" @keyup="searchColumn('inv_no', 1)"></td>
                                            <td></td>
                                            <td></td>
                                            <td><input type="text" v-model="invoice_amount" class="form-control" id="inv_amount" @keyup="searchColumn('inv_amount', 4)"></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr v-for="itm in commission_invoice" :key="itm.commission_id" :class="itm.overdue > 90 ? 'text-danger' : ''" class="text-center">
                                            <td><input type="checkbox" class="d-block" v-model="selected" :id="itm.commission_id" @change="invoiceselect" :value="{'id': itm.commission_id, 'fid': itm.fid }"  required></td>
				                            <td>{{ itm.invoiceno }}</td>
				                            <td>{{ itm.financialyear }}</td>
				                            <td>{{ itm.date}}</td>
                                            <td>{{ itm.amount }}</td>
                                            <td v-if="itm.overdue > 90" class="text-danger">{{ itm.overdue }}</td><td v-else>{{ itm.overdue }}</td>
				                            <td><a :href="'/account/commission/invoice/view-invoice/'+itm.commission_id"><em class="icon ni ni-eye"></em></a></td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr class="text-center">
                                            <td class="text-left"><b>Selected Invoice : </b>{{ selectedinvoice }}</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td><b>{{ totalinvoice }}</b></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                                <button class="btn btn-primary generateCommission float-right disabled" @click="generateCommission($event)">Generate Commission</button>
                                <button class="btn btn-primary" @click="showCommissionRemark">Right Of Amount</button>
                                <div class="hidden my-2" id="commissioninvoice-remark-div">
                                        
                                        <textarea v-model="commissioninvoice_remark" class="form-control" ></textarea>
                                        <button class="btn btn-success mt-2" @click="updateInvoiceRemark">ADD</button>
                                    </div>
                            </div>

                        </div><!-- .card -->
                    </div><!-- .nk-block -->
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import $ from "jquery";
    import Multiselect from 'vue-multiselect';
    import Form from 'vform';

    export default {
        name: 'createCommission',
        components: {
            Multiselect,
        },
        props: {
            scope: String,
            id: Number,
        },
        data() {
            return {
                cancel_url: '/commission',
                company: [],
                selected: [],
                commission_invoice: [],
                invoice_no: '',
                invoice_amount: '',
                commissioninvoice_remark: '',
                selectedinvoice: '',
                totalinvoice: 0,
                form: new Form({
                    id: '',
                    company: '',
                })
            }
        },
        created() {
            axios.get('/commission/list-company')
            .then(response => {
                this.company = response.data;
            });
        },
        methods: {
            searchColumn(id, index) {
                var input, filter, table, tr, td, i, txtValue;
                input = document.getElementById(id);
                filter = input.value.toUpperCase();
                table = document.getElementById("commissioninvoice");
                tr = table.getElementsByTagName("tr");
                for (i = 0; i < tr.length; i++) {
                    if (i == 1) { continue; }
                    td = tr[i].getElementsByTagName("td")[index];
                    if (td) {
                        txtValue = td.textContent || td.innerText;
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            tr[i].style.display = "";
                        } else {
                            tr[i].style.display = "none";
                        }
                    }
                }
            },
            updateInvoiceRemark(event){
                let remark = this.commissioninvoice_remark;
                let invoices = this.selected;
                if (invoices.length > 0) {
                    axios.post('/commission/update-invoice-remarks', {
                        right_of_comment: remark,
                        invoices: invoices
                    })
                    .then(response => {
                        if (response.data.success == 1) {
                            this.commissioninvoice_remark = '';
                            $('#commissioninvoice-remark-div').slideUp();
                            this.selected = [];
                        }
                    });
                }
            },
            invoiceselect(event) {
                let totalbill = 0;
                this.selectedinvoice = this.selected.length;
                this.selected.forEach(value => {
                this.commission_invoice.forEach(value1 => {
                    if (value.id == value1.commission_id) {
                        totalbill += parseInt(value1.amount);
                        
                    }
                });
            });
            this.totalinvoice = totalbill;
            },
            showCommissionRemark() {
                $('#commissioninvoice-remark-div').slideDown();
            },
            generateCommission(event){
               if (this.selected.length == 0) {
                   alert('please select commission invoice');
                   return false;
               }
               axios.post('/commission/generate-commission', {
                    commissioninvoice: this.selected,
                    company: this.form.company.id,
                })
                .then(responce => {
                    window.location.href = '/commission/add-commission';
                })
            },
            
            searchCommissionInvoices(event) {
                const self = this;
                axios.post('/commission/searchcommissioninvoice', {
                    company: self.form.company.id,
                })
                .then(function (response) {
                    $(".commissioninvoice").removeClass("d-none");
                    $(".generateCommission").removeClass('disabled');
                    setTimeout(() => {
                        self.commission_invoice = response.data.commissioninvoice;
                    }, 1000);
                })
                .catch(function (error) {
                });
            },
        },
    };
</script>

<style scoped>

    input[type=checkbox] + label {
        display: block;
        margin: 0.2em;
        cursor: pointer;
        padding: 0.2em;
        text-transform: capitalize;
    }

    input[type=checkbox] {
        display: none;
    }

    input[type=checkbox] + label:before {
        content: "\2714";
        border: 2px solid #dbdfea;
        border-radius: 0.2em;
        display: inline-block;
        width: 25px;
        height: 25px;
        padding-left: 5px;
        padding-bottom: 5px;
        margin-right: 15px;
        vertical-align: bottom;
        color: transparent;
        transition: .2s;
    }

    input[type=checkbox] + label:active:before {
        transform: scale(0);
    }

    input[type=checkbox]:checked + label:before {
        background-color: #6576ff;
        border-color: #6576ff;
        color: #fff;
    }
</style>
