<template>
  <div class="min-vh-100 bg-light py-5">
    <!-- Loading Spinner -->
    <div 
      v-if="loading" 
      class="d-flex justify-content-center align-items-center"
      style="height: 80vh;"
    >
      <div class="spinner-border text-info" role="status" style="width: 3rem; height: 3rem;">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>

    <!-- Actual Prospect Page -->
    <div v-else>
      <!-- Header Section -->
      <div class="container mb-5">

        <div class="mb-4">
            <a href="/dashboard" class="text-decoration-none text-muted d-flex align-items-center gap-2">
                <span>&lt;</span>
                <span>BACK</span>
            </a>
        </div>

        <div class="text-center mb-4">
          <div class="mb-3">
            <svg class="mx-auto text-info" width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M6 9h12M6 9V4h12v5M6 9v11h12V9M9 13h6M9 17h6M9 9v8M15 9v8"></path>
            </svg>
          </div>
          <h1 class="display-5 fw-bold mb-2">Prospects</h1>
          <p class="text-muted mb-1">Prospect contact information listed below</p>
          <p class="text-muted">Click card for details</p>
        </div>

        <!-- Filter Icon -->
        <div class="d-flex justify-content-end mb-4 position-relative">
          <button class="btn btn-link text-muted p-0" @click="showFilter = !showFilter">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
            </svg>
          </button>

          <!-- Filter dropdown menu -->
          <div v-if="showFilter" class="position-absolute top-100 end-0 mt-2 bg-white border rounded p-4" style="width: 350px; z-index: 1000;">
            <div class="mb-3">
              <div class="form-check mb-3">
                <input class="form-check-input" type="radio" name="filter" id="showClients" v-model="selectedFilter" value="clients">
                <label class="form-check-label" for="showClients">
                  <strong>Show Clients Only</strong>
                  <div class="text-muted small">These are companies that you have signed contracts with.</div>
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="filter" id="showProspects" v-model="selectedFilter" value="prospects">
                <label class="form-check-label" for="showProspects">
                  <strong>Show Prospects Only</strong>
                  <div class="text-muted small">These are companies that you hope to close a deal with.</div>
                </label>
              </div>
            </div>
            <div class="text-end">
              <button class="btn btn-link p-0 text-info text-decoration-none" @click="clearFilters">Clear Filters</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Prospects List -->
      <div class="container">
        <div class="row">
          <div class="col-12">
            <div
              v-for="prospect in prospects"
              :key="prospect.id"
              class="d-flex align-items-center bg-white mb-2 p-3 cursor-pointer"
              style="border-right: 6px solid #17A2B8; background-color: #f8f9fa;"
            >
              <h5 class="fw-bold mb-0">{{ prospect.name }}</h5>
            </div>
          </div>
        </div>
      </div>

      <!-- Prospect Modal -->
      <prospect-modal 
        :isOpen="showModal" 
        :prospects="prospects"
        @close="closeModal">
      </prospect-modal>
    </div>
  </div>
</template>


<script>
import axios from 'axios'
import ProspectModal from './Modals/ProspectModal.vue'

export default {
  components: { ProspectModal },

  data() {
    return {
      prospects: [],
      showFilter: false,
      selectedFilter: null,
      showModal: false,
      loading: true,
    }
  },

  async mounted() {
    await this.fetchProspects()
    const params = new URLSearchParams(window.location.search)
    const shouldOpen = params.get('openModal')

    this.showModal = shouldOpen === '1'
  },

  methods: {
    async fetchProspects() {
      try {
        this.loading = true
        const response = await axios.get('/api/prospects')
        this.prospects = response.data.map(p => ({
          id: p.id,
          name: p.company_name,
        }))
      } catch (error) {
        console.error('Error fetching prospects:', error)
      } finally {
        this.loading = false
      }
    },

    closeModal() {
      this.showModal = false
    },

    clearFilters() {
      this.selectedFilter = null
      this.showFilter = false
    },
  },
}
</script>

<style scoped>
.min-vh-100 {
  min-height: 100vh;
}

.cursor-pointer {
  cursor: pointer;
  transition: all 0.3s ease;
}

.cursor-pointer:hover {
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  transform: translateX(4px);
}
</style>
