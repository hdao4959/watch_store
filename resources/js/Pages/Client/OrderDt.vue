<template>
    <div>
        


       <div class="card description mx-5 my-2">
        <div class="card-header text-center bg-primary">
            <span class="text-center">Thông tin đơn hàng</span>
        </div>
        <div class="card-body ">
        <span>Họ và tên: {{ order.full_name }}</span><br>
        <span>Số đt: {{ order.phone_number }}</span><br>
        <span>Địa chỉ: {{ order.address }}</span><br>
        <span>Hình thức thanh toán: {{ order.type_pay == 0 ? "Thanh toán khi nhận hàng" : "Thanh toán online" }}</span><br>
        <span>Trạng thái đơn hàng: <span class="fw-bold">{{ order.status }}</span> </span><br>
        <span>Thời gian đặt hàng: {{ order.created_at }}</span><br> 
        <span>Tổng tiền: {{ formatPrice(order.amount) }}</span><br> 
        
        </div>
        </div>

        <div class="card mx-5">
            <div class="card-header text-center bg-primary">
                <span>Sản phẩm trong đơn hàng</span>
            </div>
            <div class="card-body">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Hình ảnh</th>
                            <th>Tên sản phẩm</th>
                            <th>Kích cỡ</th>
                            <th>Màu sắc</th>
                            <th>Số lượng</th>
                            <th>Tổng giá</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in order.order_items" :key="item.id">
                            <td>
                                <img width="50" class="img-thumbnail" :src="`${url_image}${item.img_thumbnail}`"
                                    alt="Hình ảnh sản phẩm" />
                            </td>
                            <td>{{ item.product_name }}</td>
                            <td>{{ item.size }}mm</td>
                            <td>{{ item.color }}</td>
                            <td>{{ item.quantity }}</td>
                            <td>{{ formatPrice(item.total_price) }}</td>
                        </tr>
                    </tbody>
                </table>
                <span class="text-danger fw-bold"> Tổng giá: {{ formatPrice(order.amount) }}</span>
            </div>
        </div>
    </div>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import { useRoute } from 'vue-router';
import { ClientApi, url_image } from '../../config';
import { formatPrice } from '../../utils/formatPrice';

const {params} = useRoute();

const order = ref({});
const getData = async () => {
    const token = localStorage.getItem('token');
        const {data}  = await ClientApi.get('/orders/' + params.id, {
            headers: {
                Authorization: `Bearer ${token}`
            }
        });
        order.value = data.order;        
    }

onMounted(() => {
    getData()
})

</script>

<style lang="scss" scoped>
tbody tr td{
    vertical-align: middle;
}
</style>