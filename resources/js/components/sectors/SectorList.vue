<template>
    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col-8">
                    <h3 class="mb-0">Sectors</h3>
                </div>
                <div class="col-4 text-right">
                    <!-- Button trigger to open Sector create modal -->
                    <button 
                        type="button" 
                        @click="openSectorModal" 
                        class="btn btn-sm btn-primary" 
                        data-toggle="modal"
                >
                        Add New <i class="fas fa-bezier-curve"></i>
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
                        @update="fetchAllSectors">
                        <template v-slot:input="picker" style="min-width: 100%;">
                            {{ picker.startDate | defaultFormat }} - {{ picker.endDate | defaultFormat }}
                        </template>
                    </date-range>
                </div>
                <div class="col-sm-12 col-md-4">
                    <input 
                        type="text" 
                        v-model="search.name"
                        @keyup="fetchAllSectors" 
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
                            <tr role="row" class="odd" v-for="sector in sectors.data" :key="sector.id">
                                <td>{{ sector.name }}</td>
                                <td class="text-center"><span class="badge" :class="[ sector.status === 1 ? 'badge-success' : 'badge-danger' ]">{{sector.status === 1 ? 'Active' : 'Inactive' }}</span></td>
                                <td class="text-right">
                                    <button 
                                        class="btn btn-sm btn-info" 
                                        @click="editSectorModal(sector)"
                                        :title="'Edit '+sector.name+' info'">
                                            <i class="fas fa-edit"></i>
                                    </button>
                                    <button 
                                        class="btn btn-sm btn-warning" 
                                        @click="changeStatus(sector.id)"
                                        :title="[ sector.status === 1 ? 'Inactive '+sector.name : 'Activate '+sector.name ]">
                                            <i class="fas fa-eye"></i>
                                    </button>
                                    <button 
                                        class="btn btn-sm btn-danger"
                                        @click="deleteSector(sector)"
                                        :title="'Delete '+sector.name+' info'">
                                            <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            
                        </tbody>
                        <tbody>
                            <tr>
                                <td colspan="8">
                                    <pagination class="float-right mb-0 pb-0" :data="sectors" @pagination-change-page="fetchAllSectors"></pagination>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <sector-modal 
                :formData="formData" 
                :errors="errors" 
                :editMode="editMode"
                @getErrors="getErrorsFromChild"
                @fetchAllSectors="fetchAllSectors"
            ></sector-modal>
    </div>
</template>


<script>
import SectorModal from './SectorModal';
export default {
    name: 'sector-list',
    data() {
        return {
            auth: {},
            sectors: {},
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
        openSectorModal () {
            this.editMode = false;
            this.formData = {};
            $('#sectorModal').modal('show');
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
        async editSectorModal (sector) {
            this.editMode = true;
            this.formData = sector;
            $('#sectorModal').modal('show');
        },
        async fetchAllSectors (page = 1) {
            if (typeof page === 'object') {
                page = 1;
            }
            let params = 'page='+page+'&name='+this.search.name;
            let startDate = moment(this.search.dateRange.startDate).format('YYYY-MM-DD');
            params += '&start_date='+startDate;

            let endDate = moment(this.search.dateRange.endDate).format('YYYY-MM-DD');
            params += '&end_date='+endDate;

            const response = await axios.get('/sectors/all?'+params);
            if (response.status == 200) {
                this.sectors = response.data
            }
        },
        async changeStatus (id) {
            const response = await axios.put('/sectors/change/status/'+id)
            if (response.status == 200 && response.data.success) {
                this.fetchAllSectors();
                const sector = response.data.sector;
                const message = sector.status === 1 ? 'Active' : 'Inactive';
                this.$toast.fire({
                    type: 'success',
                    title: sector.name + ' is now ' + message + '!'
                });
            }
        },
        async deleteSector (sector) {
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
                const sectorName = sector.name;
                const response = await axios.delete('/sectors/'+sector.id)
                if (response.status == 200 && response.data.success) {
                    this.fetchAllSectors();
                    this.$toast.fire({
                        type: 'success',
                        title: sectorName + ' removed permanently!'
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
        this.fetchAllSectors();
        this.authInfo();
    },
    components: {
        'sector-modal': SectorModal
    }
}
</script>