<template>
    <div class="my-2">
        <h2>Thông tin tài khoản</h2>

        <h3>{{ info_account.email }}</h3>
        <h3> Đơn hàng của tôi </h3>

        <table class="table table-striped mt-2">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Họ và tên</th>
                    <th>Số điện thoại</th>
                    <th>Địa chỉ</th>
                    <th>Trạng thái</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-if="orders.length > 0" v-for="order in orders">
                    <td>{{ order.id }}</td>
                    <td>{{ order.full_name }}</td>
                    <td>{{ order.phone_number }}</td>
                    <td>{{ order.address }}</td>
                    <td>{{ order.status }}</td>
                    <td>
                    <router-link :to="{name: 'client-order-detail', params:{ id: order.id}}" class="btn btn-sm btn-secondary">Chi tiết</router-link></td>
                </tr>
                <tr v-else>
                    <td colspan="3">Không có đơn hàng nào</td>
                </tr>
            </tbody>
        </table>

        <button  @click="handleLogout"  class="btn btn-danger">Đăng xuất</button>

    </div>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import { ClientApi } from '../../config';
import { useRouter } from 'vue-router';
import { logout } from '../Auth/Logout';
const router = useRouter();
const info_account = ref({});
const token = localStorage.getItem('token') || '';
const orders = ref([]);


const getData = async () => {
    try {
        const { data } = await ClientApi.get('/user', {
            headers: {
                Authorization: `Bearer ${token}`
            }
        })  
        info_account.value  = data;
    } catch (error) {
        alert(error.response.data.message);
    }

    
}
 
const getOrderHistory = async () => {
    try {
        const token = localStorage.getItem('token');
        const {data} = await ClientApi.get('/order_history', {
                headers: {
                    Authorization: `Bearer ${token}`
                }
            })
        orders.value = data.orders        
    } catch (error) {
        alert(error.response.data.message);
        
    }
    
}

const handleLogout = () => {
    const hasToken = localStorage.getItem('token') ? true : false;    
    if(hasToken){
        logout();
    }
}
onMounted(() => {
    if(token){
        getData();
        getOrderHistory();
    }
    else{
        router.push('/')
    }
})


</script>

<style lang="scss" scoped>

</style>