<template>
    <v-container fluid class="py-8 px-4 bg-grey-lighten-5">
        <v-row align="center" justify="center">
            <!-- Title and Description -->
            <v-col cols="12" md="10" class="text-center mb-6">
                <h1 class="text-h3 font-weight-bold text-uppercase mb-2">Shop Products</h1>
                <p class="text-body-1">Discover trendy and high-quality products for your lifestyle.</p>
            </v-col>

            <!-- Category Chips -->
            <v-col cols="12" class="text-center mb-4">
                <v-chip-group v-model="selectedCategory" mandatory column>
                    <v-chip
                        v-for="category in categories"
                        :key="category.id"
                        :value="category.id"
                        class="ma-1"
                        color="primary"
                        variant="outlined"
                        @click="selectCategory(category.id)"
                        outlined
                    >
                        {{ category.name }}
                    </v-chip>
                </v-chip-group>
            </v-col>

            <!-- Price Filter -->
            <v-col cols="12" md="6" class="text-center mb-4">
                <v-select
                    v-model="priceOrder"
                    :items="['Low to High', 'High to Low']"
                    label="Sort by Price"
                    outlined
                    dense
                    clearable
                    @update:model-value="applyPriceFilter"
                />
            </v-col>

            <!-- Search Box -->
            <v-col cols="12" md="6" class="text-center mb-4">
                <v-text-field
                    v-model="searchQuery"
                    label="Search Products"
                    outlined
                    dense
                    clearable
                    @input="fetchProducts"
                />
            </v-col>

            <!-- Create Product Button -->
            <v-col cols="12" class="text-center mb-4">
                <v-btn color="primary" @click="showCreateDialog = true">
                    + Create New Product
                </v-btn>
            </v-col>

            <!-- Product Grid -->
            <v-col v-for="product in products" :key="product.id" cols="12" sm="6" md="4" lg="3">
                <v-card class="mx-auto hoverable" max-width="300" elevation="2">
                    <v-img
                        :src="`/image-proxy?url=${encodeURIComponent(product.images[0])}`"
                        height="200"
                        cover
                    >
                        <template #error>
                            <v-img src="https://via.placeholder.com/300x200?text=No+Image" cover />
                        </template>
                    </v-img>

                    <v-card-text>
                        <div class="font-weight-bold text-h6 mb-1">{{ product.title }}</div>
                        <div class="text-subtitle-1 text-success font-weight-bold">${{ product.price }}</div>
                        <div class="text-body-2 text-truncate mb-2">{{ product.description }}</div>
                    </v-card-text>

                    <v-card-actions class="justify-space-between">
                        <v-btn color="primary" size="small" @click="showDetails(product)">View Details</v-btn>
                        <v-btn color="green" size="small" @click="addToCart(product)">Add to Cart</v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>

            <!-- Pagination -->
            <v-col cols="12" class="text-center mt-6" v-if="totalPages > 1">
                <v-pagination
                    v-model="page"
                    :length="totalPages"
                    total-visible="5"
                    @update:model-value="fetchProducts"
                />
            </v-col>

            <!-- Error Alert -->
            <v-col cols="12" class="text-center" v-if="error">
                <v-alert type="error" variant="outlined">
                    Failed to load products. Please try again later.
                </v-alert>
            </v-col>
        </v-row>

        <!-- Product Details Dialog -->
        <v-dialog v-model="dialog" max-width="500" persistent>
            <v-card>
                <v-card-title class="text-h6">{{ selectedProduct?.title }}</v-card-title>
                <v-card-text>
                    <v-img
                        :src="`/image-proxy?url=${encodeURIComponent(selectedProduct?.images[0] || '')}`"
                        height="400"
                        cover
                        class="mb-4"
                    />

                    <template v-if="!editMode">
                        <p><strong>Price:</strong> ${{ selectedProduct?.price }}</p>
                        <p><strong>Description:</strong> {{ selectedProduct?.description }}</p>
                    </template>

                    <template v-else>
                        <v-text-field v-model="selectedProduct.title" label="Title" dense outlined />
                        <v-text-field
                            v-model.number="selectedProduct.price"
                            label="Price"
                            type="number"
                            dense
                            outlined
                        />
                        <v-textarea v-model="selectedProduct.description" label="Description" outlined dense />
                        <v-text-field v-model="selectedProduct.images[0]" label="Image URL" dense outlined />
                    </template>
                </v-card-text>

                <v-card-actions>
                    <v-spacer />
                    <v-btn v-if="!editMode" color="blue" @click="editMode = true">Update</v-btn>
                    <v-btn v-else color="green" @click="updateProduct">Save</v-btn>
                    <v-btn v-if="editMode" text @click="cancelEdit">Cancel</v-btn>
                    <v-btn v-if="!editMode" color="red" @click="deleteProduct">Delete</v-btn>
                    <v-btn text @click="dialog = false">Close</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <!-- Create Product Dialog -->
        <v-dialog v-model="showCreateDialog" max-width="500">
            <v-card>
                <v-card-title class="text-h6">Create New Product</v-card-title>
                <v-card-text>
                    <v-text-field v-model="newProduct.title" label="Title" outlined dense />
                    <v-text-field
                        v-model.number="newProduct.price"
                        label="Price"
                        outlined
                        dense
                        type="number"
                    />
                    <v-textarea v-model="newProduct.description" label="Description" outlined dense />
                    <v-select
                        v-model="newProduct.categoryId"
                        :items="categories.filter((c) => c.id)"
                        item-title="name"
                        item-value="id"
                        label="Category"
                        outlined
                        dense
                    />
                    <v-text-field v-model="newProduct.images[0]" label="Image URL" outlined dense />
                </v-card-text>
                <v-card-actions>
                    <v-spacer />
                    <v-btn text @click="showCreateDialog = false">Cancel</v-btn>
                    <v-btn color="green" @click="createProduct">Save</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <!-- Snackbar -->
        <v-snackbar v-model="snackbar" :color="snackbarColor" timeout="3000" top>
            {{ snackbarMessage }}
        </v-snackbar>
    </v-container>
