<template>
    <div>
        <h2>Danh sách đơn hàng</h2>
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
                    <button class="btn btn-sm btn-secondary">Chi tiết</button></td>
                </tr>

                <tr v-else>
                    <td colspan="3">Không có đơn hàng nào</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import AdminApi from '../../../config';
const orders = ref([]);
const getData = async () => {
    try {
        const { data } = await AdminApi.get('orders');
        orders.value = data.orders.data
        
    } catch (error) {
        alert(error.response.data.message)
    }
    
}
onMounted(() => {
    getData();
})
</script>

<style lang="scss" scoped>

</style>