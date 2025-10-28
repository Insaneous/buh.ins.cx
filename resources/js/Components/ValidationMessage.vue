<template>
  <transition name="fade">
    <div
      v-if="message"
      class="flex items-center gap-1 text-xs text-red-700 bg-red-100 border border-red-200 rounded-md px-2 py-[1px]"
    >
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-3 h-3">
        <path
          fill-rule="evenodd"
          d="M8 1.333a6.667 6.667 0 1 0 0 13.334A6.667 6.667 0 0 0 8 1.333ZM7.333 4a.667.667 0 1 1 1.334 0v4a.667.667 0 1 1-1.334 0V4Zm.667 6.667a.667.667 0 1 1 0 1.333.667.667 0 0 1 0-1.333Z"
          clip-rule="evenodd"
        />
      </svg>
      {{ message }}
    </div>
  </transition>
</template>

<script setup>
defineProps({
  message: String,
})
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
