<template>
    <div>
        <header>
            <nav class="row">
                <ul class="col d-flex nav_left">
                    <li class="nav-link"><router-link to="/" class="nav-link mx-2 my-2">Trang chủ</router-link></li>
                   
                    <li class="nav-link position-relative" v-for="category in categories" :key="category.id">
                        <router-link class="nav-link mx-2 my-2" :to="{name:'product-of-category', params: {slug: category.slug}}">
                            {{ category.name }}
                        </router-link>
                        <ul v-if="category.children && category.children.length" class="dropdown">
                            <li v-for="child in category.children" :key="child.id">
                                <router-link class="dropdown-link" :to="{name:'product-of-category', params: {slug: child.slug}}">
                                    {{ child.name }}
                                </router-link>
                            </li>
                        </ul>
                    </li>
                </ul>
              <ul  class="col nav_right">
                <div class="text-end mt-2 ">
                    
                    <router-link to="/cart"  ><i class="icons fa-solid fa-cart-shopping mx-2"></i></router-link>
                    
                      <template v-if="account_name">
                        <router-link to="/account">{{ account_name }}</router-link>
                        <!-- <button @click="logout" class="btn btn-danger">Đăng xuất</button> -->
                    </template>
                    <template v-else>
                    <router-link to="/login"  v-if="!account_name">Đăng nhập</router-link>
                    </template>
                    <!-- <i class="icons fa-regular fa-user mx-2"></i> -->
                </div>
                
                </ul>
            </nav>
            
        </header>
    </div>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import { ClientApi } from '../../config';

const account_name = ref(localStorage.getItem('account_name') || '');



const categories = ref([]);
const getData = async () => {
    const {data} = await ClientApi.get('/categories');
    categories.value = data.categories;
}

onMounted(() => {
    getData()
})

const logout = async () => {
try {
    const token = localStorage.getItem('token')
    
    const {data} = await ClientApi.post('/logout', {}, {
        headers: {
            Authorization: `Bearer ${token}`
        }
    })

    if(data.success == true){
        localStorage.removeItem('token');
        localStorage.removeItem('account_name');
        account_name.value = ''
        router.push('/')
        alert(data.message);
    }

    if(data.success == false){
        alert(data.message);
    }
} catch (error) {
    alert(error.response.data.message);
}
}
</script>

<style lang="scss" scoped>
nav{
    border-bottom: 1px solid black;

}
ul li{
    color: black;
   
}


.dropdown {
    display: none; 
    position: absolute; 
    top: 100%; 
    left: 0;
    background-color: #333; 
    padding: 10px;
    border-radius: 5px;
    z-index: 10;
    list-style: none;
}

.nav-link:hover .dropdown {
    display: block; 
}

.dropdown-link {
    display: block;
    padding: 5px 10px;
    color: #fff;
    text-decoration: none;
    white-space: nowrap;
}

.dropdown-link:hover {
    background-color: #444; 
    color: #f9f9f9;
}
.icons{
    font-size: 20px;
}
</style>