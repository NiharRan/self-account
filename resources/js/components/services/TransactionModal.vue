<template>
    <div>
        <!-- Modal -->
        <div class="modal fade" id="transactionModal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">All transactions related to <strong class="text-success">{{ service.title }} </strong> service.</h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="table-responsive" v-if="hasTransaction">
                        <table class="table table-striped">
                            <thead class="thead-light">
                                <tr role="row">
                                    <th scope="col">Amount</th>
                                    <th scope="col">Transaction Date</th>
                                    <th class="text-center" scope="col">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr role="row" class="odd" v-for="transaction in service.transactions" :key="transaction.id">
                                    <td>{{ transaction.amount }}</td>
                                    <td>{{ transaction.created_at | defaultFormat }}</td>
                                    <td class="text-center"><span class="badge" :class="[ transaction.status === 1 ? 'badge-success' : 'badge-danger' ]">{{transaction.status === 1 ? 'Active' : 'Inactive' }}</span></td>
                                </tr>
                                
                            </tbody>
                        </table>
                    </div>
                    <div class="row" v-else>
                        <div class="col-md-6 offset-md-3 col-sm-12 text-center position-relative">
                            <img style="width: 100%" src="/img/undraw/undraw_no_data.svg" alt="">
                            <h1 class="centered-text">No Transaction Found!</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
export default {
    props: ['service', 'hasTransaction'],
    data() {
        return {
            
        }
    },
}
</script>