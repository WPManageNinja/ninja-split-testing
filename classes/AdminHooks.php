<?php namespace  NinjaABClass\classes;

class AdminHooks {

	public function __construct()
	{

	}

	public function adminMenu()
	{
		add_menu_page('Ninja Split Testing','Ninja Split Testing','manage_options','ninja-split-testing', array($this, 'showMainPage'));
		add_submenu_page('ninja-split-testing','All Campaigns','All Campaigns','manage_options','all-campaigns', array($this, 'showCampaignsPage'));
		add_submenu_page('ninja-split-testing','Add Campaign','Add Campaign','manage_options','add-campaign', array($this, 'showAddCampaign'));
		add_submenu_page('ninja-split-testing','Settings','Settings','manage_options','settings', array($this, 'showSettingsPage'));
		add_submenu_page('ninja-split-testing','Help','Help','manage_options','help', array($this, 'showHelpPage'));
	}

	public function showMainPage()
	{
		echo Helper::loadViewFile('show_main_page');
	}

	public function showCampaignsPage()
	{
		echo Helper::loadViewFile('show_campaigns_page');
	}

	public function showAddCampaign()
	{
		echo Helper::loadViewFile('show_campaigns_page');
	}

	public function showSettingsPage()
	{
		echo Helper::loadViewFile('show_settings_page');
	}

	public function showHelpPage()
	{
		echo Helper::loadViewFile('show_help_page');
	}
	
}