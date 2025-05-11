import { ref } from 'vue';

export const cart = ref([]);

export const addToCart = (product) => {
    cart.value.push(product);
};

export const removeFromCart = (item) => {
    const index = cart.value.findIndex((cartItem) => cartItem.id === item.id);
    if (index !== -1) {
        cart.value.splice(index, 1);
    }
};
