<template>
  <div class="container mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6">Crear Nuevo Producto</h1>
    
    <!-- Formulario de creación de productos -->
    <form @submit.prevent="submitForm" class="bg-white p-6 rounded-lg shadow-md max-w-lg mx-auto">
      
      <!-- Campo Nombre del Producto -->
      <div class="mb-4">
        <label for="name" class="block text-gray-700">Nombre del Producto</label>
        <input 
          v-model="form.name" 
          type="text" 
          id="name" 
          class="w-full p-2 border border-gray-300 rounded mt-1"
          placeholder="Nombre del producto">
      </div>

      <!-- Campo Precio del Producto -->
      <div class="mb-4">
        <label for="price" class="block text-gray-700">Precio</label>
        <input 
          v-model="form.price" 
          type="number" 
          step="0.01" 
          id="price" 
          class="w-full p-2 border border-gray-300 rounded mt-1"
          placeholder="Precio del producto">
      </div>

      <!-- Campo Descripción del Producto -->
      <div class="mb-4">
        <label for="description" class="block text-gray-700">Descripción</label>
        <textarea 
          v-model="form.description" 
          id="description" 
          class="w-full p-2 border border-gray-300 rounded mt-1"
          placeholder="Descripción del producto"></textarea>
      </div>

      <!-- Campo Imagen (URL) -->
      <div class="mb-4">
        <label for="image" class="block text-gray-700">URL de la Imagen</label>
        <input 
          v-model="form.image" 
          type="text" 
          id="image" 
          class="w-full p-2 border border-gray-300 rounded mt-1"
          placeholder="URL de la imagen del producto">
      </div>

      <!-- Campo Stock -->
      <div class="mb-4">
        <label for="stock" class="block text-gray-700">Stock</label>
        <input 
          v-model="form.stock" 
          type="number" 
          id="stock" 
          class="w-full p-2 border border-gray-300 rounded mt-1"
          placeholder="Cantidad en stock">
      </div>

      <!-- Campo Tipo de Producto -->
      <div class="mb-4">
        <label for="type" class="block text-gray-700">Tipo de Producto</label>
        <input 
          v-model="form.type" 
          type="text" 
          id="type" 
          class="w-full p-2 border border-gray-300 rounded mt-1"
          placeholder="Tipo de producto (ej: electrónico, ropa)">
      </div>

      <!-- Botón de Enviar -->
      <button 
        type="submit" 
        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
        Crear Producto
      </button>
    </form>
  </div>
</template>

<script>
export default {
  data() {
    return {
      form: {
        name: '',
        description: '',
        price: '',
        image: '',
        stock: '',
        type: ''
      }
    };
  },
  methods: {
    submitForm() {
      // Envío de los datos del formulario al backend
      this.$inertia.post('/products', this.form);
    }
  }
};
</script>
