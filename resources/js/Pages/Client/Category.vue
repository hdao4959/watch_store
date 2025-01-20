<template>
    <div class="mx-5">
        <template v-if="!message">
            <div class="mx-5 row row-cols-1 row-cols-md-4">
                <div v-for="product in products" :key="product.id">
                    <ProductCard :prd="product" :url_image="url_image" :prd_slug="product.slug" />
                </div>
            </div>
        </template>
        <template v-else>
            <h3 class="text-center">{{ message }}</h3>
        </template>
    </div>
</template>

<script setup>
import { useRoute } from "vue-router";
import { ClientApi, url_image } from "../../config";
import { onMounted, ref, watch } from "vue";
import ProductCard from "../../components/Client/ProductCard.vue";
const route = useRoute();

const products = ref([]);
const message = ref("");
const getData = async () => {
    try {
        const { data } = await ClientApi.get("/categories/" + route.params.slug);
        message.value = "";
        if (data.success) {
            if (data.message) {
                message.value = data.message;
            } else {
                products.value = data.products;
            }
        } else {
            message.value = data.message;
        }
    } catch (error) {
        alert(error.response.data.message);
    }
};

watch(
    () => route.params.slug,
    (newSlug, oldSlug) => {
        if (newSlug !== oldSlug) {
            getData();
        }
    }
);

onMounted(getData);
</script>

<style lang="scss" scoped></style>
