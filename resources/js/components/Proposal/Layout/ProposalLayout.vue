<template>
  <div class="min-vh-100 bg-light">
    <!-- Back Button -->
    <div class="ps-4 pt-3 pb-2">
      <a href="/proposals" class="text-muted text-decoration-none small fw-semibold">
        <i class="bi bi-chevron-left"></i> BACK TO PROPOSALS
      </a>
    </div>

    <!-- Prospect Info -->
    <div class="prospect-card p-4 mx-4 mb-3" v-if="prospect">
      <h5 class="fw-bold text-dark mb-2">Prospect: {{ prospect.company_name }}</h5>
      <p class="text-muted small mb-0">
        Location:
        {{ [prospect.address, prospect.unit_number, prospect.city, prospect.state, prospect.zip]
          .filter(Boolean)
          .join(', ') }}
      </p>
    </div>
    <div v-else class="prospect-card p-4 mx-4 mb-3 placeholder-glow">
      <h5 class="placeholder w-50"></h5>
      <p class="placeholder w-75"></p>
    </div>

    <!-- Stepper -->
    <div class="stepper-container mx-4 mb-4">
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
      <router-view :proposal-id="proposalId" />
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import axios from 'axios'

const route = useRoute()
const proposalId = Number(route.params.id)
const prospect = ref(null)

const steps = [
  { label: 'Cleaning Tasks', name: 'proposal.tasks' },
  { label: 'Special Projects', name: 'proposal.projects' },
  { label: 'Price Calculator', name: 'proposal.calculator' },
  { label: 'Finalize Proposal', name: 'proposal.finalize' },
]

const fetchProspect = async () => {
  try {
    const response = await axios.get(`/api/proposals/${proposalId}/data-for-tasks`)
    prospect.value = response.data.prospect
  } catch (error) {
    console.error('Error fetching prospect:', error)
  }
}

onMounted(fetchProspect)
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
