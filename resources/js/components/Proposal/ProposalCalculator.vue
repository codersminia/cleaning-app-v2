<template>
  <div class="price-calculator-container">
    <!-- Header Section with Light Gray Background -->
    <div class="bg-light pb-4 mb-4">
      <div class="container-fluid">
        <div class="row align-items-center">
          <!-- Go Back Button -->
          <div class="col-md-3">
            <button class="btn btn-outline-secondary btn-sm">
              <span class="text-info">←</span> Go Back
            </button>
          </div>
          <!-- Center Title -->
          <div class="col-md-6 text-center">
            <h4 class="fw-bold" style="font-size: 2.5rem; letter-spacing: 0.05em;">PRICE CALCULATOR</h4>
            <p class="text-muted mb-0">Calculate your costs before finishing</p>
          </div>
          <!-- Next Step Button -->
          <div class="col-md-3 text-end">
            <button class="btn btn-outline-secondary btn-sm">
              Im done here <span class="text-info">→</span>
            </button>
          </div>
        </div>
      </div>
    </div>
    <!-- Options Section -->
    <div class="container pb-5">
      <div class="row g-4">
        <div class="col-md-2"></div>
        <!-- Option 1 Card -->
        <div class="col-md-4">
          <div class="card border-0 option-card bg-info text-white" :class="{ 'shadow-lg': selectedOption === '1' }">
            <div class="card-body">
              <div class="d-flex gap-3">
                <!-- Icon Circle -->
                <div class="icon-wrapper flex-shrink-0">
                  <div class="rounded-circle bg-white bg-opacity-25 d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
                    <i class="bi bi-gear text-white" style="font-size: 24px;"></i>
                    <input style="width:25px;height:25px;" name="type" type="radio" v-model="selectedOption" value="1">
                  </div>
                </div>
                <!-- Text Content -->
                <div class="flex-grow-1">
                  <p class="fw-bold mb-2 text-white">OPTION 1: DIY ADVANCED COST ESTIMATOR</p>
                  <p class="mb-0" >Customers Can Estimate Costs Themselves By Entering Labor And Material Details With This Option.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- Option 2 Card -->
        <div class="col-md-4">
          <div class="card border-0 option-card bg-info text-white" :class="{ 'shadow-lg': selectedOption === '2' }">
            <div class="card-body">
              <div class="d-flex gap-3">
                <!-- Icon Circle -->
                <div class="icon-wrapper flex-shrink-0">
                  <div class="rounded-circle bg-white bg-opacity-25 d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
                    <i class="bi bi-gear text-white" style="font-size: 24px;"></i>
                     <input style="width:25px;height:25px;" name="type" type="radio" v-model="selectedOption" value="2">
                  </div>
                </div>
                <!-- Text Content -->
                <div class="flex-grow-1">
                  <p class="fw-bold mb-2 text-white">OPTION 2: AUTOMATED PRICE ESTIMATOR</p>
                  <p class="mb-0">With This Option, Our Software Automates Pricing For Customers, Saving Them Time.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-2"></div>
      </div>
    </div>
    <!-- Payroll Cost Factors Section -->
        <div v-if="payrollExpanded && selectedOption === '1'" class="payroll-section mt-4">
            <!-- Header with Toggle Button -->
            <div class="d-flex justify-content-between align-items-center mb-3">
              <h5 class="fw-bold mb-0">Payroll Cost Factors</h5>
            </div>
            <!-- Collapsible Content -->
            <div class="payroll-content p-4 border">
              <p class="text-muted mb-4">Ex: Payroll tax and Work Comp costs that factor into your payroll hourly rate</p>
              <!-- Three Input Fields Row -->
              <div class="row mb-4">
                <!-- Payroll Taxes -->
                <div class="col-md-4">
                  <label class="form-label fw-bold text-uppercase" style="font-size: 0.85rem;">Payroll Taxes</label>
                  <div class="input-group">
                    <input type="number" class="form-control" placeholder="0.00" v-model="payrollTaxes">
                    <span class="input-group-text">%</span>
                    <button class="btn btn-link text-teal pl-2">
                      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 5V19M5 12H19" stroke="#17a2b8" stroke-width="2" stroke-linecap="round"/>
                      </svg>
                    </button>
                  </div>
                </div>
                <!-- Insurance -->
                <div class="col-md-4" style="padding-left: 0px;">
                  <label class="form-label fw-bold text-uppercase" style="font-size: 0.85rem;">Insurance</label>
                  <div class="input-group">
                    <input type="number" class="form-control" placeholder="0.00" v-model="insurance">
                    <span class="input-group-text">%</span>
                    <button class="btn btn-link text-teal pl-2">
                      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 5V19M5 12H19" stroke="#17a2b8" stroke-width="2" stroke-linecap="round"/>
                      </svg>
                    </button>
                  </div>
                </div>
                <!-- Overhead -->
                <div class="col-md-4" style="padding-left: 0px;">
                  <label class="form-label fw-bold text-uppercase" style="font-size: 0.85rem;">Overhead</label>
                  <div class="input-group">
                    <input type="number" class="form-control" placeholder="0.00" v-model="overhead">
                    <span class="input-group-text">%</span>
                  </div>
                </div>
              </div>
              <!-- Total Row -->
              <div class="row bg-secondary-light p-3 rounded">
                <div class="col-md-6">
                  <span class="fw-bold">Total</span>
                </div>
                <div class="col-md-6 text-end">
                  <span class="fw-bold">{{ totalPayroll.toFixed(2) }}%</span>
                </div>
              </div>
            </div>
        </div>
        <div class="labor-section bg-light py-4" v-if="selectedOption === '1'">
            <div class="row align-items-center">
              <!-- Expand All Toggle -->
              <div class="col-md-3">
                <div class="d-flex align-items-center gap-2">
                  <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="expandAll">
                  </div>
                  <label class="form-check-label" for="expandAll">
                    Expand all
                  </label>
                </div>
              </div>
              <!-- Title Section -->
              <div class="col-md-6 text-center">
                <div class="d-flex justify-content-center align-items-center gap-3">
                  <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="20" cy="12" r="6" stroke="#17a2b8" stroke-width="2"/>
                    <path d="M8 32C8 25.37 13.37 20 20 20C26.63 20 32 25.37 32 32" stroke="#17a2b8" stroke-width="2" stroke-linecap="round"/>
                  </svg>
                  <div>
                    <h4 class="mb-0 fw-bold text-dark">Janitorial Labor Costs</h4>
                  </div>
                  <button class="btn btn-link p-0" @click="payrollExpanded = !payrollExpanded" style="background: none; border: none; cursor: pointer;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="12" cy="5" r="2" fill="#6c757d"/>
                    <circle cx="12" cy="12" r="2" fill="#6c757d"/>
                    <circle cx="12" cy="19" r="2" fill="#6c757d"/>
                    </svg>
                </button>
                </div>
              </div>
              <!-- Add New Button -->
              <div class="col-md-3 text-end">
                <button class="btn btn-teal text-white px-4 py-2 fw-bold" @click="openLaborCostModal">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-right: 8px; display: inline;">
                    <circle cx="12" cy="12" r="6" stroke="white" stroke-width="2"/>
                    <path d="M8 32C8 25.37 13.37 20 20 20C26.63 20 32 25.37 32 32" stroke="white" stroke-width="2" stroke-linecap="round"/>
                  </svg>
                  ADD A NEW LABOR COST
                </button>
              </div>
            </div>
        </div>
        <!-- Labor Cost Card Section -->
        <div class="mt-5" v-if="selectedOption === '1'">
          <!-- Labor Cost Card Collapsed View -->
          <div v-if="!laborCardExpanded" class="labor-cost-card bg-white p-4 rounded border-0 shadow-sm">
            <div class="row justify-content-around align-items-center g-0">
              <!-- Labor Cost Label and Title -->
              <div class="col-md-2 pe-4 border-end">
                <label class="text-uppercase small text-muted fw-bold" style="font-size: 0.75rem; letter-spacing: 0.05em;">Labor Cost</label>
                <h5 class="fw-bold mt-2 mb-0">Custodians</h5>
              </div>
              <!-- Cost Per Clean -->
              <div class="col-md-2 px-4 border-end">
                <label class="text-uppercase small text-muted fw-bold" style="font-size: 0.75rem; letter-spacing: 0.05em;">Cost Per Clean</label>
                <p class="fw-bold mb-0" style="font-size: 1.25rem;">$0.00</p>
              </div>
              <!-- Annual Cost -->
              <div class="col-md-2 px-4 border-end">
                <label class="text-uppercase small text-muted fw-bold" style="font-size: 0.75rem; letter-spacing: 0.05em;">Annual Cost</label>
                <p class="fw-bold mb-0" style="font-size: 1.25rem;">$0.00</p>
              </div>
              <!-- Monthly -->
              <div class="col-md-2 px-4 border-end">
                <label class="text-uppercase small text-muted fw-bold" style="font-size: 0.75rem; letter-spacing: 0.05em;">Monthly</label>
                <p class="fw-bold mb-0" style="font-size: 1.25rem;">$0.00</p>
              </div>
              <!-- Show Cost Details and Actions -->
              <div class="col-md-2 d-flex justify-content-around align-items-center">
                <a href="#" class="text-teal text-decoration-none fw-bold small" @click.prevent="laborCardExpanded = true">SHOW COST DETAILS</a>
                <button class="btn btn-sm btn-teal text-white rounded-circle" style="width: 20px; height: 20px; padding: 0; display: flex; align-items: center; justify-content: center;" @click="laborCardExpanded = true">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M7 10L12 15L17 10" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                  </svg>
                </button>
                <button class="btn btn-link p-0" style="background: none; border: none;">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="12" cy="5" r="2" fill="#6c757d"/>
                    <circle cx="12" cy="12" r="2" fill="#6c757d"/>
                    <circle cx="12" cy="19" r="2" fill="#6c757d"/>
                  </svg>
                </button>
              </div>
            </div>
          </div>
          <!-- Labor Cost Card Expanded View -->
          <div v-if="laborCardExpanded" class="labor-cost-card bg-white p-4 rounded border-0 shadow-sm">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
              <div>
                <label class="text-uppercase small text-muted fw-bold" style="font-size: 0.75rem; letter-spacing: 0.05em;">Labor Cost</label>
                <h5 class="fw-bold mt-2 mb-0">Custodians</h5>
              </div>
              <button class="btn btn-link p-0" style="background: none; border: none;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <circle cx="12" cy="5" r="2" fill="#6c757d"/>
                  <circle cx="12" cy="12" r="2" fill="#6c757d"/>
                  <circle cx="12" cy="19" r="2" fill="#6c757d"/>
                </svg>
              </button>
            </div>
            <!-- Expanded Content with Input Fields -->
            <div class="row mb-4 pb-4 border-bottom">
              <!-- Labor Type -->
              <div class="col-md-2">
                <label class="form-label fw-bold text-teal" style="font-size: 0.9rem;">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-right: 6px; display: inline;">
                    <circle cx="20" cy="12" r="6" stroke="#17a2b8" stroke-width="2"/>
                    <path d="M8 32C8 25.37 13.37 20 20 20C26.63 20 32 25.37 32 32" stroke="#17a2b8" stroke-width="2" stroke-linecap="round"/>
                  </svg>
                  Labor
                </label>
                <select class="form-control form-select">
                  <option>Custodians</option>
                  <option>Cleaners</option>
                  <option>Supervisors</option>
                </select>
              </div>
              <!-- Staff -->
              <div class="col-md-2">
                <label class="form-label fw-bold" style="font-size: 0.9rem;">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-right: 6px; display: inline; opacity: 0.5;">
                    <circle cx="20" cy="12" r="6" stroke="#666" stroke-width="2"/>
                    <path d="M8 32C8 25.37 13.37 20 20 20C26.63 20 32 25.37 32 32" stroke="#666" stroke-width="2" stroke-linecap="round"/>
                  </svg>
                  Staff
                </label>
                <div class="input-group">
                  <button class="btn btn-outline-secondary" type="button">−</button>
                  <input type="number" class="form-control text-center" value="0" readonly>
                  <button class="btn btn-outline-secondary" type="button">+</button>
                </div>
              </div>
              <!-- Rate of Pay -->
              <div class="col-md-2">
                <label class="form-label fw-bold" style="font-size: 0.9rem;">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-right: 6px; display: inline;">
                    <circle cx="12" cy="12" r="10" stroke="#999" stroke-width="2"/>
                    <path d="M12 6V18M8 12H16C17.1046 12 18 12.8954 18 14C18 15.1046 17.1046 16 16 16H8" stroke="#999" stroke-width="2" stroke-linecap="round"/>
                  </svg>
                  Rate of Pay
                </label>
                <input type="number" class="form-control" placeholder="0" value="0">
              </div>
              <!-- Hours -->
              <div class="col-md-2">
                <label class="form-label fw-bold" style="font-size: 0.9rem;">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-right: 6px; display: inline;">
                    <circle cx="12" cy="12" r="10" stroke="#999" stroke-width="2"/>
                    <path d="M12 7V12L16 14" stroke="#999" stroke-width="2" stroke-linecap="round"/>
                  </svg>
                  Hours
                </label>
                <input type="number" class="form-control" placeholder="0" value="0">
              </div>
              <!-- Frequency -->
              <div class="col-md-2">
                <label class="form-label fw-bold" style="font-size: 0.9rem;">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-right: 6px; display: inline;">
                    <circle cx="12" cy="12" r="10" stroke="#999" stroke-width="2"/>
                    <path d="M12 2V12L20 20" stroke="#999" stroke-width="2" stroke-linecap="round"/>
                  </svg>
                  Freq
                </label>
                <select class="form-control form-select">
                  <option>Weekly</option>
                  <option>Monthly</option>
                  <option>Annual</option>
                </select>
              </div>
              <!-- Per -->
              <div class="col-md-2">
                <label class="form-label fw-bold" style="font-size: 0.9rem;">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-right: 6px; display: inline;">
                    <rect x="3" y="3" width="18" height="18" stroke="#999" stroke-width="2"/>
                    <path d="M3 9H21M9 9V21M15 9V21" stroke="#999" stroke-width="2"/>
                  </svg>
                  Per
                </label>
                <select class="form-control form-select">
                  <option>Clean</option>
                  <option>Hour</option>
                  <option>Day</option>
                </select>
              </div>
            </div>
            <!-- Cost Summary Section (Light Blue Background) -->
            <div class="row bg-info bg-opacity-10 p-4 rounded mb-4">
              <div class="col-md-4">
                <label class="text-uppercase fw-bold text-teal" style="font-size: 0.85rem;">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-right: 6px; display: inline;">
                    <circle cx="12" cy="12" r="10" stroke="#17a2b8" stroke-width="2"/>
                    <path d="M12 6V18M8 12H16C17.1046 12 18 12.8954 18 14C18 15.1046 17.1046 16 16 16H8" stroke="#17a2b8" stroke-width="2" stroke-linecap="round"/>
                  </svg>
                  Your Labor Cost Per Clean
                </label>
                <div class="bg-white p-3 rounded mt-2 fw-bold" style="font-size: 1.1rem;">0.00</div>
              </div>
              <div class="col-md-4">
                <label class="text-uppercase fw-bold text-teal" style="font-size: 0.85rem;">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-right: 6px; display: inline;">
                    <circle cx="12" cy="12" r="10" stroke="#17a2b8" stroke-width="2"/>
                    <path d="M12 6V18M8 12H16C17.1046 12 18 12.8954 18 14C18 15.1046 17.1046 16 16 16H8" stroke="#17a2b8" stroke-width="2" stroke-linecap="round"/>
                  </svg>
                  Your Annual Labor Cost
                </label>
                <div class="bg-white p-3 rounded mt-2 fw-bold" style="font-size: 1.1rem;">0.00</div>
              </div>
              <div class="col-md-4">
                <label class="text-uppercase fw-bold text-teal" style="font-size: 0.85rem;">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-right: 6px; display: inline;">
                    <circle cx="12" cy="12" r="10" stroke="#17a2b8" stroke-width="2"/>
                    <path d="M12 6V18M8 12H16C17.1046 12 18 12.8954 18 14C18 15.1046 17.1046 16 16 16H8" stroke="#17a2b8" stroke-width="2" stroke-linecap="round"/>
                  </svg>
                  Your Monthly Labor Cost
                </label>
                <div class="bg-white p-3 rounded mt-2 fw-bold" style="font-size: 1.1rem;">0.00</div>
              </div>
            </div>
            <!-- Hide Cost Details Button -->
            <div class="text-end">
              <button class="btn btn-link text-teal fw-bold text-decoration-none" @click="laborCardExpanded = false">
                HIDE COST DETAILS
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-left: 6px; display: inline;">
                  <path d="M17 14L12 9L7 14" stroke="#17a2b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
              </button>
            </div>
          </div>
        </div>
        <!-- Pricing Summary Section -->
        <div class="mt-5 mb-5" v-if="selectedOption === '1'">
          <div class="row g-0" style="background-color: #a8dfe1;">
            <!-- Left Section - Total Price Card with teal background -->
            <div class="col-md-5">
              <div class="p-5 rounded-start" >
                <h4 class="fw-bold mb-2" style="color: #17a2b8;">
                  Total of Price for All<br>Labor Costs
                </h4>
                <p class="text-muted mb-4">All labor costs added above combined into one monthly price</p>
                <!-- Building Sq Ft Section -->
                <div class="mb-4">
                  <div class="d-flex align-items-center gap-2 mb-3">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M3 9V21H21V9M3 9L12 3L21 9M9 13H15V21H9V13Z" stroke="#17a2b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <label class="fw-bold text-dark">Building Sq Ft.</label>
                  </div>
                  <input type="number" class="form-control border-0 border-bottom text-center fw-bold" placeholder="0" v-model="buildingSqFt">
                </div>
                <!-- Per Sq Ft Section -->
                <div class="mb-4">
                  <label class="fw-bold mb-2 d-block" style="color: #17a2b8;">$ Per Sq Ft</label>
                  <input type="number" class="form-control bg-white fw-bold text-center" placeholder="0" v-model="perSqFt">
                </div>
                <!-- Sales Tax Toggle -->
                <div class="d-flex align-items-center gap-3 mb-4">
                  <label class="mb-0 text-dark">Add Sales Tax to your services?</label>
                  <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="salesTax" v-model="addSalesTax">
                  </div>
                </div>
              </div>
            </div>
            <!-- Right Section - Summary Details with white background -->
            <div class="col-md-7">
              <div class="p-5 rounded-end border-start">
                <!-- Cleaning Labor Costs -->
                  <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                      <h6 class="fw-bold mb-1">Cleaning Labor Costs (1)</h6>
                      <p class="text-muted small mb-0">1 labor cost added</p>
                    </div>
                    <span class="fw-bold" style="font-size: 1.25rem;">$0.00</span>
                  </div>
                <!-- Additional Expenses -->
                <div class="mb-4">
                  <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                      <h6 class="fw-bold mb-1">Additional Expenses (0)</h6>
                      <p class="text-muted small mb-0">0 labor additional expense added</p>
                    </div>
                    <span class="fw-bold" style="font-size: 1.25rem;">$0</span>
                  </div>
                  <!-- Expense Input Row with blue left border -->
                  <div class="row g-2 mb-2">
                    <div class="col-auto ps-3" style="border-left: 4px solid #17a2b8;"></div>
                    <div class="col">
                      <input type="text" class="form-control border-0 border-bottom" placeholder="Expense Name" style="border-bottom: 1px solid #17a2b8 !important;">
                    </div>
                    <div class="col-auto">
                      <input type="text" class="form-control border-0 border-bottom" placeholder="0 $" style="border-bottom: 1px solid #17a2b8 !important;">
                    </div>
                    <div class="col-auto">
                      <button class="btn text-white fw-bold px-4" style="background-color: #17a2b8;">ADD EXPENSE</button>
                    </div>
                  </div>
                </div>
                <!-- Sub-Total -->
                <div class="mb-2">
                  <div class="d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0">Sub-Total</h6>
                    <span class="fw-bold" style="font-size: 1.1rem;">$0</span>
                  </div>
                </div>
                <!-- Add Your Profit Margin -->
                <div class="mb-4 pb-4 border-bottom">
                  <h6 class="fw-bold mb-3">Add Your Profit Margin</h6>
                  <p class="text-muted small mb-3">(Margin cannot be equal to or greater than 100%)</p>
                  <div class="row align-items-center g-3">
                    <div class="col-auto">
                      <div class="input-group">
                        <input type="number" class="form-control text-center fw-bold border-0 border-bottom" placeholder="0" v-model="marginPercent" style="max-width: 80px;">
                        <span class="input-group-text bg-white border-0 border-bottom">%</span>
                      </div>
                    </div>
                    <div class="col-auto">
                      <span class="fw-bold text-muted">or</span>
                    </div>
                    <div class="col-auto">
                      <div class="input-group">
                        <span class="input-group-text bg-white border-0 border-bottom">$</span>
                        <input type="number" class="form-control text-center fw-bold border-0 border-bottom" placeholder="0" v-model="marginDollar" style="max-width: 100px;">
                      </div>
                    </div>
                    <div class="col-auto">
                      <span class="fw-bold" style="font-size: 1.1rem;">$0</span>
                    </div>
                  </div>
                </div>
                <!-- Charge to Client -->
                <div class="p-2 d-flex justify-content-between align-items-center" style="background-color: #d4f1f3;">
                  <h6 class="fw-bold text-uppercase mb-0" style="color: #17a2b8;">Charge to Client (Monthly)</h6>
                  <div class="d-flex align-items-center gap-2">
                    <span class="fw-bold" style="font-size: 1.1rem;">=</span>
                    <span class="fw-bold" style="font-size: 1.3rem; color: #17a2b8;">$</span>
                    <input type="number" class="form-control text-center fw-bold border-0 border-bottom" value="0.00" readonly style="max-width: 120px; color: #17a2b8;">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="mt-5" v-if="selectedOption === '1'">
          <!-- Labor Cost Card Collapsed View -->
          <div v-if="!laborCardExpanded2" class="labor-cost-card bg-white p-4 rounded border-0 shadow-sm">
            <div class="row justify-content-around align-items-center g-0">
              <!-- Labor Cost Label and Title -->
              <div class="col-md-2 pe-4 border-end">
                <label class="text-uppercase small text-muted fw-bold" style="font-size: 0.75rem; letter-spacing: 0.05em;">RECURRING PROJECT</label>
                <h5 class="fw-bold mt-2 mb-0">Test</h5>
              </div>
              <!-- Cost Per Clean -->
              <div class="col-md-2 px-4 border-end">
                <label class="text-uppercase small text-muted fw-bold" style="font-size: 0.75rem; letter-spacing: 0.05em;">Cost Per Clean</label>
                <p class="fw-bold mb-0" style="font-size: 1.25rem;">$0.00</p>
              </div>
              <!-- Annual Cost -->
              <div class="col-md-2 px-4 border-end">
                <label class="text-uppercase small text-muted fw-bold" style="font-size: 0.75rem; letter-spacing: 0.05em;">Annual Cost</label>
                <p class="fw-bold mb-0" style="font-size: 1.25rem;">$0.00</p>
              </div>
              <!-- Monthly -->
              <div class="col-md-2 px-4 border-end">
                <label class="text-uppercase small text-muted fw-bold" style="font-size: 0.75rem; letter-spacing: 0.05em;">Monthly</label>
                <p class="fw-bold mb-0" style="font-size: 1.25rem;">$0.00</p>
              </div>
              <!-- Show Cost Details and Actions -->
              <div class="col-md-2 d-flex justify-content-around align-items-center">
                <a href="#" class="text-teal text-decoration-none fw-bold small" @click.prevent="laborCardExpanded2 = true">SHOW COST DETAILS</a>
                <button class="btn btn-sm btn-teal text-white rounded-circle" style="width: 20px; height: 20px; padding: 0; display: flex; align-items: center; justify-content: center;" @click="laborCardExpanded2 = true">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M7 10L12 15L17 10" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                  </svg>
                </button>
                <button class="btn btn-link p-0" style="background: none; border: none;">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="12" cy="5" r="2" fill="#6c757d"/>
                    <circle cx="12" cy="12" r="2" fill="#6c757d"/>
                    <circle cx="12" cy="19" r="2" fill="#6c757d"/>
                  </svg>
                </button>
              </div>
            </div>
          </div>
          <!-- Labor Cost Card Expanded View -->
          <div v-if="laborCardExpanded2" class="labor-cost-card bg-white p-4 rounded border-0 shadow-sm">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
              <div>
                <label class="text-uppercase small text-muted fw-bold" style="font-size: 0.75rem; letter-spacing: 0.05em;">Labor Cost</label>
                <h5 class="fw-bold mt-2 mb-0">Custodians</h5>
              </div>
              <button class="btn btn-link p-0" style="background: none; border: none;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <circle cx="12" cy="5" r="2" fill="#6c757d"/>
                  <circle cx="12" cy="12" r="2" fill="#6c757d"/>
                  <circle cx="12" cy="19" r="2" fill="#6c757d"/>
                </svg>
              </button>
            </div>
            <div class="mt-3 mb-5">
                <!-- Frequency and Areas Included Section -->
                <div class="row g-0 mb-2 p-3 bg-light align-items-center">
                    <!-- Frequency Section -->
                    <div class="col-md-3">
                    <div class="d-flex align-items-center gap-3">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="12" cy="12" r="10" stroke="#6c757d" stroke-width="2"/>
                        <path d="M12 7V12L16 14" stroke="#6c757d" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                        <div>
                        <label class="text-uppercase small fw-bold text-muted mb-1 d-block" style="font-size: 0.75rem; letter-spacing: 0.05em;">Frequency</label>
                        <p class="mb-0 border-bottom pb-2 fw-bold">1 time per week</p>
                        </div>
                    </div>
                    </div>
                    <!-- Areas Included Section -->
                    <div class="col-md-6 text-center">
                    <div class="d-flex flex-column align-items-center justify-content-center">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="mb-3">
                        <rect x="3" y="5" width="18" height="14" stroke="#17a2b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M3 9H21M12 5V9" stroke="#17a2b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <rect x="7" y="10" width="3" height="3" fill="#17a2b8"/>
                        <rect x="14" y="10" width="3" height="3" fill="#17a2b8"/>
                        </svg>
                        <p class="text-muted fw-bold mb-3">Areas included in this project</p>
                        <span class="badge bg-light text-dark fw-bold" style="padding: 8px 16px; font-size: 0.85rem;">WHOLE_BUILDING</span>
                    </div>
                    </div>
                </div>
                </div>
            <!-- Expanded Content with Input Fields -->
            <div class="row mb-4 pb-4 border-bottom">
              <!-- Labor Type -->
              <div class="col-md-2">
                <label class="form-label fw-bold text-teal" style="font-size: 0.9rem;">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-right: 6px; display: inline;">
                    <circle cx="20" cy="12" r="6" stroke="#17a2b8" stroke-width="2"/>
                    <path d="M8 32C8 25.37 13.37 20 20 20C26.63 20 32 25.37 32 32" stroke="#17a2b8" stroke-width="2" stroke-linecap="round"/>
                  </svg>
                  Labor
                </label>
                <select class="form-control form-select">
                  <option>Custodians</option>
                  <option>Cleaners</option>
                  <option>Supervisors</option>
                </select>
              </div>
              <!-- Staff -->
              <div class="col-md-2">
                <label class="form-label fw-bold" style="font-size: 0.9rem;">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-right: 6px; display: inline; opacity: 0.5;">
                    <circle cx="20" cy="12" r="6" stroke="#666" stroke-width="2"/>
                    <path d="M8 32C8 25.37 13.37 20 20 20C26.63 20 32 25.37 32 32" stroke="#666" stroke-width="2" stroke-linecap="round"/>
                  </svg>
                  Staff
                </label>
                <div class="input-group">
                  <button class="btn btn-outline-secondary" type="button">−</button>
                  <input type="number" class="form-control text-center" value="0" readonly>
                  <button class="btn btn-outline-secondary" type="button">+</button>
                </div>
              </div>
              <!-- Rate of Pay -->
              <div class="col-md-2">
                <label class="form-label fw-bold" style="font-size: 0.9rem;">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-right: 6px; display: inline;">
                    <circle cx="12" cy="12" r="10" stroke="#999" stroke-width="2"/>
                    <path d="M12 6V18M8 12H16C17.1046 12 18 12.8954 18 14C18 15.1046 17.1046 16 16 16H8" stroke="#999" stroke-width="2" stroke-linecap="round"/>
                  </svg>
                  Rate of Pay
                </label>
                <input type="number" class="form-control" placeholder="0" value="0">
              </div>
              <!-- Hours -->
              <div class="col-md-2">
                <label class="form-label fw-bold" style="font-size: 0.9rem;">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-right: 6px; display: inline;">
                    <circle cx="12" cy="12" r="10" stroke="#999" stroke-width="2"/>
                    <path d="M12 7V12L16 14" stroke="#999" stroke-width="2" stroke-linecap="round"/>
                  </svg>
                  Hours
                </label>
                <input type="number" class="form-control" placeholder="0" value="0">
              </div>
            </div>
            <div class="mb-4">
            <button @click="openProjectCostModal" class="cost-button">Add New Project Cost</button>
            </div>
            <!-- Cost Summary Section (Light Blue Background) -->
            <div class="row bg-info justify-content-end bg-opacity-10 p-4 rounded mb-4">
              <div class="col-md-4">
                <label class="text-uppercase fw-bold text-teal" style="font-size: 0.85rem;">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-right: 6px; display: inline;">
                    <circle cx="12" cy="12" r="10" stroke="#17a2b8" stroke-width="2"/>
                    <path d="M12 6V18M8 12H16C17.1046 12 18 12.8954 18 14C18 15.1046 17.1046 16 16 16H8" stroke="#17a2b8" stroke-width="2" stroke-linecap="round"/>
                  </svg>
                  Your Labor Cost Per Clean
                </label>
                <div class="bg-white p-3 rounded mt-2 fw-bold" style="font-size: 1.1rem;">0.00</div>
              </div>
            </div>
            <div class="mt-5 mb-5">
          <div class="row g-0 bg-info bg-opacity-10" >
            <!-- Left Section - Total Price Card with teal background -->
            <div class="col-md-5">
              <div class="p-5 rounded-start" >
                <h4 class="fw-bold mb-2" style="color: #17a2b8;">
                  Total of Price for All<br>Labor Costs
                </h4>
                <p class="text-muted mb-4">All labor costs added above combined into one monthly price</p>
                <!-- Building Sq Ft Section -->
                <div class="mb-4">
                  <div class="d-flex align-items-center gap-2 mb-3">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M3 9V21H21V9M3 9L12 3L21 9M9 13H15V21H9V13Z" stroke="#17a2b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <label class="fw-bold text-dark">Building Sq Ft.</label>
                  </div>
                  <input type="number" class="form-control border-0 border-bottom text-center fw-bold" placeholder="0" v-model="buildingSqFt">
                </div>
                <!-- Per Sq Ft Section -->
                <div class="mb-4">
                  <label class="fw-bold mb-2 d-block" style="color: #17a2b8;">$ Per Sq Ft</label>
                  <input type="number" class="form-control bg-white fw-bold text-center" placeholder="0" v-model="perSqFt">
                </div>
                <!-- Sales Tax Toggle -->
                <div class="d-flex align-items-center gap-3 mb-4">
                  <label class="mb-0 text-dark">Add Sales Tax to your services?</label>
                  <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="salesTax" v-model="addSalesTax">
                  </div>
                </div>
              </div>
            </div>
            <!-- Right Section - Summary Details with white background -->
            <div class="col-md-7">
              <div class="p-5 rounded-end border-start">
                <!-- Cleaning Labor Costs -->
                  <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                      <h6 class="fw-bold mb-1">Cleaning Labor Costs (1)</h6>
                      <p class="text-muted small mb-0">1 labor cost added</p>
                    </div>
                    <span class="fw-bold" style="font-size: 1.25rem;">$0.00</span>
                  </div>
                <!-- Additional Expenses -->
                <div class="mb-4">
                  <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                      <h6 class="fw-bold mb-1">Additional Expenses (0)</h6>
                      <p class="text-muted small mb-0">0 labor additional expense added</p>
                    </div>
                    <span class="fw-bold" style="font-size: 1.25rem;">$0</span>
                  </div>
                  <!-- Expense Input Row with blue left border -->
                  <div class="row g-2 mb-2">
                    <div class="col-auto ps-3" style="border-left: 4px solid #17a2b8;"></div>
                    <div class="col">
                      <input type="text" class="form-control border-0 border-bottom" placeholder="Expense Name" style="border-bottom: 1px solid #17a2b8 !important;">
                    </div>
                    <div class="col-auto">
                      <input type="text" class="form-control border-0 border-bottom" placeholder="0 $" style="border-bottom: 1px solid #17a2b8 !important;">
                    </div>
                    <div class="col-auto">
                      <button class="btn text-white fw-bold px-4" style="background-color: #17a2b8;">ADD EXPENSE</button>
                    </div>
                  </div>
                </div>
                <!-- Sub-Total -->
                <div class="mb-2">
                  <div class="d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0">Sub-Total</h6>
                    <span class="fw-bold" style="font-size: 1.1rem;">$0</span>
                  </div>
                </div>
                <!-- Add Your Profit Margin -->
                <div class="mb-4 pb-4 border-bottom">
                  <h6 class="fw-bold mb-3">Add Your Profit Margin</h6>
                  <p class="text-muted small mb-3">(Margin cannot be equal to or greater than 100%)</p>
                  <div class="row align-items-center g-3">
                    <div class="col-auto">
                      <div class="input-group">
                        <input type="number" class="form-control text-center fw-bold border-0 border-bottom" placeholder="0" v-model="marginPercent" style="max-width: 80px;">
                        <span class="input-group-text bg-white border-0 border-bottom">%</span>
                      </div>
                    </div>
                    <div class="col-auto">
                      <span class="fw-bold text-muted">or</span>
                    </div>
                    <div class="col-auto">
                      <div class="input-group">
                        <span class="input-group-text bg-white border-0 border-bottom">$</span>
                        <input type="number" class="form-control text-center fw-bold border-0 border-bottom" placeholder="0" v-model="marginDollar" style="max-width: 100px;">
                      </div>
                    </div>
                    <div class="col-auto">
                      <span class="fw-bold" style="font-size: 1.1rem;">$0</span>
                    </div>
                  </div>
                </div>
                <!-- Charge to Client -->
                <div class="p-2 d-flex justify-content-between align-items-center" style="background-color: #d4f1f3;">
                  <h6 class="fw-bold text-uppercase mb-0" style="color: #17a2b8;">Charge to Client (Monthly)</h6>
                  <div class="d-flex align-items-center gap-2">
                    <span class="fw-bold" style="font-size: 1.1rem;">=</span>
                    <span class="fw-bold" style="font-size: 1.3rem; color: #17a2b8;">$</span>
                    <input type="number" class="form-control text-center fw-bold border-0 border-bottom" value="0.00" readonly style="max-width: 120px; color: #17a2b8;">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
            <!-- Hide Cost Details Button -->
            <div class="text-end">
              <button class="btn btn-link text-teal fw-bold text-decoration-none" @click="laborCardExpanded2 = false">
                HIDE COST DETAILS
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-left: 6px; display: inline;">
                  <path d="M17 14L12 9L7 14" stroke="#17a2b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
              </button>
            </div>
          </div>
        </div>
        <!-- Pricing Summary Section -->
        <div class="mt-5 mb-5" v-if="selectedOption === '1'">
          <div class="row g-0" style="background-color: #a8dfe1;">
            <!-- Left Section - Total Price Card with teal background -->
            <div class="col-md-5">
              <div class="p-5 rounded-start" >
                <h4 class="fw-bold mb-2" style="color: #17a2b8;">
                  Total Price for All <br> Recurring Projects
                </h4>
                <p class="text-muted mb-4">All recurring projects added above combined into one monthly price</p>
              </div>
            </div>
            <!-- Right Section - Summary Details with white background -->
            <div class="col-md-7">
              <div class="p-5 rounded-end border-start">
                <!-- Cleaning Labor Costs -->
                  <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                      <h6 class="fw-bold mb-1">All Recurring Projects (1)</h6>
                      <p class="text-muted small mb-0">1 recurring projects added</p>
                    </div>
                    <span class="fw-bold" style="font-size: 1.25rem;">$0.00</span>
                  </div>
                <!-- Charge to Client -->
                <div class="p-2 d-flex justify-content-between align-items-center" style="background-color: #d4f1f3;">
                  <h6 class="fw-bold text-uppercase mb-0" style="color: #17a2b8;">Charge to Client (Monthly)</h6>
                  <div class="d-flex align-items-center gap-2">
                    <span class="fw-bold" style="font-size: 1.1rem;">=</span>
                    <span class="fw-bold" style="font-size: 1.3rem; color: #17a2b8;">$</span>
                    <input type="number" class="form-control text-center fw-bold border-0 border-bottom" value="0.00" readonly style="max-width: 120px; color: #17a2b8;">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>



    <div class="border-card mt-5" v-if="selectedOption === '2'">
      <!-- Tabs Header -->
      <div class="tab-header">
        <div class="tab-item" :class="{ active: activeTab === 'account' }" @click="activeTab = 'account'">
          <span class="tab-badge">1</span>
          Account Info
        </div>
        <div class="tab-item" :class="{ active: activeTab === 'cost' }" @click="activeTab = 'cost'">
          <span class="tab-badge">2</span>
          Cost Info
        </div>
      </div>

      <!-- Form Content -->
      <div class="container">
        <!-- ACCOUNT INFO TAB -->
        <div v-if="activeTab === 'account'">
          <!-- Building Types Dropdown -->
          <div class="form-group-custom">
            <label class="form-label fw-bold text-teal" style="font-size: 0.9rem; margin-bottom: 0.5rem; display: block;">Building Types</label>
            <select class="form-control-custom form-select w-100" v-model="buildingType">
              <option value="">Select a building type</option>
              <option value="car-dealership">Car Dealership</option>
              <option value="office">Office</option>
              <option value="retail">Retail</option>
              <option value="warehouse">Warehouse</option>
            </select>
          </div>

          <!-- How Many Sq Ft Input -->
          <div class="form-group-custom">
            <label class="form-label fw-bold" style="font-size: 0.9rem; margin-bottom: 0.5rem; display: block;">How Many Sq Ft</label>
            <div class="position-relative">
              <input type="number" class="form-control-custom w-100" placeholder="0" v-model="sqFtAmount">
              <div class="text-end text-muted small mt-1">{{ sqFtAmount || 0 }} / 9</div>
            </div>
          </div>

          <!-- Frequency Dropdown -->
          <div class="form-group-custom">
            <label class="form-label fw-bold text-teal" style="font-size: 0.9rem; margin-bottom: 0.5rem; display: block;">Frequency</label>
            <select class="form-control-custom form-select w-100" v-model="frequency">
              <option value="">Select frequency</option>
              <option value="1/week">1/week</option>
              <option value="2/week">2/week</option>
              <option value="monthly">Monthly</option>
            </select>
          </div>

          <!-- Difficulty Slider -->
          <div class="slider-container">
            <label class="slider-label">Difficulty</label>
            <input type="range" class="range-slider" min="0" max="100" v-model="difficulty" style="background: linear-gradient(to right, #17a2b8 0%, #17a2b8 50%, #e9ecef 50%, #e9ecef 100%);">
            <div class="slider-labels">
              <span>Easy</span>
              <span>Medium</span>
              <span>Difficult</span>
            </div>
          </div>

          <!-- Floor Slider (Carpet vs Hard Floor) -->
          <div class="slider-container">
            <label class="slider-label">Floor</label>
            <input type="range" class="range-slider" min="0" max="100" v-model="floorType" @input="updateFloorGradient">
            <div class="slider-labels">
              <div class="text-start">
                <div style="font-size: 0.75rem; font-weight: 600;">{{ 100 - floorType }}%</div>
                <div style="color: #999;">Carpet</div>
              </div>
              <div class="text-center">
                <div style="font-size: 0.9rem; font-weight: 600;">{{ floorType }}/{{ 100 - floorType }}</div>
              </div>
              <div class="text-end">
                <div style="font-size: 0.75rem; font-weight: 600;">{{ floorType }}%</div>
                <div style="color: #999;">Hard Floor</div>
              </div>
            </div>
          </div>

          <!-- Next Button -->
          <div class="mt-4">
            <button class="btn-next" @click="handleNext">Next</button>
          </div>
        </div>

        <!-- COST INFO TAB -->
        <div v-if="activeTab === 'cost'">
          <!-- Will you use Employees? -->
          <div class="form-group-custom">
            <label class="form-label fw-bold text-teal" style="font-size: 0.9rem; margin-bottom: 0.5rem; display: block;">Will you use Employees?</label>
            <select class="form-control-custom form-select w-100" v-model="useEmployees">
              <option value="">Select option</option>
              <option value="yes">Yes</option>
              <option value="no">No</option>
            </select>
          </div>

          <!-- Rate of Pay -->
          <div class="form-group-custom">
            <label class="form-label fw-bold" style="font-size: 0.9rem; margin-bottom: 0.5rem; display: block;">Rate of Pay</label>
            <div class="position-relative">
              <input type="number" class="form-control-custom w-100" placeholder="0" v-model="rateOfPay">
              <div class="text-end text-muted small mt-1">{{ rateOfPayCount || 0 }} / 3</div>
            </div>
          </div>

          <!-- Payroll Tax -->
          <div class="form-group-custom">
            <label class="form-label fw-bold text-teal" style="font-size: 0.9rem; margin-bottom: 0.5rem; display: block;">Payroll tax</label>
            <div class="position-relative">
              <input type="number" class="form-control-custom w-100" placeholder="30%" v-model="payrollTaxCost">
              <div class="text-end text-muted small mt-1">{{ payrollTaxCount || 0 }} / 3</div>
            </div>
          </div>

          <!-- Supplies -->
          <div class="form-group-custom">
            <label class="form-label fw-bold text-teal" style="font-size: 0.9rem; margin-bottom: 0.5rem; display: block;">Supplies</label>
            <div class="position-relative">
              <input type="number" class="form-control-custom w-100" placeholder="5%" v-model="supplies">
              <div class="text-end text-muted small mt-1">{{ suppliesCount || 0 }} / 3</div>
            </div>
          </div>

          <!-- Overhead -->
          <div class="form-group-custom">
            <label class="form-label fw-bold text-teal" style="font-size: 0.9rem; margin-bottom: 0.5rem; display: block;">Overhead</label>
            <div class="position-relative">
              <input type="number" class="form-control-custom w-100" placeholder="5%" v-model="overheadCost">
              <div class="text-end text-muted small mt-1">{{ overheadCount || 0 }} / 3</div>
            </div>
          </div>

          <!-- Profit Margin Slider with Color Gradient -->
          <div class="slider-container">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <label class="slider-label mb-0">Profit Margin</label>
              <div class="badge fw-bold" :style="{ backgroundColor: getProfitMarginColor() }">{{ profitMarginValue }}%</div>
            </div>
            <input
              type="range"
              class="range-slider-profit"
              min="0"
              max="100"
              v-model="profitMarginValue"
              @input="updateProfitMarginSlider"
              style="width: 100%;"
            >
            <div class="slider-labels">
              <span>Low</span>
              <span>Safe</span>
              <span>High</span>
            </div>
          </div>

          <!-- Monthly Profit, Cleaning Time, Price Per Service, Monthly Price -->
          <div class="row g-3 mb-4">
            <div class="col-md-6">
              <label class="form-label fw-bold small">Monthly Profit:</label>
              <input type="text" class="form-control text-center fw-bold" value="$0.00" readonly>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold small">Cleaning Time:</label>
              <input type="text" class="form-control text-center fw-bold" value="---" readonly>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold small">Price Per Service:</label>
              <input type="text" class="form-control text-center fw-bold" value="$0.00" readonly>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold small">Monthly Price:</label>
              <input type="text" class="form-control text-center fw-bold" value="$0.00" readonly>
            </div>
          </div>

          <!-- Next Button for Cost Info -->
          <div class="mt-4">
            <button class="btn-next" @click="handleCostInfoNext">Next</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Component -->
    <add-labor-cost-modal
      :isOpen="showLaborCostModal"
      @close="closeLaborCostModal"
      @save="saveLaborCost"
    />

    <add-project-cost-modal
    :isOpen="showProjectCostModal"
      @close="closeProjectCostModal"
      @save="saveProjectCost"
    />

  </div>
