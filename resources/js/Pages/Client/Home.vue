<template>
    <div class="mx-5">
        <div class=" row row-cols-1 row-cols-md-4">
            <div class="prd_card col my-2 " v-for="product in products">
                
                <div class="icon_love"><i class="fa-regular fa-heart"></i></div>
                <div class="my-4">
                    <img class="img-fluid" :src="`${url_image}${product.img_thumbnail}`" alt="">
                </div>
                <span class="bottom_card" >{{ product.name }}</span>
            </div>
        </div>
    </div>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import { ClientApi, url_image } from '../../config';
const products = ref([]);
const getData = async () => {
    const { data } = await ClientApi.get('/');
    products.value = data.products.data
    console.log(products.value);
}

onMounted(() => {
    getData();
})
</script>

<style lang="scss" scoped>




.prd_card {
    flex: 1 0 calc(25% - 20px);
    margin: 5px;
    position:relative;
    min-height: 380px;
    background-color: rgb(227, 227, 227);
    // border: 1px solid rgb(97, 95, 95);
    &:hover{
        .bottom_card{
            // background-color: orange;
            color: orange;
        };
        img{
            transform: scale(1.1)
        }
        .icon_love{
            display: block
        }
    }
}

img{
    transition: transform 0.5s ease ;
}

.bottom_card {
    color: rgb(57, 57, 57);
    margin-top: 20px;
    margin-bottom: 20px;
}

.icon_love{
    margin-top: 10px;
    margin-right: 5px;
    width: 40px;
    height: 40px;
    background-color: rgb(255, 255, 255);
    text-align: center;
    border-radius: 50%;
    align-content: center;
    position: absolute;
    z-index: 10;
    top: 0;
    right: 0;
    // display: none;
    &:hover{
        background-color: pink;
        color: white;
    }
}
</style>