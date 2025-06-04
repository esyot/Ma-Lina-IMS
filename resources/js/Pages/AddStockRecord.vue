<script setup>
import Layout from "@/Layouts/Layout.vue";
import { defineProps } from "vue";
import { useForm } from "@inertiajs/vue3";

const props = defineProps({
  user: {
    type: Object,
    required: true,
  },
  items: {
    type: Array,
    default: () => [],
  },
});

const form = useForm({
  date: "",
  stocks: props.items.reduce((acc, item) => {
    acc[item.id] = {
      item_id: item.id,
      beg_inv: item.final_inv,
      stock_in: 0,
      stock_out: 0,
    };
    return acc;
  }, {}),
});

const handleSave = () => {
  form.post("/stocks/stock-store", {
    onSuccess: () => {
      console.log("Successfully submitted");
    },
    onError: (errors) => {
      console.error(errors);
    },
  });
};
</script>

<template>
  <Layout :user="props.user">
    <section>
      <form @submit.prevent="handleSave">
        <div class="flex border-b items-center justify-between p-2">
          <h1 class="text-2xl font-bold">Add Stock Record</h1>

          <div class="flex justify-end space-x-2">
            <button
              type="submit"
              class="px-4 py-2 bg-blue-500 text-blue-100 rounded hover:opacity-50"
            >
              Save
            </button>
            <a
              href="/stocks"
              type="button"
              class="px-4 py-2 bg-red-800 text-red-100 rounded hover:opacity-50"
            >
              Cancel
            </a>
          </div>
        </div>

        <div class="p-4">
          <label for="date">Date:</label>
          <input
            type="date"
            v-model="form.date"
            id="date"
            class="block border px-4 py-2 rounded"
            required
          />
        </div>

        <div class="flex mt-4">
          <div class="flex-1">
            <table class="min-w-full border">
              <thead>
                <tr>
                  <th class="border px-4 py-2 text-left w-80">Item Name</th>

                  <th class="border px-2 py-2 text-left w-20 bg-green-500/50">
                    Beg. Inv.
                  </th>
                  <th class="border px-2 py-2 text-left w-20 bg-yellow-500/50">
                    Stock In
                  </th>
                  <th class="border px-2 py-2 text-left w-20 bg-red-500/50">Stock Out</th>
                  <th class="border px-2 py-2 text-left w-20 bg-blue-500/50">
                    Final Inv
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(item, idx) in props.items" :key="item.id">
                  <td class="border px-4 py-2 w-80">{{ item.name }}</td>

                  <td class="border px-2 py-2 w-20 bg-green-500/10">
                    <input
                      type="number"
                      :value="item.final_inv"
                      disabled
                      class="w-full border rounded px-1 py-1"
                    />
                  </td>

                  <td class="border px-2 py-2 w-20 bg-yellow-500/10">
                    <input
                      type="number"
                      v-model.number="form.stocks[item.id].stock_in"
                      class="w-full border rounded px-1 py-1"
                    />
                  </td>

                  <td class="border px-2 py-2 w-20 bg-red-500/10">
                    <input
                      type="number"
                      v-model.number="form.stocks[item.id].stock_out"
                      class="w-full border rounded px-1 py-1"
                    />
                  </td>

                  <td class="border px-2 py-2 w-20 bg-blue-500/10">
                    <input
                      type="number"
                      :value="
                        form.stocks[item.id].beg_inv +
                        form.stocks[item.id].stock_in -
                        form.stocks[item.id].stock_out
                      "
                      class="w-full border rounded px-1 py-1"
                      disabled
                    />
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </form>
    </section>
  </Layout>
</template>
