<template>
  <GuestHeader />
  <div class="container mx-auto p-8 mt-20">
    <h1 class="text-2xl font-bold mb-6">Crear Nuevo Producto</h1>

    <!-- Formulario de creación de productos -->
    <form @submit.prevent="submitForm" class="bg-white p-6 rounded-lg shadow-md max-w-lg mx-auto">

      <!-- Campo Nombre del Producto -->
      <div class="mb-4">
        <label for="name" class="block text-gray-700">Nombre del Producto</label>
        <input v-model="form.name" type="text" id="name" class="w-full p-2 border border-gray-300 rounded mt-1"
          placeholder="Nombre del producto">
      </div>

      <!-- Campo Precio del Producto -->
      <div class="mb-4">
        <label for="price" class="block text-gray-700">Precio</label>
        <input v-model="form.price" type="number" step="0.01" id="price"
          class="w-full p-2 border border-gray-300 rounded mt-1" placeholder="Precio del producto">
      </div>

      <!-- Campo Descripción del Producto -->
      <div class="mb-4">
        <label for="description" class="block text-gray-700">Descripción</label>
        <textarea v-model="form.description" id="description" class="w-full p-2 border border-gray-300 rounded mt-1"
          placeholder="Descripción del producto"></textarea>
      </div>

      <!-- Campo Imagen (URL) -->
      <div class="mb-4">
        <label for="image" class="block text-gray-700">URL de la Imagen</label>
        <input v-model="form.image" type="text" id="image" class="w-full p-2 border border-gray-300 rounded mt-1"
          placeholder="URL de la imagen del producto">
      </div>

      <!-- Campo Stock -->
      <div class="mb-4">
        <label for="stock" class="block text-gray-700">Stock</label>
        <input v-model="form.stock" type="number" id="stock" class="w-full p-2 border border-gray-300 rounded mt-1"
          placeholder="Cantidad en stock">
      </div>

      <!-- Campo Tipo de Producto -->
      <div class="mb-4">
        <label for="type" class="block text-gray-700">Tipo de Producto</label>
        <input v-model="form.type" type="text" id="type" class="w-full p-2 border border-gray-300 rounded mt-1"
          placeholder="Tipo de producto (ej: electrónico, ropa)">
      </div>

      <!-- Botón de Enviar -->
      <div class="flex justify-between mt-4">
        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
          Crear Producto
        </button>

        <Link href="/products" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded ml-20">
          Volver
        </Link>
      </div>
    </form>
  </div>
</template>

<script setup>
import { reactive } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import GuestHeader from '@/Components/GuestHeader.vue'
import { Link } from '@inertiajs/inertia-vue3';

const form = reactive({
  name: '',
  description: '',
  price: '',
  image: '',
  stock: '',
  type: ''
})

const submitForm = () => {
  Inertia.post('/products', form)
}
</script>
