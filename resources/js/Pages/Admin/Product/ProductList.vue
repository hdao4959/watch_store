<template>

    <div>
        <h2>Danh sách sản phẩm</h2>
        <router-link class="btn btn-success" to="add-product">Thêm mới</router-link>
        <table class="table table-striped ">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-if="products.length > 0" v-for="product in products">
                    <td>{{ product.id }}</td>
                    <td>{{ product.name }}</td>
                    <td>{{ product.slug }}</td>
                    <td>
                        <button class="btn btn-secondary">Show</button>
                        <button class="btn btn-warning">Edit</button>
                        <button class="btn btn-danger">Delete</button>
                    </td>
                </tr>

                <tr v-else>
                    <td colspan="4">Không có sản phẩm nào</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup>

import axios from 'axios';
import { onMounted, ref } from 'vue';

const products = ref([]);
const getData = async () => {
    const { data } = await axios.get('/api/admin/products');
    products.value = data.products
}

onMounted(() => {
    getData()
})
</script>

<style lang="scss" scoped>
.table {
    margin-top: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

td {
    vertical-align: middle;
}
</style>