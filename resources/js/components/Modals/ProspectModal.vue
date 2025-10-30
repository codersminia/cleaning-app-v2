<template>
  <!-- Modal Backdrop -->
  <div v-if="isOpen" class="position-fixed top-0 start-0 w-100 h-100 bg-dark" style="z-index: 1040; opacity: 0.5;"></div>

  <!-- Modal -->
  <div
    v-if="isOpen"
    class="position-fixed top-50 start-50 bg-white"
    style="width: 90%; max-width: 900px; z-index: 1050; transform: translate(-50%, -50%); max-height: 80vh; overflow-y: auto;"
  >
    <!-- Teal Header -->
    <div class="bg-info text-white p-3 d-flex justify-content-end align-items-center">
      <button
        class="btn text-white p-0"
        @click="closeModal"
        style="font-size: 28px; line-height: 1;"
      >
        ×
      </button>
    </div>

    <!-- Modal Content -->
    <div class="p-5">
      <div class="row align-items-end mb-4">
        <!-- Left Side - Dropdown -->
        <div class="col-md-8">
          <select
            v-model="selectedProspectId"
            class="form-select border-0 border-bottom border-info rounded-0"
            style="border-width: 2px !important;"
          >
            <option value="">Select from existing</option>
            <option
              v-for="prospect in prospects"
              :key="prospect.id"
              :value="prospect.id"
            >
              {{ prospect.name }}
            </option>
          </select>
        </div>

        <!-- Right Side - Button -->
        <div class="col-md-4 d-flex justify-content-end">
          <button
            @click="openAddProspectModal"
            class="btn btn-info text-white fw-bold px-4 py-2"
          >
            ADD NEW PROSPECT
          </button>
        </div>
      </div>

      <!-- Cancel Button -->
      <div class="mt-5 pt-3 border-top">
        <button class="btn text-muted p-0 fw-bold" @click="closeModal">
          CANCEL
        </button>
      </div>
    </div>
  </div>

  <!-- Add Prospect Modal -->
  <add-prospect-modal
    :isOpen="showAddProspectModal"
    @close="closeAddProspectModal"
  />
</template>

<script>
import AddProspectModal from './AddProspectModal.vue'

export default {
  name: 'ProspectModal',

  components: { AddProspectModal },

  props: {
    isOpen: {
      type: Boolean,
      required: true
    },
    prospects: {
      type: Array,
      default: () => []
    }
  },

  emits: ['close'],

  data() {
    return {
      showAddProspectModal: false,
      selectedProspectId: ''
    }
  },

  watch: {
    isOpen(newVal) {
      if (!newVal) {
        this.showAddProspectModal = false
      }
    }
  },

  methods: {
    closeModal() {
      this.$emit('close')
    },
    openAddProspectModal() {
      this.showAddProspectModal = true
    },
    closeAddProspectModal() {
      this.showAddProspectModal = false
    }
  }
}
</script>

<style scoped>
.form-select {
  background-image: none;
  padding-bottom: 0.5rem;
}

.form-select:focus {
  border-color: #17a2b8;
  box-shadow: none;
}
</style>
