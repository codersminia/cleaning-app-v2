<template>
  <div class="bg-light min-vh-100">
    
    <!-- PUBLIC HEADER (Kept Intact) -->
    <div class="bg-white border-bottom py-3 px-4 shadow-sm sticky-top d-flex justify-content-between align-items-center">
        <div>
            <h5 class="fw-bold mb-0 text-teal" v-if="proposal">Proposal for: {{ proposal.prospect?.company_name }}</h5>
        </div>
        <div v-if="!isSigned">
            <button @click="openSignModal" class="btn btn-teal text-white fw-bold d-flex align-items-center gap-2 pulse-animation">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                CLICK HERE TO SIGN PROPOSAL
            </button>
        </div>
        <div v-else class="text-success fw-bold d-flex align-items-center gap-2">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            SIGNED {{ new Date(signatureData.signed_at).toLocaleDateString() }}
        </div>
    </div>

    <!-- DOCUMENT PREVIEW -->
    <div v-if="loading" class="text-center p-5">
        <div class="spinner-border text-teal" role="status"></div>
        <p class="mt-2 text-muted">Loading Proposal...</p>
    </div>
    
    <div v-else class="container py-5 d-flex justify-content-center">
        <!-- Render the Dynamic HTML -->
        <div class="bg-dark rounded shadow p-4 d-flex flex-column align-items-center gap-4" style="height: 90vh; overflow-y: auto;">
             <div id="pdf-content" v-html="fullPdfHtml"></div>
        </div>
    </div>

    <!-- SIGN MODAL -->
    <SignProposalModal :isOpen="showSignModal" @close="showSignModal = false" @signed="handleSigned" />

  </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount, nextTick, computed } from 'vue'
import { useRoute } from 'vue-router'
import axios from 'axios'
import SignProposalModal from './Modals/SignProposalModal.vue'

const route = useRoute()
const token = route.query.token 
const recipientToken = route.query.rid 

// --- STATE ---
const loading = ref(true)
const proposal = ref(null)
const projects = ref([]) // Dynamic Projects
const calcData = ref({}) // Calculator Data
const sectionContent = ref({}) // Text Content
const isJanitorial = ref(false)
const isConstruction = ref(false)

// Signature State
const showSignModal = ref(false)
const isSigned = ref(false)
const signatureData = ref(null)

// Tracking State
let trackingInterval = null
let observer = null
const currentSection = ref('Cover Letter')

// --- 1. FETCH DATA ---
onMounted(async () => {
    try {
        const res = await axios.get(`/api/proposal/${token}`)
        
        // 1. Basic Data
        proposal.value = res.data.proposal
        projects.value = res.data.projects
        isJanitorial.value = res.data.is_janitorial
        isConstruction.value = res.data.is_construction
        
        // 2. Content Data (Text Sections)
        sectionContent.value = res.data.proposal.content_data || {}

        // 3. Calculator Data (Pricing)
        if (proposal.value.calculations && proposal.value.calculations.calculator_data) {
             calcData.value = typeof proposal.value.calculations.calculator_data == 'string' 
                ? JSON.parse(proposal.value.calculations.calculator_data) 
                : proposal.value.calculations.calculator_data;
        }
        
        // 4. Signature Check
        if(res.data.signature) {
            isSigned.value = true
            signatureData.value = res.data.signature
        }

        // 5. Tracking Init
        await nextTick()
        setupIntersectionObserver()
        startTrackingTimer()

    } catch (e) {
        console.error(e)
        // alert("Invalid Link") 
    } finally {
        loading.value = false
    }
})

// Cleanup
onBeforeUnmount(() => {
    if (trackingInterval) clearInterval(trackingInterval)
    if (observer) observer.disconnect()
})

// --- 2. CALCULATION LOGIC (Exact copy from Finalize) ---

// Helpers
const formatCurrency = (value) => new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(value);
const formatBullets = (text) => {
    if(!text) return '';
    return text.split('\n').filter(line => line.trim() !== '').map(line => `<li>${line}</li>`).join('');
}

