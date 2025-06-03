<script setup>
import Navbar from "@/Components/Navbar.vue";

import { defineProps } from "vue";

defineProps({
  user: {
    type: Object,
    default: {},
  },
});
</script>

<template>
  <Navbar :user="user" />
  <slot></slot>
</template>
