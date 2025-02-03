<template>
    <div class="mx-5">
    
    <h5><router-link class="text-dark" to="/">Trang chủ</router-link> | Tìm kiếm | {{ route.query.query }}</h5>

    <h3 class="my-3 text-center">
        Kết quả tìm kiếm cho <span class="text-success">`{{ route.query.query }}`</span></h3>

    <div>
        <template v-if="products.length > 0">
            <div class="mx-5 row row-cols-1 row-cols-md-4">
                <div v-for="product in products" :key="product.id">
                    <ProductCard :prd="product" :url_image="url_image" />
                </div>
            
            </div>
        </template>
        <h2 class="text-center" v-else >
            {{ message }}
        </h2>
    </div>
</div>
</template>

<script setup>
import { useRoute } from 'vue-router';
import { ClientApi, url_image } from '../../config';
import { onMounted, ref, watch } from 'vue';
import ProductCard from '../../components/Client/ProductCard.vue';

const route = useRoute();
const products = ref([]);
const message = ref('');
const getDataSearch = async (queryValue) => {
    try {
        const { data } = await ClientApi.get('search?query=' + queryValue);
        if (data.success == true && data.message) {
            products.value = [];
            message.value = data.message;
        } else {
            message.value = '';
            products.value = data.products;
        }
    } catch (error) {
        message.value = error.response.data.message;
        products.value = [];
    }

}

onMounted(() => {
    getDataSearch(route.query.query)
})

watch(() => route.query.query, (newQuery) => {

    if(newQuery){
        console.log(newQuery);
        getDataSearch(newQuery)

    }
    { immediate: true } // Gọi ngay lần đầu
})
</script>

<style lang="scss" scoped></style>