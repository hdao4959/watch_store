<template>
    <div class="mx-5">
        <div class="row">
            <!-- Hình ảnh sản phẩm -->
            <div class="col-md-6">
                <div class="text-center">
                    <img width="60%" class="img-fluid" :src="`${url_image}${product.img_thumbnail}`"
                        alt="Hình ảnh sản phẩm">
                </div>
            </div>

            <!-- Thông tin sản phẩm -->
            <div v-if="product?.category?.name" class="col-md-6">
                <h3>{{ product.name }}</h3>
                <h5 class="text-success">
                    Dòng: {{ product?.category?.parent?.name }} {{ product?.category?.name }}
                </h5>

                <div v-if="colors.length > 0" class="row_color my-2">
                    Màu sắc:
                    <template v-if="colors" v-for="(color, index) in colors" :key="index">
                        <button :class="{ active: activeColor == color.color.id }"
                            @click="handleActiveColor(color.color.id)" class="button_select">{{ color.color.name }} <div
                                class="icon_check"><i class="fa-solid fa-check"></i></div></button>

                    </template>
                </div>
                <div v-if="sizes.length > 0" class="row_size">
                    Kích cỡ:
                    <template v-if="sizes.length > 0" v-for="(variant, index) in sizes" :key="index">
                        <button
                            :class="{ active: activeSize == variant.size.id, 'disabled-button': variant.quantity <= 0 }"
                            @click="handleActiveSize(variant.size.id)" class="button_select mx-1">
                            {{ variant.size.name }}mm
                            <div class="icon_check"><i class="fa-solid fa-check"></i></div>
                        </button>
                    </template>
                    <div v-else>
                        <span>Vui lòng chọn màu để hiển thị kích cỡ.</span>
                    </div>
                </div>

                <div class="mt-2">
                    Giá: <span class="price" v-if="price != null">{{ price }}</span>
                </div>
                <div class="mt-2">
                    Số lượng: <input class="text-center" style="width:70px" min="1" max="10" v-model="quantity"
                        value="1" type="number" name="" id="">
                </div>
                <div class="group_button_buy row row-cols-3  my-3 gap-2">
                    <button type="submit" class="button_buynow col btn btn-danger"><i
                            class="fa-solid fa-bag-shopping"></i> Mua
                        ngay</button>
                    <button @click="handleAddCart" class="button_add_cart col btn btn-warning"> <i
                            class="fa-solid fa-cart-shopping"></i> Thêm vào giỏ hàng </button>
                </div>


            </div>
        </div>
    </div>
</template>


<script setup>
import { useRoute } from 'vue-router';
import { ClientApi, url_image } from '../../config';
import { computed, onMounted, ref } from 'vue';
import { formatPrice } from '../../utils/formatPrice';
import { countQuantityCart } from '../../utils/countQuantityCart';


const { params } = useRoute();
const product = ref({});
const sizes = ref([]);
const colors = ref([]);
const price = ref(null);
const getData = async (slug) => {
    const { data } = await ClientApi.get('/products/' + slug);
    product.value = data.product;
    setDefaultSize();
    setDefaultColor();

};

const setDefaultSize = () => {
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

}

const setDefaultColor = () => {
    const seen = new Set();
    colors.value = product.value.variants.filter((variant) => {
        const isDuplicate = seen.has(variant.color.id);
        if (!isDuplicate) {
            seen.add(variant.color.id);
            return true
        }
        return false
    });
}

const showPrice = () => {
    if (activeColor.value != null && activeSize.value != null) {
        const variant = product.value.variants.find(
            (variant) => variant.color_id == activeColor.value && variant.size_id == activeSize.value);
        price.value = variant ? formatPrice(variant.price) : null
    } else {
        price.value = null
    }

}


const activeColor = ref(null);
const activeSize = ref(null);
const quantity = ref(1);
// Khi chọn một màu, lọc ra các size tương ứng
const handleActiveColor = (color_id) => {
    if (color_id == activeColor.value) {
        activeColor.value = null;
        setDefaultSize()

    }
    else {
    handleActiveSize(null)

        activeColor.value = color_id;
        // Lấy các size tương ứng với màu được chọn
        sizes.value = product.value.variants.filter(variant => variant.color_id === color_id);
        showPrice()
    }
};

const handleActiveSize = (size_id) => {
    
    if (size_id == activeSize.value) {
        activeSize.value = null
    } else {
        activeSize.value = size_id
        showPrice()
    }
}


onMounted(() => {
    getData(params.slug)
})


const handleAddCart = () => {
    if (!activeColor.value || !activeSize.value) {
        alert('Vui lòng chọn Màu sắc và Kích cỡ!');
    } else {

        const newProduct = {
            id: product.value.id,
            size: activeSize.value,
            color: activeColor.value,
            quantity: quantity.value
        }

        const cart = JSON.parse(sessionStorage.getItem('cart')) ?? [];
// Kiểm tra tồn tại trong giỏ hàng
        const existProductInCart = cart.find((item) =>
            item.id == newProduct.id
            && item.size == newProduct.size
            && item.color == newProduct.color);

        if (existProductInCart) {
            existProductInCart.quantity += newProduct.quantity
        } else {
            cart.push(newProduct)
        }
        sessionStorage.setItem('cart', JSON.stringify(cart));
        countQuantityCart();
    }

}
</script>

<style lang="scss" scoped>
.row_color {
    display: flex;
    justify-content: start;
    flex-wrap: wrap;
}

.button_select {
    position: relative;
    text-align: center;
    width: 50px;
    height: 30px;
    margin: 0 5px 0 5px;
    border: 1px solid gray;
    align-content: center;


}

.icon_check {
    display: none;
    position: absolute;
    bottom: -7px;
    right: 0px;

    .fa-check {
        font-size: 10px;
    }
}

.button_select.active {
    color: orangered;
    border: 1px solid orangered;

    // .icon_check {
    //     display: block;
    // }
}

.price {
    color: red;
    font-size: large;
    font-weight: bold;
}

.disabled-button {
    background-color: #ccc;
    color: #707070;
    cursor: not-allowed;
    pointer-events: none;
}
</style>