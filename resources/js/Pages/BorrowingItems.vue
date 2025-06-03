<script setup>
import Layout from "@/Layouts/Layout.vue";

import BorrowingSlip from "@/Modals/BorrowingSlip.vue";

import { defineProps, ref } from "vue";
import { router } from "@inertiajs/vue3";

defineProps({
  user: {
    type: Object,
    required: true,
  },
  borrowing_slips: {
    type: Array,
    default: () => [],
  },
});

const isOpenBorrowingSlipModal = ref(false);
const toggleBorrowingSlipModal = () => {
  isOpenBorrowingSlipModal.value = !isOpenBorrowingSlipModal.value;
};

const markAsReturned = (id) => {
  router.patch(
    "/borrowing-items/mark-as-returned",
    { id },
    {
      onSuccess: () => {
        console.log(`Slip with ID ${id} marked as returned.`);
      },
      onError: (errors) => {
        console.error("Error marking slip as returned:", errors);
      },
    }
  );
};

const filterStatus = ref("");
</script>

<template>
  <Layout :user="user">
    <BorrowingSlip
      v-if="isOpenBorrowingSlipModal"
      @toggleBorrowingSlipModal="toggleBorrowingSlipModal"
    />
    <section class="py-2 px-4">
      <div class="flex items-center justify-between border-b border-red-800/50 p-2">
        <h1 class="text-2xl font-bold text-gray-800">Borrowers</h1>
        <button
          @click="toggleBorrowingSlipModal"
          class="bg-red-800 text-red-100 px-4 py-2 rounded hover:opacity-50"
        >
          Borrowing Slip
        </button>
      </div>

      <section class="my-4 flex gap-4 items-center">
        <label class="font-semibold text-gray-700">Filter:</label>
        <select
          v-model="filterStatus"
          class="border rounded px-3 py-1 text-gray-700 focus:outline-none"
        >
          <option value="">All</option>
          <option value="Ongoing">Ongoing</option>
          <option value="Returned">Returned</option>
        </select>
      </section>
      <div
        class="mt-2 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6"
      >
        <div
          v-for="(slip, index) in borrowing_slips"
          :key="index"
          class="bg-white rounded-lg shadow p-5 flex flex-col gap-2 border hover:shadow-lg transition"
        >
          <div class="flex items-center gap-2 mb-2">
            <span class="font-semibold text-lg text-gray-700">{{ slip.name }}</span>
          </div>
          <div class="text-gray-500 text-sm">
            <span class="block"><strong>Contact:</strong> {{ slip.contact_no }}</span>
            <span class="block"><strong>Purpose:</strong> {{ slip.purpose }}</span>
            <span class="block"><strong>Status:</strong> {{ slip.status }}</span>
          </div>
          <div class="mt-4 flex gap-2">
            <button
              class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600 text-xs"
            >
              View Borrowed Items
            </button>

            <button
              @click="markAsReturned(slip.id)"
              class="bg-blue-500 text-white px-3 py-1 rounded hover:opacity-50 text-xs"
            >
              Mark as Returned
            </button>
          </div>
        </div>
      </div>
    </section>
  </Layout>
</template>
