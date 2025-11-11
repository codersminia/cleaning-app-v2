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
    
    <!-- Loading State -->
    <div v-if="isLoading" class="text-center py-5">
        <div class="spinner-border text-info" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <p class="text-muted mt-2">Fetching projects and tasks...</p>
    </div>

    <!-- 💥 No Projects State (Matches the image) 💥 -->
    <div v-if="!isLoading && !hasProjects" class="text-center py-5 no-projects-container">
        <div class="folder-icon-wrapper mx-auto mb-4">
            <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="#17a2b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-folder">
                <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
            </svg>
        </div>
        <h2 class="fw-bold mb-3">Create Your Projects</h2>
        <p class="text-muted mb-5">You can choose type of project: Recurring or One-Time</p>
        <div class="d-flex justify-content-center gap-3">
            <button
                class="btn btn-info btn-lg d-flex align-items-center py-3 px-4 rounded"
                @click="openModal('recurring')"
            >
                <i class="bi bi-arrow-repeat me-2 fw-bold fs-5"></i> ADD A FIRST RECURRING PROJECT
            </button>
            <button
                class="btn btn-success btn-lg d-flex align-items-center py-3 px-4 rounded"
                @click="openModal('one-time')"
            >
                <i class="bi bi-1-circle me-2 fw-bold fs-5"></i> ADD A FIRST ONE-TIME PROJECT
            </button>
        </div>
    </div>


    <!-- 🟢 Projects Exist State 🟢 -->
    <div v-else-if="!isLoading" class="projects-list">
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

                <!-- Recurring Project Cards (Dynamic) -->
                <div v-for="project in recurringProjects" :key="project.id" class="project-item p-3 mb-3 border rounded">
                    <div class="d-flex align-items-center">
                        <!-- Project Title (Name) -->
                        <span class="project-title fw-bold text-dark flex-grow-1">{{ project.name }}</span>
                        <button class="btn btn-outline-secondary btn-sm me-2">0 Notes</button>
                        
                        <!-- Area Type -->
                        <span class="text-muted small me-2">Area Type:</span>
                        <span class="fw-bold me-3">Special Areas</span>
                        
                        <!-- Frequency -->
                        <span class="text-muted small me-2">Frequency:</span>
                        <span class="fw-bold me-3">{{ project.frequency_id }} {{ project.per }}</span>
                        
                        <!-- Tasks -->
                        <span class="text-muted small me-2">Tasks:</span>
                        <span class="fw-bold me-3">{{ project.total_tasks }}</span>
                        
                        <!-- Toggle Details Button -->
                        <button class="btn btn-link text-decoration-none text-info show-details me-2" @click="toggleProjectDetails(project.id)">
                          {{ expandedProjects[project.id] ? 'HIDE PROJECT DETAILS' : 'SHOW PROJECT DETAILS' }}
                        </button>
                    </div>
                    
                    <!-- Recurring Project Details Section (Dynamic Expanded) -->
                    <div v-if="expandedProjects[project.id]" class="project-details mt-3 pt-3 border-top">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center">
                                <span class="text-muted small me-2">Frequency</span>
                                <!-- Frequency Dropdown -->
                                <select class="form-select form-select-sm w-auto me-2" v-model="project.frequency_id">
                                    <option v-for="i in 30" :key="i" :value="i">{{ i }}</option>
                                </select>
                                <span class="text-muted small me-2">Per</span>
                                <!-- Per Dropdown -->
                                <select class="form-select form-select-sm w-auto" v-model="project.per">
                                    <option value="week">Week</option>
                                    <option value="month">Month</option>
                                    <option value="quarter">Quarter</option>
                                    <option value="year">Year</option>
                                </select>
                            </div>
                            <span class="fw-bold">Tasks ({{ project.total_tasks }})</span>
                        </div>
                        <div class="task-list-container">
                            <!-- Dynamic Task List -->
                            <div v-for="task in availableTasks" :key="task.id" class="task-item">
                                <div class="task-left">
                                    <input type="checkbox" class="form-check-input me-3" :id="task.id" />
                                    <div class="task-icon"><i class="bi bi-check-circle"></i></div>
                                    <div class="task-details">
                                        <p class="task-description mb-0">{{ task.description }}</p>
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

                <!-- One Time Project Cards (Dynamic) -->
                <div v-for="project in oneTimeProjects" :key="project.id" class="project-item p-3 mb-3 border rounded">
                    <div class="d-flex align-items-center">
                        <!-- Project Title (Name) -->
                        <span class="project-title fw-bold text-dark flex-grow-1">{{ project.name }}</span>
                        <button class="btn btn-outline-secondary btn-sm me-2">0 Notes</button>
                        
                        <!-- Area Type -->
                        <span class="text-muted small me-2">Area Type:</span>
                        <span class="fw-bold me-3">Special Areas</span>
                        
                        <!-- Frequency (1 time) -->
                        <span class="text-muted small me-2">Frequency:</span>
                        <span class="fw-bold me-3">1 time</span>
                        
                        <!-- Tasks -->
                        <span class="text-muted small me-2">Tasks:</span>
                        <span class="fw-bold me-3">{{ project.total_tasks }}</span>
                        
                        <!-- Toggle Details Button -->
                        <button class="btn btn-link text-decoration-none text-info show-details me-2" @click="toggleProjectDetails(project.id)">
                            {{ expandedProjects[project.id] ? 'HIDE PROJECT DETAILS' : 'SHOW PROJECT DETAILS' }}
                        </button>
                    </div>

                    <!-- One Time Project Details Section (Dynamic Expanded) -->
                    <div v-if="expandedProjects[project.id]" class="project-details mt-3 pt-3 border-top">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center">
                                <span class="text-muted small me-2">Frequency</span>
                                <select class="form-select form-select-sm w-auto me-2" disabled>
                                    <option :value="project.frequency_id" selected>{{ project.frequency_id }}</option>
                                </select>
                                <span class="text-muted small me-2">Per</span>
                                <input type="text" class="form-control form-control-sm w-auto" value="Time" disabled>
                            </div>
                            <span class="fw-bold">Tasks ({{ project.total_tasks }})</span>
                        </div>
                        <div class="task-list-container">
                            <!-- Dynamic Task List -->
                            <div v-for="task in availableTasks" :key="task.id" class="task-item">
                                <div class="task-left">
                                    <input type="checkbox" class="form-check-input me-3" :id="task.id" />
                                    <div class="task-icon"><i class="bi bi-check-circle"></i></div>
                                    <div class="task-details">
                                        <p class="task-description mb-0">{{ task.description }}</p>
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
    </div>


    <AddNewProjectModal
      :isOpen="isModalOpen"
      :projectType="projectType"
      :proposalId="proposalId"
      @close="closeModal"
      @save="handleSave"
    />
  </div>
