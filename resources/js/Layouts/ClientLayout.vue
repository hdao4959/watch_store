<template>
    <div id="client">
        <Menu/>
        <main>
            <router-view class="mt-2"></router-view>
        </main>
    </div>
</template>

<script setup>
import Menu from '../components/Client/Menu.vue';

const name = 'ClientLayout'
</script>

<style lang="scss" scoped>
* {
    margin: 0;
    padding: 0;
    font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
    // background-color: rgb(255, 255, 255);
}
#client {
    
    min-height: 100vh;
}

</style>