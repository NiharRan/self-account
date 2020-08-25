/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import moment from 'moment'
window.moment = moment;

import {swalPlugin, toastPlugin} from './utilities/sweet-alert';
Vue.use(swalPlugin);
Vue.use(toastPlugin);

import Multiselect from 'vue-multiselect';
Vue.component('multiselect', Multiselect);

import DateRangePicker from 'vue2-daterange-picker';
import 'vue2-daterange-picker/dist/vue2-daterange-picker.css';
Vue.component('date-range', DateRangePicker);

import DatePick from 'vue-date-pick';
import 'vue-date-pick/dist/vueDatePick.css';
Vue.component('date-pick', DatePick);

Vue.component('pagination', require('laravel-vue-pagination'));

import SectorList from './components/sectors/SectorList';
import PaymentModeList from './components/paymentmodes/PaymentModeList';
import ServiceList from './components/services/ServiceList';
import TransactionTypeList from './components/transactiontypes/TransactionTypeList';
import TransactionList from './components/transactions/TransactionList';

Vue.filter('defaultFormat', function (date) {
    return moment(date).format('DD-MM-YYYY');
})

const app = new Vue({
    el: '#app',
    components : {
        SectorList,
        PaymentModeList,
        ServiceList,
        TransactionTypeList,
        TransactionList,
    }
});
