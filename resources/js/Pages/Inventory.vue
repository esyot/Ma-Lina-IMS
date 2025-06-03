<script setup>
import Layout from "@/Layouts/Layout.vue";

import { ref, defineProps } from "vue";
import { WhenVisible } from "@inertiajs/vue3";

import Loader from "@/Loaders/Loader.vue";

import AddItem from "@/Modals/AddItem.vue";
import AddCategory from "@/Modals/AddCategory.vue";

defineProps({
  user: {
    type: Object,
    required: true,
  },
  categories: {
    type: Array,
    default: () => [],
  },
  items: {
    type: Array,
    default: () => [],
  },
  uoms: {
    type: Object,
  },
});

const isOpenAddItemModal = ref(false);
const isOpenAddCategoryModal = ref(false);

const toggleAddItemModal = () => {
  isOpenAddItemModal.value = !isOpenAddItemModal.value;
};

const toggleAddCategoryModal = () => {
  isOpenAddCategoryModal.value = !isOpenAddCategoryModal.value;
};
</script>

<template>
  <Layout :user="user">
    <AddItem
      v-if="isOpenAddItemModal"
      :categories="categories"
      :uoms="uoms"
      @toggleAddItemModal="toggleAddItemModal"
    />
    <AddCategory
      v-if="isOpenAddCategoryModal"
      @toggleAddCategoryModal="toggleAddCategoryModal"
    />
    <!-- <section class="flex w-full sticky top-0 bg-white shadow-md z-10">
      <ul class="flex justify-between w-full bg-gray-100 p-2">
        <li
          class="hover:text-blue-500 cursor-pointer"
          v-for="(category, index) in categories"
          :key="index"
        >
          {{ category }}
        </li>
        <li class="hover:text-blue-500 cursor-pointer space-x-2 flex items-center">
          <span>More</span>
          <i class="fas fa-chevron-down"></i>
        </li>
      </ul>
    </section> -->
    <div class="flex justify-between">
      <nav>
        <button
          class="flex w-full justify-between items-center px-4 space-x-2 border-x shadow-md"
        >
          <span class="font-bold py-2">Categories</span>
          <i class="fas fa-chevron-down fa-sm"></i>
        </button>
        <ul
          class="flex flex-col justify-between w-full bg-gray-100 p-4 space-y-2 border-b rounded-b-lg shadow-md"
        >
          <li
            class="hover:text-blue-500 cursor-pointer"
            v-for="(category, index) in categories"
            :key="index"
          >
            {{ category.name }}
          </li>
          <button
            @click="toggleAddCategoryModal"
            class="border rounded-lg flex justify-center bg-white px-4 py-2 items-center space-x-2 hover:bg-red-800/50 hover:text-white transition-colors duration-200"
          >
            <i class="fas fa-plus"></i>
          </button>
        </ul>
      </nav>
      <aside class="flex-1 m-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
          <button
            v-if="categories.length > 0"
            class="relative bg-white rounded-xl shadow hover:shadow-lg transition-shadow duration-200 flex flex-col items-center justify-center cursor-pointer border-2 border-dashed border-gray-300 hover:border-blue-400 min-h-64"
            @click="toggleAddItemModal"
          >
            <div class="flex flex-col items-center justify-center h-full py-8">
              <i class="fas fa-plus text-3xl text-gray-400 mb-2"></i>
              <span class="text-gray-500">Add Item</span>
            </div>
          </button>
          <WhenVisible data="items">
            <template #fallback>
              <Loader :message="'Loading products, please wait.'"></Loader>
            </template>

            <div
              class="relative bg-white rounded-xl shadow hover:shadow-lg transition-shadow duration-200 flex flex-col"
              v-for="(item, index) in items"
              :key="index"
            >
              <div
                class="w-full h-40 flex items-center justify-center bg-gray-50 rounded-t-xl"
              >
                <img
                  :src="
                    item?.img ? `/storage/${item.img}` : '/assets/images/item-default.png'
                  "
                  :alt="item?.name ?? 'Item image'"
                  class="max-h-32 max-w-32 object-contain"
                />
              </div>

              <div class="flex-1 flex flex-col p-4">
                <span class="text-base font-semibold text-gray-900 truncate mb-1">
                  {{ item.name }}
                </span>
                <small class="text-gray-500 mb-2 truncate">{{ item.description }}</small>
                <div class="flex items-center justify-between mt-auto">
                  <span class="text-sm font-bold text-blue-700">
                    {{ item.final_inv }} {{ item.UOM }} /s
                  </span>
                  <span class="text-xs text-gray-400">#{{ item.code || index + 1 }}</span>
                </div>
              </div>
            </div>
          </WhenVisible>
        </div>
      </aside>
    </div>
  </Layout>
</template>
