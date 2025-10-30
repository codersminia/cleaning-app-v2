<template>
  <div class="min-vh-100 bg-light">
    <!-- Back Button -->
    <div class="ps-4 pt-3 pb-2">
      <a href="#" class="text-muted text-decoration-none small fw-semibold">
        <i class="bi bi-chevron-left"></i> BACK TO PROPOSALS
      </a>
    </div>

    <!-- Prospect Info Card -->
    <div class="prospect-card p-4 mx-4 mb-3">
      <h5 class="fw-bold text-dark mb-2">Prospect: Golf Castro Traders</h5>
      <p class="text-muted small mb-0">
        Location: Culpa deserunt in accusamus adiuta officia qui nobis fuga Debitis impedit cum eos similique eius, Unit 547, Id incididunt aut ipsum adipisci fugiat dolore aut, 63035
      </p>
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
          <span class="badge-selected">{{ selectedAreas.length }} AREAS SELECTED</span>
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
        <div class="areas-list">
          <div v-for="area in allAreas" :key="area" class="area-wrapper mb-3">
            <!-- Card View - Expanded Area Card -->
            <div v-if="isCardView && selectedAreas.includes(area)" class="area-card-expanded">
              <div class="area-header">
                <div class="area-header-left">
                  <input
                    type="checkbox"
                    class="form-check-input me-3"
                    :checked="true"
                    @change="toggleArea(area)"
                  />
                  <div class="area-info">
                    <h6 class="area-title mb-0">{{ area }}</h6>
                    <small class="area-subtitle">{{ getTaskCount(area) }} task selected</small>
                  </div>
                </div>

                <div class="area-header-center">
                  <button class="action-btn">
                    <i class="bi bi-file-text"></i>
                    <span>0 Notes</span>
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
                        v-model="areaCarpet[area]"
                        class="form-check-input"
                      />
                      <span>Carpet</span>
                    </label>
                    <label class="area-checkbox-label">
                      <input 
                        type="checkbox" 
                        v-model="areaHardFloor[area]"
                        class="form-check-input"
                      />
                      <span>Hard Floor</span>
                    </label>
                  </div>
                  <div class="rooms-control">
                    <span class="rooms-label">Rooms</span>
                    <div class="counter-group">
                      <button class="counter-btn" @click="decrementRooms(area)">−</button>
                      <span class="counter-value">{{ rooms[area] || 1 }}</span>
                      <button class="counter-btn" @click="incrementRooms(area)">+</button>
                    </div>
                  </div>
                  <button 
                    class="btn-task-list"
                    :class="{ 'active': expandedTasks[area] }"
                    @click="toggleTaskList(area)"
                  >
                    {{ expandedTasks[area] ? 'Close Task List' : 'Task List' }}
                    <i :class="expandedTasks[area] ? 'bi bi-chevron-up' : 'bi bi-chevron-down'"></i>
                  </button>
                </div>
              </div>

              <!-- Task List -->
              <div v-if="expandedTasks[area]" class="task-list-container">
                <!-- Added hours, mins, sqft2 fields at the top of task list -->
                <div class="task-metrics-header">
                  <div class="metric-item">
                    <div class="metric-label">
                      <i class="bi bi-clock"></i>
                      <span>Hours</span>
                    </div>
                    <input type="number" min="0" class="metric-value"></input>
                  </div>
                  <div class="metric-item">
                    <div class="metric-label">
                      <i class="bi bi-clock"></i>
                      <span>Minutes</span>
                    </div>
                    <input type="number" min="0" class="metric-value"></input>
                  </div>
                  <div class="metric-item">
                    <div class="metric-label">
                      <i class="bi bi-building"></i>
                      <span>Square footage (ft2)</span>
                    </div>
                    <input type="number" min="0" class="metric-value"></input>
                  </div>
                </div>

                <div 
                  v-for="task in tasks[area]" 
                  :key="task.id"
                  class="task-item"
                  :class="{ 'selected': task.selected }"
                >
                  <div class="task-left">
                    <input
                      type="checkbox"
                      v-model="task.selected"
                      class="form-check-input me-3"
                    />
                    <div class="task-icon">
                      <i :class="task.icon"></i>
                    </div>
                    <div class="task-details">
                      <h6 class="task-title mb-1">{{ task.title }}</h6>
                      <p class="task-description mb-0">{{ task.description }}</p>
                    </div>
                  </div>
                  <div class="task-right">
                    <select v-model="task.frequency" class="form-select form-select-sm">
                      <option>Daily</option>
                      <option>Weekly</option>
                      <option>1x Week</option>
                      <option>Monthly</option>
                      <option>Annual</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>

            <!-- List View - Simple Row -->
            <div 
              v-else-if="!isCardView"
              class="area-card-list"
              :class="{ 'selected': selectedAreas.includes(area) }"
            >
              <div class="list-left">
                <input
                  type="checkbox"
                  class="form-check-input"
                  :checked="selectedAreas.includes(area)"
                  @change="toggleArea(area)"
                />
                <span class="list-label">{{ area }}</span>
              </div>
              
              <div v-if="selectedAreas.includes(area)" class="list-right">
                <div class="area-checkboxes-inline">
                  <label class="area-checkbox-label-inline">
                    <input 
                      type="checkbox" 
                      v-model="areaCarpet[area]"
                      class="form-check-input"
                    />
                    <span>Carpet</span>
                  </label>
                  <label class="area-checkbox-label-inline">
                    <input 
                      type="checkbox" 
                      v-model="areaHardFloor[area]"
                      class="form-check-input"
                    />
                    <span>Hard Floor</span>
                  </label>
                </div>
                <div class="rooms-control-inline">
                  <span class="rooms-label">Rooms</span>
                  <div class="counter-group">
                    <button class="counter-btn" @click="decrementRooms(area)">−</button>
                    <span class="counter-value">{{ rooms[area] || 1 }}</span>
                    <button class="counter-btn" @click="incrementRooms(area)">+</button>
                  </div>
                </div>
                <button 
                  class="btn-task-list-inline"
                  :class="{ 'active': expandedTasks[area] }"
                  @click="toggleTaskList(area)"
                >
                  Task List
                  <i :class="expandedTasks[area] ? 'bi bi-chevron-up' : 'bi bi-chevron-down'"></i>
                </button>
              </div>

              <!-- Task List for List View -->
              <div v-if="selectedAreas.includes(area) && expandedTasks[area]" class="task-list-container-inline">
                <!-- Added hours, mins, sqft2 fields for list view -->
                <div class="task-metrics-header">
                  <div class="metric-item">
                    <div class="metric-label">
                      <i class="bi bi-clock"></i>
                      <span>Hours</span>
                    </div>
                    <div class="metric-value">{{ areaMetrics[area]?.hours || 0 }}</div>
                  </div>
                  <div class="metric-item">
                    <div class="metric-label">
                      <i class="bi bi-clock"></i>
                      <span>Minutes</span>
                    </div>
                    <div class="metric-value">{{ areaMetrics[area]?.mins || 0 }}</div>
                  </div>
                  <div class="metric-item">
                    <div class="metric-label">
                      <i class="bi bi-building"></i>
                      <span>Square footage (ft2)</span>
                    </div>
                    <div class="metric-value">{{ areaMetrics[area]?.sqft2 || 0 }}</div>
                  </div>
                </div>

                <div 
                  v-for="task in tasks[area]" 
                  :key="task.id"
                  class="task-item"
                  :class="{ 'selected': task.selected }"
                >
                  <div class="task-left">
                    <input
                      type="checkbox"
                      v-model="task.selected"
                      class="form-check-input me-3"
                    />
                    <div class="task-icon">
                      <i :class="task.icon"></i>
                    </div>
                    <div class="task-details">
                      <h6 class="task-title mb-1">{{ task.title }}</h6>
                      <p class="task-description mb-0">{{ task.description }}</p>
                    </div>
                  </div>
                  <div class="task-right">
                    <select v-model="task.frequency" class="form-select form-select-sm">
                      <option>Daily</option>
                      <option>Weekly</option>
                      <option>1x Week</option>
                      <option>Monthly</option>
                      <option>Annual</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>

            <!-- Card View - Collapsed (Unselected) -->
            <div 
              v-else-if="isCardView && !selectedAreas.includes(area)"
              class="area-card-collapsed"
              @click="toggleArea(area)"
            >
              <div class="collapsed-checkbox"></div>
              <span class="collapsed-label">{{ area }}</span>
            </div>
          </div>
        </div>

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
        Great! You've have <span class="highlight">{{ selectedAreas.length }} areas</span> 
        and selected <span class="highlight">{{ getTotalSelectedTasks() }} tasks</span>.
      </p>
      <button class="btn-projects">
        Projects <i class="bi bi-arrow-right ms-2"></i>
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue'

