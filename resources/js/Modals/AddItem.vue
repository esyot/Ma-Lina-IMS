<script setup>
import { defineEmits, defineProps, ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import { useToast } from "vue-toastification";

const form = useForm({
  name: "",
  description: "",
  category_id: "",
  UOM: "",
  img: null,
});

const submitForm = () => {
  const toast = useToast();
  form.post("/items/item-store", {
    onSuccess: (success) => {
      toast.success("Item added successfully!", {
        position: "top-right",
        timeout: 5000,
        closeOnClick: true,
        pauseOnHover: true,
      });
      closeModal();
      form.reset();
      imagePreview.value = null;
    },
    onError: () => {
      toast.error("Submission Error! Please check the fields correctly.", {
        position: "top-right",
        timeout: 5000,
        closeOnClick: true,
        pauseOnHover: true,
      });
    },
  });
};

const props = defineProps({
  uoms: {
    type: Object,
  },
  categories: {
    type: Array,
    default: () => [],
  },
});
const emit = defineEmits(["toggleAddItemModal"]);
const closeModal = () => {
  emit("toggleAddItemModal");
};

const imagePreview = ref(null);
const onImageChange = (event) => {
  const file = event.target.files[0];
  form.img = file;
  if (file) {
    const reader = new FileReader();
    reader.onload = (e) => {
      imagePreview.value = e.target.result;
    };
    reader.readAsDataURL(file);
  } else {
    imagePreview.value = null;
  }
};
</script>

<template>
  <div
    @click.self="closeModal"
    class="flex fixed justify-center items-center inset-0 bg-gray-800/50 z-50"
  >
    <div class="bg-white p-6 rounded w-full max-w-md shadow-md">
      <div class="flex justify-between w-full border-b mb-2">
        <h2 class="text-xl font-bold p-2">Add Item</h2>

        <button class="text-red-800 hover:opacity-50" @click="closeModal">
          <i class="fas fa-circle-xmark"></i>
        </button>
      </div>
      <form @submit.prevent="submitForm">
        <div class="mb-2">
          <div v-if="imagePreview" class="flex justify-center">
            <img :src="imagePreview" alt="Preview" class="max-h-20 rounded border" />
          </div>
          <input
            id="img"
            type="file"
            class="w-full border rounded px-3 py-2"
            placeholder="Choose an image"
            @change="onImageChange"
            accept="image/*"
          />
        </div>
        <div class="mb-2">
          <label class="block text-gray-700 mb-1" for="name">Name</label>
          <input
            id="name"
            type="text"
            class="w-full border rounded px-3 py-2"
            placeholder="Enter item name"
            v-model="form.name"
          />
          <i class="text-red-500" v-if="form.errors">{{ form.errors.name }}</i>
        </div>
        <div class="mb-2">
          <label class="block text-gray-700 mb-1" for="description">Description</label>
          <textarea
            id="description"
            class="w-full border rounded px-3 py-2"
            placeholder="Enter item description"
            v-model="form.description"
          ></textarea>
          <i class="text-red-500" v-if="form.errors">{{ form.errors.description }}</i>
        </div>

        <div class="mb-2">
          <label class="block text-gray-700 mb-1" for="category_id">Category</label>
          <select
            id="category_id"
            class="w-full border rounded px-3 py-2"
            v-model="form.category_id"
          >
            <option value="" selected disabled>Select Category</option>
            <option
              v-for="(category, index) in categories"
              :key="index"
              :value="category.id"
              :title="category.description"
            >
              {{ category.name }}
            </option>
          </select>
          <i class="text-red-500" v-if="form.errors">{{ form.errors.category_id }}</i>
        </div>
        <div class="mb-4">
          <label class="block text-gray-700 mb-1" for="UOM">Unit of Measure</label>
          <select id="UOM" class="w-full border rounded px-3 py-2" v-model="form.UOM">
            <option value="" selected disabled>Select UOM</option>
            <option v-for="(uom, index) in uoms" :key="index" :value="uom.symbol">
              {{ uom.name }} ({{ uom.symbol }})
            </option>
          </select>
          <i class="text-red-500" v-if="form.errors">{{ form.errors.UOM }}</i>
        </div>

        <div class="flex justify-end">
          <button
            type="submit"
            class="bg-red-800 text-red-100 px-4 py-2 rounded hover:bg-blue-700 disabled:opacity-50"
            :disabled="form.processing"
          >
            <span v-if="form.processing">Submitting...</span>
            <span v-else>Submit</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</template>