</template>

<script>
import CreateLaborCostModal from './Modals/LaboutCostModal.vue';
import CreateProjectCostModal from './Modals/ProjectCostModel.vue';

export default {
  name: 'PriceCalculator',
  components: {
    CreateLaborCostModal,
    CreateProjectCostModal
  },
  data() {
    return {
      selectedOption: '1',
      payrollExpanded: false,
      laborCardExpanded: false,
      laborCardExpanded2:false,
      payrollTaxes: 0,
      insurance: 0,
      overhead: 0,
      buildingSqFt: 0,
      perSqFt: 0,
      addSalesTax: false,
      marginPercent: 0,
      marginDollar: 0,
      buildingType: '',
      sqFtAmount: 0,
      frequency: '',
      difficulty: 50,
      floorType: 50,
      activeTab: 'account',
      useEmployees: '',
      rateOfPay: 0,
      payrollTaxCost: 30,
      supplies: 5,
      overheadCost: 5,
      profitMarginValue: 41,
      rateOfPayCount: 0,
      payrollTaxCount: 2,
      suppliesCount: 1,
      overheadCount: 1,
      showLaborCostModal: false,
      showProjectCostModal: false
    };
  },
  computed: {
    totalPayroll() {
      return parseFloat(this.payrollTaxes || 0) + parseFloat(this.insurance || 0) + parseFloat(this.overhead || 0);
    }
  },
  methods: {
    handleNext() {
      console.log('Form Data:', {
        buildingType: this.buildingType,
        sqFt: this.sqFtAmount,
        frequency: this.frequency,
        difficulty: this.difficulty,
        floorType: this.floorType
      });
      this.activeTab = 'cost';
    },
    handleCostInfoNext() {
      console.log('Cost Info Data:', {
        useEmployees: this.useEmployees,
        rateOfPay: this.rateOfPay,
        payrollTax: this.payrollTaxCost,
        supplies: this.supplies,
        overhead: this.overheadCost,
        profitMargin: this.profitMarginValue
      });
    },
    getProfitMarginColor() {
      if (this.profitMarginValue <= 30) return '#dc3545';
      if (this.profitMarginValue <= 50) return '#ffc107';
      return '#28a745';
    },
    updateProfitMarginSlider() {
      const slider = document.querySelector('.range-slider-profit');
      if (slider) {
        slider.style.background = `linear-gradient(to right, #dc3545 0%, #ffc107 30%, #28a745 50%, #ffc107 70%, #dc3545 100%)`;
      }
    },
    updateFloorGradient() {
      const slider = document.querySelector('.range-slider:nth-of-type(2)');
      if (slider) {
        slider.style.background = `linear-gradient(to right, #17a2b8 0%, #17a2b8 ${this.floorType}%, #5a9bb0 ${this.floorType}%, #5a9bb0 100%)`;
      }
    },
    openLaborCostModal() {
      this.showLaborCostModal = true;
    },
    closeLaborCostModal() {
      this.showLaborCostModal = false;
    },
    saveLaborCost(formData) {
      console.log('New Labor Cost Added:', formData);
      this.closeLaborCostModal();
    },
    openProjectCostModal() {
      this.showProjectCostModal = true;
    },
    closeProjectCostModal() {
      this.showProjectCostModal = false;
    },
    saveProjectCost(formData) {
      console.log('New Labor Cost Added:', formData);
      this.closeProjectCostModal();
    },
  }
};
</script>

