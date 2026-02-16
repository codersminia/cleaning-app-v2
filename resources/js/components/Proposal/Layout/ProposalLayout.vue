<template>
  <div class="min-vh-100 bg-light pb-5">
    <!-- Back Button -->
    <div class="ps-3 ps-md-4 pt-3 pb-2">
      <!-- <a href="/proposals" class="text-muted text-decoration-none small fw-semibold">
        <i class="bi bi-chevron-left"></i> BACK TO PROPOSALS
      </a> -->
    </div>

    <!-- Prospect Info / Loader -->
    <div class="prospect-card p-3 p-md-4 mx-3 mx-md-4 mb-3" v-if="prospect">
      <h5 class="fw-bold text-dark mb-2 fs-6 fs-md-5">Prospect: {{ prospect.company_name }}</h5>
      <p class="text-muted small mb-0">
        <span class="fw-bold">Location:</span>
        {{ [prospect.address, prospect.unit_number, prospect.city, prospect.state, prospect.zip]
          .filter(Boolean)
          .join(', ') }}
      </p>
    </div>
    
    <!-- Skeleton Loader while fetching -->
    <div v-else class="prospect-card p-3 p-md-4 mx-3 mx-md-4 mb-3 placeholder-glow">
      <h5 class="placeholder w-50"></h5>
      <p class="placeholder w-75"></p>
    </div>

    <!-- Stepper (Scrollable on Mobile) -->
    <div class="stepper-wrapper mb-4">
      <div class="stepper-container px-3 px-md-4" v-if="steps.length > 0">
        <router-link
          v-for="(step, index) in steps"
          :key="index"
          :to="{ name: step.name, params: { id: proposalId } }"
          class="stepper-step text-decoration-none"
          :class="{
            active: $route.name == step.name,
            first: index == 0,
            last: index == steps.length - 1
          }"
          :ref="el => { if ($route.name == step.name) activeStepRef = el }"
        >
          <div class="step-content">
            <span class="step-number">{{ index + 1 }}</span>
            <span class="step-label d-none d-md-inline">{{ step.label }}</span>
            <span class="step-label d-inline d-md-none">{{ step.label.split(' ')[0] }}</span>
          </div>
        </router-link>
      </div>
    </div>

    <!-- Main content -->
    <div class="p-3 p-md-4">
      <!-- Show loader if data is still fetching to prevent flash of wrong content -->
      <div v-if="loading" class="d-flex justify-content-center py-5">
        <div class="spinner-border text-info" role="status"></div>
      </div>
      <router-view v-else :proposal-id="proposalId" />
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, nextTick } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'

const route = useRoute()
const router = useRouter()
const proposalId = Number(route.params.id)

const prospect = ref(null)
const proposal = ref(null)
const loading = ref(true)

// The Full List of Steps
const allSteps = [
  { label: 'Cleaning Tasks', name: 'proposal.tasks' },
  { label: 'Special Projects', name: 'proposal.projects' },
  { label: 'Price Calculator', name: 'proposal.calculator' },
  { label: 'Finalize Proposal', name: 'proposal.finalize' },
]

const activeStepRef = ref(null)
const steps = ref([])

const fetchData = async () => {
  try {
    // 1. Fetch Data
    const response = await axios.get(`/api/proposals/${proposalId}/data-for-tasks`)
    
    prospect.value = response.data.prospect
    proposal.value = response.data.proposal 

    calculateSteps()
    
    loading.value = false

    // Scroll active step into view on mobile
    setTimeout(() => {
      if (activeStepRef.value && activeStepRef.value.$el) {
        activeStepRef.value.$el.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' })
      }
    }, 100)

  } catch (error) {
    console.error('Error fetching data:', error)
    loading.value = false
  }
}

const calculateSteps = () => {
  if (!proposal.value) return;

  const p = proposal.value;
  
  // LOGIC: Check conditions to SHOW Step 1 (Cleaning Tasks)
  // 1. Commercial + Janitorial
  const isCommercialJanitorial = 
      p.proposal_type == 'commercial' && 
      p.commercial_category == 'janitorial_cleaning';

  // 2. Residential + Cleaning Projects
  const isResidentialCleaning = 
      p.proposal_type == 'residential' && 
      p.residential_category == 'cleaning_projects';

  // Combined Condition
  const showCleaningTasks = isCommercialJanitorial || isResidentialCleaning;

  if (showCleaningTasks) {
    // SHOW ALL 4 STEPS
    steps.value = allSteps;
  } else {
    // HIDE FIRST STEP (Slice the array to skip the first item)
    // New Steps: Projects -> Calculator -> Finalize
    steps.value = allSteps.slice(1);

    // 🛡️ SECURITY REDIRECT
    // If the user manually typed the URL for 'Cleaning Tasks' but it's supposed to be hidden,
    // redirect them to the first available step (Special Projects).
    if (route.name == 'proposal.tasks') {
        router.replace({ name: 'proposal.projects', params: { id: proposalId } });
    }
  }
}

