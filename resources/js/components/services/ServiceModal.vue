<template>
    <div>
        <!-- Modal -->
        <div class="modal fade" id="serviceModal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">{{ editMode ? `Update ${formData.name}'s Info` : 'Store Service Info' }}</h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="storeOrUpdate" autocomplete="off">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 col-sm-12">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <label class="form-control-label">Title</label>
                                                <input type="text" v-model="formData.title" class="form-control form-control-alternative" placeholder="Service Title">
                                                <!-- if title field is empty and try to submit show error message -->
                                                <span class="text-danger">{{ errors.title }}</span>
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                <label class="form-control-label">Sector</label>
                                                <multiselect 
                                                    v-model="formData.sector" 
                                                    :options="sectors"
                                                    placeholder="Pick a value"
                                                    label="name"
                                                    track-by="name">
                                                </multiselect>
                                                <!-- if sector field is empty and try to submit show error message -->
                                                <span class="text-danger">{{ errors.sector_id }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12 text-center">
                                    <button type="submit" class="btn btn-success btn-sm">{{ editMode ? 'Update' : 'Create' }}</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
export default {
    props: ['formData', 'sectors', 'payment_modes', 'errors', 'editMode'],
    data() {
        return {
            showPaidInput: false
        }
    },
    methods: {
        checkSelectedOption (payment_mode) {
            this.showPaidInput = payment_mode.name === 'Some Paid' || payment_mode.name === 'Some Due' ? true : false;
        },
        storeOrUpdate () {
            this.$emit('getErrors', null);
            if (this.editMode) {
                this.update();
            }else {
                this.store();
            }
        },
        async store () {
            let paid = 0;
            if (this.formData.payment_mode.name === 'Paid') {
                paid = this.formData.amount;
            }
            else if (this.showPaidInput) {
                paid = this.formData.paid;
            }
            let sector_id = typeof this.formData.sector == 'object' ? this.formData.sector.id : this.formData.sector;
            let payment_mode_id = typeof this.formData.payment_mode == 'object' ? this.formData.payment_mode.id : this.formData.payment_mode;
            const data = {};
            data.title = this.formData.title;
            data.amount = this.formData.amount;
            data.serve_at = this.formData.serve_at;
            data.paid = paid;
            data.sector_id = sector_id;
            data.payment_mode_id = payment_mode_id;
            try {
                const response = await axios.post('/services', data);
                if (response.data) {
                    this.showMessage();
                }
            } catch (error) {
                this.$emit('getErrors', error.response.data.errors);
            }
        },
        async update () {
            let sector_id = typeof this.formData.sector == 'object' ? this.formData.sector.id : this.formData.sector;
            let payment_mode_id = typeof this.formData.payment_mode == 'object' ? this.formData.payment_mode.id : this.formData.payment_mode;
            const data = {};
            data.title = this.formData.title;
            data.sector_id = sector_id;
            try {
                const response = await axios.put('/services/'+this.formData.id, data);
                if (response.data) {
                    this.showMessage();
                }
            } catch (error) {
                this.$emit('getErrors', error.response.data.errors);
            }
        },
        showMessage () {
            const message = this.editMode ? 'updated' : 'stored';
            this.$toast.fire({
                type: 'success',
                title: 'Service info' + message + ' successfully!'
            })
            $('#serviceModal').modal('hide');
            this.$emit('fetchAllServices');
        }
    },
    mounted() {
        
    },
}
</script>