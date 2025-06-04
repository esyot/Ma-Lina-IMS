<script setup>
import Layout from "@/Layouts/Layout.vue";

import { ref, defineProps } from "vue";
import { WhenVisible, router } from "@inertiajs/vue3";

import Loader from "@/Loaders/Loader.vue";

import AddItem from "@/Modals/AddItem.vue";
import AddCategory from "@/Modals/AddCategory.vue";

const props = defineProps({
  user: {
    type: Object,
    required: true,
  },
  categories: {
    type: Array,
    default: () => [],
  },
  items: {
    type: Object,
    default: () => [],
  },
  uoms: {
    type: Object,
  },
  searchTerm: {
    type: String,
  },
  categoryId: {
    type: String,
    default: "all",
  },
});

const isOpenAddItemModal = ref(false);
const isOpenAddCategoryModal = ref(false);
const searchValue = ref(props.searchTerm ?? "");
const selectedCategory = ref(props.categoryId ?? "all");

const toggleAddItemModal = () => {
  isOpenAddItemModal.value = !isOpenAddItemModal.value;
};

const toggleAddCategoryModal = () => {
  isOpenAddCategoryModal.value = !isOpenAddCategoryModal.value;
};

const changeCategory = (categoryId) => {
  selectedCategory.value = categoryId;
  if (categoryId === "all") {
    router.visit("/inventory");
    return;
  } else {
    router.visit(`/inventory/${categoryId}`);
  }
};

const paginationControl = (url) => {
  if (url) {
    router.visit(url);
  }
};

const searchItems = () => {
  if (searchValue.value.trim() !== "") {
    router.visit(`/inventory/search/${selectedCategory.value}/${searchValue.value}`);
  } else {
    router.visit("/inventory");
  }
};

const showCategories = ref(true);
if (props.categoryId === "all") {
  showCategories.value = false;
} else {
  showCategories.value = true;
}
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
    <section
      class="flex justify-between items-center w-full sticky top-0 bg-white shadow-md z-10"
    >
      <h1 class="px-4 font-bold text-xl">Inventory</h1>

      <div
        class="flex items-center px-4 py-2 border border-red-800/50 m-2 rounded-full shadow-inner"
      >
        <input
          v-model="searchValue"
          type="text"
          class="outline-none bg-transparent"
          placeholder="Search items..."
          @keyup.enter="searchItems"
        />
        <div class="flex items-center space-x-2">
          <a href="/inventory" v-if="searchValue">
            <i class="fas fa-circle-xmark text-gray-500 hover:opacity-50"></i>
          </a>
          <button @click="searchItems" class="text-red-800 hover:opacity-50">
            <i class="fas fa-magnifying-glass"></i>
          </button>
        </div>
      </div>
    </section>
    <div class="flex justify-between">
      <nav>
        <button
          class="flex w-full justify-between items-center px-4 space-x-2 border-x shadow-md"
          @click="showCategories = !showCategories"
        >
          <span class="font-bold py-2">Categories</span>
          <i
            :class="[
              'fas fa-chevron-down fa-sm transition-transform duration-300',
              showCategories ? 'rotate-180' : '',
            ]"
          ></i>
        </button>

        <ul
          v-show="true"
          :class="[
            'flex flex-col justify-between w-full bg-gray-100 p-4 space-y-2 border-b rounded-b-lg shadow-md transform transition-all duration-300 ease-in-out origin-top',
            showCategories
              ? 'max-h-96 opacity-100 scale-100'
              : 'max-h-0 opacity-0 scale-y-95 overflow-hidden',
          ]"
        >
          <li class="hover:text-red-800 cursor-pointer" @click="changeCategory('all')">
            All
          </li>
          <li
            v-for="(category, index) in categories"
            :key="category.id || index"
            @click="changeCategory(category.id)"
            class="hover:text-red-800 cursor-pointer"
          >
            {{ category.name }}
          </li>
          <button
            @click="toggleAddCategoryModal"
            class="border rounded-lg flex justify-center bg-white px-4 py-2 items-center space-x-2 hover:bg-red-800/50 hover:text-white transition-colors duration-200"
            type="button"
          >
            <i class="fas fa-plus"></i>
          </button>
        </ul>
      </nav>

      <aside class="flex-1 m-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
          <button
            v-if="categories.length > 0"
            class="relative bg-white rounded-xl shadow hover:shadow-lg transition-shadow duration-200 flex flex-col items-center justify-center cursor-pointer border-2 border-dashed border-gray-300 hover:border-red-800 min-h-64"
            @click="toggleAddItemModal"
            type="button"
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
              v-for="(item, index) in items.data"
              :key="item.id || index"
              :title="item.description"
              class="relative bg-white rounded-xl shadow hover:shadow-lg transition-shadow duration-200 flex flex-col"
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
                  <span class="text-sm font-bold text-red-800">
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
    <div class="footer flex items-center justify-center space-x-4 mt-6">
      <i
        @click="paginationControl(items.first_page_url)"
        class="fa-solid fa-angles-left cursor-pointer"
      ></i>
      <i
        @click="paginationControl(items.prev_page_url)"
        class="fas fa-chevron-left cursor-pointer"
      ></i>
      <span>Page {{ items.current_page }} of {{ items.last_page }}</span>
      <i
        @click="paginationControl(items.next_page_url)"
        class="fas fa-chevron-right cursor-pointer"
      ></i>
      <i
        @click="paginationControl(items.last_page_url)"
        class="fa-solid fa-angles-right cursor-pointer"
      ></i>
    </div>
  </Layout>
</template>
