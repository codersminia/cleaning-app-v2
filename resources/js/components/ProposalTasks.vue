<template>
  <div class="min-vh-100 bg-light">
    <!-- Back Button -->
    <div class="ps-4 pt-3 pb-2">
      <a href="#" class="text-muted text-decoration-none small fw-semibold">
        <i class="bi bi-chevron-left"></i> BACK TO PROPOSALS
      </a>
    </div>

    <!-- Prospect Info Card -->
    <div class="prospect-card p-4 mx-4 mb-3" v-if="prospect">
      <h5 class="fw-bold text-dark mb-2">Prospect: {{ prospect.company_name }}</h5>
      <p class="text-muted small mb-0">
        Location: {{ prospect.address,prospect.unit_number,prospect.city,prospect.state,prospect.zip }}
        <!-- Adjust based on your actual prospect object structure -->
      </p>
    </div>
    <div v-else class="prospect-card p-4 mx-4 mb-3 placeholder-glow">
      <h5 class="placeholder w-50"></h5>
      <p class="placeholder w-75"></p>
    </div>

    <!-- Progress Stepper -->
    <div class="stepper-container mx-4 mb-4">
      <div
        v-for="(step, index) in steps"
        :key="index"
        class="stepper-step"
        :class="{
          'active': currentStep === index + 1,
          'first': index === 0,
          'last': index === steps.length - 1
        }"
        @click="currentStep = index + 1"
      >
        <div class="step-content">
          <span class="step-number">{{ index + 1 }}.</span>
          <span class="step-label">{{ step }}</span>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="main-content-wrapper mx-4 mb-4">
      <div class="text-center mb-4 pt-4">
        <h6 class="fw-bold text-dark mb-1">ADD AREAS & CLEANING</h6>
        <h6 class="fw-bold text-dark mb-3">TASKS BELOW BEFORE CONTINUING</h6>
        <div class="d-flex justify-content-center align-items-center gap-3">
          <span class="badge-selected">{{ selectedAreasData.length }} AREAS SELECTED</span>
          <a href="#" class="text-info text-decoration-none small fw-semibold">
            Im done here next step →
          </a>
        </div>
      </div>

      <!-- Area Builder Section -->
      <div class="area-builder-card p-4 mb-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <button class="btn-add-area">
            <i class="bi bi-grid-3x3-gap me-2"></i>ADD CUSTOM AREA
          </button>

          <div class="text-center">
            <div class="building-icon-wrapper">
              <i class="bi bi-building"></i>
            </div>
            <h6 class="fw-bold mt-2 mb-0">Area Builder</h6>
          </div>

          <div class="view-options">
            <button
              @click="toggleView"
              class="btn-view-toggle"
            >
              {{ isCardView ? 'Show List View' : 'Show Card View' }}
            </button>
            <div class="global-checkboxes">
              <label class="checkbox-label">
                <input
                  type="checkbox"
                  v-model="globalCarpet"
                  class="form-check-input"
                />
                <span>Carpet</span>
              </label>
              <span class="separator">|</span>
              <label class="checkbox-label">
                <input
                  type="checkbox"
                  v-model="globalHardFloor"
                  class="form-check-input"
                />
                <span>Hard Floor</span>
              </label>
            </div>
          </div>
        </div>

        <!-- Area List -->
        <div class="areas-list" v-if="allAreaTypes.length > 0">
          <div v-for="areaType in allAreaTypes" :key="areaType.id" class="area-wrapper mb-3">
            <!-- Card View - Expanded Area Card -->
            <div
              v-if="isCardView && isAreaSelected(areaType.id)"
              class="area-card-expanded"
            >
              <div class="area-header">
                <div class="area-header-left">
                  <input
                    type="checkbox"
                    class="form-check-input me-3"
                    :checked="isAreaSelected(areaType.id)"
                    @change="toggleArea(areaType)"
                  />
                  <div class="area-info">
                    <h6 class="area-title mb-0">{{ areaType.name }}</h6>
                    <small class="area-subtitle">{{ getTaskCount(areaType.id) }} task selected</small>
                  </div>
                </div>

                <div class="area-header-center">
                  <button class="action-btn">
                    <i class="bi bi-file-text"></i>
                    <span>{{ getAreaNotes(areaType.id) ? '1 Note' : '0 Notes' }}</span>
                  </button>
                  <button class="action-btn">
                    <i class="bi bi-files"></i>
                    <span>Make a Copy</span>
                  </button>
                </div>

                <div class="area-header-right">
                  <div class="area-checkboxes">
                    <label class="area-checkbox-label">
                      <input
                        type="checkbox"
                        :checked="getAreaProp(areaType.id, 'carpet')"
                        @change="updateAreaProp(areaType.id, 'carpet', $event.target.checked)"
                        class="form-check-input"
                      />
                      <span>Carpet</span>
                    </label>
                    <label class="area-checkbox-label">
                      <input
                        type="checkbox"
                        :checked="getAreaProp(areaType.id, 'hardfloor')"
                        @change="updateAreaProp(areaType.id, 'hardfloor', $event.target.checked)"
                        class="form-check-input"
                      />
                      <span>Hard Floor</span>
                    </label>
                  </div>
                  <div class="rooms-control">
                    <span class="rooms-label">Rooms</span>
                    <div class="counter-group">
                      <button class="counter-btn" @click="decrementRooms(areaType.id)">−</button>
                      <span class="counter-value">{{ getAreaProp(areaType.id, 'rooms') }}</span>
                      <button class="counter-btn" @click="incrementRooms(areaType.id)">+</button>
                    </div>
                  </div>
                  <button
                    class="btn-task-list"
                    :class="{ 'active': expandedTasks[areaType.id] }"
                    @click="toggleTaskList(areaType.id)"
                  >
                    {{ expandedTasks[areaType.id] ? 'Close Task List' : 'Task List' }}
                    <i :class="expandedTasks[areaType.id] ? 'bi bi-chevron-up' : 'bi bi-chevron-down'"></i>
                  </button>
                </div>
              </div>

              <!-- Task List -->
              <div v-if="expandedTasks[areaType.id]" class="task-list-container">
                <div class="task-metrics-header">
                  <div class="metric-item">
                    <div class="metric-label">
                      <i class="bi bi-clock"></i>
                      <span>Hours</span>
                    </div>
                    <input
                        type="number"
                        min="0"
                        class="metric-value"
                        :value="getAreaProp(areaType.id, 'hours')"
                        @input="updateAreaProp(areaType.id, 'hours', $event.target.value)"
                    />
                  </div>
                  <div class="metric-item">
                    <div class="metric-label">
                      <i class="bi bi-clock"></i>
                      <span>Minutes</span>
                    </div>
                    <input
                        type="number"
                        min="0"
                        class="metric-value"
                        :value="getAreaProp(areaType.id, 'minutes')"
                        @input="updateAreaProp(areaType.id, 'minutes', $event.target.value)"
                    />
                  </div>
                  <div class="metric-item">
                    <div class="metric-label">
                      <i class="bi bi-building"></i>
                      <span>Square footage (ft2)</span>
                    </div>
                    <input
                        type="number"
                        min="0"
                        class="metric-value"
                        :value="getAreaProp(areaType.id, 'sqft')"
                        @input="updateAreaProp(areaType.id, 'sqft', $event.target.value)"
                    />
                  </div>
                </div>

                <div
                  v-for="task in getAvailableTasksForArea(areaType.id)"
                  :key="task.id"
                  class="task-item"
                  :class="{ 'selected': isTaskSelected(areaType.id, task.id) }"
                >
                  <div class="task-left">
                    <input
                      type="checkbox"
                      :checked="isTaskSelected(areaType.id, task.id)"
                      @change="toggleAreaTask(areaType.id, task)"
                      class="form-check-input me-3"
                    />
                    <!-- <div class="task-icon">
                      <i :class="task.icon || 'bi bi-check-circle'"></i>
                    </div> -->
                    <div class="task-details">
                      <h6 class="task-title mb-1">{{ task.name }}</h6>
                      <p class="task-description mb-0">{{ task.description }}</p>
                    </div>
                  </div>
                  <div class="task-right">
                    <select
                      class="form-select form-select-sm"
                      :value="getTaskFrequencyId(areaType.id, task.id)"
                      @change="updateTaskFrequency(areaType.id, task.id, $event.target.value)"
                    >
                      <option
                        v-for="freq in allFrequencies"
                        :key="freq.id"
                        :value="freq.id"
                      >
                        {{ freq.label }}
                      </option>
                    </select>
                  </div>
                </div>
              </div>
            </div>

            <!-- List View - Simple Row -->
            <div
              v-else-if="!isCardView"
              class="area-card-list"
              :class="{ 'selected': isAreaSelected(areaType.id) }"
            >
              <div class="list-left">
                <input
                  type="checkbox"
                  class="form-check-input"
                  :checked="isAreaSelected(areaType.id)"
                  @change="toggleArea(areaType)"
                />
                <span class="list-label">{{ areaType.name }}</span>
              </div>

              <div v-if="isAreaSelected(areaType.id)" class="list-right">
                <div class="area-checkboxes-inline">
                  <label class="area-checkbox-label-inline">
                    <input
                      type="checkbox"
                      :checked="getAreaProp(areaType.id, 'carpet')"
                      @change="updateAreaProp(areaType.id, 'carpet', $event.target.checked)"
                      class="form-check-input"
                    />
                    <span>Carpet</span>
                  </label>
                  <label class="area-checkbox-label-inline">
                    <input
                      type="checkbox"
                      :checked="getAreaProp(areaType.id, 'hardfloor')"
                      @change="updateAreaProp(areaType.id, 'hardfloor', $event.target.checked)"
                      class="form-check-input"
                    />
                    <span>Hard Floor</span>
                  </label>
                </div>
                <div class="rooms-control-inline">
                  <span class="rooms-label">Rooms</span>
                  <div class="counter-group">
                    <button class="counter-btn" @click="decrementRooms(areaType.id)">−</button>
                    <span class="counter-value">{{ getAreaProp(areaType.id, 'rooms') }}</span>
                    <button class="counter-btn" @click="incrementRooms(areaType.id)">+</button>
                  </div>
                </div>
                <button
                  class="btn-task-list-inline"
                  :class="{ 'active': expandedTasks[areaType.id] }"
                  @click="toggleTaskList(areaType.id)"
                >
                  Task List
                  <i :class="expandedTasks[areaType.id] ? 'bi bi-chevron-up' : 'bi bi-chevron-down'"></i>
                </button>
              </div>

              <!-- Task List for List View -->
              <div v-if="isAreaSelected(areaType.id) && expandedTasks[areaType.id]" class="task-list-container-inline">
                <div class="task-metrics-header">
                  <div class="metric-item">
                    <div class="metric-label">
                      <i class="bi bi-clock"></i>
                      <span>Hours</span>
                    </div>
                    <input
                        type="number"
                        min="0"
                        class="metric-value"
                        :value="getAreaProp(areaType.id, 'hours')"
                        @input="updateAreaProp(areaType.id, 'hours', $event.target.value)"
                    />
                  </div>
                  <div class="metric-item">
                    <div class="metric-label">
                      <i class="bi bi-clock"></i>
                      <span>Minutes</span>
                    </div>
                    <input
                        type="number"
                        min="0"
                        class="metric-value"
                        :value="getAreaProp(areaType.id, 'minutes')"
                        @input="updateAreaProp(areaType.id, 'minutes', $event.target.value)"
                    />
                  </div>
                  <div class="metric-item">
                    <div class="metric-label">
                      <i class="bi bi-building"></i>
                      <span>Square footage (ft2)</span>
                    </div>
                    <input
                        type="number"
                        min="0"
                        class="metric-value"
                        :value="getAreaProp(areaType.id, 'sqft')"
                        @input="updateAreaProp(areaType.id, 'sqft', $event.target.value)"
                    />
                  </div>
                </div>

                <div
                  v-for="task in getAvailableTasksForArea(areaType.id)"
                  :key="task.id"
                  class="task-item"
                  :class="{ 'selected': isTaskSelected(areaType.id, task.id) }"
                >
                  <div class="task-left">
                    <input
                      type="checkbox"
                      :checked="isTaskSelected(areaType.id, task.id)"
                      @change="toggleAreaTask(areaType.id, task)"
                      class="form-check-input me-3"
                    />
                    <div class="task-icon">
                      <i :class="task.icon || 'bi bi-check-circle'"></i>
                    </div>
                    <div class="task-details">
                      <h6 class="task-title mb-1">{{ task.name }}</h6>
                      <p class="task-description mb-0">{{ task.description }}</p>
                    </div>
                  </div>
                  <div class="task-right">
                    <select
                      class="form-select form-select-sm"
                      :value="getTaskFrequencyId(areaType.id, task.id)"
                      @change="updateTaskFrequency(areaType.id, task.id, $event.target.value)"
                    >
                      <option
                        v-for="freq in allFrequencies"
                        :key="freq.id"
                        :value="freq.id"
                      >
                        {{ freq.label }}
                      </option>
                    </select>
                  </div>
                </div>
              </div>
            </div>

            <!-- Card View - Collapsed (Unselected) -->
            <div
              v-else-if="isCardView && !isAreaSelected(areaType.id)"
              class="area-card-collapsed"
              @click="toggleArea(areaType)"
            >
              <div class="collapsed-checkbox"></div>
              <span class="collapsed-label">{{ areaType.name }}</span>
            </div>
          </div>
        </div>
        <div v-else class="text-center py-5 text-muted">Loading areas...</div>


        <!-- Bottom Links -->
        <div class="bottom-links mt-4 pt-3">
          <a href="#" class="text-info text-decoration-none small fw-semibold">Hide Unused Areas/Tasks</a>
        </div>
      </div>

      <!-- Bottom Add Button -->
      <div class="text-end mb-4">
        <button class="btn-add-area">
          <i class="bi bi-grid-3x3-gap me-2"></i>ADD CUSTOM AREA
        </button>
      </div>
    </div>

    <!-- Footer -->
    <div class="footer-section">
      <p class="footer-text mb-3">
        Great! You've have <span class="highlight">{{ selectedAreasData.length }} areas</span>
        and selected <span class="highlight">{{ getTotalSelectedTasks() }} tasks</span>.
      </p>
      <button class="btn-projects">
        Projects <i class="bi bi-arrow-right ms-2"></i>
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted, computed, watch } from 'vue';
import axios from 'axios'; // Ensure axios is installed and imported