<style scoped>
.price-calculator-container {
  min-height: 100vh;
  background-color: #f8f9fa;
}

/* Bootstrap card styling for option cards */
.option-card {
  font-size: 10px;
  border-radius: 1rem;
  transition: all 0.3s ease;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  background: linear-gradient(135deg, #17a2b8 0%, #138496 100%) !important;
}

.option-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 16px rgba(23, 162, 184, 0.3);
}

.option-card .card-body {
  padding: 1rem;
}

.icon-wrapper {
  flex-shrink: 0;
}

/* Ensure proper spacing on mobile */
@media (max-width: 768px) {
  .container {
    padding: 1rem;
  }

  .option-card .card-body {
    padding: 1.5rem;
  }

  h1 {
    font-size: 1.75rem !important;
  }
}

/* Additional styles for the labor section */
.labor-section {
  background-color: #f8f9fa;
  padding-top: 1.5rem;
  padding-bottom: 1.5rem;
  margin-top: 2rem;
}

.labor-section .form-check-label {
  cursor: pointer;
}

.btn-teal {
  background-color: #17a2b8;
  border: none;
  border-radius: 6px;
  transition: all 0.3s ease;
}

.btn-teal:hover {
  background-color: #138496;
  color: white !important;
}

.form-check-input:checked {
  background-color: #17a2b8;
  border-color: #17a2b8;
}

