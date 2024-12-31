<template>
    <div class="my-2">
        <h2>Thông tin tài khoản</h2>

        <h3>{{ info_account.email }}</h3>

        <button  @click="logout"  class="btn btn-danger">Đăng xuất</button>
    </div>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import { ClientApi } from '../../config';
import { useRouter } from 'vue-router';
const router = useRouter();
const info_account = ref({});
const token = localStorage.getItem('token') || '';

const getData = async () => {
    try {
        const { data } = await ClientApi.get('/user', {
            headers: {
                Authorization: `Bearer ${token}`
            }
        })
        
        info_account.value  = data;
    } catch (error) {
        alert(error.response.data.message);
    }

    
}
onMounted(() => {
    if(token){
        getData();
    }
    else{
        router.push('/')
    }
})

const logout = async () => {
    try {
        const token = localStorage.getItem('token');
        const {data} = await ClientApi.post('/logout', {}, {
            headers: {
                Authorization: `Bearer ${token}`
            }
        })

        

        if(data.success == true){
            localStorage.removeItem('token');
            localStorage.removeItem('account_name');
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

</style>