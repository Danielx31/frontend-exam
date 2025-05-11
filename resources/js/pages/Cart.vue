<template>
    <v-container fluid class="py-8 px-4">
      <v-row justify="center" align="start">
        <!-- Left Column: Cart Items -->
        <v-col cols="12" md="8">
          <h1 class="text-h4 font-weight-bold text-uppercase mb-4">Your Cart</h1>

          <!-- If Cart Is Empty -->
          <div v-if="cart.length === 0" class="text-center grey--text">
            <v-icon size="64" color="grey">mdi-cart-off</v-icon>
            <p class="text-body-1 mt-2">Your cart is empty. Please add items to your cart.</p>
          </div>

          <!-- Cart Items -->
          <v-card v-for="item in cart" :key="item.id" class="mb-4 pa-4">
            <v-row no-gutters>
              <!-- Product Image -->
              <v-col cols="12" sm="4">
                <v-img
                  :src="`/image-proxy?url=${encodeURIComponent(item.images[0])}`"
                  :alt="item.title"
                  height="120"
                  class="rounded"
                  contain
                />
              </v-col>

              <!-- Product Info -->
              <v-col cols="12" sm="8">
                <div class="d-flex flex-column justify-space-between h-100">
                  <div>
                    <div class="text-h6 font-weight-medium">{{ item.title }}</div>
                    <div class="text-subtitle-1 grey--text">Price: ${{ item.price }}</div>
                  </div>

                  <!-- Quantity & Remove Controls -->
                  <v-row align="center" justify="space-between" class="mt-3">
                    <v-col cols="6" class="d-flex align-center">
                      <v-btn icon @click="updateQuantity(item, -1)" :disabled="item.quantity <= 1">
                        <v-icon>mdi-minus</v-icon>
                      </v-btn>
                      <span class="mx-3 text-subtitle-1 font-weight-bold">{{ item.quantity }}</span>
                      <v-btn icon @click="updateQuantity(item, 1)">
                        <v-icon>mdi-plus</v-icon>
                      </v-btn>
                    </v-col>

                    <v-col cols="6" class="d-flex justify-end">
                      <v-btn color="red" @click="removeFromCart(item)" text>
                        <v-icon left>mdi-delete</v-icon> Remove
                      </v-btn>
                    </v-col>
                  </v-row>
                </div>
              </v-col>
            </v-row>
          </v-card>
        </v-col>

        <!-- Right Column: Order Summary (Always Visible) -->
        <v-col cols="12" md="4" class="mt-md-0 mt-6">
          <v-card class="pa-4 elevation-2 rounded-xl">
            <h3 class="text-h6 font-weight-bold mb-4">Order Summary</h3>
            <v-divider class="mb-4"></v-divider>

            <div class="d-flex justify-space-between mb-2">
              <span>Subtotal:</span>
              <span>${{ subtotal.toFixed(2) }}</span>
            </div>

            <div class="d-flex justify-space-between mb-2">
              <span>Shipping Fee:</span>
              <span>${{ shippingFee.toFixed(2) }}</span>
            </div>

            <v-divider class="mb-4"></v-divider>

            <div class="d-flex justify-space-between text-h6 font-weight-bold">
              <span>Estimated Total:</span>
              <span>${{ (subtotal + shippingFee).toFixed(2) }}</span>
            </div>

            <v-btn
              :loading="isCheckingOut"
              color="primary"
              size="large"
              class="mt-4 w-100"
              @click="checkout"
              :disabled="cart.length === 0"
            >
              <v-icon left>mdi-cart-check</v-icon>
              Checkout
            </v-btn>
          </v-card>

          <!-- Checkout Confirmation -->
          <v-alert
            v-if="checkoutMessage"
            type="success"
            :value="true"
            class="mt-4"
            dismissible
          >
            {{ checkoutMessage }}
          </v-alert>
        </v-col>
      </v-row>
    </v-container>
  </template>


<script setup>
import { ref, computed } from 'vue';
import { cart, removeFromCart } from '../store/cartState.js';

const checkoutMessage = ref(null);
const isCheckingOut = ref(false);

const updateQuantity = (item, change) => {
    const newQuantity = item.quantity + change;

    if (newQuantity >= 1) {
        item.quantity = newQuantity;
    }
};

const checkout = () => {
    if (cart.value.length > 0) {
        isCheckingOut.value = true;

        setTimeout(() => {
            cart.value = [];
            checkoutMessage.value = 'Your order has been processed!';
            isCheckingOut.value = false;

            setTimeout(() => {
                checkoutMessage.value = null;
            }, 5000);
        }, 2000);
    } else {
        checkoutMessage.value = 'Your cart is empty. Please add items before checking out.';
    }
};

const shippingFee = 0.0;

const subtotal = computed(() =>
    cart.value.reduce((sum, item) => sum + item.price * item.quantity, 0)
);
</script>

<style scoped>
.text-center {
    text-align: center;
}
</style>
