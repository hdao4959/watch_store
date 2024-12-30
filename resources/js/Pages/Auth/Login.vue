<template>
    <div>
        <h2 class="text-center">Đăng nhập</h2>
        <form action="" @submit.prevent="submit_login" class="form">
        <span class="text-danger" v-if="error.message">{{ error.message }}</span>
            <div class="mt-2">
                <label for="">Email</label>
                <input :class="{ 'form-control': true, active: error.rule == 'email'  }" type="text" v-model.trim="email"
                    placeholder="Địa chỉ email" name="" id="">
            </div>
            <div class="mt-2">

                <label for="">Password</label>
                <input :class="{ 'form-control': true, active: error.rule == 'password' }" type="text"
                    v-model.trim="password" placeholder="Password" name="" id="">
            </div>
           
            <div class="mt-2 text-center">
                <button type="submit" class="btn btn-primary">Đăng nhập</button>
            </div>
            <div class="text-center">
                <router-link to="/register">Chưa có tài khoản</router-link>
            </div>

        </form>
    </div>
</template>


<script setup>
import { ref } from 'vue';
import { ClientApi } from '../../config';
import { useRouter } from 'vue-router';


const router = useRouter();
const email = ref('')
const password = ref('');
const error = ref({
    message: '',
    rule: ''
});

const validateForm = () => {

    error.value.message = '';
    error.value.rule = '';
    if(!email.value){
        error.value.message = 'Bạn chưa nhập email',
        error.value.rule = 'email'
        return false
    }
    if(!password.value){
        error.value.message = 'Bạn chưa nhập password',
        error.value.rule = 'password'
        return false
    }
    return true

}

const submit_login = async () => {
    if(!validateForm()) return
    
    try {
    const {data} = await ClientApi.post('/login', {
        email: email.value, 
        password: password.value
    })

    if(data.success == true && data.token){
        localStorage.setItem('token', data.token);
        localStorage.setItem('account_name', data.account_name);
        router.push('/');
    }
} catch (e) {
        error.value.message = e.response.data.message
        email.value = ''
        password.value = ''
    }

}
</script>

<style lang="scss" scoped>
.form {
    margin: 10px 300px 10px 300px;
    padding: 10px 100px 10px 100px;
    // border: 1px solid gray;
}

.form-control.active {
    border: 2px solid rgb(255, 79, 79);
}
</style>