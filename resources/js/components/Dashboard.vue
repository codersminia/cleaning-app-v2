<template>
  <div class="bg-light min-vh-100 py-5 px-3">
    <!-- Header Section -->
    <div class="text-center mb-5">
      <div class="mb-3">
        <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M24 4L8 16V44H40V16L24 4Z" stroke="#17A2B8" stroke-width="2" fill="none"/>
          <path d="M24 24V44" stroke="#17A2B8" stroke-width="2"/>
          <path d="M16 32H32V44H16Z" fill="#17A2B8"/>
        </svg>
      </div>
      <h1 class="fw-bold fs-1 mb-2">Home Page</h1>
      <p class="text-muted fs-6">Welcome to Clean Proposals</p>
    </div>

    <!-- Main Action Buttons -->
    <div class="container mb-5">
      <div class="row justify-content-center">
        <div class="col-12 col-md-6 col-lg-4 mb-3">
          <button class="btn btn-info text-white fw-bold text-uppercase rounded-pill py-5 w-100" @click="goToProspectsWithModal">CREATE PROPOSAL</button>
        </div>
        <div class="col-12 col-md-6 col-lg-4 mb-3">
          <button @click="goToTracking" class="btn btn-info text-white fw-bold text-uppercase rounded-pill py-5 w-100">TRACK SENT PROPOSALS</button>
        </div>
        <div class="col-12 col-md-6 col-lg-4 mb-3">
          <button @click="goToProposal" class="btn btn-info text-white fw-bold text-uppercase rounded-pill py-5 w-100">EDIT CLIENTS & PROPOSALS</button>
        </div>
      </div>
    </div>

    <!-- Additional Features Section -->
    <div class="container border border-dark">
      <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
          <div class="p-4">
            <h2 class="fw-bold fs-5 text-center mb-4">Additional Feature</h2>
            
            <!-- Changed from flex-column to responsive grid layout for horizontal display on desktop -->
            <div class="row justify-content-center g-3">
              <div class="col-12 col-md-6 col-lg-5">
                <button class="btn btn-info text-white fw-bold text-uppercase rounded-pill py-5 w-100">
                  TRY THE NEW CALCULATOR PRICE ESTIMATE
                  <span class="badge bg-transparent border border-white ms-2">BETA</span>
                </button>
              </div>
              
              <div class="col-12 col-md-6 col-lg-5">
                <button class="btn btn-info text-white fw-bold text-uppercase rounded-pill py-5 w-100">
                  HOW TO USE CLEANPROPOSALS FOR WALK THROUGH
                </button>
              </div>
              
              <div class="col-12 col-md-6 col-lg-5">
                <button class="btn btn-info text-white fw-bold text-uppercase rounded-pill py-5 w-100">
                  INVENTORY APP
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
// Dashboard component logic
const goToProspectsWithModal = () => {
  window.location.href = '/prospects?openModal=1'
}

const goToTracking = () => {
    window.location.href = '/tracking'
}

const goToProposal = () => {
    window.location.href = '/prospects?openModal=0'
}
</script>

<style scoped>
.btn-info:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(23, 162, 184, 0.3);
}

.btn-info:active {
  transform: translateY(0);
}

.btn-info{
    background-color: #20b2aa;
}

.min-vh-100 {
  min-height: 100vh;
}
</style>
