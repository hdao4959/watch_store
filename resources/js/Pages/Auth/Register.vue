<template>
    <div>
        <h2 class="text-center">Đăng ký</h2>
        <form action="" @submit.prevent="register" class="form">
            <span v-if="error.message" class="text-danger">{{ error.message }}</span>
            <div class="mt-2">
                <label for="">Email</label>
                <input :class="{ 'form-control': true, active: error.rule == 'email' }" type="text" v-model.trim="email"
                    placeholder="Địa chỉ email" name="" id="">
            </div>
            <div class="mt-2">

                <label for="">Password</label>
                <input :class="{ 'form-control': true, active: error.rule == 'password' }" type="text"
                    v-model.trim="password" placeholder="Password" name="" id="">
            </div>
            <div class="mt-2">

                <label for="">Confirm password</label>
                <input :class="{ 'form-control': true, active: error.rule == 'confirmPassword' }" type="text"
                    v-model="confirmPassword" placeholder="Nhập lại password" name="" id="">
            </div>
            <div class="mt-2 text-center">
                <button class="btn btn-primary">Đăng ký</button>
            </div>
            <div class="text-center">
                <router-link to="/login">Đã có tài khoản</router-link>


            </div>

        </form>
    </div>
</template>

<script setup>
import { reactive, ref } from 'vue';
import { ClientApi } from '../../config';
import { useRouter } from 'vue-router';


const route = useRouter();
const email = ref('');
const password = ref('');
const confirmPassword = ref('');
const error = reactive({
    message: '',
    rule: ''
});


const validateInputs = () => {

    if (!email.value) {
        error.message = 'Email không được để trống!';
        error.rule = 'email'
        return
    }


    if (!password.value) {
        error.message = 'Bạn chưa nhập mật khẩu';
        error.rule = 'password'
        return
    }
    if (!confirmPassword.value) {
        error.message = 'Bạn chưa xác nhận mật khẩu';
        error.rule = 'confirmPassword'
        return
    }

    if (password.value && confirmPassword.value && password.value != confirmPassword.value) {
        error.message = "Mật khẩu xác nhận không trùng khớp!"
        error.rule = 'confirmPassword'
        return
    }

    return null
}
const register = async () => {
    error.message = '';
    error.rule = '';

    validateInputs();

    if (!error.message) {
        try {
            const {data} = await ClientApi.post('/register', {
                email: email.value,
                password: password.value,
                confirmPassword: confirmPassword.value
            });
            if(data.token){
                console.log(data.token);
                
                localStorage.setItem('token', data.token);
                localStorage.setItem('account_name', data.account_name);
                localStorage.setItem('role', 2);
                route.push('/');
            }
            
            
        } catch (e) {
            error.message = e.response.data.message
        }
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