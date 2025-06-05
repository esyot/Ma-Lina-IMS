<script setup>
import { ref, defineProps } from "vue";

defineProps({
  user: {
    type: Object,
    default: [],
  },
});

const isOpen = ref(false);
</script>

<template>
  <nav class="bg-white sticky top-0 z-50 shadow-md">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between h-16">
        <div class="flex">
          <div class="flex-shrink-0">
            <img
              class="w-32"
              src="/public/assets/images/ma-lina-logo.png"
              alt="Ma Lina Logo"
            />
          </div>
        </div>

        <div class="flex md:hidden">
          <button
            @click="isOpen = !isOpen"
            type="button"
            class="inline-flex items-center justify-center p-2 rounded-md text-gray-900 hover:text-gray-700 focus:outline-none"
            aria-controls="mobile-menu"
            aria-expanded="isOpen"
          >
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path
                v-if="!isOpen"
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M4 6h16M4 12h16M4 18h16"
              />
              <path
                v-else
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M6 18L18 6M6 6l12 12"
              />
            </svg>
          </button>
        </div>

        <div class="hidden md:flex md:items-center md:space-x-4">
          <a
            href="/"
            class="relative px-4 py-2 rounded font-semibold text-red-800 transition group"
          >
            Home
            <span
              class="absolute left-0 -bottom-1 w-full h-0.5 bg-red-800 rounded transition-all duration-300"
              :class="[
                $page.url === '/' ? 'opacity-100 scale-x-100' : 'opacity-0 scale-x-0',
                'group-hover:opacity-100 group-hover:scale-x-100',
              ]"
            ></span>
          </a>
          <a
            href="/inventory"
            class="relative px-4 py-2 rounded font-semibold text-red-800 transition group"
          >
            Inventory
            <span
              class="absolute left-0 -bottom-1 w-full h-0.5 bg-red-800 rounded transition-all duration-300"
              :class="[
                $page.url.startsWith('/inventory')
                  ? 'opacity-100 scale-x-100'
                  : 'opacity-0 scale-x-0',
                'group-hover:opacity-100 group-hover:scale-x-100',
              ]"
            ></span>
          </a>
          <a
            href="/stocks"
            class="relative px-4 py-2 rounded font-semibold text-red-800 transition group"
          >
            Stocks
            <span
              class="absolute left-0 -bottom-1 w-full h-0.5 bg-red-800 rounded transition-all duration-300"
              :class="[
                $page.url.startsWith('/stocks')
                  ? 'opacity-100 scale-x-100'
                  : 'opacity-0 scale-x-0',
                'group-hover:opacity-100 group-hover:scale-x-100',
              ]"
            ></span>
          </a>
          <a
            href="/borrowing-items"
            class="relative px-4 py-2 rounded font-semibold text-red-800 transition group"
          >
            Borrowing Items
            <span
              class="absolute left-0 -bottom-1 w-full h-0.5 bg-red-800 rounded transition-all duration-300"
              :class="[
                $page.url.startsWith('/borrowing-items')
                  ? 'opacity-100 scale-x-100'
                  : 'opacity-0 scale-x-0',
                'group-hover:opacity-100 group-hover:scale-x-100',
              ]"
            ></span>
          </a>
        </div>

        <div class="relative group hidden md:block">
          <button class="flex items-center space-x-2 focus:outline-none">
            <img
              src="/public/assets/images/user-logo.png"
              alt="User"
              class="w-8 h-8 rounded-full border border-gray-300"
            />
            <span>{{ user.name }}</span>
          </button>
          <div
            class="absolute mt-2 right-0 w-48 bg-white border border-gray-200 rounded shadow-lg z-50 opacity-0 group-hover:opacity-100 group-focus-within:opacity-100 pointer-events-none group-hover:pointer-events-auto group-focus-within:pointer-events-auto transition-opacity"
          >
            <a href="/account" class="block px-4 py-2 text-gray-700 hover:bg-red-50">
              Account
            </a>
            <form method="GET" action="/logout">
              <button
                type="submit"
                class="w-full text-left px-4 py-2 text-gray-700 hover:bg-red-50"
              >
                Logout
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <div class="md:hidden" id="mobile-menu" v-show="isOpen">
      <div class="px-2 pb-2 space-y-1">
        <a
          href="/"
          :class="[
            $page.url === '/'
              ? 'border border-red-800 text-red-800 px-4 py-2 rounded font-semibold block'
              : 'text-red-800 hover:bg-red-100 px-4 py-2 rounded transition block',
          ]"
          >Home</a
        >
        <a
          href="/inventory"
          :class="[
            $page.url.startsWith('/inventory')
              ? 'border border-red-800 text-red-800 px-4 py-2 rounded font-semibold block'
              : 'text-red-800 hover:bg-red-100 px-4 py-2 rounded transition block',
          ]"
          >Inventory</a
        >
        <a
          href="/stocks"
          :class="[
            $page.url.startsWith('/stocks')
              ? 'border border-red-800 text-red-800 px-4 py-2 rounded font-semibold block'
              : 'text-red-800 hover:bg-red-100 px-4 py-2 rounded transition block',
          ]"
          >Stocks</a
        >
        <a
          href="/borrowing-items"
          :class="[
            $page.url.startsWith('/borrowing-items')
              ? 'border border-red-800 text-red-800 px-4 py-2 rounded font-semibold block'
              : 'text-red-800 hover:bg-red-100 px-4 py-2 rounded transition block',
          ]"
          >Borrowing Items</a
        >

        <a
          href="/account"
          :class="[
            $page.url.startsWith('/account')
              ? 'border border-red-800 text-red-800 px-4 py-2 rounded font-semibold block'
              : 'text-red-800 hover:bg-red-100 px-4 py-2 rounded transition block',
          ]"
          >Account</a
        >

        <form method="GET" action="/logout">
          <button
            type="submit"
            class="w-full text-left px-4 py-2 text-red-800 hover:bg-red-50 block"
          >
            Logout
          </button>
        </form>
      </div>
    </div>
  </nav>
</template>
