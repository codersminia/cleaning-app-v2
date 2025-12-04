<template>
  <div class="container py-5">

    <!-- Fullscreen Loader -->
    <div v-if="loading" class="loader-overlay">
      <div class="d-flex flex-column align-items-center">
        <div class="spinner-border text-info mb-2" role="status" style="width: 3rem; height: 3rem;"></div>
        <span class="text-white fw-bold">Processing...</span>
      </div>
    </div>

    <!-- Main Content -->
    <div v-else>
      <!-- Prospect Header Card -->
      <div class="card shadow-sm mb-5" style="background-color: #c3e5e2; border:none; border-radius:10px;">
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

      <!-- Proposals Section Header -->
      <div class="text-center mb-4">
        <h4 class="fw-bold">
          Proposals for {{ prospect.company_name }}
        </h4>
        <p class="text-muted">Here are the proposals you created for this facility</p>
        
        <button
          class="btn btn-info text-white px-4 fw-bold mt-2"
          @click="openProposalModal"
        >
          CREATE YOUR FIRST PROPOSAL
        </button>
      </div>

      <!-- PROPOSALS LIST (Dynamic Cards) -->
      <div v-if="proposals.length > 0" class="d-flex flex-column gap-3">
        
        <div v-for="proposal in proposals" :key="proposal.id" class="card shadow-sm p-3 border-0 rounded-3">
          <div class="row align-items-center">
            
            <!-- Proposal Name -->
            <div class="col-md-3">
              <h5 class="fw-bold mb-0 text-dark">{{ proposal.proposal_name }}</h5>
            </div>

            <!-- Info Bar (Gray Background) -->
            <div class="col-md-6">
              <div class="bg-light rounded p-2 d-flex justify-content-around align-items-center">
                
                <!-- Proposal Type / Category -->
                <div class="text-start">
                  <small class="text-muted d-block" style="font-size: 0.75rem;">Proposal Type</small>
                  <span class="fw-bold text-dark">{{ formatCategory(proposal) }}</span>
                </div>

                <!-- Separator -->
                <div class="border-end mx-2" style="height: 30px;"></div>

                <!-- Status -->
                <div class="text-start">
                  <small class="text-muted d-block" style="font-size: 0.75rem;">Status</small>
                  <span class="fw-bold d-flex align-items-center gap-2">
                    <span 
                      class="rounded-circle d-inline-block" 
                      :class="proposal.status == 'sent' || proposal.status == 'accepted' ? 'bg-success' : 'bg-warning'"
                      style="width: 10px; height: 10px;">
                    </span>
                    {{ proposal.status == 'accepted' ? 'Signed' : (proposal.status == 'sent' ? 'Sent' : 'Not Signed') }}
                  </span>
                </div>

              </div>
            </div>

            <!-- Actions -->
            <div class="col-md-3 d-flex justify-content-end align-items-center gap-3">
              <!-- Edit Button -->
              <button class="btn btn-info text-white fw-bold rounded-pill px-4" @click="editProposal(proposal)">
                EDIT YOUR PROPOSAL
              </button>

              <!-- More Dropdown -->
              <div class="dropdown">
                <button class="btn btn-link text-dark text-decoration-none fw-bold" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                  MORE <i class="bi bi-three-dots-vertical"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                  <li>
                    <button class="dropdown-item text-danger" @click="deleteProposal(proposal.id)">
                      <i class="bi bi-trash me-2"></i> Delete
                    </button>
                  </li>
                </ul>
              </div>
            </div>

          </div>
        </div>

      </div>

      <!-- No Proposals State -->
      <div v-else class="text-center py-5 text-muted">
        <p>No proposals found. Click the button above to create one.</p>
      </div>

      <add-proposal-modal
        v-if="showProposalModal"
        :isOpen="showProposalModal"
        :facilities="facilities"
        :frequencies="frequencies"
        :weekdays="weekdays"  
        :prospect-id="prospect.id"
        @close="handleModalClose"
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
      weekdays: [],
      proposals: [] 
    };
  },
  mounted() {
    this.fetchProposals();
  },
  methods: {
    // 1. Fetch Proposals API (With Loader)
    async fetchProposals() {
      this.loading = true; // START LOADER
      try {
        const response = await axios.get(`/api/prospects/${this.prospect.id}/proposals`);
        this.proposals = response.data;
      } catch (error) {
        console.error("Error fetching proposals", error);
      } finally {
        this.loading = false; // STOP LOADER
      }
    },

    // 2. Open Modal (Pre-fetch data)
    async openProposalModal() {
      this.loading = true;
      try {
        const [facilitiesRes, frequenciesRes, weekdaysRes] = await Promise.all([
          axios.get('/api/facilities'),
          axios.get('/api/frequencies'),
          axios.get('/api/weekdays')
        ]);

        this.facilities = facilitiesRes.data;
        this.frequencies = frequenciesRes.data;
        this.weekdays = weekdaysRes.data;

        this.showProposalModal = true;
      } catch (error) {
        console.error('Error fetching data:', error);
        alert('Failed to load required data. Please try again.');
      } finally {
        this.loading = false;
      }
    },

    // Handle modal close (refresh list just in case they added but didn't redirect)
    handleModalClose() {
        this.showProposalModal = false;
        this.fetchProposals();
    },

    // 3. Format Category Name for Display
    formatCategory(proposal) {
        if (proposal.proposal_type == 'commercial' && proposal.commercial_category) {
            return this.prettifyString(proposal.commercial_category);
        }
        if (proposal.proposal_type == 'residential' && proposal.residential_category) {
            return this.prettifyString(proposal.residential_category);
        }
        return 'Unknown';
    },

    prettifyString(str) {
        if(!str) return '';
        return str.split('_')
                  .map(word => word.charAt(0).toUpperCase() + word.slice(1))
                  .join(' ')
                  .replace('Cleaning Projects', 'Janitorial & Projects')
                  .replace('Janitorial Cleaning', 'Janitorial & Projects');
    },

    // 4. SMART REDIRECT
    editProposal(proposal) {
        this.loading = true; // Show loader briefly before redirect

        setTimeout(() => {
            let nextRoute = 'tasks'; 

            const isCommercialJanitorial = 
                proposal.proposal_type == 'commercial' && 
                (proposal.commercial_category == 'janitorial_projects' || proposal.commercial_category == 'janitorial_cleaning');
            
            const isResidentialCleaning = 
                proposal.proposal_type == 'residential' && 
                proposal.residential_category == 'cleaning_projects';

            if (!isCommercialJanitorial && !isResidentialCleaning) {
                nextRoute = 'projects';
            }

            window.location.href = `/proposals/${proposal.id}/${nextRoute}`;
        }, 500);
    },

    // 5. Delete Function (With Loader)
    async deleteProposal(id) {
        if(!confirm("Are you sure you want to delete this proposal? This action cannot be undone.")) {
            return;
        }

        this.loading = true; // START LOADER

        try {
            await axios.delete(`/api/proposals/${id}`);
            
            // Update list locally
            this.proposals = this.proposals.filter(p => p.id !== id);
            
        } catch (error) {
            console.error("Error deleting proposal", error);
            alert("Failed to delete proposal.");
        } finally {
            this.loading = false; // STOP LOADER
        }
    }
  }
};
</script>

<style scoped>
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

.dropdown-toggle::after {
    display: none;
}
</style>