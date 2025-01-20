<template>

    <router-link class="nav-link prd_card col my-2" :to="{ name: 'product-detail', params:{ slug: prd_slug } }">
            <div class="icon_love"><i class="fa-regular fa-heart"></i></div>
            <div class="my-4">
                <img class="img-fluid" :src="`${url_image}${prd.img_thumbnail}`"
                    :alt="`${url_image}${prd.img_thumbnail}`">
            </div>
            <div class="bottom_card">
                <span>Msp: {{ prd.id }}</span><br>
                <span>{{ prd.name }}</span>
            </div>
    </router-link>
</template>

<script>

export default {
    props: {
        prd: {
            type: Object,
            required: true
        },
        url_image: {
            type: String,
            required: true
        },
        prd_slug: {
            type: String,
            required: true
        }

    }
}
</script>


<style lang="scss" scoped>
.prd_card {
    box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;
    flex: 1 0 calc(25% - 20px);
    // margin: 5px;
    padding-top: 10px;
    position: relative;
    min-height: 400px;
    background-color: rgb(227, 227, 227);

    // border: 1px solid rgb(97, 95, 95);
    &:hover {
        .bottom_card {
            // background-color: orange;
            color: orange;
        }

        ;

        img {
            transform: scale(1.1)
        }

        .icon_love {
            display: block
        }
    }
}

img {

    transition: transform 0.5s ease;
}

.bottom_card {
    color: rgb(57, 57, 57);
    margin: 10px 10px 10px 10px;
    font-size: 15px;
}

.icon_love {
    margin-top: 10px;
    margin-right: 5px;
    width: 40px;
    height: 40px;
    background-color: rgb(255, 255, 255);
    text-align: center;
    border-radius: 50%;
    align-content: center;
    position: absolute;
    z-index: 10;
    top: 0;
    right: 0;

    &:hover {
        background-color: pink;
        color: white;
    }
}
</style>