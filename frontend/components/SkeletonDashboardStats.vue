<template>
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 animate-pulse">
    <div v-for="i in (count || 3)" :key="i" class="bg-white rounded-xl border border-gray-200 p-6">
      <div class="h-8 bg-gray-200 rounded w-16 mb-2" />
      <div class="h-3 bg-gray-200 rounded w-24" />
    </div>
  </div>
</template>

<script setup lang="ts">
defineProps<{ count?: number }>()
</script>
