<template>
  <div>
    <header>
      <nav class="row">
        <ul class="col d-flex nav_left">
          <li class="nav-link">
            <router-link to="/" class="nav-link mx-2 my-2">Trang chủ</router-link>
          </li>

          <li class="nav-link position-relative" v-for="category in categories" :key="category.id">
            <router-link class="nav-link mx-2 my-2"
              :to="{ name: 'product-of-category', params: { slug: category.slug } }">
              {{ category.name }}
            </router-link>
            <ul v-if="category.children && category.children.length" class="dropdown">
              <li v-for="child in category.children" :key="child.id">
                <router-link class="dropdown-link" :to="{ name: 'product-of-category', params: { slug: child.slug } }">
                  {{ child.name }}
                </router-link>
              </li>
            </ul>
          </li>
        </ul>
        <form id="form_search" class="col nav_center" action="" style="width:100%">
          <input class="input_search" type="text" id="input_search" v-model="valueSearch"
            placeholder="Tìm kiếm sản phẩm" />
          <i class="icon_search fa-solid fa-magnifying-glass"></i>
        </form>
        <ul class="col nav_right">
          <div class="text-end mt-2">
            <router-link to="/cart"><i class="icons fa-solid fa-cart-shopping mx-2"></i></router-link>
            <span>{{ countQuantityCart() }}</span>
            <template v-if="account_name">
              <router-link to="/account">{{ account_name }}</router-link>
              <!-- <button @click="logout" class="btn btn-danger">Đăng xuất</button> -->
            </template>
            <template v-else>
              <router-link to="/login" v-if="!account_name">Đăng nhập</router-link>
            </template>
            <!-- <i class="icons fa-regular fa-user mx-2"></i> -->
          </div>
        </ul>
      </nav>
    </header>
  </div>
</template>

<script setup>
import { onMounted, ref } from "vue";
import { ClientApi } from "../../config";
import { countQuantityCart } from "../../utils/countQuantityCart";
import { useRouter } from "vue-router";

const route = useRouter();
const account_name = ref(localStorage.getItem("account_name") || "");
const valueSearch = ref('');

const categories = ref([]);

// Danh sách danh mục hiển thị lên menu
const getCagetories = async () => {
  const { data } = await ClientApi.get("/categories");
  categories.value = data.categories;
};

onMounted(() => {
  const formSearch = document.getElementById("form_search");
  formSearch.onsubmit = function (event) {
    search(event, valueSearch.value);
    valueSearch.value = '';
  }
  // Submit tìm kiếm sản phẩm
  getCagetories();

});

 //Logic tìm kiếm sản phẩm
const search = (event,valueSearch) => {
  route.push({path: '/search', query: {query:valueSearch}})
  event.preventDefault();
}
// Đăng xuất
// const logout = async () => {
//   try {
//     const token = localStorage.getItem("token");

//     const { data } = await ClientApi.post(
//       "/logout",
//       {},
//       {
//         headers: {
//           Authorization: `Bearer ${token}`,
//         },
//       }
//     );

//     if (data.success == true) {
//       localStorage.removeItem("token");
//       localStorage.removeItem("account_name");
//       account_name.value = "";
//       router.push("/");
//       alert(data.message);
//     }

//     if (data.success == false) {
//       alert(data.message);
//     }
//   } catch (error) {
//     alert(error.response.data.message);
//   }
// };
</script>

<style lang="scss" scoped>
nav {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  z-index: 1000;
  background-color: white;
  border-bottom: 1px solid #ddd;
  padding: 1rem 2rem;
  box-shadow: 0px 10px 12px rgb(138, 137, 137);

  .nav_left,
  .nav_right,
  .nav_center {
    display: flex;
    align-items: center;
    list-style: none;
    padding: 0;
    margin: 0;

    .nav-link {
      position: relative;
      font-size: 1rem;
      font-weight: bold;
      text-decoration: none;
      margin: 0 1rem;
      transition: color 0.3s ease;

      &:hover {
        color: #ffc107;
      }

      &.active {
        color: #ffc107;
        text-decoration: underline;
      }

      .dropdown {
        position: absolute;
        top: 100%;
        left: 0;
        background-color: white;
        border: 1px solid #ddd;
        border-radius: 5px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        display: none;
        z-index: 10;

        li {
          width: 100px;
          list-style: none;

          .dropdown-link {
            color: #333;
            text-decoration: none;
            transition: background-color 0.3s ease;

            &:hover {
              background-color: #f8f9fa;
              color: #ffc107;
            }
          }
        }
      }

      &:hover .dropdown {
        display: block;
      }
    }
  }

  .nav_right {
    justify-content: flex-end;

    .icons {
      color: black;
      font-size: 1.2rem;
      transition: transform 0.3s ease;

      &:hover {
        color: #ffc107;
      }
    }

    .text-end {
      display: flex;
      align-items: center;

      a {
        margin-left: 1rem;
        color: black;
        font-weight: bold;
        text-decoration: none;
        transition: color 0.3s ease;

        &:hover {
          color: #ffc107;
        }
      }
    }
  }

  .nav_center {
    .input_search {
      position: relative;
      border-radius: 20px;
      width: 100%;
      text-align: center;
      border: 1px solid gray;
      height: 35px;
    }

    .icon_search {
      position: absolute;
      margin-left: 10px;
    }
  }
}
</style>
