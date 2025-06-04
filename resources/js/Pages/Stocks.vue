<script setup>
import Layout from "@/Layouts/Layout.vue";

import { defineProps } from "vue";
defineProps({
  user: {
    type: Object,
    required: true,
  },
  stocks: {
    type: Object,
  },
});

const formattedDate = (date) =>
  new Date(date).toLocaleDateString(undefined, {
    year: "numeric",
    month: "long",
    day: "numeric",
  });
</script>

<template>
  <Layout :user="user">
    <section class="select-none">
      <div class="flex items-center justify-between p-2 shadow-md">
        <h1 class="text-xl font-bold">Stock Records</h1>

        <a
          href="/stocks/add-stock-record"
          class="bg-red-800 text-white px-4 py-2 rounded hover:opacity-50"
        >
          Add New Stock Record
        </a>
      </div>
    </section>
    <section class="h-[80vh] overflow-y-auto">
      <div v-for="(stock, date) in stocks" :key="date">
        <a
          :href="'/stocks/stock-record/' + date"
          class="flex items-center justify-between spaxe-x-2 border-b p-2 hover:bg-gray-100 cursor-pointer"
        >
          <span>{{ formattedDate(date) }}</span>
          <button class="p-4 hover:opacity-50">
            <i class="fas fa-eye text-red-800 px-2"></i>
          </button>
        </a>
      </div>
    </section>
  </Layout>
</template>
