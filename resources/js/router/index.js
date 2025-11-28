import { createRouter, createWebHistory } from 'vue-router'

// Import components
import ProposalLayout from '../components/Proposal/Layout/ProposalLayout.vue'
import ProposalTasks from '../components/Proposal/ProposalTasks.vue'
import ProposalProjects from '../components/Proposal/ProposalProjects.vue'
import ProposalCalculator from '../components/Proposal/ProposalCalculator.vue'
import ProposalFinalize from '../components/Proposal/ProposalFinalize.vue'

// Import the Public View
import PublicProposalView from '../components/Proposal/PublicProposalView.vue'

const routes = [
	{
		path: '/view-proposal', 
		name: 'public.proposal', 
		component: PublicProposalView,
		meta: { layout: 'blank' }
	},
	{
		path: '/proposals/:id',
		component: ProposalLayout,
		props: true,
		children: [
		{ path: 'tasks', name: 'proposal.tasks', component: ProposalTasks },
		{ path: 'projects', name: 'proposal.projects', component: ProposalProjects },
		{ path: 'calculator', name: 'proposal.calculator', component: ProposalCalculator },
		{ path: 'finalize', name: 'proposal.finalize', component: ProposalFinalize },
		],
	}
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

export default router
