<template>
    <v-carousel cycle interval="3000" hide-delimiters>
        <v-carousel-item v-for="(item, i) in items" :key="i">
            <v-img :src="item.src" :alt="item.alt" class="fill-height" cover></v-img>
        </v-carousel-item>
    </v-carousel>
</template>

<script setup>
import { ref } from 'vue';

import slide1 from '../assets/slide1.jpg';
import slide2 from '../assets/slide2.jpg';
import slide3 from '../assets/slide3.jpg';

const items = ref([
  { src: slide1, alt: 'Slide 1' },
  { src: slide2, alt: 'Slide 2' },
  { src: slide3, alt: 'Slide 3' },
]);
</script>
