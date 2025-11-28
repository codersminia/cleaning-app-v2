<template>
  <div class="container-fluid p-0">
    <header class="bg-light border-bottom py-4 px-3 px-md-4">
      <!-- Web Layout -->
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

      <!-- Mobile Layout -->
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
      <!-- LEFT SIDEBAR -->
      <div class="col-lg-4">
        <div class="bg-white border rounded-3 p-4">
          <h5 class="fw-bold mb-4 text-center">Sections</h5>
          <div class="d-flex flex-column gap-3">
            <div v-for="section in PROPOSAL_SECTIONS" :key="section.id" class="border rounded-2 p-3">
              <button @click="toggleExpandedSection(section.id)" class="btn btn-link w-100 text-start d-flex align-items-center justify-content-between p-0 text-decoration-none mb-2">
                <div class="d-flex align-items-center gap-2">
                  <svg width="16" height="16" class="text-teal" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/>
                  </svg>
                  <span class="fw-bold text-teal small">{{ section.title }}</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                  <small class="text-teal fw-bold text-uppercase">{{ expandedSections[section.id] ? 'COLLAPSE' : 'EXPAND' }}</small>
                  <svg width="14" height="14" class="text-teal" fill="none" stroke="currentColor" stroke-width="2" :style="{ transform: expandedSections[section.id] ? 'rotate(180deg)' : 'rotate(0deg)', transition: 'transform 0.3s' }" viewBox="0 0 24 24">
                    <path d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                  </svg>
                </div>
              </button>

              <div v-if="expandedSections[section.id]" class="border-top pt-3 mt-3">
                <div class="small">
                  <label class="form-label fw-bold small mb-2">Edit Content:</label>
                  <!-- LIVE EDIT TEXTAREA -->
                  <textarea 
                    v-model="sectionContent[section.id]" 
                    class="form-control p-2 border rounded-2 mb-2" 
                    style="min-height: 150px; border: 2px solid #e9ecef; resize: vertical; font-size: 0.85rem;"
                  ></textarea>
                  <div class="d-flex gap-2">
                    <button @click="toggleExpandedSection(section.id)" class="btn btn-teal text-white fw-bold btn-sm px-3">Done</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- RIGHT SIDE - PREVIEW -->
      <div class="col-lg-8">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="d-flex gap-2">
                </div>
                <button @click="downloadPDF" class="btn btn-teal text-white btn-sm d-flex align-items-center gap-2 fw-bold">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    DOWNLOAD PDF
                </button>
            </div>

        <!-- SCROLLABLE PERFECT PREVIEW -->
        <div class="bg-light rounded-3 shadow" style="height: 90vh; overflow-y: auto; padding: 40px 20px; display: flex; flex-direction: column; align-items: center;">
            <div id="pdf-content" v-html="fullPdfHtml" style="width: 100%"></div>
        </div>
      </div>
    </div>

    <SendProposalModal :isOpen="showSendProposalModal" @close="closeSendProposalModal" @next="handleProposalSent" />
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import axios from 'axios'
import html2pdf from 'html2pdf.js'
import SendProposalModal from './Modals/SendProposalModal.vue' 

const props = defineProps({
	proposalId: {
		type: [Number, String],
		required: true
	}
})

// 1. DATA CONFIGURATION
const PROPOSAL_SECTIONS = [
  { id: 'cover-letter', title: 'Cover Letter' },
  { id: 'agreement', title: 'Agreement' },
  { id: 'compensation', title: 'Compensation' },
  { id: 'legal', title: 'Legal' },
  { id: 'insurance', title: 'Insurance' },
  { id: 'signature', title: 'Signature' },
  { id: 'exhibits', title: 'Exhibits - Scope of Work' },
]

// 2. STATE MANAGEMENT
const expandedSections = ref({})
const showSendProposalModal = ref(false)

