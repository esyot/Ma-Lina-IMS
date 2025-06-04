<script setup>
import { defineEmits, ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import axios from "axios";
import { useToast } from "vue-toastification";

const isSearchingItems = ref(false);
const items = ref([]);
const search_value = ref("");

const itemQuantities = ref({});

const selectItem = (id, name) => {
  const qty = itemQuantities.value[id];
  if (!qty || qty <= 0) return;

  const exists = form.selected_items.find((item) => item.id === id);
  if (!exists) {
    form.selected_items.push({ id, name, qty });
  } else {
    exists.qty = qty;
  }

  itemQuantities.value[id] = "";
  search_value.value = "";
  isSearchingItems.value = false;
  items.value = [];
};

const form = useForm({
  name: "",
  contact_no: "",
  purpose: "",
  date_start: "",
  date_end: "",
  selected_items: [],
});

const emit = defineEmits(["toggleBorrowingSlipModal"]);

const closeModal = () => {
  emit("toggleBorrowingSlipModal");
};

const submitForm = () => {
  const toast = useToast();
  form.post("/borrowing-items/submit-slip", {
    onSuccess: () => {
      toast.success("Borrowing slip submitted successfully.", {
        position: "top-right",
        timeout: 3000,
      });
      closeModal();
    },
    onError: (errors) => {
      toast.error("Failed to submit borrowing slip. Please check the form.", {
        position: "top-right",
        timeout: 3000,
      });
    },
  });
};

const searchItems = () => {
  const toast = useToast();
  isSearchingItems.value = true;

  if (form.date_start === "" || form.date_end === "") {
    toast.error("Please complete the date start and date end fields.");
    isSearchingItems.value = false;
    return;
  }
  if (form.date_end < form.date_start) {
    toast.error("Date end must be greater than or equal to date start.");
    isSearchingItems.value = false;
    return;
  }

  axios
    .get("/items-search", {
      params: {
        search_value: search_value.value,
        date_start: form.date_start,
        date_end: form.date_end,
      },
      headers: { "Content-Type": "application/json" },
    })
    .then(({ data }) => {
      items.value = Array.isArray(data) ? data : [];
      isSearchingItems.value = false;
    })
    .catch(() => {
      console.error("Error fetching items");
      items.value = [];
      isSearchingItems.value = false;
    });
};
</script>
<template>
  <div
    @click.self="closeModal"
    class="flex fixed justify-center items-center bg-gray-800/50 inset-0 z-50"
  >
    <div class="bg-white p-6 rounded shadow-lg">
      <form @submit.prevent="submitForm">
        <h2 class="text-xl font-semibold mb-4 p-2 border-b border-red-800">
          Borrowing Slip
        </h2>
        <section class="flex space-x-2">
          <div>
            <div class="mb-2">
              <label for="name" class="block text-sm font-medium text-gray-700"
                >Name:</label
              >
              <input
                type="text"
                id="name"
                v-model="form.name"
                placeholder="Enter borrower's name"
                class="block px-4 py-2 w-full border border-gray-300 rounded"
              />
            </div>

            <div class="mb-2">
              <label for="contact_no" class="block text-sm font-medium text-gray-700"
                >Contact No:</label
              >
              <input
                type="text"
                id="contact_no"
                v-model="form.contact_no"
                required
                placeholder="Enter borrower's contact number"
                class="block px-4 py-2 w-full border border-gray-300 rounded"
              />
            </div>

            <div class="mb-2">
              <label for="date_start" class="block text-sm font-medium text-gray-700"
                >Date Start:</label
              >
              <input
                type="date"
                id="date_start"
                v-model="form.date_start"
                required
                class="block px-4 py-2 w-full border border-gray-300 rounded"
              />
            </div>

            <div class="mb-2">
              <label for="date_end" class="block text-sm font-medium text-gray-700"
                >Date End:</label
              >
              <input
                type="date"
                id="date_end"
                v-model="form.date_end"
                required
                class="block px-4 py-2 w-full border border-gray-300 rounded"
              />
            </div>

            <div class="mb-2">
              <label for="purpose" class="block text-sm font-medium text-gray-700"
                >Purpose:</label
              >
              <textarea
                id="purpose"
                v-model="form.purpose"
                placeholder="Enter borrower's purpose"
                class="block px-4 py-2 w-full border border-gray-300 rounded"
              ></textarea>
            </div>
          </div>

          <div>
            <label>Search Items:</label>
            <div class="bg-gray-100">
              <div
                class="flex px-2 py-2 items-center justify-between space-x-2 border border-gray-300 bg-white rounded mt-2"
              >
                <input
                  type="text"
                  v-model="search_value"
                  class="outline-none flex-grow"
                  placeholder="Search items..."
                />
                <button
                  @click.prevent="searchItems"
                  class="bg-red-800 text-white px-2 py-1 rounded hover:opacity-50"
                >
                  <i class="fas fa-magnifying-glass"></i>
                </button>
              </div>

              <ul class="p-2" v-if="items.length">
                <li
                  v-for="(item, index) in items"
                  :key="index"
                  class="flex justify-between space-x-4 items-center"
                >
                  <span>{{ item.name }}, available: {{ item.final_inv }} pcs</span>

                  <div :class="item.final_inv <= 0 ? 'hidden' : 'flex'">
                    <input
                      type="number"
                      :max="parseInt(item.final_inv, 10)"
                      v-model="itemQuantities[item.id]"
                      class="px-2 py-2 w-16 border rounded-l-lg"
                      placeholder="qty"
                      required
                    />
                    <button
                      @click="selectItem(item.id, item.name)"
                      type="button"
                      class="rounded-r-lg bg-red-800 p-2 text-red-100 hover:opacity-50"
                    >
                      <i class="fas fa-plus"></i>
                    </button>
                  </div>
                </li>
              </ul>
            </div>

            <div class="mt-4">
              <h1 class="font-semibold mb-2">Selected Items:</h1>
              <ul class="bg-gray-50 rounded p-2 shadow-sm space-y-2 h-48 overflow-y-auto">
                <li
                  v-for="(item, index) in form.selected_items"
                  :key="index"
                  class="flex items-center justify-between px-2 py-1 border-b last:border-b-0"
                >
                  <span class="">{{ item.name }}</span>
                  <div class="flex items-center space-x-2">
                    <span class="">{{ item.qty }} pcs</span>
                    <button
                      class="hover:text-red-500"
                      @click="selected_items.splice(index, 1)"
                    >
                      <i class="fas fa-trash"></i>
                    </button>
                  </div>
                </li>
                <li
                  v-if="!form.selected_items.length"
                  class="text-gray-400 text-sm px-2 py-1"
                >
                  No items selected.
                </li>
              </ul>
            </div>
          </div>
        </section>

        <div class="flex justify-end mt-4">
          <button
            type="submit"
            :disabled="form.processing"
            class="bg-red-800 text-red-100 px-4 py-2 rounded hover:opacity-50 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <span v-if="form.processing">Submitting...</span>
            <span v-else>Submit</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</template>
