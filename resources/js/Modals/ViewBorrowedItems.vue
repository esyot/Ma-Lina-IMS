<script setup>
import { defineProps, defineEmits } from "vue";

const props = defineProps({
  items: {
    type: Array,
    required: true,
  },
});

const emit = defineEmits(["toggleViewBorrowedItemsModal"]);

const closeModal = () => {
  emit("toggleViewBorrowedItemsModal", []);
};
</script>

<template>
  <div
    @click.self="closeModal"
    class="flex fixed inset-0 justify-center items-center bg-gray-800/50 z-50"
  >
    <div class="bg-white rounded-lg shadow-xl p-2 w-full max-w-2xl">
      <div class="flex justify-between items-center mb-2">
        <h1 class="text-2xl font-bold text-gray-800">List of Items Borrowed</h1>
        <button
          @click="closeModal"
          class="text-gray-400 hover:text-gray-600 transition-colors text-2xl font-bold focus:outline-none"
          aria-label="Close"
        >
          &times;
        </button>
      </div>
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th
                class="px-6 py-3 text-left text-red-800 text-xs font-semibold text-gray-600 uppercase tracking-wider"
              >
                Item Name
              </th>
              <th
                class="px-6 py-3 text-left text-red-800 text-xs font-semibold text-gray-600 uppercase tracking-wider"
              >
                Quantity
              </th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td colspan="2" class="p-0">
                <div class="max-h-[50vh] overflow-y-auto">
                  <table class="min-w-full divide-y divide-gray-100">
                    <tbody>
                      <tr
                        v-for="item in items"
                        :key="item.id"
                        class="hover:bg-gray-50 transition-colors"
                      >
                        <td class="px-6 py-4 whitespace-nowrap text-gray-800 font-medium">
                          {{ item.item.name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                          {{ item.quantity }} {{ item.item.UOM }}/s
                        </td>
                      </tr>
                      <tr v-if="!items.length">
                        <td colspan="2" class="px-6 py-4 text-center text-gray-400">
                          No items borrowed.
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>
