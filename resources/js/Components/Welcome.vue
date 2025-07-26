<script setup>
import { Link } from '@inertiajs/vue3'
</script>

<template>
  <div class="p-6 bg-white border-b border-gray-200 rounded-lg space-y-6">
    <!-- Bienvenida -->
    <div>
      <h2 class="text-2xl font-semibold text-gray-800">¡Bienvenido de nuevo!</h2>
      <p class="text-gray-600">Aquí puedes revisar tus actividades recientes y acceder rápidamente a las secciones importantes.</p>
    </div>

    <!-- Accesos rápidos -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
      <Link href="/products" class="block p-4 bg-blue-100 hover:bg-blue-200 rounded-xl text-center shadow">
        <span class="text-lg font-semibold text-blue-800">Ver Catálogo</span>
      </Link>

      <Link href="/orders" class="block p-4 bg-green-100 hover:bg-green-200 rounded-xl text-center shadow">
        <span class="text-lg font-semibold text-green-800">Mis Pedidos</span>
      </Link>

      <Link href="/user/profile" class="block p-4 bg-yellow-100 hover:bg-yellow-200 rounded-xl text-center shadow">
        <span class="text-lg font-semibold text-yellow-800">Mi Cuenta</span>
      </Link>
    </div>

    <!-- Sección futura: recomendaciones o mensajes -->
    <div class="mt-8">
      <h3 class="text-lg font-medium text-gray-700 mb-2">¿No sabes por dónde empezar?</h3>
      <p class="text-gray-500">Explora las nuevas ofertas en el catálogo o revisa el estado de tus pedidos recientes.</p>
    </div>
  </div>
</template>
