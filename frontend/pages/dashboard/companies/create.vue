<template>
  <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Add Company</h1>

    <form class="space-y-6" @submit.prevent="handleSubmit">
      <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Company Name</label>
          <input v-model="form.name" type="text" required class="w-full rounded-lg border-gray-300 text-sm">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Headline</label>
          <input v-model="form.headline" type="text" class="w-full rounded-lg border-gray-300 text-sm" placeholder="We build amazing Vue.js products">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Website</label>
          <input v-model="form.website" type="url" class="w-full rounded-lg border-gray-300 text-sm" placeholder="https://company.com">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Logo URL</label>
          <input v-model="form.logo_url" type="url" class="w-full rounded-lg border-gray-300 text-sm" placeholder="https://...">
        </div>
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Size</label>
            <select v-model="form.size" class="w-full rounded-lg border-gray-300 text-sm">
              <option value="">Select</option>
              <option value="1-10">1-10</option>
              <option value="11-50">11-50</option>
              <option value="51-200">51-200</option>
              <option value="201-500">201-500</option>
              <option value="500+">500+</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Industry</label>
            <input v-model="form.industry" type="text" class="w-full rounded-lg border-gray-300 text-sm" placeholder="SaaS, Fintech...">
          </div>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">About</label>
          <textarea v-model="form.about" rows="4" class="w-full rounded-lg border-gray-300 text-sm" placeholder="Tell developers about your company..." />
        </div>
      </div>

      <div v-if="error" class="text-red-500 text-sm">{{ error }}</div>

      <button
        type="submit"
        class="bg-vue text-white px-6 py-2.5 rounded-lg font-medium hover:bg-vue/90 transition"
        :disabled="submitting"
      >
        {{ submitting ? 'Creating...' : 'Create Company' }}
      </button>
    </form>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ middleware: 'auth' })

const { apiFetch } = useApi()
const submitting = ref(false)
const error = ref('')

const form = reactive({
  name: '',
  headline: '',
  website: '',
  logo_url: '',
  size: '',
  industry: '',
  about: '',
  is_public: true,
})

async function handleSubmit() {
  submitting.value = true
  error.value = ''
  try {
    await apiFetch('/companies', { method: 'POST', body: form })
    navigateTo('/dashboard')
  } catch (e: any) {
    error.value = e?.data?.message || 'Failed to create company'
  } finally {
    submitting.value = false
  }
}
</script>
