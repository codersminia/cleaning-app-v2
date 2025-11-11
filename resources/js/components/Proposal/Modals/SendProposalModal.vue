<template>
  <div class="modal-overlay" v-if="isOpen" @click="closeModal">
    <div class="modal-content" @click.stop>
      <!-- Modal Header -->
      <div class="modal-header bg-teal text-white d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0">Send Your Proposal</h5>
        <button class="btn-close btn-close-white" @click="closeModal"></button>
      </div>

      <!-- Modal Body -->
      <div class="modal-body p-5">
        <div class="form-group mb-0">
            <label class="form-label text-teal fw-bold mb-3">Client Email:</label>
            <input
                v-model="email"
                type="email"
                class="form-control border-0 border-bottom rounded-0 p-0 py-2"
                style="border-bottom: 2px solid #17a2b8 !important; font-size: 1rem;background-color: transparent;"
                placeholder="Enter client email address"
            >
        </div>
      </div>

      <!-- Modal Footer -->
      <div class="modal-footer p-4 bg-light border-top d-flex gap-3">
        <button class="btn btn-teal text-white px-4 py-2 fw-bold" @click="saveForm">SAVE</button>
        <button class="btn text-muted" @click="closeModal">CANCEL</button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'CreateLaborCostModal',
  props: {
    isOpen: {
      type: Boolean,
      required: true
    }
  },
  data() {
    return {
      formData: {
        labor: '',
        staff: 0,
        rateOfPay: 0,
        hours: 0,
        frequency: '',
        per: ''
      }
    };
  },
  methods: {
    closeModal() {
      this.$emit('close');
      this.resetForm();
    },
    saveForm() {
      console.log('Labor Cost Saved:', this.formData);
      this.$emit('save', this.formData);
      this.resetForm();
    },
    resetForm() {
      this.formData = {

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

.modal-content {
  background-color: white;
  border-radius: 8px;
  width: 90%;
  max-width: 900px;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
  animation: slideIn 0.3s ease-out;
  display: flex;
  flex-direction: column;
  max-height: 90vh;
}

.modal-header {
  background-color: #17a2b8 !important;
  border-radius: 8px 8px 0 0;
  padding: 1.5rem;
  flex-shrink: 0;
}

.modal-body {
  background-color: white;
  overflow-y: auto;
  flex: 1;
  min-height: 0;
}

.modal-footer {
  background-color: #f8f9fa;
  border-radius: 0 0 8px 8px;
  flex-shrink: 0;
}

.form-control:focus {
  box-shadow: none;
  outline: none;
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

.btn-close {
  opacity: 0.8;
}

.btn-close:hover {
  opacity: 1;
}
</style>
