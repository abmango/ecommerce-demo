<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import GuestHeader from '@/Components/GuestHeader.vue';
import { reactive, computed } from 'vue'

const form = useForm({
    name: '',
    email: '',
    cuit: '',
    phone: '',
    preferred_contact_method: '',
    password: '',
    password_confirmation: '',
    terms: false,
});

const touched = reactive({
    name: false,
    email: false,
    cuit: false,
    phone: false,
    preferred_contact_method: false,
})

const errors = reactive({
    name: 'Ingrese su nombre.',
    email: 'Debes usar una dirección de correo REAL. Caso contrario, no podrás validarla luego.',
    cuit: 'Ingrese su CUIT.',
    phone: 'Ingrese un número de teléfono con código de área sin 0 ni 15.',
    preferred_contact_method: 'Debe seleccionar un medio de preferencia.',
})

const isNameValid = computed(() => form.name.trim().length > 0)
const isEmailValid = computed(() => {
    const re = /^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/
    return re.test(form.email.trim())
}
)
const isCuitValid = computed(() => /^\d{11}$/.test(form.cuit))
const isPhoneValid = computed(() => /^\d{10}$/.test(form.phone))

const isFormValid = computed(() =>
    isNameValid.value &&
    isEmailValid.value &&
    isCuitValid.value &&
    isPhoneValid.value
)

const submit = () => {
    if (!isFormValid.value) return
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>

    <Head title="Register" />
    <GuestHeader />

    <AuthenticationCard>
        <template #logo>
            <AuthenticationCardLogo />
        </template>

        <form @submit.prevent="submit">
            <h1 class="block pb-1 border-b text-2xl mb-3">Registrate</h1>
            <div>
                <InputLabel for="name" value="Nombre o Razón Social" />
                <TextInput id="name" v-model="form.name" type="text" @blur="touched.name = true"
                    class="mt-1 block w-full" required autofocus autocomplete="name"
                    placeholder="Juan Perez / Distribuidora X S.A" />
                <InputError v-if="touched.name && !isNameValid" class="mt-2" :message="errors.name" />
            </div>

            <div class="mt-4">
                <InputLabel for="email" value="Email" />
                <TextInput id="email" v-model="form.email" type="email" @blur="touched.email = true"
                    class="mt-1 block w-full" required autocomplete="username" placeholder="tu@correo.com" />
                <InputError v-if="touched.email && !isEmailValid" class="mt-2" :message="errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="cuit" value="CUIT" />
                <TextInput id="cuit" v-model="form.cuit" type="cuit" @blur="touched.cuit = true"
                    class="mt-1 block w-full" required autocomplete="cuit"
                    placeholder="XXXXXXXXXXX (sin espacios ni guiones)" />
                <InputError v-if="touched.cuit && !isCuitValid" class="mt-2" :message="errors.cuit" />
            </div>

            <div class="mt-4">
                <InputLabel for="phone" value="Teléfono de contacto" />
                <TextInput id="phone" v-model="form.phone" type="phone" @blur="touched.phone = true"
                    class="mt-1 block w-full" required autocomplete="phone" placeholder="XXXXXXXXXX" />
                <InputError v-if="touched.phone && !isPhoneValid" class="mt-2" :message="errors.phone" />
            </div>

            <div class="mt-4">
                <InputLabel for="preferred_contact_method" value="Medio de contacto preferido"></InputLabel>
                <select id="preferred_contact_method" v-model="form.preferred_contact_method"
                    @blur="touched.preferred_contact_method = true"
                    class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    required>
                    <option disabled value="">Seleccione un medio</option>
                    <option value="email">Correo electrónico</option>
                    <option value="telefono">Teléfono</option>
                    <option value="whatsapp">WhatsApp</option>
                </select>
                <InputError v-if="!form.preferred_contact_method" class="mt-2"
                    :message="errors.preferred_contact_method" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Clave" />
                <TextInput id="password" v-model="form.password" type="password" class="mt-1 block w-full" required
                    autocomplete="new-password" placeholder="****" />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-4">
                <InputLabel for="password_confirmation" value="Confirmá tu clave" />
                <TextInput id="password_confirmation" v-model="form.password_confirmation" type="password"
                    class="mt-1 block w-full" required autocomplete="new-password" placeholder="****" />
                <InputError class="mt-2" :message="form.errors.password_confirmation" />
            </div>

            <div v-if="$page.props.jetstream.hasTermsAndPrivacyPolicyFeature" class="mt-4">
                <InputLabel for="terms">
                    <div class="flex items-center">
                        <Checkbox id="terms" v-model:checked="form.terms" name="terms" required />

                        <div class="ms-2">
                            I agree to the <a target="_blank" :href="route('terms.show')"
                                class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Terms
                                of Service</a> and <a target="_blank" :href="route('policy.show')"
                                class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Privacy
                                Policy</a>
                        </div>
                    </div>
                    <InputError class="mt-2" :message="form.errors.terms" />
                </InputLabel>
            </div>

            <div class="flex items-center justify-end mt-4">
                <Link :href="route('login')"
                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Ya estas registrado?
                </Link>

                <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Crear cuenta
                </PrimaryButton>
            </div>
        </form>
    </AuthenticationCard>
</template>
