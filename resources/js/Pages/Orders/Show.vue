<template>
    <div class="container mx-auto py-8">
      <h1 class="text-2xl font-bold mb-6">Detalles de la Orden #{{ order.id }}</h1>
  
      <p class="mb-4"><strong>Total:</strong> ${{ order.total }}</p>
      <p class="mb-4"><strong>Estado:</strong> {{ order.status }}</p>
  
      <h3 class="text-xl font-bold mb-4">Productos</h3>
      <div v-if="order.products.length > 0">
        <ul>
          <li v-for="product in order.products" :key="product.id">
            {{ product.name }} - Cantidad: {{ product.pivot.quantity }} - Precio: ${{ product.pivot.price }}
          </li>
        </ul>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    props: {
      order: Object
    }
  }
  </script>
  