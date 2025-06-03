<script setup>
import Layout from "@/Layouts/Layout.vue";
import { defineProps } from "vue";
import { useForm } from "@inertiajs/vue3";

defineProps({
  stocks: {
    type: Array,
    default: () => [],
  },
  date: {
    type: String,
    default: "",
  },
});
</script>

<template>
  <Layout>
    <section>
      <div class="flex border-b items-center justify-between p-2">
        <h1 class="text-2xl font-bold">Stock Record on {{ date }}</h1>
      </div>

      <div class="flex mt-4">
        <div class="flex-1">
          <table class="min-w-full border">
            <thead>
              <tr>
                <th class="border px-4 py-2 text-left w-80">Item Name</th>

                <th class="border px-2 py-2 text-left w-20 bg-green-500/50">Beg. Inv.</th>
                <th class="border px-2 py-2 text-left w-20 bg-yellow-500/50">Stock In</th>
                <th class="border px-2 py-2 text-left w-20 bg-red-500/50">Stock Out</th>
                <th class="border px-2 py-2 text-left w-20 bg-blue-500/50">Final Inv</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(stock, index) in stocks" :key="index">
                <td class="border px-4 py-2 w-80">{{ stock.item.name }}</td>

                <td class="border px-2 py-2 w-20 bg-green-500/10">
                  {{ stock.beg_inv }}
                </td>

                <td class="border px-2 py-2 w-20 bg-yellow-500/10">
                  {{ stock.stock_in }}
                </td>

                <td class="border px-2 py-2 w-20 bg-red-500/10">
                  {{ stock.stock_out }}
                </td>

                <td class="border px-2 py-2 w-20 bg-blue-500/10">
                  {{ stock.final_inv }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </section>
  </Layout>
</template>
