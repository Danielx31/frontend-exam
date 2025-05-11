<template>
    <v-app-bar app color="white" flat>

        <v-app-bar-nav-icon @click="drawer = !drawer" class="d-flex d-md-none" />

        <v-img src="/logo.png" alt="Logo" max-height="200" max-width="120" class="mr-4" contain />

        <v-row class="d-none d-md-flex" align="center" no-gutters>
            <v-btn text to="/" class="mx-1">Home</v-btn>
            <v-btn text to="/products" class="mx-1">Products</v-btn>
        </v-row>

        <v-spacer />

        <v-btn icon :to="{ path: '/cart' }">
            <v-icon>mdi-cart</v-icon>
            <span v-if="cart.length > 0" class="cart-count">{{ cart.length }}</span>
        </v-btn>

    </v-app-bar>

    <!-- Mobile Drawer -->
    <v-navigation-drawer v-model="drawer" temporary>
        <v-list nav>
            <v-list-item link to="/">
                <v-list-item-title>Home</v-list-item-title>
            </v-list-item>
            <v-list-item link to="/products">
                <v-list-item-title>Products</v-list-item-title>
            </v-list-item>
        </v-list>
    </v-navigation-drawer>
</template>

<script setup>
import { ref } from 'vue';
import { cart } from '../store/cartState.js';
const drawer = ref(false);
</script>

<style scoped>
.cart-count {
    background-color: red;
    color: white;
    border-radius: 50%;
    padding: 2px 6px;
    font-size: 12px;
    position: absolute;
    top: 5px;
    right: 5px;
}
</style>