// 3. EDITABLE CONTENT (Default values taken from your HTML)
const sectionContent = ref({
    'cover-letter': `Believe 313 staffing provides cleaning services for office and industrial buildings. It is our hope that when you consider contracting these services for your facility, you will have at hand this brief introduction to our company— Believe 313 staffing was founded to deliver superior quality and was soon distinguished for its technology based quality control system and management practices. Primarily by referral, our client list has grown to include major offices and industrial buildings. More importantly, it still includes many of our original clients.`,
    
    'agreement': `THIS CLEANING SERVICES AGREEMENT (the “Agreement”) is entered into on 11/26/2025 (the “Effective Date”) by and between Believe 313 staffing (hereinafter the “Company”), located at (14500 Ashton , Detroit , Mi, 48223) and Gallagher and Alston Traders (hereinafter the “Client”), located at (Praesentium quia animi iusto quasi aut tempor hic commodi in blanditiis eum, Unit 245, Id quos voluptate in ea dolorem voluptatem soluta , Hic dolore ex iure nisi alias id eum sapiente ips, 91850) (referred to collectively herein as the “Parties,” and individually as a “Party”).WHEREAS, Client desires for the Company to provide certain cleaning services to Client at the location described below (the “Premises”); and WHEREAS, Company desires to offer such cleaning services to Client in accordance with the terms and conditions contained herein.NOW, THEREFORE, for good and valuable consideration, the receipt of which is hereby acknowledged, the Parties hereto agree as follows:`,
    
    'compensation': `Client shall compensate Company for the Services (See EXHIBIT A for specific services to be rendered) at the agreed price(s) listed below. Company shall furnish an invoice to Client detailing activities performed during the preceding month. Client shall pay the Contractor within fifteen days after the receipt thereof. All invoices are payable to Company at (14500 Ashton , Detroit , Mi, 48223). Client shall not be responsible for any payment to Company for any additional services or expenses that are not specifically included in this Agreement except upon agreement in writing by both Parties. The Parties shall attempt to resolve any payment disputes within thirty (30) days after the invoice date.`,
    
    'legal': `The Company shall be liable hereunder only for its own gross negligence, willful misconduct or bad faith. The Customer agrees to indemnify the Company and save it harmless against any and all liabilities, including judgments, costs and reasonable counsel fees, for anything done or omitted by the Company in the execution of this Agreement, except as a result of the Company's gross negligence, willful misconduct or bad faith. The Company shall not be liable for loss of, or damage to, personal property while rendering the Services unless such loss or damage is a direct result of the Company's negligence.`,
    
    'insurance': `The Company, at its own cost and expense, shall procure and maintain throughout the Term of this Agreement (a) a workers' compensation insurance policy for the protection of its employees engaged in work under this Agreement and (b) a comprehensive liability insurance and property damage insurance policy to cover claims of injury to or death of any person, or of damage to or destruction of any property arising from or in connection with the Services hereunder. A certificate of such insurance policies shall be provided by Company to Client prior to the date in which Company shall begin the Services defined herein for Client.`,
    
    'signature': `The Parties have executed this Cleaning Services Agreement as of the date first written above.`,
    
    'exhibits': `Technicians will perform a pre-work inspection to gather photo documentation, identify potential challenges and address expected outcomes.
Predesignated furniture and other items will be moved prior to starting our work.
Slip and Fall Protection - Place safety cones and wet floors signs in appropriate areas before beginning
Remove large debris, staples, paper clips, gum etc. prior to vacuuming
Walk-off mats are used during carpet to floor transitions to ensure safety and to protect your hard surface flooring.
Pre Vacuuming is one of the most important steps in cleaning as it removes dry soil. Our techs use only the best commercial-grade-HEPA-filtered vacuum systems to remove dirt and debris deep below the surface
We apply the appropriate cleaning solution to begin the process of breaking down and emulsifying the heavy soils and to test material safety
After pretreatment has had time to dwell we target stains and spots with customized cleaning solutions (Additional Charges will Apply)
Apply an emulsifier to separate and suspend the soils.
Agitate the carpet nap to accelerate the action of the emulsifier.
At this point, we allow 'dwell time' (this is where the real cleaning takes place).
Once the soils have been separated from the carpet fibers, we use hot water extraction to remove the soils and cleaning agents. (We use a jetless rotary cleaner which leaves your carpet 80% drier and much cleaner than standard wand cleaning.) This residue-free cleaning helps retard soiling.
Our technician or supervisor will circle back to review the work and will treat any missed sections
Your carpet is groomed so that the pile of your carpet looks neat and uniform when we leave.
We use high velocity air movers to help your floors dry quickly
Replace predesignated items and furniture when the floor is dry
To ensure your satisfaction we invite you to thoroughly inspect our work. Once you are happy, post care instructions will be provided`
})

