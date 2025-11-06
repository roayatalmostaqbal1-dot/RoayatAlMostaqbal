<template>
  <form @submit.prevent="handleSubmit" class="space-y-4">
    <div v-for="field in fields" :key="field.name">
      <!-- Text Input -->
      <Input
        v-if="['text', 'email', 'password', 'number', 'url'].includes(field.type)"
        :model-value="formData[field.name] || ''"
        :type="field.type"
        :label="field.label"
        :placeholder="field.placeholder"
        :required="field.required"
        :error="errors[field.name]"
        :hint="field.hint"
        :disabled="isLoading"
        @update:model-value="updateField(field.name, $event)"
      />

      <!-- Textarea -->
      <div v-else-if="field.type === 'textarea'" class="w-full">
        <label class="block text-sm font-semibold text-white mb-2">
          {{ field.label }}
          <span v-if="field.required" class="text-red-500">*</span>
        </label>
        <textarea
          :value="formData[field.name] || ''"
          :placeholder="field.placeholder"
          :required="field.required"
          :disabled="isLoading"
          @input="updateField(field.name, $event.target.value)"
          :class="[
            'w-full px-4 py-2.5 rounded-lg border-2 transition-all duration-300',
            'bg-[#162936] text-white placeholder-gray-500 resize-none',
            'focus:outline-none focus:border-[#27e9b5] focus:ring-2 focus:ring-[#27e9b5] focus:ring-opacity-50',
            'disabled:opacity-50 disabled:cursor-not-allowed',
            errors[field.name] ? 'border-red-500' : 'border-[#3b5265]',
          ]"
          rows="4"
        ></textarea>
        <p v-if="errors[field.name]" class="text-red-500 text-sm mt-1">{{ errors[field.name] }}</p>
        <p v-if="field.hint" class="text-gray-400 text-sm mt-1">{{ field.hint }}</p>
      </div>

      <!-- Select -->
      <div v-else-if="field.type === 'select'" class="w-full">
        <label class="block text-sm font-semibold text-white mb-2">
          {{ field.label }}
          <span v-if="field.required" class="text-red-500">*</span>
        </label>
        <select
          :value="formData[field.name] || ''"
          :required="field.required"
          :disabled="isLoading"
          @change="updateField(field.name, $event.target.value)"
          :class="[
            'w-full px-4 py-2.5 rounded-lg border-2 transition-all duration-300',
            'bg-[#162936] text-white',
            'focus:outline-none focus:border-[#27e9b5] focus:ring-2 focus:ring-[#27e9b5] focus:ring-opacity-50',
            'disabled:opacity-50 disabled:cursor-not-allowed',
            errors[field.name] ? 'border-red-500' : 'border-[#3b5265]',
          ]"
        >
          <option value="">Select {{ field.label }}</option>
          <option v-for="option in field.options" :key="option.value" :value="option.value">
            {{ option.label }}
          </option>
        </select>
        <p v-if="errors[field.name]" class="text-red-500 text-sm mt-1">{{ errors[field.name] }}</p>
      </div>

      <!-- Checkbox -->
      <div v-else-if="field.type === 'checkbox'" class="flex items-center">
        <input
          :id="`field-${field.name}`"
          type="checkbox"
          :checked="formData[field.name] || false"
          :disabled="isLoading"
          @change="updateField(field.name, $event.target.checked)"
          class="w-4 h-4 rounded border-[#3b5265] bg-[#162936] text-[#27e9b5] cursor-pointer"
        />
        <label :for="`field-${field.name}`" class="ml-2 text-sm font-semibold text-white cursor-pointer">
          {{ field.label }}
        </label>
      </div>
    </div>
  </form>
</template>

<script setup>
import Input from '../ui/Input.vue';

const props = defineProps({
  fields: {
    type: Array,
    required: true,
  },
  formData: {
    type: Object,
    required: true,
  },
  errors: {
    type: Object,
    default: () => ({}),
  },
  isLoading: {
    type: Boolean,
    default: false,
  },
});

const emit = defineEmits(['update:form-data', 'submit']);

const updateField = (fieldName, value) => {
  emit('update:form-data', {
    ...props.formData,
    [fieldName]: value,
  });
};

const handleSubmit = () => {
  emit('submit');
};
</script>

