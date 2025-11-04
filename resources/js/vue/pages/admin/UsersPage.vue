<template>
  <DashboardLayout page-title="Users" page-description="Manage system users">
    <Card>
      <template #header>
        <div class="flex items-center justify-between">
          <h2 class="text-lg font-bold text-white">Users List</h2>
          <Button variant="primary" size="sm">Add User</Button>
        </div>
      </template>
      
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead>
            <tr class="border-b border-[#3b5265]">
              <th class="text-left py-3 px-4 text-gray-400 font-semibold text-sm">Name</th>
              <th class="text-left py-3 px-4 text-gray-400 font-semibold text-sm">Email</th>
              <th class="text-left py-3 px-4 text-gray-400 font-semibold text-sm">Role</th>
              <th class="text-left py-3 px-4 text-gray-400 font-semibold text-sm">Status</th>
              <th class="text-left py-3 px-4 text-gray-400 font-semibold text-sm">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="i in 5" :key="i" class="border-b border-[#3b5265] hover:bg-[#1f3a4a] transition-colors">
              <td class="py-3 px-4 text-white">User {{ i }}</td>
              <td class="py-3 px-4 text-gray-400">user{{ i }}@example.com</td>
              <td class="py-3 px-4">
                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-[#27e9b5] bg-opacity-20 text-[#27e9b5]">
                  Admin
                </span>
              </td>
              <td class="py-3 px-4">
                <span class="flex items-center gap-2">
                  <span class="w-2 h-2 rounded-full bg-green-500"></span>
                  <span class="text-green-400 text-sm">Active</span>
                </span>
              </td>
              <td class="py-3 px-4">
                <div class="flex gap-2">
                  <Button variant="ghost" size="sm">Edit</Button>
                  <Button variant="danger" size="sm">Delete</Button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </Card>
  </DashboardLayout>
</template>

<script setup>
import DashboardLayout from '../../components/layout/DashboardLayout.vue';
import Card from '../../components/ui/Card.vue';
import Button from '../../components/ui/Button.vue';
</script>

