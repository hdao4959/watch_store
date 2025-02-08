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
import ProductDetailClient from './js/Pages/Client/ProductDetail.vue';
import Register from './js/Pages/Auth/Register.vue';
import Account from './js/Pages/Client/Account.vue';
import Login from './js/Pages/Auth/Login.vue';
import Cart from './js/Pages/Client/Cart.vue';
import Checkout from './js/Pages/Client/Checkout.vue';
import OrderList from './js/Pages/Admin/Order/OrderList.vue';
import ShowBill from './js/Pages/Client/ShowBill.vue';
import OrderDetail from './js/Pages/Admin/Order/OrderDetail.vue';
import OrderDt from './js/Pages/Client/OrderDt.vue';
import Search from './js/Pages/Client/Search.vue';
import ColorAdd from './js/Pages/Admin/Color/ColorAdd.vue';
import ProductEdit from './js/Pages/Admin/Product/ProductEdit.vue';
const routes = [
    {
        path: '/',
        component: ClientLayout,
        children: [
            { path: '', name: 'home', component: Home },
            { path: '/categories/:slug', name: 'product-of-category', component: Category },
            { path: '/products/:slug', name: 'product-detail', component: ProductDetailClient },
            { path: '/register', name: 'register', component: Register },
            { path: '/account', name: 'account', component: Account },
            { path: '/login', name: 'login', component: Login },
            { path: '/cart', name: 'cart', component: Cart },
            { path: '/checkout', name: 'checkout', component: Checkout },
            { path: '/bill', name: 'bill', component: ShowBill },
            { path: 'order-dt/:id', name: 'client-order-detail', component: OrderDt },
            { path: '/search', name: 'search', component: Search }
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
            { path: 'edit-product/:id', name: 'edit-product', component: ProductEdit },
            { path: 'sizes', name: 'sizes', component: SizeList },
            { path: 'add-size', name: 'add-size', component: SizeAdd },
            { path: 'colors', name: 'colors', component: ColorList },
            { path: 'add-color', name: 'add-color', component: ColorAdd },
            { path: 'orders', name: 'orders', component: OrderList },
            { path: 'order-detail/:id', name: 'order-detail', component: OrderDetail },
        ],
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

export default router;