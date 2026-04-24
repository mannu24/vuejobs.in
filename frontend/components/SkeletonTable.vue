<template>
  <div class="animate-pulse">
    <div class="bg-gray-50 border-b border-gray-200 px-5 py-3 flex gap-8">
      <div class="h-3 bg-gray-200 rounded w-20" />
      <div class="h-3 bg-gray-200 rounded w-16 hidden md:block" />
      <div class="h-3 bg-gray-200 rounded w-12 hidden lg:block" />
      <div class="h-3 bg-gray-200 rounded w-16 hidden md:block" />
      <div class="h-3 bg-gray-200 rounded w-20 ml-auto" />
    </div>
    <div v-for="i in (rows || 5)" :key="i" class="px-5 py-4 border-b border-gray-100 flex items-center gap-8">
      <div class="flex-1">
        <div class="h-4 bg-gray-200 rounded w-3/5 mb-1" />
        <div class="h-3 bg-gray-200 rounded w-2/5" />
      </div>
      <div class="h-5 bg-gray-200 rounded w-16 hidden md:block" />
      <div class="h-3 bg-gray-200 rounded w-12 hidden lg:block" />
      <div class="h-3 bg-gray-200 rounded w-16 hidden md:block" />
      <div class="flex gap-2">
        <div class="h-4 bg-gray-200 rounded w-8" />
        <div class="h-4 bg-gray-200 rounded w-8" />
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
defineProps<{ rows?: number }>()
</script>
