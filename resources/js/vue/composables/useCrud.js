import { ref, reactive } from 'vue';
import api from '../services/api';

/**
 * Composable for managing CRUD operations
 * @param {string} endpoint - API endpoint (e.g., '/users', '/products')
 * @returns {Object} CRUD management object
 */
export function useCrud(endpoint) {
  const items = ref([]);
  const isLoading = ref(false);
  const errors = reactive({});
  const isModalOpen = ref(false);
  const modalMode = ref('create');
  const selectedItem = ref(null);

  /**
   * Fetch all items from the API
   */
  const fetchItems = async () => {
    isLoading.value = true;
    errors.general = null;
    try {
      const response = await api.get(endpoint);
      items.value = response.data.data || response.data;
    } catch (error) {
      errors.general = error.response?.data?.message || 'Failed to fetch items';
      console.error('Fetch error:', error);
    } finally {
      isLoading.value = false;
    }
  };

  /**
   * Create a new item
   */
  const createItem = async (data) => {
    isLoading.value = true;
    Object.keys(errors).forEach(key => delete errors[key]);
    try {
      const response = await api.post(endpoint, data);
      items.value.push(response.data.data || response.data);
      closeModal();
      return response.data;
    } catch (error) {
      if (error.response?.data?.errors) {
        Object.assign(errors, error.response.data.errors);
      } else {
        errors.general = error.response?.data?.message || 'Failed to create item';
      }
      throw error;
    } finally {
      isLoading.value = false;
    }
  };

  /**
   * Update an existing item
   */
  const updateItem = async (id, data) => {
    isLoading.value = true;
    Object.keys(errors).forEach(key => delete errors[key]);
    try {
      const response = await api.put(`${endpoint}/${id}`, data);
      const index = items.value.findIndex(item => item.id === id);
      if (index !== -1) {
        items.value[index] = response.data.data || response.data;
      }
      closeModal();
      return response.data;
    } catch (error) {
      if (error.response?.data?.errors) {
        Object.assign(errors, error.response.data.errors);
      } else {
        errors.general = error.response?.data?.message || 'Failed to update item';
      }
      throw error;
    } finally {
      isLoading.value = false;
    }
  };

  /**
   * Delete an item
   */
  const deleteItem = async (id) => {
    isLoading.value = true;
    errors.general = null;
    try {
      await api.delete(`${endpoint}/${id}`);
      items.value = items.value.filter(item => item.id !== id);
      closeModal();
    } catch (error) {
      errors.general = error.response?.data?.message || 'Failed to delete item';
      throw error;
    } finally {
      isLoading.value = false;
    }
  };

  /**
   * Open modal for creating a new item
   */
  const openCreateModal = () => {
    modalMode.value = 'create';
    selectedItem.value = null;
    isModalOpen.value = true;
  };

  /**
   * Open modal for editing an item
   */
  const openEditModal = (item) => {
    modalMode.value = 'edit';
    selectedItem.value = { ...item };
    isModalOpen.value = true;
  };

  /**
   * Open modal for viewing an item
   */
  const openViewModal = (item) => {
    modalMode.value = 'view';
    selectedItem.value = { ...item };
    isModalOpen.value = true;
  };

  /**
   * Close the modal
   */
  const closeModal = () => {
    isModalOpen.value = false;
    selectedItem.value = null;
    Object.keys(errors).forEach(key => delete errors[key]);
  };

  /**
   * Handle form submission
   */
  const handleSubmit = async (formData) => {
    try {
      if (modalMode.value === 'create') {
        await createItem(formData);
      } else if (modalMode.value === 'edit') {
        await updateItem(selectedItem.value.id, formData);
      }
    } catch (error) {
      console.error('Submit error:', error);
    }
  };

  /**
   * Handle item deletion
   */
  const handleDelete = async (item) => {
    try {
      await deleteItem(item.id);
    } catch (error) {
      console.error('Delete error:', error);
    }
  };

  return {
    items,
    isLoading,
    errors,
    isModalOpen,
    modalMode,
    selectedItem,
    fetchItems,
    createItem,
    updateItem,
    deleteItem,
    openCreateModal,
    openEditModal,
    openViewModal,
    closeModal,
    handleSubmit,
    handleDelete,
  };
}

