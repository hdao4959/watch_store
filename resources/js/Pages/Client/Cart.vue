<template>
    <div class="cart-container">
        <h2 class="text-center">Giỏ hàng</h2>

        <div class="cart-list">
            <div class="cart-item" v-for="item in listItems">
                <img
                    class="product-img"
                    :src="`${url_image}${item.img_thumbnail}`"
                    :alt="`${item.name}`"
                />
                <div class="product-details">
                    <h4 class="product-name">{{ item.name }} {{  item.variant.color.name }} {{ item.variant.size.name }}mm</h4>
                    <p class="product-price">Giá: {{formatPrice(item.quantity * item.variant.price)}}</p>
                    <div class="quantity-control">
                        <button class="quantity-btn">-</button>
                        <input type="number" class="quantity-input" :value="`${item.quantity}`" />
                        <button class="quantity-btn">+</button>
                    </div>
                </div>
                <button class="remove-btn">Xóa</button>
            </div>



        </div>

        <div class="cart-footer">
            <h4 class="total-price">Tổng cộng: 50.000.000₫</h4>
            <button class="checkout-btn">Thanh toán</button>
        </div>
    </div>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import { ClientApi, url_image } from '../../config';
import { formatPrice } from '../../utils/formatPrice';


const cart = JSON.parse(sessionStorage.getItem('cart')) ?? [];
const listItems = ref([]);
const getItemCart = async () => {
    try {
        const {data} = await ClientApi.post('/cart', {
            cart: cart
        });
        
        if(data.success){
            listItems.value = data.items
            console.log(listItems.value);
            
        }else{
            alert(data.message);
        }
        
    } catch (error) {
        alert(error.response.data.message)
    }
    
}

onMounted(()=> {
    getItemCart();
})

</script>

<style lang="scss" scoped>
.cart-container {
    margin: 20px auto;
    max-width: 800px;
    background: #f9f9f9;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.text-center {
    text-align: center;
    color: #333;
}

.cart-list {
    margin-top: 20px;
}

.cart-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 15px;
    background: #fff;
    border-radius: 5px;
    margin-bottom: 15px;
    box-shadow: 0 1px 5px rgba(0, 0, 0, 0.1);
}

.product-img {
    width: 80px;
    height: auto;
    border-radius: 5px;
    margin-right: 15px;
}

.product-details {
    flex-grow: 1;
    padding: 0 15px;
}

.product-name {
    font-size: 18px;
    font-weight: bold;
    color: #333;
    margin-bottom: 5px;
}

.product-price {
    color: #f57224;
    font-weight: bold;
}

.quantity-control {
    display: flex;
    align-items: center;
    margin-top: 10px;
}

.quantity-btn {
    background-color: #f57224;
    color: white;
    border: none;
    padding: 5px 10px;
    border-radius: 3px;
    cursor: pointer;
    margin: 0 5px;
}

.quantity-btn:hover {
    background-color: #d45a1d;
}

.quantity-input {
    width: 50px;
    text-align: center;
    border: 1px solid #ddd;
    border-radius: 3px;
    padding: 5px;
}

.remove-btn {
    background: #e74c3c;
    color: white;
    border: none;
    border-radius: 3px;
    padding: 10px;
    cursor: pointer;
}

.remove-btn:hover {
    background: #c0392b;
}

.cart-footer {
    text-align: right;
    margin-top: 20px;
}

.total-price {
    font-size: 20px;
    font-weight: bold;
    color: #333;
}

.checkout-btn {
    background: #28a745;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
}

.checkout-btn:hover {
    background: #218838;
}
</style>