</template>

<script setup>
import { defineProps, ref, computed, onMounted } from 'vue';
import AddNewProjectModal from '../Modals/AddProjectModal.vue'
import axios from 'axios'; // Import axios

const props = defineProps({
  proposalId: {
    type: [String, Number],
    required: true,
  },
});

// State
const isLoading = ref(true); // New loading state
const isModalOpen = ref(false);
const projectType = ref('');
const recurringProjects = ref([]); // Store fetched recurring projects
const oneTimeProjects = ref([]);   // Store fetched one-time projects
const availableTasks = ref([]);    // Store all available tasks
const expandedProjects = ref({}); // State to manage the expansion of project details

// Computed
const hasProjects = computed(() => {
  return recurringProjects.value.length > 0 || oneTimeProjects.value.length > 0;
});

// Methods

// Function to fetch data from the backend
const fetchProjectsAndTasks = async () => {
    isLoading.value = true;
    try {
        const response = await axios.get(`/api/proposals/${props.proposalId}/projects-and-tasks`);
        const data = response.data;
        
        // Populate reactive data
        recurringProjects.value = data.recurringProjects || [];
        oneTimeProjects.value = data.oneTimeProjects || [];
        availableTasks.value = data.availableTasks || [];

        // Clear and re-initialize expandedProjects based on new IDs
        expandedProjects.value = {};
        [...recurringProjects.value, ...oneTimeProjects.value].forEach(project => {
            expandedProjects.value[project.id] = false;
        });

    } catch (error) {
        console.error('Failed to fetch projects and tasks:', error);
        // Optional: show a user-friendly error message
    } finally {
        isLoading.value = false;
    }
};

// Open modal function
const openModal = (type) => {
  projectType.value = type
  isModalOpen.value = true
}

// Close modal function
const closeModal = () => {
  isModalOpen.value = false
}

// Handle Save event from modal (refetches data)
const handleSave = (newProject) => {
  console.log('New project added:', newProject);
  isModalOpen.value = false;
  // Re-fetch all projects to update the list with the new project
  fetchProjectsAndTasks(); 
}

// Function to toggle the visibility of project details
const toggleProjectDetails = (projectId) => {
  expandedProjects.value[projectId] = !expandedProjects.value[projectId];
};

// Lifecycle Hook
onMounted(() => {
    fetchProjectsAndTasks();
});
</script>

<style scoped>
/* Scoped styles specific to this component */
.projects-page {
  /* Add any page-specific styling here if needed */
}

/* Custom styles for the "No Projects" state (from the image) */
.no-projects-container {
    max-width: 600px; /* Constrain the width for better presentation */
    margin-left: auto;
    margin-right: auto;
}

.folder-icon-wrapper {
    position: relative;
    width: 60px;
    height: 60px;
    /* Add background/effect to mimic the image's style */
}

.btn-lg {
    font-size: 1rem;
    padding: 0.75rem 1.5rem;
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
  color: #17a2b8 !important;
}

.bi-folder-fill.text-success {
  color: #20c997 !important;
}

.bi-plus {
  font-size: 1.25rem;
  line-height: 1;
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
  align-items: flex-start;
  padding: 10px 15px;
  margin-bottom: 8px;
  background-color: #fff;
  border: 1px solid #e0e0e0;
  border-radius: 5px;
  box-shadow: 0 1px 2px rgba(0,0,0,0.05);
}

.task-item.selected {
  border-color: #20c997;
}

.task-left {
  display: flex;
  align-items: center;
  flex-grow: 1;
}

.task-icon {
  font-size: 1.2rem;
  color: #20c997;
  margin-right: 15px;
}

.task-details {
  flex-grow: 1;
}

.task-description {
  font-size: 0.8rem;
  color: #666;
}

.task-right {
    display: flex;
    align-items: center;
    margin-left: 15px;
}

.edit-icon {
    font-size: 1.1rem;
    color: #6c757d;
    cursor: pointer;
}

.edit-icon:hover {
    color: #5a6268;
}

/* Adjust dropdowns for frequency/per to be smaller and fit */
.form-select-sm.w-auto, .form-control-sm.w-auto {
    width: fit-content !important;
    min-width: 80px;
}
</style>