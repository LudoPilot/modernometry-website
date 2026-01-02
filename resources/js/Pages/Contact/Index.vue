<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Head, useForm, usePage } from '@inertiajs/vue3'

const form = useForm({
    firstname: '',
    lastname: '',
    email: '',
    message: '',
})

const submit = () => {
  form.post(route('contact.send'), {
    onSuccess: () => form.reset(),
  })
}


</script>

<template>
    <AppLayout>
        <Head title="Contact" />

        <div class="max-w-xl mx-auto py-10 space-y-6">

            <h1 class="text-3xl font-bold mb-6">Contact</h1>

            <form @submit.prevent="submit" class="space-y-4">

                <div class="flex gap-4">
                    <div class="flex-1">
                        <label class="block mb-1">Prénom</label>
                        <input v-model="form.firstname" type="text" class="input">
                    </div>
                    <div class="flex-1">
                        <label class="block mb-1">Nom</label>
                        <input v-model="form.lastname" type="text" class="input">
                    </div>
                </div>

                <div>
                    <label class="block mb-1">E-mail *</label>
                    <input v-model="form.email" type="email" class="input">
                    <div v-if="form.errors.email" class="text-red-500 text-sm mt-1">
                        {{ form.errors.email }}
                    </div>
                </div>

                <div>
                    <label class="block mb-1">Message *</label>
                    <textarea v-model="form.message" rows="5" class="textarea"></textarea>
                    <div v-if="form.errors.message" class="text-red-500 text-sm mt-1">
                        {{ form.errors.message }}
                    </div>
                </div>

                <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    Envoyer
                </button>
            </form>

        </div>
    </AppLayout>
</template>

<style scoped>
.input {
    @apply w-full border rounded-lg p-2 bg-white dark:bg-gray-800;
}
.textarea {
    @apply w-full border rounded-lg p-2 bg-white dark:bg-gray-800;
}
</style>