</template>
<script setup>
import { ref, onMounted, watch } from 'vue';
import { cart } from '../store/cartState.js';

const products = ref([]);
const categories = ref([{ id: null, name: 'All Categories' }]);
const selectedCategory = ref(null);
const page = ref(1);
const perPage = 12;
const totalPages = ref(1);
const loading = ref(false);
const error = ref(false);
const dialog = ref(false);
const selectedProduct = ref(null);
const priceOrder = ref(null);
const searchQuery = ref('');
const showCreateDialog = ref(false);
const editMode = ref(false);
const snackbar = ref(false);
const snackbarMessage = ref('');
const snackbarColor = ref('success');

const showSnackbar = (message, color = 'success') => {
    snackbarMessage.value = message;
    snackbarColor.value = color;
    snackbar.value = true;
};

// Fetch categories
const fetchCategories = async () => {
    try {
        const response = await fetch('https://api.escuelajs.co/api/v1/categories');
        const data = await response.json();
        categories.value.push(...data);
    } catch (err) {
        console.error('Error fetching categories:', err);
        error.value = true;
    }
};

const fetchProducts = async () => {
    loading.value = true;
    error.value = false;

    const offset = (page.value - 1) * perPage;
    const categoryFilter = selectedCategory.value ? `&categoryId=${selectedCategory.value}` : '';

    try {
        const response = await fetch(`https://api.escuelajs.co/api/v1/products?offset=0&limit=200${categoryFilter}`);
        let data = await response.json();

        if (searchQuery.value.trim() !== '') {
            const query = searchQuery.value.toLowerCase();
            data = data.filter(product =>
                product.title.toLowerCase().includes(query) ||
                product.description.toLowerCase().includes(query)
            );
        }

        if (priceOrder.value === 'Low to High') {
            data.sort((a, b) => a.price - b.price);
        } else if (priceOrder.value === 'High to Low') {
            data.sort((a, b) => b.price - a.price);
        }

        if (searchQuery.value.trim() === '') {
            totalPages.value = Math.ceil(data.length / perPage);
            products.value = data.slice(offset, offset + perPage);
        } else {
            totalPages.value = 1;
            products.value = data;
        }
    } catch (err) {
        console.error('Error fetching products:', err);
        error.value = true;
    } finally {
        loading.value = false;
    }
};

