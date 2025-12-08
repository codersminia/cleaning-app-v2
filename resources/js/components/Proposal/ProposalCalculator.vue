<template>
  <div class="price-calculator-container">
    <!-- Header Section with Light Gray Background -->
    <div class="bg-light pb-4 mb-4">
      <div class="container-fluid">
        <div class="row align-items-center">
          <!-- Go Back Button -->
          <div class="col-md-3">
            <!-- <button class="btn btn-outline-secondary btn-sm">
              <span class="text-info">←</span> Go Back
            </button> -->
          </div>
          <!-- Center Title -->
          <div class="col-md-6 text-center">
            <h4 class="fw-bold" style="font-size: 2.5rem; letter-spacing: 0.05em;">PRICE CALCULATOR</h4>
            <p class="text-muted mb-0">Calculate your costs before finishing</p>
          </div>
          <!-- Next Step Button -->
          <div class="col-md-3 text-end">
            <!-- <button class="btn btn-outline-secondary btn-sm">
              Im done here <span class="text-info">→</span>
            </button> -->
          </div>
        </div>
      </div>
    </div>
    <!-- Options Section -->
    <!-- <div class="container pb-5">
      <div class="row g-4">
        <div class="col-md-2"></div>
        <!-- Option 1 Card 
        <div class="col-md-4">
          <div class="card border-0 option-card bg-info text-white" :class="{ 'shadow-lg': selectedOption == '1' }">
            <div class="card-body">
              <div class="d-flex gap-3">
                <!-- Icon Circle 
                <div class="icon-wrapper flex-shrink-0">
                  <div class="rounded-circle bg-white bg-opacity-25 d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
                    <i class="bi bi-gear text-white" style="font-size: 24px;"></i>
                    <input style="width:25px;height:25px;" name="type" type="radio" v-model="selectedOption" value="1">
                  </div>
                </div>
                <!-- Text Content 
                <div class="flex-grow-1">
                  <p class="fw-bold mb-2 text-white">OPTION 1: DIY ADVANCED COST ESTIMATOR</p>
                  <p class="mb-0" >Customers Can Estimate Costs Themselves By Entering Labor And Material Details With This Option.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- Option 2 Card 
        <div class="col-md-4">
          <div class="card border-0 option-card bg-info text-white" :class="{ 'shadow-lg': selectedOption == '2' }">
            <div class="card-body">
              <div class="d-flex gap-3">
                <!-- Icon Circle 
                <div class="icon-wrapper flex-shrink-0">
                  <div class="rounded-circle bg-white bg-opacity-25 d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
                    <i class="bi bi-gear text-white" style="font-size: 24px;"></i>
                     <input style="width:25px;height:25px;" name="type" type="radio" v-model="selectedOption" value="2">
                  </div>
                </div>
                <!-- Text Content 
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
    </div> -->
    <!-- Payroll Cost Factors Section -->
        <div v-if="payrollExpanded && selectedOption == '1' && showJanitorialSection" class="payroll-section mt-4">
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
        <div class="labor-section bg-light py-4" v-if="selectedOption == '1' && showJanitorialSection">
            <div class="row align-items-center">
              <!-- Expand All Toggle -->
              <div class="col-md-3">
                <div class="d-flex align-items-center gap-2">
                  <!-- <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="expandAll">
                  </div> -->
                  <!-- <label class="form-check-label" for="expandAll">
                    Expand all
                  </label> -->
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
                    <h4 class="mb-0 fw-bold text-dark">{{ janitorialSectionLabel }}</h4>
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
        <div class="mt-5" v-if="selectedOption == '1' && showJanitorialSection">
          
          <!-- Display message if no costs added yet -->
          <div v-if="laborCosts.length == 0" class="text-center text-muted py-4">
            <p>No labor costs added yet. Click "ADD A NEW LABOR COST" to begin.</p>
          </div>

          <!-- Dynamic Loop -->
          <div v-for="(cost, index) in laborCosts" :key="cost.id" class="mb-4">
            
            <!-- Collapsed View Header -->
            <div v-if="!cost.isExpanded" class="labor-cost-card bg-white p-4 rounded border-0 shadow-sm">
              <div class="row justify-content-around align-items-center g-0">
                <div class="col-md-2 pe-4 border-end">
                  <label class="text-uppercase small text-muted fw-bold" style="font-size: 0.75rem;">Labor Cost {{ index + 1 }}</label>
                  <h5 class="fw-bold mt-2 mb-0">{{ cost.labor || 'Unspecified' }}</h5>
                </div>
                <!-- Dynamic Summary Fields (Collapsed) -->
                <div class="col-md-2 px-4 border-end">
                  <label class="text-uppercase small text-muted fw-bold" style="font-size: 0.75rem;">Cost Per Clean</label>
                  <p class="fw-bold mb-0" style="font-size: 1.25rem;">${{ getCostPerClean(cost).toFixed(2) }}</p>
                </div>
                <div class="col-md-2 px-4 border-end">
                  <label class="text-uppercase small text-muted fw-bold" style="font-size: 0.75rem;">Annual Cost</label>
                  <p class="fw-bold mb-0" style="font-size: 1.25rem;">${{ getAnnualCost(cost).toFixed(2) }}</p>
                </div>
                <div class="col-md-2 px-4 border-end">
                  <label class="text-uppercase small text-muted fw-bold" style="font-size: 0.75rem;">Monthly</label>
                  <p class="fw-bold mb-0" style="font-size: 1.25rem;">${{ getMonthlyCost(cost).toFixed(2) }}</p>
                </div>
                
                <div class="col-md-2 d-flex justify-content-around align-items-center">
                  <a href="#" class="text-teal text-decoration-none fw-bold small" @click.prevent="cost.isExpanded = true">SHOW DETAILS</a>
                  <button class="btn btn-sm btn-teal text-white rounded-circle" style="width: 20px; height: 20px;" @click="cost.isExpanded = true">
                    <i class="bi bi-chevron-down"></i> <!-- Or your SVG -->
                  </button>
                  <!-- Delete Button -->
                  <button class="btn btn-link p-0 text-danger" @click="laborCosts.splice(index, 1)">
                      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                  </button>
                </div>
              </div>
            </div>

            <!-- Expanded View -->
            <div v-if="cost.isExpanded" class="labor-cost-card bg-white p-4 rounded border-0 shadow-sm">
              <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                <div>
                  <label class="text-uppercase small text-muted fw-bold" style="font-size: 0.75rem;">Labor Cost {{ index + 1 }}</label>
                  <h5 class="fw-bold mt-2 mb-0">{{ cost.labor || 'Unspecified' }}</h5>
                </div>
                <button class="btn btn-link p-0" @click="cost.isExpanded = false">HIDE</button>
              </div>

              <!-- Inputs Row -->
              <div class="row mb-4 pb-4 border-bottom">
                <div class="col-md-2">
                  <label class="form-label fw-bold text-teal small">Labor</label>
                  <select class="form-control form-select" v-model="cost.labor">
                    <option value="custodians">Custodians</option>
                    <option value="cleaners">Cleaners</option>
                    <option value="supervisors">Supervisors</option>
                  </select>
                </div>
                <div class="col-md-2">
                  <label class="form-label fw-bold small">Staff</label>
                  <input type="number" class="form-control text-center" v-model.number="cost.staff">
                </div>
                <div class="col-md-2">
                  <label class="form-label fw-bold small">Rate ($/hr)</label>
                  <input type="number" class="form-control" v-model.number="cost.rateOfPay">
                </div>
                <div class="col-md-2">
                  <label class="form-label fw-bold small">Hours</label>
                  <input type="number" class="form-control" v-model.number="cost.hours">
                </div>
                
                <!-- Dynamic Frequency 1-30 -->
                <div class="col-md-2">
                  <label class="form-label fw-bold small">Freq</label>
                  <select class="form-control form-select" v-model.number="cost.frequency">
                    <option v-for="n in 30" :key="n" :value="n">{{ n }}</option>
                  </select>
                </div>

                <!-- Dynamic Per (Week/Month/etc) -->
                <div class="col-md-2">
                  <label class="form-label fw-bold small">Per</label>
                  <select class="form-control form-select" v-model="cost.per">
                    <option value="Week">Week</option>
                    <option value="Month">Month</option>
                    <option value="Quarter">Quarter</option>
                    <option value="Year">Year</option>
                  </select>
                </div>
              </div>

              <!-- Expanded Blue Summary Box -->
              <div class="row bg-info bg-opacity-10 p-4 rounded mb-4">
                <div class="col-md-4">
                  <label class="text-uppercase fw-bold text-teal small">Your Labor Cost Per Clean</label>
                  <div class="bg-white p-3 rounded mt-2 fw-bold fs-5">${{ getCostPerClean(cost).toFixed(2) }}</div>
                </div>
                <div class="col-md-4">
                  <label class="text-uppercase fw-bold text-teal small">Your Annual Labor Cost</label>
                  <div class="bg-white p-3 rounded mt-2 fw-bold fs-5">${{ getAnnualCost(cost).toFixed(2) }}</div>
                </div>
                <div class="col-md-4">
                  <label class="text-uppercase fw-bold text-teal small">Your Monthly Labor Cost</label>
                  <div class="bg-white p-3 rounded mt-2 fw-bold fs-5">${{ getMonthlyCost(cost).toFixed(2) }}</div>
                </div>
              </div>

              <div class="text-end">
                <button class="btn btn-link text-teal fw-bold text-decoration-none" @click="cost.isExpanded = false">
                  HIDE COST DETAILS
                </button>
              </div>
            </div>
          </div>

        </div>
        <!-- Pricing Summary Section -->
        <div class="mt-5 mb-5" v-if="selectedOption == '1' && showJanitorialSection">
          <div class="row g-0" style="background-color: #a8dfe1;">
            
            <!-- Left Section - Total Price Card -->
            <div class="col-md-5">
              <div class="p-5 rounded-start">
                <h4 class="fw-bold mb-2" style="color: #17a2b8;">
                  Total of Price for All<br>Labor Costs
                </h4>
                <p class="text-muted mb-4">All labor costs added above combined into one monthly price</p>
                
                <!-- Building Sq Ft Section -->
                <div class="mb-4">
                  <div class="d-flex align-items-center gap-2 mb-3">
                    <!-- Icon -->
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M3 9V21H21V9M3 9L12 3L21 9M9 13H15V21H9V13Z" stroke="#17a2b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <label class="fw-bold text-dark">Building Sq Ft.</label>
                  </div>
                  <input type="number" disabled class="form-control border-0 border-bottom text-center fw-bold" placeholder="0" v-model="buildingSqFt">
                </div>
                
                <!-- Per Sq Ft Section (Read Only Calculation) -->
                <div class="mb-4">
                  <label class="fw-bold mb-2 d-block" style="color: #17a2b8;">$ Per Sq Ft</label>
                  <!-- Display calculated per sq ft -->
                  <div aria-disabled="true" class="bg-white p-2 fw-bold text-center rounded">
                    ${{ calculatedPerSqFt }}
                  </div>
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

            <!-- Right Section - Summary Details -->
            <div class="col-md-7">
              <div class="p-5 rounded-end border-start bg-white h-100">
                
                <!-- Cleaning Labor Costs Summary -->
                <div class="d-flex justify-content-between align-items-start mb-2">
                  <div>
                    <h6 class="fw-bold mb-1">Cleaning Labor Costs ({{ laborCosts.length }})</h6>
                    <p class="text-muted small mb-0">{{ laborCosts.length }} labor cost(s) added</p>
                  </div>
                  <span class="fw-bold" style="font-size: 1.25rem;">${{ totalMonthlyLaborCost.toFixed(2) }}</span>
                </div>

                <!-- Additional Expenses Section -->
                <div class="mb-4 mt-4">
                  <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                      <h6 class="fw-bold mb-1">Additional Expenses ({{ additionalExpenses.length }})</h6>
                      <p class="text-muted small mb-0">{{ additionalExpenses.length }} additional expense(s) added</p>
                    </div>
                    <span class="fw-bold" style="font-size: 1.25rem;">${{ totalAdditionalExpenses.toFixed(2) }}</span>
                  </div>

                  <!-- List of Added Expenses -->
                  <div v-for="(expense, index) in additionalExpenses" :key="expense.id" class="row g-2 mb-2 align-items-center">
                    <div class="col-auto ps-3" style="border-left: 4px solid #17a2b8;"></div>
                    <div class="col">
                      <span class="fw-bold text-muted">{{ expense.name }}</span>
                    </div>
                    <div class="col-auto">
                      <span class="fw-bold">${{ expense.cost.toFixed(2) }}</span>
                    </div>
                    <div class="col-auto">
                      <!-- Remove Button -->
                      <button class="btn btn-sm text-danger" @click="removeExpense(index)">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                      </button>
                    </div>
                  </div>

                  <!-- Add Expense Inputs (Show if < 5) -->
                  <div class="row g-2 mb-2 mt-3" v-if="additionalExpenses.length < 5">
                    <div class="col-auto ps-3" style="border-left: 4px solid #ccc;"></div>
                    <div class="col">
                      <input type="text" class="form-control border-0 border-bottom" placeholder="Expense Name" v-model="newExpenseName">
                    </div>
                    <div class="col-auto">
                      <input type="number" class="form-control border-0 border-bottom" placeholder="0.00" v-model="newExpenseCost" style="max-width: 100px;">
                    </div>
                    <div class="col-auto">
                      <button class="btn text-white fw-bold px-3 btn-sm" style="background-color: #17a2b8;" @click="addExpense">ADD</button>
                    </div>
                  </div>
                  <div v-else class="text-muted small text-center fst-italic">
                    Max 5 expenses reached.
                  </div>
                </div>

                <!-- Sub-Total -->
                <div class="mb-2 py-2 border-top">
                  <div class="d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0">Sub-Total</h6>
                    <span class="fw-bold" style="font-size: 1.1rem;">${{ subTotal.toFixed(2) }}</span>
                  </div>
                </div>

                <!-- Add Your Profit Margin -->
                <div class="mb-4 pb-4 border-bottom">
                  <h6 class="fw-bold mb-3">Add Your Profit Margin</h6>
                  <p class="text-muted small mb-3">(Margin cannot be equal to or greater than 100%)</p>
                  
                  <div class="row align-items-center g-3">
                    <!-- Percentage Input -->
                    <div class="col-auto">
                      <div class="input-group">
                        <input type="number" class="form-control text-center fw-bold border-0 border-bottom" placeholder="0" v-model="marginPercent" @input="updateMarginFromPercent" style="max-width: 80px;">
                        <span class="input-group-text bg-white border-0 border-bottom">%</span>
                      </div>
                    </div>
                    <div class="col-auto">
                      <span class="fw-bold text-muted">or</span>
                    </div>
                    <!-- Dollar Input -->
                    <div class="col-auto">
                      <div class="input-group">
                        <span class="input-group-text bg-white border-0 border-bottom">$</span>
                        <input type="number" class="form-control text-center fw-bold border-0 border-bottom" placeholder="0" v-model="marginDollar" @input="updateMarginFromDollar" style="max-width: 100px;">
                      </div>
                    </div>
                    <!-- Resulting Profit Display -->
                    <div class="col-auto ms-auto">
                      <span class="fw-bold text-success" style="font-size: 1.1rem;">+ ${{ (parseFloat(marginDollar) || 0).toFixed(2) }}</span>
                    </div>
                  </div>
                </div>

                <!-- Sales Tax Input (Visible only if toggled ON) -->
                <div class="mb-4 pb-4 border-bottom" v-if="addSalesTax">
                  <div class="d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0">Sales Tax</h6>
                    <div class="d-flex align-items-center gap-2">
                      <div class="input-group input-group-sm" style="width: 100px;">
                        <input type="number" class="form-control fw-bold" v-model="salesTaxPercent" placeholder="0">
                        <span class="input-group-text">%</span>
                      </div>
                      <span class="fw-bold text-danger">+ ${{ calculatedTaxAmount.toFixed(2) }}</span>
                    </div>
                  </div>
                </div>

                <!-- Charge to Client -->
                <div class="p-3 d-flex justify-content-between align-items-center rounded" style="background-color: #d4f1f3;">
                  <h6 class="fw-bold text-uppercase mb-0" style="color: #17a2b8;">Charge to Client (Monthly)</h6>
                  <div class="d-flex align-items-center gap-2">
                    <span class="fw-bold" style="font-size: 1.1rem;">=</span>
                    <span class="fw-bold" style="font-size: 1.5rem; color: #17a2b8;">${{ finalMonthlyCharge.toFixed(2) }}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- RECURRING PROJECTS SECTION -->
        <div class="mt-5" v-if="selectedOption == '1' && recurringProjects.length > 0 && showRecurringSection">
          
          <!-- Header -->
          <div class="text-center mb-4">
            <div class="d-flex justify-content-center align-items-center gap-2">
              <i class="bi bi-arrow-repeat text-teal fs-3"></i> 
            </div>
            <h4 class="fw-bold">Recurring Projects</h4>
          </div>

          <div v-for="(project, index) in recurringProjects" :key="project.id" class="mb-4">
            
            <!-- COLLAPSED VIEW (Matches your Screenshot) -->
            <div v-if="!project.isExpanded" class="labor-cost-card bg-white shadow-sm border-0 p-4 d-flex align-items-center justify-content-between">
              
              <!-- Left: Title -->
              <div class="d-flex flex-column" style="min-width: 250px;">
                <label class="text-uppercase small text-muted fw-bold" style="font-size: 0.7rem; letter-spacing: 0.5px;">RECURRING PROJECT</label>
                <h5 class="fw-bold mb-0 text-dark">{{ project.name }}</h5>
              </div>

              <!-- Middle: Stats with Slashes -->
              <div class="d-flex align-items-center bg-light rounded py-2 px-4 flex-grow-1 justify-content-center mx-4">
                
                <!-- Per Sq Ft -->
                <div class="text-center px-3">
                  <label class="text-uppercase text-muted fw-bold d-block" style="font-size: 0.65rem;">PER SQ FT</label>
                  <span class="fw-bold text-dark">${{ getProjectPerSqFt(project) }}</span>
                </div>

                <!-- Slash Separator -->
                <div class="text-muted fw-light fs-4 mx-2">/</div>

                <!-- Cost Per Clean -->
                <div class="text-center px-3">
                  <label class="text-uppercase text-muted fw-bold d-block" style="font-size: 0.65rem;">COST PER CLEAN</label>
                  <span class="fw-bold text-dark">${{ getProjectLaborCost(project).toFixed(2) }}</span>
                </div>

                <!-- Slash Separator -->
                <div class="text-muted fw-light fs-4 mx-2">/</div>

                <!-- Monthly Total -->
                <div class="text-center px-3">
                  <label class="text-uppercase text-muted fw-bold d-block" style="font-size: 0.65rem;">MONTHLY</label>
                  <span class="fw-bold text-dark">${{ getProjectGrandTotal(project).toFixed(2) }}</span>
                </div>

              </div>

              <!-- Right: Button -->
              <div>
                <button class="btn btn-white text-teal fw-bold small d-flex align-items-center gap-2 border-0 shadow-none" @click="project.isExpanded = true">
                  SHOW PROJECT DETAILS 
                  <i class="bi bi-arrow-down-circle-fill fs-5 text-teal"></i>
                </button>
              </div>

            </div>

            <!-- EXPANDED VIEW (Kept exactly as previously built) -->
            <div v-if="project.isExpanded" class="labor-cost-card bg-white border-0 shadow-sm rounded overflow-hidden">
              
              <!-- 1. Header & Info Bar -->
              <div class="p-4 border-bottom">
                <label class="text-uppercase small text-muted fw-bold" style="font-size: 0.7rem;">RECURRING PROJECT</label>
                <h4 class="fw-bold mb-3">{{ project.name }}</h4>
                
                <div class="bg-light p-3 rounded d-flex justify-content-between align-items-center">
                  <div>
                    <i class="bi bi-clock text-muted me-2"></i>
                    <span class="small text-muted fw-bold text-uppercase d-block" style="font-size: 0.7rem;">Frequency</span>
                    <span class="fw-bold">{{ project.frequency }} times per {{ project.per }}</span>
                  </div>
                  <div class="text-center">
                    <i class="bi bi-building text-teal mb-1 d-block"></i>
                    <small class="text-muted d-block mb-1">Areas included</small>
                    <span class="badge bg-white text-dark border rounded-pill px-3 py-2">{{ project.areaNames }}</span>
                  </div>
                  <div><!-- Spacer --></div>
                </div>
              </div>

              <!-- 2. Inputs -->
              <div class="p-4 border-bottom">
                <div class="row g-4">
                  <div class="col-md-3">
                    <label class="fw-bold text-teal mb-2"><i class="bi bi-people-fill me-1"></i> Labor</label>
                    <select class="form-select border-bottom border-0 rounded-0 ps-0 bg-transparent" style="border-color: #333 !important;">
                      <option>Custodians</option>
                      <option value="cleaners">Cleaners</option>
                    <option value="supervisors">Supervisors</option>
                    </select>
                  </div>
                  <div class="col-md-3">
                    <label class="fw-bold text-muted mb-2"><i class="bi bi-person me-1"></i> Staff</label>
                    <input type="number" class="form-control border-bottom border-0 rounded-0 ps-0 bg-transparent text-center fw-bold" v-model="project.staff">
                  </div>
                  <div class="col-md-3">
                    <label class="fw-bold text-teal mb-2"><i class="bi bi-currency-dollar me-1"></i> Rate of Pay</label>
                    <input type="number" class="form-control border-bottom border-0 rounded-0 ps-0 bg-transparent text-center fw-bold" v-model="project.rateOfPay">
                  </div>
                  <div class="col-md-3">
                    <label class="fw-bold text-muted mb-2"><i class="bi bi-clock me-1"></i> Hours</label>
                    <input type="number" class="form-control border-bottom border-0 rounded-0 ps-0 bg-transparent text-center fw-bold" v-model="project.hours">
                  </div>
                </div>
              </div>

              <!-- 3. Result Strip (Cost Per Clean AND Monthly) -->
              <div class="bg-light p-4 d-flex justify-content-end align-items-center gap-4">
                <div class="d-flex align-items-center">
                  <span class="fw-bold text-uppercase me-3 small text-muted">Your Labor Cost Per Clean</span>
                  <div class="bg-white px-3 py-2 rounded shadow-sm fw-bold border">${{ getProjectLaborCost(project).toFixed(2) }}</div>
                </div>
                <div class="d-flex align-items-center">
                  <span class="fw-bold text-uppercase me-3 small text-muted">Your Monthly Labor Cost</span>
                  <div class="bg-white px-3 py-2 rounded shadow-sm fw-bold border">${{ getProjectMonthlyLaborCost(project).toFixed(2) }}</div>
                </div>
              </div>

              <!-- 4. SUMMARY SECTION (Specific to This Project) -->
              <div class="p-5" style="background-color: #eafcfd;">
                <h4 class="fw-bold text-teal mb-1">Price for This Recurring Project</h4>
                <p class="text-muted mb-4">For this recurring project only</p>

                <div class="row">
                  <!-- Left Col -->
                  <div class="col-md-5 border-end">
                    <div class="mb-4 text-center">
                      <label class="fw-bold text-dark"><i class="bi bi-rulers text-teal me-2"></i>Project Sq Ft.</label>
                      <input type="number" disabled class="form-control border-0 border-bottom bg-transparent text-center fw-bold fs-5" placeholder="0" v-model="project.sqFt">
                    </div>

                    <div class="d-flex align-items-center justify-content-center gap-3 mb-5">
                      <label class="fw-bold text-teal">$ Per Sq Ft</label>
                      <div aria-disabled="true" class="bg-white px-3 py-2 rounded border fw-bold">{{ getProjectPerSqFt(project) }}</div>
                    </div>

                    <div class="d-flex align-items-center justify-content-center gap-2">
                      <small>Add Sales Tax?</small>
                      <div class="form-check form-switch">
                          <input class="form-check-input" type="checkbox" v-model="project.addSalesTax">
                      </div>
                    </div>
                  </div>

                  <!-- Right Col (Calculations) -->
                  <div class="col-md-7 ps-4">
                    <div class="d-flex justify-content-between mb-2">
                      <span class="fw-bold">All Labor Costs for this Project</span>
                      <span class="fw-bold">${{ getProjectMonthlyLaborCost(project).toFixed(2) }}</span>
                    </div>

                    <!-- Expenses -->
                    <div class="mb-3">
                      <div class="d-flex justify-content-between">
                        <span class="fw-bold">Additional Expenses ({{ project.expenses.length }})</span>
                        <span class="fw-bold">${{ (getProjectSubTotal(project) - getProjectMonthlyLaborCost(project)).toFixed(2) }}</span>
                      </div>
                      <!-- List -->
                      <div v-for="(exp, i) in project.expenses" :key="i" class="d-flex justify-content-between small text-muted mt-1 ps-3 border-start border-3 border-info">
                        <span>{{ exp.name }}</span>
                        <span>${{ exp.cost.toFixed(2) }} 
                          <button class="btn btn-sm text-danger" @click="removeProjectExpense(project, i)">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                      </button>
                          <!-- <i class="bi bi-x text-danger cursor-pointer" @click="removeProjectExpense(project, i)"></i> -->
                        </span>
                      </div>
                      <!-- Inputs -->
                      <div class="row g-2 mt-2">
                        <div class="col-5"><input class="form-control form-control-sm border-0 border-bottom bg-transparent" placeholder="Expense Name" v-model="project.newExpenseName"></div>
                        <div class="col-3"><input class="form-control form-control-sm border-0 border-bottom bg-transparent" placeholder="$ 0" type="number" v-model="project.newExpenseCost"></div>
                        <div class="col-4"><button class="btn btn-sm btn-teal text-white w-100" @click="addProjectExpense(project)">ADD EXPENSE</button></div>
                      </div>
                    </div>

                    <div class="d-flex justify-content-between mb-3 pt-2 border-top border-dark">
                      <span class="fw-bold">This Recurring Project Cost Subtotal</span>
                      <span class="fw-bold fs-5">${{ getProjectSubTotal(project).toFixed(2) }}</span>
                    </div>

                    <!-- Profit -->
                    <div class="d-flex align-items-center justify-content-between mb-3">
                      <span class="fw-bold">Add Your Profit Margin</span>
                      <div class="d-flex gap-2 align-items-center">
                        <div class="input-group input-group-sm" style="width: 80px;"><input class="form-control fw-bold" v-model="project.marginPercent" @input="updateProjectMarginPercent(project)"><span class="input-group-text border-0 bg-transparent">%</span></div>
                        <span class="text-muted small">or</span>
                        <div class="input-group input-group-sm" style="width: 100px;"><span class="input-group-text border-0 bg-transparent">$</span><input class="form-control fw-bold" v-model="project.marginDollar" @input="updateProjectMarginDollar(project)"></div>
                      </div>
                      <span class="fw-bold">${{ (parseFloat(project.marginDollar)||0).toFixed(2) }}</span>
                    </div>

                    <!-- Tax -->
                    <div class="d-flex align-items-center justify-content-between mb-3" v-if="project.addSalesTax">
                      <span class="fw-bold text-muted">Sales Tax</span>
                      <div class="input-group input-group-sm" style="width: 100px;"><input class="form-control fw-bold" v-model="project.salesTaxPercent"><span class="input-group-text border-0 bg-transparent">%</span></div>
                      <span class="fw-bold text-danger">+ ${{ ((getProjectSubTotal(project) + parseFloat(project.marginDollar||0)) * (project.salesTaxPercent/100)).toFixed(2) }}</span>
                    </div>

                    <!-- Monthly Charge for This Project -->
                    <div class="bg-white p-3 rounded d-flex justify-content-between align-items-center mt-3">
                      <span class="fw-bold text-teal">MONTHLY CHARGE TO CLIENT FOR THIS PROJECT</span>
                      <div class="d-flex align-items-center gap-2">
                        <span class="text-teal fs-5">=</span>
                        <span class="text-teal fw-bold fs-5">$</span>
                        <span class="text-teal fw-bold fs-4 border-bottom border-teal px-2">{{ getProjectGrandTotal(project).toFixed(2) }}</span>
                      </div>
                    </div>

                  </div>
                </div>
              </div>
              
              <!-- Footer -->
              <div class="p-3 bg-white text-end">
                  <button class="btn btn-link text-muted text-decoration-none small fw-bold" @click="project.isExpanded = false">
                    HIDE COST DETAILS <i class="bi bi-arrow-up-circle-fill text-teal ms-1"></i>
                  </button>
              </div>
            </div>
          </div>

          <!-- 5. RECURRING PROJECTS GLOBAL SUMMARY (Screenshot 3) -->
          <div class="mt-5 mb-5">
            <div class="row g-0" style="background-color: #a8dfe1;">
              <!-- Left Section -->
              <div class="col-md-5 p-5">
                <h4 class="fw-bold mb-2" style="color: #17a2b8;">Total Price for All <br> Recurring Projects</h4>
                <p class="text-muted mb-4">All recurring projects added above combined into one monthly price</p>
              </div>
              <!-- Right Section -->
              <div class="col-md-7 p-5 rounded-end border-start bg-white">
                <div class="d-flex justify-content-between align-items-start mb-4">
                  <div>
                    <h6 class="fw-bold mb-1">All Recurring Projects ({{ recurringProjectCount }})</h6>
                    <p class="text-muted small mb-0">{{ recurringProjectCount }} recurring projects added</p>
                  </div>
                </div>
                <!-- Grand Total Charge -->
                <div class="p-3 d-flex justify-content-between align-items-center" style="background-color: #d4f1f3;">
                  <h6 class="fw-bold text-uppercase mb-0" style="color: #17a2b8;">CHARGE TO CLIENT (MONTHLY)</h6>
                  <div class="d-flex align-items-center gap-2">
                    <span class="fw-bold" style="font-size: 1.3rem; color: #17a2b8;">= ${{ totalRecurringGlobalCharge.toFixed(2) }}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- COMBINED TOTAL CARD (Janitorial + Recurring Projects) -->
        <div class="container mt-5 mb-5" v-if="selectedOption == '1' && showCombinedTotalCard">
          <div class="d-flex align-items-center rounded shadow-sm text-white position-relative overflow-hidden" style="background-color: #5c6b7f; min-height: 120px;">
            
            <!-- Decorative slants (Optional css polish to match image background style) -->
            <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(105deg, rgba(255,255,255,0.05) 20%, transparent 20%); pointer-events: none;"></div>

            <!-- Icons Section -->
            <div class="ps-5 pe-4 py-4 d-flex align-items-center gap-3 position-relative">
              <!-- Person Icon -->
              <svg data-v-0bd23656="" width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg"><circle data-v-0bd23656="" cx="20" cy="12" r="6" stroke="#17a2b8" stroke-width="2"></circle><path data-v-0bd23656="" d="M8 32C8 25.37 13.37 20 20 20C26.63 20 32 25.37 32 32" stroke="#17a2b8" stroke-width="2" stroke-linecap="round" style="background-color:white;"></path></svg>
              
              <!-- Plus Icon (Teal Circle) -->
              <div class="rounded-circle bg-teal d-flex align-items-center justify-content-center" style="width: 20px; height: 20px; background-color: #20c997;">
                +
              </div>
              
              <!-- Folder/Recurring Icon -->
              <!-- <div class="position-relative">

              </div> -->
            </div>

            <!-- Text Section -->
            <div class="flex-grow-1 px-3 position-relative">
              <p class="mb-0" style="font-size: 1.1rem; line-height: 1.4;">
                Combine <span class="fw-bold">{{ janitorialSectionLabel }} Expenses</span> with <span class="fw-bold">Recurring Projects</span> to give me <br>
                a monthly cost to charge Client
              </p>
            </div>

            <!-- Vertical Divider -->
            <div style="width: 1px; height: 70px; background-color: rgba(255,255,255,0.2); margin-right: 2rem;"></div>

            <!-- Price Section -->
            <div class="pe-5 text-end position-relative">
              <span class="d-block text-light mb-1" style="font-size: 0.9rem; opacity: 0.9;">Monthly Charge to Client</span>
              <span class="fw-bold" style="font-size: 2.5rem; letter-spacing: 1px;">${{ combinedMonthlyGrandTotal.toFixed(2) }}</span>
            </div>
          </div>
        </div>

        <!-- One Time PRojects Section-->

        <div class="mt-5" v-if="selectedOption == '1' && oneTimeProjects.length > 0">
          
          <!-- Header -->
          <div class="text-center mb-4">
            <div class="d-flex justify-content-center align-items-center gap-2">
              <i class="bi bi-folder text-teal fs-3"></i>
            </div>
            <h4 class="fw-bold">One-Time Projects</h4>
          </div>

          <div v-for="(project, index) in oneTimeProjects" :key="project.id" class="mb-4">
            
            <!-- COLLAPSED VIEW (Matches your Screenshot) -->
            <div v-if="!project.isExpanded" class="labor-cost-card bg-white shadow-sm border-0 p-4 d-flex align-items-center justify-content-between">
              
              <!-- Left: Title -->
              <div class="d-flex flex-column" style="min-width: 250px;">
                <label class="text-uppercase small text-muted fw-bold" style="font-size: 0.7rem; letter-spacing: 0.5px;">ONE-TIME PROJECT</label>
                <h5 class="fw-bold mb-0 text-dark">{{ project.name }}</h5>
              </div>

              <!-- Middle: Stats with Slashes -->
              <div class="d-flex align-items-center bg-light rounded py-2 px-4 flex-grow-1 justify-content-center mx-4">
                
                <!-- Per Sq Ft -->
                <div class="text-center px-3">
                  <label class="text-uppercase text-muted fw-bold d-block" style="font-size: 0.65rem;">PER SQ FT</label>
                  <span class="fw-bold text-dark">${{ getProjectPerSqFt(project) }}</span>
                </div>

                <!-- Slash Separator -->
                <div class="text-muted fw-light fs-4 mx-2">/</div>

                <!-- Cost Per Clean -->
                <div class="text-center px-3">
                  <label class="text-uppercase text-muted fw-bold d-block" style="font-size: 0.65rem;">COST PER CLEAN</label>
                  <span class="fw-bold text-dark">${{ getProjectLaborCost(project).toFixed(2) }}</span>
                </div>

                <!-- Slash Separator -->
                <div class="text-muted fw-light fs-4 mx-2">/</div>

                <!-- One-Time Total -->
                <div class="text-center px-3">
                  <label class="text-uppercase text-muted fw-bold d-block" style="font-size: 0.65rem;">ONE-TIME</label>
                  <span class="fw-bold text-dark">${{ getProjectGrandTotal(project).toFixed(2) }}</span>
                </div>

              </div>

              <!-- Right: Button -->
              <div>
                <button class="btn btn-white text-teal fw-bold small d-flex align-items-center gap-2 border-0 shadow-none" @click="project.isExpanded = true">
                  SHOW PROJECT DETAILS 
                  <i class="bi bi-arrow-down-circle-fill fs-5 text-teal"></i>
                </button>
              </div>

            </div>

            <!-- EXPANDED VIEW (Kept exactly as previously built) -->
            <div v-if="project.isExpanded" class="labor-cost-card bg-white border-0 shadow-sm rounded overflow-hidden">
              
              <!-- 1. Header & Info Bar -->
              <div class="p-4 border-bottom">
                <label class="text-uppercase small text-muted fw-bold" style="font-size: 0.7rem;">ONE-TIME PROJECT</label>
                <h4 class="fw-bold mb-3">{{ project.name }}</h4>
                
                <div class="bg-light p-3 rounded d-flex justify-content-between align-items-center">
                  <div>
                    <i class="bi bi-clock text-muted me-2"></i>
                    <span class="small text-muted fw-bold text-uppercase d-block" style="font-size: 0.7rem;">Frequency</span>
                    <span class="fw-bold">1 Time</span>
                  </div>
                  <div class="text-center">
                    <i class="bi bi-building text-teal mb-1 d-block"></i>
                    <small class="text-muted d-block mb-1">Areas included in this project</small>
                    <span class="badge bg-white text-dark border rounded-pill px-3 py-2">{{ project.areaNames }}</span>
                  </div>
                  <div><!-- Spacer for alignment --></div>
                </div>
              </div>

              <!-- 2. Inputs Section -->
              <div class="p-4 border-bottom">
                <div class="row g-4">
                  <div class="col-md-3">
                    <label class="fw-bold text-teal mb-2"><i class="bi bi-people-fill me-1"></i> Labor</label>
                    <select class="form-select border-bottom border-0 rounded-0 ps-0 bg-transparent" style="border-color: #333 !important;">
                      <option>Custodians</option>
                      <option value="cleaners">Cleaners</option>
                      <option value="supervisors">Supervisors</option>
                    </select>
                  </div>
                  <div class="col-md-3">
                    <label class="fw-bold text-muted mb-2"><i class="bi bi-person me-1"></i> Staff</label>
                    <input type="number" class="form-control border-bottom border-0 rounded-0 ps-0 bg-transparent text-center fw-bold" v-model="project.staff">
                  </div>
                  <div class="col-md-3">
                    <label class="fw-bold text-teal mb-2"><i class="bi bi-currency-dollar me-1"></i> Rate of Pay</label>
                    <input type="number" class="form-control border-bottom border-0 rounded-0 ps-0 bg-transparent text-center fw-bold" v-model="project.rateOfPay">
                  </div>
                  <div class="col-md-3">
                    <label class="fw-bold text-muted mb-2"><i class="bi bi-clock me-1"></i> Hours</label>
                    <input type="number" class="form-control border-bottom border-0 rounded-0 ps-0 bg-transparent text-center fw-bold" v-model="project.hours">
                  </div>
                </div>
                
                <!-- <button class="btn btn-light text-teal fw-bold mt-3 rounded-pill small px-4">ADD NEW PROJECT COST</button> -->
              </div>

              <!-- 3. Result Strip -->
              <div class="bg-light p-4 d-flex justify-content-end align-items-center">
                <span class="fw-bold text-uppercase me-3 small" style="letter-spacing: 1px;">Your One Time Labor Cost</span>
                <div class="bg-white px-4 py-2 rounded shadow-sm fw-bold border">
                  ${{ getProjectLaborCost(project).toFixed(2) }}
                </div>
              </div>

              <!-- 4. SUMMARY SECTION (The Blue Box) -->
              <div class="p-5" style="background-color: #eafcfd;">
                <h4 class="fw-bold text-teal mb-1">Price for This One-Time Project</h4>
                <p class="text-muted mb-4">For this one-time project only</p>

                <div class="row">
                  <!-- Left Col -->
                  <div class="col-md-5 border-end">
                    <div class="mb-4 text-center">
                      <label class="fw-bold text-dark"><i class="bi bi-rulers text-teal me-2"></i>Project Sq Ft.</label>
                      <input type="number" disabled class="form-control border-0 border-bottom bg-transparent text-center fw-bold fs-5" placeholder="0" v-model="project.sqFt">
                      <small class="text-muted">0 / 8</small>
                    </div>

                    <div class="d-flex align-items-center justify-content-center gap-3 mb-5">
                      <label class="fw-bold text-teal">$ Per Sq Ft</label>
                      <div aria-disabled="true" class="bg-white px-3 py-2 rounded border fw-bold">{{ getProjectPerSqFt(project) }}</div>
                    </div>

                    <div class="d-flex align-items-center justify-content-center gap-2">
                      <small>Add Sales Tax to your services?</small>
                      <div class="form-check form-switch">
                          <input class="form-check-input" type="checkbox" v-model="project.addSalesTax">
                      </div>
                    </div>
                  </div>

                  <!-- Right Col (Calculations) -->
                  <div class="col-md-7 ps-4">
                    <!-- Labor Line -->
                    <div class="d-flex justify-content-between mb-2">
                      <span class="fw-bold">All Labor Costs for this One Time Project</span>
                      <span class="fw-bold">${{ getProjectLaborCost(project).toFixed(2) }}</span>
                    </div>

                    <!-- Expenses -->
                    <div class="mb-3">
                      <div class="d-flex justify-content-between">
                        <span class="fw-bold">Additional Expenses ({{ project.expenses.length }})</span>
                        <span class="fw-bold">${{ (getProjectSubTotal(project) - getProjectLaborCost(project)).toFixed(2) }}</span>
                      </div>
                      <!-- Expense List -->
                      <div v-for="(exp, i) in project.expenses" :key="i" class="d-flex justify-content-between small text-muted mt-1 ps-3 border-start border-3 border-info">
                        <span>{{ exp.name }}</span>
                        <span>${{ exp.cost.toFixed(2) }} 
                          <button class="btn btn-sm text-danger"@click="removeProjectExpense(project, i)">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                      </button>
                      <!-- <i class="bi bi-x text-danger cursor-pointer" @click="removeProjectExpense(project, i)"></i> -->
                    </span>
                      </div>
                      <!-- Add Expense Inputs -->
                      <div class="row g-2 mt-2">
                        <div class="col-5"><input class="form-control form-control-sm border-0 border-bottom bg-transparent" placeholder="Expense Name" v-model="project.newExpenseName"></div>
                        <div class="col-3"><input class="form-control form-control-sm border-0 border-bottom bg-transparent" placeholder="$ 0" type="number" v-model="project.newExpenseCost"></div>
                        <div class="col-4"><button class="btn btn-sm btn-teal text-white w-100" @click="addProjectExpense(project)">ADD EXPENSE</button></div>
                      </div>
                    </div>

                    <div class="d-flex justify-content-between mb-3 pt-2 border-top border-dark">
                      <span class="fw-bold">This One-Time Project Cost Subtotal</span>
                      <span class="fw-bold fs-5">${{ getProjectSubTotal(project).toFixed(2) }}</span>
                    </div>

                    <!-- Profit -->
                    <div class="d-flex align-items-center justify-content-between mb-3">
                      <span class="fw-bold">Add Your Profit Margin</span>
                      <div class="d-flex gap-2 align-items-center">
                        <div class="input-group input-group-sm" style="width: 80px;"><input class="form-control fw-bold" v-model="project.marginPercent" @input="updateProjectMarginPercent(project)"><span class="input-group-text border-0 bg-transparent">%</span></div>
                        <span class="text-muted small">or</span>
                        <div class="input-group input-group-sm" style="width: 100px;"><span class="input-group-text border-0 bg-transparent">$</span><input class="form-control fw-bold" v-model="project.marginDollar" @input="updateProjectMarginDollar(project)"></div>
                      </div>
                      <span class="fw-bold">${{ (parseFloat(project.marginDollar)||0).toFixed(2) }}</span>
                    </div>

                    <!-- Tax Input (Visible if Toggled) -->
                    <div class="d-flex align-items-center justify-content-between mb-3" v-if="project.addSalesTax">
                      <span class="fw-bold text-muted">Sales Tax</span>
                      <div class="input-group input-group-sm" style="width: 100px;"><input class="form-control fw-bold" v-model="project.salesTaxPercent"><span class="input-group-text border-0 bg-transparent">%</span></div>
                      <span class="fw-bold text-danger">+ ${{ ((getProjectSubTotal(project) + parseFloat(project.marginDollar||0)) * (project.salesTaxPercent/100)).toFixed(2) }}</span>
                    </div>

                    <!-- Grand Total -->
                    <div class="bg-white p-3 rounded d-flex justify-content-between align-items-center mt-3">
                      <span class="fw-bold text-teal">GRAND TOTAL</span>
                      <div class="d-flex align-items-center gap-2">
                        <span class="text-teal fs-5">=</span>
                        <span class="text-teal fw-bold fs-5">$</span>
                        <span class="text-teal fw-bold fs-4 border-bottom border-teal px-2">{{ getProjectGrandTotal(project).toFixed(2) }}</span>
                      </div>
                    </div>

                  </div>
                </div>
              </div>

              <!-- Footer -->
              <div class="p-3 bg-white text-end">
                <button class="btn btn-link text-muted text-decoration-none small fw-bold" @click="project.isExpanded = false">
                  HIDE COST DETAILS <i class="bi bi-arrow-up-circle-fill text-teal ms-1"></i>
                </button>
              </div>

            </div>
          </div>
        </div>

    <!-- <div class="border-card mt-5" v-if="selectedOption == '2'">
      <!-- Tabs Header 
      <div class="tab-header">
        <div class="tab-item" :class="{ active: activeTab == 'account' }" @click="activeTab = 'account'">
          <span class="tab-badge">1</span>
          Account Info
        </div>
        <div class="tab-item" :class="{ active: activeTab == 'cost' }" @click="activeTab = 'cost'">
          <span class="tab-badge">2</span>
          Cost Info
        </div>
      </div>

      <!-- Form Content 
      <div class="container">
        <!-- ACCOUNT INFO TAB 
        <div v-if="activeTab == 'account'">
          <!-- Building Types Dropdown 
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

          <!-- How Many Sq Ft Input 
          <div class="form-group-custom">
            <label class="form-label fw-bold" style="font-size: 0.9rem; margin-bottom: 0.5rem; display: block;">How Many Sq Ft</label>
            <div class="position-relative">
              <input type="number" class="form-control-custom w-100" placeholder="0" v-model="sqFtAmount">
              <div class="text-end text-muted small mt-1">{{ sqFtAmount || 0 }} / 9</div>
            </div>
          </div>

          <!-- Frequency Dropdown 
          <div class="form-group-custom">
            <label class="form-label fw-bold text-teal" style="font-size: 0.9rem; margin-bottom: 0.5rem; display: block;">Frequency</label>
            <select class="form-control-custom form-select w-100" v-model="frequency">
              <option value="">Select frequency</option>
              <option value="1/week">1/week</option>
              <option value="2/week">2/week</option>
              <option value="monthly">Monthly</option>
            </select>
          </div>

          <!-- Difficulty Slider 
          <div class="slider-container">
            <label class="slider-label">Difficulty</label>
            <input type="range" class="range-slider" min="0" max="100" v-model="difficulty" style="background: linear-gradient(to right, #17a2b8 0%, #17a2b8 50%, #e9ecef 50%, #e9ecef 100%);">
            <div class="slider-labels">
              <span>Easy</span>
              <span>Medium</span>
              <span>Difficult</span>
            </div>
          </div>

          <!-- Floor Slider (Carpet vs Hard Floor) 
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

          <!-- Next Button 
          <div class="mt-4">
            <button class="btn-next" @click="handleNext">Next</button>
          </div>
        </div>

        <!-- COST INFO TAB 
        <div v-if="activeTab == 'cost'">
          <!-- Will you use Employees? 
          <div class="form-group-custom">
            <label class="form-label fw-bold text-teal" style="font-size: 0.9rem; margin-bottom: 0.5rem; display: block;">Will you use Employees?</label>
            <select class="form-control-custom form-select w-100" v-model="useEmployees">
              <option value="">Select option</option>
              <option value="yes">Yes</option>
              <option value="no">No</option>
            </select>
          </div>

          <!-- Rate of Pay 
          <div class="form-group-custom">
            <label class="form-label fw-bold" style="font-size: 0.9rem; margin-bottom: 0.5rem; display: block;">Rate of Pay</label>
            <div class="position-relative">
              <input type="number" class="form-control-custom w-100" placeholder="0" v-model="rateOfPay">
              <div class="text-end text-muted small mt-1">{{ rateOfPayCount || 0 }} / 3</div>
            </div>
          </div>

          <!-- Payroll Tax 
          <div class="form-group-custom">
            <label class="form-label fw-bold text-teal" style="font-size: 0.9rem; margin-bottom: 0.5rem; display: block;">Payroll tax</label>
            <div class="position-relative">
              <input type="number" class="form-control-custom w-100" placeholder="30%" v-model="payrollTaxCost">
              <div class="text-end text-muted small mt-1">{{ payrollTaxCount || 0 }} / 3</div>
            </div>
          </div>

          <!-- Supplies 
          <div class="form-group-custom">
            <label class="form-label fw-bold text-teal" style="font-size: 0.9rem; margin-bottom: 0.5rem; display: block;">Supplies</label>
            <div class="position-relative">
              <input type="number" class="form-control-custom w-100" placeholder="5%" v-model="supplies">
              <div class="text-end text-muted small mt-1">{{ suppliesCount || 0 }} / 3</div>
            </div>
          </div>

          <!-- Overhead 
          <div class="form-group-custom">
            <label class="form-label fw-bold text-teal" style="font-size: 0.9rem; margin-bottom: 0.5rem; display: block;">Overhead</label>
            <div class="position-relative">
              <input type="number" class="form-control-custom w-100" placeholder="5%" v-model="overheadCost">
              <div class="text-end text-muted small mt-1">{{ overheadCount || 0 }} / 3</div>
            </div>
          </div>

          <!-- Profit Margin Slider with Color Gradient 
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

          <!-- Monthly Profit, Cleaning Time, Price Per Service, Monthly Price 
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

          <!-- Next Button for Cost Info 
          <div class="mt-4">
            <button class="btn-next" @click="handleCostInfoNext">Next</button>
          </div>
        </div>
      </div>
    </div> -->

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
      // laborCardExpanded: false,
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
      showProjectCostModal: false,
      laborCosts: [], 
      additionalExpenses: [], // Stores { id, name, cost }
      newExpenseName: '',     // Input for new expense name
      newExpenseCost: '',     // Input for new expense cost
      salesTaxPercent: 0, 
      isLoadingProjects: false,
      recurringProjects: [], // Stores fetched projects with is_recurring = 1
      oneTimeProjects: [],   // Stores fetched projects with is_recurring = 0
      
      // Recurring Projects Summary Data (Distinct from Janitorial)
      recurringExpenses: [],
      newRecurringExpenseName: '',
      newRecurringExpenseCost: '',
      recurringMarginPercent: 0,
      recurringMarginDollar: 0,
      recurringSalesTaxPercent: 0,
      addRecurringSalesTax: false,
      proposalType: '',     
      proposalCategory: '',
    };
  },
  computed: {
    combinedMonthlyGrandTotal() {
      const janitorial = parseFloat(this.finalMonthlyCharge) || 0;
      const recurringProjects = parseFloat(this.totalRecurringGlobalCharge) || 0;
      return janitorial + recurringProjects;
    },
    totalPayroll() {
      return parseFloat(this.payrollTaxes || 0) + parseFloat(this.insurance || 0) + parseFloat(this.overhead || 0);
    },
    totalMonthlyLaborCost() {
      return this.laborCosts.reduce((sum, cost) => {
        return sum + this.getMonthlyCost(cost); // Uses the helper method we made earlier
      }, 0);
    },

    // 2. Sum of Additional Expenses
    totalAdditionalExpenses() {
      return this.additionalExpenses.reduce((sum, item) => {
        return sum + (parseFloat(item.cost) || 0);
      }, 0);
    },

    // 3. Sub-Total (Labor + Expenses)
    subTotal() {
      return this.totalMonthlyLaborCost + this.totalAdditionalExpenses;
    },

    // 4. Calculate Tax Amount (if toggle is on)
    calculatedTaxAmount() {
      if (!this.addSalesTax) return 0;
      // Tax is usually calculated on (Subtotal + Profit)
      const taxableAmount = this.subTotal + (parseFloat(this.marginDollar) || 0);
      return taxableAmount * ((parseFloat(this.salesTaxPercent) || 0) / 100);
    },

    // 5. Final Monthly Charge to Client
    finalMonthlyCharge() {
      const profit = parseFloat(this.marginDollar) || 0;
      return this.subTotal + profit + this.calculatedTaxAmount;
    },

    // 6. Price Per Sq Ft (Calculated Result)
    calculatedPerSqFt() {
      const sqFt = parseFloat(this.buildingSqFt) || 0;
      if (sqFt == 0) return 0;
      return (this.finalMonthlyCharge / sqFt).toFixed(2);
    },
    totalRecurringProjectsCost() {
      return this.recurringProjects.reduce((sum, proj) => {
        return sum + this.getRecurringProjectMonthlyCost(proj);
      }, 0);
    },

    // 2. Sum of Recurring Expenses
    totalRecurringExpenses() {
      return this.recurringExpenses.reduce((sum, item) => sum + (parseFloat(item.cost) || 0), 0);
    },

    // 3. Recurring Subtotal
    recurringSubTotal() {
      return this.totalRecurringProjectsCost + this.totalRecurringExpenses;
    },

    // 4. Recurring Tax
    recurringCalculatedTax() {
      if (!this.addRecurringSalesTax) return 0;
      const taxable = this.recurringSubTotal + (parseFloat(this.recurringMarginDollar) || 0);
      return taxable * ((parseFloat(this.recurringSalesTaxPercent) || 0) / 100);
    },

    // 5. Recurring Final Charge
    recurringFinalCharge() {
      return this.recurringSubTotal + (parseFloat(this.recurringMarginDollar) || 0) + this.recurringCalculatedTax;
    },
    totalRecurringGlobalCharge() {
      return this.recurringProjects.reduce((sum, proj) => {
        return sum + this.getProjectGrandTotal(proj);
      }, 0);
    },
    
    // Count of projects
    recurringProjectCount() {
      return this.recurringProjects.length;
    },

    isJanitorial() {
      return this.proposalCategory == 'janitorial_projects' || 
             this.proposalCategory == 'janitorial_cleaning' || // DB value might differ
             this.proposalCategory == 'cleaning_projects';
    },

    isConstruction() {
      return this.proposalCategory == 'construction_cleaning';
    },

    isProjectsOnly() {
      return this.proposalCategory == 'projects_only' || 
             this.proposalCategory == 'projects'; // Handle potential DB variations
    },

    // CASE 1 & Logic: Show Janitorial Section?
    // Show for Janitorial. Hide for Construction & Projects Only.
    showJanitorialSection() {
      return this.isJanitorial;
    },

    // CASE 1 Dynamic Label:
    janitorialSectionLabel() {
      if (this.proposalType == 'residential' && this.proposalCategory == 'cleaning_projects') {
        return 'Cleaning Labor Costs';
      }
      return 'Janitorial Labor Costs'; // Default
    },

    // CASE 2 & 3: Show Recurring Projects?
    // Hide for Construction. Show for Janitorial and Projects Only.
    showRecurringSection() {
      return !this.isConstruction; 
    },

    // CASE 2 & 3: Show One-Time Projects?
    // Always show if they exist (Your requirements imply showing them in all cases if they exist)
    showOneTimeSection() {
      return this.oneTimeProjects.length > 0;
    },

    // Summary Card Visibility
    showCombinedTotalCard() {
       // Only show if we are showing Janitorial AND Recurring sections
       return this.showJanitorialSection;
    }
  },
  watch: {
    // Watch deep objects (Arrays)
    laborCosts: { handler() { this.debouncedSave(); }, deep: true },
    additionalExpenses: { handler() { this.debouncedSave(); }, deep: true },
    recurringProjects: { handler() { this.debouncedSave(); }, deep: true },
    oneTimeProjects: { handler() { this.debouncedSave(); }, deep: true },
    recurringExpenses: { handler() { this.debouncedSave(); }, deep: true },

    // Watch Simple Variables
    payrollTaxes() { this.debouncedSave(); },
    insurance() { this.debouncedSave(); },
    overhead() { this.debouncedSave(); },
    buildingSqFt() { this.debouncedSave(); },
    
    marginPercent() { this.debouncedSave(); },
    marginDollar() { this.debouncedSave(); },
    salesTaxPercent() { this.debouncedSave(); },
    addSalesTax() { this.saveToDatabase(); }, // Toggle saves instantly

    recurringMarginPercent() { this.debouncedSave(); },
    recurringMarginDollar() { this.debouncedSave(); },
    recurringSalesTaxPercent() { this.debouncedSave(); },
    addRecurringSalesTax() { this.saveToDatabase(); },
  },
  async mounted() {
    // 1. Fetch structure from DB (Proposals table)
    await this.fetchProposalProjects();
    
    // 2. Fill in the saved numbers from DB (Calculations table)
    await this.loadFromDatabase();
  },
  methods: {
      getCostPerClean(cost) {
          const base = (parseFloat(cost.staff) || 0) * 
               (parseFloat(cost.rateOfPay) || 0) * 
               (parseFloat(cost.hours) || 0);
               
          const burdenMultiplier = 1 + (this.totalPayroll / 100);

          return base * burdenMultiplier;
      },

      getMonthlyCost(cost) {
        const perClean = this.getCostPerClean(cost);
        const freq = parseFloat(cost.frequency) || 0;
        const type = cost.per;
        let monthly = 0;

        if (type == 'Week') {
          monthly = perClean * (freq * 4.333);
        } else if (type == 'Month') {
          monthly = perClean * freq;
        } else if (type == 'Quarter') {
          monthly = perClean * (freq / 3);
        } else if (type == 'Year') {
          monthly = perClean * (freq / 12);
        }
        return monthly;
      },

      getAnnualCost(cost) {
        return this.getMonthlyCost(cost) * 12;
      },
    // },
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
      // Check for limit
      // if (this.laborCosts.length >= 5) {
      //   alert("You can only add up to 5 labor costs.");
      //   return;
      // }

      // Push new object to array
      this.laborCosts.push({
        id: Date.now(), // Unique ID for v-key
        ...formData,    // Spread the data from the modal (labor, staff, etc.)
        isExpanded: false // Default UI state
      });

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
    addExpense() {
      if (this.additionalExpenses.length >= 5) {
        alert("You can only add up to 5 additional expenses.");
        return;
      }
      if (!this.newExpenseName || !this.newExpenseCost) {
        alert("Please enter both a name and a cost.");
        return;
      }

      this.additionalExpenses.push({
        id: Date.now(),
        name: this.newExpenseName,
        cost: parseFloat(this.newExpenseCost)
      });

      // Reset inputs
      this.newExpenseName = '';
      this.newExpenseCost = '';
    },

    removeExpense(index) {
      this.additionalExpenses.splice(index, 1);
    },

    // --- Profit Margin Bidirectional Logic ---
    
    // Called when user types in % field
    updateMarginFromPercent() {
      // Logic: Dollar = SubTotal * (% / 100)
      const percent = parseFloat(this.marginPercent) || 0;
      this.marginDollar = (this.subTotal * (percent / 100)).toFixed(2);
    },

    // Called when user types in $ field
    updateMarginFromDollar() {
      // Logic: % = (Dollar / SubTotal) * 100
      const dollar = parseFloat(this.marginDollar) || 0;
      if (this.subTotal == 0) {
        this.marginPercent = 0;
        return;
      }
      this.marginPercent = ((dollar / this.subTotal) * 100).toFixed(2);
    },

    async fetchProposalProjects() {
      this.isLoadingProjects = true;
      try {
        const proposalId = this.$route.params.id; 
        const response = await axios.get(`/api/proposals/${proposalId}/projects-for-calculator`);
        this.proposalType = response.data.proposal_type;
        this.proposalCategory = response.data.category;
        const projects = response.data.projects; 

        this.recurringProjects = [];
        this.oneTimeProjects = [];

        projects.forEach(p => {
          const mappedProject = {
            id: p.id,
            name: p.service_type_name || 'Project', 
            areaNames: Array.isArray(p.area_names) ? p.area_names.join(', ') : 'Whole Building',
            is_recurring: p.is_recurring,
            frequency: p.frequency_id, 
            per: p.per, 
            
            // --- INPUTS ---
            staff: 1,
            rateOfPay: 0,
            hours: 0,
            
            // --- CALCULATOR STATE (Specific to this project) ---
            isExpanded: false,
            expenses: [], // Stores {name, cost}
            newExpenseName: '',
            newExpenseCost: '',
            marginPercent: 0,
            marginDollar: 0,
            addSalesTax: false,
            salesTaxPercent: 0,
            sqFt: 0,
          };

          if (p.is_recurring == 1) {
            this.recurringProjects.push(mappedProject);
          } else {
            this.oneTimeProjects.push(mappedProject);
          }
        });

      } catch (error) {
        console.error("Error fetching projects:", error);
      } finally {
        this.isLoadingProjects = false;
      }
    },

    // --- PROJECT SPECIFIC HELPER METHODS ---

    // 1. Calculate Labor Cost (Base)
    getProjectLaborCost(project) {
      const base = (parseFloat(project.staff) || 0) * 
               (parseFloat(project.rateOfPay) || 0) * 
               (parseFloat(project.hours) || 0);

      // 2. Calculate Burden Multiplier
      const burdenMultiplier = 1 + (this.totalPayroll / 100);

      // 3. Return Fully Loaded Cost
      return base * burdenMultiplier;
    },

    // 2. Calculate Monthly Cost (For Recurring)
    getProjectMonthlyLaborCost(project) {
      const base = this.getProjectLaborCost(project);
      const freq = parseFloat(project.frequency) || 0;
      const per = project.per ? project.per.toLowerCase() : '';
      
      if (per == 'week') return base * (freq * 4.333);
      if (per == 'month') return base * freq;
      if (per == 'quarter') return base * (freq / 3);
      if (per == 'year') return base * (freq / 12);
      return base * freq;
    },

    // 3. Add Expense to a specific project
    addProjectExpense(project) {
      if (project.expenses.length >= 5) return;
      if (!project.newExpenseName || !project.newExpenseCost) return;

      project.expenses.push({
        id: Date.now(),
        name: project.newExpenseName,
        cost: parseFloat(project.newExpenseCost)
      });
      
      // Clear inputs
      project.newExpenseName = '';
      project.newExpenseCost = '';
    },

    // 4. Remove Expense
    removeProjectExpense(project, index) {
      project.expenses.splice(index, 1);
    },

    // 5. Calculate Project Subtotal (Labor + Expenses)
    getProjectSubTotal(project) {
      const labor = project.is_recurring == 1 
        ? this.getProjectMonthlyLaborCost(project) 
        : this.getProjectLaborCost(project);
        
      const expenses = project.expenses.reduce((sum, item) => sum + item.cost, 0);
      return labor + expenses;
    },

    // 6. Update Margins (Bidirectional)
    updateProjectMarginPercent(project) {
      const sub = this.getProjectSubTotal(project);
      const pct = parseFloat(project.marginPercent) || 0;
      project.marginDollar = (sub * (pct / 100)).toFixed(2);
    },

    updateProjectMarginDollar(project) {
      const sub = this.getProjectSubTotal(project);
      const dol = parseFloat(project.marginDollar) || 0;
      if(sub == 0) { project.marginPercent = 0; return; }
      project.marginPercent = ((dol / sub) * 100).toFixed(2);
    },

    // 7. Get Grand Total for a Project
    getProjectGrandTotal(project) {
      const sub = this.getProjectSubTotal(project);
      const profit = parseFloat(project.marginDollar) || 0;
      let tax = 0;
      
      if (project.addSalesTax) {
        tax = (sub + profit) * ((parseFloat(project.salesTaxPercent) || 0) / 100);
      }
      
      return sub + profit + tax;
    },

    // 8. Get $ Per Sq Ft
    getProjectPerSqFt(project) {
      const total = this.getProjectGrandTotal(project);
      const sqFt = parseFloat(project.sqFt) || 0;
      if (sqFt == 0) return '0.00';
      return (total / sqFt).toFixed(2);
    },
    // --- STEP 2: CALCULATION FORMULAS ---

    // 1. Recurring Project Monthly Cost (Same logic as Janitorial)
    getRecurringProjectMonthlyCost(project) {
      const baseCost = (parseFloat(project.staff) || 0) * 
                      (parseFloat(project.rateOfPay) || 0) * 
                      (parseFloat(project.hours) || 0);
      
      const freq = parseFloat(project.frequency) || 0;
      const per = project.per ? project.per.toLowerCase() : '';

      let monthly = 0;
      if (per == 'week') monthly = baseCost * (freq * 4.333);
      else if (per == 'month') monthly = baseCost * freq;
      else if (per == 'quarter') monthly = baseCost * (freq / 3);
      else if (per == 'year') monthly = baseCost * (freq / 12);
      // fallback
      else monthly = baseCost * freq; 

      return monthly;
    },

    // 2. One-Time Project Total Cost (Simple Multiplication)
    getOneTimeProjectTotalCost(project) {
      // For one-time, frequency usually implies "doing it once", 
      // but typically it's just Staff * Rate * Hours for the whole job.
      const base = (parseFloat(project.staff) || 0) * 
                  (parseFloat(project.rateOfPay) || 0) * 
                  (parseFloat(project.hours) || 0);
                  
      // 2. Calculate Burden Multiplier
      const burdenMultiplier = 1 + (this.totalPayroll / 100);

      // 3. Return Fully Loaded Cost
      return base * burdenMultiplier;
    },

    // --- STEP 3: RECURRING SUMMARY HELPERS (Expenses/Margins) ---
    addRecurringExpense() {
      if (this.recurringExpenses.length >= 5) return;
      if (!this.newRecurringExpenseName || !this.newRecurringExpenseCost) return;
      
      this.recurringExpenses.push({
        id: Date.now(),
        name: this.newRecurringExpenseName,
        cost: parseFloat(this.newRecurringExpenseCost)
      });
      this.newRecurringExpenseName = '';
      this.newRecurringExpenseCost = '';
    },
    removeRecurringExpense(index) {
      this.recurringExpenses.splice(index, 1);
    },
    updateRecurringMarginFromPercent() {
      const percent = parseFloat(this.recurringMarginPercent) || 0;
      this.recurringMarginDollar = (this.recurringSubTotal * (percent / 100)).toFixed(2);
    },
    updateRecurringMarginFromDollar() {
      const dollar = parseFloat(this.recurringMarginDollar) || 0;
      if (this.recurringSubTotal == 0) {
        this.recurringMarginPercent = 0;
        return;
      }
      this.recurringMarginPercent = ((dollar / this.recurringSubTotal) * 100).toFixed(2);
    },

    async saveToDatabase() {
      const proposalId = this.$route.params.id;
      
      // Construct the payload with ALL state variables
      const payload = {
        // 1. Global Payroll/Settings
        payrollTaxes: this.payrollTaxes,
        insurance: this.insurance,
        overhead: this.overhead,
        buildingSqFt: this.buildingSqFt,
        
        // 2. Janitorial Data
        laborCosts: this.laborCosts,
        additionalExpenses: this.additionalExpenses,
        marginPercent: this.marginPercent,
        marginDollar: this.marginDollar,
        addSalesTax: this.addSalesTax,
        salesTaxPercent: this.salesTaxPercent,

        // 3. Project Data (We save the whole array to keep their specific inputs)
        recurringProjects: this.recurringProjects,
        oneTimeProjects: this.oneTimeProjects,
        
        // 4. Recurring Summary Data
        recurringExpenses: this.recurringExpenses,
        recurringMarginPercent: this.recurringMarginPercent,
        recurringMarginDollar: this.recurringMarginDollar,
        addRecurringSalesTax: this.addRecurringSalesTax,
        recurringSalesTaxPercent: this.recurringSalesTaxPercent,
      };

      try {
        await axios.post(`/api/proposals/${proposalId}/calculator`, payload);
        console.log("Auto-saved successfully");
      } catch (error) {
        console.error("Save failed", error);
      }
    },

    async loadFromDatabase() {
      const proposalId = this.$route.params.id;
      try {
        const response = await axios.get(`/api/proposals/${proposalId}/calculator`);
        const data = response.data;

        if (Object.keys(data).length == 0) return; // No data saved yet

        // 1. Restore Global Settings
        this.payrollTaxes = data.payrollTaxes || 0;
        this.insurance = data.insurance || 0;
        this.overhead = data.overhead || 0;
        this.buildingSqFt = data.buildingSqFt || 0;

        // 2. Restore Janitorial
        this.laborCosts = data.laborCosts || [];
        this.additionalExpenses = data.additionalExpenses || [];
        this.marginPercent = data.marginPercent || 0;
        this.marginDollar = data.marginDollar || 0;
        this.addSalesTax = data.addSalesTax || false;
        this.salesTaxPercent = data.salesTaxPercent || 0;

        // 3. Restore Recurring Summary
        this.recurringExpenses = data.recurringExpenses || [];
        this.recurringMarginPercent = data.recurringMarginPercent || 0;
        this.recurringMarginDollar = data.recurringMarginDollar || 0;
        this.addRecurringSalesTax = data.addRecurringSalesTax || false;
        this.recurringSalesTaxPercent = data.recurringSalesTaxPercent || 0;

        // 4. Restore Projects (Tricky Part: Merge saved values with DB structure)
        // We call this AFTER fetchProposalProjects finishes
        this.mergeProjectData(this.recurringProjects, data.recurringProjects);
        this.mergeProjectData(this.oneTimeProjects, data.oneTimeProjects);

      } catch (error) {
        console.error("Load failed", error);
      }
    },

    // Helper to merge saved project inputs (staff, rate, etc) into the fresh DB list
    mergeProjectData(currentList, savedList) {
      if (!savedList || savedList.length == 0) return;

      currentList.forEach(currentProj => {
        const savedProj = savedList.find(s => s.id == currentProj.id);
        if (savedProj) {
          // Restore inputs
          currentProj.staff = savedProj.staff;
          currentProj.rateOfPay = savedProj.rateOfPay;
          currentProj.hours = savedProj.hours;
          
          // Restore calculator state
          currentProj.expenses = savedProj.expenses || [];
          currentProj.marginPercent = savedProj.marginPercent;
          currentProj.marginDollar = savedProj.marginDollar;
          currentProj.addSalesTax = savedProj.addSalesTax;
          currentProj.salesTaxPercent = savedProj.salesTaxPercent;
          currentProj.sqFt = savedProj.sqFt;
        }
      });
    }
  },
  created() {
    // Debounce function to prevent saving on every single keystroke
    // It waits 1 second after the user stops typing
    let timeout;
    this.debouncedSave = () => {
      clearTimeout(timeout);
      timeout = setTimeout(() => {
        this.saveToDatabase();
      }, 1000);
    };
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
