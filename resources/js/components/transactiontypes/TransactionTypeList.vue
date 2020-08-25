<template>
    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col-8">
                    <h3 class="mb-0">Transaction Types</h3>
                </div>
                <div class="col-4 text-right">
                    <!-- Button trigger to open Service create modal -->
                    <button 
                        type="button" 
                        @click="openTransactionTypeModal" 
                        class="btn btn-sm btn-primary" 
                        data-toggle="modal"
                >
                        Add New <i class="fas fa-tag"></i>
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
                        @update="fetchAllTransactionTypes">
                        <template v-slot:input="picker" style="min-width: 100%;">
                            {{ picker.startDate | defaultFormat }} - {{ picker.endDate | defaultFormat }}
                        </template>
                    </date-range>
                </div>
                <div class="col-sm-12 col-md-4">
                    <input 
                        type="text" 
                        v-model="search.name"
                        @keyup="fetchAllTransactionTypes" 
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
                            <tr role="row" class="odd" v-for="transactionType in transactionTypes.data" :key="transactionType.id">
                                <td>{{ transactionType.name }}</td>
                                <td class="text-center"><span class="badge" :class="[ transactionType.status === 1 ? 'badge-success' : 'badge-danger' ]">{{transactionType.status === 1 ? 'Active' : 'Inactive' }}</span></td>
                                <td class="text-right">
                                    <button 
                                        class="btn btn-sm btn-info" 
                                        @click="editTransactionTypeModal(transactionType)"
                                        :title="'Edit '+transactionType.name+' info'">
                                            <i class="fas fa-edit"></i>
                                    </button>
                                    <button 
                                        class="btn btn-sm btn-warning" 
                                        @click="changeStatus(transactionType.id)"
                                        :title="[ transactionType.status === 1 ? 'Inactive '+transactionType.name : 'Activate '+transactionType.name ]">
                                            <i class="fas fa-eye"></i>
                                    </button>
                                    <button 
                                        class="btn btn-sm btn-danger"
                                        @click="deleteTransactionType(transactionType)"
                                        :title="'Delete '+transactionType.name+' info'">
                                            <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            
                        </tbody>
                        <tbody>
                            <tr>
                                <td colspan="8">
                                    <pagination class="float-right mb-0 pb-0" :data="transactionTypes" @pagination-change-page="fetchAllTransactionTypes"></pagination>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <transaction-type-modal 
                :formData="formData" 
                :errors="errors" 
                :editMode="editMode"
                @getErrors="getErrorsFromChild"
                @fetchAllTransactionTypes="fetchAllTransactionTypes"
            ></transaction-type-modal>
    </div>
</template>


<script>
import TransactionTypeModal from './TransactionTypeModal';
export default {
    name: 'transactionType-list',
    data() {
        return {
            auth: {},
            transactionTypes: {},
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
        openTransactionTypeModal () {
            this.editMode = false;
            this.formData = {};
            $('#transactionTypeModal').modal('show');
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
        async editTransactionTypeModal (transactionType) {
            this.editMode = true;
            this.formData = transactionType;
            $('#transactionTypeModal').modal('show');
        },
        async fetchAllTransactionTypes (page = 1) {
            if (typeof page === 'object') {
                page = 1;
            }
            let params = 'page='+page+'&name='+this.search.name;
            let startDate = moment(this.search.dateRange.startDate).format('YYYY-MM-DD');
            params += '&start_date='+startDate;

            let endDate = moment(this.search.dateRange.endDate).format('YYYY-MM-DD');
            params += '&end_date='+endDate;

            const response = await axios.get('/transactiontypes/all?'+params);
            if (response.status == 200) {
                this.transactionTypes = response.data
            }
        },
        async changeStatus (id) {
            const response = await axios.put('/transactiontypes/change/status/'+id)
            if (response.status == 200 && response.data.success) {
                this.fetchAllTransactionTypes();
                const transactionType = response.data.transactionType;
                const message = transactionType.status === 1 ? 'Active' : 'Inactive';
                this.$toast.fire({
                    type: 'success',
                    title: transactionType.name + ' is now ' + message + '!'
                });
            }
        },
        async deleteTransactionType (transactionType) {
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
                const transactionTypeName = transactionType.name;
                const response = await axios.delete('/transactiontypes/'+transactionType.id)
                if (response.status == 200 && response.data.success) {
                    this.fetchAllTransactionTypes();
                    this.$toast.fire({
                        type: 'success',
                        title: transactionTypeName + ' removed permanently!'
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
        this.fetchAllTransactionTypes();
        this.authInfo();
    },
    components: {
        'transaction-type-modal': TransactionTypeModal
    }
}
</script>