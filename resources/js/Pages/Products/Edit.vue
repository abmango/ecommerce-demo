<template>
  <div class="container mx-auto py-8">
    <h2 class="text-2xl font-bold mb-6">Editar Producto</h2>
    <form @submit.prevent="updateProduct" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
      <div class="mb-4">
        <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Nombre del Producto</label>
        <input v-model="form.name" id="name" type="text" required
          class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
      </div>

      <div class="mb-4">
        <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Descripción</label>
        <textarea v-model="form.description" id="description" rows="4"
          class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
      </div>

      <div class="mb-4">
        <label for="price" class="block text-gray-700 text-sm font-bold mb-2">Precio</label>
        <input v-model="form.price" id="price" type="number" required
          class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
      </div>

      <div class="mb-4">
        <label for="image" class="block text-gray-700 text-sm font-bold mb-2">URL de la Imagen</label>
        <input v-model="form.image" id="image" type="text" required
          class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
      </div>

      <div class="mb-4">
        <label for="stock" class="block text-gray-700 text-sm font-bold mb-2">Stock</label>
        <input v-model="form.stock" id="stock" type="number" required
          class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
      </div>

      <div class="mb-4">
        <label for="type" class="block text-gray-700 text-sm font-bold mb-2">Tipo de Producto</label>
        <input v-model="form.type" id="type" type="text" required
          class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
      </div>

      <div class="flex items-center justify-between">
        <button type="submit"
          class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Actualizar
          Producto</button>
      </div>
    </form>
  </div>
</template>

<script>
import axios from 'axios';
import { Inertia } from '@inertiajs/inertia';

export default {
  props: {
    product: {
      type: Object,
      required: true,  // Se asegura de que el producto sea pasado correctamente
    },
    id: {
      type: Number,
      required: true,  // Se asegura de que el id sea pasado correctamente
    },
  },
  data() {
    return {
      form: {
        name: this.product.name || '',
        description: this.product.description || '',
        price: this.product.price || '',
        image: this.product.image || '',
        stock: this.product.stock || '',
        type: this.product.type || '',
      },
    };
  },
  methods: {
    updateProduct() {
      axios.put(`/products/${this.id}`, this.form)
        .then(response => {
          alert('Producto actualizado correctamente');
          Inertia.visit(`/products/${this.id}`);
        })
        .catch(error => {
          console.error(error);
        });
    },
  },
};
</script>