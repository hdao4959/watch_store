<template>
    <div>
        <h2 class="text-primary">{{ productDetail.name }}</h2>
        <h3>Dòng sản phẩm: {{ productDetail?.category?.parent.name }} {{ productDetail?.category?.name }}</h3> 

    </div>

    <div class="row">
        <div class="col-md-5 text-center">
            <img class=" col-md-8 img-fluid" :src="`${url_image}${productDetail.img_thumbnail}`" alt="">
        </div>

        <div class="col-md-7">
            <table class="table text-center">
                <thead class="bg-primary">
                    <tr>
                        <th class="bg-primary" >Size</th>
                        <th class="bg-primary">Color</th>
                        <th class="bg-primary">Price</th>
                        <th class="bg-primary">Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="variant in productDetail.variants">
                        
                        <td class="fw-bold">{{ variant?.size?.name }}mm</td>
                        <td :style="`color:${variant?.color?.name}`">{{ variant?.color?.name }}</td>
                        <td>{{ formatPrice(variant.price) }}</td>
                        <td class="text-danger">{{ variant.quantity }}</td>
                    </tr>
                </tbody>
            </table>


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