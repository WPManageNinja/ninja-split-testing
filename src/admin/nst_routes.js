/**
 * Ninja Split Tesiting Components
 */
const NstHome = require('./components/NstHome.vue');
const NstAddCampaign = require('./components/NstAddCampaign.vue');
const NstSettings = require('./components/NstSettings.vue');
const NstHelp = require('./components/NstHelp.vue');
const NstAllCampaign = require('./components/NstAllCampaign.vue');

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
				path: '/',
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
	
];