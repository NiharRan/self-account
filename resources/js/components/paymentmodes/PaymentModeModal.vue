<template>
    <div>
        <!-- Modal -->
        <div class="modal fade" id="paymentModeModal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">{{ editMode ? `Update ${formData.name}'s Info` : 'Create New Payment Mode' }}</h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="storeOrUpdate" autocomplete="off">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 col-sm-12">
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                <label class="form-control-label">Name</label>
                                                <input type="text" v-model="formData.name" class="form-control form-control-alternative" placeholder="Payment Mode Name">
                                                <!-- if name field is empty and try to submit show error message -->
                                                <span class="text-danger">{{ errors.name }}</span>
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
    name: 'payment-mode-modal',
    props: ['formData', 'errors', 'editMode'],
    data() {
        return {
            
        }
    },
    methods: {
        storeOrUpdate () {
            this.$emit('getErrors', null);
            if (this.editMode) {
                this.update();
            }else {
                this.store();
            }
        },
        async store () {
            const data = {};
            data.name = this.formData.name;
            try {
                const response = await axios.post('/paymentmodes', data);
                if (response.data) {
                    this.showMessage();
                }
            } catch (error) {
                this.$emit('getErrors', error.response.data.errors);
            }
        },
        async update () {
            const data = {};
            data.name = this.formData.name;

            try {
                const response = await axios.put('/paymentmodes/'+this.formData.id, data);
                if (response.data.success) {
                    this.showMessage();
                }
            } catch (error) {
                this.$emit('getErrors', error.response.data.errors);
            }
        },
        showMessage () {
            const message = this.editMode ? 'updated' : 'created';
            this.$toast.fire({
                type: 'success',
                title: 'Payment Mode ' + message + ' successfully!'
            })
            $('#paymentModeModal').modal('hide');
            this.$emit('fetchAllPaymentModes');
        }
    },
    mounted() {
        
    },
}
</script>