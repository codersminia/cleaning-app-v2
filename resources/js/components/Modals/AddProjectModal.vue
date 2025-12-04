<template>
  <div v-if="isOpen" class="modal-overlay" @click.self="closeModal">
    <div class="modal-dialog-custom">
      <!-- Modal Header -->
      <div class="modal-header-custom">
        <h5 class="modal-title-custom">Add New {{ projectType == 'one-time' ? 'One-Time' : 'Recurring' }} Project</h5>
        <button class="btn-close-modal" @click="closeModal">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M18 6L6 18M6 6L18 18" stroke="white" stroke-width="2" stroke-linecap="round"/>
          </svg>
        </button>
      </div>

      <!-- Modal Body -->
      <div class="modal-body-custom">
        <p class="add-project-subtitle">Add project information below</p>

        <!-- Service Type -->
        <div class="form-group mb-4">
          <label class="form-label fw-bold text-teal">Service Type <span class="text-danger">*</span></label>
          <div class="select-wrapper">
            <select class="form-control" v-model="formData.serviceType_id" :disabled="isLoading">
              <option value="" disabled>Select Service Type</option>
              <option v-for="service in availableServiceTypes" :key="service.value" :value="service.value">
                {{ service.text }}
              </option>
            </select>
            <div class="dropdown-arrow">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="6 9 12 15 18 9"></polyline>
              </svg>
            </div>
          </div>
          <small v-if="errors.serviceType_id" class="text-danger">{{ errors.serviceType_id[0] }}</small>
        </div>

        <!-- Add Specific Area Here (Multi-select) -->
        <div class="form-group mb-4">
          <label class="form-label fw-bold text-teal">Add Specific Area Here <span class="text-danger">*</span></label>
          <div class="multi-select-wrapper">
            <div class="selected-areas-display form-control" @click="toggleAreaDropdown" :class="{ 'is-disabled': isLoading }">
              <span v-if="formData.area_ids.length == 0" class="placeholder-text">Select Area(s)</span>
              <div v-else class="area-tags">
                <span v-for="areaId in formData.area_ids" :key="areaId" class="area-tag">
                  {{ getAreaName(areaId) }}
                  <button @click.stop="removeArea(areaId)" class="remove-tag-btn" :disabled="isLoading">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <line x1="18" y1="6" x2="6" y2="18"></line>
                      <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                  </button>
                </span>
              </div>
            </div>
            <button type="button" class="area-dropdown-btn" @click="toggleAreaDropdown" :disabled="isLoading">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="6 9 12 15 18 9"></polyline>
              </svg>
            </button>
            <div v-if="showAreaDropdown && !isLoading" class="area-options-dropdown">
              <label v-for="area in availableProposalAreaTypes" :key="area.value" class="dropdown-option">
                <input type="checkbox" :value="area.value" v-model="formData.area_ids">
                {{ area.text }}
              </label>
            </div>
          </div>
          <small v-if="errors.area_ids" class="text-danger">{{ errors.area_ids[0] }}</small>
        </div>

        <!-- Frequency and Per Row (Conditionally rendered) -->
        <div v-if="projectType == 'recurring'" class="row g-3 mb-4">
          <div class="col-md-6">
            <label class="form-label fw-bold text-teal">Frequency</label>
            <div class="select-wrapper">
              <select class="form-control" v-model="formData.frequency_id" :disabled="isLoading">
                <option value="" disabled>Select Frequency</option>
                <option v-for="freq in availableFrequencies" :key="freq.value" :value="freq.value">
                  {{ freq.text }}
                </option>
              </select>
              <div class="dropdown-arrow">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <polyline points="6 9 12 15 18 9"></polyline>
                </svg>
              </div>
            </div>
            <small class="form-text text-muted">Select Frequency of the project</small>
            <small v-if="errors.frequency_id" class="text-danger">{{ errors.frequency_id[0] }}</small>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-bold text-teal">Per</label>
            <div class="select-wrapper">
              <select class="form-control" v-model="formData.per" :disabled="isLoading">
                <option value="" disabled>Select Per</option>
                <option v-for="option in availablePerOptions" :key="option.value" :value="option.value">
                  {{ option.text }}
                </option>
              </select>
              <div class="dropdown-arrow">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <polyline points="6 9 12 15 18 9"></polyline>
                </svg>
              </div>
            </div>
            <small v-if="errors.per" class="text-danger">{{ errors.per[0] }}</small>
          </div>
        </div>
      </div>

      <!-- Modal Footer -->
      <div class="modal-footer-custom">
        <button class="btn btn-teal text-white fw-bold px-5 py-2" @click="handleAddProject" :disabled="isLoading">
            <span v-if="isLoading">Saving...</span>
            <span v-else-if="successMessage">{{ successMessage }}</span>
            <span v-else>ADD PROJECT</span>
        </button>
        <button class="btn btn-link text-muted fw-bold text-decoration-none" @click="closeModal" :disabled="isLoading">CANCEL</button>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'AddNewProjectModal',
  props: {
    isOpen: {
      type: Boolean,
      default: false
    },
    projectType: { // New prop to receive 'one-time' or 'recurring'
      type: String,
      required: true
    },
    proposalId: { // New prop to receive proposalId
      type: [String, Number],
      required: true,
    }
  },
  data() {
    return {
      isLoading: false,
      formData: {
        serviceType_id: '', // Changed to _id to match backend
        area_ids: [],     // Changed to _ids to match backend
        frequency_id: '', // Changed to _id and empty default for selection
        per: ''           // Empty default for selection
      },
      showAreaDropdown: false,
      availableServiceTypes: [],
      availableProposalAreaTypes: [],
      availableFrequencies: [],
      availablePerOptions: [],
      successMessage: '',
      errors: {} // To store validation errors from backend
    };
  },
  watch: {
    isOpen(newVal) {
      if (newVal) {
        this.resetForm(); // Reset form when modal opens
        this.fetchProjectModalData();
      } else {
        this.errors = {}; // Clear errors when modal closes
      }
    },
    projectType: { // Watch projectType to set default frequency/per
      immediate: true, // Run immediately on component mount
      handler(newType) {
        if (newType == 'one-time') {
          this.formData.frequency_id = 1; // Default for one-time
          this.formData.per = 'once'; // Default for one-time (ensure 'once' in enum)
        } else {
          this.formData.frequency_id = ''; // Clear for recurring to force selection
          this.formData.per = '';         // Clear for recurring to force selection
        }
      }
    }
  },
  methods: {
    getAreaName(areaId) {
      const area = this.availableProposalAreaTypes.find(a => a.value == areaId);
      return area ? area.text : `ID: ${areaId}`;
    },
    closeModal() {
      this.$emit('close');
      this.showAreaDropdown = false;
    },
    async fetchProjectModalData() {
      this.isLoading = true;
      this.errors = {}; // Clear previous errors
      try {
        const response = await axios.get('/api/project-modal-data');
        const data = response.data;
        this.availableServiceTypes = data.serviceTypes;
        this.availableProposalAreaTypes = data.proposalAreaTypes;
        this.availableFrequencies = data.frequencies;
        this.availablePerOptions = data.perOptions;

        // Set default values if not already set, or based on projectType
        if (!this.formData.serviceType_id && this.availableServiceTypes.length > 0) {
          // You might want to pre-select the first one or leave empty
          // this.formData.serviceType_id = this.availableServiceTypes[0].value;
        }
        if (this.projectType == 'recurring') {
            // Ensure frequency and per are cleared for selection in recurring
            this.formData.frequency_id = '';
            this.formData.per = '';
        } else {
            // One-time logic is handled by the watcher
        }

      } catch (error) {
        console.error('Error fetching project modal data:', error);
        alert('Failed to load project data. Please try again.');
      } finally {
        this.isLoading = false;
      }
    },
    async handleAddProject() {
      this.isLoading = true;
      this.errors = {}; // Clear previous errors

      // Client-side validation for basic required fields
      if (!this.formData.serviceType_id) {
        this.errors.serviceType_id = ['Service Type is required.'];
      }
      if (this.formData.area_ids.length == 0) {
        this.errors.area_ids = ['At least one area is required.'];
      }
      if (this.projectType == 'recurring') {
        if (!this.formData.frequency_id) {
          this.errors.frequency_id = ['Frequency is required for recurring projects.'];
        }
        if (!this.formData.per) {
          this.errors.per = ['Per is required for recurring projects.'];
        }
      }

      if (Object.keys(this.errors).length > 0) {
        this.isLoading = false;
        return;
      }

      try {
        const payload = {
          proposal_id: this.proposalId,
          serviceType_id: this.formData.serviceType_id,
          area_ids: this.formData.area_ids,
          is_recurring: this.projectType == 'recurring',
          frequency_id: this.formData.frequency_id,
          per: this.formData.per,
          // You might want to add a 'name' field to the form if you need it for the projects table
          // name: 'My new project', // Placeholder
        };

        const response = await axios.post('/api/projects', payload);
        this.$emit('save', response.data.project); // Emit the saved project data
        this.closeModal();
            this.successMessage = 'Project Added!';
            setTimeout(() => {
            this.successMessage = '';
            this.closeModal();
            }, 1200);

      } catch (error) {
        console.error('Error adding project:', error);
        if (error.response && error.response.status == 422) {
          this.errors = error.response.data.errors; // Display validation errors
        } else {
          alert('Failed to add project. Please try again.');
        }
      } finally {
        this.isLoading = false;
      }
    },
    resetForm() {
      this.formData = {
        serviceType_id: '',
        area_ids: [],
        frequency_id: '', // Reset to empty for proper selection in recurring
        per: ''           // Reset to empty for proper selection in recurring
      };
      // Re-apply one-time defaults if projectType is one-time
      if (this.projectType == 'one-time') {
        this.formData.frequency_id = 1;
        this.formData.per = 'once';
      }
      this.showAreaDropdown = false;
      this.errors = {}; // Clear errors
      this.successMessage = '';
    },
    toggleAreaDropdown() {
      if (!this.isLoading) {
        this.showAreaDropdown = !this.showAreaDropdown;
      }
    },
    removeArea(areaId) {
      if (!this.isLoading) {
        this.formData.area_ids = this.formData.area_ids.filter(
          (id) => id !== areaId
        );
      }
    }
  }
};
</script>

