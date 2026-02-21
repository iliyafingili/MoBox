// 1️⃣ bootstrap + axios
import './bootstrap';
import axios from 'axios';

window.axios = axios;

axios.interceptors.response.use(
    response => response,
    error => {
        if (error.response && (error.response.status === 401 || error.response.status === 419)) {
            const currentUrl = encodeURIComponent(window.location.href);
            window.location.href = `/auth/login?intended=${currentUrl}`;
        }
        return Promise.reject(error);
    }
);



// 2️⃣ Alpine
import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();


// 4 seewtalert
import Swal from 'sweetalert2';
window.Swal = Swal;

// 3️⃣ بقیه ماژول‌ها
import './admin-dashboard';
import './show-&-edit-products';
import './produce-show';
import './shopping-cart.js';

// 4️⃣ CSRF
window.axios.defaults.headers.common['X-CSRF-TOKEN'] =
    document.querySelector('meta[name="csrf-token"]').content;

// =========================================================







