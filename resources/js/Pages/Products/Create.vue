<template>
  <Head title="Nuevo producto"></Head>
  <GuestHeader />
  <div class="container mx-auto p-8 mt-20">
    <form @submit.prevent="submitCreation()" 
      class="bg-white p-6 rounded-lg shadow-md max-w-lg mx-auto">
      <h1 class="text-2xl font-semibold mb-4 pb-1 border-b block">Crear nuevo producto</h1>

      <template v-if="success!==null">
        <div :class="alertStyle">{{ creationMessage }}</div>
      </template>

      <div class="mb-2">
        <InputLabel for="name" value="Nombre del producto" />
        <TextInput required @input="checkIsRequired('name')" @blur="checkIsRequired('name')" id="name" v-model="form.name" type="text" class="mt-1 block w-full"
          autocomplete="name" placeholder="Nombre del producto" />
        <InputError v-if="form.errors.name" class="mt-2" :message="form.errors.name" />
      </div>
      <div class="mb-2">
        <InputLabel for="price" value="Precio" />
        <TextInput required @input="checkIsRequired('price')" @blur="checkIsRequired('price')" id="price" type="number" v-model="form.price"
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
        <TextInput  required @input="checkIsRequired('stock')" @blur="checkIsRequired('stock')" id="stock" type="number" v-model="form.stock"
          class="mt-1 block w-full" autocomplete="stock" placeholder="0" />
        <InputError v-if="form.errors.stock" class="mt-2" :message="form.errors.stock" />
      </div>
      <div class="mb-2">
        <InputLabel for="tipo" value="Tipo de producto" />
        <TextInput required @input="checkIsRequired('type')" @blur="checkIsRequired('type')" id="tipo" v-model="form.type"
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

      <div class="flex justify-end gap-3 items-center mt-4">
        <PrimaryButton type="submit" :disabled="form.processing">Guardar</PrimaryButton>
        <Link class="block underline" :href="route('products.index')">Volver</Link>
      </div>
    </form>
  </div>
</template>


<script>

import GuestHeader from '@/Components/GuestHeader.vue'
import { Head, Link } from '@inertiajs/vue3'; 

import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { useForm } from '@inertiajs/vue3';


export default {

    props : {
      success : null
    },  
    // 1. Componentes
    components: {
        GuestHeader,
        Head,
        Link,
        InputLabel,
        TextInput,
        InputError,
        PrimaryButton
    },

    // 2. Data
    // useForm debe ser llamado dentro de la función data para que sea reactivo y específico
    // de esta instancia del componente.
    data() {
        return {
            form: useForm({
                name: '',
                description: '',
                price: "0",
                image: '',
                stock: '0',
                type: ''
            })
        }
    },

    computed: {
      creationMessage() {
        if(this.success !== null){
          if(this.success) 
            return 'Producto creado exitosamente!';
          return 'Hubo un error, intenta nuevamente mas tarde.';
        }
        return '';
      },
      alertStyle() {
          const baseClasses = 'p-2 mb-2 rounded border'; // Padding, bordes redondeados y borde

          const successClasses = `${baseClasses} bg-green-100 border-green-400 text-green-700`;
          const errorClasses = `${baseClasses} bg-red-100 border-red-400 text-red-700`;

          // Retorna la cadena completa de clases
          return (this.success) ? successClasses : errorClasses;
      }
    },    

    // 3. Métodos
    methods: {
        checkIsRequired(k) {
            // El acceso a `form` se hace con `this.form`
            if (String(this.form[k] ?? '').trim().length === 0) {

                this.form.setError(k, 'Este campo es requerido.');

            } else {
              
              if(this.form.errors[k]) {
                  delete this.form.errors[k];
              }
            }
        },

        submitCreation() {
            // El acceso a `form` se hace con `this.form`
            this.form.post(route('products.store'), {
                preserveScroll: true,
                onFinish : () => {
                  this.form.reset(['name', 'description', 'price', 'image', 'stock']);
                }
            })
        }
    }
}
</script>