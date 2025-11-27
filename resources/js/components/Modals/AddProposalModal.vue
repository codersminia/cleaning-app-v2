<template>
  <div v-if="isOpen" class="modal-overlay">
    <div class="modal-backdrop"></div>

    <div class="modal-container">
      <!-- Header -->
      <div class="modal-header-custom">
        <h5 class="m-0 text-white fw-bold">Add a New Proposal</h5>
        <div class="d-flex align-items-center gap-3">
          <button class="btn btn-outline-light rounded-pill px-3 py-2 text-white fw-bold">
            Your Saved Templates <span class="ms-2">▼</span>
          </button>
          <button class="btn-close-custom" @click="closeModal">×</button>
        </div>
      </div>

      <!-- Body -->
      <div class="modal-body-custom">
        <div v-if="loading" class="text-center py-5">
          <div class="spinner-border text-info" role="status"></div>
          <p class="mt-3 text-muted">Loading data...</p>
        </div>

        <form v-else @submit.prevent="submitForm">
          <!-- Proposal Name -->
          <div class="mb-5">
            <label class="text-danger fw-bold mb-2">Proposal Name</label>
            <input
              type="text"
              v-model="form.proposal_name"
              class="form-control-custom"
              placeholder="Enter proposal name"
              maxlength="25"
            />
            <div class="d-flex justify-content-between mt-2">
              <small v-if="!form.proposal_name && submitted" class="text-danger">
                Please enter proposal name
              </small>
              <small class="text-muted">{{ form.proposal_name.length }} / 25</small>
            </div>
          </div>

          <!-- Type Selection -->
          <div class="d-flex gap-4 mb-5 align-items-center">
            <label class="d-flex align-items-center gap-2 cursor-pointer">
              <input type="radio" value="commercial" v-model="form.type" class="form-check-input" />
              <span>Commercial</span>
            </label>
            <label class="d-flex align-items-center gap-2 cursor-pointer">
              <input type="radio" value="residential" v-model="form.type" class="form-check-input" />
              <span>Residential</span>
            </label>
          </div>

          <!-- Facility (only for commercial) -->
          <div v-if="form.type == 'commercial'" class="mb-5">
            <label class="fw-bold mb-2 d-block">Pick Facility</label>
            <select v-model="form.facility_id" class="form-control-custom">
              <option value="">Select Facility</option>
              <option v-for="facility in facilities" :key="facility.id" :value="facility.id">
                {{ facility.name }}
              </option>
            </select>
            <small v-if="!form.facility_id && submitted" class="text-danger d-block mt-2">
              Please select a facility
            </small>
          </div>

          <!-- Category Selection -->
          <div class="mb-5">
            <label class="fw-bold mb-2 d-block">Category</label>
            <div class="d-flex flex-wrap gap-4 mb-3">
              <template v-if="form.type == 'commercial'">
                <label class="d-flex align-items-center gap-2 cursor-pointer">
                  <input type="radio" value="janitorial_projects" v-model="form.category" class="form-check-input" />
                  <span>Janitorial & Projects</span>
                </label>
                <label class="d-flex align-items-center gap-2 cursor-pointer">
                  <input type="radio" value="construction_cleaning" v-model="form.category" class="form-check-input" />
                  <span>Construction Cleaning</span>
                </label>
                <label class="d-flex align-items-center gap-2 cursor-pointer">
                  <input type="radio" value="projects_only" v-model="form.category" class="form-check-input" />
                  <span>Projects Only</span>
                </label>
              </template>

              <template v-else>
                <label class="d-flex align-items-center gap-2 cursor-pointer">
                  <input type="radio" value="cleaning_projects" v-model="form.category" class="form-check-input" />
                  <span>Cleaning & Projects</span>
                </label>
                <label class="d-flex align-items-center gap-2 cursor-pointer">
                  <input type="radio" value="construction_cleaning" v-model="form.category" class="form-check-input" />
                  <span>Construction Cleaning</span>
                </label>
                <label class="d-flex align-items-center gap-2 cursor-pointer">
                  <input type="radio" value="projects_only" v-model="form.category" class="form-check-input" />
                  <span>Projects Only</span>
                </label>
              </template>
            </div>
          </div>

          <!-- CASES -->
          <!-- Janitorial/ Cleaning & Projects -->
          <div
            v-if="
              form.category == 'janitorial_projects' ||
              form.category == 'cleaning_projects'
            "
            class="mb-5"
          >            

            <label class="fw-bold mb-2 d-block">Frequency</label>
            <select v-model="form.frequency" class="form-control-custom">
              <option value="">Select Frequency</option>
              <option v-for="freq in frequencies" :key="freq.id" :value="freq.label">
                {{ freq.label }}
              </option>
            </select>

            <small v-if="submitted && errors.frequency" class="text-danger d-block mt-2">
              {{ errors.frequency }}
            </small>
            <!-- Weekdays -->
            <div v-if="showWeekdays" class="mt-4">
              <label class="fw-bold mb-3 d-block">Select Days</label>
              <div class="d-flex gap-2 flex-wrap mb-2">
                <label v-for="day in days" :key="day.value" class="day-checkbox">
                  <input type="checkbox" v-model="form.selectedDays" :value="day.value" />
                  <span class="day-label">{{ day.label }}</span>
                </label>
              </div>
              <small
                v-if="submitted && form.selectedDays.length !== requiredDays"
                class="text-danger d-block"
              >
                Please select Days and Days must be equal to frequency
              </small>
            </div>
          </div>

          <div v-if="form.category == 'construction_cleaning'" class="mb-5">
            <label class="fw-bold mb-2 d-block">Select Phase</label>
            <div class="d-flex flex-wrap gap-3">
              <label class="d-flex align-items-center gap-2 cursor-pointer">
                <input type="radio" value="phase_1" v-model="form.phase" class="form-check-input" />
                <span>Phase 1 Rough Clean</span>
              </label>
              <label class="d-flex align-items-center gap-2 cursor-pointer">
                <input type="radio" value="phase_2" v-model="form.phase" class="form-check-input" />
                <span>Phase 2 Final Clean</span>
              </label>
              <label class="d-flex align-items-center gap-2 cursor-pointer">
                <input type="radio" value="phase_3" v-model="form.phase" class="form-check-input" />
                <span>Phase 3 Touch Up Clean</span>
              </label>
              <label class="d-flex align-items-center gap-2 cursor-pointer">
                <input type="radio" value="all_in_one" v-model="form.phase" class="form-check-input" />
                <span>All 3 Phases in one Cleaning Project</span>
              </label>
            </div>

            <!-- inline validation message -->
            <small v-if="submitted && errors.phase" class="text-danger d-block mt-2">
              {{ errors.phase }}
            </small>
          </div>

          <!-- Projects Only -->
          <div v-if="form.category === 'projects_only'" class="alert alert-info mt-4">
            <p class="m-0">
              <strong>Projects are special jobs.</strong><br />
              Example: Carpet Cleaning, Window Washing, Scrubbing Floors.
            </p>
          </div>

          <!-- Buttons -->
          <div class="d-flex gap-3 mt-5">
            <button type="submit" class="btn btn-info text-white fw-bold px-4 py-2">
              ADD PROPOSAL
            </button>
            <button type="button" class="btn text-muted fw-bold" @click="closeModal">CLOSE</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    isOpen: Boolean,
    facilities: Array,
    frequencies: Array,
    weekdays: Array,
    prospectId: {
      type: Number,
      required: true
    }
  },
  data() {
    return {
      loading: false,
      submitted: false,
      form: {
        proposal_name: '',
        type: 'commercial',
        facility_id: '',
        category: 'janitorial_projects',
        frequency: '',
        phase: '',
        selectedDays: []
      },
      errors: {} // 🆕 store validation errors
    };
  },
  computed: {
    days() {
      return this.weekdays.map(day => ({
        label: day.short_name,
        value: day.id // ✅ use numeric id instead of short_name
      }));
    },
    showWeekdays() {
      return !this.form.frequency || this.form.frequency.includes('Week');
    },
    requiredDays() {
      const match = this.form.frequency.match(/(\d+)/);
      return match ? parseInt(match[1]) : 0;
    }
  },
  watch: {
    'form.type'(val) {
      this.form.facility_id = '';
      this.form.category = val == 'commercial' ? 'janitorial_projects' : 'cleaning_projects';
      this.form.frequency = '';
      this.form.phase = '';
      this.form.selectedDays = [];
      this.errors = {};
    }
  },
  methods: {
    validateForm() {
      this.errors = {};

      if (!this.form.proposal_name.trim()) {
        this.errors.proposal_name = 'Proposal name is required.';
      }

      if (!this.form.type) {
        this.errors.type = 'Please select a proposal type.';
      }

      if (this.form.type == 'commercial' && !this.form.facility_id) {
        this.errors.facility_id = 'Please select a facility.';
      }

      if (
        this.form.category == 'janitorial_projects' ||
        this.form.category == 'cleaning_projects'
      ) {
        if (!this.form.frequency) {
          this.errors.frequency = 'Please select a frequency.';
        } else if (this.showWeekdays) {
          if (this.form.selectedDays.length === 0) {
            this.errors.selectedDays = 'Please select at least one weekday.';
          } else if (this.form.selectedDays.length !== this.requiredDays) {
            this.errors.selectedDays = 'Selected days must match the frequency.';
          }
        }
      }

      if (this.form.category == 'construction_cleaning' && !this.form.phase) {
        this.errors.phase = 'Please select a cleaning phase.';
      }

      return Object.keys(this.errors).length === 0;
    },

    async submitForm() {
      this.submitted = true;

      if (!this.validateForm()) {
        return; // ❌ Stop if validation fails
      }

      const payload = {
        ...this.form,
        prospect_id: this.prospectId
      };

      try {
        this.loading = true;

        const res = await axios.post('/api/proposals', payload);
        const proposal = res.data.proposal;
          setTimeout(() => {
            this.closeModal();
            let nextRoute = 'tasks'; // Default to step 1

            const isCommercialJanitorial = 
                proposal.proposal_type == 'commercial' && 
                proposal.commercial_category == 'janitorial_cleaning'; // Note: Your DB might save it as 'janitorial_projects' based on your radio value
            
            const isResidentialCleaning = 
                proposal.proposal_type == 'residential' && 
                proposal.residential_category == 'cleaning_projects';

            // If it DOES NOT match the "Show Step 1" conditions, skip to 'projects'
            if (!isCommercialJanitorial && !isResidentialCleaning) {
                nextRoute = 'projects';
            }

            // Redirect
            window.location.href = `/proposals/${proposal.id}/${nextRoute}`;

          }, 200);
      } catch (err) {
        console.error(err);
        alert('❌ Failed to create proposal.');
      } finally {
        this.loading = false;
      }
    },

    closeModal() {
      this.$emit('close');
      this.errors = {};
      this.submitted = false;
    }
  }
};
</script>




