<template>
  <div v-if="isOpen" class="modal-overlay" @click.self="closeModal">
    <div class="modal-dialog-custom">
      <!-- Modal Header -->
      <div class="modal-header-custom">
        <h5 class="modal-title-custom">Create a Project Cost</h5>
        <button class="btn-close-modal" @click="closeModal">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M18 6L6 18M6 6L18 18" stroke="white" stroke-width="2" stroke-linecap="round"/>
          </svg>
        </button>
      </div>

      <!-- Modal Body -->
      <div class="modal-body-custom">
        <!-- Labor Section -->
        <div class="form-group mb-4">
          <label class="form-label fw-bold text-teal" style="font-size: 0.9rem; margin-bottom: 0.5rem; display: block;">Labor</label>
          <select class="form-control form-select" style="border-radius: 24px; border: 2px solid #dee2e6; padding: 0.75rem 1.25rem;">
            <option>Custodians</option>
            <option>Cleaners</option>
            <option>Supervisors</option>
          </select>
        </div>

        <!-- Staff, Rate of Pay, Hours Row -->
        <div class="row g-3 mb-4">
          <div class="col-md-4">
            <label class="form-label fw-bold">Staff</label>
            <input type="number" class="form-control border-0 border-bottom" placeholder="0" v-model="formData.staff">
          </div>
          <div class="col-md-4">
            <label class="form-label fw-bold">Rate of Pay</label>
            <input type="number" class="form-control border-0 border-bottom" placeholder="0" v-model="formData.ratePay">
          </div>
          <div class="col-md-4">
            <label class="form-label fw-bold">Hours</label>
            <input type="number" class="form-control border-0 border-bottom" placeholder="0" v-model="formData.hours">
          </div>
        </div>
      </div>

      <!-- Modal Footer -->
      <div class="modal-footer-custom">
        <button class="btn btn-teal text-white fw-bold px-5 py-2" @click="handleSave">SAVE</button>
        <button class="btn btn-link text-muted fw-bold text-decoration-none" @click="closeModal">CANCEL</button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'CreateProjectCostModal',
  props: {
    isOpen: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      formData: {
        labor: 'Custodians',
        staff: 0,
        ratePay: 0,
        hours: 0
      }
    };
  },
  methods: {
    closeModal() {
      this.$emit('close');
    },
    handleSave() {
      this.$emit('save', this.formData);
      this.resetForm();
    },
    resetForm() {
      this.formData = {
        labor: 'Custodians',
        staff: 0,
        ratePay: 0,
        hours: 0
      };
    }
  }
};
</script>

<style scoped>
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0, 0, 0, 0.6);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1050;
}

.modal-dialog-custom {
  width: 90%;
  max-width: 900px;
  background-color: white;
  border-radius: 8px;
  box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
  display: flex;
  flex-direction: column;
  overflow: hidden;
}

.modal-header-custom {
  background-color: #17a2b8;
  color: white;
  padding: 1.5rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-shrink: 0;
}

.modal-title-custom {
  font-size: 1.25rem;
  font-weight: bold;
  margin: 0;
}

.btn-close-modal {
  background: none;
  border: none;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 0;
  width: 32px;
  height: 32px;
}

.modal-body-custom {
  flex: 1;
  overflow-y: auto;
  padding: 2rem;
  background-color: #f8f9fa;
}

.form-group {
  margin-bottom: 1.5rem;
}

.form-label {
  font-size: 0.9rem;
  margin-bottom: 0.5rem;
  display: block;
}

.form-control {
  border-radius: 6px;
  padding: 0.75rem 1rem;
  font-size: 0.95rem;
  transition: all 0.3s ease;
}

.form-control:focus {
  border-color: #17a2b8;
  box-shadow: none;
  outline: none;
}

.text-teal {
  color: #17a2b8;
}

.cost-summary-box {
  background-color: #d4f1f3;
  padding: 1.5rem;
  border-radius: 8px;
  margin-top: 1.5rem;
}

.cost-value {
  font-size: 1.25rem;
  font-weight: bold;
  margin: 0.75rem 0 0 0;
  color: #17a2b8;
}

.modal-footer-custom {
  padding: 1.5rem 2rem;
  background-color: white;
  border-top: 1px solid #dee2e6;
  display: flex;
  gap: 1rem;
  flex-shrink: 0;
}

.btn-teal {
  background-color: #17a2b8;
  border: none;
  border-radius: 6px;
  transition: all 0.3s ease;
}

.btn-teal:hover {
  background-color: #138496;
  color: white !important;
}

.btn-teal:focus {
  box-shadow: none;
  outline: none;
}
</style>
