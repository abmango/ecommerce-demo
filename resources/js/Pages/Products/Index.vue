<template>
  <GuestHeader />

  <Head title="Catalogo de productos"></Head>
  <div class="container mx-auto p-5 mt-20">

    <div class="catalogo-header flex justify-between items-center border-b border-b-slate-300 mb-3">
      <h1 class="text-2xl font-semibold">Lista de Productos</h1>
    </div>
    
    <ProductLogged v-if="isLogged && isAdmin" :products="products" />
    <ProductGuest v-else :products="products" />

  </div>
</template>

<script setup>
import GuestHeader from '@/Components/GuestHeader.vue';
import ProductLogged from '@/Components/Product/Logged.vue';
import ProductGuest from '@/Components/Product/Guest.vue';


const props = defineProps({
  products: Array,
  isLogged: Boolean,
  isAdmin: Boolean
})


</script>
