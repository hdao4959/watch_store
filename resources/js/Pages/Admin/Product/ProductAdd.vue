<template>
    <div>
        <h2>Thêm mới sản phẩm</h2>

        <form action="" @submit.prevent="onSubmit">

            <div class="row">
                <div class="col-md-6">


                    <span v-if="error != ''">{{ error }}</span>
                    <div>
                        <label for="">Tên sản phẩm</label>
                        <input class="form-control" type="text" name="name" v-model="productName"
                            placeholder="Tên sản phẩm">
                    </div>

                    <div>
                        <label for="">Slug</label>
                        <input class="form-control" type="text" v-model="productSlug" disabled>
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
                        <input class="form-control" type="file" @change="handleUploadImage" id="">

                    </div>
                    <div class="mt-2">
                        <button type="submit" class="btn btn-success mx-1">Thêm mới</button>
                        <router-link to="/products" class="btn btn-secondary">Quay lại</router-link>
                    </div>
                </div>

                <div class="col-md-6">
                    <div>
                        <label for="">Sizes</label>
                        <table class="table text-center">
                            <thead>
                                <tr>
                                    <th>Size</th>
                                    <th>Màu sắc</th>
                                    <th>Số lượng</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template v-for="(size, sizeIndex) in sizes" :key="sizeIndex">
                                    <template v-for="(color, colorIndex) in colors" :key="colorIndex">
                                        <tr v-if="colorIndex === 0">
                                            <!-- Cột Size: Hiển thị rowspan -->
                                            <td :rowspan="colors.length">{{ size.name }}mm</td>
                                            <td :style="`color:${color.name}`">{{ color.name }}</td>
                                            <td><input type="number"></td>
                                        </tr>
                                        <tr v-else>
                                            <!-- Các hàng sau không hiển thị Size -->
                                            <td :style="`color:${color.name}`">{{ color.name }}</td>
                                            <td><input type="number"></td>
                                        </tr>
                                    </template>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>

<script setup>
import axios from 'axios';
import { onMounted, ref, watch } from 'vue';
import slugify from 'slugify';
import { useRouter } from 'vue-router';

const router = useRouter();
const categories = ref([]);
const sizes = ref([]);
const colors = ref([]);
const productName = ref('');
const categoryId = ref('');
const productSlug = ref('');
const img_thumbnail = ref(null);

watch(productName, (newValue) => {
    productSlug.value = slugify(newValue, { lower: true });
})
const getCategories = async () => {
    const { data } = await axios.get('/api/admin/allCategories');
    categories.value = data.allCategories
}

const getSizes = async () => {
    const { data } = await axios.get('/api/admin/allSizes');
    sizes.value = data.sizes
}
const getColors = async () => {
    const { data } = await axios.get('/api/admin/allColors');
    colors.value = data.colors
}
onMounted(() => {
    getCategories();
    getSizes();
    getColors();
})

const handleUploadImage = (event) => {
    img_thumbnail.value = event.target.files[0]
}
const onSubmit = async () => {
    const formData = new FormData();
    formData.append('name', productName.value)
    formData.append('slug', productSlug.value)
    formData.append('category_id', categoryId.value)
    formData.append('img_thumbnail', img_thumbnail.value)

    try {
        const response = await axios.post('/api/admin/products', formData);

        if (response.data.success) {
            alert(response.data.message);
            router.push('/products')
        }


    } catch (error) {
        alert(error.response?.data?.message || 'Đã xảy ra lỗi không xác định');
    }

}



</script>

<style lang="scss" scoped></style>
