<template>
    <v-container fluid class="py-12 px-4">
      <v-row align="center" justify="center">
        <v-col cols="12" md="10">
          <v-row align="center">
            <!-- Main Heading -->
            <v-col cols="12" md="12" class="text-md-start text-center">
              <h1 class="text-h1 text-md-h1 font-weight-bold text-uppercase mb-4">
                Craft Your Unique Look
              </h1>
            </v-col>

            <!-- Left Paragraph Text -->
            <v-col cols="12" md="4">
              <h5 class="text-body-2 font-weight-regular">
                Show the world your true style. Our collection lets you design a wardrobe that reflects who you are — bold, timeless, and unmistakably you.
              </h5>
            </v-col>

            <!-- Right Paragraph Text -->
            <v-col cols="12" md="8" class="text-md-start text-center">
              <h1 class="text-h2 text-md-h2 font-weight-bold text-uppercase mb-4">
                With Our Collection
              </h1>
            </v-col>
          </v-row>
        </v-col>

        <v-col cols="12" md="10">
          <v-row>
            <!-- Carousel Component -->
            <v-col cols="12" md="12">
              <carousel></carousel>
            </v-col>

            <!-- Explore Button -->
            <v-col cols="12" class="text-center">
                <v-btn
                    color="black"
                    size="large"
                    class="text-uppercase font-weight-bold"
                    :to="{ path: '/products' }"
                >
                    Explore our products
                </v-btn>
            </v-col>
          </v-row>
        </v-col>
      </v-row>
    </v-container>
  </template>

  <script setup>
  import Carousel from '../components/Carousel.vue';
  </script>