// ACTIONS
const toggleExpandedSection = (id) => expandedSections.value[id] = !expandedSections.value[id]
const openSendProposalModal = () => showSendProposalModal.value = true
const closeSendProposalModal = () => showSendProposalModal.value = false

const handleProposalSent = async (email) => {
    try {
        // props.proposalId is now valid
        await axios.post(`/api/proposals/${props.proposalId}/finalize-and-send`, {
            email: email,
            content_data: sectionContent.value 
        })
        alert("Proposal Sent Successfully!")
        closeSendProposalModal()
    } catch (error) {
        console.error(error)
        alert("Failed to send proposal")
    }
}

// HELPER: Convert text with newlines to bullet points HTML
const formatBullets = (text) => {
    if(!text) return '';
    return text.split('\n').filter(line => line.trim() !== '').map(line => `<li>${line}</li>`).join('');
}

// 4. COMPUTED HTML (INJECTS STATE INTO TEMPLATE)
const fullPdfHtml = computed(() => `
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <style>
    @page { margin: 0; size: letter; }
  body { margin:0; padding:0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background:#f8f9fa; }
  
  /* ALL PAGES - exact Letter size */
  .page {
    width: 100%;
    height: auto;
    background: white;
    position: relative;
    page-break-after: always;
    overflow: visible !important;
    box-shadow: 0 8px 25px rgba(0,0,0,0.12);
    margin: 0 auto 60px auto;
    box-sizing: border-box;
  }

  /* COVER PAGE */
  .cover-image {
    width: 100%; height: 100%; object-fit: cover; position: absolute; top:0; left:0; z-index:1;
  }
  .cover-overlay {
    position: absolute; bottom:0; left:0; width:100%; height:340px;
    background: linear-gradient(to top, #22c55e 0%, transparent 100%);
    border-radius: 120px 0 0 0; z-index:2;
  }
  .cover-text {
    position: absolute; bottom:70px; left:50%; transform:translateX(-50%);
    text-align:center; color:white; width:90%; z-index:5;
  }
  .cover-text h2 { font-size:3.2rem; margin:0 0 12px; font-weight:900; }
  .cover-text p { font-size:1.15rem; line-height:1.6; margin-bottom:30px; }
  .btn-website {
    background:#f39c12; color:white; padding:16px 50px; border-radius:50px;
    font-weight:bold; font-size:1.3rem; border:none; box-shadow:0 6px 20px rgba(243,156,18,0.4);
  }

  /* INTERNAL PAGES */
  .header-wave {
    position: absolute; top:0; left:0; right:0; height:120px;
    background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
    border-radius: 0 0 100px 0;
  }
  .logo {
    position: absolute; top:18px; right:40px; z-index:10;
  }
  .logo img { width:82px; }

  .content {
    padding: 160px 80px 80px 80px;
    line-height: 1.7;
    color: #333;
    font-size: 0.95rem;
    position: relative;
    z-index: 2;
  }

  .page-number {
    position: absolute;
    bottom: 50px;
    right: 80px;
    color: #888;
    font-size: 0.9rem;
  }

    .circle-num { display:inline-flex; align-items:center; justify-content:center; width:40px; height:40px; border-radius:50%; background:#22c55e; color:white; font-weight:bold; font-size:1.3rem; margin-right:15px; flex-shrink:0; }
    .section-title { display:flex; align-items:center; margin:40px 0 20px; }
    .section-title h3 { margin:0; color:#333; font-size:1.4rem; }
    table { width:100%; border-collapse:collapse; margin:25px 0; font-size:0.95rem; }
    th { background:#22c55e; color:white; padding:15px; text-align:center; font-weight:bold; }
    td { padding:13px 15px; border:1px solid #ddd; text-align:center; }
    .price { font-weight:bold; color:#22c55e; font-size:1.2rem; }
    .page-number { position:absolute; bottom:50px; right:80px; color:#888; font-size:1rem; }
    .exhibit-table td { text-align:left; vertical-align:top; padding:10px 0; }
    .exhibit-table td:first-child { width:30%; font-weight:bold; }
    .exhibit-table td:last-child { text-align:right; font-weight:bold; }
    .signature-section { margin-top:60px; padding:40px; border:2px solid #22c55e; border-radius:16px; background:#f8fff8; }
    .signature-box { border:1px solid #ddd; border-radius:8px; padding:30px; margin:20px 0; text-align:center; background:#fafafa; }
  </style>
</head>
<body>

  <!-- PAGE 1: COVER -->
  <div class="page" style="height:9in">
    <img src="/images/cover10.jpg" class="cover-image" alt="Cover">
    <div class="logo"><img src="/images/logo.png" alt="Logo"></div>
    <div class="cover-overlay"></div>
    <div class="cover-text">
      <h3>Believe313staffing.com</h3>
      <p>For Gallagher And Alston Traders 11/26/2025<br>
      ea dolorem voluptatem soluta, Hic dolore ex iure nisi alias id<br>
      eum sapiente ips, 91850</p>
      <button class="btn-website">believe313staffing.com</button>
    </div>
  </div>

    <!-- PAGE 2: INTRODUCTION -->
    <div class="page">
      <div class="header-wave"></div>
      <div class="logo">
        <img src="/images/logo.png" alt="Logo" width="70px" />
      </div>
      <div class="content">
        <h3 style="color: #22c55e; font-size: 2rem; margin-bottom: 30px;">GALLAGHER AND ALSTON TRADERS</h3>
        
        <!-- DYNAMIC COVER LETTER -->
        <p style="text-align: justify; margin-bottom: 30px;">
          ${sectionContent.value['cover-letter']}
        </p>

        <div style="display: flex; gap: 60px; margin-bottom: 40px;">
          <div style="flex: 1;">
            <h4 style="color: #22c55e; margin-bottom: 10px;">CUSTOMER SERVICE.</h4>
            <p>Regular on site quality control is designed to anticipate the client's needs; and a call from a client at any time of the day or night will be answered by immediate action to correct a problem or fulfill a special request.</p>
            <h4 style="color: #22c55e; margin-top: 30px; margin-bottom: 10px;">CONSISTENT QUALITY.</h4>
            <p>Consistency and Quality are the pillars of our service standards. We hold ourselves accountable by performing thorough and Routine Quality Control Inspections.</p>
          </div>
          <div style="flex: 1;">
            <h4 style="color: #22c55e; margin-bottom: 10px;">EXPERIENCE.</h4>
            <p>Over the years we worked hard at not just solving existing problems for our clients, but we've learned how to predict and prevent most recurring challenges as well.</p>
            <h4 style="color: #22c55e; margin-top: 30px; margin-bottom: 10px;">RESOURCES.</h4>
            <p>Since we serve facilities and buildings just like yours throughout the area, we have the equipment and personnel ready to deal with any contingency and provide continuity of service.</p>
          </div>
        </div>
        <div style="margin-top: 50px; text-align: center;">
          <h4 style="color: #22c55e; margin-bottom: 15px;">HUMBLY SUBMITTED</h4>
          <p style="font-weight: bold; font-size: 1.2rem;">
            Mario Kelly<br>Ceo<br>Believe 313 staffing<br>313-656-8730
          </p>
        </div>
        <div class="page-number">Page 1</div>
      </div>
    </div>

    <!-- PAGE 3: AGREEMENT INTRO -->
    <div class="page">
      <div class="header-wave"></div>
      <div class="logo"><img src="/images/logo.png" alt="Logo" width="70px"/></div>
      <div class="content">
        <div class="section-title">
          <div class="circle-num">1</div>
          <h3>THIS CLEANING SERVICES AGREEMENT</h3>
        </div>
        <!-- DYNAMIC AGREEMENT -->
        <p style="text-align: justify;">
           ${sectionContent.value['agreement']}
        </p>
        
        <div class="section-title">
          <div class="circle-num">2</div>
          <h3>CLEANING SERVICES & LOCATION</h3>
        </div>
        <p><strong>2.1 CLEANING SERVICES</strong> Company shall provide the cleaning services (collectively the “Services”) as provided in , EXHIBIT A (“Scope of Services”) and EXHIBIT C (“ONE TIME PROJECT WORK”)</p>
        <p><strong>2.2 THE PREMISES</strong> Company shall provide the Services at the following location(s): Praesentium quia animi iusto quasi aut tempor hic commodi in blanditiis eum, Unit 245, Id quos voluptate in ea dolorem voluptatem soluta , Hic dolore ex iure nisi alias id eum sapiente ips, 91850.</p>
        
        <div class="section-title">
          <div class="circle-num">3</div>
          <h3>SERVICE COMPENSATION</h3>
        </div>
        <!-- DYNAMIC COMPENSATION -->
        <p>
            ${sectionContent.value['compensation']}
        </p>
        
        <p><strong>3.1 PRICING.</strong> Client shall compensate Company for the following Services (See EXHIBIT A for specific services to be rendered) at the prices listed below.</p>
        <div class="page-number">Page 2</div>
      </div>
    </div>

    <!-- PAGE 4: JANITORIAL -->
    <div class="page">
      <div class="header-wave"></div>
      <div class="logo"><img src="/images/logo.png" alt="Logo" width="70px"/></div>
      <div class="content">
        <p><strong>JANITORIAL SERVICES</strong><br>See EXHIBIT A for specific services to be rendered</p>
        <table>
          <thead><tr><th>AREA SERVICED</th><th>FREQUENCY</th><th>MONTHLY PRICE</th></tr></thead>
          <tbody><tr><td>SEE EXHIBIT A</td><td>2 x week</td><td class="price">$3456.00</td></tr></tbody>
        </table>
        <div class="section-title"><div class="circle-num">4</div><h3>ONE TIME PROJECT WORK COMPENSATION.</h3></div>
        <p>Client shall compensate Company for the following Service(s) (See EXHIBIT C for specific services to be rendered) at the one-time price of ($3105.00). These are one time projects and will be invoiced upon completion of the project work. Client shall pay the Contractor within thirty days after the receipt of the invoice.</p>
        <p><strong>4.A One Time Project Pricing:</strong></p>
        <p><strong>CARPET - HOT WATER EXTRACT</strong><br>See EXHIBIT C for specific services to be rendered</p>
        <table>
          <thead><tr><th>AREA SERVICED</th><th>FREQUENCY</th><th>PRICE PER SERVICE</th></tr></thead>
          <tbody><tr><td>Whole Building</td><td>1 time</td><td class="price">$3105.00</td></tr></tbody>
        </table>
        <div class="section-title"><div class="circle-num">5</div><h3>EQUIPMENT & SUPPLIES</h3></div>
        <p><strong>5.1 EQUIPMENT</strong> Company will furnish all labor, transportation, equipment and cleaning chemicals requisite to the performance of these Services, except as otherwise specified in the attached exhibits and listed supplies below.</p>
        <p><strong>5.2 SUPPLIES</strong> Client will provide consumable products such as, but not limited to, toilet paper, paper towels, hand soap and trash liners</p>
        <div class="section-title"><div class="circle-num">6</div><h3>TERM & TERMINATION.</h3></div>
        <p><strong>6.1 TERM</strong> This Agreement shall be effective upon execution by Client for a period of twelve (12) months, unless sooner terminated under the terms set forth below.</p>
        <div class="page-number">Page 3</div>
      </div>
    </div>

    <!-- PAGE 5: TERMINATION -->
    <div class="page">
      <div class="header-wave"></div>
      <div class="logo"><img src="/images/logo.png" alt="Logo" width="70px"/></div>
      <div class="content">
        <p><strong>6.2 TERMINATION</strong> This Agreement may be terminated by either Party hereto upon a thirty (30) day written notice to the non-terminating party.</p>
        <p><strong>6.3 AUTOMATIC RENEWAL</strong> After the natural expiration of the term of this twelve (12) month Agreement, this Agreement will automatically convert to a month-to-month agreement between Client and Company. All the Services will be subject to the terms and conditions of this Agreement, but will be terminable upon either Party giving one (1) month's written notice to the other Party.</p>
        <div class="section-title"><div class="circle-num">7</div><h3>KEYS.</h3></div>
        <p>Client shall issue essential keys upon award of the Agreement for all service locations. Company shall sign for said keys. Company shall return all issued keys at the termination of this Agreement.</p>
        <div class="section-title"><div class="circle-num">8</div><h3>ACCESS</h3></div>
        <p>The hours of service shall be after 6:00 p.m., unless otherwise specified. All federal holidays are excluded from service unless otherwise specified.</p>
        <div class="section-title"><div class="circle-num">9</div><h3>PHOTO IDENTIFICATION</h3></div>
        <p>Company's employees will wear a photo identification (“ID”) badge, a professional uniform and will have completed Company's employee training program.</p>
        <div class="section-title"><div class="circle-num">10</div><h3>INDEPENDENT CONTRACTOR.</h3></div>
        <p>Neither Party shall, for any purpose, be deemed to be an agent of the other Party and the relationship between the Parties shall only be that of independent contractors. Neither Party shall have any right or authority to assume or create any obligations or to make any representations or warranties on behalf of any other Party, whether express or implied, or to bind the other Party in any respect whatsoever.</p>
        <div class="section-title"><div class="circle-num">11</div><h3>NON-SOLICITATION OF EMPLOYEES</h3></div>
        <p>Client agrees and covenants not to directly or indirectly solicit, hire, recruit, attempt to hire or recruit, or induce the termination of employment of any employee and/or contractor of the Company during the effective term of this Agreement and for a period of one (1) year after the termination of this Agreement.</p>
        <div class="page-number">Page 4</div>
      </div>
    </div>

    <!-- PAGE 6: INDEMNIFICATION -->
    <div class="page">
      <div class="header-wave"></div>
      <div class="logo"><img src="/images/logo.png" alt="Logo" width="70px"/></div>
      <div class="content">
        <div class="section-title"><div class="circle-num">12</div><h3>INDEMNIFICATION.</h3></div>
        <!-- DYNAMIC LEGAL -->
        <p>
            ${sectionContent.value['legal']}
        </p>

        <div class="section-title"><div class="circle-num">13</div><h3>WARRANTY.</h3></div>
        <p>The Company shall provide its services and meet its obligations under this Agreement in a timely and workmanlike manner, using knowledge and recommendations for performing the Services which meet generally acceptable standards in the Company's industry.</p>
        <div class="section-title"><div class="circle-num">14</div><h3>COMPLIANCE WITH LAW.</h3></div>
        <p>All work and services rendered hereunder shall be provided in accordance with all applicable ordinances, resolutions, statutes, rules and regulations of the City and any Federal, State or local governmental agency of competent jurisdiction.</p>
        <div class="section-title"><div class="circle-num">15</div><h3>ENTIRE AGREEMENT.</h3></div>
        <p>This Agreement contains the entire agreement of the Parties, and there are no other promises or conditions in any other agreement whether oral or written concerning the subject matter of this Agreement. This Agreement supersedes any prior written or oral agreements between the Parties.</p>
        <div class="section-title"><div class="circle-num">16</div><h3>FORCE MAJEURE.</h3></div>
        <p>In the event that the performance of any of the covenants of this Agreement shall be prevented by an act of God, acts and regulations of public authorities, or labor disputes, acts of the public enemy, acts of superior governmental authority, or other circumstances, or cause beyond their or its reasonable control, the Client and Company shall be respectively relieved of their obligations hereunder with respect to the performance(s) so prevented. In the above-mentioned event, Company grants Client the right to reschedule the performance(s) under the same terms and conditions of this Agreement.</p>
        <div class="section-title"><div class="circle-num">17</div><h3>AMENDMENT.</h3></div>
        <p>This Agreement may be modified or amended in writing, if the writing is signed by the Party obligated under the amendment.</p>
        <div class="page-number">Page 5</div>
      </div>
    </div>

    <!-- PAGE 7: INSURANCE -->
    <div class="page">
      <div class="header-wave"></div>
      <div class="logo"><img src="/images/logo.png" alt="Logo" width="70px"/></div>
      <div class="content">
        <div class="section-title"><div class="circle-num">18</div><h3>GOVERNING LAW.</h3></div>
        <p>This Agreement shall be construed in accordance with the laws of the State of Hic dolore ex iure nisi alias id eum sapiente ips.</p>
        <div class="section-title"><div class="circle-num">19</div><h3>SEVERABILITY.</h3></div>
        <p>The phrases, clauses, sentences, paragraphs or sections of these conditions are severable. If any phrase, clause, sentence, paragraph, or section of these conditions should be declared invalid by the final decree or judgment of any court of competent jurisdiction, such invalidity shall not affect any of the remaining phrases, clauses, sentences, paragraphs and sections of these conditions.</p>
        <div class="section-title"><div class="circle-num">20</div><h3>ATTORNEYS' FEES.</h3></div>
        <p>In case of failure to faithfully perform the terms and covenants herein set forth, the defaulting Party shall pay all costs, expenses, and reasonable attorneys' fees resulting from the enforcement of this Agreement or any right arising out of such breach.</p>
        <div class="section-title"><div class="circle-num">21</div><h3>ASSIGNMENT.</h3></div>
        <p>Neither Party hereto may assign its rights or delegate its obligations hereunder without the written consent of the other Party.</p>
        <div class="section-title"><div class="circle-num">22</div><h3>WAIVER.</h3></div>
        <p>The failure of either party to enforce any provision of this Agreement shall not be construed as a waiver or limitation of that party's right to subsequently enforce and compel strict compliance with every provision of this Agreement.</p>
        <div class="section-title"><div class="circle-num">23</div><h3>INSURANCE CONTRACT</h3></div>
        <!-- DYNAMIC INSURANCE -->
        <p>
            ${sectionContent.value['insurance']}
        </p>
        <div class="page-number">Page 6</div>
      </div>
    </div>

    <!-- PAGE 8: SIGNATURE -->
    <div class="page">
      <div class="header-wave"></div>
      <div class="logo"><img src="/images/logo.png" alt="Logo" width="70px"/></div>
      <div class="content">
        <!-- DYNAMIC SIGNATURE -->
        <p>
            ${sectionContent.value['signature']}
        </p>

        <div class="signature-section">
          <div class="section-title"><div class="circle-num">24</div><h3>IN WITNESS WHEREOF.</h3></div>
          <p>SIGNATURE LINES 24.1</p>
          <div style="display: flex; gap: 60px; margin-top: 50px;">
            <div style="flex: 1;">
              <div class="signature-box">
                <h4 style="color: #22c55e; margin-bottom: 20px;">BELIEVE 313 STAFFING</h4>
                <p style="font-weight: bold;">Company</p>
                <p>By: __________________________</p>
                <p>Name (printed): <strong>MARIO KELLY</strong></p>
                <p>Title: CEO</p>
                <p>Date: __________________________</p>
              </div>
            </div>
            <div style="flex: 1;">
              <div class="signature-box">
                <h4 style="color: #22c55e; margin-bottom: 20px;">GALLAGHER AND ALSTON TRADERS</h4>
                <p style="font-weight: bold;">Client</p>
                <p>By: __________________________</p>
                <p>Name (printed): __________________________</p>
                <p>Title: __________________________</p>
                <p>Date: __________________________</p>
              </div>
            </div>
          </div>
        </div>
        <div class="page-number">Page 7</div>
      </div>
    </div>

    <!-- PAGE 10: EXHIBIT A - BREAK ROOM PART 1 -->
    <div class="page">
      <div class="header-wave"></div>
      <div class="logo"><img src="/images/logo.png" alt="Logo" width="70px"/></div>
      <div class="content">
        <h2 style="color: #22c55e; text-align: center; font-size: 1.8rem; margin-bottom: 40px;">EXHIBIT A SCOPE OF SERVICES</h2>
        <h3 style="color: #22c55e; margin-bottom: 20px;">BREAK ROOM</h3>
        <table class="exhibit-table">
          <tr><td>Air Vents & Fans</td><td>Remove dust from Ceiling fans, air returns and vents.</td><td>1XMONTH</td></tr>
          <tr><td>Buss dishes</td><td>Remove dishes and buss to designated locations</td><td>2XWEEK</td></tr>
          <tr><td>Coffee & Beverage Stations</td><td>Clean the exterior of coffee crafts, clean, empty, and rinse coffee pots, empty coffee grounds, rinse and clean all other beverage crafts</td><td>2XWEEK</td></tr>
          <tr><td>Dispenser</td><td>Replenish paper supplies, soap and hand cleaner. Wipe clean outside surface of dispensers.</td><td>2XWEEK</td></tr>
          <tr><td>Doors</td><td>Wipe Doors, Doors Knobs, Kick Plates, Push Plates, and Frames.</td><td>1XWEEK</td></tr>
          <tr><td>Exterior of Drawers and Cabinets</td><td>Spot clean Outside of Shelves, Drawers/face, Cabinets/face, Credenzas, display case, and apply polish if needed</td><td>1XWEEK</td></tr>
          <tr><td>Interior Microwave</td><td>Thoroughly Clean and degrease interior of microwave removing all food, splashes, and spills</td><td>2XWEEK</td></tr>
          <tr><td>Kitchen Appliances</td><td>Clean exterior surfaces of Ice Machines, Microwaves, Ovens, refrigerators, toasters, Stove tops, dishwashers. grills, mixers, Vending Machines and other designated appliances with food safe cleaning agent.</td><td>2XWEEK</td></tr>
          <tr><td>Routine Dusting</td><td>Routine dusting will be performed on all unobstructed horizontal surfaces between 3' and 8' of height. Personal items will not be moved or disturbed.</td><td>2XWEEK</td></tr>
          <tr><td>Seating</td><td>Wipe or vacuum couches, recliners, loveseats, benches and chairs.</td><td>2XWEEK</td></tr>
        </table>
        <div class="page-number">Page 8</div>
      </div>
    </div>

    <!-- PAGE 11: EXHIBIT A - BREAK ROOM PART 2 -->
    <div class="page">
      <div class="header-wave"></div>
      <div class="logo"><img src="/images/logo.png" alt="Logo" width="70px"/></div>
      <div class="content">
        <table class="exhibit-table">
          <tr><td>Sinks</td><td>Scrub sinks, drains and faucets with non abrasive cleaning agents to remove hard water, stains, soap scum, and rust</td><td>2XWEEK</td></tr>
          <tr><td>Spot Clean Glass</td><td>Spot clean interior windows, door glass and partition glass using microfiber cloths or paper towels leaving glass free of splash, smudges and streaks</td><td>2XWEEK</td></tr>
          <tr><td>Tables & Counters</td><td>Wipe tables and counters to remove all debris or smudges, bus dishes to designated area, discard any trash</td><td>2XWEEK</td></tr>
          <tr><td>Trash & Recycling</td><td>Empty and remove trash and recycling keep seperate; and deposit into appropriate disposal containers. Replace can liners as needed</td><td>2XWEEK</td></tr>
          <tr><td>Trash Splash</td><td>Wipe walls adjacent to trash cans</td><td>2XWEEK</td></tr>
          <tr><td>Walls</td><td>Spot clean Walls, Light Switches, and Electrical outlets to remove smudges and scuff marks</td><td>BI MONTHLY</td></tr>
        </table>
        <div class="page-number">Page 9</div>
      </div>
    </div>

    <!-- PAGE 12: EXHIBIT C - CARPET CLEANING -->
    <div class="page">
      <div class="header-wave"></div>
      <div class="logo"><img src="/images/logo.png" alt="Logo" width="70px"/></div>
      <div class="content">
        <h2 style="color: #22c55e; text-align: center; font-size: 1.8rem; margin-bottom: 40px;">EXHIBIT C ONE-TIME PROJECT WORK</h2>
        <h3 style="color: #22c55e; margin-bottom: 20px;">CARPET - HOT WATER EXTRACT</h3>
        <!-- DYNAMIC EXHIBITS (Bulleted List) -->
        <ul>
            ${formatBullets(sectionContent.value['exhibits'])}
        </ul>
        <div class="page-number">Page 10</div>
      </div>
    </div>

    <!-- PAGE 13: EXHIBIT C TABLE -->
    <div class="page">
      <div class="header-wave"></div>
      <div class="logo"><img src="/images/logo.png" alt="Logo" width="70px"/></div>
      <div class="content">
        <table>
          <thead><tr><th>AREAS SERVICED</th><th>FREQUENCY</th></tr></thead>
          <tbody><tr><td>Whole Building</td><td>1 time</td></tr></tbody>
        </table>
        <div class="page-number">Page 11</div>
      </div>
    </div>
  </div>
</body>
</html>
`.trim())

// DOWNLOAD PDF
const downloadPDF = () => {
  const element = document.getElementById('pdf-content')
  const opt = {
    margin: [0.5, 0.5, 0.5, 0.5],
    filename: 'Believe313_Proposal_GallagherAlstonTraders.pdf',
    image: { type: 'jpeg', quality: 0.98 },
    html2canvas: { 
      scale: 2.8, 
      useCORS: true, 
      letterRendering: true, 
      allowTaint: true,
      backgroundColor: '#ffffff'
    },
    jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
  }
  html2pdf().set(opt).from(element).save()
}
</script>

<style scoped>
.btn-teal { background-color: #17a2b8 !important; }
.text-teal { color: #17a2b8; }
.btn-success { background-color: #22c55e !important; border: none; }
.btn-success:hover { background-color: #16a34a !important; }
</style>