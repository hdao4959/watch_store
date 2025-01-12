<template>
    <div class="container my-5">
        <h3 class="text-center mb-4">Thanh toán</h3>

        <!-- Form thông tin khách hàng -->
        <div class="card p-4 shadow-sm mb-4">
            <h5>Thông tin khách hàng</h5>
            <form>
                <div class="mb-3">
                    <label for="fullName" class="form-label">Họ và tên</label>
                    <input type="text" id="fullName" class="form-control" v-model="full_name"
                        placeholder="Nhập họ và tên">
                </div>
                <div class="mb-3">
                    <label for="phoneNumber" class="form-label">Số điện thoại</label>
                    <input type="text" id="phoneNumber" class="form-control" v-model="phone_number"
                        placeholder="Nhập số điện thoại">
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Địa chỉ</label>
                    <input type="text" id="address" class="form-control" v-model="address" placeholder="Nhập địa chỉ">
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Hình thức thanh toán</label>
                    <select class="form-control" v-model="type_pay" name="" id="">
                        <option value="0">Thanh toán khi nhận hàng</option>
                        <option value="1">Thanh toán online</option>
                    </select>
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
            <button @click="handleOrder" class="btn btn-danger mt-3">Thanh toán</button>
        </div>
        <span>{{ txt }}</span>
    </div>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { ClientApi, url_image } from '../../config';
import { formatPrice } from '../../utils/formatPrice';

const router = useRouter();
const full_name = ref('');
const phone_number = ref('');
const address = ref('');
const type_pay = ref(0);
const id_user = ref('');
const items = ref([]);
const total_price = ref(0);
const cart = sessionStorage.getItem('cart') ?? [];


const getDescriptionCart = async () => {
    const { data } = await ClientApi.post('/checkout', {
        cart: JSON.parse(cart)
    });
    items.value = data.items ?? '[]';
    total_price.value = data.total_price || 0;
}

const getInfoAccount = async () => {
    const token = ref(localStorage.getItem('token') ?? '');

    const { data } = await ClientApi.get('/user', {
        headers: {
            Authorization: `Bearer ${token.value}`
        }
    });
    id_user.value = data.id
}
const txt = ref('');


function getVnpCreateDate() {
    const now = new Date();

    // Tạo thời gian theo UTC+7 (Asia/Ho_Chi_Minh)
    const offset = 7 * 60 * 60 * 1000; // UTC+7
    const localTime = new Date(now.getTime() + offset);

    const year = localTime.getFullYear();
    const month = String(localTime.getMonth() + 1).padStart(2, '0');
    const day = String(localTime.getDate()).padStart(2, '0') ;
    const hours = String(localTime.getHours()).padStart(2, '0');
    const minutes = String(localTime.getMinutes()).padStart(2, '0');
    const seconds = String(localTime.getSeconds()).padStart(2, '0');

    return `${year}${month}${day}${hours}${minutes}${seconds}`;
    // return day
}



// Sử dụng
const vnp_CreateDate = getVnpCreateDate();
// alert(vnp_CreateDate);


onMounted(() => {
    getDescriptionCart();
    getInfoAccount()

});


// const crypto = require('crypto');



const handleOrder = async () => {

    const { data } = await ClientApi.post('/order', {
        full_name: full_name.value,
        phone_number: phone_number.value,
        address: address.value,
        type_pay: type_pay.value,
        id_user: id_user.value,
        cart: JSON.parse(cart)
    })
    console.log(data);
    // if (type_pay.value == 0) {
    // }
    // else {
    // }



}
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