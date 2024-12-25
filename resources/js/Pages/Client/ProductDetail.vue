<template>
    <div class="mx-5">
        <div class="row">
            <!-- Hình ảnh sản phẩm -->
            <div class="col-md-6">
                <div class="text-center" >
                    <img width="60%" class="img-fluid" :src="`${url_image}${product.img_thumbnail}`" alt="Hình ảnh sản phẩm">
                </div>
            </div>

            <!-- Thông tin sản phẩm -->
            <div class="col-md-6">
                <h3>{{ product.name }}</h3>
                <h5 class="text-success">
                    Dòng: {{ product?.category?.parent?.name }} {{ product?.category?.name }}
                </h5>

                <div class="row_color">
                Màu sắc: 
                    <template v-if="product.variants" v-for="(variant, index) in product.variants" :key="index">
                        <button :class="{active: activeIndex == index  }" @click="handleActive(index)" class="button_color">{{ variant.color.name }} <div class="icon_check"><i class="fa-solid fa-check"></i></div></button>
                        
                    </template>
                </div>
                Kích cỡ
                <div class="row_size">
                    <template v-if="sizes" v-for="(size) in sizes" >
                        <button class="button_size">{{ size.size.name }}mm <div class="icon_check"><i class="fa-solid fa-check"></i></div></button>
                    </template>
                </div>

                
            </div>
        </div>
    </div>
</template>


<script setup>
import { useRoute } from 'vue-router';
import { ClientApi, url_image } from '../../config';
import { computed, onMounted, ref } from 'vue';


const activeIndex = ref(false);
const { params } = useRoute();
const product = ref({});
const sizes = ref([]);
const getData = async (slug) => {
    const { data } = await ClientApi.get('/products/' + slug);
    product.value = data.product;

    // Lọc các kích thước duy nhất
    const seen = new Set();
    sizes.value = product.value.variants
        ?.filter((variant) => {
            const isDuplicate = seen.has(variant.size.id);
            if (!isDuplicate) {
                seen.add(variant.size.id);
                return true;
            }
            return false;
        });
};




const handleActive = (index) => {
    if(index === activeIndex.value){
        activeIndex.value = null
    }else{
        activeIndex.value = index
    }
    
}



onMounted(() => {
    getData(params.slug)
})

</script>

<style lang="scss" scoped>

.row_color{
    display: flex;
    justify-content: start;
    flex-wrap: wrap;
}

.button_color{
    position: relative;
    text-align: center;
    width: 50px;
    height:30px;
    margin: 0 5px 0 5px;
    border: 1px solid gray;
    align-content: center;

    
}

.icon_check{
    display: none;
    position: absolute;
    bottom: -7px;
    right: 0px;
    .fa-check{
        font-size: 10px;
    }
}
.button_color.active{
        color: orangered;
        border: 1px solid orangered;
        .icon_check{
            display: block;
        }
}

</style>