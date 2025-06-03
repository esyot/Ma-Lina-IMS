<script setup>
import { defineProps } from "vue";
defineProps({
  message: {
    type: String,
    default: "Loading...",
  },
});
</script>
<template>
  <div class="flex flex-col items-center p-6 space-y-2 justify-center w-full h-full">
    <img
      class="w-10 h-10"
      src="/public/assets/loaders/circular_loader.gif"
      alt="loader"
    />
    <p>{{ message }}</p>
  </div>
</template>
