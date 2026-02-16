<template>
  <div class="container bg-light min-vh-100 py-4">
    
    <!-- 1. BACK NAVIGATION -->
    <div class="mb-4">
      <button v-if="viewState !== 'list'" @click="goBack" class="btn btn-link text-decoration-none text-muted d-flex align-items-center gap-2 p-0">
        <span>&lt;</span>
        <span class="fw-bold">BACK</span>
      </button>
      <a v-else href="/dashboard" class="text-decoration-none text-muted d-flex align-items-center gap-2">
        <span>&lt;</span>
        <span>BACK</span>
      </a>
    </div>

    <!-- 2. HEADER -->
    <div class="text-center mb-4 mb-md-5" v-if="viewState == 'list'">
      <div class="mb-2 mb-md-3">
        <svg width="40" height="40" class="d-md-none" viewBox="0 0 50 50" fill="none" stroke="#17a2b8" stroke-width="2">
            <rect x="8" y="6" width="34" height="38" rx="2" />
            <line x1="12" y1="14" x2="38" y2="14" />
            <line x1="12" y1="20" x2="38" y2="20" />
            <line x1="12" y1="26" x2="30" y2="26" />
        </svg>
        <svg width="50" height="50" class="d-none d-md-inline" viewBox="0 0 50 50" fill="none" stroke="#17a2b8" stroke-width="2">
            <rect x="8" y="6" width="34" height="38" rx="2" />
            <line x1="12" y1="14" x2="38" y2="14" />
            <line x1="12" y1="20" x2="38" y2="20" />
            <line x1="12" y1="26" x2="30" y2="26" />
        </svg>
      </div>
      <h1 class="fw-bold mb-2 fs-3 fs-md-2">All Sent Proposals</h1>
      <p class="text-muted small">Select a proposal to see more tracking details</p>
    </div>

    <!-- ============================================== -->
    <!-- VIEW 1: MAIN LIST                              -->
    <!-- ============================================== -->
    <div class="table-responsive shadow-sm rounded-3" v-if="viewState === 'list'">
      <table class="table table-hover mb-0 bg-white align-middle">
        <thead>
          <tr class="bg-teal text-white">
            <th class="text-white fw-bold py-2 py-md-3 ps-3 small">PROSPECTS</th>
            <th class="text-white fw-bold py-2 py-md-3 d-none d-md-table-cell small">LOCATIONS</th>
            <th class="text-white fw-bold py-2 py-md-3 small">PROPOSALS</th>
            <th class="text-white fw-bold py-2 py-md-3 d-none d-lg-table-cell small">SENT</th>
            <th class="text-white fw-bold py-2 py-md-3 small">OPENED</th>
            <th class="text-white fw-bold py-2 py-md-3 pe-3 text-center small">TRACKING</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="prop in proposals" :key="prop.id" class="border-bottom">
            <td class="py-2 py-md-3 fw-bold ps-3 fs-7 fs-md-6">{{ prop.prospect_name }}</td>
            <td class="py-2 py-md-3 d-none d-md-table-cell"><small class="text-truncate d-block" style="max-width: 150px;">{{ prop.location }}</small></td>
            <td class="py-2 py-md-3 fs-7 fs-md-6">{{ prop.proposal_name }}</td>
            <td class="py-2 py-md-3 d-none d-lg-table-cell small">{{ formatDate(prop.sent_at) }}</td>
            <td class="py-2 py-md-3">
              <div v-if="prop.opened_at">
                <div class="fw-bold fs-7 fs-md-6">{{ formatDate(prop.opened_at) }}</div>
                <small class="text-muted d-block extra-small d-none d-md-block">{{ formatTime(prop.opened_at) }}</small>
              </div>
              <div v-else class="text-muted extra-small">Not Opened</div>
            </td>
            <td class="py-2 py-md-3 text-center pe-3">
               <span v-if="prop.status === 'signed'" class="badge bg-info text-white p-1 p-md-2 extra-small">
                  SIGNED
               </span>
               <button v-else @click="loadDetails(prop)" class="btn btn-xs btn-sm btn-info text-white rounded-pill px-2 px-md-3 fw-bold extra-small">
                  TRACK
               </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- ============================================== -->
    <!-- VIEW 2: RECIPIENT LIST (Drill Down)            -->
    <!-- ============================================== -->
    <div v-if="viewState === 'details'" class="bg-white p-2 p-md-4 shadow-sm rounded">
        <h3 class="text-center fw-bold text-teal mb-3 mb-md-4 fs-5 fs-md-4">{{ selectedProposal.proposal_name }}</h3>
        
        <div class="table-responsive rounded-3">
          <table class="table table-hover align-middle">
              <thead class="bg-teal text-white">
                  <tr style="background-color: #17a2b8;">
                      <th class="text-white py-2 py-md-3 ps-3 small">RECIPIENT</th>
                      <th class="text-white py-2 py-md-3 d-none d-sm-table-cell small">SENT DATE</th>
                      <th class="text-white py-2 py-md-3 small">OPENED</th>
                      <th class="text-white py-2 py-md-3 text-end pe-3 small">ACTION</th>
                  </tr>
              </thead>
              <tbody>
                  <tr v-for="rec in recipients" :key="rec.id">
                      <td class="py-2 py-md-3 ps-3 fw-bold fs-7 fs-md-6">{{ rec.email }}</td>
                      <td class="py-2 py-md-3 small d-none d-sm-table-cell">{{ formatDate(rec.created_at) }}</td>
                      <td class="py-2 py-md-3 small">
                          <div v-if="rec.opened_at" class="text-success fw-bold extra-small fs-md-7">
                              {{ formatDate(rec.opened_at) }}<br class="d-md-none">
                              <small class="d-none d-md-inline">{{ formatTime(rec.opened_at) }}</small>
                          </div>
                          <span v-else class="text-muted extra-small">N/A</span>
                      </td>
                      <td class="text-end pe-3 py-2 py-md-3">
                           <button @click="loadActivity(rec)" class="btn btn-sm btn-info text-white rounded-pill px-2 px-md-3 extra-small fw-bold">
                               ACTIVITY
                           </button>
                      </td>
                  </tr>
              </tbody>
          </table>
        </div>
    </div>

    <!-- ============================================== -->
    <!-- VIEW 3: ACTIVITY LOGS (Time Spent)             -->
    <!-- ============================================== -->
    <div v-if="viewState === 'activity'" class="bg-white p-2 p-md-4 shadow-sm rounded text-center">
        <h4 class="fw-bold mb-3 mb-md-4 fs-6 fs-md-5">Activity for: <span class="text-teal small d-block">{{ selectedRecipient.email }}</span></h4>
        
        <div class="table-responsive rounded-3">
          <table class="table table-striped text-start align-middle mb-0">
              <thead style="background-color: #17a2b8;">
                  <tr>
                      <th class="text-white py-2 ps-3 small">SECTION</th>
                      <th class="text-white py-2 text-end pe-3 small">TIME SPENT</th>
                  </tr>
              </thead>
              <tbody>
                  <tr v-for="log in activityLogs" :key="log.section_name">
                      <td class="ps-3 py-2 extra-small fs-md-7">{{ log.section_name }}</td>
                      <td class="text-end pe-3 py-2 extra-small fs-md-7 fw-bold">{{ formatDuration(log.duration_seconds) }}</td>
                  </tr>
                  <tr class="fw-bold bg-secondary text-white">
                      <td class="ps-3 py-2 fs-7">TOTAL</td>
                      <td class="text-end pe-3 py-2 fs-7 fw-bold">{{ formatDuration(totalSeconds) }}</td>
                  </tr>
              </tbody>
          </table>
        </div>
    </div>

  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';

