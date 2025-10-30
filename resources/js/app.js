
import './bootstrap';
import { createApp } from 'vue';

const app = createApp({});

// Register global components
app.component('example-component', require('./components/ExampleComponent.vue').default);
app.component('login-component', require('./components/Auth/Login.vue').default);
app.component('dashboard-component', require('./components/Dashboard.vue').default);
app.component('navbar-component', require('./components/navbar.vue').default);
app.component('prospect-component', require('./components/Prospects.vue').default);
app.component('prospect-modal', require('./components/Modals/ProspectModal.vue').default);
app.component('add-prospect-modal', require('./components/Modals/AddProspectModal.vue').default);
app.component('location-component', require('./components/location.vue').default);
app.component('add-proposal-modal', require('./components/Modals/AddProposalModal.vue').default);
app.component('proposal-tasks', require('./components/ProposalTasks.vue').default);

// Mount Vue app
app.mount('#app');

