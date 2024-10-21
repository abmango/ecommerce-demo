<template>
  <div class="container mx-auto py-8">
    <div class="absolute top-0 right-0 mt-4 mr-4">
      <InertiaLink href="/products" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
        Ver más productos
      </InertiaLink>
    </div>

    <h1 class="text-2xl font-bold mb-6 text-center">Carrito de Compras</h1>
    
    <div v-if="cart.length > 0" class="overflow-x-auto">
      <table class="min-w-full bg-white border border-gray-300 table-auto">
        <thead>
          <tr>
            <th class="py-2 px-4 border border-gray-300 text-center bg-gray-200">Producto</th>
            <th class="py-2 px-4 border border-gray-300 text-center bg-gray-200">Cantidad</th>
            <th class="py-2 px-4 border border-gray-300 text-center bg-gray-200">Precio</th>
            <th class="py-2 px-4 border border-gray-300 text-center bg-gray-200">Subtotal</th>
            <th class="py-2 px-4 border border-gray-300 text-center bg-gray-200">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in cart" :key="item.id" class="text-center">
            <td class="py-2 px-4 border border-gray-300">{{ item.name }}</td>
            <td class="py-2 px-4 border border-gray-300">{{ item.quantity }}</td>
            <td class="py-2 px-4 border border-gray-300">${{ item.price }}</td>
            <td class="py-2 px-4 border border-gray-300">${{ (item.price * item.quantity).toFixed(2) }}</td>
            <td class="py-2 px-4 border border-gray-300">
              <button @click="removeFromCart(item.id)" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                Eliminar
              </button>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Total -->
      <div class="mt-4 text-right">
        <p class="text-xl font-bold">Total: ${{ total.toFixed(2) }}</p>
      </div>

      <!-- Botón para procesar la compra -->
      <div class="mt-4 text-right">
        <button @click="processPurchase" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
          Comprar
        </button>
      </div>
    </div>
    <div v-else>
      <p class="text-center text-gray-500">El carrito está vacío.</p>
    </div>
  </div>
</template>

<script>
import { Link as InertiaLink } from '@inertiajs/vue3';

export default {
  props: {
    cart: Array,
  },
  computed: {
    total() {
      return this.cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
    }
  },
  components: { InertiaLink },
  methods: {
    removeFromCart(id) {
      this.$inertia.delete(`/cart/remove/${id}`);
    },
    processPurchase() {
      if (confirm('¿Estás seguro de que quieres procesar esta compra?')) {
        this.$inertia.post('/cart/checkout');
      }
    }
  }
}
</script>

<style scoped>
/* Estilo para las tablas */
table {
  border-collapse: collapse;
  width: 100%;
}

/* Estilo para las celdas de la tabla */
th, td {
  text-align: center;
  padding: 8px;
  border: 1px solid #ddd;
}

/* Estilo para los encabezados de la tabla */
thead th {
  background-color: #f2f2f2;
  font-weight: bold;
}

/* Fondo diferente para filas pares */
tbody tr:nth-child(even) {
  background-color: #f9f9f9;
}

/* Estilo para el hover de las filas */
tbody tr:hover {
  background-color: #f1f1f1;
}

/* Estilo para los botones */
button {
  transition: background-color 0.3s ease;
}
</style>
