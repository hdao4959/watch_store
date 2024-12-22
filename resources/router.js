import { createRouter, createWebHistory } from 'vue-router';  
import ClientLayout from './js/Layouts/ClientLayout.vue';  
import AdminLayout from './js/Layouts/AdminLayout.vue';  
import CategoryList from './js/Pages/Admin/Category/CategoryList.vue';  
import CategoryAdd from './js/Pages/Admin/Category/CategoryAdd.vue';
import ProductList from './js/Pages/Admin/Product/ProductList.vue';
import ProductAdd from './js/Pages/Admin/Product/ProductAdd.vue';
const routes = [  
    // {  
    //     path: '/',  
    //     component: ClientLayout,  
    // },  
    {  
        path: '/',  
        // component: AdminLayout,  
        children: [ 
            { path: '', name: 'dashboard', component: CategoryList },
            { path: 'categories', name: 'categories', component: CategoryList },
            { path: 'add-category', name: 'add-category', component: CategoryAdd},
            { path: 'products', name: 'products', component: ProductList},
            { path: 'add-product', name: 'add-product', component: ProductAdd}
        ]  
    }  
];  

const router = createRouter({  
    history: createWebHistory(),  
    routes  
});  

export default router;