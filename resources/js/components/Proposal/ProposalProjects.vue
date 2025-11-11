<template>
  <div class="projects-page">
    <!-- Top Navigation -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <router-link :to="{ name: 'proposal.tasks', params: { id: proposalId } }" class="btn btn-link text-decoration-none text-muted">
        <i class="bi bi-arrow-left"></i> Go Back
      </router-link>
      <h3 class="mb-0 text-dark fw-bold">PROJECTS</h3>
      <router-link :to="{ name: 'proposal.calculator', params: { id: proposalId } }" class="btn btn-link text-decoration-none text-muted">
        I'm done here next step <i class="bi bi-arrow-right"></i>
      </router-link>
    </div>
    <p class="text-center text-muted small mb-5">Create projects to fit your plan</p>

    <!-- Recurring Projects Section -->
    <div class="card shadow-sm mb-4">
      <div class="card-body">
        <div class="d-flex align-items-center mb-3">
          <i class="bi bi-folder-fill text-info fs-3 me-2"></i>
          <h4 class="card-title fw-bold mb-0">Recurring Projects</h4>
          <button
            class="btn btn-info ms-auto d-flex align-items-center py-2 px-3 rounded-pill"
            @click="openModal('recurring')"
          >
            <i class="bi bi-plus me-2 fw-bold fs-5"></i> ADD A NEW RECURRING PROJECT
          </button>
        </div>
        <p class="text-muted small mb-3">Expand all areas</p>

        <!-- Recurring Project Cards -->
        <div class="project-item p-3 mb-3 border rounded">
          <div class="d-flex align-items-center">
            <span class="project-title fw-bold text-dark flex-grow-1">Recurring Project - Carpet - Hot Water Extract</span>
            <button class="btn btn-outline-secondary btn-sm me-2">0 Notes</button>
            <span class="text-muted small me-2">Area Type:</span>
            <span class="fw-bold me-3">Whole Building</span>
            <span class="text-muted small me-2">Frequency:</span>
            <span class="fw-bold me-3">2 month</span>
            <span class="text-muted small me-2">Tasks:</span>
            <span class="fw-bold me-3">17</span>
            <button class="btn btn-link text-decoration-none text-info show-details me-2" @click="toggleProjectDetails('recurring1')">
              {{ expandedProjects.recurring1 ? 'HIDE PROJECT DETAILS' : 'SHOW PROJECT DETAILS' }}
            </button>
          </div>
          <!-- Recurring Project Details Section (Expanded) -->
          <div v-if="expandedProjects.recurring1" class="project-details mt-3 pt-3 border-top">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center">
                    <span class="text-muted small me-2">Frequency</span>
                    <select class="form-select form-select-sm w-auto me-2">
                        <option value="1">1</option>
                        <option value="2" selected>2</option>
                        <option value="3">3</option>
                    </select>
                    <span class="text-muted small me-2">Per</span>
                    <select class="form-select form-select-sm w-auto">
                        <option value="week">Week</option>
                        <option value="month" selected>Month</option>
                        <option value="year">Year</option>
                    </select>
                </div>
                <span class="fw-bold">Tasks (17)</span>
            </div>
            <div class="task-list-container">
              <div class="task-item selected">
                <div class="task-left">
                  <input type="checkbox" checked class="form-check-input me-3" />
                  <div class="task-icon"><i class="bi bi-check-circle"></i></div>
                  <div class="task-details">
                    <p class="task-description mb-0">Technicians will perform a pre-work inspection to gather photo documentation, identify potential challenges and address expected outcomes.</p>
                  </div>
                </div>
                <div class="task-right">
                    <i class="bi bi-pencil-square edit-icon"></i>
                </div>
              </div>
              <div class="task-item selected">
                <div class="task-left">
                  <input type="checkbox" checked class="form-check-input me-3" />
                  <div class="task-icon"><i class="bi bi-check-circle"></i></div>
                  <div class="task-details">
                    <p class="task-description mb-0">Predesignated furniture and other items will be moved prior to starting our work.</p>
                  </div>
                </div>
                <div class="task-right">
                    <i class="bi bi-pencil-square edit-icon"></i>
                </div>
              </div>
              <div class="task-item selected">
                <div class="task-left">
                  <input type="checkbox" checked class="form-check-input me-3" />
                  <div class="task-icon"><i class="bi bi-check-circle"></i></div>
                  <div class="task-details">
                    <p class="task-description mb-0">Slip and Fall Protection - Place safety cones and wet floors signs in appropriate areas before beginning</p>
                  </div>
                </div>
                <div class="task-right">
                    <i class="bi bi-pencil-square edit-icon"></i>
                </div>
              </div>
              <div class="task-item selected">
                <div class="task-left">
                  <input type="checkbox" checked class="form-check-input me-3" />
                  <div class="task-icon"><i class="bi bi-check-circle"></i></div>
                  <div class="task-details">
                    <p class="task-description mb-0">Remove large debris, staples, paper clips, gum etc. prior to vacuuming</p>
                  </div>
                </div>
                <div class="task-right">
                    <i class="bi bi-pencil-square edit-icon"></i>
                </div>
              </div>
              <div class="task-item selected">
                <div class="task-left">
                  <input type="checkbox" checked class="form-check-input me-3" />
                  <div class="task-icon"><i class="bi bi-check-circle"></i></div>
                  <div class="task-details">
                    <p class="task-description mb-0">Walk-off mats are used during carpet to floor transitions to ensure safety and to protect your hard surface flooring.</p>
                  </div>
                </div>
                <div class="task-right">
                    <i class="bi bi-pencil-square edit-icon"></i>
                </div>
              </div>
              <div class="task-item selected">
                <div class="task-left">
                  <input type="checkbox" checked class="form-check-input me-3" />
                  <div class="task-icon"><i class="bi bi-check-circle"></i></div>
                  <div class="task-details">
                    <p class="task-description mb-0">Pre Vacuuming is one of the most important steps in cleaning as it removes dry soil. Our techs use only the best commercial-grade-HEPA-filtered vacuum systems to remove dirt and debris deep below the surface</p>
                  </div>
                </div>
                <div class="task-right">
                    <i class="bi bi-pencil-square edit-icon"></i>
                </div>
              </div>
              <div class="task-item selected">
                <div class="task-left">
                  <input type="checkbox" checked class="form-check-input me-3" />
                  <div class="task-icon"><i class="bi bi-check-circle"></i></div>
                  <div class="task-details">
                    <p class="task-description mb-0">We apply the appropriate cleaning solution to begin the process of breaking down and emulsifying the heavy soils and to test material safety</p>
                  </div>
                </div>
                <div class="task-right">
                    <i class="bi bi-pencil-square edit-icon"></i>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="project-item p-3 mb-3 border rounded">
          <div class="d-flex align-items-center">
            <span class="project-title fw-bold text-dark flex-grow-1">Recurring Project - Sliding Soft Wash</span>
            <button class="btn btn-outline-secondary btn-sm me-2">0 Notes</button>
             
            <span class="text-muted small me-2">Area Type:</span>
            <span class="fw-bold me-3">Specific Areas</span>
            <span class="text-muted small me-2">Frequency:</span>
            <span class="fw-bold me-3">2 week</span>
            <span class="text-muted small me-2">Tasks:</span>
            <span class="fw-bold me-3">7</span>
            <button class="btn btn-link text-decoration-none text-info show-details me-2" @click="toggleProjectDetails('recurring2')">
              {{ expandedProjects.recurring2 ? 'HIDE PROJECT DETAILS' : 'SHOW PROJECT DETAILS' }}
            </button>
          </div>
          <!-- Recurring Project Details Section (Expanded) -->
          <div v-if="expandedProjects.recurring2" class="project-details mt-3 pt-3 border-top">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center">
                    <span class="text-muted small me-2">Frequency</span>
                    <select class="form-select form-select-sm w-auto me-2">
                        <option value="1">1</option>
                        <option value="2" selected>2</option>
                        <option value="3">3</option>
                    </select>
                    <span class="text-muted small me-2">Per</span>
                    <select class="form-select form-select-sm w-auto">
                        <option value="day">Day</option>
                        <option value="week" selected>Week</option>
                        <option value="month">Month</option>
                    </select>
                </div>
                <span class="fw-bold">Tasks (7)</span>
            </div>
            <div class="task-list-container">
              <div class="task-item selected">
                <div class="task-left">
                  <input type="checkbox" checked class="form-check-input me-3" />
                  <div class="task-icon"><i class="bi bi-check-circle"></i></div>
                  <div class="task-details">
                    <p class="task-description mb-0">Description for task 1 of sliding soft wash.</p>
                  </div>
                </div>
                <div class="task-right">
                    <i class="bi bi-pencil-square edit-icon"></i>
                </div>
              </div>
              <div class="task-item selected">
                <div class="task-left">
                  <input type="checkbox" checked class="form-check-input me-3" />
                  <div class="task-icon"><i class="bi bi-check-circle"></i></div>
                  <div class="task-details">
                    <p class="task-description mb-0">Description for task 2 of sliding soft wash.</p>
                  </div>
                </div>
                <div class="task-right">
                    <i class="bi bi-pencil-square edit-icon"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- One Time Projects Section -->
    <div class="card shadow-sm">
      <div class="card-body">
        <div class="d-flex align-items-center mb-3">
          <i class="bi bi-folder-fill text-success fs-3 me-2"></i>
          <h4 class="card-title fw-bold mb-0">One Time Projects</h4>
          <button
            class="btn btn-success ms-auto d-flex align-items-center py-2 px-3 rounded-pill"
            @click="openModal('one-time')"
          >
            <i class="bi bi-plus me-2 fw-bold fs-5"></i> ADD A NEW ONE-TIME PROJECT
          </button>
        </div>
        <p class="text-muted small mb-3">Expand all areas</p>

        <!-- One Time Project Cards -->
        <div class="project-item p-3 mb-3 border rounded">
          <div class="d-flex align-items-center">
            <span class="project-title fw-bold text-dark flex-grow-1">One-Time Project - Carpet - Urine Removal</span>
            <button class="btn btn-outline-secondary btn-sm me-2">0 Notes</button>
             
            <span class="text-muted small me-2">Area Type:</span>
            <span class="fw-bold me-3">Specific Areas</span>
            <span class="text-muted small me-2">Frequency:</span>
            <span class="fw-bold me-3">1 time</span>
            <span class="text-muted small me-2">Tasks:</span>
            <span class="fw-bold me-3">7</span>
            <button class="btn btn-link text-decoration-none text-info show-details me-2" @click="toggleProjectDetails('onetime1')">
              {{ expandedProjects.onetime1 ? 'HIDE PROJECT DETAILS' : 'SHOW PROJECT DETAILS' }}
            </button>
          </div>
          <!-- One Time Project Details Section (Expanded) -->
          <div v-if="expandedProjects.onetime1" class="project-details mt-3 pt-3 border-top">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center">
                    <span class="text-muted small me-2">Frequency</span>
                    <select class="form-select form-select-sm w-auto me-2" disabled>
                        <option value="1" selected>1</option>
                    </select>
                    <span class="text-muted small me-2">Per</span>
                    <input type="text" class="form-control form-control-sm w-auto" value="Time" disabled>
                </div>
                <span class="fw-bold">Tasks (7)</span>
            </div>
            <div class="task-list-container">
              <div class="task-item selected">
                <div class="task-left">
                  <input type="checkbox" checked class="form-check-input me-3" />
                  <div class="task-icon"><i class="bi bi-check-circle"></i></div>
                  <div class="task-details">
                    <p class="task-description mb-0">Description for urine removal task 1.</p>
                  </div>
                </div>
                <div class="task-right">
                    <i class="bi bi-pencil-square edit-icon"></i>
                </div>
              </div>
              <div class="task-item selected">
                <div class="task-left">
                  <input type="checkbox" checked class="form-check-input me-3" />
                  <div class="task-icon"><i class="bi bi-check-circle"></i></div>
                  <div class="task-details">
                    <p class="task-description mb-0">Description for urine removal task 2.</p>
                  </div>
                </div>
                <div class="task-right">
                    <i class="bi bi-pencil-square edit-icon"></i>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="project-item p-3 mb-3 border rounded">
          <div class="d-flex align-items-center">
            <span class="project-title fw-bold text-dark flex-grow-1">One-Time Project - Carpet - Hot Water Extract</span>
            <button class="btn btn-outline-secondary btn-sm me-2">0 Notes</button>
             
            <span class="text-muted small me-2">Area Type:</span>
            <span class="fw-bold me-3">Specific Areas</span>
            <span class="text-muted small me-2">Frequency:</span>
            <span class="fw-bold me-3">1 time</span>
            <span class="text-muted small me-2">Tasks:</span>
            <span class="fw-bold me-3">17</span>
            <button class="btn btn-link text-decoration-none text-info show-details me-2" @click="toggleProjectDetails('onetime2')">
              {{ expandedProjects.onetime2 ? 'HIDE PROJECT DETAILS' : 'SHOW PROJECT DETAILS' }}
            </button>
          </div>
          <!-- One Time Project Details Section (Expanded) -->
          <div v-if="expandedProjects.onetime2" class="project-details mt-3 pt-3 border-top">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center">
                    <span class="text-muted small me-2">Frequency</span>
                    <select class="form-select form-select-sm w-auto me-2" disabled>
                        <option value="1" selected>1</option>
                    </select>
                    <span class="text-muted small me-2">Per</span>
                    <input type="text" class="form-control form-control-sm w-auto" value="Time" disabled>
                </div>
                <span class="fw-bold">Tasks (17)</span>
            </div>
            <div class="task-list-container">
              <div class="task-item selected">
                <div class="task-left">
                  <input type="checkbox" checked class="form-check-input me-3" />
                  <div class="task-icon"><i class="bi bi-check-circle"></i></div>
                  <div class="task-details">
                    <p class="task-description mb-0">Description for one-time hot water extract task 1.</p>
                  </div>
                </div>
                <div class="task-right">
                    <i class="bi bi-pencil-square edit-icon"></i>
                </div>
              </div>
              <div class="task-item selected">
                <div class="task-left">
                  <input type="checkbox" checked class="form-check-input me-3" />
                  <div class="task-icon"><i class="bi bi-check-circle"></i></div>
                  <div class="task-details">
                    <p class="task-description mb-0">Description for one-time hot water extract task 2.</p>
                  </div>
                </div>
                <div class="task-right">
                    <i class="bi bi-pencil-square edit-icon"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <AddNewProjectModal
        :isOpen="isModalOpen"
        :projectType="projectType"
        :proposalId="proposalId"
        @close="closeModal"
        @save="handleSave"
      />
    </div>
  </div>