onMounted(fetchData)
</script>

<style scoped>
/* General Styles */
.bg-light {
  background-color: #f8f9fa !important;
}

/* Prospect Card */
.prospect-card {
  background: linear-gradient(135deg, #d4f1f4 0%, #b8e6ea 100%);
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

/* Stepper - Scrollable Wrapper */
.stepper-wrapper {
  width: 100%;
  overflow-x: auto;
  -webkit-overflow-scrolling: touch;
  background: white;
  border-bottom: 1px solid #dee2e6;
  scrollbar-width: none; /* Hide scrollbar Firefox */
}

.stepper-wrapper::-webkit-scrollbar {
  display: none; /* Hide scrollbar Chrome/Safari */
}

.stepper-container {
  display: flex;
  min-width: 100%;
  width: max-content;
  gap: 0;
  position: relative;
  height: 45px;
}

@media (min-width: 768px) {
  .stepper-container {
    height: 55px;
    width: 100%;
  }
}

.stepper-step {
  flex: 1;
  min-width: 100px;
  background: #e9ecef;
  color: #6c757d;
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
  cursor: pointer;
  transition: all 0.3s ease;
  clip-path: polygon(0 0, calc(100% - 15px) 0, 100% 50%, calc(100% - 15px) 100%, 0 100%, 15px 50%);
  padding: 0 10px 0 20px;
  border: none;
  outline: none;
}

@media (min-width: 768px) {
  .stepper-step {
    clip-path: polygon(0 0, calc(100% - 25px) 0, 100% 50%, calc(100% - 25px) 100%, 0 100%, 25px 50%);
    padding: 0 15px 0 35px;
    min-width: 150px;
  }
}

.stepper-step.first {
  clip-path: polygon(0 0, calc(100% - 15px) 0, 100% 50%, calc(100% - 15px) 100%, 0 100%);
  padding-left: 15px;
}

@media (min-width: 768px) {
  .stepper-step.first {
    clip-path: polygon(0 0, calc(100% - 25px) 0, 100% 50%, calc(100% - 25px) 100%, 0 100%);
    padding-left: 25px;
  }
}

.stepper-step.last {
  clip-path: polygon(0 0, 100% 0, 100% 100%, 0 100%, 15px 50%);
  padding-right: 15px;
}

@media (min-width: 768px) {
  .stepper-step.last {
    clip-path: polygon(0 0, 100% 0, 100% 100%, 0 100%, 25px 50%);
    padding-right: 25px;
  }
}

.stepper-step:not(.first) {
  margin-left: -15px;
}

@media (min-width: 768px) {
  .stepper-step:not(.first) {
    margin-left: -25px;
  }
}

.stepper-step.active {
  background: linear-gradient(135deg, #17a2b8 0%, #20c997 100%);
  color: white;
  z-index: 2;
  box-shadow: 0 4px 10px rgba(23, 162, 184, 0.2);
}

.stepper-step.active:hover {
  background: #138496;
}

.step-content {
  display: flex;
  align-items: center;
  gap: 6px;
  position: relative;
  z-index: 1;
}

@media (min-width: 768px) {
  .step-content {
    gap: 10px;
  }
}

.step-number {
  background: rgba(0, 0, 0, 0.1);
  color: currentColor;
  width: 18px;
  height: 18px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
  font-size: 10px;
  flex-shrink: 0;
}

@media (min-width: 768px) {
  .step-number {
    width: 24px;
    height: 24px;
    font-size: 13px;
  }
}

.stepper-step.active .step-number {
  background: white;
  color: #17a2b8;
}

.step-label {
  font-weight: 600;
  font-size: 11px;
  white-space: nowrap;
  letter-spacing: 0.3px;
}

@media (min-width: 768px) {
  .step-label {
    font-size: 14px;
    letter-spacing: 0.5px;
  }
}
</style>