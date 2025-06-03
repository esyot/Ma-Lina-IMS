<script setup>
import Layout from "@/Layouts/Layout.vue";

import { ref, defineProps } from "vue";

defineProps({
  user: {
    type: Object,
    default: [],
  },
  missing_items: {
    type: Array,
    default: () => [],
  },
  out_of_stocks: {
    type: Array,
    default: () => [],
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
    <section class="flex justify-around m-2 space-x-2 select-none">
      <div
        class="border-2 border-red-800/30 rounded-lg w-full shadow-md bg-yellow-100/50"
      >
        <h2 class="text-lg text-red-800 font-semibold p-2 bg-gray-100 rounded-t-lg">
          Missing
        </h2>
        <ul class="px-4 rounded-b-lg">
          <li
            v-for="(missing, index) in missing_items"
            :key="index"
            class="border-b border-gray-800/30 py-2"
          >
            {{ missing.name }} last used on {{ formattedDate(missing.last_date_used) }}
          </li>
        </ul>
        <span v-if="!missing_items" class="flex justify-center">No Items</span>
      </div>

      <div
        class="border-2 border-red-800/30 rounded-lg w-full shadow-md bg-yellow-100/50"
      >
        <h2 class="text-lg text-red-800 font-semibold p-2 bg-gray-100 rounded-t-lg">
          Near Out of Stock
        </h2>
        <ul class="px-4 rounded-b-lg">
          <li
            v-for="(out, index) in out_of_stocks"
            :key="index"
            class="border-b border-gray-800/30 py-2"
          >
            {{ out.name }} - {{ out.final_inv }} {{ out.UOM }} left
          </li>
        </ul>
      </div>
    </section>

    <!-- <section>
      <div class="mx-2 border-2 border-red-800/30 rounded-lg shadow-md select-none">
        <h2 class="text-lg text-red-800 font-semibold p-2 bg-gray-100 rounded-t-lg">
          Borrowed Items
        </h2>
      </div>
    </section> -->
  </Layout>
</template>
