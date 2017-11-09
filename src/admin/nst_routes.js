/**
 * Ninja Split Tesiting Components
 */
const NstHome = require('./components/NstHome.vue');
const NstAddCampaign = require('./components/NstAddCampaign.vue');
const NstSettings = require('./components/NstSettings.vue');
const NstHelp = require('./components/NstHelp.vue');
const NstAllCampaign = require('./components/NstAllCampaign.vue');
const NstViewCampaignHome = require('./components/nstViewCampaignHome.vue');
const NstViewCampaignTestingPage = require('./components/NstViewCampaignTestingPage.vue');
const NstViewCampaignAnalyticsPage = require('./components/NstViewCampaignAnalytics.vue');
const NstViewCampaignSettingsPage = require('./components/NstViewCampaignSettings.vue');

/**
 * Exporting Routes
 */
export const routes = [
	{
		path: '/', 
		component: NstHome,
		props: true,
		children: [
			{
				path: '',
				name: 'nst_home',
				component: NstAllCampaign,
				props: true,
			},
			{
				path: '/add-campaign',
				name: 'nst_add_campaign',
				component: NstAddCampaign,
				props: true
			},
			{
				path: '/settings',
				name: 'nst_settings',
				component: NstSettings,
				props: true
			},
			{
				path: '/help',
				name: 'nst_help',
				component: NstHelp,
				props: true
			}
		]
	},
	{
		path: '/view-campaign/',
		component: NstViewCampaignHome,
		props: true,
		children: [
			{
				path: ':id/testing-pages',
				name: 'nst_view_testing_page',
				component: NstViewCampaignTestingPage,
				props: true
			},
			{
				path: ':id/analytics',
				name: 'nst_view_analytics_page',
				component: NstViewCampaignAnalyticsPage,
				props: true
			},
			{
				path: ':id/settings',
				name: 'nst_view_settings_page',
				component: NstViewCampaignSettingsPage,
				props: true
			},
		]
	}
	
];