</template>

<script setup>
import { defineProps, ref } from 'vue';
import AddNewProjectModal from '../Modals/AddProjectModal.vue'

const props = defineProps({
  proposalId: {
    type: [String, Number],
    required: true,
  },
});

const isModalOpen = ref(false)

// 🔹 Store project type (recurring or one-time)
const projectType = ref('')

// 🔹 Open modal function
const openModal = (type) => {
  projectType.value = type
  isModalOpen.value = true
}

// 🔹 Close modal function
const closeModal = () => {
  isModalOpen.value = false
}

// 🔹 Handle Save event from modal
const handleSave = (data) => {
  console.log('New project added:', { ...data, type: projectType.value })
  // 👉 here you can later call an API or push into a local projects array
  isModalOpen.value = false
}

// State to manage the expansion of project details
const expandedProjects = ref({
  recurring1: false,
  recurring2: false,
  onetime1: false,
  onetime2: false,
});

// Function to toggle the visibility of project details
const toggleProjectDetails = (projectId) => {
  expandedProjects.value[projectId] = !expandedProjects.value[projectId];
};
</script>

<style scoped>
/* Scoped styles specific to this component */
.projects-page {
  /* Add any page-specific styling here if needed */
}

.card {
  border: none; /* Remove default card border */
}

.card-body {
  padding: 2rem;
}

