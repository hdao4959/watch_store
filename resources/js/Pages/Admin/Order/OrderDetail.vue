<template>
    <div class="order-detail-container">
        <div class="card order-info mb-4">
            <div class="card-header">
                <h2 class="text-center">Chi tiết đơn hàng #{{ order.id }}</h2>
            </div>
            <div class="card-body row row-cols-2">
                <div class="col info_light">
                    <p><strong>Họ và tên:</strong> {{ order.full_name }}</p>
                    <p><strong>Số điện thoại:</strong> {{ order.phone_number }}</p>
                    <p><strong>Địa chỉ:</strong> {{ order.address }}</p>
                </div>
                <div class="col info_right">
                    <p><strong>Hình thức thanh toán:</strong> {{ order.type_pay == 0 ? 'Thanh toán khi nhận hàng' :
                        'Thanh toán online' }}</p>
                    <p><strong>Trạng thái:</strong> <span class="text-success fw-bold">{{ order.status }}</span></p>
                    <p><strong>Thời gian đặt hàng:</strong> {{ order.created_at }}</p>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="text-center">Sản phẩm trong đơn hàng</h3>
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
                                <img width="100" class="img-thumbnail" :src="`${url_image}${item.img_thumbnail}`"
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
import { useRoute } from 'vue-router';
import AdminApi, { url_image } from '../../../config';
import { onMounted, ref } from 'vue';
import { formatPrice } from '../../../utils/formatPrice';

const { params } = useRoute();
const order = ref([]);

const getData = async () => {
    const { data } = await AdminApi.get('/orders/' + params.id);
    order.value = data.order;
    console.log(order.value);
};

onMounted(() => {
    getData();
});
</script>

<style lang="scss" scoped>
.order-detail-container {
    padding: 20px;
}

.card {
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
    overflow: hidden;
}

.card-header {
    background-color: #f8f9fa;
    padding: 15px;
    border-bottom: 1px solid #dee2e6;
}

.card-header h2,
.card-header h3 {
    margin: 0;
    font-size: 1.5rem;
    color: #343a40;
}

.card-body {
    padding: 15px;
    line-height: 1.6;
}

.table {
    margin-top: 15px;
}

.table img {
    display: block;
    margin: auto;
    border-radius: 5px;
}

.table-hover tbody tr:hover {
    background-color: #f1f1f1;
}

.table th,
.table td {
    vertical-align: middle;
    text-align: center;
}

.table th {
    color: #495057;
    font-weight: 600;
}

.table-light {
    background-color: #f8f9fa;
}

.mb-4 {
    margin-bottom: 1.5rem;
}
</style>