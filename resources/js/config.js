import axios from "axios"

export const url_image = 'http://127.0.0.1:8000/storage/'

const AdminApi = axios.create({
    baseURL: '/api/admin',
    timeout:10000,
    headers:{
        'Content-Type': 'application/json'
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