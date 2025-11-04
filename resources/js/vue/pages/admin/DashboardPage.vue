<template>
  <DashboardLayout page-title="Dashboard" page-description="Overview of your system">
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
      <StatCard
        title="Total Users"
        value="1,234"
        icon="users"
        trend="+12%"
        trendUp
      />
      <StatCard
        title="Active Sessions"
        value="456"
        icon="activity"
        trend="+5%"
        trendUp
      />
      <StatCard
        title="Revenue"
        value="$12,345"
        icon="trending-up"
        trend="+8%"
        trendUp
      />
      <StatCard
        title="Conversion Rate"
        value="3.24%"
        icon="target"
        trend="-2%"
        :trendUp="false"
      />
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
      <!-- Main Chart -->
      <Card class="lg:col-span-2">
        <template #header>
          <h2 class="text-lg font-bold text-white">User Activity</h2>
        </template>
        <div class="h-64 flex items-center justify-center text-gray-400">
          <p>Chart placeholder - integrate Chart.js here</p>
        </div>
      </Card>

      <!-- Recent Activity -->
      <Card>
        <template #header>
          <h2 class="text-lg font-bold text-white">Recent Activity</h2>
        </template>
        <div class="space-y-4">
          <div v-for="i in 5" :key="i" class="flex items-start gap-3 pb-4 border-b border-[#3b5265] last:border-0">
            <div class="w-2 h-2 rounded-full bg-[#27e9b5] mt-2 flex-shrink-0"></div>
            <div class="flex-1 min-w-0">
              <p class="text-white text-sm font-semibold">User activity {{ i }}</p>
              <p class="text-gray-400 text-xs">2 hours ago</p>
            </div>
          </div>
        </div>
      </Card>
    </div>

    <!-- Additional Info -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <!-- Quick Actions -->
      <Card>
        <template #header>
          <h2 class="text-lg font-bold text-white">Quick Actions</h2>
        </template>
        <div class="space-y-3">
          <Button variant="secondary" class="w-full">Create New User</Button>
          <Button variant="secondary" class="w-full">Generate Report</Button>
          <Button variant="secondary" class="w-full">View Analytics</Button>
        </div>
      </Card>

      <!-- System Status -->
      <Card>
        <template #header>
          <h2 class="text-lg font-bold text-white">System Status</h2>
        </template>
        <div class="space-y-4">
          <div class="flex items-center justify-between">
            <span class="text-gray-400">API Server</span>
            <span class="flex items-center gap-2">
              <span class="w-2 h-2 rounded-full bg-green-500"></span>
              <span class="text-green-400 text-sm">Operational</span>
            </span>
          </div>
          <div class="flex items-center justify-between">
            <span class="text-gray-400">Database</span>
            <span class="flex items-center gap-2">
              <span class="w-2 h-2 rounded-full bg-green-500"></span>
              <span class="text-green-400 text-sm">Operational</span>
            </span>
          </div>
          <div class="flex items-center justify-between">
            <span class="text-gray-400">Cache Server</span>
            <span class="flex items-center gap-2">
              <span class="w-2 h-2 rounded-full bg-yellow-500"></span>
              <span class="text-yellow-400 text-sm">Degraded</span>
            </span>
          </div>
        </div>
      </Card>
    </div>
  </DashboardLayout>
</template>

<script setup>
import DashboardLayout from '../../components/layout/DashboardLayout.vue';
import Card from '../../components/ui/Card.vue';
import Button from '../../components/ui/Button.vue';
import StatCard from '../../components/dashboard/StatCard.vue';
</script>

