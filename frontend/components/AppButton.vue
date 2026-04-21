<template>
  <button
    :type="type"
    :disabled="loading || disabled"
    class="inline-flex items-center justify-center gap-2 rounded-lg text-sm font-medium transition disabled:opacity-60 disabled:cursor-not-allowed"
    :class="variantClasses"
  >
    <span v-if="loading" class="w-4 h-4 border-2 border-current border-t-transparent rounded-full animate-spin" />
    <slot />
  </button>
</template>

<script setup lang="ts">
const props = withDefaults(defineProps<{
  loading?: boolean
  disabled?: boolean
  variant?: 'primary' | 'secondary' | 'danger'
  type?: 'button' | 'submit'
}>(), {
  loading: false,
  disabled: false,
  variant: 'primary',
  type: 'button',
})

const variantClasses = computed(() => {
  switch (props.variant) {
    case 'secondary': return 'border border-gray-300 text-gray-700 px-5 py-2 hover:bg-gray-50'
    case 'danger': return 'bg-red-600 text-white px-5 py-2 hover:bg-red-700'
    default: return 'bg-vue text-white px-5 py-2 hover:bg-vue/90'
  }
})
</script>
