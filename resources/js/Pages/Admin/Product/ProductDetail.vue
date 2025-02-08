<template>
    <div>
        <h2 class="text-primary">{{ productDetail.name }}</h2>
        <h3>Dòng sản phẩm: {{ productDetail?.category?.parent.name }} {{ productDetail?.category?.name }}</h3>

    </div>

    <div class="row">
    <!--Col trái -->
        <div class="col-md-5 text-center">
            <img class=" col-md-8 img-fluid" :src="`${url_image}${productDetail.img_thumbnail}`" alt="">
        </div>

<!--Col phải -->
        <div class="col-md-7">
        <!-- Table biến thể -->
            <table class="table text-center">
                <thead>
                    <tr >
                        <th class="bg-dark text-white">Size</th>
                        <th class="bg-dark text-white">Color</th>
                        <th class="bg-dark text-white">Price</th>
                        <th class="bg-dark text-white">Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="variant in productDetail.variants">

                        <td class="fw-bold">{{ variant?.size?.name }}mm</td>
                        <td >{{ variant?.color?.name }}</td>
                        <td>{{ formatPrice(variant.price) }}</td>
                        <td class="text-danger">{{ variant.quantity }}</td>
                    </tr>
                </tbody>
            </table>

<!-- Div nội dung -->
            <div v-html="productDetail.description">
            </div>
        </div>
    </div>


</template>

<script setup>
import { useRoute } from 'vue-router';
import AdminApi from '../../../config';
import { onMounted, ref } from 'vue';
import { url_image } from '../../../config';
const { params } = useRoute();
const productDetail = ref({});


const getData = async () => {
    try {
        const { data } = await AdminApi.get('/products/' + params.id);
        productDetail.value = data.product;

    } catch (error) {
        alert(error.response.data.message)
    }
}

onMounted(() => {
    getData();
});


const formatPrice = (price) => {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
    }).format(price);
}
</script>

<style lang="scss" scoped></style>