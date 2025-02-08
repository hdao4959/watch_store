<template>
    <div>
        <h2>Thêm mới màu sắc</h2>
        <form action="" @submit.prevent="onSubmit">
            <div>
                <label for="">Màu sắc</label>
                <input type="text" class="form-control" v-model="colorName">
            </div>
            <div class="mt-2">
                <button type="submit" class="btn btn-success">Thêm</button>
                <router-link to="colors" class="btn btn-secondary">Quay lại</router-link>
            </div>
        </form>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import AdminApi from '../../../config';

const router = useRouter();
const colorName = ref('');
const onSubmit = async () => {

    try {
        const { data } = await AdminApi.post('/colors', { name: colorName.value });
        alert(data.message);
        router.push('colors');
    } catch (error) {
        alert(error.response.data.message);
    }
}
</script>

<style lang="scss" scoped></style>