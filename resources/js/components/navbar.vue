<template>
    <nav
        class="navbar navbar-expand-lg navbar-dark sticky-top shadow-sm"
        style="background-color: #475569"
    >
        <div class="container">
            <!-- Logo and HOME text -->
            <a
                class="navbar-brand d-flex align-items-center gap-2"
                href="/dashboard"
            >
                <img
                    src="/images/logo.png"
                    alt="Logo"
                    width="30"
                    height="30"
                    class="d-inline-block align-top"
                />
                <span style="font-size: 1.1rem; font-weight: bold; color: white"
                    >HOME</span
                >
            </a>

            <!-- Navbar toggler for mobile -->
            <button
                class="navbar-toggler border-0"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarNav"
                aria-controls="navbarNav"
                aria-expanded="false"
                aria-label="Toggle navigation"
            >
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navbar items -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    <li class="nav-item">
                        <a
                            class="nav-link px-lg-3"
                            href="/dashboard"
                            :class="{ active: $route.path === '/dashboard' }"
                            >Dashboard</a
                        >
                    </li>

                    <!-- Menu Dropdown -->
                    <li class="nav-item dropdown">
                        <a
                            class="nav-link dropdown-toggle px-lg-3"
                            href="#"
                            id="menuDropdown"
                            role="button"
                            data-bs-toggle="dropdown"
                            aria-expanded="false"
                            style="color: white; font-weight: 500"
                        >
                            Menu
                        </a>
                        <ul
                            class="dropdown-menu dropdown-menu-end border-0 shadow"
                            aria-labelledby="menuDropdown"
                            style="background-color: #334155"
                        >
                            <li>
                                <a
                                    class="dropdown-item py-2"
                                    @click.prevent="logout"
                                    href="#"
                                    style="color: white"
                                    >Logout</a
                                >
                            </li>
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
        await axios.post("/api/logout");

        // 2. Force a hard reload to the login page.
        // This clears the browser's memory of the dashboard.
        window.location.href = "/login";
    } catch (error) {
        console.error("Logout failed or session already expired", error);
        // If the API fails (e.g., internet down), force redirect anyway
        window.location.href = "/";
    }
};
</script>

<style scoped>
.navbar {
    transition: all 0.3s ease;
}

.nav-link {
    color: rgba(255, 255, 255, 0.8) !important;
    font-weight: 500;
    transition: color 0.2s;
}

.nav-link:hover,
.nav-link.active {
    color: #14b8a6 !important;
}

.dropdown-item {
    transition: all 0.2s;
}

.dropdown-item:hover {
    background-color: #475569 !important;
    color: #14b8a6 !important;
}

@media (max-width: 991.98px) {
    .navbar-collapse {
        background-color: #334155;
        margin: 0 -1rem;
        padding: 1rem;
        border-radius: 0 0 0.5rem 0.5rem;
        margin-top: 0.5rem;
    }

    .nav-link {
        padding: 0.75rem 0 !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }

    .nav-item:last-child .nav-link {
        border-bottom: none;
    }
}
</style>
