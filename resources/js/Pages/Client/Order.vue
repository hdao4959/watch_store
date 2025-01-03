<template>
    <div class="container my-5">
        <h3 class="text-center mb-4">Thanh toán</h3>

        <!-- Form thông tin khách hàng -->
        <div class="card p-4 shadow-sm mb-4">
            <h5>Thông tin khách hàng</h5>
            <form>
                <div class="mb-3">
                    <label for="fullName" class="form-label">Họ và tên</label>
                    <input type="text" id="fullName" class="form-control" placeholder="Nhập họ và tên">
                </div>
                <div class="mb-3">
                    <label for="phoneNumber" class="form-label">Số điện thoại</label>
                    <input type="text" id="phoneNumber" class="form-control" placeholder="Nhập số điện thoại">
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Địa chỉ</label>
                    <input type="text" id="address" class="form-control" placeholder="Nhập địa chỉ">
                </div>
            </form>
        </div>

        <!-- Bảng sản phẩm -->
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">Sản phẩm trong đơn hàng</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered text-center">
                    <thead class="table-light">
                        <tr>
                            <th>Ảnh</th>
                            <th>Tên sản phẩm</th>
                            <th>Kích cỡ</th>
                            <th>Màu sắc</th>
                            <th>Số lượng</th>
                            <th>Giá</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="items.length > 0" v-for="item in items" :key="item.product_id">
                            <td>
                                <img :src="`${url_image}${item.img_thumbnail}`" alt="Product Image"
                                    class="img-thumbnail" width="50">
                            </td>
                            <td>{{ item.product_name }}</td>
                            <td>{{ item.size_name }}mm</td>
                            <td>{{ item.color_name }}</td>
                            <td>{{ item.quantity }}</td>
                            <td>{{ formatPrice(item.price) }}</td>
                        </tr>
                    </tbody>
                </table>
                <div v-if="items.length > 0" class="text-end mt-3">
                    <h5 class="fw-bold text-danger">Tổng tiền: {{ formatPrice(total_price) }}</h5>
                </div>
                <div v-else class="text-center">
                    <p class="text-muted">Giỏ hàng trống.</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import { useRoute } from 'vue-router';
import { url_image } from '../../config';
import { formatPrice } from '../../utils/formatPrice';

const { query } = useRoute();

const items = ref([]);
const total_price = ref(0);

onMounted(() => {
    items.value = JSON.parse(query.items || '[]');
    total_price.value = query.total_price || 0;
});
</script>

<style lang="scss" scoped>
.container {
    max-width: 900px;
    margin: 0 auto;
}

.card {
    border-radius: 10px;
}

.table img {
    max-height: 50px;
}

.table th,
.table td {
    vertical-align: middle;
}
</style>