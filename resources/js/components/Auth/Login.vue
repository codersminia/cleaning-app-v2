<template>
  <div class="login-container">
    <!-- Logo -->
    <div class="logo">
      <img
        src="/images/logo.png"
        alt="Logo"
      />
    </div>

    <!-- Login Card -->
    <div class="login-card">
      <h2>Sign in</h2>
      <p class="subtitle">Enter your email and password</p>

      <!-- Email Input -->
      <div class="input-group">
        <input
          type="email"
          v-model="email"
          placeholder="Email"
          required
        />
        <span class="icon">📧</span>
      </div>

      <!-- Password Input -->
      <div class="input-group">
        <input
          type="password"
          v-model="password"
          placeholder="Password"
          required
        />
        <span class="icon">🔒</span>
      </div>

      <!-- Forgot Password -->
      <!-- <div class="forgot">
        <a href="#">FORGOT YOUR PASSWORD?</a>
      </div> -->

      <!-- Login Button -->
      <button class="login-btn" @click.prevent="handleSubmit">
        <span class="btn-icon">👤</span> Login
      </button>

      <div class="mt-3" style="margin-top: 20px;">
        <p style="font-size: 14px; color: gray;">
          Don't have an account? 
          <a href="/register" style="color: #20b2aa; text-decoration: none; font-weight: bold;">
            Register here
          </a>
        </p>
      </div>

      <!-- Message -->
      <p v-if="errorMessage" class="error-message">{{ errorMessage }}</p>
    </div>
  </div>
</template>

<script>
import axios from "axios";
axios.defaults.withCredentials = true; 
export default {
  name: "LoginComponent",

  data() {
    return {
      email: "",
      password: "",
      errorMessage: "",
    };
  },

  methods: {
async handleSubmit() {
      this.errorMessage = "";

      try {
        // 👇 THIS IS REQUIRED. 
        // It sets the XSRF-TOKEN cookie in your browser.
        await axios.get('/sanctum/csrf-cookie');
        
        // NOW perform the login
        const payload = {
            data: {
                attributes: {
                    email: this.email,
                    password: this.password
                }
            }
        };

        const response = await axios.post("/api/login", payload);

        // Redirect
        window.location.href = "/dashboard";

      } catch (error) {
        console.error(error);
        if (error.response && error.response.status === 419) {
            this.errorMessage = "Security token expired. Please refresh the page.";
        } else if (error.response && error.response.status === 401) {
          this.errorMessage = "Invalid email or password.";
        } else {
          this.errorMessage = "An unexpected error occurred.";
        }
      }
    },
  },
};
</script>

<style scoped>
/* Center Page */
.login-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  height: 100vh;
  background: #fff;
}

/* Logo */
.logo img {
  width: 80px;
  margin-bottom: 20px;
}

/* Card */
.login-card {
  background: #fff;
  padding: 40px;
  box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.1);
  width: 400px;
  max-width: 100%;
  text-align: center;
  border-radius: 10px;
}

/* Title */
.login-card h2 {
  margin-bottom: 15px;
  font-size: 24px;
  font-weight: bold;
}

/* Subtitle */
.subtitle {
  color: gray;
  margin-bottom: 25px;
  font-size: 14px;
}

/* Input Group */
.input-group {
  position: relative;
  margin-bottom: 20px;
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
  top: 50%;
  transform: translateY(-50%);
  font-size: 16px;
  color: #888;
}

/* Forgot Password */
.forgot {
  margin-bottom: 20px;
  text-align: right;
}

.forgot a {
  text-decoration: none;
  font-size: 13px;
  color: gray;
}

/* Button */
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

.login-btn .btn-icon {
  margin-right: 8px;
}

/* Error Message */
.error-message {
  color: red;
  margin-top: 10px;
  font-size: 14px;
}

button:focus {
  outline: 0 !important;
}
</style>
