<template>
    <div>
        <h2>Thêm mới size</h2>
        <form action="" @submit.prevent="onSubmit">
            <div>
                <label for="">Số size</label>
                <input type="number" class="form-control" v-model="sizeName">
            </div>
            <div class="mt-2">
                <button type="submit" class="btn btn-success">Thêm</button>
                <router-link to="/sizes" class="btn btn-secondary">Quay lại</router-link>
            </div>
        </form>
    </div>
</template>

<script setup>
import axios from 'axios';
import { ref } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();
const sizeName = ref('');
const onSubmit = async () => {

    try {
        const { data } = await axios.post('/api/admin/sizes', { name: sizeName.value });
        alert(data.message);
        router.push('/sizes');
    } catch (error) {
        alert(error.response.data.message);
    }
}
</script>

<style lang="scss" scoped></style>