// Janitorial Total
const janitorialMonthlyTotal = computed(() => {
    if (!calcData.value.laborCosts) return 0;
    const laborSum = calcData.value.laborCosts.reduce((sum, cost) => {
        const base = (parseFloat(cost.staff)||0) * (parseFloat(cost.rateOfPay)||0) * (parseFloat(cost.hours)||0);
        const payrollTotal = (parseFloat(calcData.value.payrollTaxes)||0) + (parseFloat(calcData.value.insurance)||0) + (parseFloat(calcData.value.overhead)||0);
        const costPerClean = base * (1 + (payrollTotal / 100));
        let monthly = 0;
        const freq = parseFloat(cost.frequency)||0;
        const per = cost.per;
        if(per == 'Week') monthly = costPerClean * (freq * 4.333);
        else if(per == 'Month') monthly = costPerClean * freq;
        else if(per == 'Quarter') monthly = costPerClean * (freq / 3);
        else if(per == 'Year') monthly = costPerClean * (freq / 12);
        else monthly = costPerClean * freq;
        return sum + monthly;
    }, 0);
    const expensesSum = (calcData.value.additionalExpenses || []).reduce((sum, item) => sum + (parseFloat(item.cost)||0), 0);
    const subTotal = laborSum + expensesSum;
    const profit = parseFloat(calcData.value.marginDollar) || 0;
    let tax = 0;
    if (calcData.value.addSalesTax) tax = (subTotal + profit) * ((parseFloat(calcData.value.salesTaxPercent)||0) / 100);
    return subTotal + profit + tax;
});

// Project Total
const calculateProjectTotal = (proj, isRecurring) => {
    const base = (parseFloat(proj.staff)||0) * (parseFloat(proj.rateOfPay)||0) * (parseFloat(proj.hours)||0);
    const payrollTotal = (parseFloat(calcData.value.payrollTaxes)||0) + (parseFloat(calcData.value.insurance)||0) + (parseFloat(calcData.value.overhead)||0);
    const laborCost = base * (1 + (payrollTotal / 100));
    let finalLabor = laborCost;
    if (isRecurring) {
         const freq = parseFloat(proj.frequency)||0;
         const per = proj.per ? proj.per.toLowerCase() : '';
         if(per == 'week') finalLabor = laborCost * (freq * 4.333);
         else if(per == 'month') finalLabor = laborCost * freq;
         else if(per == 'quarter') finalLabor = laborCost * (freq / 3);
         else if(per == 'year') finalLabor = laborCost * (freq / 12);
         else finalLabor = laborCost * freq; 
    }
    const expenses = (proj.expenses || []).reduce((sum, e) => sum + (parseFloat(e.cost)||0), 0);
    const sub = finalLabor + expenses;
    const profit = parseFloat(proj.marginDollar) || 0;
    let tax = 0;
    if (proj.addSalesTax) tax = (sub + profit) * ((parseFloat(proj.salesTaxPercent)||0) / 100);
    return sub + profit + tax;
};

const recurringGlobalTotal = computed(() => {
    if (!calcData.value.recurringProjects) return 0;
    return calcData.value.recurringProjects.reduce((sum, proj) => sum + calculateProjectTotal(proj, true), 0);
});

const grandMonthlyTotal = computed(() => janitorialMonthlyTotal.value + recurringGlobalTotal.value);

// --- 3. DYNAMIC HTML GENERATORS ---

const clientName = computed(() => proposal.value?.prospect?.company_name || 'Client Name');
const clientEmail = computed(() => proposal.value?.prospect?.email || 'Email');
const clientAddress = computed(() => {
    const p = proposal.value?.prospect;
    if(!p) return 'Client Address';
    return `${p.address}, ${p.city}, ${p.state}, ${p.zip}`;
});
const currentDate = computed(() => new Date().toLocaleDateString());

// Tables
const recurringProjectRows = computed(() => {
    const projs = calcData.value.recurringProjects || [];
    if (projs.length == 0) return '';
    return projs.map(proj => `<tr><td style="text-align:left; padding:12px; border:1px solid #ddd;">${proj.name}</td><td style="text-align:center; padding:12px; border:1px solid #ddd;">${proj.frequency} x ${proj.per}</td><td class="price" style="text-align:center; padding:12px; border:1px solid #ddd; font-weight:bold; color:#22c55e;">${formatCurrency(calculateProjectTotal(proj, true))}</td></tr>`).join('');
});