const props = defineProps({
  proposalId: {
    type: Number,
    required: true
  }
});

const currentStep = ref(1);
const steps = ['Cleaning Tasks', 'Special Projects', 'Price Calculator', 'Finalize Proposal'];

const isCardView = ref(true);
const globalCarpet = ref(false);
const globalHardFloor = ref(false);

const prospect = ref(null);
const allAreaTypes = ref([]); // Stores all possible area types from DB
const allTasks = ref([]); // Stores all possible tasks from DB
const allFrequencies = ref([]); // Stores all frequencies from DB

// This will store the actual ProposalArea records from the backend
const selectedAreasData = ref([]); // Array of objects, each representing a stored ProposalArea

// Reactive state for UI interactions (not directly synced with DB in real-time until update/delete)
const expandedTasks = reactive({});

// --- Data Fetching ---
const fetchData = async () => {
  try {
    const response = await axios.get(`/api/proposals/${props.proposalId}/data-for-tasks`);
    prospect.value = response.data.prospect;
    allAreaTypes.value = response.data.allAreaTypes;
    allTasks.value = response.data.allTasks;
    allFrequencies.value = response.data.allFrequencies;

    // Initialize selectedAreasData with existing data
    selectedAreasData.value = response.data.existingProposalAreas;

    // Initialize expandedTasks for any pre-selected areas that might have tasks
    selectedAreasData.value.forEach(area => {
        if (area.area_tasks && area.area_tasks.length > 0) {
            expandedTasks[area.area_type_id] = false; // By default, keep closed
        }
    });

  } catch (error) {
    console.error('Error fetching data for proposal tasks:', error);
    // Handle error, e.g., show a message to the user
  }
};

