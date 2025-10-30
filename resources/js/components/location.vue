<template>
  <div class="container py-5">

    <!-- Fullscreen Loader -->
    <div v-if="loading" class="loader-overlay">
      <div class="spinner-border text-info" role="status" style="width: 3rem; height: 3rem;"></div>
    </div>

    <!-- Main Content -->
    <div v-else>
      <!-- Prospect Header Card -->
      <div class="card shadow-sm mb-5" style="background-color: #c3e5e2;">
        <div class="card-body">
          <div class="text-center mb-3">
            <p class="text-muted mb-0">PROSPECT</p>
            <h3 class="fw-bold text-info mb-3">{{ prospect.company_name }}</h3>
          </div>
          <hr>
          <div class="row text-center">
            <div class="col-md-3 mb-3">
              <i class="bi bi-geo-alt text-info fs-4"></i>
              <h6 class="fw-bold">ADDRESS</h6>
              <p class="mb-0 small">{{ prospect.address }}, {{ prospect.city }}, {{ prospect.state }}, {{ prospect.zip }}</p>
            </div>
            <div class="col-md-3 mb-3">
              <i class="bi bi-building text-info fs-4"></i>
              <h6 class="fw-bold">LOCATION NAME</h6>
              <p class="mb-0 small">{{ prospect.contact_person || '—' }}</p>
            </div>
            <div class="col-md-3 mb-3">
              <i class="bi bi-envelope text-info fs-4"></i>
              <h6 class="fw-bold">EMAIL</h6>
              <p class="mb-0 small">{{ prospect.email || '—' }}</p>
            </div>
            <div class="col-md-3 mb-3">
              <i class="bi bi-telephone text-info fs-4"></i>
              <h6 class="fw-bold">PHONE</h6>
              <p class="mb-0 small">
                C {{ prospect.mobile || '—' }}<br>
                P {{ prospect.phone || '—' }}
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- Proposals Section -->
      <div class="text-center py-5">
        <h4 class="fw-bold">
          Proposals for {{ prospect.company_name }} {{ prospect.address }}
        </h4>
        <p class="text-muted">Here are the proposals you created for this facility</p>

        <button
          class="btn btn-info text-white px-4 fw-bold mt-3"
          @click="openProposalModal"
        >
          CREATE YOUR FIRST PROPOSAL
        </button>
      </div>

      <add-proposal-modal
		v-if="showProposalModal"
		:isOpen="showProposalModal"
		:facilities="facilities"
		:frequencies="frequencies"
		:weekdays="weekdays"  
		:prospect-id="prospect.id"
		@close="showProposalModal = false"
		/>
    </div>
  </div>
</template>

<script>
import AddProposalModal from './Modals/AddProposalModal.vue';
import axios from 'axios';

export default {
  name: 'LocationComponent',
  components: { AddProposalModal },
  props: {
    prospect: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      loading: false,
      showProposalModal: false,
      facilities: [],
      frequencies: [],
      weekdays: [] // 🆕 Add weekdays
    };
  },
  methods: {
    async openProposalModal() {
      this.loading = true;
      try {
        const [facilitiesRes, frequenciesRes, weekdaysRes] = await Promise.all([
          axios.get('/api/facilities'),
          axios.get('/api/frequencies'),
          axios.get('/api/weekdays') // 🆕 Fetch weekdays
        ]);

        this.facilities = facilitiesRes.data;
        this.frequencies = frequenciesRes.data;
        this.weekdays = weekdaysRes.data; // 🆕

        this.loading = false;
        this.showProposalModal = true;
      } catch (error) {
        console.error('Error fetching data:', error);
        this.loading = false;
        alert('Failed to load required data. Please try again.');
      }
    }
  }
};
</script>

<style scoped>
.card {
  border: none;
  border-radius: 10px;
}
.loader-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.7);
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  z-index: 2000;
}
</style>