const oneTimeProjectRows = computed(() => {
    const projs = calcData.value.oneTimeProjects || [];
    if (projs.length == 0) return '';
    return projs.map(proj => `<tr><td style="text-align:left; padding:12px; border:1px solid #ddd;">${proj.name}</td><td style="text-align:center; padding:12px; border:1px solid #ddd;">1 Time</td><td class="price" style="text-align:center; padding:12px; border:1px solid #ddd; font-weight:bold; color:#22c55e;">${formatCurrency(calculateProjectTotal(proj, false))}</td></tr>`).join('');
});

const exhibitARows = computed(() => {
    if (!proposal.value?.proposal_areas || proposal.value.proposal_areas.length == 0) return '<tr><td colspan="3" style="text-align:center;">No Janitorial Areas</td></tr>';
    let rows = '';
    proposal.value.proposal_areas.forEach(area => {
        const areaName = area.area_type?.name || 'Area';
        area.area_tasks.forEach(at => {
            const desc = at.custom_description || at.task?.description || '';
            let freq = at.custom_frequency ? at.custom_frequency.label : (at.task?.default_frequency?.label || 'Daily');
            rows += `<tr><td style="padding:10px; border:1px solid #ddd;"><strong>${areaName}</strong></td><td style="padding:10px; border:1px solid #ddd;">${desc}</td><td style="padding:10px; border:1px solid #ddd;">${freq}</td></tr>`;
        });
    });
    return rows;
});

// Exhibits B & C
const getProjectExhibitHtml = (isRecurring) => {
    const relevantProjects = projects.value.filter(p => !!p.is_recurring == isRecurring);
    if (relevantProjects.length == 0) return '';
    let html = '';
    relevantProjects.forEach(proj => {
        const projName = proj.service_type?.name || 'Project';
        const tasks = proj.project_tasks || [];
        html += `<h4 style="color:#22c55e; margin-top:20px;">${projName}</h4>
                 <table style="width:100%; border-collapse:collapse; margin-bottom:20px;">
                    <thead><tr><th style="background:#f0f0f0; color:#333; border:1px solid #ddd;">Task Description</th></tr></thead>
                    <tbody>`;
        if (tasks.length > 0) {
            tasks.forEach(pt => {
                const desc = pt.task_description || pt.task?.description || 'No Description';
                html += `<tr><td style="padding:10px; border:1px solid #ddd;">${desc}</td></tr>`;
            });
        } else {
            html += `<tr><td style="padding:10px; border:1px solid #ddd;">See Exhibit C standard procedures.</td></tr>`;
        }
        html += `</tbody></table>`;
    });
    return html;
}
const exhibitBContent = computed(() => getProjectExhibitHtml(true));
const exhibitCContent = computed(() => getProjectExhibitHtml(false));


