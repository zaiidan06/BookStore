import axios from 'axios';
window.axios = axios;

axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
