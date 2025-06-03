<script setup>
import { ref } from "vue";

const email = ref("");
const password = ref("");
const error = ref("");

function login() {
  error.value = "";
  if (!email.value || !password.value) {
    error.value = "Please enter both email and password.";
    return;
  }

  if (email.value === "user@example.com" && password.value === "password") {
    alert("Login successful!");
  } else {
    error.value = "Invalid email or password.";
  }
}
</script>

<template>
  <div class="max-w-md mx-auto mt-10 p-8 border border-gray-200 rounded-lg shadow">
    <div class="flex justify-center mb-6">
      <img
        src="/public/assets/images/ma-lina-logo.png"
        alt="Logo"
        class="w-24 h-24 rounded-full object-cover shadow-lg border-4 border-white"
      />
    </div>
    <h2 class="text-2xl font-semibold mb-6 text-center">Login</h2>
    <form @submit.prevent="login">
      <div class="mb-4">
        <label for="email" class="block text-sm font-medium text-gray-700"
          >Email or Username</label
        >
        <input
          id="email"
          v-model="email"
          type="email"
          placeholder="Enter your email or username"
          required
          class="mt-1 w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
        />
      </div>
      <div class="mb-4">
        <label for="password" class="block text-sm font-medium text-gray-700"
          >Password</label
        >
        <input
          id="password"
          v-model="password"
          type="password"
          placeholder="Enter your password"
          required
          class="mt-1 w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
        />
      </div>
      <div v-if="error" class="text-red-600 mb-4">{{ error }}</div>
      <button
        type="submit"
        class="w-full py-3 bg-red-800 text-white font-semibold rounded hover:bg-blue-700 transition"
      >
        Login
      </button>
    </form>
  </div>
</template>
