<template>
  <GuestHeader />
  <div class="container mx-auto p-8 mt-20">
    <!-- Formulario de creación de productos -->
    <form @submit.prevent="submitCreation" :disabled="form.processing"
      class="bg-white p-6 rounded-lg shadow-md max-w-lg mx-auto">
      <h1 class="text-2xl font-semibold mb-4 pb-1 border-b block">Crear nuevo producto</h1>
      <div class="mb-2">
        <InputLabel for="name" value="Nombre del producto" />
        <TextInput @keyup="checkIsRequired('name')" id="name" v-model="form.name" type="text" class="mt-1 block w-full"
          autocomplete="name" placeholder="Nombre del producto" />
        <InputError v-if="form.errors.name" class="mt-2" :message="form.errors.name" />
      </div>
      <div class="mb-2">
        <InputLabel for="price" value="Precio" />
        <TextInput @keyup="checkIsRequired('price')" id="price" type="number" v-model="form.price"
          class="mt-1 block w-full" autocomplete="price" placeholder="0.00" />
        <InputError v-if="form.errors.price" class="mt-2" :message="form.errors.price" />
      </div>
      <div class="mb-2">
        <InputLabel for="image" value="Link de la imagen de producto" />
        <TextInput id="image" v-model="form.image" type="text" class="mt-1 block w-full" autocomplete="image"
          placeholder="Link de la imagen de producto" />
        <InputError v-if="form.errors.image" class="mt-2" :message="form.errors.image" />
      </div>
      <div class="mb-2">
        <InputLabel for="stock" value="Stock" />
        <TextInput @keyup="checkIsRequired('stock')" id="stock" type="number" v-model="form.stock"
          class="mt-1 block w-full" autocomplete="stock" placeholder="0" />
        <InputError v-if="form.errors.stock" class="mt-2" :message="form.errors.stock" />
      </div>
      <div class="mb-2">
        <InputLabel for="tipo" value="Tipo de producto" />
        <TextInput @keyup="checkIsRequired('type')" :disabled="form.processing" id="tipo" v-model="form.type"
          type="text" class="mt-1 block w-full" autocomplete="tipo"
          placeholder="Tipo de producto (ej.: telefonía, pastelería, deportes)" />
        <InputError v-if="form.errors.type" class="mt-2" :message="form.errors.type" />
      </div>
      <div class="mb-2">
        <InputLabel for="description" value="Descripcion" />
        <textarea v-model="form.description" id="description" rows="4"
          class="w-full p-2 border border-gray-300 rounded mt-1" placeholder="Descripción del producto"></textarea>
        <InputError v-if="form.errors.description" class="mt-2" :message="form.errors.description" />
      </div>

      <!-- Botón de Enviar -->
      <div class="flex justify-end gap-3 items-center mt-4">
        <PrimaryButton :disabled="form.processing">Guardar</PrimaryButton>
        <Link class="block underline">Volver</Link>
      </div>
    </form>
  </div>
</template>

<script setup>
import GuestHeader from '@/Components/GuestHeader.vue'
import { Link, useForm } from '@inertiajs/inertia-vue3';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';


const checkIsRequired = (k) => {

  if (form.errors[k]) {
    delete form.errors[k];
  }

  if (String(form[k]).trim().length == 0)
    form.setError(k, 'Este campo es requerido.');
  else
    delete form.errors[k];
}

const form = useForm({
  name: '',
  description: '',
  price: "",
  image: '',
  stock: '',
  type: ''
})

const submitCreation = () => {
  form.post(route('products.store'), { preserveScroll: true })
}
</script>
