<template>
    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col-8">
                    <h3 class="mb-0">Payment Modes</h3>
                </div>
                <div class="col-4 text-right">
                    <!-- Button trigger to open paymentmode create modal -->
                    <button 
                        type="button" 
                        @click="openPaymentModeModal" 
                        class="btn btn-sm btn-primary" 
                        data-toggle="modal">
                        Add New <i class="fas fa-money-check-alt"></i>
                    </button>
                </div>                   
            </div>
        </div>
        
        <div class="card-body">
            
            <div class="row" v-if="isEmpty">
                <div class="col-md-6 offset-md-3 col-sm-12 text-center position-relative">
                    <img style="width: 100%" src="/img/undraw/undraw_no_data.svg" alt="">
                    <h1 class="centered-text">No Data Found!</h1>
                </div>
            </div>
            <div class="table-responsive" v-else>
                <table class="table table-striped">
                    <thead class="thead-light">
                        <tr role="row">
                            <th scope="col">Name</th>
                            <th class="text-center" scope="col">Status</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr role="row" class="odd" v-for="paymentmode in paymentmodes.data" :key="paymentmode.id">
                            <td>{{ paymentmode.name }}</td>
                            <td class="text-center"><span class="badge" :class="[ paymentmode.status === 1 ? 'badge-success' : 'badge-danger' ]">{{paymentmode.status === 1 ? 'Active' : 'Inactive' }}</span></td>
                            <td class="text-right">
                                <button 
                                    class="btn btn-sm btn-info" 
                                    @click="editPaymentModeModal(paymentmode)"
                                    :title="'Edit '+paymentmode.name+' info'">
                                        <i class="fas fa-edit"></i>
                                </button>
                                <button 
                                    class="btn btn-sm btn-warning" 
                                    @click="changeStatus(paymentmode.id)"
                                    :title="[ paymentmode.status === 1 ? 'Inactive '+paymentmode.name : 'Activate '+paymentmode.name ]">
                                        <i class="fas fa-eye"></i>
                                </button>
                                <button 
                                    class="btn btn-sm btn-danger"
                                    @click="deletePaymentMode(paymentmode)"
                                    :title="'Delete '+paymentmode.name+' info'">
                                        <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        
                    </tbody>
                    <tbody>
                        <tr>
                            <td colspan="8">
                                <pagination 
                                    class="float-right mb-0 pb-0" 
                                    :data="paymentmodes" 
                                    @pagination-change-page="fetchAllPaymentModes">
                                </pagination>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <payment-mode-modal 
                :formData="formData" 
                :errors="errors" 
                :editMode="editMode"
                @getErrors="getErrorsFromChild"
                @fetchAllPaymentModes="fetchAllPaymentModes"
            ></payment-mode-modal>
        </div>
    </div>
</template>


<script>
import PaymentModeModal from './PaymentModeModal';
export default {
    name: 'payment-mode-list',
    data() {
        return {
            auth: {},
            paymentmodes: {},
            formData: {
                name: '',
                price: '',
            },
            errors: {},
            editMode: false,
            search: {
                name: '',
            },
            isEmpty: true,
        }
    },
    methods: {
        openPaymentModeModal () {
            this.editMode = false;
            this.formData = {};
            $('#paymentModeModal').modal('show');
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
        async editPaymentModeModal (paymentmode) {
            this.editMode = true;
            this.formData = paymentmode;
            $('#paymentModeModal').modal('show');
        },
        async fetchAllPaymentModes (page = 1) {
            if (typeof page === 'object') {
                page = 1;
            }
            let params = 'page='+page+'&name='+this.search.name;
            const response = await axios.get('/paymentmodes/all?'+params);
            if (response.status == 200) {
                this.paymentmodes = response.data
                this.isEmpty = this.paymentmodes.data.length > 0 ? false : true;
            }
        },
        async changeStatus (id) {
            const response = await axios.put('/paymentmodes/change/status/'+id)
            if (response.status == 200 && response.data.success) {
                this.fetchAllPaymentModes();
                const paymentmode = response.data.paymentmode;
                const message = paymentmode.status === 1 ? 'Active' : 'Inactive';
                this.$toast.fire({
                    type: 'success',
                    title: paymentmode.name + ' is now ' + message + '!'
                });
            }
        },
        async deletePaymentMode (paymentmode) {
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
                const paymentModeName = paymentmode.name;
                const response = await axios.delete('/paymentmodes/'+paymentmode.id)
                if (response.status == 200 && response.data.success) {
                    this.fetchAllPaymentModes();
                    this.$toast.fire({
                        type: 'success',
                        title: paymentModeName + ' removed permanently!'
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
        this.fetchAllPaymentModes();
        this.authInfo();
    },
    components: {
        'payment-mode-modal': PaymentModeModal
    }
}
</script>