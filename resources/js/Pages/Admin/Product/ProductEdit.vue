<template>
    <h4>Chỉnh sửa sản phẩm</h4>
    <div >
        <form action="" class="row">
        <div class="col-md-5">
        
            <div>
                <label for="">Tên sản phẩm</label>
                <input class="form-control" type="text" name="name" v-model="productName" placeholder="Tên sản phẩm">
            </div>

            <div>
                <label for="">Slug</label>
                <input class="form-control" type="text" v-model="productSlug" disabled>
            </div>

            <div>
                <label for="">Danh mục</label>
                <select class="form-control" form="form-control"v-model="categoryId" id="">
                    <option value="">--Danh mục--</option>
                    <template v-if="categories.length > 0" v-for="category in categories" :key="category.id">
                        <option :value=category.id class="text-success">{{ category.name }}</option>
                        <template v-if="category.children.length > 0" v-for="cate in category.children" :key="cate.id">
                            <option :value=cate.id class="text-danger">\__{{ cate.name }}</option>
                        </template>
                    </template>
                </select>
            </div>

            <div>
                        <label for="">Hình ảnh</label>
                        <input class="form-control" type="file" @change="handleUploadImage" id="">
                <div>
                    <img style="width:20%" class="img-fluid" :src="`${url_image}${img_thumbnail}`" alt="">
                </div>
                    </div>



        </div>
        <div class="col-md-7">
            <div class="my-2">
                        <label class="" for="">Mô tả sản phẩm</label>
                        <div id="editor" style="height:200px;background-color:white;border: 1px solid gray">
                        </div>
                    </div>
        </div>
        </form>
    </div>
</template>

<script setup>
import slugify from 'slugify';
import { onMounted, ref, watch } from 'vue';
import { useRoute } from 'vue-router';
import AdminApi, { url_image } from '../../../config';

const route = useRoute();
const productName = ref('');
const productSlug = ref('');
const categories = ref([]);
const categoryId = ref('');
const description = ref('');
const img_thumbnail = ref('');
watch(productName, (newValue) => {
    productSlug.value = slugify(newValue, { lower: true });
})

const getProduct = async ( ) => {
    const {data} = await AdminApi.get('products/' + route.params.id);
    const product = data.product;

    productName.value = product.name;
    categoryId.value = product.category.id;
    description.value = product.description;
    img_thumbnail.value = product.img_thumbnail;
    
}
const getCategories = async () => {
    const { data } = await AdminApi.get('/allCategories');
    categories.value = data.allCategories
}

const handleUploadImage = (event) => {
    img_thumbnail.value = event.target.files[0]
}

const initQuillEditor = () => {
    const quill = new Quill("#editor", {
        theme: "snow",
    });
    quill.on('text-change', () => {
        description.value = quill.root.innerHTML;
    })

    watch(description, (newValue)=> {
        if(quill.root.innerHTML !== newValue){
            quill.root.innerHTML = newValue;
        }
    })
    return quill;
}

onMounted(() => {
    initQuillEditor();
    getProduct()
    getCategories();
})
</script>

<style lang="scss" scoped></style>