onMounted(fetchData);

// --- Computed Properties for UI Logic ---

// Helper to check if an area is selected (exists in selectedAreasData)
const isAreaSelected = (areaTypeId) => {
  return selectedAreasData.value.some(area => area.area_type_id === areaTypeId);
};

// Helper to get a specific property for a selected area
const getAreaProp = (areaTypeId, propName) => {
  const area = selectedAreasData.value.find(a => a.area_type_id === areaTypeId);
  return area ? area[propName] : null;
};

// Helper to get the number of selected tasks for a given area type
const getTaskCount = (areaTypeId) => {
  const area = selectedAreasData.value.find(a => a.area_type_id === areaTypeId);
  return area && area.area_tasks ? area.area_tasks.length : 0;
};

// Returns tasks from allTasks that are relevant for a specific area (you might want to refine this)
const getAvailableTasksForArea = (areaTypeId) => {
    // For now, let's return all available tasks.
    // In a real app, you might filter this based on areaType (e.g., 'Restrooms' have different tasks than 'Break Room')
    // or category (janitorial, construction, etc. from the initial proposal form).
    return allTasks.value;
};

// Check if a specific task is selected for a given area
const isTaskSelected = (areaTypeId, taskId) => {
  const area = selectedAreasData.value.find(a => a.area_type_id === areaTypeId);
  return area && area.area_tasks ? area.area_tasks.some(at => at.task_id === taskId) : false;
};

