<template>
  <div v-if="isOpen" class="modal-overlay">
    <!-- Backdrop -->
    <div class="modal-backdrop"></div>

    <!-- Modal Container -->
    <div class="modal-container">
      <!-- Header -->
      <div class="modal-header-custom">
        <span class="header-text">Fill in client information below:</span>
        <button @click="closeModal" class="btn-close-custom">×</button>
      </div>

      <!-- Content -->
      <div class="modal-body-custom">
        <h5 class="text-center mb-4 fw-bold">Add Prospect</h5>

        <form @submit.prevent="submitForm">
          <div class="row g-4">
            <!-- Left Column -->
            <div class="col-md-6">
              <!-- Company Name -->
              <div class="form-group-custom">
                <label class="required-label">Client's Company Name/ Property Owner*</label>
                <div class="input-container">
                  <input
                    v-model="form.companyName"
                    type="text"
                    class="form-control-custom"
                    placeholder="Example: ABC Company LLC or John Doe Residence"
                    maxlength="50"
                  />
                  <span v-if="form.companyName" class="checkmark">✓</span>
                </div>
                <small class="char-count">{{ form.companyName.length }} / 50</small>
              </div>

              <!-- Contact -->
              <div class="form-group-custom">
                <label>Who is your contact for this account?</label>
                <div class="input-container">
                  <input v-model="form.contactName" type="text" class="form-control-custom" maxlength="50" />
                  <span v-if="form.contactName" class="checkmark">✓</span>
                </div>
                <small class="char-count">{{ form.contactName.length }} / 50</small>
              </div>

              <!-- Email -->
              <div class="form-group-custom">
                <label>Email</label>
                <div class="input-container">
                  <input v-model="form.email" type="email" class="form-control-custom" maxlength="50" />
                  <span v-if="form.email" class="checkmark">✓</span>
                </div>
                <small class="char-count">{{ form.email.length }} / 50</small>
              </div>

              <!-- Mobile -->
              <div class="form-group-custom">
                <label>Mobile Number</label>
                <div class="d-flex align-items-center gap-2">
                  <select class="form-select-custom" style="max-width: 90px;">
                    <option>US +1</option>
                  </select>
                  <input
                    v-model="form.mobileNumber"
                    type="tel"
                    class="form-control-custom flex-grow-1"
                    placeholder="000-000-0000"
                  />
                </div>
              </div>

              <!-- Unit -->
              <div class="form-group-custom">
                <label>Unit Number</label>
                <div class="input-container">
                  <input v-model="form.unitNumber" type="text" class="form-control-custom" maxlength="50" />
                  <span v-if="form.unitNumber" class="checkmark">✓</span>
                </div>
                <small class="char-count">{{ form.unitNumber.length }} / 50</small>
              </div>

              <!-- State -->
              <div class="form-group-custom">
                <label class="required-label">State*</label>
                <div class="input-container">
                  <input v-model="form.state" type="text" class="form-control-custom" maxlength="50" />
                  <span v-if="form.state" class="checkmark">✓</span>
                </div>
                <small class="char-count">{{ form.state.length }} / 50</small>
              </div>
            </div>

            <!-- Right Column -->
            <div class="col-md-6">
              <!-- Website -->
              <div class="form-group-custom">
                <label class="required-label">Website</label>
                <div class="input-container">
                  <input v-model="form.website" type="url" class="form-control-custom" maxlength="50" />
                  <span v-if="form.website" class="checkmark">✓</span>
                </div>
                <small class="char-count">{{ form.website.length }} / 50</small>
              </div>

              <!-- Square Ft -->
              <div class="form-group-custom">
                <label>Square ft</label>
                <div class="input-container">
                  <input v-model="form.squareFt" type="number" class="form-control-custom" maxlength="50" />
                  <span v-if="form.squareFt" class="checkmark">✓</span>
                </div>
                <small class="char-count">{{ String(form.squareFt).length }} / 50</small>
              </div>

              <!-- Phone -->
              <div class="form-group-custom">
                <label>Phone</label>
                <div class="d-flex align-items-center gap-2">
                  <select class="form-select-custom" style="max-width: 90px;">
                    <option>US +1</option>
                  </select>
                  <input
                    v-model="form.phone"
                    type="tel"
                    class="form-control-custom flex-grow-1"
                    placeholder="000-000-0000"
                  />
                  <input v-model="form.phoneExt" type="text" class="form-control-custom" placeholder="Ext" style="max-width: 70px;" />
                </div>
              </div>

              <!-- Address -->
              <div class="form-group-custom">
                <label class="required-label">Address*</label>
                <div class="input-container">
                  <input v-model="form.address" type="text" class="form-control-custom" maxlength="100" />
                  <span v-if="form.address" class="checkmark">✓</span>
                </div>
                <small class="char-count">{{ form.address.length }} / 100</small>
              </div>

              <!-- City -->
              <div class="form-group-custom">
                <label class="required-label">City*</label>
                <div class="input-container">
                  <input v-model="form.city" type="text" class="form-control-custom" maxlength="50" />
                  <span v-if="form.city" class="checkmark">✓</span>
                </div>
                <small class="char-count">{{ form.city.length }} / 50</small>
              </div>

              <!-- Zip -->
              <div class="form-group-custom">
                <label class="required-label">Zip*</label>
                <div class="input-container">
                  <input v-model="form.zip" type="text" class="form-control-custom" maxlength="10" />
                  <span v-if="form.zip" class="checkmark">✓</span>
                </div>
                <small class="char-count">{{ form.zip.length }} / 10</small>
              </div>
            </div>
          </div>

          <!-- Buttons -->
          <div class="d-flex gap-3 mt-4">
            <button type="submit" class="btn btn-info text-white fw-bold px-4">NEXT</button>
            <button type="button" @click="closeModal" class="btn btn-link text-muted fw-bold text-decoration-none">CANCEL</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div
    v-if="isLoading"
    class="position-fixed top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center bg-white bg-opacity-75"
    style="z-index: 2000;"
    >
    <div class="text-center">
        <div class="spinner-border text-info" role="status" style="width: 3rem; height: 3rem;"></div>
    </div>
    </div>

