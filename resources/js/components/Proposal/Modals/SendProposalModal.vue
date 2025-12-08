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
            <label class="form-label text-teal fw-bold mb-2">Client Email(s):</label>
            <p class="text-muted small mb-3">
              To send to multiple people, separate emails with a comma (e.g., <em>client@company.com, boss@company.com</em>)
            </p>
            <input
                v-model="email"
                type="text"
                class="form-control border-0 border-bottom rounded-0 p-0 py-2"
                style="border-bottom: 2px solid #17a2b8 !important; font-size: 1rem; background-color: transparent;"
                placeholder="Enter client email address(es)"
                @keyup.enter="saveForm"
            >
        </div>
      </div>

      <!-- Modal Footer -->
      <div class="modal-footer p-4 bg-light border-top d-flex gap-3">
        <button class="btn btn-teal text-white px-4 py-2 fw-bold" :disabled="loading" @click="saveForm"><span v-if="loading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
  {{ loading ? 'SENDING...' : 'SEND PROPOSAL' }}</button>
        <button class="btn text-muted" @click="closeModal">CANCEL</button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'SendProposalModal',
  props: {
    isOpen: {
      type: Boolean,
      required: true
    },
     loading: {
    type: Boolean,
    default: false
  }
  },
  data() {
    return {
      email: '' // Corrected: This stores the input value
    };
  },
  methods: {
    closeModal() {
      this.$emit('close');
      this.email = ''; // Reset on close
    },
    saveForm() {
      if (!this.email) {
        alert("Please enter at least one email address.");
        return;
      }
      // Emit the 'next' event with the email string
      // This matches your parent component: @next="handleProposalSent"
      this.$emit('next', this.email);
      this.email = ''; 
    }
  }
};
</script>

<style scoped>
/* Keeping your existing styles exactly the same */
.modal-overlay {
  position: fixed; top: 0; left: 0; right: 0; bottom: 0;
  background-color: rgba(0, 0, 0, 0.6);
  display: flex; align-items: center; justify-content: center;
  z-index: 1050;
}

.modal-content {
  background-color: white; border-radius: 8px; width: 90%; max-width: 600px; /* Reduced width for better look */
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
  animation: slideIn 0.3s ease-out; display: flex; flex-direction: column;
}

.modal-header {
  background-color: #17a2b8 !important; border-radius: 8px 8px 0 0;
  padding: 1.5rem; flex-shrink: 0;
}

.modal-body {
  background-color: white; flex: 1;
}

.modal-footer {
  background-color: #f8f9fa; border-radius: 0 0 8px 8px; flex-shrink: 0;
}

.form-control:focus { box-shadow: none; outline: none; }

.btn-teal {
  background-color: #17a2b8; border: none; border-radius: 6px; transition: all 0.3s ease;
}

.btn-teal:hover { background-color: #138496; color: white !important; }
.btn-close { opacity: 0.8; }
.btn-close:hover { opacity: 1; }
</style>