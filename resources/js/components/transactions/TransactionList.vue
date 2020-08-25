<template>
    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col-8">
                    <h3 class="mb-0">Transactions</h3>
                </div>
                <div class="col-4 text-right">
                    <!-- Button trigger to open Service create modal -->
                    <button 
                        type="button" 
                        @click="openTransactionModal" 
                        class="btn btn-sm btn-primary" 
                        data-toggle="modal"
                >
                        Add New <i class="fas fa-money-check-alt"></i>
                    </button>
                </div>                   
            </div>
        </div>
        
        <div class="card-body">
            <div class="row">
                <div class="col-sm-12 col-md-8">
                    <date-range
                        ref="picker"
                        opens="center"
                        :locale-data="{ firstDay: 1, format: 'DD-MM-YYYY' }"
                        :timePicker="false"
                        v-model="search.dateRange"
                        @update="fetchAllTransactions">
                        <template v-slot:input="picker" style="min-width: 100%;">
                            {{ picker.startDate | defaultFormat }} - {{ picker.endDate | defaultFormat }}
                        </template>
                    </date-range>
                </div>
                <div class="col-sm-12 col-md-4">
                    <input 
                        type="text" 
                        v-model="search.name"
                        @keyup="fetchAllTransactions" 
                        class="form-control" 
                        placeholder="Search" 
                        aria-controls="datatable-basic">
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-md-6">
                </div>
                <div class="col-sm-12 col-md-6">
                    <div class="float-right">
                        <label>Search:
                            <input type="search" class="form-control form-control-sm" placeholder="" aria-controls="datatable-basic"></label>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead class="thead-light">
                            <tr role="row">
                                <th scope="col">Name</th>
                                <th class="text-center" scope="col">Status</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr role="row" class="odd" v-for="transaction in transactions.data" :key="transaction.id">
                                <td>{{ transaction.name }}</td>
                                <td class="text-center"><span class="badge" :class="[ transaction.status === 1 ? 'badge-success' : 'badge-danger' ]">{{transaction.status === 1 ? 'Active' : 'Inactive' }}</span></td>
                                <td class="text-right">
                                    <button 
                                        class="btn btn-sm btn-info" 
                                        @click="editTransactionModal(transaction)"
                                        :title="'Edit '+transaction.name+' info'">
                                            <i class="fas fa-edit"></i>
                                    </button>
                                    <button 
                                        class="btn btn-sm btn-warning" 
                                        @click="changeStatus(transaction.id)"
                                        :title="[ transaction.status === 1 ? 'Inactive '+transaction.name : 'Activate '+transaction.name ]">
                                            <i class="fas fa-eye"></i>
                                    </button>
                                    <button 
                                        class="btn btn-sm btn-danger"
                                        @click="deleteTransaction(transaction)"
                                        :title="'Delete '+transaction.name+' info'">
                                            <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            
                        </tbody>
                        <tbody>
                            <tr>
                                <td colspan="8">
                                    <pagination class="float-right mb-0 pb-0" :data="transactions" @pagination-change-page="fetchAllTransactions"></pagination>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <transaction-modal 
                :formData="formData" 
                :errors="errors" 
                :editMode="editMode"
                @getErrors="getErrorsFromChild"
                @fetchAllTransactions="fetchAllTransactions"
            ></transaction-modal>
    </div>
</template>


<script>
import TransactionModal from './TransactionModal';
export default {
    name: 'transaction-list',
    data() {
        return {
            auth: {},
            transactions: {},
            formData: {
                name: '',
                price: '',
            },
            errors: {},
            editMode: false,
            search: {
                name: '',
                dateRange: {
                    startDate: new Date(),
                    endDate: new Date()
                }
            }
        }
    },
    methods: {
        openTransactionModal () {
            this.editMode = false;
            this.formData = {};
            $('#TransactionModal').modal('show');
        },
        getErrorsFromChild (errors) {
            if (errors != null) {
                let customizeError =errors;
                Object.keys(errors).forEach(key => {
                    customizeError[key] = errors[key][0];
                });
                this.errors = customizeError;
            }else {
                this.errors = {};
            }
        },
        async editTransactionModal (transaction) {
            this.editMode = true;
            this.formData = transaction;
            $('#TransactionModal').modal('show');
        },
        async fetchAllTransactions (page = 1) {
            if (typeof page === 'object') {
                page = 1;
            }
            let params = 'page='+page+'&name='+this.search.name;
            let startDate = moment(this.search.dateRange.startDate).format('YYYY-MM-DD');
            params += '&start_date='+startDate;

            let endDate = moment(this.search.dateRange.endDate).format('YYYY-MM-DD');
            params += '&end_date='+endDate;

            const response = await axios.get('/transactions/all?'+params);
            if (response.status == 200) {
                this.transactions = response.data
            }
        },
        async changeStatus (id) {
            const response = await axios.put('/transactions/change/status/'+id)
            if (response.status == 200 && response.data.success) {
                this.fetchAllTransactions();
                const transaction = response.data.transaction;
                const message = transaction.status === 1 ? 'Active' : 'Inactive';
                this.$toast.fire({
                    type: 'success',
                    title: transaction.name + ' is now ' + message + '!'
                });
            }
        },
        async deleteTransaction (transaction) {
            const result = await this.$swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            });
            if (result.value) {
                const transactionName = transaction.name;
                const response = await axios.delete('/transactions/'+transaction.id)
                if (response.status == 200 && response.data.success) {
                    this.fetchAllTransactions();
                    this.$toast.fire({
                        type: 'success',
                        title: transactionName + ' removed permanently!'
                    });
                }
            }
        },
        async authInfo () {
            const response =  await axios.get('/auth');
            if (response.status == 200) {
                this.auth = response.data;
            }
        }
    },
    computed: {
        
    },
    mounted() {
        this.fetchAllTransactions();
        this.authInfo();
    },
    components: {
        'transaction-modal': TransactionModal
    }
}
</script>