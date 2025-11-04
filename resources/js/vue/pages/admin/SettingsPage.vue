<template>
  <DashboardLayout page-title="Settings" page-description="Configure system settings">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Settings Menu -->
      <div class="lg:col-span-1">
        <Card>
          <nav class="space-y-2">
            <button
              v-for="item in settingsMenu"
              :key="item.id"
              @click="activeTab = item.id"
              :class="[
                'w-full text-left px-4 py-3 rounded-lg transition-all duration-300',
                'text-gray-400 hover:text-white hover:bg-[#1f3a4a]',
                activeTab === item.id && 'bg-[#27e9b5] text-[#051824] font-semibold',
              ]"
            >
              {{ item.label }}
            </button>
          </nav>
        </Card>
      </div>

      <!-- Settings Content -->
      <div class="lg:col-span-2">
        <Card>
          <template #header>
            <h2 class="text-lg font-bold text-white">{{ activeTabLabel }}</h2>
          </template>

          <!-- General Settings -->
          <div v-if="activeTab === 'general'" class="space-y-6">
            <div>
              <label class="block text-white font-semibold mb-2">Site Name</label>
              <Input v-model="settings.siteName" placeholder="Enter site name" />
            </div>
            <div>
              <label class="block text-white font-semibold mb-2">Site Description</label>
              <textarea
                v-model="settings.siteDescription"
                class="w-full px-4 py-2.5 rounded-lg border-2 border-[#3b5265] bg-[#162936] text-white placeholder-gray-500 focus:outline-none focus:border-[#27e9b5]"
                rows="4"
                placeholder="Enter site description"
              ></textarea>
            </div>
            <Button variant="primary">Save Changes</Button>
          </div>

          <!-- Security Settings -->
          <div v-if="activeTab === 'security'" class="space-y-6">
            <div class="p-4 bg-[#1f3a4a] rounded-lg border border-[#3b5265]">
              <h3 class="text-white font-semibold mb-2">Two-Factor Authentication</h3>
              <p class="text-gray-400 text-sm mb-4">Enhance your account security with 2FA</p>
              <Button variant="secondary">Enable 2FA</Button>
            </div>
            <div class="p-4 bg-[#1f3a4a] rounded-lg border border-[#3b5265]">
              <h3 class="text-white font-semibold mb-2">Change Password</h3>
              <p class="text-gray-400 text-sm mb-4">Update your password regularly</p>
              <Button variant="secondary">Change Password</Button>
            </div>
          </div>

          <!-- Notification Settings -->
          <div v-if="activeTab === 'notifications'" class="space-y-4">
            <div class="flex items-center justify-between p-4 bg-[#1f3a4a] rounded-lg border border-[#3b5265]">
              <div>
                <p class="text-white font-semibold">Email Notifications</p>
                <p class="text-gray-400 text-sm">Receive email updates</p>
              </div>
              <input type="checkbox" class="w-5 h-5 rounded" checked />
            </div>
            <div class="flex items-center justify-between p-4 bg-[#1f3a4a] rounded-lg border border-[#3b5265]">
              <div>
                <p class="text-white font-semibold">Push Notifications</p>
                <p class="text-gray-400 text-sm">Receive push notifications</p>
              </div>
              <input type="checkbox" class="w-5 h-5 rounded" />
            </div>
          </div>
        </Card>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import DashboardLayout from '../../components/layout/DashboardLayout.vue';
import Card from '../../components/ui/Card.vue';
import Input from '../../components/ui/Input.vue';
import Button from '../../components/ui/Button.vue';

const activeTab = ref('general');

const settingsMenu = [
  { id: 'general', label: 'General' },
  { id: 'security', label: 'Security' },
  { id: 'notifications', label: 'Notifications' },
];

const settings = ref({
  siteName: 'Roayat Al Mostaqbal',
  siteDescription: 'Your site description here',
});

const activeTabLabel = computed(() => {
  const item = settingsMenu.find(m => m.id === activeTab.value);
  return item?.label || 'Settings';
});
</script>