const newProduct = ref({
    title: '',
    price: null,
    description: '',
    categoryId: null,
    images: [''],
});

const createProduct = async () => {
    if (
        !newProduct.value.title ||
        !newProduct.value.price ||
        !newProduct.value.description ||
        !newProduct.value.categoryId ||
        !newProduct.value.images[0]
    ) {
        showSnackbar('Please fill in all fields.', 'error');
        return;
    }

    const payload = {
        title: newProduct.value.title,
        price: Number(newProduct.value.price),
        description: newProduct.value.description,
        categoryId: newProduct.value.categoryId,
        images: [newProduct.value.images[0]],
    };

    try {
        const response = await fetch('https://api.escuelajs.co/api/v1/products', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload),
        });

        if (!response.ok) {
            const errorData = await response.json();
            console.error('Server Response:', errorData);
            throw new Error('Failed to create product');
        }

        const created = await response.json();
        showSnackbar(`Product "${created.title}" created successfully!`);

        showCreateDialog.value = false;
        fetchProducts();

        // Reset form
        newProduct.value = {
            title: '',
            price: null,
            description: '',
            categoryId: null,
            images: [''],
        };
    } catch (err) {
        showSnackbar('Failed to create product.', 'error');
    }
};

const updateProduct = async () => {
    try {
        // Validate that all image URLs are valid or use the proxy route
        const invalidImages = selectedProduct.value.images.filter(
            (image) =>
                !image ||
                (!image.match(/^https?:\/\/.+\..+/) && !image.startsWith('/image-proxy?url='))
        );

        if (invalidImages.length > 0) {
            showSnackbar('All images must be valid URLs.', 'error');
            return;
        }

        const response = await fetch(`https://api.escuelajs.co/api/v1/products/${selectedProduct.value.id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                title: selectedProduct.value.title,
                price: selectedProduct.value.price,
                description: selectedProduct.value.description,
                categoryId: selectedProduct.value.category?.id || selectedProduct.value.categoryId,
                images: selectedProduct.value.images,
            }),
        });

        if (!response.ok) throw new Error('Update failed');
        const data = await response.json();
        showSnackbar(`Updated "${data.title}"`);
        editMode.value = false;
        fetchProducts();
    } catch (err) {
        console.error('Update error:', err);
        showSnackbar('Failed to update product.', 'error');
    }
};

const deleteProduct = async () => {
    if (!selectedProduct.value?.id) {
        showSnackbar('No product selected for deletion.', 'error');
        return;
    }

    const confirmDelete = confirm(`Are you sure you want to delete "${selectedProduct.value.title}"?`);
    if (!confirmDelete) return;

    try {
        const response = await fetch(`https://api.escuelajs.co/api/v1/products/${selectedProduct.value.id}`, {
            method: 'DELETE',
        });

        if (!response.ok) throw new Error('Failed to delete product');

        showSnackbar(`Product "${selectedProduct.value.title}" deleted successfully!`);
        dialog.value = false;
        fetchProducts();
    } catch (err) {
        console.error('Error deleting product:', err);
        showSnackbar('Failed to delete product.', 'error');
    }
};

const addToCart = (product) => {
    const existingProduct = cart.value.find((item) => item.id === product.id);

    if (existingProduct) {
        existingProduct.quantity += 1;
    } else {
        cart.value.push({ ...product, quantity: 1 });
    }

    showSnackbar(`Added "${product.title}" to cart.`);
};

const selectCategory = (categoryId) => {
    selectedCategory.value = categoryId;
    page.value = 1;
    fetchProducts();
};

const showDetails = (product) => {
    selectedProduct.value = product;
    dialog.value = true;
};

const applyPriceFilter = () => {
    page.value = 1;
    fetchProducts();
};

const cancelEdit = () => {
    editMode.value = false;
    fetchProducts();
};


watch(page, () => {
    fetchProducts();
});

onMounted(() => {
    fetchCategories();
    fetchProducts();
});
</script>
