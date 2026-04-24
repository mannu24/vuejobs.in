<template>
  <div>
    <h1 class="text-xl font-bold text-gray-900 mb-6">My Profile</h1>

    <form class="space-y-5 max-w-2xl" @submit.prevent="handleSubmit">
      <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
          <input v-model="form.name" type="text" class="w-full rounded-lg border-gray-300 text-sm">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Headline</label>
          <input v-model="form.headline" type="text" class="w-full rounded-lg border-gray-300 text-sm" placeholder="Hiring Manager at TechCorp">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">About</label>
          <textarea v-model="form.about" rows="3" class="w-full rounded-lg border-gray-300 text-sm" />
        </div>
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Location</label>
            <input v-model="form.location" type="text" class="w-full rounded-lg border-gray-300 text-sm">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Timezone</label>
            <input v-model="form.timezone" type="text" class="w-full rounded-lg border-gray-300 text-sm" placeholder="Asia/Kolkata">
          </div>
        </div>
      </div>

      <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
        <h2 class="font-semibold text-gray-900">Links</h2>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">LinkedIn</label>
          <input v-model="form.linkedin_url" type="url" class="w-full rounded-lg border-gray-300 text-sm">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Website</label>
          <input v-model="form.website_url" type="url" class="w-full rounded-lg border-gray-300 text-sm">
        </div>
      </div>

      <div v-if="error" class="text-red-500 text-sm">{{ error }}</div>
      <div v-if="success" class="text-green-600 text-sm">Profile updated.</div>

      <button
        type="submit"
        class="inline-flex items-center gap-2 bg-vue text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-vue/90 transition disabled:opacity-50"
        :disabled="submitting"
      >
        <span v-if="submitting" class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin" />
        {{ submitting ? 'Saving...' : 'Save Profile' }}
      </button>
    </form>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ layout: 'recruiter', middleware: 'recruiter' })

const auth = useAuthStore()
const { apiFetch } = useApi()
const submitting = ref(false)
const error = ref('')
const success = ref(false)

const form = reactive({
  name: '',
  headline: '',
  about: '',
  location: '',
  timezone: '',
  linkedin_url: '',
  website_url: '',
})

onMounted(async () => {
  await auth.fetchUser()
  if (auth.user) {
    Object.assign(form, {
      name: auth.user.name ?? '',
      headline: auth.user.headline ?? '',
      about: auth.user.about ?? '',
      location: auth.user.location ?? '',
      timezone: '',
      linkedin_url: auth.user.links?.linkedin ?? '',
      website_url: auth.user.links?.website ?? '',
    })
  }
})

async function handleSubmit() {
  submitting.value = true
  error.value = ''
  success.value = false
  try {
    await apiFetch('/me', { method: 'PUT', body: form })
    success.value = true
    await auth.fetchUser(true)
  } catch (e: any) {
    error.value = e?.data?.message || 'Failed to update profile'
  } finally {
    submitting.value = false
  }
}
</script>
