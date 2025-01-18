<template>
    <div class="footer">
        <h1 class="text-center">Hair Watch</h1>
       <div  class="row text-center">
        <div class="col"> 
        <a href="">Trang chủ</a> <br>
        <template v-if="categories.length > 0" v-for="cate in categories">
            <router-link :to="`/categories/${cate.slug}`">{{ cate.name }}</router-link><br>
        </template>
        </div>
        <div class="col"> 
        <a href="">Trang chủ</a>
        </div>
        <div class="col"> 
        <a href="">Trang chủ</a>
        </div>
       </div>
    </div>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import { ClientApi } from '../../config';
const categories = ref([]);
const getCategory = async () => {
    const { data } = await ClientApi.get('/categories');
    categories.value = data.categories
}
onMounted(() => {
    getCategory()
})
</script>

<style lang="scss" scoped>
.footer{
    background-color: rgb(214, 214, 214);

}
</style>