<style scoped>
/* Keep your existing styles as they are.
   Just make sure to add `is-disabled` class styles if you want a visual cue for disabled states. */

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
  max-width: 800px;
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
  padding: 1.25rem 2rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-shrink: 0;
}

.modal-title-custom {
  font-size: 1.25rem;
  font-weight: 600;
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
  opacity: 0.8;
  transition: opacity 0.2s ease;
}

.btn-close-modal:hover {
  opacity: 1;
}

.modal-body-custom {
  flex: 1;
  overflow-y: auto;
  padding: 1.5rem 2rem;
  background-color: #f8f9fa;
}

.add-project-subtitle {
  text-align: center;
  color: #666;
  font-size: 0.9rem;
  margin-bottom: 2rem;
}

.form-group {
  margin-bottom: 1.5rem;
}

.form-label {
  font-size: 0.85rem;
  margin-bottom: 0.4rem;
  display: block;
  color: #17a2b8;
  font-weight: 600;
}

.text-teal {
  color: #17a2b8;
}

.text-danger {
  color: #dc3545;
}

.form-control {
  border-radius: 6px;
  padding: 0.65rem 1rem;
  font-size: 0.9rem;
  border: 1px solid #ced4da;
  transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
  background-color: #fff;
  appearance: none;
  -webkit-appearance: none;
  -moz-appearance: none;
}