.text-teal {
  color: #17a2b8;
}

.payroll-section {
  margin-bottom: 2rem;
}

.payroll-content {
  border: 1px solid #dee2e6;
  animation: slideDown 0.3s ease-out;
  box-shadow: 0 1px 3px rgba(0, 0, 0, .25882352941176473) !important;
  background: #fff;
}

@keyframes slideDown {
  from {
    opacity: 0;
    transform: translateY(-10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.bg-secondary-light {
  background-color: #eee;
}

.input-group input {
  border-right: none;
}

.input-group .input-group-text {
  background-color: white;
  border-left: none;
}

.labor-cost-card {
  background-color: #ffffff;
  padding: 1.25rem;
  border-radius: 8px;
  box-shadow: 0 5px 10px rgba(0, 0, 0, .2) !important;
}

.cost-button {
    background: #f5f5f5;
    color: #00b2ab;
    padding: 8px 22px !important;
    border-radius: 50px;
    text-transform: uppercase;
    font-weight: 600;
    font-size: 12px;
    border: none;
    box-shadow: 0 5px 5px rgba(0, 0, 0, .2) !important;
}

.tab-header {
  display: flex;
  justify-content: center;
  gap: 3rem;
  border-bottom: 2px solid #dee2e6;
  padding-bottom: 1rem;
  margin-bottom: 2rem;
}

.tab-item {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  cursor: pointer;
  font-weight: 600;
  text-decoration: none;
  color: #6c757d;
  position: relative;
}

.tab-item.active {
  color: #333;
}

.tab-item.active::after {
  content: '';
  position: absolute;
  bottom: -1rem;
  left: 0;
  right: 0;
  height: 3px;
  background-color: #333;
}

.tab-badge {
  width: 32px;
  height: 32px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
  font-size: 0.9rem;
  background-color: #6c757d;
  color: white;
}

.tab-item.active .tab-badge {
  background-color: #17a2b8;
}

.form-group-custom {
  margin-bottom: 1.5rem;
}

.form-control-custom {
  border: 2px solid #dee2e6;
  border-radius: 24px;
  padding: 0.75rem 1.25rem;
  font-size: 1rem;
  transition: all 0.3s ease;
}

.form-control-custom:focus {
  border-color: #17a2b8;
  box-shadow: none;
  outline: none;
}

.form-control-custom::placeholder {
  color: #999;
}

.slider-container {
  margin-bottom: 2rem;
}

.slider-label {
  font-weight: 600;
  margin-bottom: 0.75rem;
  display: block;
}

.range-slider {
  width: 100%;
  height: 6px;
  border-radius: 3px;
  background: linear-gradient(to right, #17a2b8 0%, #17a2b8 50%, #e9ecef 50%, #e9ecef 100%);
  outline: none;
  -webkit-appearance: none;
  appearance: none;
  cursor: pointer;
}

.range-slider::-webkit-slider-thumb {
  -webkit-appearance: none;
  appearance: none;
  width: 24px;
  height: 24px;
  border-radius: 50%;
  background: white;
  border: 3px solid #17a2b8;
  cursor: pointer;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.range-slider::-moz-range-thumb {
  width: 24px;
  height: 24px;
  border-radius: 50%;
  background: white;
  border: 3px solid #17a2b8;
  cursor: pointer;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.slider-labels {
  display: flex;
  justify-content: space-between;
  margin-top: 0.5rem;
  font-size: 0.85rem;
  color: #666;
}

.btn-next {
  width: 100%;
  padding: 0.75rem 2rem;
  background-color: #17a2b8;
  color: white;
  border: none;
  border-radius: 6px;
  font-weight: bold;
  font-size: 1rem;
  cursor: pointer;
  transition: all 0.3s ease;
}

.btn-next:hover {
  background-color: #138496;
}

.border-card {
  border: 2px solid #999;
  padding: 2rem;
  background-color: #f9f9f9;
  width: 512px;
  max-width: 96%;
  max-height: 96%;
  border: 1px solid #707070;
  margin: 30px auto 0;
  background-color: #fff;
}

.range-slider-profit {
  width: 100%;
  height: 8px;
  border-radius: 4px;
  background: linear-gradient(to right, #dc3545 0%, #ffc107 30%, #28a745 50%, #ffc107 70%, #dc3545 100%);
  outline: none;
  -webkit-appearance: none;
  appearance: none;
  cursor: pointer;
}

.range-slider-profit::-webkit-slider-thumb {
  -webkit-appearance: none;
  appearance: none;
  width: 24px;
  height: 24px;
  border-radius: 50%;
  background: white;
  border: 3px solid #6c757d;
  cursor: pointer;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.15);
}

.range-slider-profit::-moz-range-thumb {
  width: 24px;
  height: 24px;
  border-radius: 50%;
  background: white;
  border: 3px solid #6c757d;
  cursor: pointer;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.15);
}

</style>
