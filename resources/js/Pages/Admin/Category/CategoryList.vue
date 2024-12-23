<template>
    <div>
      <h2>Danh sách danh mục</h2>
      <router-link class="btn btn-success" to="add-category">Thêm mới</router-link>
      <table class="table table-striped">
        <thead>
          <tr>
            <th>Id</th>
            <th>Name</th>
            <th>Slug</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <!-- Lặp qua tất cả các danh mục cha -->
          <template v-for="category in categories" :key="category.id">
            <tr>
              <td>{{ category.id }}</td>
              <td>{{ category.name }}</td>
              <td>{{ category.slug }}</td>
              <td>
                <button class="btn btn-secondary">Show</button>
                <button class="btn btn-warning">Edit</button>
                <button @click="onDelete(category.id)" class="btn btn-danger">Delete</button>
              </td>
            </tr>
            <!-- Kiểm tra và hiển thị các danh mục con -->
            <template v-if="category.children && category.children.length > 0">
              <tr v-for="child in category.children" :key="child.id">
                <td></td> <!-- Tạo khoảng cách cho danh mục con -->
                <td>
                  <span class="text-danger">\___{{ child.name }}</span>
                </td>
                <td>{{ child.slug }}</td>
                <td>
                  <button class="btn btn-secondary">Show</button>
                  <button class="btn btn-warning">Edit</button>
                  <button @click="onDelete(child.id)" class="btn btn-danger">Delete</button>
                </td>
              </tr>
            </template>
          </template>
        </tbody>
      </table>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  import { ref, onMounted } from 'vue';
  

  export default {
    setup() {
      const categories = ref([]);
  
      const onDelete = async (id) => {
        let is_delete = confirm('Bạn có chắc muốn xoá danh mục này không?')
        if(is_delete){

            try{
            const response =  await axios.delete('/api/admin/categories/' + id);
            if(response.data){
                getData();
                alert(response.data.message);
            }
            }catch(error){
                alert(error.data.message);
            }
        }
        }
      const getData = async () => {
        const { data } = await axios.get('/api/admin/categories');
        categories.value = data;
      };
  
      onMounted(getData);
  
      return { categories, onDelete };
    },
  };


  </script>
  
  <style lang="scss" scoped>
  .table {
    margin-top: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
  }
  
  td {
    vertical-align: middle;
  }
  </style>
  