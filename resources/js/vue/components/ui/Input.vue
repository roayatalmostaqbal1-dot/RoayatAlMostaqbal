<template>
  <div class="w-full">
    <label v-if="label" class="block text-sm font-semibold text-white mb-2">
      {{ label }}
      <span v-if="required" class="text-red-500">*</span>
    </label>
    <input
      :value="modelValue"
      :type="type"
      :placeholder="placeholder"
      :disabled="disabled"
      :required="required"
      @input="$emit('update:modelValue', $event.target.value)"
      @blur="$emit('blur')"
      @focus="$emit('focus')"
      :class="[
        'w-full px-4 py-2.5 rounded-lg border-2 transition-all duration-300',
        'bg-[#162936] text-white placeholder-gray-500',
        'focus:outline-none focus:border-[#27e9b5] focus:ring-2 focus:ring-[#27e9b5] focus:ring-opacity-50',
        'disabled:opacity-50 disabled:cursor-not-allowed',
        error ? 'border-red-500' : 'border-[#3b5265]',
      ]"
    />
    <p v-if="error" class="text-red-500 text-sm mt-1">{{ error }}</p>
    <p v-if="hint" class="text-gray-400 text-sm mt-1">{{ hint }}</p>
  </div>
</template>

<script setup>
defineProps({
  modelValue: {
    type: [String, Number],
    default: '',
  },
  type: {
    type: String,
    default: 'text',
  },
  label: String,
  placeholder: String,
  error: String,
  hint: String,
  disabled: Boolean,
  required: Boolean,
});

defineEmits(['update:modelValue', 'blur', 'focus']);
</script>