const currentStep = ref(1)
const steps = ['Cleaning Tasks', 'Special Projects', 'Price Calculator', 'Finalize Proposal']

const isCardView = ref(true)
const globalCarpet = ref(false)
const globalHardFloor = ref(false)

const allAreas = [
  'Break Room',
  'Conference Room', 
  'Enclosed Offices',
  'Entrance Area',
  'Escalator',
  'Hallways',
  'IT Room',
  'Janitor\'s Closet',
  'Lobbies',
  'Locker/Shower Rooms',
  'Open Space Offices',
  'Outdoor Space',
  'Parts Department',
  'Quiet/Nursing Room',
  'Receptionist Area',
  'Restrooms',
  'Service & Parts Reception',
  'Service Bays',
  'Service Department',
  'Service Reception Drive-Up',
  'Showroom',
  'Stairwells',
  'Storage',
  'Training Rooms',
  'Utility Room',
  'Waiting Area'
]

const selectedAreas = ref(['Break Room'])
const expandedTasks = reactive({})
const rooms = reactive({ 'Break Room': 1 })
const areaCarpet = reactive({ 'Break Room': false })
const areaHardFloor = reactive({ 'Break Room': false })

const areaMetrics = reactive({
  'Break Room': { hours: 0, mins: 0, sqft2: 0 }
})