// Get the frequency ID for a task within an area (either custom or default)
const getTaskFrequencyId = (areaTypeId, taskId) => {
  const area = selectedAreasData.value.find(a => a.area_type_id === areaTypeId);
  if (area && area.area_tasks) {
    const areaTask = area.area_tasks.find(at => at.task_id === taskId);
    if (areaTask) {
      return areaTask.custom_frequency_id || areaTask.task.default_frequency_id;
    }
  }
  // If task not found in area or no frequency set, return default or first frequency
  const taskDetails = allTasks.value.find(t => t.id === taskId);
  return taskDetails ? taskDetails.default_frequency_id : (allFrequencies.value[0]?.id || null);
};

const getAreaNotes = (areaTypeId) => {
    const area = selectedAreasData.value.find(a => a.area_type_id === areaTypeId);
    return area ? area.notes : '';
}

// Total selected tasks for the footer
const getTotalSelectedTasks = () => {
  let total = 0;
  selectedAreasData.value.forEach(area => {
    total += area.area_tasks ? area.area_tasks.length : 0;
  });
  return total;
};


// --- Methods for UI Interactions and API Calls ---

const toggleView = () => {
  isCardView.value = !isCardView.value;
};

const toggleArea = async (areaType) => {
  const existingArea = selectedAreasData.value.find(a => a.area_type_id === areaType.id);

  if (existingArea) {
    // Area is currently selected, so deselect it (delete from DB)
    try {
      await axios.delete(`/api/proposals/${props.proposalId}/areas/${existingArea.id}`);
      selectedAreasData.value = selectedAreasData.value.filter(a => a.id !== existingArea.id);
      delete expandedTasks[areaType.id]; // Close task list if deleted
      console.log(`Area '${areaType.name}' deselected.`);
    } catch (error) {
      console.error(`Error deselecting area ${areaType.name}:`, error);
      alert('Failed to remove area.');
    }
  } else {
    // Area is not selected, so select it (store in DB)
    try {
      const payload = {
        area_type_id: areaType.id,
        rooms: 1, // Default value
        carpet: globalCarpet.value, // Apply global defaults
        hardfloor: globalHardFloor.value, // Apply global defaults
        hours: 0,
        minutes: 0,
        sqft: 0
      };
      const response = await axios.post(`/api/proposals/${props.proposalId}/areas`, payload);
      selectedAreasData.value.push(response.data.proposalArea);
      expandedTasks[areaType.id] = false; // Initialize task list as closed
      console.log(`Area '${areaType.name}' selected and stored.`);
    } catch (error) {
      console.error(`Error selecting area ${areaType.name}:`, error);
      alert('Failed to add area.');
    }
  }
};

