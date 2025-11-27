<template>
  <div class="min-vh-100 bg-light">
    <!-- Back Button (Currently Commented Out based on your code) -->
    <div class="ps-4 pt-3 pb-2">
      <!-- <a href="/proposals" class="text-muted text-decoration-none small fw-semibold">
        <i class="bi bi-chevron-left"></i> BACK TO PROPOSALS
      </a> -->
    </div>

    <!-- Prospect Info / Loader -->
    <div class="prospect-card p-4 mx-4 mb-3" v-if="prospect">
      <h5 class="fw-bold text-dark mb-2">Prospect: {{ prospect.company_name }}</h5>
      <p class="text-muted small mb-0">
        Location:
        {{ [prospect.address, prospect.unit_number, prospect.city, prospect.state, prospect.zip]
          .filter(Boolean)
          .join(', ') }}
      </p>
    </div>
    
    <!-- Skeleton Loader while fetching -->
    <div v-else class="prospect-card p-4 mx-4 mb-3 placeholder-glow">
      <h5 class="placeholder w-50"></h5>
      <p class="placeholder w-75"></p>
    </div>

    <!-- Stepper -->
    <!-- Only show stepper if steps are calculated -->
    <div class="stepper-container mx-4 mb-4" v-if="steps.length > 0">
      <router-link
        v-for="(step, index) in steps"
        :key="index"
        :to="{ name: step.name, params: { id: proposalId } }"
        class="stepper-step text-decoration-none"
        :class="{
          active: $route.name === step.name,
          first: index === 0,
          last: index === steps.length - 1
        }"
      >
        <div class="step-content">
          <span class="step-number">{{ index + 1 }}.</span>
          <span class="step-label">{{ step.label }}</span>
        </div>
      </router-link>
    </div>

    <!-- Main content -->
    <div class="p-4">
      <!-- Show loader if data is still fetching to prevent flash of wrong content -->
      <div v-if="loading" class="d-flex justify-content-center py-5">
        <div class="spinner-border text-info" role="status"></div>
      </div>
      <router-view v-else :proposal-id="proposalId" />
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
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

// The Dynamic List (starts empty)
const steps = ref([])

const fetchData = async () => {
  try {
    // 1. Fetch Data
    const response = await axios.get(`/api/proposals/${proposalId}/data-for-tasks`)
    
    prospect.value = response.data.prospect
    proposal.value = response.data.proposal // Ensure your Controller sends this!

    // 2. Calculate which steps to show
    calculateSteps()
    
    // 3. Finish loading
    loading.value = false

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
    if (route.name === 'proposal.tasks') {
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
</style>