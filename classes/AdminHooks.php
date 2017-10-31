<?php namespace  NinjaABClass\classes;

class AdminHooks {

	public function __construct()
	{

	}

	public function adminMenu()
	{
		add_menu_page('Ninja Split Testing','Ninja Split Testing','manage_options','ninja-split-testing', array($this, 'showMainPage'));
		add_submenu_page('ninja-split-testing','All Campaigns','All Campaigns','manage_options','all-campaigns', array($this, 'showCampaignsPage'));
		add_submenu_page('ninja-split-testing','Add Campaign','Add Campaign','manage_options','add-campaign', array($this, 'showCampaignsPage'));
		add_submenu_page('ninja-split-testing','Settings','Settings','manage_options','settings', array($this, 'showSettingsPage'));
		add_submenu_page('ninja-split-testing','Help','Help','manage_options','help', array($this, 'showHelpPage'));
		remove_submenu_page( 'ninja-split-testing', 'ninja-split-testing' );
	}

	public function showMainPage()
	{
		echo Helper::loadViewFile('show_main_page');
	}


	/**
	* This method is for testing purpose only
	**/

	public function showCampaignsPage()
	{
		$data = ninjaDB('posts')->where('post_type','=', 'post')->get();
		echo Helper::loadViewFile('show_campaigns_page', $data);
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