</template>

<script>
export default {
  props: {
    isOpen: { type: Boolean, default: false }
  },
  data() {
    return {
      isLoading: false,
      form: {
        companyName: '',
        contactName: '',
        email: '',
        mobileNumber: '',
        unitNumber: '',
        state: '',
        website: '',
        squareFt: '',
        phone: '',
        phoneExt: '',
        address: '',
        city: '',
        zip: ''
      }
    }
  },
  watch: {
    isOpen(val) {
      if (!val) this.resetForm()
    }
  },
  methods: {
    closeModal() {
      this.$emit('close')
    },
    resetForm() {
      Object.keys(this.form).forEach(k => this.form[k] = '')
    },
    submitForm() {
        if (!this.form.companyName || !this.form.state || !this.form.address || !this.form.city || !this.form.zip) {
            alert('Please fill in all required fields')
            return
        }

        this.isLoading = true; // start loader

        // Prepare the data to match Laravel field names
        const payload = {
            company_name: this.form.companyName,
            website: this.form.website,
            contact_person: this.form.contactName,
            area_in_sqft: this.form.squareFt,
            email: this.form.email,
            phone: this.form.phone,
            ext: this.form.phoneExt,
            mobile: this.form.mobileNumber,
            address: this.form.address,
            unit_number: this.form.unitNumber,
            city: this.form.city,
            state: this.form.state,
            zip: this.form.zip,
        }

        axios.post('/api/saveprospect', payload)
            .then(response => {
                const prospectId = response.data.id
                this.resetForm();

                // Redirect to location page
                this.isLoading = true;
                setTimeout(() => {
                    window.location.href = `/location/${prospectId}`;
                }, 1000);
            })
            .catch(error => {
                console.error(error)
                alert('Failed to save prospect. Please check the console for details.')
            })
        }
    }
}
</script>

<style scoped>
/* ===== Base Layout ===== */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: 1050;
}
.modal-backdrop {
  position: fixed;
  width: 100%;
  height: 100%;
  background: rgba(0,0,0,0.5);
  z-index: 1040;
}
.modal-container {
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  background: #fff;
  border-radius: 10px;
  width: 90%;
  max-width: 900px;
  max-height: 90vh;
  overflow-y: auto;
  z-index: 1051;
}

/* ===== Header ===== */
.modal-header-custom {
  background-color: #00b2a9;
  color: white;
  font-weight: bold;
  font-size: 15px;
  padding: 10px 16px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.btn-close-custom {
  background: none;
  border: none;
  color: white;
  font-size: 26px;
  line-height: 1;
  cursor: pointer;
}

/* ===== Body ===== */
.modal-body-custom {
  padding: 40px 50px;
}

/* ===== Form Styles ===== */
.form-group-custom {
  margin-bottom: 28px;
}
.form-control-custom,
.form-select-custom {
  width: 100%;
  border: none;
  border-bottom: 1px solid #ddd;
  border-radius: 0;
  outline: none;
  font-size: 14px;
  padding: 4px 2px;
}
.form-control-custom:focus {
  border-color: #00b2a9;
  box-shadow: none;
}
.required-label {
  color: #d946a6;
  font-weight: 600;
  font-size: 13px;
}
label {
  font-size: 13px;
  color: #777;
  display: block;
  margin-bottom: 5px;
}
.checkmark {
  color: #00b2a9;
  font-size: 18px;
  font-weight: bold;
}
.input-container {
  display: flex;
  align-items: center;
  gap: 6px;
}
.char-count {
  font-size: 11px;
  color: #aaa;
  margin-top: 4px;
  display: block;
}
</style>
