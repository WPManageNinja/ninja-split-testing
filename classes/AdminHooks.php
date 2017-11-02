<?php namespace  NinjaABClass\classes;

class AdminHooks {

	public function __construct()
	{
	}

	public function adminMenu()
	{
		global $submenu;
		$capability = 'manage_options';
		add_menu_page( __('Ninja Split Testing', 'ninja-split-testing'),
			__('Ninja Split Testing', 'ninja-split-testing'),
			$capability,
			'ninja-split-testing',
			array($this, 'showMainPage')
		);
		if(current_user_can($capability)) {
			$submenu['ninja-split-testing'][] = array(
				__( 'All Campaigns', 'ninja-split-testing' ),
				$capability,
				'admin.php?page=ninja-split-testing#/'
			);
			$submenu['ninja-split-testing'][] = array(
				__( 'Add Campaigns', 'ninja-split-testing' ),
				$capability,
				'admin.php?page=ninja-split-testing#/add-campaign'
			);
			$submenu['ninja-split-testing'][] = array(
				__( 'Settings', 'ninja-split-testing' ),
				$capability,
				'admin.php?page=ninja-split-testing#/settings'
			);
			$submenu['ninja-split-testing'][] = array(
				__( 'Help', 'ninja-split-testing' ),
				$capability,
				'admin.php?page=ninja-split-testing#/help'
			);
		}
	}

	public function showMainPage()
	{
		$this->enqueue_nst_scripts();
		echo Helper::loadViewFile('show_main_page');

	}

	public function enqueue_nst_scripts()
	{
		$this->enqueue_scripts();
	}

	public function enqueue_scripts()
	{
		wp_enqueue_script(
			'ninja_split_testing',
			Helper::getAssetDirUrl().'js/ninja-split-testing.min.js',
			array( 'jquery' )
		);
	}

	/**
	 * Testing
	 */
	public function getMainPageData()
	{
		$data = [
			'posts' => ninjaDB('posts')->where('post_type', '=', 'post')->get()
		];
		wp_send_json_success($data, 200);
		exit;
	}
	
}