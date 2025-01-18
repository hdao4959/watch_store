import { ref } from "vue";

const quantity = ref(0)
export const countQuantityCart = () => {

    const cart = sessionStorage.getItem('cart') || null;
    if(cart){
        const parse_cart = JSON.parse(cart);
        const total_quantity = parse_cart.reduce((sum, item) => sum + item.quantity, 0);
    
        quantity.value = total_quantity
        
    }else{
        quantity.value = 0;
    }
    
     return quantity.value
}
