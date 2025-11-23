
<template>
  <BaseChart
    :type="'line'"
    :labels="labels"
    :datasets="datasets"
    :options="chartOptions"
  />
</template>

<script setup>
import { computed } from "vue";
import BaseChart from "./BaseChart.vue";
import { useDashboardStore } from "@/stores/dashboardStore";

const dashboardStore = useDashboardStore();

// هنا نفترض أنك تجلب بيانات النشاط من API
// dashboardStore.recentActivity أو أي مسار آخر

const labels = computed(() =>
  dashboardStore.recentActivity.map(item => item.date)
);

const datasets = computed(() => [
  {
    label: "Users Activity",
    data: dashboardStore.recentActivity.map(item => item.count),
    borderWidth: 2,
    tension: 0.4,
  }
]);

const chartOptions = {
  plugins: {
    legend: {
      labels: { color: "#fff" }
    }
  },
  scales: {
    x: { ticks: { color: "#ccc" } },
    y: { ticks: { color: "#ccc" } }
  }
};
</script>