// --- 4. MASTER HTML GENERATOR ---
const fullPdfHtml = computed(() => {
    if(!proposal.value) return '';

    // 4a. Manual Signature Block
    const manualSignatureHtml = `
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
                        <h4 style="color: #22c55e; margin-bottom: 20px;">${clientName.value}</h4>
                        <p style="font-weight: bold;">Client</p>
                        <p>By: __________________________</p>
                        <p>Name (printed): __________________________</p>
                        <p>Title: __________________________</p>
                        <p>Date: __________________________</p>
                    </div>
                </div>
            </div>
        </div>
    `;

    // 4b. Digital Signature Block
    const digitalSignatureHtml = `
        <div class="signature-section" style="border: 3px solid #22c55e; background-color: #f0fff4; margin-top: 60px; padding: 40px; border-radius: 16px;">
            <div class="section-title"><div class="circle-num" style="background: #22c55e;">✔</div><h3>AGREEMENT EXECUTED.</h3></div>
            <div style="display: flex; gap: 60px; margin-top: 30px;">
                <div style="flex: 1;">
                    <div class="signature-box" style="background: white; border: 1px solid #22c55e; padding: 20px; text-align:center;">
                        <h4 style="color: #22c55e; margin-bottom: 10px;">BELIEVE 313 STAFFING</h4>
                        <p style="margin: 0;"><strong>MARIO KELLY</strong> (CEO)</p>
                        <p style="color: #666; font-size: 0.9rem;">Auto-Signed upon acceptance</p>
                    </div>
                </div>
                <div style="flex: 1;">
                    <div class="signature-box" style="background: white; border: 2px solid #22c55e; padding: 20px; text-align:center;">
                        <h4 style="color: #22c55e; margin-bottom: 5px;">ACCEPTED BY CLIENT</h4>
                        <img src="${signatureData.value?.signature_image}" style="max-height: 80px; margin: 10px 0;" alt="Signature" />
                        <div style="text-align: left; font-size: 0.9rem;">
                            <p>Name: <strong>${signatureData.value?.signer_name}</strong></p>
                            <p>Title: <strong>${signatureData.value?.signer_title}</strong></p>
                            <p>Date: ${new Date(signatureData.value?.signed_at).toLocaleString()}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;

    // 4c. The Document
    return `
        <!DOCTYPE html>
        <html lang="en">
        <head>
        <meta charset="UTF-8">
        <style>
            @page { margin: 0; size: letter; }
            body { margin:0; padding:0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background:#f8f9fa; }
            .page { width: 8.5in; height: 11in; background: white; position: relative; page-break-after: always; margin: 0 auto 30px auto; overflow: hidden; }
            /* Graphics */
            .header-wave { position: absolute; top:0; left:0; right:0; height:140px; background: #22c55e; border-radius: 0 0 100% 0; z-index:1; }
            .logo { position: absolute; top:30px; right:50px; z-index:10; }
            .logo img { width:100px; }
            /* Content */
            .content { padding: 180px 60px 80px 60px; line-height: 1.6; color: #333; font-size: 14px; }
            /* Components */
            .section-title { display:flex; align-items:center; margin:25px 0 10px; }
            .circle-num { display:inline-flex; align-items:center; justify-content:center; width:30px; height:30px; border-radius:50%; background:#22c55e; color:white; font-weight:bold; font-size:1rem; margin-right:15px; }
            .section-title h3 { margin:0; color:#333; font-size:1.2rem; }
            table { width:100%; border-collapse:collapse; margin:15px 0; font-size:13px; }
            th { background:#22c55e; color:white; padding:10px; text-align:center; font-weight:bold; text-transform:uppercase; }
            td { padding:10px; border:1px solid #ddd; }
            .price { font-weight:bold; color:#22c55e; font-size:1.1rem; }
            .page-number { position:absolute; bottom:40px; right:60px; color:#888; font-size:12px; }
            /* Cover */
            .cover-overlay { position: absolute; bottom:0; left:0; width:100%; height:35%; background: linear-gradient(to top, #22c55e 0%, transparent 100%); border-radius: 100% 0 0 0; }
            .cover-text { position: absolute; bottom:80px; left:50%; transform:translateX(-50%); text-align:center; color:white; width:90%; z-index:5; }
            .btn-website { background:#f39c12; color:white; padding:15px 40px; border-radius:50px; font-weight:bold; font-size:1.2rem; border:none; margin-top:20px; }
        </style>
        </head>
        <body>

        <!-- PAGE 1: COVER -->
        <div class="page" data-section="Cover">
            <img src="/images/cover10.jpg" style="width:100%; height:100%; object-fit:cover; position:absolute;" alt="Cover">
            <div class="logo"><img src="/images/logo.png" alt="Logo"></div>
            <div class="cover-overlay"></div>
            <div class="cover-text">
            <h3>${clientEmail.value}</h3>
            <p>Prepared For: ${clientName.value}<br>${currentDate.value}<br>${clientAddress.value}</p>
            <button class="btn-website">believe313staffing.com</button>
            </div>
        </div>

        <!-- PAGE 2: INTRO -->
        <div class="page" data-section="Intro">
            <div class="header-wave"></div>
            <div class="logo"><img src="/images/logo.png" alt="Logo"></div>
            <div class="content">
                <h3 style="color:#22c55e; font-size:2.2rem; margin-bottom:30px;">${clientName.value}</h3>
                <p>${sectionContent.value['cover-letter']}</p>
                <div style="display: flex; gap: 40px; margin-top:30px;">
                <div style="flex: 1;">
                    <h4>CUSTOMER SERVICE.</h4><p>Regular on site quality control is designed to anticipate the client's needs...</p>
                    <h4>CONSISTENT QUALITY.</h4><p>Consistency and Quality are the pillars of our service standards...</p>
                </div>
                <div style="flex: 1;">
                    <h4>EXPERIENCE.</h4><p>Over the years we worked hard at not just solving existing problems...</p>
                    <h4>RESOURCES.</h4><p>Since we serve facilities and buildings just like yours...</p>
                </div>
                </div>
                <div style="margin-top: 60px; text-align: center;">
                <h4 style="color:#22c55e;">HUMBLY SUBMITTED</h4>
                <p style="font-weight: bold; font-size: 1.2rem;">Mario Kelly<br>CEO<br>Believe 313 staffing</p>
                </div>
                <div class="page-number">Page 1</div>
            </div>
        </div>

        <!-- PAGE 3: AGREEMENT -->
        <div class="page" data-section="Agreement">
            <div class="header-wave"></div>
            <div class="logo"><img src="/images/logo.png" alt="Logo"></div>
            <div class="content">
                <div class="section-title"><div class="circle-num">1</div><h3>THIS CLEANING SERVICES AGREEMENT</h3></div>
                <p>${sectionContent.value['agreement']}</p>
                <div class="section-title"><div class="circle-num">2</div><h3>CLEANING SERVICES & LOCATION</h3></div>
                <p><strong>2.1 SERVICES:</strong> See EXHIBIT A (Scope of Services) and EXHIBIT C (Projects).</p>
                <p><strong>2.2 LOCATION:</strong> ${clientAddress.value}</p>
                <div class="section-title"><div class="circle-num">3</div><h3>SERVICE COMPENSATION</h3></div>
                <p>${sectionContent.value['compensation']}</p>
                <p><strong>3.1 PRICING.</strong> Client shall compensate Company for the following Services at the prices listed below.</p>
                <div class="page-number">Page 2</div>
            </div>
        </div>

        <!-- PAGE 4: PRICING -->
        <div class="page" data-section="Pricing">
            <div class="header-wave"></div>
            <div class="logo"><img src="/images/logo.png" alt="Logo"></div>
            <div class="content">
                ${ (isJanitorial.value || !isConstruction.value) ? `
                    <p><strong>JANITORIAL SERVICES</strong><br>See EXHIBIT A for specific services to be rendered</p>
                    <table>
                    <thead><tr><th>AREA SERVICED</th><th>FREQUENCY</th><th>MONTHLY PRICE</th></tr></thead>
                    <tbody><tr><td>Whole Facility (See Exhibit A)</td><td>Various</td><td class="price">${formatCurrency(janitorialMonthlyTotal.value)}</td></tr></tbody>
                    </table>
                ` : '' }

                ${ recurringProjectRows.value ? `
                    <p><strong>RECURRING PROJECT SERVICES (See EXHIBIT B)</strong></p>
                    <table>
                    <thead><tr><th>PROJECT SERVICE</th><th>FREQUENCY</th><th>MONTHLY PRICE</th></tr></thead>
                    <tbody>${recurringProjectRows.value}</tbody>
                    </table>
                ` : '' }

                ${ (isJanitorial.value && recurringProjectRows.value) ? `
                    <div style="text-align:right; margin-bottom:20px;">
                        <span style="font-weight:bold; font-size:1.1rem;">TOTAL MONTHLY CHARGE: <span style="color:#22c55e;">${formatCurrency(grandMonthlyTotal.value)}</span></span>
                    </div>
                ` : '' }

                ${ oneTimeProjectRows.value ? `
                    <div class="section-title"><div class="circle-num">4</div><h3>ONE TIME PROJECT WORK COMPENSATION</h3></div>
                    <p>Client shall compensate Company for the following project work as invoiced.</p>
                    <table>
                    <thead><tr><th>PROJECT TYPE</th><th>FREQUENCY</th><th>PRICE</th></tr></thead>
                    <tbody>${oneTimeProjectRows.value}</tbody>
                    </table>
                ` : '' }

                <div class="section-title"><div class="circle-num">5</div><h3>EQUIPMENT & SUPPLIES</h3></div>
                <p><strong>5.1 EQUIPMENT</strong> Company will furnish all labor, transportation, equipment...</p>
                <p><strong>5.2 SUPPLIES</strong> Client will provide consumable products...</p>
                
                <div class="section-title"><div class="circle-num">6</div><h3>TERM & TERMINATION</h3></div>
                <p><strong>6.1 TERM</strong> This Agreement shall be effective upon execution...</p>
                <div class="page-number">Page 3</div>
            </div>
        </div>

        <!-- PAGE 5-6: LEGAL -->
        <div class="page" data-section="Legal 1">
            <div class="header-wave"></div>
            <div class="logo"><img src="/images/logo.png" alt="Logo"></div>
            <div class="content">
                <p><strong>6.2 TERMINATION</strong>...</p>
                <div class="section-title"><div class="circle-num">7</div><h3>KEYS</h3></div><p>Client shall issue essential keys...</p>
                <div class="section-title"><div class="circle-num">8</div><h3>ACCESS</h3></div><p>The hours of service shall be...</p>
                <div class="section-title"><div class="circle-num">9</div><h3>PHOTO IDENTIFICATION</h3></div><p>Employees wear ID badges...</p>
                <div class="section-title"><div class="circle-num">10</div><h3>INDEPENDENT CONTRACTOR</h3></div><p>Neither Party shall...</p>
                <div class="section-title"><div class="circle-num">11</div><h3>NON-SOLICITATION</h3></div><p>Client agrees not to...</p>
                <div class="page-number">Page 4</div>
            </div>
        </div>

        <div class="page" data-section="Legal 2">
            <div class="header-wave"></div>
            <div class="logo"><img src="/images/logo.png" alt="Logo"></div>
            <div class="content">
                <div class="section-title"><div class="circle-num">12</div><h3>INDEMNIFICATION</h3></div><p>${sectionContent.value['legal']}</p>
                <div class="section-title"><div class="circle-num">13</div><h3>WARRANTY</h3></div><p>Services performed in workmanlike manner...</p>
                <div class="section-title"><div class="circle-num">14</div><h3>COMPLIANCE WITH LAW</h3></div><p>Work performed in accordance...</p>
                <div class="section-title"><div class="circle-num">15</div><h3>ENTIRE AGREEMENT</h3></div><p>Contains entire agreement...</p>
                <div class="section-title"><div class="circle-num">16</div><h3>FORCE MAJEURE</h3></div><p>Performance excused by acts of God...</p>
                <div class="section-title"><div class="circle-num">17</div><h3>AMENDMENT</h3></div><p>Modifications must be in writing.</p>
                <div class="page-number">Page 5</div>
            </div>
        </div>

        <!-- PAGE 7: SIGNATURE -->
        <div class="page" data-section="Signature">
            <div class="header-wave"></div>
            <div class="logo"><img src="/images/logo.png" alt="Logo"></div>
            <div class="content">
                <div class="section-title"><div class="circle-num">18</div><h3>GOVERNING LAW</h3></div>
                <div class="section-title"><div class="circle-num">19</div><h3>SEVERABILITY</h3></div>
                <div class="section-title"><div class="circle-num">20</div><h3>ATTORNEYS' FEES</h3></div>
                <div class="section-title"><div class="circle-num">21</div><h3>ASSIGNMENT</h3></div>
                <div class="section-title"><div class="circle-num">22</div><h3>WAIVER</h3></div>
                <div class="section-title"><div class="circle-num">23</div><h3>INSURANCE CONTRACT</h3></div>
                <p>${sectionContent.value['insurance']}</p>
                
                ${ isSigned.value ? digitalSignatureHtml : manualSignatureHtml }
                
                <div class="page-number">Page 6</div>
            </div>
        </div>

        <!-- PAGE 8: EXHIBIT A -->
        <div class="page" data-section="Exhibit A">
            <div class="header-wave"></div>
            <div class="logo"><img src="/images/logo.png" alt="Logo"></div>
            <div class="content">
                <h2 style="color: #22c55e; text-align: center; margin-bottom:30px;">EXHIBIT A: SCOPE OF SERVICES</h2>
                ${ (isJanitorial.value || !isConstruction.value) ? `
                    <table><thead><tr><th width="30%">AREA</th><th width="50%">TASK DESCRIPTION</th><th width="20%">FREQUENCY</th></tr></thead><tbody>${exhibitARows.value}</tbody></table>
                ` : '<p style="text-align:center;">No Janitorial Services.</p>' }
                <div class="page-number">Page 7</div>
            </div>
        </div>

        <!-- PAGE 9: EXHIBIT B -->
        <div class="page" data-section="Exhibit B">
            <div class="header-wave"></div>
            <div class="logo"><img src="/images/logo.png" alt="Logo"></div>
            <div class="content">
                <h2 style="color: #22c55e; text-align: center; margin-bottom:30px;">EXHIBIT B: RECURRING PROJECT WORK</h2>
                ${ exhibitBContent.value ? exhibitBContent.value : '<p style="text-align:center;">No Recurring Projects.</p>' }
                <div class="page-number">Page 8</div>
            </div>
        </div>

        <!-- PAGE 10: EXHIBIT C -->
        <div class="page" data-section="Exhibit C">
            <div class="header-wave"></div>
            <div class="logo"><img src="/images/logo.png" alt="Logo"></div>
            <div class="content">
                <h2 style="color: #22c55e; text-align: center; margin-bottom:30px;">EXHIBIT C: ONE-TIME PROJECT WORK</h2>
                ${ exhibitCContent.value ? exhibitCContent.value : '<p style="text-align:center;">No One-Time Projects.</p>' }
                ${ oneTimeProjectRows.value ? `
                    <h3 style="color:#22c55e; margin-top:40px;">Pricing Summary</h3>
                    <table><thead><tr><th>AREAS SERVICED</th><th>FREQUENCY</th><th>PRICE</th></tr></thead><tbody>${oneTimeProjectRows.value}</tbody></table>
                ` : '' }
                <div class="page-number">Page 9</div>
            </div>
        </div>

        </body>
        </html>
    `
})

// --- 5. FUNCTIONALITY (Signature & Tracking) ---

const openSignModal = () => showSignModal.value = true

const handleSigned = async (data) => {
    try {
        const res = await axios.post(`/api/proposal/${token}/sign`, {
            signer_name: data.name,
            signer_title: data.title,
            signature_image: data.signatureImage
        })
        
        if(res.data.success) {
            isSigned.value = true
            showSignModal.value = false
            signatureData.value = {
                signer_name: data.name,
                signer_title: data.title,
                signature_image: data.signatureImage,
                signed_at: new Date().toISOString(),
                ip_address: '127.0.0.1' // Server fills this
            }
            window.location.reload()
        }
    } catch (e) {
        alert("Error signing proposal")
    }
}

// Tracking Observer
const setupIntersectionObserver = () => {
    const pages = document.querySelectorAll('.page')
    if (pages.length == 0) return
    observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                const sectionName = entry.target.getAttribute('data-section')
                if (sectionName) currentSection.value = sectionName
            }
        })
    }, { threshold: 0.5 })
    pages.forEach((page) => observer.observe(page))
}

const startTrackingTimer = () => {
    if (!recipientToken) return 
    trackingInterval = setInterval(() => {
        axios.post('/api/track-activity', {
            rid: recipientToken,
            section: currentSection.value,
            seconds: 5
        }).catch(err => {})
    }, 5000)
}
</script>

<style scoped>
.text-teal { color: #17a2b8; }
.btn-teal { background-color: #17a2b8; border: none; }
.btn-teal:hover { background-color: #138496; }
.pulse-animation { animation: pulse 2s infinite; }
@keyframes pulse {
    0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(23, 162, 184, 0.7); }
    70% { transform: scale(1.05); box-shadow: 0 0 0 10px rgba(23, 162, 184, 0); }
    100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(23, 162, 184, 0); }
}
/* Ensure Preview looks like pages */
#pdf-content { background-color: #525659; padding: 20px; width: 100%; display: flex; flex-direction: column; align-items: center; }
</style>