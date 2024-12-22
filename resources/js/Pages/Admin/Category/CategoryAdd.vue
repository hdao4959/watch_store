<template>
    <div>
      <h2>Thêm mới danh mục</h2>
      <div v-if="successMessage" class="alert alert-success mt-3">{{ successMessage }}</div>
      <form @submit.prevent="addCategory">
        <div>
          <label for="name">Tên danh mục</label>
          <input type="text" id="name" v-model="categoryName"
            class="form-control" placeholder="Nhập tên danh mục"/>
        </div>
        <div>
            <label for="">Danh mục cha</label>
            <select class="form-control" name="parent_id" v-model="parentId" id="">
            <option value="">--Danh mục cha--</option>
            <template v-for="parentCategory in parentCategories">
            <option :value=parentCategory.id>{{ parentCategory.name }}</option>
            </template>
            </select>
        </div>
        <span v-if="errorMessage" class="text-danger">{{ errorMessage }}</span>
        <div class="text-center mt-3">
          <button class="btn btn-success" type="submit">Thêm</button>
          <router-link to="/categories" class="btn btn-secondary">Quay lại</router-link>
        </div>
      </form>
    </div>
  </template>
  
  <script setup>
  import { onMounted, ref } from "vue";
  import { useRouter } from "vue-router";
  import axios from "axios";
  
  const categoryName = ref("");
  const parentId = ref("");
  const errorMessage = ref("");
  const successMessage = ref("");
  const router = useRouter();
  const parentCategories = ref([]);

  const getParentCategories = async() => {
        const {data} = await axios.get('/api/admin/parentCategories');
        parentCategories.value = data.parentCategories;

  }

  onMounted(() => {
    getParentCategories();
  })

  const addCategory = async () => {
    try {
      if (!categoryName.value.trim()) {
        errorMessage.value = "Tên danh mục không được để trống!";
        return;
      }
  
      const response = await axios.post("/api/admin/categories", { name: categoryName.value, parent_id: parentId.value });
      
      if(response.data.success == true){
        successMessage.value = response.data.message
        categoryName.value = "",
        parentId.value = ""
        router.push('/categories')
      }
      if(response.data.success == false){
        errorMessage.value = response.data.message;
      }

    } catch (error) {
      errorMessage.value = error.response?.data?.message || "Đã xảy ra lỗi!";
      successMessage.value = "";
    }
  };


  </script>
  
  <style lang="scss" scoped>

  </style>
  