const toggleTaskList = (areaTypeId) => {
  // Close all other task lists (accordion behavior)
  Object.keys(expandedTasks).forEach(key => {
    if (parseInt(key) !== areaTypeId) { // Ensure key is compared as number
      expandedTasks[key] = false;
    }
  });
  // Toggle current task list
  expandedTasks[areaTypeId] = !expandedTasks[areaTypeId];
};


const updateAreaProp = async (areaTypeId, propName, value) => {
  const areaIndex = selectedAreasData.value.findIndex(a => a.area_type_id === areaTypeId);
  if (areaIndex === -1) return;

  const currentArea = selectedAreasData.value[areaIndex];
  // Convert value to appropriate type if needed (e.g., numbers from inputs)
  let newValue = value;
  if (['rooms', 'hours', 'minutes', 'sqft'].includes(propName)) {
    newValue = parseInt(value) || 0;
  }
  if (['carpet', 'hardfloor'].includes(propName)) {
    newValue = Boolean(value);
  }

  // Optimistically update UI
  selectedAreasData.value[areaIndex][propName] = newValue;

  try {
    const payload = { [propName]: newValue };
    await axios.put(`/api/proposals/${props.proposalId}/areas/${currentArea.id}`, payload);
    console.log(`Area ${propName} updated for ${currentArea.areaType.name}.`);
    // Re-fetch or update the specific area in selectedAreasData if backend returns it
    // For simplicity, we assume the optimistic update is fine, or we can refresh just this area data if needed.
  } catch (error) {
    console.error(`Error updating area ${propName} for ${currentArea.areaType.name}:`, error);
    alert(`Failed to update ${propName}.`);
    // Rollback optimistic update if API fails (optional but good for robustness)
    // selectedAreasData.value[areaIndex][propName] = oldValue;
  }
};

const incrementRooms = (areaTypeId) => {
  const currentRooms = getAreaProp(areaTypeId, 'rooms');
  updateAreaProp(areaTypeId, 'rooms', currentRooms + 1);
};

const decrementRooms = (areaTypeId) => {
  const currentRooms = getAreaProp(areaTypeId, 'rooms');
  if (currentRooms > 1) {
    updateAreaProp(areaTypeId, 'rooms', currentRooms - 1);
  }
};

const toggleAreaTask = async (areaTypeId, task) => {
  const area = selectedAreasData.value.find(a => a.area_type_id === areaTypeId);
  if (!area) return;

  const existingAreaTask = area.area_tasks.find(at => at.task_id === task.id);

  if (existingAreaTask) {
    // Task is selected, deselect it (delete from DB)
    try {
      await axios.delete(`/api/proposals/${props.proposalId}/area-tasks/${existingAreaTask.id}`);
      area.area_tasks = area.area_tasks.filter(at => at.id !== existingAreaTask.id);
      console.log(`Task '${task.name}' deselected for area '${area.areaType.name}'.`);
    } catch (error) {
      console.error(`Error deselecting task ${task.name}:`, error);
      alert('Failed to remove task from area.');
    }
  } else {
    // Task is not selected, select it (store in DB)
    try {
      const payload = {
        proposal_area_id: area.id,
        task_id: task.id,
        custom_description: null, // Default, can be customized later
        custom_frequency_id: task.default_frequency_id // Use default frequency
      };
      const response = await axios.post(`/api/proposals/${props.proposalId}/area-tasks`, payload);
      area.area_tasks.push(response.data.areaTask); // Add the new areaTask object
      console.log(`Task '${task.name}' selected for area '${area.areaType.name}' and stored.`);
    } catch (error) {
      console.error(`Error selecting task ${task.name}:`, error);
      alert('Failed to add task to area.');
    }
  }
};

