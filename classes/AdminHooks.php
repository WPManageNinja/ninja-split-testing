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
		$this->enqueue_styles();
	}

	public function enqueue_scripts()
	{
		wp_enqueue_script(
			'ninja_split_testing',
			Helper::getAssetDirUrl().'js/ninja-split-testing.min.js',
			array( 'jquery' )
		);
	}

	public function enqueue_styles()
	{
		wp_enqueue_style(
			'ninja_split_testing',
			Helper::getAssetDirUrl().'css/ninja-split-testing.min.css'
		);
	}


	public function ajax_routes() {
		if ( ! current_user_can( 'manage_options') ) {
			return;
		}

		$valid_routes = array(
			'add-campaign'  				=> 'addCampaign',
			'get-all-campaign'  			=> 'getAllCampaign',
			'get-campaign-by-id' 			=> 'getCampaignByID',
			'add-campaign-post-id'			=> 'addCampaignPostID',
			'get-all-post-and-pages'		=> 'getAllPostAndPages',
			'get-all-post-types'			=> 'getAllPostTypes',
			'store-campaign-testing-page' 	=> 'storeCampaignTestingPage',
			'get-all-testing-page' 			=> 'getAllTestingPage',
			'update-testing-page-status'	=> 'updateTestingPageStatus'
		);

		$requested_route = $_REQUEST['target_action'];

		if ( isset( $valid_routes[ $requested_route ] ) ) {
			$this->{$valid_routes[ $requested_route ]}();
		}

		wp_die();
	}


	public function addCampaign() {

		if ( ! $_REQUEST['title'] ) {
			wp_send_json_error( array(
				'message' => __( 'The title field is required.', 'ninja-split-testing' )
			), 423 );
			die();
		}

		$title = sanitize_text_field($_REQUEST['title']);

		if (! $title) {
			wp_send_json_error( array(
				'message' => __( 'Please give text only', 'ninja-split-testing' )
			), 423 );
			die();
		}

		$data = array(
		    'id' => isset($_REQUEST['id']) ? $_REQUEST['id'] : NULL,
		    'title' => $title,
		    'description' => wp_kses_post($_REQUEST['description'])
		);

		Queries::store('nst_campaigns', $data);
	}

	public function addCampaignPostID() {
		if( !$_REQUEST['post_id']) {
			wp_send_json_error( array(
				'message' => __( 'Please Select Any Post or Page', 'ninja-split-testing')
			), 423);
			die();
		}

		$data = [
			'id' => (int) $_REQUEST['id'],
			'post_id' => (int) $_REQUEST['post_id']
		];

		Queries::storePostID('nst_campaigns', $data);
	}

	public function getAllCampaign() {
		Queries::getAllData('nst_campaigns');
	}

	public function getCampaignByID() {
		Queries::getSingleData('nst_campaigns', $_REQUEST['campaign_id']);
	}

	public function getAllPostAndPages() {
		
		$args = [
			'post_type' => $_REQUEST['post_type']
		];

		$data = get_posts($args);

		wp_send_json_success($data, 200);
	}

	public function getAllPostTypes() {

		$args = ['public' => true];
		$data = ['post_types' => get_post_types($args)];

		wp_send_json_success($data, 200);
	}

	public function storeCampaignTestingPage() {

		if ( ! $_REQUEST['data']['title']       || 
			 ! $_REQUEST['data']['target_post_id'] || 
			 ! $_REQUEST['data']['traffic_split_amount']
			) 
		{
			wp_send_json_error( array(
				'message' => __( 'All fields are required.', 'ninja-split-testing' )
			), 423 );
			die();
		} 

		$title = sanitize_text_field($_REQUEST['data']['title']);

		if (! $title) {
			wp_send_json_error( array(
				'message' => __( 'Please give text only', 'ninja-split-testing' )
			), 423 );
			die();
		}

		Queries::storeTestingPage('nst_campaign_urls', $_REQUEST['data']);
	}

	public function getAllTestingPage() {
		Queries::getAllTestingPage('nst_campaign_urls', $_REQUEST['campaign_id']);
	}

	public function updateTestingPageStatus() {
		Queries::updateTestingPageStatus('nst_campaign_urls', $_REQUEST['update_status']);
	}
}