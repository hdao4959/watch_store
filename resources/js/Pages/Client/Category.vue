<template>
    <div class="mx-5">
        <template v-if="!message">
        <div class="mx-5 row row-cols-1 row-cols-md-4">
            <router-link class="nav-link" :to="{ name: 'product-detail', params: { slug: product.slug } }" 
              v-for="product in products" :key="product.id">
               <ProductCard :prd="product" :url_image="url_image"/>
               </router-link>
        </div>
        </template>
        <template v-else>
            <h3 class="text-center">{{ message }}</h3>
        </template>
    </div>
</template>

<script setup>
import { useRoute } from 'vue-router';
import { ClientApi, url_image } from '../../config';
import { onMounted, ref } from 'vue';
import ProductCard from '../../components/Client/ProductCard.vue';
const { params } = useRoute();

const products = ref([]);
const message = ref('');
const getData = async () => {
    try {
        const { data } = await ClientApi.get('/categories/' + params.slug);

        if (data.success) {
            if(data.message){
                message.value = data.message;
            }else{
                products.value = data.products
            }
        } else {
            message.value = data.message
        }
    } catch (error) {
        alert(error.response.data.message);
    }


}
onMounted(() => {
    getData()
})
</script>

<style lang="scss" scoped></style>