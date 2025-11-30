<template>
    <DashboardLayout page-title="Dashboard" page-description="Overview of your system">
        <!-- Loading State -->
        <div v-if="dashboardStore.isLoading" class="flex items-center justify-center py-12">
            <div class="text-center">
                <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-[#27e9b5]"></div>
                <p class="text-gray-400 mt-4">Loading dashboard statistics...</p>
            </div>
        </div>

        <!-- Stats Grid -->
        <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <StatCard v-for="stat in dashboardStore.statistics" :key="stat.title" :title="stat.title"
                :value="stat.value.toString()" :icon="stat.icon" :trend="stat.trend" :trend-up="stat.trend_up" />
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Main Chart -->
            <Card class="lg:col-span-2">
                <template #header>
                    <h2 class="text-lg font-bold text-white">User Activity</h2>
                </template>
                <div class="h-64">
                    <BaseChart :type="'line'" :labels="labels" :datasets="datasets" :options="chartOptions" />
                </div>
            </Card>

            <!-- Recent Activity -->
            <Card>
                <template #header>
                    <h2 class="text-lg font-bold text-white">Recent Activity</h2>
                </template>
                <div v-if="dashboardStore.recentActivity.length > 0" class="space-y-4 overflow-y-scroll h-72 ">
                    <div v-for="activity in dashboardStore.recentActivity" :key="activity.id"
                        class="flex items-start gap-3 pb-4 border-b  border-[#3b5265] last:border-0">
                        <div class="w-2 h-2 rounded-full bg-[#27e9b5] mt-2 shrink-0"></div>
                        <div class="flex-1 min-w-0">
                            <p class="text-white text-sm font-semibold">
                                {{ activity.user_name }} <span class="text-gray-400">{{ activity.action }}</span> {{
                                activity.model_type }}
                            </p>
                            <p class="text-gray-400 text-xs">{{ activity.timestamp }}</p>
                        </div>
                    </div>
                </div>
                <div v-else class="text-center py-8">
                    <p class="text-gray-400">No recent activity</p>
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
import { onMounted, computed } from 'vue';
import DashboardLayout from '../../components/layout/DashboardLayout.vue';
import Card from '../../components/ui/Card.vue';
import Button from '../../components/ui/Button.vue';
import StatCard from '../../components/dashboard/StatCard.vue';
import { useDashboardStore } from '../../stores/dashboardStore';
import BaseChart from "../../components/charts/BaseChart.vue";

const dashboardStore = useDashboardStore();

const activityGroupedByDate = computed(() => {
  const grouped = {};

  dashboardStore.recentActivity.forEach(item => {
    const date = item.timestamp.split(",")[0];
    grouped[date] = (grouped[date] || 0) + 1;
    console.log(grouped[date]);
  });
  const sorted = Object.keys(grouped)
    .sort((a, b) => new Date(a) - new Date(b)) // الترتيب حسب التاريخ
    .reduce((acc, key) => {
      acc[key] = grouped[key];
      return acc;
    }, {});

  return sorted;
//   return grouped;
});

const labels = computed(() => Object.keys(activityGroupedByDate.value));

const datasets = computed(() => [
  {
    label: "User Activity",
    data: Object.values(activityGroupedByDate.value),
    borderWidth: 2,
    tension: 0.4,
  }
]);

onMounted(() => {
    dashboardStore.fetchStatistics();
});
</script>
