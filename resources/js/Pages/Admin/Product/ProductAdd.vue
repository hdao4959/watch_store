<template>
    <div>
    <h2>Thêm mới sản phẩm</h2>
        <form action="" @submit.prevent="onSubmit">
            <div>
                <label for="">Tên sản phẩm</label>
                <input class="form-control" type="text" name="name" v-model="productName" placeholder="Tên sản phẩm">
            </div>

            <div>
                <label for="">Slug</label>
                <input class="form-control" type="text"  v-model="productSlug" disabled>
            </div>
            <div>
                <label for="">Danh mục</label>
                <select class="form-control" form="form-control" name="" v-model="categoryId" id="">
                    <option value="">--Danh mục--</option>
                    <template v-if="categories.length > 0" v-for="category in categories">
                        <option :value=category.id class="text-success">{{ category.name }}</option>
                        <template v-if="category.children.length > 0" v-for="cate in category.children">
                        <option :value=cate.id class="text-danger">\__{{ cate.name }}</option>
                        </template>
                    </template>
                </select>
            </div>

            <div>
            <label for="">Hình ảnh</label>
            <input class="form-control"type="file" ref="img_thumbnail" @change="handleUploadImage()" id="">

            <button type="submit" class="btn btn-success">Thêm mới</button>
            </div>
        </form>
    </div>
</template>

<script setup>
import axios from 'axios';
import { onMounted, ref, watch } from 'vue';
import slugify from 'slugify';
const categories = ref([]);
const productName = ref('');
const categoryId = ref('');
const productSlug = ref('');
const img_thumbnail = ref(null);

watch(productName, (newValue) => {
    productSlug.value = slugify(newValue, {lower:true});
})
const getCategories = async () => {
    const {data}  = await axios.get('/api/admin/allCategories');
    categories.value = data.allCategories
}
onMounted(() => {
    getCategories();
})

const handleUploadImage = (event) => {
    img_thumbnail.value = event.target.files[0]
}
const onSubmit = async () => {
    // const response = await axios.post('/api/admin/products', {
    //     name: productName.value,
    //     slug: productSlug.value,
    //     category_id: categoryId.value,
    //     img_thumbnail: img_thumbnail.value
    // })
    console.log(
        {
    name: productName.value,
    slug: productSlug.value,
    category_id: categoryId.value,
    img_thumbnail: img_thumbnail.value
 }
    );
    
}



</script>

<style lang="scss" scoped></style>