const updateTaskFrequency = async (areaTypeId, taskId, newFrequencyId) => {
  const area = selectedAreasData.value.find(a => a.area_type_id === areaTypeId);
  if (!area) return;

  const areaTask = area.area_tasks.find(at => at.task_id === taskId);
  if (!areaTask) return; // Should not happen if dropdown is shown for selected task

  // Optimistically update UI
  areaTask.custom_frequency_id = parseInt(newFrequencyId);

  try {
    const payload = { custom_frequency_id: parseInt(newFrequencyId) };
    await axios.put(`/api/proposals/${props.proposalId}/area-tasks/${areaTask.id}`, payload);
    console.log(`Frequency updated for task '${areaTask.task.name}'.`);
  } catch (error) {
    console.error(`Error updating frequency for task ${areaTask.task.name}:`, error);
    alert('Failed to update task frequency.');
    // Rollback optimistic update if API fails
    // areaTask.custom_frequency_id = oldFrequencyId;
  }
};

// --- Watchers ---
watch(globalCarpet, (newValue) => {
  // Apply global carpet setting to all selected areas
  selectedAreasData.value.forEach(area => {
    updateAreaProp(area.area_type_id, 'carpet', newValue);
  });
});

watch(globalHardFloor, (newValue) => {
  // Apply global hardfloor setting to all selected areas
  selectedAreasData.value.forEach(area => {
    updateAreaProp(area.area_type_id, 'hardfloor', newValue);
  });
});
</script>

