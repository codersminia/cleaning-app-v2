<template>
  <div class="bg-light min-vh-100 py-5 px-3">
    <!-- Header Section -->
    <div class="text-center mb-5">
      <div class="mb-3">
        <img width="120" height="120"
          src="/images/logo.png"
          alt="Logo"
        />
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
            
            <div class="row justify-content-center g-3">
              <div class="col-12 col-md-6 col-lg-5">
                <!-- TRIGGER CALCULATOR MODAL -->
                <button @click="openCalc" class="btn btn-info text-white fw-bold text-uppercase rounded-pill py-5 w-100">
                  TRY THE NEW CALCULATOR PRICE ESTIMATE
                  <span class="badge bg-transparent border border-white ms-2">BETA</span>
                </button>
              </div>
              
              <div class="col-12 col-md-6 col-lg-5">
                <button @click="isVideoOpen = true" class="btn btn-info text-white fw-bold text-uppercase rounded-pill py-5 w-100">
                  HOW TO USE CLEANPROPOSALS FOR WALK THROUGH
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- CALCULATOR MODAL OVERLAY -->
    <Teleport to="body">
      <div v-if="calcVisible" class="modal-overlay" @click.self="closeCalc">
        
        <!-- STEP 0: SELECTION (Image 1) -->
        <div v-if="calcStep === 0" class="selection-container">
          <div class="row g-4 justify-content-center">
            <div class="col-10 col-md-5">
              <div class="select-card shadow" @click="selectType('residential')">
                <div class="icon-house">
                   <svg viewBox="0 0 100 100" width="120">
                     <path d="M50 15L15 45V85H85V45L50 15Z" fill="#FFC107" />
                     <path d="M50 15L15 45L50 15Z" fill="#F06292" />
                     <path d="M50 15L85 45L50 15Z" fill="#F06292" />
                     <rect x="42" y="55" width="16" height="30" fill="#fff"/>
                   </svg>
                </div>
                <h2 class="text-secondary fw-bold">Residential</h2>
              </div>
            </div>
            <div class="col-10 col-md-5">
              <div class="select-card shadow" @click="selectType('commercial')">
                <div class="icon-shop">
                   <svg viewBox="0 0 100 100" width="120">
                     <rect x="20" y="45" width="60" height="40" fill="#E0E0E0" />
                     <rect x="55" y="55" width="15" height="30" fill="#00BCD4" />
                     <rect x="25" y="55" width="20" height="20" fill="#00BCD4" />
                     <path d="M15 45L85 45L75 30L25 30L15 45Z" fill="#F44336" />
                   </svg>
                </div>
                <h2 class="text-secondary fw-bold">Commercial</h2>
              </div>
            </div>
          </div>
        </div>

        <!-- FORM CONTAINER (Steps 1 & 2) -->
        <div v-else class="calc-form-card shadow-lg">
          
          <!-- Modal Progress Header -->
          <div class="calc-header d-flex justify-content-center align-items-center mb-4">
            <div class="step-dot" :class="{ active: currentFormStep === 1 }">1</div>
            <div class="step-line"></div>
            <div class="step-dot" :class="{ active: currentFormStep === 2 }">2</div>
          </div>
          <div class="calc-labels d-flex justify-content-center gap-5 mb-3 text-muted small fw-bold">
            <span>{{ calcType === 'residential' ? 'Home' : 'Account Info' }}</span>
            <span>{{ calcType === 'residential' ? 'Extras' : 'Cost Info' }}</span>
          </div>

          <!-- RESIDENTIAL FLOW -->
          <div v-if="calcType === 'residential'">
            <!-- Step 1: Home Info (Image 2) -->
            <div v-if="currentFormStep === 1">
              <h4 class="text-center mb-4">Please tell us about the home</h4>
              <select class="form-select mb-3 rounded-pill" v-model="resData.state">
                <option value="">What state?</option>
                <option>California</option>
                <option>Texas</option>
              </select>
              <select class="form-select mb-3 rounded-pill" v-model="resData.type">
                <option value="">Type of Cleaning</option>
                <option>Deep Clean</option>
                <option>Standard</option>
              </select>
              <select class="form-select mb-4 rounded-pill" v-model="resData.area">
                <option value="">Total area to be cleaned</option>
                <option>1000 - 1500 sq ft</option>
              </select>

              <div class="slider-group mb-3">
                <label class="small fw-bold">Number of bedrooms: {{ resData.bedrooms }}</label>
                <input type="range" class="form-range" min="0" max="10" v-model="resData.bedrooms">
              </div>
              <div class="slider-group mb-3">
                <label class="small fw-bold">No. of standard bathrooms: {{ resData.bathrooms }}</label>
                <input type="range" class="form-range" min="0" max="5" v-model="resData.bathrooms">
              </div>
              <div class="form-check form-switch d-flex justify-content-between align-items-center mb-3">
                <label class="form-check-label small fw-bold">Clean the Kitchen?</label>
                <input class="form-check-input" type="checkbox" v-model="resData.cleanKitchen">
              </div>
            </div>

            <!-- Step 2: Extras (Image 3) -->
            <div v-if="currentFormStep === 2">
              <h4 class="text-center mb-4">Select extra services</h4>
              <div class="mb-3 text-center">
                <span v-for="room in ['DINING ROOM', 'PLAY ROOM', 'LAUNDRY', 'GYM']" :key="room" 
                      class="badge border text-dark m-1 rounded-pill p-2" style="cursor:pointer">
                  {{ room }} +
                </span>
              </div>
              <div class="price-display text-center my-4">
                <p class="mb-0 text-muted">Estimated Price to charge:</p>
                <h2 class="fw-bold">$7.00</h2>
              </div>
              <div class="alert alert-light border text-center small">
                <strong>This information Will not Save.</strong><br>
                To use this calculator in a Proposal, create a new Proposal.
              </div>
            </div>
          </div>

          <!-- COMMERCIAL FLOW -->
          <div v-if="calcType === 'commercial'">
            <!-- Step 1: Account Info (Image 4) -->
            <div v-if="currentFormStep === 1">
              <select class="form-select mb-3 rounded-pill" v-model="commData.buildingType">
                <option value="">Building Types</option>
                <option>Office</option>
                <option>Medical</option>
              </select>
              <input type="text" class="form-control mb-3 rounded-pill" placeholder="How Many Sq Ft" v-model="commData.sqft">
              <select class="form-select mb-4 rounded-pill" v-model="commData.frequency">
                <option value="">Frequency</option>
                <option>Weekly</option>
              </select>
              <div class="slider-group mb-4">
                <label class="small fw-bold">Difficulty</label>
                <input type="range" class="form-range" v-model="commData.difficulty">
                <div class="d-flex justify-content-between small text-muted">
                  <span>Easy</span><span>Medium</span><span>Difficult</span>
                </div>
              </div>
            </div>

            <!-- Step 2: Cost Info (Image 5) -->
            <div v-if="currentFormStep === 2">
              <select class="form-select mb-3 rounded-pill" v-model="commData.employees">
                <option>Will you use Employees? No</option>
              </select>
              <div class="row g-2 mb-3">
                <div class="col-6"><input type="text" class="form-control rounded-pill" placeholder="Supplies 5%"></div>
                <div class="col-6"><input type="text" class="form-control rounded-pill" placeholder="Overhead 5%"></div>
              </div>
              <div class="profit-bar mb-4">
                <label class="small fw-bold">Profit Margin: 40%</label>
                <div class="gradient-slider-bg"></div>
                <input type="range" class="form-range" v-model="commData.profit">
              </div>
              <div class="results-table">
                <div class="d-flex justify-content-between mb-2"><span>Monthly Profit:</span> <input type="text" readonly class="form-control-sm border-0 bg-light w-25" value="$0.00"></div>
                <div class="d-flex justify-content-between mb-2"><span>Cleaning Time:</span> <input type="text" readonly class="form-control-sm border-0 bg-light w-25" value="---"></div>
                <div class="d-flex justify-content-between mb-2"><span>Monthly Price:</span> <input type="text" readonly class="form-control-sm border-0 bg-light w-25" value="$0.00"></div>
              </div>
            </div>
          </div>

          <!-- Footer Actions -->
          <div class="calc-footer mt-4 d-flex gap-2">
            <button v-if="currentFormStep === 1" @click="currentFormStep = 2" class="btn btn-teal w-100 py-2 fw-bold text-white rounded-3">Next</button>
            <button v-if="currentFormStep === 2" @click="closeCalc" class="btn btn-teal w-100 py-2 fw-bold text-white rounded-3">Done</button>
            <button @click="closeCalc" class="btn btn-link text-muted text-decoration-none">Cancel</button>
          </div>

        </div>
      </div>
    </Teleport>

    <!-- VIDEO MODAL (Existing) -->
    <Teleport to="body">
      <div v-if="isVideoOpen" class="video-overlay" @click.self="isVideoOpen = false">
        <div class="video-wrapper">
          <button class="close-btn" @click="isVideoOpen = false">
            <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <line x1="18" y1="6" x2="6" y2="18"></line>
              <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
          </button>
          <div class="video-container shadow-lg">
            <iframe src="https://www.youtube.com/embed/x7ZXIDF4OtE" title="How to use Clean Proposals" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
          </div>
        </div>
      </div>
    </Teleport>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue';

