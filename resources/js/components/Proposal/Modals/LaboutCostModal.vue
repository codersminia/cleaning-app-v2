<template>
  <div class="modal-overlay" v-if="isOpen" @click="closeModal">
    <div class="modal-content" @click.stop>
      <!-- Modal Header -->
      <div class="modal-header bg-teal text-white d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0">Create a Labor Cost</h5>
        <button class="btn-close btn-close-white" @click="closeModal"></button>
      </div>

      <!-- Modal Body -->
      <div class="modal-body p-5">
        <!-- Labor Type Dropdown -->
        <div class="form-group-custom mb-4">
          <label class="form-label fw-bold text-teal mb-3" style="font-size: 0.9rem; display: block;">Labor</label>
          <select class="form-control-modal w-100" v-model="formData.labor">
            <option value="">Select labor type</option>
            <option value="custodians">Custodians</option>
            <option value="cleaners">Cleaners</option>
            <option value="supervisors">Supervisors</option>
          </select>
        </div>

        <!-- Three Column Row: Staff, Rate of Pay, Hours -->
        <div class="row g-4 mb-4">
          <div class="col-md-4">
            <label class="form-label fw-bold mb-2" style="font-size: 0.9rem;">Staff</label>
            <input type="number" class="form-control text-center" placeholder="0" v-model="formData.staff">
          </div>
          <div class="col-md-4">
            <label class="form-label fw-bold mb-2" style="font-size: 0.9rem;">Rate of Pay</label>
            <input type="number" class="form-control text-center" placeholder="0" v-model="formData.rateOfPay">
          </div>
          <div class="col-md-4">
            <label class="form-label fw-bold mb-2" style="font-size: 0.9rem;">Hours</label>
            <input type="number" class="form-control text-center" placeholder="0" v-model="formData.hours">
          </div>
        </div>

        <!-- Frequency and Per Row -->
        <div class="row g-4 mb-4">
          <div class="col-md-6">
            <label class="form-label fw-bold mb-2" style="font-size: 0.9rem;">Frequency</label>
            <!-- Dynamic loop 1 to 30 -->
            <select class="form-control-modal w-100" v-model.number="formData.frequency">
              <option v-for="n in 30" :key="n" :value="n">{{ n }}</option>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-bold mb-2" style="font-size: 0.9rem;">Per</label>
            <select class="form-control-modal w-100" v-model="formData.per">
              <option value="Week">Week</option>
              <option value="Month">Month</option>
              <option value="Quarter">Quarter</option>
              <option value="Year">Year</option>
            </select>
          </div>
        </div>

        <!-- Cost Summary Section (Calculated Read-Only) -->
        <div class="row g-3 p-4 rounded" style="background-color: #f0f8f9;">
          <div class="col-md-4">
            <label class="text-uppercase fw-bold text-teal" style="font-size: 0.85rem;">Cost per Clean</label>
            <div class="fw-bold mt-2" style="font-size: 1.1rem;">${{ calculatedPerClean }}</div>
          </div>
          <div class="col-md-4">
            <label class="text-uppercase fw-bold text-teal" style="font-size: 0.85rem;">Annual Cost</label>
            <div class="fw-bold mt-2" style="font-size: 1.1rem;">${{ calculatedAnnual }}</div>
          </div>
          <div class="col-md-4">
            <label class="text-uppercase fw-bold text-teal" style="font-size: 0.85rem;">Monthly Cost</label>
            <div class="fw-bold mt-2" style="font-size: 1.1rem;">${{ calculatedMonthly }}</div>
          </div>
        </div>
      </div>
      <!-- Modal Footer -->
      <div class="modal-footer p-4 bg-light border-top d-flex gap-3">
        <button class="btn btn-teal text-white px-4 py-2 fw-bold" @click="saveForm">SAVE</button>
        <button class="btn btn-link text-muted" @click="closeModal">CANCEL</button>
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
        frequency: 1,
        per: 'Week'
      }
    };
  },
  computed: {
    // 1. Base Calculation: Staff * Rate * Hours
    calculatedPerClean() {
      const val = (parseFloat(this.formData.staff) || 0) * 
                  (parseFloat(this.formData.rateOfPay) || 0) * 
                  (parseFloat(this.formData.hours) || 0);
      return val.toFixed(2);
    },
    // 2. Monthly Calculation based on "Per" logic
    calculatedMonthly() {
      const perClean = parseFloat(this.calculatedPerClean);
      const freq = parseFloat(this.formData.frequency) || 0;
      const type = this.formData.per;
      let monthly = 0;

      if (type == 'Week') {
        // Freq * 4.333
        monthly = perClean * (freq * 4.333);
      } else if (type == 'Month') {
        // Freq (No scaling)
        monthly = perClean * freq;
      } else if (type == 'Quarter') {
        // Freq / 3
        monthly = perClean * (freq / 3);
      } else if (type == 'Year') {
        // Freq / 12
        monthly = perClean * (freq / 12);
      }

      return monthly.toFixed(2);
    },
    // 3. Annual Calculation: Monthly * 12
    calculatedAnnual() {
      return (parseFloat(this.calculatedMonthly) * 12).toFixed(2);
    }
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
        labor: '',
        staff: 0,
        rateOfPay: 0,
        hours: 0,
        frequency: 1,
        per: 'Week'
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

.form-control-modal {
  border: 2px solid #e9ecef;
  border-radius: 24px;
  padding: 0.75rem 1.25rem;
  font-size: 1rem;
  transition: all 0.3s ease;
}

.form-control-modal:focus {
  border-color: #17a2b8;
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