.form-control:focus {
  border-color: #17a2b8;
  box-shadow: 0 0 0 0.2rem rgba(23, 162, 184, 0.25);
  outline: none;
}

/* Custom dropdown arrow for standard selects */
.select-wrapper {
  position: relative;
}

.select-wrapper .form-control {
  padding-right: 2.5rem;
}

.dropdown-arrow {
  position: absolute;
  right: 1rem;
  top: 50%;
  transform: translateY(-50%);
  pointer-events: none;
  color: #6c757d;
}

/* Multi-select for Specific Areas */
.multi-select-wrapper {
  position: relative;
}

.selected-areas-display {
  min-height: 42px;
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  gap: 0.5rem;
  cursor: pointer;
  padding-right: 2.5rem;
  border: 1px solid #ced4da;
  background-color: #fff;
}

.selected-areas-display.form-control {
  padding-top: 0.5rem;
  padding-bottom: 0.5rem;
}

.placeholder-text {
  color: #6c757d;
  font-size: 0.9rem;
}

.area-tags {
  display: flex;
  flex-wrap: wrap;
  gap: 0.4rem;
}

.area-tag {
  display: inline-flex;
  align-items: center;
  background-color: #e2f3f5;
  color: #17a2b8;
  border-radius: 4px;
  padding: 0.3rem 0.6rem;
  font-size: 0.85rem;
  font-weight: 500;
  gap: 0.3rem;
  white-space: nowrap;
}

