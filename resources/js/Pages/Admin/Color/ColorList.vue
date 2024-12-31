<template>
    <div>
        <h2>Danh sách size</h2>
        <router-link to="add-size" class="btn btn-success">Thêm mới</router-link>
        <table class="table table-striped mt-2">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-if="colors.length > 0" v-for="size in colors">
                    <td>{{ size.id }}</td>
                    <td>{{ size.name }}</td>
                    <td>
                    <button @click="onDelete(size.id)" class="btn btn-danger">Delete</button></td>
                </tr>

                <tr v-else>
                    <td colspan="3">Không có biến thể màu nào</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import AdminApi from '../../../config';
const colors = ref([]);

const getData = async () => {

    try {
        const {data} = await AdminApi.get('/colors');
        colors.value = data.colors.data
    } catch (error) {
        console.log(error.response.data.message);
    }

}

const onDelete = async (id) => {
    try {
        const isConfirm = confirm('Bạn có chắc muốn xoá màu này không?');
        if(isConfirm){
            const {data} = await AdminApi.delete('/colors/' + id);
            alert(data?.message);
            colors.value = colors.value.filter(size => size.id !== id);
        }
    } catch (error) {
        alert(error?.response?.data?.message || "Có lỗi xảy ra!");
    }

}
onMounted(() => {
    getData();
})
</script>

<style lang="scss" scoped></style>