const tasks = reactive({
  'Break Room': [
    { 
      id: 1, 
      title: 'Air Vents & Fans', 
      description: 'Remove dust from Ceiling fans, air returns and vents.', 
      frequency: 'Monthly', 
      selected: true,
      icon: 'bi bi-fan'
    },
    { 
      id: 2, 
      title: 'Clean Blinds', 
      description: 'Open blinds with moist free cleaning solution and microfiber cloths.', 
      frequency: 'Annual', 
      selected: false,
      icon: 'bi bi-window'
    },
    { 
      id: 3, 
      title: 'Desk Dusting', 
      description: 'Partner desk available surface area only to not disturb paperwork and personal property.', 
      frequency: '1x Week', 
      selected: true,
      icon: 'bi bi-brush'
    },
    { 
      id: 4, 
      title: 'High Dusting', 
      description: 'The following surfaces shall be dusted below 12 feet of height: ledges, architectural details, light fixtures and diffusers, exit signs, remove cobwebs and dust from ceilings.', 
      frequency: 'Monthly', 
      selected: false,
      icon: 'bi bi-arrow-up-circle'
    },
    { 
      id: 5, 
      title: 'Interior of Drawers and Cabinets', 
      description: 'Clean available surfaces and front area of drawers and cabinets.', 
      frequency: 'Monthly', 
      selected: true,
      icon: 'bi bi-box'
    },
    { 
      id: 6, 
      title: 'Routine Dusting', 
      description: 'Routine dusting shall be performed on an unobstructed horizontal surfaces between 3 and 8 ft of height. Personal items will not be moved.', 
      frequency: '1x Week', 
      selected: true,
      icon: 'bi bi-wind'
    },
    { 
      id: 7, 
      title: 'Sealing', 
      description: 'Wipe or vacuum couches, recliners, loveseats, benches and chairs.', 
      frequency: '1x Week', 
      selected: false,
      icon: 'bi bi-house-door'
    },
    { 
      id: 8, 
      title: 'Spot Clean Glass', 
      description: 'Spot clean interior windows, door glass and partition glass using microfiber cloths or paper towels leaving glass free of splash, smudges and streaks.', 
      frequency: '1x Week', 
      selected: true,
      icon: 'bi bi-droplet'
    },
    { 
      id: 9, 
      title: 'Tables & Counters', 
      description: 'Wipe tables and counters to remove all stains or smudges, box dishes to designated area, discard used trash.', 
      frequency: '1x Week', 
      selected: true,
      icon: 'bi bi-table'
    },
    { 
      id: 10, 
      title: 'Trash & Recycling', 
      description: 'Empty and remove trash and recycling bags, separate, and deposit into appropriate disposal containers. Replace can liners as needed.', 
      frequency: '1x Week', 
      selected: false,
      icon: 'bi bi-trash'
    },
    { 
      id: 11, 
      title: 'Trash Splash', 
      description: 'Wipe trash adjacent to trash cans.', 
      frequency: '1x Week', 
      selected: true,
      icon: 'bi bi-droplet-half'
    }
  ],
  'Conference Room': [
    { 
      id: 12, 
      title: 'Vacuum Carpet', 
      description: 'Vacuum all carpeted areas thoroughly.', 
      frequency: 'Daily', 
      selected: false,
      icon: 'bi bi-wind'
    },
    { 
      id: 13, 
      title: 'Dust Surfaces', 
      description: 'Dust all horizontal surfaces including tables and chairs.', 
      frequency: 'Weekly', 
      selected: false,
      icon: 'bi bi-brush'
    }
  ],
  'Enclosed Offices': [
    { 
      id: 14, 
      title: 'Empty Trash', 
      description: 'Empty all trash bins and replace liners.', 
      frequency: 'Daily', 
      selected: false,
      icon: 'bi bi-trash'
    }
  ]
})

