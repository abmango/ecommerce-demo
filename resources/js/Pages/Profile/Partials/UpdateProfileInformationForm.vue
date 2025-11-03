<script setup>
import { ref } from 'vue';
import { Link, router, useForm } from '@inertiajs/vue3';
import ActionMessage from '@/Components/ActionMessage.vue';
import FormSection from '@/Components/FormSection.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

const props = defineProps({
    user: Object,
});

const form = useForm({
    _method: 'PUT',
    name: props.user.name,
    email: props.user.email,
    cuit: props.user.cuit,
    phone: props.user.phone,
    preferred_contact_method: props.user.preferred_contact_method,
    photo: null,
});

console.log(props.user);

const verificationLinkSent = ref(null);
const photoPreview = ref(null);
const photoInput = ref(null);

const updateProfileInformation = () => {
    if (photoInput.value) {
        form.photo = photoInput.value.files[0];
    }

    form.post(route('user-profile-information.update'), {
        errorBag: 'updateProfileInformation',
        preserveScroll: true,
        onSuccess: () => clearPhotoFileInput(),
    });
};

const sendEmailVerification = () => {
    verificationLinkSent.value = true;
};

const selectNewPhoto = () => {
    photoInput.value.click();
};

const updatePhotoPreview = () => {
    const photo = photoInput.value.files[0];

    if (!photo) return;

    const reader = new FileReader();

    reader.onload = (e) => {
        photoPreview.value = e.target.result;
    };

    reader.readAsDataURL(photo);
};

const deletePhoto = () => {
    router.delete(route('current-user-photo.destroy'), {
        preserveScroll: true,
        onSuccess: () => {
            photoPreview.value = null;
            clearPhotoFileInput();
        },
    });
};

const clearPhotoFileInput = () => {
    if (photoInput.value?.value) {
        photoInput.value.value = null;
    }
};
</script>

<template>
    <FormSection @submitted="updateProfileInformation">
        <template #title>
            Información del perifl
        </template>

        <template #description>
            Actualiza la información de perfil de tu cuenta y la dirección de correo electrónico.
        </template>

        <template #form>
            <!-- Profile Photo -->
            <div v-if="$page.props.jetstream.managesProfilePhotos" class="col-span-6 sm:col-span-4">
                <!-- Profile Photo File Input -->
                <input id="photo" ref="photoInput" type="file" class="hidden" @change="updatePhotoPreview">

                <InputLabel for="photo" value="Photo" />

                <!-- Current Profile Photo -->
                <div v-show="!photoPreview" class="mt-2">
                    <img :src="user.profile_photo_url" :alt="user.name" class="rounded-full h-20 w-20 object-cover">
                </div>

                <!-- New Profile Photo Preview -->
                <div v-show="photoPreview" class="mt-2">
                    <span class="block rounded-full w-20 h-20 bg-cover bg-no-repeat bg-center"
                        :style="'background-image: url(\'' + photoPreview + '\');'" />
                </div>

                <SecondaryButton class="mt-2 me-2" type="button" @click.prevent="selectNewPhoto">
                    Seleccionar nueva foto
                </SecondaryButton>

                <SecondaryButton v-if="user.profile_photo_path" type="button" class="mt-2" @click.prevent="deletePhoto">
                    Remover foto
                </SecondaryButton>

                <InputError :message="form.errors.photo" class="mt-2" />
            </div>

            <!-- Name -->
            <div class="col-span-6 sm:col-span-4">
                <InputLabel for="name" value="Nombre completo" />
                <TextInput id="name" v-model="form.name" type="text" class="mt-1 block w-full" required
                    autocomplete="name" />
                <InputError :message="form.errors.name" class="mt-2" />
            </div>

            <!-- Email -->
            <div class="col-span-6 sm:col-span-4">
                <InputLabel for="email" value="Email" />
                <TextInput id="email" v-model="form.email" type="text" class="mt-1 block w-full" required
                    autocomplete="username" />
                <InputError :message="form.errors.email" class="mt-2" />

                <div v-if="$page.props.jetstream.hasEmailVerification && user.email_verified_at === null">
                    <p class="text-sm mt-2">
                        Tu cuenta no se encuentra verificada aún.

                        <Link :href="route('verification.send')" method="post" as="button"
                            class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            @click.prevent="sendEmailVerification">
                        Click aquí para que te enviemos un nuevo email de activación.
                        </Link>
                    </p>

                    <div v-show="verificationLinkSent" class="mt-2 font-medium text-sm text-green-600">
                        Revisa tu buzón / spam.
                    </div>
                </div>
            </div>

            <!-- CUIT -->
            <div class="col-span-6 sm:col-span-4">
                <InputLabel for="cuit" value="CUIT" />
                <TextInput id="cuit" v-model="form.cuit" type="text" class="mt-1 block w-full" required
                    autocomplete="cuit" />
                <InputError :message="'Tiene que ingresar un CUIT'" class="mt-2" />
            </div>

            <!-- Phone -->
            <div class="col-span-6 sm:col-span-4">
                <InputLabel for="phone" value="phone" />
                <TextInput id="phone" v-model="form.phone" type="text" class="mt-1 block w-full" required
                    autocomplete="phone" />
                <InputError :message="'Ingrese teléfono de contacto'" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-4"> <label for="preferred_contact_method"
                    class="block text-sm font-medium text-gray-700"> Medio de
                    contacto preferido </label> <select id="preferred_contact" v-model="form.preferred_contact_method"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    required>
                    <option disabled value="">Seleccione un medio</option>
                    <option value="email">Correo electrónico</option>
                    <option value="telefono">Teléfono</option>
                    <option value="whatsapp">WhatsApp</option>
                </select>
                <InputError class="mt-2" :message="'Por favor, seleccione una opción.'" />
            </div>
        </template>

        <template #actions>
            <ActionMessage :on="form.recentlySuccessful" class="me-3">
                Saved.
            </ActionMessage>

            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Save
            </PrimaryButton>
        </template>
    </FormSection>
</template>