.card-title {
  color: #333;
}

.btn-info {
  background-color: #17a2b8;
  border-color: #17a2b8;
  color: white;
  font-weight: 600;
  font-size: 0.85rem;
}

.btn-info:hover {
  background-color: #138496;
  border-color: #117a8b;
}

.btn-success {
  background-color: #20c997;
  border-color: #20c997;
  color: white;
  font-weight: 600;
  font-size: 0.85rem;
}

.btn-success:hover {
  background-color: #199e7d;
  border-color: #179374;
}

.project-item {
  background-color: #fff;
  border-color: #e0e0e0 !important;
  box-shadow: 0 1px 3px rgba(0,0,0,0.08);
}

.project-title {
  font-size: 0.95rem;
}

.show-details {
  font-size: 0.8rem;
  font-weight: 600;
}

/* Bi icons styling */
.bi-folder-fill.text-info {
  color: #17a2b8 !important; /* Ensure it matches the "info" color */
}

.bi-folder-fill.text-success {
  color: #20c997 !important; /* Ensure it matches the "success" color */
}

.bi-plus {
  font-size: 1.25rem;
  line-height: 1;
}

/* Custom dropdown for consistency */
.dropdown-menu {
    border-radius: 0.25rem;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.dropdown-item {
    font-size: 0.875rem;
}

/* Styles for expanded project details and tasks */
.project-details {
    background-color: #f9f9f9;
    padding: 1rem;
    border-radius: 0.25rem;
    margin-top: 1rem;
}

.task-list-container {
  margin-top: 1rem;
}

.task-item {
  display: flex;
  justify-content: space-between;
  align-items: flex-start; /* Align to top for multi-line descriptions */
  padding: 10px 15px;
  margin-bottom: 8px;
  background-color: #fff;
  border: 1px solid #e0e0e0;
  border-radius: 5px;
  box-shadow: 0 1px 2px rgba(0,0,0,0.05);
}

.task-item.selected {
  border-color: #20c997; /* Green border for selected/checked tasks */
}

.task-left {
  display: flex;
  align-items: center;
  flex-grow: 1;
}

.task-icon {
  font-size: 1.2rem;
  color: #20c997; /* Green for the checkmark icon */
  margin-right: 15px;
}

.task-details {
  flex-grow: 1;
}

.task-title {
  font-size: 0.95rem;
  font-weight: 600;
  color: #333;
}

.task-description {
  font-size: 0.8rem;
  color: #666;
}

.task-right {
    display: flex;
    align-items: center; /* Vertically center the edit icon */
    margin-left: 15px; /* Space between description and edit icon */
}

.edit-icon {
    font-size: 1.1rem;
    color: #6c757d; /* Bootstrap secondary color for consistency */
    cursor: pointer;
}

.edit-icon:hover {
    color: #5a6268;
}

/* Adjust dropdowns for frequency/per to be smaller and fit */
.form-select-sm.w-auto, .form-control-sm.w-auto {
    width: fit-content !important; /* Important to override bootstrap utility classes */
    min-width: 80px; /* Give them a minimum width */
}
</style>