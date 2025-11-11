<template>
    <div class="container-fluid p-0">
        <!-- Header -->
        <header class="bg-light border-bottom py-4 px-3 px-md-4">
            <!-- Web Layout (Desktop) -->
            <div class="d-none d-md-flex align-items-center justify-content-between gap-4">
                <div class="bg-white rounded p-3 d-flex align-items-center gap-2">
                    <button class="btn btn-link text-decoration-none d-flex align-items-center gap-2 p-0 text-dark">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M19 12H5M12 19l-7-7 7-7" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                        <span class="fw-bold">Go Back</span>
                    </button>
                </div>

                <div class="text-center flex-grow-1">
                    <h2 class="fw-bold mb-1 text-dark">FINALIZE PROPOSAL</h2>
                    <p class="text-muted mb-0">Select & edit sections you want to include in the proposal</p>
                </div>

                <div class="bg-white rounded p-3 d-flex align-items-center gap-2">
                    <button class="btn btn-link text-decoration-none d-flex align-items-center gap-2 p-0 text-dark fw-bold" @click="openSendProposalModal">
                        Add Email & Submit
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M3 8l8-6 8 6v10a2 2 0 01-2 2H5a2 2 0 01-2-2V8z" stroke="#17a2b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Layout (Responsive) -->
            <div class="d-md-none">
                <div class="text-center mb-4">
                    <h2 class="fw-bold mb-1 text-dark">FINALIZE PROPOSAL</h2>
                    <p class="text-muted mb-0 small">Select & edit sections you want to include in the proposal</p>
                </div>

                <div class="row g-3">
                    <div class="col-6">
                        <button class="btn btn-white w-100 rounded d-flex align-items-center justify-content-center gap-2 p-3 border" style="background-color: white; border: 1px solid #dee2e6;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M19 12H5M12 19l-7-7 7-7" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                            <span class="fw-bold text-dark">Go Back</span>
                        </button>
                    </div>

                    <div class="col-6">
                        <button class="btn w-100 rounded d-flex align-items-center justify-content-center gap-2 p-3 text-white fw-bold" style="background-color: #17a2b8; border: none;" @click="openSendProposalModal">
                        Add Email & Submit
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M3 8l8-6 8 6v10a2 2 0 01-2 2H5a2 2 0 01-2-2V8z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <div class="row g-4 p-4">
            <!-- Left Sidebar with Expandable Sections & Editor -->
            <div class="col-lg-4">
                <div class="bg-white border rounded-3 p-4">
                    <!-- Hide Unused Sections Toggle -->
                    <!-- <div class="mb-4 pb-4 border-bottom">
                        <div class="d-flex align-items-center gap-2 cursor-pointer">
                        <input type="checkbox" id="hideUnused" v-model="hideUnused" class="form-check-input">
                        <label for="hideUnused" class="form-check-label small m-0">Hide Unused Sections?</label>
                        </div>
                    </div> -->

                    <!-- Upload Documents -->
                    <!-- <div class="mb-4 border-bottom">
                        <button class="btn btn-link small fw-bold mb-2 text-uppercase p-0 text-teal">Upload Documents to Your Library
                        </button>
                    </div> -->

                    <!-- Sections Title -->
                    <h5 class="fw-bold mb-4 text-center">Sections</h5>

                    <!-- Sections List -->
                    <div class="d-flex flex-column gap-3">
                        <div v-for="section in PROPOSAL_SECTIONS" :key="section.id" class="border rounded-2 p-3">
                            <!-- Section Header -->
                            <button
                                @click="toggleExpandedSection(section.id)"
                                class="btn btn-link w-100 text-start d-flex align-items-center justify-content-between p-0 text-decoration-none mb-2"
                            >
                                <div class="d-flex align-items-center gap-2">
                                    <svg width="16" height="16" class="text-teal" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/>
                                    </svg>
                                    <span class="fw-bold text-teal small">{{ section.title }}</span>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <small class="text-teal fw-bold text-uppercase">{{ expandedSections[section.id] ? 'COLLAPSE' : 'EXPAND' }}</small>
                                    <svg
                                        width="14" height="14"
                                        class="text-teal"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                        :style="{ transform: expandedSections[section.id] ? 'rotate(180deg)' : 'rotate(0deg)', transition: 'transform 0.3s' }"
                                        viewBox="0 0 24 24"
                                    >
                                        <path d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                                    </svg>
                                </div>
                            </button>

                            <!-- Section Content Preview and Editor -->
                            <div v-if="expandedSections[section.id]" class="border-top pt-3 mt-3">
                                <!-- Section Content Preview -->
                                <div class="mb-3 pb-3 border-bottom small">
                                    <div v-if="section.id === 'cover-letter'" class="text-muted">
                                        <div class="mb-2">
                                            <p class="mb-1 fw-bold text-dark text-uppercase">CUSTOMER SERVICE</p>
                                            <p class="text-muted small mb-0">Regular on-site quality control is designed to anticipate any problems.</p>
                                        </div>
                                    </div>

                                    <div v-else-if="section.id === 'agreement'" class="text-muted">
                                        <p class="mb-1 fw-bold text-dark text-uppercase">THIS CLEANING SERVICES AGREEMENT</p>
                                        <p class="text-muted small mb-0">THIS CLEANING SERVICES AGREEMENT (the "Agreement") is entered into...</p>
                                    </div>

                                    <div v-else-if="section.id === 'compensation'" class="text-muted">
                                        <p class="mb-1 fw-bold text-dark text-uppercase">SERVICE COMPENSATION</p>
                                        <p class="text-muted small mb-0">Client shall compensate Company for the Services...</p>
                                    </div>

                                    <div v-else-if="section.id === 'insurance'" class="text-muted">
                                        <p class="mb-1 fw-bold text-dark text-uppercase">CERTIFICATE OF INSURANCE</p>
                                        <p class="text-muted small mb-0">Details about certificate of insurance...</p>
                                    </div>

                                    <div v-else-if="section.id === 'signature'" class="text-muted">
                                        <p class="mb-1 fw-bold text-dark text-uppercase">BY THE WITNESS WHEREOF</p>
                                        <p class="text-muted small mb-0">Signature section details...</p>
                                    </div>

                                    <div v-else-if="section.id === 'exhibits'" class="text-muted">
                                        <p class="mb-1 fw-bold text-dark text-uppercase">EXHIBIT A</p>
                                        <p class="text-muted small mb-0">Details about Exhibit A - Judicial Scope of Services</p>
                                    </div>

                                    <div v-else class="text-muted small">
                                        <p class="mb-0">Section content preview</p>
                                    </div>
                                </div>

                                <!-- Editor Section -->
                                <div class="small">
                                    <label class="form-label fw-bold small mb-2">Edit Content:</label>
                                    <textarea
                                        v-model="sectionContent[section.id]"
                                        class="form-control p-2 border rounded-2 mb-2"
                                        style="min-height: 80px; border: 2px solid #e9ecef; resize: none; font-size: 0.85rem;"
                                        placeholder="Enter section content..."
                                    ></textarea>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-teal text-white fw-bold btn-sm px-3">Save</button>
                                        <button class="btn btn-outline-secondary fw-bold btn-sm px-3">Cancel</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right - Full-Width PDF Preview -->
            <div class="col-lg-8">
                <div class="sticky-top" style="top: 2rem;">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="d-flex gap-2">
                            <!-- <button class="btn btn-outline-secondary btn-sm fw-bold">Pick Design</button>
                            <button class="btn btn-outline-secondary btn-sm fw-bold">Change Image</button> -->
                        </div>
                        <button @click="downloadPDF" class="btn btn-teal text-white btn-sm d-flex align-items-center gap-2 fw-bold">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                            DOWNLOAD PDF
                        </button>
                    </div>

                    <!-- PDF Preview Container -->
                    <div id="pdf-content" class="bg-white border rounded-3 overflow-hidden shadow-sm">
                        <div class="position-relative bg-light">
                            <div class="bg-white h-100 d-flex flex-column p-4">

                                <!-- Header Image with Gradient Overlay -->
                                <div class="mb-4 rounded-3 overflow-hidden" style="height: 120px; background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%); position: relative;">
                                    <img src="https://images.unsplash.com/photo-1585771724684-38269d6639fd?w=600&h=300&fit=crop" alt="Cleaning" class="w-100 h-100 object-cover" style="object-fit: cover; opacity: 0.6;">
                                    <div class="position-absolute top-0 end-0 p-3">
                                        <div class="bg-white rounded-2 px-3 py-2">
                                        <p class="fw-bold text-dark mb-0 small">B3LIEVE3<br><span class="small">Staffing Services</span></p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Main Content -->
                                <div class="flex-grow-1 px-2 pb-3 small" style="overflow-y: auto;">
                                    <h6 class="fw-bold text-dark mb-2">THIS CLEANING SERVICES AGREEMENT</h6>
                                    <p class="text-muted small mb-4">{{ sectionContent['agreement'] || 'Agreement content...' }}</p>

                                    <h6 class="fw-bold text-dark mb-2">CLEANING SERVICES & LOCATION</h6>
                                    <p class="text-muted small mb-4">{{ sectionContent['compensation'] || 'Compensation content...' }}</p>

                                    <h6 class="fw-bold text-dark mb-2">GRIFFIN AND ESTRADA INC</h6>
                                    <p class="text-muted small mb-3">{{ sectionContent['legal'] || 'Legal terms...' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Send Proposal Modal -->
    <SendProposalModal
        :isOpen="showSendProposalModal"
        @close="closeSendProposalModal"
        @next="handleProposalSent"
    />
</template>

<script setup>
import { ref } from 'vue'
import html2pdf from 'html2pdf.js'

const PROPOSAL_SECTIONS = [
  { id: 'cover-letter', title: 'Cover Letter' },
  { id: 'agreement', title: 'Agreement' },
  { id: 'compensation', title: 'Compensation' },
  { id: 'legal', title: 'Legal' },
  { id: 'insurance', title: 'Insurance' },
  { id: 'signature', title: 'Signature' },
  { id: 'exhibits', title: 'Exhibits - Scope of Work' },
]

const expandedSections = ref({})
const hideUnused = ref(false)
const sectionContent = ref({
  'cover-letter': 'Regular on-site quality control is designed to anticipate any problems or correct any problems when noticed...',
  'agreement': 'THIS CLEANING SERVICES AGREEMENT (the "Agreement") is entered into on [DATE] (the "Effective Date") by and between [COMPANY NAME]...',
  'compensation': 'Client shall compensate Company for the Services (including recurring Services) in accordance with the pricing rates outlined in Section 3...',
  'legal': 'This Agreement will be governed by the laws of [STATE/PROVINCE]...',
  'insurance': 'Company shall maintain all required insurance coverage...',
  'signature': 'By signing below, both parties agree to the terms of this agreement...',
  'exhibits': 'EXHIBIT A - Scope of Services: Detailed list of cleaning services to be provided...',
})

const toggleExpandedSection = (sectionId) => {
  expandedSections.value[sectionId] = !expandedSections.value[sectionId]
}

const downloadPDF = () => {
  const element = document.getElementById('pdf-content')
  const opt = {
    margin: 0.5,
    filename: 'Proposal.pdf',
    image: { type: 'jpeg', quality: 0.98 },
    html2canvas: { scale: 2 },
    jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
  }
  html2pdf().set(opt).from(element).save()
}

const showSendProposalModal = ref(false)

const openSendProposalModal = () => {
  showSendProposalModal.value = true
}

const closeSendProposalModal = () => {
  showSendProposalModal.value = false
}

const handleProposalSent = (email) => {
  console.log('Sending proposal to:', email)
  // Handle sending proposal logic here
}

</script>

<style scoped>
.btn-teal {
  background-color: #17a2b8;
  border: none;
  transition: all 0.3s ease;
  color: white;
}

.btn-teal:hover {
  background-color: #138496;
  color: white !important;
}

.text-teal {
  color: #17a2b8;
}

.form-check-input {
  border-radius: 4px;
  border: 2px solid #dee2e6;
}

.form-check-input:checked {
  background-color: #17a2b8;
  border-color: #17a2b8;
}

.sticky-top {
  position: sticky;
}

.object-cover {
  object-fit: cover;
}
</style>
