<script setup>
import GuestHeader from '@/Components/GuestHeader.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import { reactive } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { onMounted } from 'vue'

const form = useForm({
  nombre: '',
  email: '',
  comentario: '',
})

const submit = () => {
  form.post(route('contact.send'), {
    preserveScroll: true,
    onSuccess: () => {
      form.reset()
    }
  })
}

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});

function smoothScrollTo(targetEl, duration = 800) {
  const target = document.querySelector(targetEl);
  if (!target) return;

  const headerOffset = 90;
  const targetPosition = target.getBoundingClientRect().top + window.pageYOffset - headerOffset;
  const startPosition = window.pageYOffset;
  const distance = targetPosition - startPosition;
  let startTime = null;

  function animation(currentTime) {
    if (startTime === null) startTime = currentTime;
    const timeElapsed = currentTime - startTime;
    const run = easeInOutQuad(timeElapsed, startPosition, distance, duration);
    window.scrollTo(0, run);
    if (timeElapsed < duration) requestAnimationFrame(animation);
  }

  function easeInOutQuad(t, b, c, d) {
    t /= d / 2;
    if (t < 1) return c / 2 * t * t + b;
    t--;
    return -c / 2 * (t * (t - 2) - 1) + b;
  }

  requestAnimationFrame(animation);
}

onMounted(() => {
  const url = new URL(window.location.href)
  const section = url.searchParams.get('scrollTo')

  if (section) {
    const el = document.getElementById(section)
    if (el) {
      // Pequeño delay para asegurar que todo esté renderizado
      setTimeout(() => {
        smoothScrollTo(`#${section}`)
      }, 300)
    }
  }
})
</script>

<template>
    <Head title="Pagina inicial" />
    <GuestHeader />
    <main class="mt-[86px]" >
        <section id="welcome" class="w-full h-72 bg-slate-800 flex justify-center items-center">
            <span class="text-2xl text-center text-white">
                Slider de presentacion
            </span>
        </section>
        <section id="nosotros" class="p-5 bg-white shadow">
            <div class="container sm:w-11/12 xl:w-9/12 mx-auto">
                <h1 class="text-2xl border-b pb-2">Nosotros</h1>
                <div class="mt-4 grid grid-cols-12 justify-center items-center lg:items-start">
                    <div class="pe-4 col-span-12 md:col-span-6 lg:col-span-4">
                        <img src="/images/familia-fuji.png" class="w-auto sm:w-96 lg:h-[390px] mx-auto text-center" />
                    </div>
                    <div class="pt-3 col-span-12 md:col-span-6 lg:col-span-8 text-sm">
                        <p class="mb-4">
                            Desde 1908 la familia Fujihara elabora dulces wagashi en forma artesanal. Su historia comienza en la prefectura de Hiroshima, 
                            bajo la marca 山泉堂 Sansendō, años más tarde parte de la familia se traslada a Paraguay donde comienzan a producir en el año 1958.
                        </p>
                        <p class="mb-4">
                            Luego del gran éxito en la ciudad de Encarnación, en 1968 deciden radicarse en Argentina y la empresa familiar continúa 
                            la producción de pastelería tradicional destinada a la colectividad japonesa.
                        </p>
                        <p class="mb-4">
                            En las celebraciones y conmemoraciones del calendario japonés Fuji Foods se encarga de confeccionar noshimochi para hacer
                            yakimochi a la plancha o kagami mochi para ofrendar en el altar budista y decorar la casa para recibir el nuevo año.
                        </p>
                        <p>
                            La tercera generación de pasteleros encabezada por Tadanobu elaboran manju, nerikiri, kurimanju, yōkan, yomogi mochi, 
                            geppei, namagashi, anmochi y las populares galletas osenbē mediante técnicas tradicionales utilizando antiguas herramientas yakiin, hoy consideradas reliquias familiares que representan la trayectoria gastronómica de la familia Fujihara en nuestro pais.
                        </p>
                    </div>
                </div>
            </div>
        </section>
        <section id="especialidades" class="p-5">
            <div class="container sm:w-11/12 xl:w-9/12 mx-auto">
                <h1 class="text-2xl border-b pb-2">Especialidades</h1>
                <aside class="block italic text-sm my-2">
                    Nuestra especialidad son los delicados dulces mochigashi inspirados en la naturaleza, confeccionados a base de arroz 
                    glutinoso con dulce shiroan, simbolizando las flores peonía, camelia, ume y el bambú.                
                </aside>
                <div class="grid grid-cols-12 gap-4 mt-3 p-0 m-0">
                    <div v-for="n in 6" class="col-span-12 sm:col-span-6 lg:col-span-4">
                        <div class="bg-white  w-full min-h-60 shadow bg-cover bg-center relative hover:cursor-pointer hover:text-xl hover:ease-in hover:duration-150" :style="'background-image: url(/images/home-prod-'+n+'.png)'">
                            <div class="absolute bg-black opacity-50 w-full h-full flex text-center justify-center items-center top-0 start-0">
                            </div>  
                            <div class="w-full h-full flex text-slate-200 absolute z-20 justify-center items-center text-center">
                                <div>Producto {{n}}</div>
                            </div>
                        </div>                        
                    </div>
                    <div class="col-span-12 justify-center items-center text-center my-3">
                        <Link :href="route('products.index')" class="border duration-100 border-indigo-500 text-indigo-500 rounded p-2 bg-white">Ver mas productos</Link>
                    </div>
                </div>
            </div>
        </section>

        <section id="contactanos" class="p-5 bg-white shadow">
            <div class="container sm:w-11/12 xl:w-9/12 mx-auto border-[2px] border-dashed rounded-md py-3 px-5">
                <h1 class="text-2xl border-b pb-2">Contactanos</h1>
                <aside class="block italic text-sm my-3">
                    ¿Dudas, consultas? Dejanos tu mensaje.
                </aside>
                <form @submit.prevent="submit">
                    <div class="mb-2">
                        <InputLabel class="block mb-1" for="input-nombre" value="Tu nombre: *" />
                        <TextInput id="input-nombre" v-model="form.nombre" class="w-full block" placeholder="Juan Perez" />
                        <div v-if="form.errors.nombre" class="text-red-500 text-sm">{{ form.errors.nombre }}</div>
                    </div>
                    <div class="mb-2">
                        <InputLabel class="block mb-1" for="input-email" value="Tu email: *" />
                        <TextInput id="input-email" v-model="form.email" class="w-full block" placeholder="tu@correo.com" />
                        <div v-if="form.errors.email" class="text-red-500 text-sm">{{ form.errors.email }}</div>
                    </div>
                    <div class="mb-2">
                        <InputLabel class="block mb-1" for="input-comentario" value="Comentarios: *" />
                        <textarea v-model="form.comentario" class="w-full border border-slate-300 rounded-md resize-none p-2" rows="4" placeholder="Tu comentario..."></textarea>
                        <div v-if="form.errors.comentario" class="text-red-500 text-sm">{{ form.errors.comentario }}</div>
                    </div>
                    <div class="flex justify-end">
                        <PrimaryButton :disabled="form.processing">Enviar mensaje</PrimaryButton>
                    </div>
                </form>                
            </div>
        </section>
    </main>
</template>