const isVideoOpen = ref(false);

// CALCULATOR STATE
const calcVisible = ref(false);
const calcStep = ref(0); // 0: Selection, 1: Forms
const calcType = ref(null); // 'residential' or 'commercial'
const currentFormStep = ref(1);

// FORM DATA
const resData = reactive({
  state: '', type: '', area: '', bedrooms: 1, bathrooms: 0, cleanKitchen: false
});
const commData = reactive({
  buildingType: '', sqft: '', frequency: '', difficulty: 50, profit: 40
});

const openCalc = () => {
  calcVisible.value = true;
  calcStep.value = 0;
};

const closeCalc = () => {
  calcVisible.value = false;
  calcType.value = null;
  calcStep.value = 0;
  currentFormStep.value = 1;
};

const selectType = (type) => {
  calcType.value = type;
  calcStep.value = 1;
  currentFormStep.value = 1;
};

const goToProspectsWithModal = () => { window.location.href = '/prospects?openModal=1' }
const goToTracking = () => { window.location.href = '/tracking' }
const goToProposal = () => { window.location.href = '/prospects?openModal=0' }
</script>

<style scoped>
.btn-info { background-color: #20b2aa; border: none; }
.btn-teal { background-color: #00BFA5; border: none; }
.modal-overlay {
  position: fixed; top: 0; left: 0; width: 100vw; height: 100vh;
  background: rgba(0, 0, 0, 0.85); display: flex; justify-content: center; align-items: center; z-index: 10000; padding: 20px;
}

/* Step 0 cards */
.select-card {
  background: white; border-radius: 40px; padding: 60px 60px; text-align: center; cursor: pointer;
  transition: transform 0.2s;
}
.select-card:hover { transform: scale(1.02); }

/* Form Card */
.calc-form-card {
  background: white; width: 100%; max-width: 480px; border-radius: 12px; padding: 30px;
  max-height: 90vh; overflow-y: auto;
}

/* Progress Stepper */
.step-dot {
  width: 30px; height: 30px; border-radius: 50%; background: #ccc; color: white;
  display: flex; align-items: center; justify-content: center; font-weight: bold;
}
.step-dot.active { background: #000; }
.step-line { width: 60px; height: 2px; background: #eee; margin: 0 5px; }

/* Custom Sliders and Elements */
.form-range::-webkit-slider-thumb { background: #00BFA5; }
.gradient-slider-bg {
  height: 8px; border-radius: 4px; margin-top: 10px;
  background: linear-gradient(to right, #4CAF50, #FFC107, #F44336);
}

.video-overlay { position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(0, 0, 0, 0.9); display: flex; justify-content: center; align-items: center; z-index: 10000; }
.video-wrapper { position: relative; width: 100%; max-width: 900px; }
.close-btn { position: absolute; top: -45px; right: 0; background: none; border: none; cursor: pointer; }
.video-container { position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; border-radius: 12px; background: #000; }
.video-container iframe { position: absolute; top: 0; left: 0; width: 100%; height: 100%; }
</style>