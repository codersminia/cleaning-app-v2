<template>
  <div class="container-fluid bg-light min-vh-100 py-4">
    <!-- Back Link -->
    <div class="mb-4">
      <a href="/dashboard" class="text-decoration-none text-muted d-flex align-items-center gap-2">
        <span>&lt;</span>
        <span>BACK</span>
      </a>
    </div>

    <!-- Header Section -->
    <div class="text-center mb-5">
      <div class="mb-3">
        <svg width="50" height="50" viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg">
          <rect x="8" y="6" width="34" height="38" rx="2" fill="none" stroke="#17a2b8" stroke-width="2"/>
          <line x1="12" y1="14" x2="38" y2="14" stroke="#17a2b8" stroke-width="1.5"/>
          <line x1="12" y1="20" x2="38" y2="20" stroke="#17a2b8" stroke-width="1.5"/>
          <line x1="12" y1="26" x2="30" y2="26" stroke="#17a2b8" stroke-width="1.5"/>
        </svg>
      </div>
      <h1 class="h2 fw-bold mb-2">All Sent Proposals</h1>
      <p class="text-muted">Select a proposal to see more tracking details</p>
    </div>

    <!-- Table -->
    <div class="table-responsive">
      <table class="table table-hover mb-0">
        <thead>
          <!-- Updated table header styling to match reference image exactly -->
          <tr style="background-color: #17a2b8;">
            <th class="text-white fw-bold py-3 ps-3" style="white-space: nowrap; min-width: max-content;">PROSPECTS</th>
            <th class="text-white fw-bold py-3" style="white-space: nowrap; min-width: max-content;">LOCATIONS</th>
            <th class="text-white fw-bold py-3" style="white-space: nowrap; min-width: max-content;">PROPOSALS</th>
            <th class="text-white fw-bold py-3" style="white-space: nowrap; min-width: max-content;">SENT</th>
            <th class="text-white fw-bold py-3" style="white-space: nowrap; min-width: max-content;">OPENED DATE</th>
            <th class="text-white fw-bold py-3 pe-3" style="white-space: nowrap; min-width: max-content;">TRACKING</th>
          </tr>
        </thead>
        <tbody>
          <!-- Row 1 -->
          <tr class="border-bottom">
            <td class="py-4">
              <div class="fw-bold">Ashley and Mcpherson LLC</div>
            </td>
            <td class="py-4">
              <small>Voluptas consequatur non dolorem ullam porro lorem elusmod repudiandae saepe tenetur voluptatem au,Unit 567,Reprehenderit recusandae Reprehenderit iusto ad ,Nihil explicabo Et delectus pariatur Officia ap,46585</small>
            </td>
            <td class="py-4">test</td>
            <td class="py-4">11/12/2025</td>
            <td class="py-4">
              <div>11/12/2025</div>
              <small class="text-muted">1:10 am</small>
            </td>
            <td class="py-4">
              <span class="badge bg-success text-white px-3 py-2" style="background-color: #17a2b8 !important; white-space: normal;">
                WAITING FOR YOUR SIGNATURE/SIGNED BY CODEEMINA@CMAIL.COM
              </span>
            </td>
          </tr>

          <!-- Row 2 -->
          <tr class="border-bottom" style="background-color: #f9f9f9;">
            <td class="py-4">
              <div class="fw-bold text-muted">test</div>
            </td>
            <td class="py-4">
              <small>test,Unit 12,test,test,23232</small>
            </td>
            <td class="py-4">Test</td>
            <td class="py-4">10/10/2025</td>
            <td class="py-4">Not Opened</td>
            <td class="py-4"></td>
          </tr>

          <!-- Row 3 -->
          <tr class="border-bottom">
            <td class="py-4">
              <div class="fw-bold">boys and girls</div>
            </td>
            <td class="py-4">
              <small>4242 collingwood,detroit,mi,48223</small>
            </td>
            <td class="py-4">body girls test</td>
            <td class="py-4">08/13/2025</td>
            <td class="py-4">
              <div>8/13/2025</div>
              <small class="text-muted">6:01 pm</small>
            </td>
            <td class="py-4">
              <button class="btn btn-sm px-4 py-2 text-white" style="background-color: #17a2b8; border: none; border-radius: 25px;">
                TRACK PROPOSAL
              </button>
            </td>
          </tr>

          <!-- Row 4 -->
          <tr class="border-bottom" style="background-color: #f9f9f9;">
            <td class="py-4">
              <div class="fw-bold">BUENA VISTA</div>
            </td>
            <td class="py-4">
              <small>1575 BUENA VISTA,DET,MI,48238</small>
            </td>
            <td class="py-4">BUENA VISTA</td>
            <td class="py-4">11/30/2024</td>
            <td class="py-4">Not Opened</td>
            <td class="py-4"></td>
          </tr>

          <!-- Row 5 -->
          <tr class="border-bottom">
            <td class="py-4">
              <div class="fw-bold">One Mike</div>
            </td>
            <td class="py-4">
              <small>1331 broadway,Detroit,Mich,48207</small>
            </td>
            <td class="py-4">deep clean kitchen Floor</td>
            <td class="py-4">11/26/2024</td>
            <td class="py-4">
              <div>12/5/2024</div>
              <small class="text-muted">9:12 am</small>
            </td>
            <td class="py-4">
              <button class="btn btn-sm px-4 py-2 text-white" style="background-color: #17a2b8; border: none; border-radius: 25px;">
                TRACK PROPOSAL
              </button>
            </td>
          </tr>

          <!-- Row 6 -->
          <tr class="border-bottom" style="background-color: #f9f9f9;">
            <td class="py-4">
              <div class="fw-bold">One Mike</div>
            </td>
            <td class="py-4">
              <small>1331 broadway,Detroit,Mich,48207</small>
            </td>
            <td class="py-4">One mike</td>
            <td class="py-4">11/06/2024</td>
            <td class="py-4">
              <div>11/6/2024</div>
              <small class="text-muted">10:45 am</small>
            </td>
            <td class="py-4">
              <button class="btn btn-sm px-4 py-2 text-white" style="background-color: #17a2b8; border: none; border-radius: 25px;">
                TRACK PROPOSAL
              </button>
            </td>
          </tr>

          <!-- Row 7 -->
          <tr class="border-bottom">
            <td class="py-4">
              <div class="fw-bold">Ken house cleaning</div>
            </td>
            <td class="py-4">
              <small>11091 McKinney Detroit, MI,Detroit,MI,48223</small>
            </td>
            <td class="py-4">McKinney Detroit,</td>
            <td class="py-4">07/30/2024</td>
            <td class="py-4">
              <div>8/5/2024</div>
              <small class="text-muted">2:01 pm</small>
            </td>
            <td class="py-4">
              <button class="btn btn-sm px-4 py-2 text-white" style="background-color: #17a2b8; border: none; border-radius: 25px;">
                TRACK PROPOSAL
              </button>
            </td>
          </tr>

          <!-- Row 8 -->
          <tr class="border-bottom" style="background-color: #f9f9f9;">
            <td class="py-4">
              <div class="fw-bold">Woodmount</div>
            </td>
            <td class="py-4">
              <small>14531 woodmount,Detroit,Mich ,48223</small>
            </td>
            <td class="py-4">Woodmount</td>
            <td class="py-4">07/14/2024</td>
            <td class="py-4">
              <div>7/15/2024</div>
              <small class="text-muted">12:37 pm</small>
            </td>
            <td class="py-4">
              <button class="btn btn-sm px-4 py-2 text-white" style="background-color: #17a2b8; border: none; border-radius: 25px;">
                TRACK PROPOSAL
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
// Static component - no script logic needed
</script>

<style scoped>
.table {
  background-color: white;
  margin-bottom: 0;
}

.table thead th {
  /* Set minimum width for header columns to prevent truncation */
  min-width: max-content;
  padding-left: 15px !important;
  padding-right: 15px !important;
}

.table thead th{
    background-color: #20b2aa;
}

.table tbody tr {
  border-top: 1px solid #e0e0e0;
}

.table tbody tr:hover {
  background-color: #f5f5f5 !important;
}

.table tbody td {
  vertical-align: middle;
  font-size: 14px;
}

.badge {
  font-weight: 600;
  font-size: 11px;
  line-height: 1.2;
}
</style>
