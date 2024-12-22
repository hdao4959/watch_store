<template>
    <div>
        <header>
            <nav>
                <ul>
                    <li><router-link to="/">Home</router-link></li>
                    <li><router-link to="/categories">Categories</router-link></li>
                </ul>
            </nav>
        </header>
        <main>
            <router-view></router-view>
        </main>
    </div>
</template>

<script setup>
const name = 'ClientLayout'
</script>

<style lang="scss" scoped>

</style>