// State
const viewState = ref('list'); // list, details, activity
const proposals = ref([]);
const recipients = ref([]);
const activityLogs = ref([]);
const selectedProposal = ref(null);
const selectedRecipient = ref(null);

// 1. Load Main List
onMounted(async () => {
    try {
        const res = await axios.get('/api/proposals/tracking-list');
        proposals.value = res.data;
    } catch (e) {
        console.error("Error loading proposals", e);
    }
});

// 2. Load Recipients for a Proposal
const loadDetails = async (proposal) => {
    selectedProposal.value = proposal;
    try {
        const res = await axios.get(`/api/proposals/${proposal.id}/details`);
        recipients.value = res.data.recipients;
        viewState.value = 'details';
    } catch (e) {
        console.error(e);
    }
};

// 3. Load Activity for a Recipient
const loadActivity = async (recipient) => {
    selectedRecipient.value = recipient;
    try {
        const res = await axios.get(`/api/recipients/${recipient.unique_token}/activity`);
        activityLogs.value = res.data;
        viewState.value = 'activity';
    } catch (e) {
        console.error(e);
    }
};

const goBack = () => {
    if (viewState.value === 'activity') viewState.value = 'details';
    else if (viewState.value === 'details') viewState.value = 'list';
};

// Computeds & Helpers
const totalSeconds = computed(() => {
    return activityLogs.value.reduce((sum, log) => sum + log.duration_seconds, 0);
});

const formatDate = (date) => {
    if (!date) return '';
    return new Date(date).toLocaleDateString();
};

const formatTime = (date) => {
    if (!date) return '';
    return new Date(date).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
};

const formatDuration = (seconds) => {
    const h = Math.floor(seconds / 3600);
    const m = Math.floor((seconds % 3600) / 60);
    const s = seconds % 60;
    return `${h.toString().padStart(2,'0')}:${m.toString().padStart(2,'0')}:${s.toString().padStart(2,'0')} Hrs:Min:Sec`;
};
</script>

<style scoped>
.text-teal { color: #17a2b8; }
.btn-info { background-color: #17a2b8 !important; border-color: #17a2b8 !important; }
.table thead th { font-size: 14px; }
.table thead tr th { background-color: #17a2b8 !important; }

.fs-7 { font-size: 0.85rem !important; }
.extra-small { font-size: 0.75rem !important; }

@media (max-width: 768px) {
  .table td, .table th {
    padding-left: 0.5rem !important;
    padding-right: 0.5rem !important;
  }
}
</style>