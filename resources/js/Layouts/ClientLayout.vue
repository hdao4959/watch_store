<template>
        <div id="snow_container"></div>
    <div id="client">
        <Menu />
        <Banner />
        <main>
            <router-view class="mt-2"></router-view>
        </main>
        <Footer />
    </div>
</template>

<script setup>
import { onMounted } from 'vue';
import Banner from '../components/Client/Banner.vue';
import Footer from '../components/Client/Footer.vue';
import Menu from '../components/Client/Menu.vue';
import { url_image } from '../config';

const name = 'ClientLayout';
const imgSnowIcon = url_image + "img_thumbnail/icon/beautiful-blue-snowflake-on-transparent-background-winter-christmas-element-realistic-snow-flake-cut-out-crystal-of-snow-macro-view-ai-generated-png.webp";

// onMounted(() => {
//     const snowContainer = document.getElementById('snow_container');

//     function createSnowFlake() {
//         const snowFlake = document.createElement('div');
//         // const snowFlake = document.createElement('img');
//         snowFlake.classList.add('snowFlake');
//         // snowFlake.src = imgSnowIcon;

//         const xPosition = Math.random() * window.innerWidth;
//         const size = Math.random() * 5 + 5;
//         const fallDuration = Math.random() * 5 + 3;

//         snowFlake.style.width = `${size}px`;
//         snowFlake.style.height = `${size}px`;
//         snowFlake.style.right = `${xPosition}px` ;
//         snowFlake.style.animationDuration = `${fallDuration}s`;

//         snowContainer.appendChild(snowFlake);
//         console.log(snowFlake);
        
//         setTimeout(() => {
//             snowFlake.remove();
//         }, fallDuration * 1000);
//     }

//     setInterval(createSnowFlake,1000); // Thêm độ trễ ngẫu nhiên cho mỗi lần tạo tuyết
// });
</script>

<style lang="scss" scoped>
* {
    margin: 0;
    padding: 0;
    font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
}

#client {
    height: 100vh;
    margin: 0;
    padding: 0;
}

// #snow_container {
//     position: fixed;
//     width: 100%;
//     height: 100%;
//     top: 0;
//     left: 0;
//     pointer-events: none;
// }

// .snowFlake {
//     position: absolute;
//     background-color: red;
//     border-radius: 50%;
//     animation: fall linear infinite; 
// }

// @keyframes fall {
//     0% {
//         transform: translateY(0);
//         opacity: 1;
//     }

//     100% {
//         transform: translateY(100vh);
//         opacity: 0.3;
//     }
// }
</style>