.remove-tag-btn {
  background: none;
  border: none;
  color: #17a2b8;
  cursor: pointer;
  padding: 0;
  margin-left: 0.2rem;
  opacity: 0.7;
  transition: opacity 0.2s ease;
}

.remove-tag-btn:hover {
  opacity: 1;
}

.area-dropdown-btn {
  position: absolute;
  right: 12px;
  top: 50%;
  transform: translateY(-50%);
  background: none;
  border: none;
  cursor: pointer;
  color: #6c757d;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 4px;
}

.area-options-dropdown {
  position: absolute;
  top: 100%;
  left: 0;
  right: 0;
  background-color: #fff;
  border: 1px solid #ced4da;
  border-radius: 6px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  margin-top: 0.5rem;
  z-index: 1060;
  max-height: 200px;
  overflow-y: auto;
  padding: 0.5rem 0;
}

.dropdown-option {
  display: flex;
  align-items: center;
  padding: 0.6rem 1rem;
  cursor: pointer;
  font-size: 0.9rem;
  color: #343a40;
  transition: background-color 0.2s ease;
}

.dropdown-option:hover {
  background-color: #f0f8ff;
}

.dropdown-option input[type="checkbox"] {
  margin-right: 0.8rem;
  transform: scale(1.1);
  accent-color: #17a2b8;
}

.form-text {
  display: block;
  margin-top: 0.4rem;
  font-size: 0.8rem;
  color: #6c757d;
}

.modal-footer-custom {
  padding: 1.25rem 2rem;
  background-color: white;
  border-top: 1px solid #e9ecef;
  display: flex;
  gap: 1rem;
  flex-shrink: 0;
  justify-content: flex-end;
}

.btn-teal {
  background-color: #17a2b8;
  border: none;
  border-radius: 6px;
  font-weight: 600;
  padding: 0.75rem 2.25rem;
  font-size: 0.95rem;
  transition: all 0.2s ease;
}

.btn-teal:hover {
  background-color: #138496;
  color: white !important;
  box-shadow: 0 2px 5px rgba(23, 162, 184, 0.3);
}

.btn-teal:focus {
  box-shadow: 0 0 0 0.2rem rgba(23, 162, 184, 0.5);
  outline: none;
}

.btn-link {
  color: #6c757d;
  text-decoration: none;
  cursor: pointer;
  font-weight: 600;
  padding: 0.75rem 1.5rem;
  font-size: 0.95rem;
  transition: color 0.2s ease;
}

.btn-link:hover {
  color: #343a40;
}

/* Row gutters adjustment */
.row.g-3 > .col-md-6 {
  padding-left: 0.75rem;
  padding-right: 0.75rem;
}
.row.g-3 {
  margin-left: -0.75rem;
  margin-right: -0.75rem;
}

/* Disable styles */
.form-control:disabled,
.selected-areas-display.is-disabled {
  background-color: #e9ecef;
  opacity: 0.8;
  cursor: not-allowed;
}
.area-dropdown-btn:disabled,
.remove-tag-btn:disabled {
  cursor: not-allowed;
  opacity: 0.6;
}
</style>