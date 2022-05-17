import Swal from 'sweetalert2'
window.Swal = Swal;
const Toast = Swal.mixin( {
    title: '',
    icon: '',
    toast: true,
    position: 'top',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer);
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
});
window.Toast = Toast;

//Call example
//Toast.fire('Success', 'Product added successfully', 'success')

//Position
//Popup window position, can be 'top', 'top-start', 'top-end', 'center', 'center-start', 'center-end', 'bottom', 'bottom-start', or 'bottom-end'.
