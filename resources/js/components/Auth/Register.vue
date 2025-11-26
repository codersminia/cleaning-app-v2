<template>
  <div class="login-container">
    <div class="logo">
      <img src="/images/logo.png" alt="Logo" />
    </div>

    <div class="login-card">
      <h2>Create Account</h2>
      <p class="subtitle">Sign up for a new account</p>

      <!-- Name Input -->
      <div class="input-group">
        <input type="text" v-model="form.name" placeholder="Full Name" required />
        <span class="icon">👤</span>
        <span v-if="errors.name" class="field-error">{{ errors.name[0] }}</span>
      </div>

      <!-- Email Input -->
      <div class="input-group">
        <input type="email" v-model="form.email" placeholder="Email" required />
        <span class="icon">📧</span>
        <span v-if="errors.email" class="field-error">{{ errors.email[0] }}</span>
      </div>

      <!-- Password Input -->
      <div class="input-group">
        <input type="password" v-model="form.password" placeholder="Password" required />
        <span class="icon">🔒</span>
        <span v-if="errors.password" class="field-error">{{ errors.password[0] }}</span>
      </div>

      <!-- Confirm Password Input -->
      <div class="input-group">
        <input type="password" v-model="form.password_confirmation" placeholder="Confirm Password" required />
        <span class="icon">🔒</span>
      </div>

      <!-- Register Button -->
      <button class="login-btn" @click.prevent="handleSubmit" :disabled="isLoading">
        <span class="btn-icon">✨</span> {{ isLoading ? 'Creating...' : 'Register' }}
      </button>

      <!-- Link back to Login -->
      <div class="mt-3">
        <p class="text-sm text-gray-600">
          Already have an account? 
          <a href="/login" class="login-link">Login here</a>
        </p>
      </div>

      <!-- Global Error Message -->
      <p v-if="errorMessage" class="error-message">{{ errorMessage }}</p>
    </div>
  </div>
</template>

<script>
import axios from "axios";

// Ensure cookies are sent
axios.defaults.withCredentials = true;

export default {
  name: "RegisterComponent",
  data() {
    return {
      form: {
        name: "",
        email: "",
        password: "",
        password_confirmation: "",
      },
      errors: {}, // To store field-specific errors
      errorMessage: "",
      isLoading: false,
    };
  },
  methods: {
    async handleSubmit() {
      this.errors = {};
      this.errorMessage = "";
      this.isLoading = true;

      try {
        // 1. Get CSRF Cookie
        await axios.get("/sanctum/csrf-cookie");

        // 2. Post to Register API
        const response = await axios.post("/api/register", this.form);

        // 3. Redirect on success
        if (response.data.redirect) {
            window.location.href = response.data.redirect;
        } else {
            window.location.href = "/dashboard";
        }

      } catch (error) {
        if (error.response && error.response.status === 422) {
          // Validation Errors (e.g. email taken, passwords don't match)
          this.errors = error.response.data.errors;
        } else {
          this.errorMessage = "Something went wrong. Please try again.";
          console.error(error);
        }
      } finally {
        this.isLoading = false;
      }
    },
  },
};
</script>

<style scoped>
/* Reusing your existing Login styles */
.login-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  height: 100vh;
  background: #fff;
}

.logo img {
  width: 80px;
  margin-bottom: 20px;
}

.login-card {
  background: #fff;
  padding: 40px;
  box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.1);
  width: 400px;
  max-width: 100%;
  text-align: center;
  border-radius: 10px;
}

.login-card h2 {
  margin-bottom: 15px;
  font-size: 24px;
  font-weight: bold;
}

.subtitle {
  color: gray;
  margin-bottom: 25px;
  font-size: 14px;
}

.input-group {
  position: relative;
  margin-bottom: 20px;
  text-align: left;
}

.input-group input {
  width: 100%;
  padding: 12px 35px 12px 10px;
  border: none;
  border-bottom: 1px solid #ccc;
  outline: none;
  font-size: 14px;
}

.input-group .icon {
  position: absolute;
  right: 10px;
  top: 12px; /* Adjusted for alignment */
  font-size: 16px;
  color: #888;
}

/* Field specific error text */
.field-error {
    color: red;
    font-size: 12px;
    margin-top: 4px;
    display: block;
}

.login-btn {
  width: 100%;
  padding: 12px;
  background: linear-gradient(90deg, #20b2aa, #24e09d);
  color: white;
  border: none;
  border-radius: 6px;
  font-size: 16px;
  font-weight: bold;
  cursor: pointer;
  display: flex;
  justify-content: center;
  align-items: center;
}

.login-btn:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

.error-message {
  color: red;
  margin-top: 10px;
  font-size: 14px;
}

.mt-3 {
    margin-top: 1rem;
}

.login-link {
    color: #20b2aa;
    text-decoration: none;
    font-weight: bold;
}

.login-link:hover {
    text-decoration: underline;
}

button:focus {
  outline: 0 !important;
}
</style>