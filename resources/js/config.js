import axios from "axios"
import { computed } from "vue";
import { useRouter } from "vue-router";

export const url_image = 'http://127.0.0.1:8000/storage/'

const router = useRouter();


const token = localStorage.getItem('token');
const AdminApi = axios.create({
    baseURL: '/api/admin',
    timeout: 10000,
    headers: {
        'Content-Type': 'application/json',
        Authorization: `Bearer ${token}`
    }
})



export const ClientApi = axios.create({
    baseURL: '/api',
    timeout: 10000,
    headers: {
        'Content-Type': 'application/json'
    }
})

export default AdminApi