<template>
    <div>
        <h2>Thêm mới sản phẩm</h2>

        <form action="" @submit.prevent="onSubmit">

            <div class="row">
                <div class="col-md-6">
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
                        <h4>Biến thể sản phẩm</h4>
                        <table class="table table-light text-center">
                            <thead>
                                <tr>
                                    <th>Size</th>
                                    <th>Màu sắc</th>
                                    <th>Giá bán</th>
                                    <th>Số lượng</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template v-for="(size, sizeIndex) in sizes" :key="sizeIndex">
                                    <template v-for="(color, colorIndex) in colors" :key="colorIndex">
                                       
                                            <tr v-if="colorIndex === 0">
                                                <!-- Cột Size: Hiển thị rowspan -->
                                                <td :rowspan="colors.length">{{ size.name }}mm</td>
                                                <td :style="`color:${color.name}; font-weight:bold`">{{ color.name }}
                                                </td>
                                                <td>
                                                        <input v-model="variants[size.id][color.id].price" type="number" placeholder="Giá bán" class="text-center">
                                                </td>
                                                <td>
                                                    <input v-model="variants[size.id][color.id].quantity" type="number" placeholder="Số lượng" class="text-center">
                                                </td>
                                            </tr>
                                            <tr v-else>
                                                <!-- Các hàng sau không hiển thị Size -->
                                                <td :style="`color:${color.name}; font-weight:bold`">{{ color.name }}
                                                </td>
                                                <td>
                                                        <input v-model="variants[size.id][color.id].price" type="number" placeholder="Giá bán" class="text-center">
                                                </td>
                                                <td>
                                                    <input v-model="variants[size.id][color.id].quantity" type="number" placeholder="Số lượng" class="text-center">
                                                </td>
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
const variants = ref([]);


watch(productName, (newValue) => {
    productSlug.value = slugify(newValue, { lower: true });
})
const getCategories = async () => {
    const { data } = await axios.get('/api/admin/allCategories');
    categories.value = data.allCategories
}

const getSizesAndColors = async () => {
    const sizesResponse = await axios.get('/api/admin/allSizes');
    const colorsResponse = await axios.get('/api/admin/allColors');
    sizes.value = sizesResponse.data.sizes
    colors.value = colorsResponse.data.colors
    sizes.value.forEach((size) => {
        variants.value[size.id] = {},
            colors.value.forEach((color) => {
                variants.value[size.id][color.id] = {
                    quantity: 0,
                    price: 0
                }
            });
    });
    
    
}


onMounted(() => {
    getCategories();
    getSizesAndColors()


})

const handleUploadImage = (event) => {
    img_thumbnail.value = event.target.files[0]
}

const prepareVariantsData = () => {
    const result = [];
    Object.entries(variants.value).forEach(([sizeId, colorData]) => {
        Object.entries(colorData).forEach(([colorId, data]) => {
            if (data.quantity > 0 ) {
                result.push({
                    size_id: sizeId,
                    color_id: colorId,
                    price: data.price,
                    quantity: data.quantity,
                });
            }
        });
    });
    return result;
};

const onSubmit = async () => {
    const formData = new FormData();
    formData.append('name', productName.value)
    formData.append('slug', productSlug.value)
    formData.append('category_id', categoryId.value)
    formData.append('img_thumbnail', img_thumbnail.value)

    const variantsData = prepareVariantsData(); // Tạo dữ liệu `variants` từ hàm bên dưới
    formData.append('variants', JSON.stringify(variantsData)); 


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
