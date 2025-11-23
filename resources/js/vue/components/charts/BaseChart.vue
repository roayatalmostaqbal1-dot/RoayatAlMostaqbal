<template>
  <div class="w-full h-full">
    <canvas ref="canvasRef"></canvas>
  </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount, watch, computed } from "vue";
import { Chart, registerables } from "chart.js";

Chart.register(...registerables);

const props = defineProps({
  type: { type: String, default: "line" },
  labels: { type: Array, required: true },
  datasets: { type: Array, required: true },
  options: { type: Object, default: () => ({}) }
});

const canvasRef = ref(null);
let chartInstance = null;

const renderChart = () => {
  if (chartInstance) chartInstance.destroy();

  chartInstance = new Chart(canvasRef.value, {
    type: props.type,
    data: {
      labels: props.labels,
      datasets: props.datasets
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      ...props.options
    }
  });
};
const chartData = computed(() => {
  const groups = activityGroupedByDate.value;

  return {
    labels: Object.keys(groups),
    values: Object.values(groups)
  };
});

onMounted(renderChart);

watch(
  () => [props.labels, props.datasets],
  () => renderChart(),
  { deep: true }
);

onBeforeUnmount(() => {
  if (chartInstance) chartInstance.destroy();
});
</script>

<style scoped>
div {
  height: 100%;
}
</style>
