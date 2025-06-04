<script setup>
import Layout from "@/Layouts/Layout.vue";
import { useForm } from "@inertiajs/vue3";
import { useToast } from "vue-toastification";
import { defineProps } from "vue";

const toast = useToast();

const props = defineProps({
  user: {
    type: Object,
    required: true,
  },
});

const userForm = useForm({
  name: props.user.name,
  email: props.user.email,
});

const securityForm = useForm({
  currentPassword: "",
  newPassword: "",
  confirmPassword: "",
});

const updateUser = () => {
  userForm.put("/account/user-details", {
    preserveScroll: true,
    onSuccess: () => {
      toast.success("User details updated successfully!");
    },
    onError: () => {
      toast.error("Failed to update user details.");
    },
  });
};

const updatePassword = () => {
  securityForm.put("/account/user-password", {
    preserveScroll: true,
    onSuccess: () => {
      toast.success("Password changed successfully!");
      securityForm.reset();
    },
    onError: () => {
      toast.error("Failed to change password.");
    },
  });
};
</script>

<template>
  <Layout :user="user">
    <div
      class="flex fixed inset-0 mx-auto p-6 bg-white rounded shadow flex flex-col md:flex-row gap-8"
    >
      <div class="w-full">
        <h2 class="text-2xl font-bold mb-6">User Details</h2>
        <form @submit.prevent="updateUser">
          <div class="mb-4">
            <label class="block mb-1 font-medium">Name</label>
            <input
              v-model="userForm.name"
              type="text"
              class="w-full border rounded px-3 py-2"
              required
            />
          </div>
          <div class="mb-4">
            <label class="block mb-1 font-medium">Email</label>
            <input
              v-model="userForm.email"
              type="email"
              class="w-full border rounded px-3 py-2"
              required
            />
          </div>
          <button
            type="submit"
            class="bg-red-800 text-red-100 px-4 py-2 rounded hover:opacity-50"
          >
            Save Details
          </button>
        </form>
      </div>

      <div class="w-full">
        <h2 class="text-2xl font-bold mb-6">Account Security</h2>
        <form @submit.prevent="updatePassword">
          <div class="mb-4">
            <label class="block mb-1 font-medium">Current Password</label>
            <input
              v-model="securityForm.currentPassword"
              type="password"
              class="w-full border rounded px-3 py-2"
              required
              placeholder="Enter current password"
            />
          </div>
          <div class="mb-4">
            <label class="block mb-1 font-medium">New Password</label>
            <input
              v-model="securityForm.newPassword"
              type="password"
              class="w-full border rounded px-3 py-2"
              required
              placeholder="Enter new password"
            />
          </div>
          <div class="mb-4">
            <label class="block mb-1 font-medium">Confirm New Password</label>
            <input
              v-model="securityForm.confirmPassword"
              type="password"
              class="w-full border rounded px-3 py-2"
              required
              placeholder="Confirm new password"
            />
          </div>
          <button
            type="submit"
            class="bg-red-800 text-red-100 px-4 py-2 rounded hover:opacity-50"
          >
            Change Password
          </button>
        </form>
      </div>
    </div>
  </Layout>
</template>
