import { createRouter, createWebHistory } from 'vue-router';
import ClientLayout from './js/Layouts/ClientLayout.vue';
import AdminLayout from './js/Layouts/AdminLayout.vue';
import CategoryList from './js/Pages/Admin/Category/CategoryList.vue';
import CategoryAdd from './js/Pages/Admin/Category/CategoryAdd.vue';
import ProductList from './js/Pages/Admin/Product/ProductList.vue';
import ProductAdd from './js/Pages/Admin/Product/ProductAdd.vue';
import SizeList from './js/Pages/Admin/Size/SizeList.vue';
import SizeAdd from './js/Pages/Admin/Size/SizeAdd.vue';
import ColorList from './js/Pages/Admin/Color/ColorList.vue';
import ProductDetail from './js/Pages/Admin/Product/ProductDetail.vue';
import Home from './js/Pages/Client/Home.vue';
import Category from './js/Pages/Client/Category.vue';
const routes = [
    {
        path: '/',
        component: ClientLayout,
        children: [
            { path: '', name: 'home', component: Home },
            { path: '/categories/:slug', name: 'product-of-category', component: Category
            },
        ],
    },


    {
        path: '/admin/',
        component: AdminLayout,
        children: [
            { path: '', name: 'dashboard', component: CategoryList },
            { path: 'categories', name: 'categories', component: CategoryList },
            { path: 'add-category', name: 'add-category', component: CategoryAdd },
            { path: 'products', name: 'products', component: ProductList },
            { path: 'detail-product/:id', name: 'detail-product', component: ProductDetail },
            { path: 'add-product', name: 'add-product', component: ProductAdd },
            { path: 'sizes', name: 'sizes', component: SizeList },
            { path: 'add-size', name: 'add-size', component: SizeAdd },
            { path: 'colors', name: 'colors', component: ColorList },
        ],
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

export default router;