<style scoped>
/* Added styles for task metrics header */
.task-metrics-header {
  display: flex;
  gap: 24px;
  padding: 16px 20px;
  background: linear-gradient(135deg, #b8e6ea 0%, #a0dce3 100%);
  border-radius: 8px;
  margin-bottom: 16px;
  align-items: center;
}

.metric-item {
  display: flex;
  align-items: center;
  gap: 12px;
}

.metric-label {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 14px;
  font-weight: 600;
  color: #2c3e50;
}

.metric-label i {
  font-size: 18px;
  color: #2c3e50;
}

.metric-value {
  background: white;
  color: #2c3e50;
  padding: 6px 16px;
  border-radius: 20px;
  font-size: 14px;
  font-weight: 600;
  min-width: 50px;
  text-align: center;
  border: 1px solid #ced4da; /* Add border for input field */
}
.metric-value:focus {
    border-color: #17a2b8;
    outline: none;
    box-shadow: 0 0 0 0.2rem rgba(23, 162, 184, 0.25);
}

/* General Styles */
.bg-light {
  background-color: #f8f9fa !important;
}

/* Prospect Card */
.prospect-card {
  background: linear-gradient(135deg, #d4f1f4 0%, #b8e6ea 100%);
  border-radius: 8px;
}

.placeholder-glow .placeholder {
    background-color: #e0e0e0;
    border-radius: 4px;
    height: 1em; /* Adjust based on text size */
    animation: placeholder-glow 2s ease-in-out infinite;
    display: inline-block; /* Ensure it takes up space */
}
@keyframes placeholder-glow {
  50% { opacity: 0.5; }
}


/* Progress Stepper - Enhanced Design */
.stepper-container {
  display: flex;
  gap: 0;
  position: relative;
  height: 70px;
}

.stepper-step {
  flex: 1;
  background: #5a6c7d;
  color: white;
  display: flex;
  align-items: center;
  position: relative;
  cursor: pointer;
  transition: all 0.3s ease;
  clip-path: polygon(0 0, calc(100% - 30px) 0, 100% 50%, calc(100% - 30px) 100%, 0 100%, 30px 50%);
}

.stepper-step.first {
  clip-path: polygon(0 0, calc(100% - 30px) 0, 100% 50%, calc(100% - 30px) 100%, 0 100%);
  padding-left: 30px;
}

.stepper-step.last {
  clip-path: polygon(0 0, 100% 0, 100% 100%, 0 100%, 30px 50%);
  padding-right: 30px;
}

.stepper-step:not(.first) {
  margin-left: -30px;
}

.stepper-step.active {
  background: linear-gradient(135deg, #17a2b8 0%, #20c997 100%);
  position: relative;
  z-index: 2;
}

.step-content {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 0 40px;
  position: relative;
  z-index: 1;
}

.step-number {
  background: rgba(255, 255, 255, 0.3);
  color: white;
  width: 32px;
  height: 32px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
  font-size: 14px;
  flex-shrink: 0;
}

.stepper-step.active .step-number {
  background: white;
  color: #17a2b8;
}

.step-label {
  font-weight: 600;
  font-size: 16px;
  white-space: nowrap;
}

/* Main Content */
.main-content-wrapper {
  background: #f5f5f5;
  border-radius: 8px;
  padding: 0 24px 24px;
}

.badge-selected {
  background: #17a2b8;
  color: white;
  padding: 6px 16px;
  border-radius: 20px;
  font-size: 13px;
  font-weight: 600;
}

/* Area Builder Card */
.area-builder-card {
  background: white;
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.btn-add-area {
  background: #17a2b8;
  color: white;
  border: none;
  padding: 10px 20px;
  border-radius: 6px;
  font-weight: 600;
  font-size: 14px;
  cursor: pointer;
  transition: all 0.2s;
  display: inline-flex;
  align-items: center;
}

.btn-add-area:hover {
  background: #138496;
  transform: translateY(-1px);
}

.building-icon-wrapper {
  width: 60px;
  height: 60px;
  background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
  border-radius: 12px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto;
}

.building-icon-wrapper i {
  font-size: 28px;
  color: white;
}

.view-options {
  display: flex;
  align-items: center;
  gap: 16px;
}

.btn-view-toggle {
  background: transparent;
  border: none;
  color: #17a2b8;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  text-decoration: underline;
  padding: 0;
  transition: color 0.2s;
}

.btn-view-toggle:hover {
  color: #138496;
}

.global-checkboxes {
  display: flex;
  align-items: center;
  gap: 12px;
}

.checkbox-label {
  display: flex;
  align-items: center;
  gap: 6px;
  cursor: pointer;
  margin: 0;
}

.checkbox-label span {
  font-size: 14px;
  font-weight: 600;
  color: #495057;
}

.separator {
  color: #ced4da;
  font-weight: 300;
}

/* Area Cards */
.areas-list {
  max-height: 600px;
  overflow-y: auto;
  padding-right: 8px;
}

.areas-list::-webkit-scrollbar {
  width: 6px;
}

.areas-list::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 10px;
}

.areas-list::-webkit-scrollbar-thumb {
  background: #17a2b8;
  border-radius: 10px;
}

/* List View Styles */
.area-card-list {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 14px 16px;
  background: #f8f9fa;
  border: 1px solid #e9ecef;
  border-radius: 8px;
  transition: all 0.2s;
  flex-wrap: wrap;
}

.area-card-list.selected {
  background: white;
  border-color: #17a2b8;
}

.list-left {
  display: flex;
  align-items: center;
  gap: 12px;
}

.list-label {
  font-size: 15px;
  font-weight: 500;
  color: #495057;
}

.list-right {
  display: flex;
  align-items: center;
  gap: 20px;
}

.area-checkboxes-inline {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 8px 16px;
  background: linear-gradient(135deg, #17a2b8 0%, #20c997 100%);
  border-radius: 6px;
}

.area-checkbox-label-inline {
  display: flex;
  align-items: center;
  gap: 6px;
  cursor: pointer;
  margin: 0;
}

.area-checkbox-label-inline span {
  font-size: 13px;
  font-weight: 600;
  color: white;
}

.area-checkbox-label-inline .form-check-input {
  background-color: white;
  border-color: white;
}

.rooms-control-inline {
  display: flex;
  align-items: center;
  gap: 12px;
}

.btn-task-list-inline {
  background: #17a2b8;
  color: white;
  border: none;
  padding: 8px 16px;
  border-radius: 6px;
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 8px;
  transition: all 0.2s;
}

.btn-task-list-inline:hover {
  background: #138496;
}

.btn-task-list-inline i {
  font-size: 12px;
}

.task-list-container-inline {
  width: 100%;
  margin-top: 12px;
  padding-top: 12px;
  border-top: 1px solid #e9ecef;
}

/* Collapsed Area Card */
.area-card-collapsed {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 14px 16px;
  background: #f8f9fa;
  border: 1px solid #e9ecef;
  border-radius: 8px;
  cursor: pointer;
  transition: all 0.2s;
}

.area-card-collapsed:hover {
  background: #e9ecef;
  border-color: #17a2b8;
}

.collapsed-checkbox {
  width: 20px;
  height: 20px;
  border: 2px solid #ced4da;
  border-radius: 4px;
  flex-shrink: 0;
}

.collapsed-label {
  font-size: 15px;
  font-weight: 500;
  color: #495057;
}

/* Expanded Area Card */
.area-card-expanded {
  background: white;
  border: 2px solid #17a2b8;
  border-radius: 12px;
  overflow: hidden;
}

.area-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px 24px;
  gap: 24px;
}

.area-header-left {
  display: flex;
  align-items: center;
  gap: 0;
  flex: 0 0 auto;
}

.area-icon {
  width: 40px;
  height: 40px;
  background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-right: 12px;
}

.area-icon i {
  font-size: 20px;
  color: white;
}

.area-info {
  display: flex;
  flex-direction: column;
}

.area-title {
  font-size: 16px;
  font-weight: 700;
  color: #212529;
  margin-bottom: 2px;
}

.area-subtitle {
  font-size: 13px;
  color: #6c757d;
}

.area-header-center {
  flex: 1;
  display: flex;
  justify-content: center;
  gap: 32px;
}

.action-btn {
  background: transparent;
  border: none;
  display: flex;
  align-items: center;
  gap: 8px;
  color: #6c757d;
  font-size: 14px;
  cursor: pointer;
  transition: color 0.2s;
}

.action-btn:hover {
  color: #17a2b8;
}

.action-btn i {
  font-size: 18px;
  color: #d0d0d0;
}

.area-header-right {
  display: flex;
  align-items: center;
  gap: 20px;
  flex: 0 0 auto;
}

.area-checkboxes {
  display: flex;
  flex-direction: column;
  gap: 6px;
  padding: 10px 16px;
  background: linear-gradient(135deg, #17a2b8 0%, #20c997 100%);
  border-radius: 6px;
}

.area-checkbox-label {
  display: flex;
  align-items: center;
  gap: 6px;
  cursor: pointer;
  margin: 0;
}

.area-checkbox-label span {
  font-size: 13px;
  font-weight: 600;
  color: white;
}

.area-checkbox-label .form-check-input {
  background-color: white;
  border-color: white;
}

.rooms-control {
  display: flex;
  align-items: center;
  gap: 12px;
}

.rooms-label {
  font-size: 14px;
  font-weight: 500;
  color: #495057;
}

.counter-group {
  display: flex;
  align-items: center;
  gap: 8px;
}

.counter-btn {
  width: 28px;
  height: 28px;
  background: #e8f4f8;
  border: none;
  border-radius: 4px;
  color: #17a2b8;
  font-size: 18px;
  font-weight: bold;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.2s;
}

.counter-btn:hover {
  background: #d0e8ed;
}

.counter-value {
  font-size: 14px;
  font-weight: 600;
  color: #212529;
  min-width: 24px;
  text-align: center;
}

.btn-task-list {
  background: #17a2b8;
  color: white;
  border: none;
  padding: 10px 18px;
  border-radius: 6px;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 8px;
  transition: all 0.2s;
}

.btn-task-list:hover {
  background: #138496;
}

.btn-task-list i {
  font-size: 12px;
}

/* Task List */
.task-list-container {
  background: #f8f9fa;
  padding: 20px 24px;
  border-top: 1px solid #e9ecef;
}

.task-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 16px 20px;
  background: white;
  border: 1px solid #e9ecef;
  border-radius: 8px;
  margin-bottom: 12px;
  transition: all 0.2s;
}

.task-item:last-child {
  margin-bottom: 0;
}

.task-item.selected {
  background: #e7f7f9;
  border-color: #17a2b8;
}

.task-left {
  display: flex;
  align-items: flex-start;
  gap: 0;
  flex: 1;
}

.task-icon {
  width: 36px;
  height: 36px;
  background: #e8f4f8;
  border-radius: 6px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-right: 12px;
  flex-shrink: 0;
}

.task-icon i {
  font-size: 18px;
  color: #17a2b8;
}

.task-details {
  flex: 1;
}

.task-title {
  font-size: 15px;
  font-weight: 600;
  color: #212529;
}

.task-description {
  font-size: 13px;
  color: #6c757d;
  line-height: 1.5;
}

.task-right {
  flex: 0 0 auto;
  margin-left: 16px;
}

.task-right .form-select {
  min-width: 120px;
  border-color: #ced4da;
  font-size: 14px;
}

/* Form Controls */
.form-check-input {
  width: 20px;
  height: 20px;
  border: 2px solid #ced4da;
  cursor: pointer;
  margin-right: 0;
}

.form-check-input:checked {
  background-color: #17a2b8;
  border-color: #17a2b8;
}

/* Bottom Links */
.bottom-links {
  border-top: 1px solid #e9ecef;
}

/* Footer */
.footer-section {
  background: #2c3e50;
  color: white;
  padding: 32px;
  text-align: center;
}

.footer-text {
  font-size: 16px;
  margin-bottom: 20px;
}

.footer-text .highlight {
  color: #17a2b8;
  font-weight: 700;
}

.btn-projects {
  background: #17a2b8;
  color: white;
  border: none;
  padding: 12px 32px;
  border-radius: 6px;
  font-size: 16px;
  font-weight: 600;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  transition: all 0.2s;
}

.btn-projects:hover {
  background: #138496;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(23, 162, 184, 0.3);
}
</style>