const toggleView = () => {
  isCardView.value = !isCardView.value
}

const toggleArea = (area) => {
  const index = selectedAreas.value.indexOf(area)
  if (index === -1) {
    selectedAreas.value.push(area)
    rooms[area] = 1
    areaCarpet[area] = false
    areaHardFloor[area] = false
    areaMetrics[area] = { hours: 0, mins: 0, sqft2: 0 }
    if (!tasks[area]) {
      tasks[area] = [
        { 
          id: Date.now(), 
          title: 'Sample Task', 
          description: 'This is a sample task for ' + area, 
          frequency: 'Weekly', 
          selected: false,
          icon: 'bi bi-check-circle'
        }
      ]
    }
  } else {
    selectedAreas.value.splice(index, 1)
    delete expandedTasks[area]
  }
}

const toggleTaskList = (area) => {
  // Close all other task lists (accordion behavior)
  Object.keys(expandedTasks).forEach(key => {
    if (key !== area) {
      expandedTasks[key] = false
    }
  })
  // Toggle current task list
  expandedTasks[area] = !expandedTasks[area]
}

const incrementRooms = (area) => {
  rooms[area] = (rooms[area] || 1) + 1
}

const decrementRooms = (area) => {
  if (rooms[area] > 1) {
    rooms[area]--
  }
}

const getTaskCount = (area) => {
  if (!tasks[area]) return 0
  return tasks[area].filter(t => t.selected).length
}

const getTotalSelectedTasks = () => {
  let total = 0
  selectedAreas.value.forEach(area => {
    if (tasks[area]) {
      total += tasks[area].filter(t => t.selected).length
    }
  })
  return total
}
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
