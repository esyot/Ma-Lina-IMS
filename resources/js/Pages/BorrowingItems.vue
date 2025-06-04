<script setup>
import Layout from "@/Layouts/Layout.vue";

import BorrowingSlip from "@/Modals/BorrowingSlip.vue";
import ViewBorrowedItems from "@/Modals/ViewBorrowedItems.vue";

import { defineProps, ref } from "vue";
import { router } from "@inertiajs/vue3";

const props = defineProps({
  user: {
    type: Object,
    required: true,
  },
  borrowing_slips: {
    type: Array,
    default: () => [],
  },
  status: {
    type: String,
    default: "all",
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

const borrowedItems = ref([]);

const isOpenViewBorrowedItemsModal = ref(false);
const toggleViewBorrowedItemsModal = (items) => {
  borrowedItems.value = items ?? [];
  isOpenViewBorrowedItemsModal.value = !isOpenViewBorrowedItemsModal.value;
};

const status = ref(props.status);

const filterBorrowingSlips = () => {
  router.visit(`/borrowing-items/filter-status/${status.value}`);
};

const formatDate = (dateString) => {
  const options = { year: "numeric", month: "short", day: "numeric" };
  return new Date(dateString).toLocaleDateString(undefined, options);
};
</script>

<template>
  <Layout :user="user">
    <BorrowingSlip
      v-if="isOpenBorrowingSlipModal"
      @toggleBorrowingSlipModal="toggleBorrowingSlipModal"
    />
    <ViewBorrowedItems
      v-if="isOpenViewBorrowedItemsModal"
      @toggleViewBorrowedItemsModal="toggleViewBorrowedItemsModal"
      :items="borrowedItems"
    />
    <section class="">
      <div class="flex items-center justify-between shadow-md">
        <h1 class="text-xl font-bold p-2">Borrowers</h1>
        <button
          @click="toggleBorrowingSlipModal"
          class="bg-red-800 text-red-100 px-4 py-2 m-2 rounded hover:opacity-50"
        >
          Borrowing Slip
        </button>
      </div>

      <section class="my-4 flex gap-4 items-center px-2">
        <label class="font-semibold text-gray-700">Filter:</label>
        <select
          v-model="status"
          class="border rounded px-3 py-1 text-gray-700 focus:outline-none"
          @change="filterBorrowingSlips()"
        >
          <option value="all">All</option>
          <option value="ongoing">Ongoing</option>
          <option value="returned">Returned</option>
        </select>
      </section>
      <div
        class="p-4 mt-2 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6"
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

            <span class="block"
              ><strong>Date:</strong> {{ formatDate(slip.date_start) }} -
              {{ formatDate(slip.date_end) }}</span
            >
            <span class="block"><strong>Status:</strong> {{ slip.status }}</span>
          </div>
          <div class="mt-4 flex justify-center gap-2">
            <button
              @click="toggleViewBorrowedItemsModal(slip.borrowed_items)"
              class="text-red-800 px-4 py-2 rounded hover:opacity-50 text-s"
            >
              View Borrowed Items
            </button>

            <button
              v-if="slip.status === 'ongoing'"
              @click="markAsReturned(slip.id)"
              class="bg-red-800 text-white px-4 py-2 rounded hover:opacity-50 text-s"
            >
              Mark as Returned
            </button>
          </div>
        </div>
      </div>
    </section>
  </Layout>
</template>
