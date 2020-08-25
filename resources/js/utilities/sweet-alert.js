import swal from 'sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

const toast = swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    onOpen: (toast) => {
      toast.addEventListener('mouseenter', swal.stopTimer)
      toast.addEventListener('mouseleave', swal.resumeTimer)
    }
  })

const swalPlugin = {}
const toastPlugin = {}

swalPlugin.install = function(Vue){
    Vue.prototype.$swal = swal
}

toastPlugin.install = function(Vue){
    Vue.prototype.$toast = toast
}

export {swalPlugin, toastPlugin};