<style scoped>
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: 1050;
}

.modal-backdrop {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  z-index: 0;
}

.modal-container {
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  background: #fff;
  width: 90%;
  max-width: 900px;
  border-radius: 8px;
  /* overflow-y: auto; */
  z-index: 1051;
  max-height: 90vh;
  display: flex;
  flex-direction: column;
}

.modal-header-custom {
  background-color: #17a2b8;
  padding: 20px 30px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.btn-close-custom {
  background: none;
  border: none;
  color: white;
  font-size: 28px;
  cursor: pointer;
  padding: 0;
  width: 30px;
  height: 30px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.modal-body-custom {
  padding: 40px;
  overflow-y: auto;
  flex: 1;
}

.form-control-custom {
  width: 100%;
  border: none;
  border-bottom: 2px solid #ccc;
  padding: 8px 0;
  outline: none;
  font-size: 14px;
}

.form-control-custom:focus {
  border-bottom-color: #17a2b8;
}

.form-check-input {
  width: 20px;
  height: 20px;
  border: 2px solid #ccc;
  border-radius: 50%;
  cursor: pointer;
  accent-color: #17a2b8;
}

.form-check-input:checked {
  background-color: #17a2b8;
  border-color: #17a2b8;
}

.cursor-pointer {
  cursor: pointer;
}

.day-checkbox {
  display: flex;
  align-items: center;
  gap: 6px;
  cursor: pointer;
}

.day-checkbox input[type="checkbox"] {
  width: 24px;
  height: 24px;
  border: 2px solid #ccc;
  border-radius: 4px;
  cursor: pointer;
  accent-color: #17a2b8;
}

.day-label {
  font-size: 14px;
  user-select: none;
}
</style>
