<script setup>
import { useForm } from "@inertiajs/vue3";
import { useToast } from "vue-toastification";

const form = useForm({
  email: "",
  password: "",
});

const login = () => {
  const toast = useToast();
  form.post("/login/submit-credentials", {
    onError: (errors) => {
      if (errors.email) {
        toast.error(errors.email, {
          position: "top-right",
          timeout: 5000,
        });
      }
      if (errors.password) {
        toast.error(errors.password, {
          position: "top-right",
          timeout: 5000,
        });
      }
    },
  });
};
</script>

<template>
  <div class="flex fixed inset-0 items-center justify-center p-6">
    <div class="bg-white min-w-[200px] max-w-md w-full p-8 rounded-lg shadow-lg">
      <div class="flex justify-center">
        <img
          src="/public/assets/images/ma-lina-logo.png"
          alt="Logo"
          class="w-24 h-24 rounded-full object-cover shadow-lg border-4 border-white"
        />
      </div>
      <h2 class="text-2xl font-semibold text-left">Login</h2>
      <form @submit.prevent="login">
        <div class="mb-4">
          <label for="email" class="block text-sm font-medium text-gray-700"
            >Email or Username</label
          >
          <input
            id="email"
            v-model="form.email"
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
            v-model="form.password"
            type="password"
            placeholder="Enter your password"
            required
            class="mt-1 w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
        </div>

        <button
          type="submit"
          class="w-full py-3 bg-red-800 text-white font-semibold rounded hover:opacity-50"
        >
          Login
        </button>
      </form>

      <p class="mt-6 text-center text-sm text-gray-600">
        Forgot your account credentials? Please contact the administrator or web developer
        for assistance.
      </p>
    </div>
  </div>
</template>
