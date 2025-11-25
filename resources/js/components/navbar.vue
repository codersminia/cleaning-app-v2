<template>
  <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #475569;">
    <div class="container-fluid">
      <!-- Logo and HOME text -->
      <a class="navbar-brand d-flex align-items-center gap-2" href="/dashboard">
        <span style="font-size: 18px; font-weight: bold; color: white;">HOME</span>
      </a>

      <!-- Navbar toggler for mobile -->
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- Navbar items -->
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto d-flex gap-3">
          <!-- Chat Button -->
          <li class="nav-item">
            <button class="btn btn-outline-info" style="border-color: #14b8a6; color: #14b8a6; font-weight: 500;">
              Chat
            </button>
          </li>

          <!-- Help Dropdown -->
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="helpDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color: white; font-weight: 500;">
              Help
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="helpDropdown" style="background-color: #334155;">
              <li><a class="dropdown-item" href="#" style="color: white;">Documentation</a></li>
              <li><a class="dropdown-item" href="#" style="color: white;">Support</a></li>
              <li><a class="dropdown-item" href="#" style="color: white;">FAQ</a></li>
            </ul>
          </li>

          <!-- Menu Dropdown -->
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="menuDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color: white; font-weight: 500;">
              Menu
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="menuDropdown" style="background-color: #334155;">
              <li><a class="dropdown-item" href="#" style="color: white;">Profile</a></li>
              <li><a class="dropdown-item" href="#" style="color: white;">Settings</a></li>
            <li><a class="dropdown-item" @click.prevent="logout" href="#" style="color: white;">Logout</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</template>

<script setup>
import axios from "axios";
axios.defaults.withCredentials = true;

const logout = async () => {
    try {
        // 1. Call the API to destroy the session on the server
        await axios.post('/api/logout');

        // 2. Force a hard reload to the login page.
        // This clears the browser's memory of the dashboard.
        window.location.href = '/'; 
    } catch (error) {
        console.error("Logout failed or session already expired", error);
        // If the API fails (e.g., internet down), force redirect anyway
        window.location.href = '/';
    }
  }
</script>

<style scoped>
.navbar {
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.dropdown-item:hover {
  background-color: #475569 !important;
  color: #14b8a6 !important;
}

.btn-outline-info:hover {
  background-color: #14b8a6;
  border-color: #14b8a6;
  color: white !important;
}
</style>
