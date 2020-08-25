<template>
    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col-8">
                    <h3 class="mb-0">Services</h3>
                </div>
                <div class="col-4 text-right">
                    <!-- Button trigger to open Service create modal -->
                    <button 
                        type="button" 
                        @click="openServiceModal" 
                        class="btn btn-sm btn-primary" 
                        data-toggle="modal">
                        Add New <i class="fab fa-servicestack"></i>
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
                        @update="fetchAllServices">
                        <template v-slot:input="picker" style="min-width: 100%;">
                            {{ picker.startDate | defaultFormat }} - {{ picker.endDate | defaultFormat }}
                        </template>
                    </date-range>
                </div>
                <div class="col-sm-12 col-md-4">
                    <input 
                        type="text" 
                        v-model="search.name"
                        @keyup="fetchAllServices" 
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
                                <th scope="col">Price</th>
                                <th scope="col">Paid</th>
                                <th scope="col">Due</th>
                                <th scope="col">Sector</th>
                                <th scope="col">Payment Mode</th>
                                <th class="text-center" scope="col">Status</th>
                                <th scope="col" class="float-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr role="row" class="odd" v-for="service in services.data" :key="service.id">
                                <td>{{ service.title }}</td>
                                <td>{{ service.amount }}</td>
                                <td>{{ service.paid }}</td>
                                <td>
                                    <span v-if="service.due == 0.00" class="badge badge-success">Paid</span>
                                    <!-- Button trigger to open Service create modal -->
                                    <button 
                                        v-else
                                        type="button" 
                                        @click="openServiceModal" 
                                        class="btn btn-sm btn-warning" 
                                        title="Pay now"
                                        data-toggle="modal">
                                        {{ service.due }}
                                    </button>
                                </td>
                                <td>{{ service.sector.name }}</td>
                                <td>{{ service.payment_mode.name }}</td>
                                <td class="text-center"><span class="badge" :class="[ service.status === 1 ? 'badge-success' : 'badge-danger' ]">{{service.status === 1 ? 'Active' : 'Inactive' }}</span></td>
                                <td class="text-right">
                                    <button 
                                        class="btn btn-primary btn-sm"
                                        @click="showAllTransactions(service)"
                                        :title="'Slow all transactions of ' + service.name">
                                            <i class="fas fa-exchange-alt"></i>
                                    </button>
                                    <button 
                                        class="btn btn-sm btn-info" 
                                        @click="editServiceModal(service)"
                                        :title="'Edit '+service.title+' info'">
                                            <i class="fas fa-edit"></i>
                                    </button>
                                    <button 
                                        class="btn btn-sm btn-warning" 
                                        @click="changeStatus(service.id)"
                                        :title="[ service.status === 1 ? 'Inactive '+service.title : 'Activate '+service.title ]">
                                            <i class="fas fa-eye"></i>
                                    </button>
                                    <button 
                                        class="btn btn-sm btn-danger"
                                        @click="deleteService(service)"
                                        :title="'Delete '+service.title+' info'">
                                            <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            
                        </tbody>
                        <tbody>
                            <tr>
                                <td colspan="8">
                                    <pagination class="float-right mb-0 pb-0" :data="services" @pagination-change-page="fetchAllServices"></pagination>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <service-modal 
                :formData="formData" 
                :errors="errors" 
                :sectors="sectors" 
                :payment_modes="payment_modes" 
                :editMode="editMode"
                @getErrors="getErrorsFromChild"
                @fetchAllServices="fetchAllServices"
            ></service-modal>
            <transaction-modal 
                :service="service"
                :hasTransaction="hasTransaction">
            </transaction-modal>
    </div>
</template>


<script>
import ServiceModal from './ServiceModal';
import TransactionModal from './TransactionModal';
export default {
    name: 'service-list',
    data() {
        return {
            auth: {},
            services: {},
            sectors: [],
            payment_modes: [],
            formData: {
                title: '',
                amount: '',
                serve_at: moment().format('DD.MM.YYYY')
            },
            errors: {},
            editMode: false,
            hasTransaction: false,
            search: {
                name: '',
                dateRange: {
                    startDate: new Date(),
                    endDate: new Date()
                }
            },
            service: {}
        }
    },
    methods: {
        openServiceModal () {
            this.editMode = false;
            this.formData = {};
            this.formData.serve_at = moment().format('DD.MM.YYYY');
            $('#serviceModal').modal('show');
        },
        showAllTransactions (service) {
            this.service = service;
            this.hasTransaction = service.transactions.length > 0 ? true : false;
            $('#transactionModal').modal('show');
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
        async editServiceModal (service) {
            this.editMode = true;
            this.formData = service;
            let sector = await this.sectors.find(sector => sector.id === service.sector_id);
            let payment_mode = await this.payment_modes.find(payment_mode => payment_mode.id === service.payment_mode_id);
            this.formData.sector = sector;
            this.formData.payment_mode = payment_mode;
            this.formData.serve_at = moment(service.serve_at).format('DD.MM.YYYY');
            $('#serviceModal').modal('show');
        },
        async changeStatus (id) {
            const response = await axios.put('/services/change/status/'+id)
            if (response.status == 200 && response.data.success) {
                this.fetchAllServices();
                const service = response.data.service;
                const message = service.status === 1 ? 'Active' : 'Inactive';
                this.$toast.fire({
                    type: 'success',
                    title: service.title + ' is now ' + message + '!'
                });
            }
        },
        async deleteService (service) {
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
                const serviceName = service.title;
                const response = await axios.delete('/services/'+service.id)
                if (response.status == 200 && response.data.success) {
                    this.fetchAllServices();
                    this.$toast.fire({
                        type: 'success',
                        title: serviceName + ' removed permanently!'
                    });
                }
            }
        },
        async authInfo () {
            const response =  await axios.get('/auth');
            if (response.status == 200) {
                this.auth = response.data;
            }
        },
        async fetchAllServices (page = 1) {
            if (typeof page === 'object') {
                page = 1;
            }
            let params = 'page='+page+'&name='+this.search.name;
            let startDate = moment(this.search.dateRange.startDate).format('YYYY-MM-DD');
            params += '&start_date='+startDate;

            let endDate = moment(this.search.dateRange.endDate).format('YYYY-MM-DD');
            params += '&end_date='+endDate;

            const response = await axios.get('/services/all?'+params);
            if (response.status == 200) {
                this.services = response.data
            }
        },
        async fetchAllActiveSectors () {
            const response = await axios.get('/sectors/all/only-active');
            if (response.status == 200) {
                this.sectors = response.data;
            }
        },
        async fetchAllActivePaymentModes () {
            const response = await axios.get('/paymentmodes/all/only-active');
            if (response.status == 200) {
                this.payment_modes = response.data;
            }
        },
    },
    computed: {
        
    },
    mounted() {
        this.fetchAllServices();
        this.authInfo();
        this.fetchAllActiveSectors();
        this.fetchAllActivePaymentModes();
    },
    components: {
        'service-modal': ServiceModal,
        'transaction-modal': TransactionModal,
    }
}
</script>