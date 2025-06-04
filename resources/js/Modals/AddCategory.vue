<script setup>
import { defineEmits } from "vue";
import { useForm } from "@inertiajs/vue3";
const emit = defineEmits(["toggleAddCategoryModal"]);

const closeModal = () => {
  emit("toggleAddCategoryModal");
};

const form = useForm({
  name: "",
  description: "",
});

const submitForm = () => {
  form.post("/categories/category-store", {
    onSuccess: () => {
      closeModal();
      form.reset();
    },
    onError: (errors) => {
      console.error("Error submitting form:", errors);
    },
  });
};
</script>

<template>
  <div
    @click.self="closeModal"
    class="flex fixed justify-center items-center bg-gray-800/50 inset-0 z-50"
  >
    <div class="bg-white p-6 rounded shadow-lg w-96">
      <form @submit.prevent="submitForm">
        <div class="mb-4">
          <label for="name" class="block text-gray-700 font-bold mb-2">Name</label>
          <input
            v-model="form.name"
            id="name"
            name="name"
            type="text"
            class="w-full px-3 py-2 border rounded"
            required
            autocomplete="off"
            placeholder="Enter category name"
          />
        </div>
        <div class="mb-4">
          <label for="description" class="block text-gray-700 font-bold mb-2"
            >Description</label
          >
          <textarea
            v-model="form.description"
            id="description"
            name="description"
            class="w-full px-3 py-2 border rounded"
            rows="3"
            placeholder="Enter category description"
          ></textarea>
        </div>
        <div class="flex justify-end">
          <button
            type="submit"
            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
            :disabled="form.processing"
          >
            <span v-if="form.processing">Adding...</span>
            <span v-else>Add Category</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</template>
