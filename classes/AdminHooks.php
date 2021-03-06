<?php namespace  NinjaABClass\classes;

class AdminHooks {
	
	/**
	 * Adding admin menu for the WordPress Plugin
	 * 
	 * @return void
	 */
	public function adminMenu()
	{
		global $submenu;
		$capability = 'manage_options';
		$capability = apply_filters('nst_campaign_management_permission', $capability);
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
				'admin.php?page=ninja-split-testing#/?campaign=true'
			);
			$submenu['ninja-split-testing'][] = array(
				__( 'Help', 'ninja-split-testing' ),
				$capability,
				'admin.php?page=ninja-split-testing#/help'
			);
		}
	}


	/**
	 * Showing main page of WordPress Plugin
	 * 
	 * @return void
	 */
	public function showMainPage()
	{
		$this->enqueue_nst_assets();
		echo Helper::loadViewFile('show_main_page');
	}


	/**
	 * Adding all types of assests(e.g css,js) into WordPress Plugin
	 * 
	 * @return void
	 */
	public function enqueue_nst_assets()
	{
		wp_enqueue_script(
			'ninja_split_testing',
			Helper::getAssetDirUrl().'js/ninja-split-testing.min.js',
			array( 'jquery' )
		);
		wp_localize_script('ninja_split_testing', 'ninja_split_testing_admin', array(
			'ajaxurl' => admin_url('admin-ajax.php'),
			'_link_nonce' => wp_create_nonce( 'internal-linking' )
		));
		wp_enqueue_style(
			'ninja_split_testing',
			Helper::getAssetDirUrl().'css/ninja-split-testing.min.css'
		);
	}


	/**
	 * Declaring ajax routes with target actions
	 * 
	 * @return void
	 */
	public function ajax_routes() 
	{
		$capability = 'manage_options';
		$capability = apply_filters('nst_campaign_management_permission', $capability);
		if ( ! current_user_can( $capability ) ) {
			return;
		}
		
		$valid_routes = array(
			'add-campaign'  				=> 'addCampaign',
			'update-campaign'				=> 'updateCampaign',
			'get-all-campaign'  			=> 'getAllCampaign',
			'get-campaign-by-id' 			=> 'getCampaignByID',
			'add-campaign-post-id'			=> 'addCampaignPostID',
			'store-campaign-testing-page' 	=> 'createNewTestPage',
			'get-all-testing-page' 			=> 'getAllTestingPage',
			'update-testing-page-status'	=> 'updateTestingPageStatus',
			'update-campaign-status'		=> 'updateCampaignStatus',
			'update-testing-page'	        => 'updateTestingPage',
			'delete-campaign-by-id'			=> 'deleteCampaignByID',
			'delete-testing-page-by-id'		=> 'deleteTestingPageByID',
			'get-campaign-analytics-data'	=> 'getCampaignAnalyticsData',
			'get_analytics' => 'getAnalytics'
		);

		$requested_route = $_REQUEST['target_action'];

		if ( isset( $valid_routes[ $requested_route ] ) ) {
			$this->{$valid_routes[ $requested_route ]}();
		}

		wp_die();
	}

	
	/**
	 * Adding a new Campaign
	 * 
	 * @return void
	 */
	public function addCampaign() 
	{
		if( !$_REQUEST['title']) {
			$this->responseError( __( 'Please provide title', 'ninja-split-testing') );
		}
		
		if( !$_REQUEST['post_id']) {
			$this->responseError( __( 'Please Select Any Post or Page', 'ninja-split-testing') );
		}
		
		$campaign_data = array(
			'id' => intval($_REQUEST['id']),
			'post_id' => intval( $_REQUEST['post_id'] ),
			'target_url' => sanitize_text_field($_REQUEST['permalink']),
			'title' => sanitize_text_field($_REQUEST['title']),
			'created_by' => get_current_user_id(),
			'cookie_key_prefix' => 'nst_campaign'
		);

		$campaign_data = apply_filters('nst_create_new_campaign', $campaign_data);
		
		$campaignId = NstQueries::insert(
			Helper::getDbTableName('campaigns'), 
			$campaign_data
		);
		
		do_action('nst_new_campaign_added', $campaignId, $campaign_data);
		
		wp_send_json_success(array(
			'id' => $campaignId,
			'message' => __('Campaign was added successfully', 'ninja-split-testing')
		), 200);
	}

	
	/**
	 * Updating an existing Campaign
	 * 
	 * @return void
	 */
	public function updateCampaign()
	{
		if( !$_REQUEST['title']) {
			$this->responseError( __( 'Please provide title', 'ninja-split-testing') );
		}
		
		if( !$_REQUEST['post_id']) {
			$this->responseError( __( 'Please Select Any Post or Page', 'ninja-split-testing') );
		}
		
		$campaign_data = array(
			'post_id' => intval( $_REQUEST['post_id'] ),
			'target_url' => sanitize_text_field($_REQUEST['permalink']),
			'title' => sanitize_text_field($_REQUEST['title']),
		);

		$campaign_id = intval($_REQUEST['id']);
		
		$campaign_data = apply_filters('nst_update_campaign_data', $campaign_data, $campaign_id);
		
		$result = NstQueries::update(
			Helper::getDbTableName('campaigns'), 
			$campaign_data, 
			$campaign_id
		);
		
		do_action('nst_campaign_updated', $campaign_id, $campaign_data);
		
		wp_send_json_success(array(
			'message' => __('Campaign updated successfully', 'ninja-split-testing')
		), 200);
	}


	/**
	 * Deleting a Campaign by its ID
	 * 
	 * @return void
	 */
	public function deleteCampaignByID()
	{
		$campaign_id = intval($_REQUEST['id']);
		do_action('nst_before_campaign_delete', $campaign_id);
		NstQueries::delete('nst_campaigns', $campaign_id);
		do_action('nst_after_campaign_deleted', $campaign_id);

		wp_send_json_success(array(
			'message' => __('Campaign deleted successfully', 'ninja-split-testing')
		), 200);
	}


	/**
	 * Deleting a testing page by it ID
	 * 
	 * @return void
	 */
	public function deleteTestingPageByID()
	{
		$page_id = intval($_REQUEST['id']);

		NstQueries::delete(
			Helper::getDbTableName('urls'), 
			$page_id
		);

		wp_send_json_success(array(
			'message' => __('Testing Page deleted successfully', 'ninja-split-testing')
		),200);
	}


	/**
	 * Getting all campaigns from database
	 * 
	 * @return void
	 */
	public function getAllCampaign() 
	{
		$campaigns = NstQueries::getAll(
			Helper::getDbTableName('campaigns')
		);

		$campaigns = apply_filters('nst_all_campaigns', $campaigns);

		wp_send_json_success(array(
			'campaign' => $campaigns
		), 200);
	}

	
	/**
	 * Getting a Campaign by it's ID
	 * 
	 * @return void
	 */
	public function getCampaignByID() 
	{
		$campaign = NstQueries::find(
			Helper::getDbTableName('campaigns'), 
			intval($_REQUEST['campaign_id'])
		);

		$campaign = apply_filters('nst_get_single_campaign', $campaign);
		
		wp_send_json_success($campaign, 200);
	}

	
	/**
	 * Creating a testing page of a Campaign
	 * 
	 * @return void
	 */
	public function createNewTestPage() 
	{
		if ( ! $_REQUEST['title'] || 
			 ! $_REQUEST['target_url'] ||
			 ! $_REQUEST['traffic_split_amount'] ||
			 ! strlen(sanitize_text_field($_REQUEST['title']))
			) 
		{
			$this->responseError(__( 'Please provide all the required data', 'ninja-split-testing' ));
		}

		$campaign_id = intval($_REQUEST['campaign_id']);
		$page_data = array(
			'title' => sanitize_text_field($_REQUEST['title']),
			'target_post_id' => intval($_REQUEST['target_post_id']),
            'target_url' => sanitize_text_field($_REQUEST['target_url']),
            'traffic_split_amount' => intval($_REQUEST['traffic_split_amount']),
			'campaign_id' => $campaign_id,
			'user_id' => get_current_user_id(),
			'status' => 'active'
		);
		
		$page_data = apply_filters('nst_insert_campaign_page', $page_data );
		
		$pageId = NstQueries::insert(
			Helper::getDbTableName('urls'), 
			$page_data
		);
		
		do_action('nst_created_new_campaign_page', $campaign_id, $pageId );
		
		$campaign_page = NstQueries::find(
			Helper::getDbTableName('urls'), 
			$pageId
		);

		wp_send_json_success(array(
			'message' => __('New Test Page successfully added', 'ninja-split-testing'),
			'test_id' => $pageId,
			'test_page' => $campaign_page
		), 200);
	}


	/**
	 * Updating an existing testing page of a campaign
	 * 
	 * @return void
	 */
	public function updateTestingPage() 
	{
		if ( ! $_REQUEST['title'] ||
		     ! $_REQUEST['target_url'] ||
		     ! $_REQUEST['traffic_split_amount'] ||
		     ! strlen(sanitize_text_field($_REQUEST['title']))
		)
		{
			$this->responseError(__( 'Please provide all the required data', 'ninja-split-testing' ));
		}

		$page_id = intval($_REQUEST['id']);
		$campaign_id = intval($_REQUEST['campaign_id']);
		$page_data = array(
			'title' => sanitize_text_field($_REQUEST['title']),
			'target_post_id' => intval($_REQUEST['target_post_id']),
			'target_url' => sanitize_text_field($_REQUEST['target_url']),
			'traffic_split_amount' => intval($_REQUEST['traffic_split_amount']),
			'campaign_id' => $campaign_id,
			'user_id' => get_current_user_id(),
			'status' => 'active'
		);
		
		$page_data = apply_filters('nst_update_campaign_page_data', $page_data, $campaign_id);

		$pageId = NstQueries::update(
			Helper::getDbTableName('urls'), 
			$page_data, 
			$page_id
		);
		
		do_action('nst_updated_campaign_page', $campaign_id, $pageId );
		
		wp_send_json_success(array(
			'message' => __('Test successfully updated', 'ninja-split-testing'),
			'test_id' => $pageId
		), 200);
	}


	/**
	 * Getting all testing pages of a Campaign
	 * 
	 * @return void
	 */
	public function getAllTestingPage() 
	{
		$campaign_id = intval($_REQUEST['campaign_id']);

		$pages = NstQueries::get_where(
			Helper::getDbTableName('urls'), 
			'campaign_id', 
			$campaign_id
		);

		$pages = apply_filters('nst_all_campaign_pages', $pages, $campaign_id);

		wp_send_json_success(array(
			'pages' => $pages
		), 200);
	}


	/**
	 * Updating status of a Testing Page (e.g active <=> pending)
	 * 
	 * @return void
	 */
	public function updateTestingPageStatus() 
	{
		$pageId =  intval($_REQUEST['update_status']['id']);
		$updateData = array(
			'status' => sanitize_text_field($_REQUEST['update_status']['status'])	
		);
		$updateData = apply_filters('nst_update_page_status', $updateData, $pageId);
		
		NstQueries::update(
			Helper::getDbTableName('urls'),
			$updateData,
			$pageId
		);
		
		do_action('nst_updated_page_status', $pageId, $updateData);
		
		wp_send_json_success(array(
			'message' => __('Status changed successfully', 'ninja-split-testing')), 
		200);
	}


	/**
	 * Updating status of a Campaign (e.g active <=> draft)
	 *
	 * @return void
	 */
	public function updateCampaignStatus()
	{
		$campaign_id = intval($_REQUEST['update_status']['id']);
		$update_data = array(
			'status' => sanitize_text_field($_REQUEST['update_status']['status'])
		);
		$update_data = apply_filters('nst_update_campaign_status_data', $update_data, $campaign_id );
		
		NstQueries::update(
			Helper::getDbTableName('campaigns'),
			$update_data,
			$campaign_id
		);
		
		do_action('nst_updated_campaign_status', $campaign_id, $update_data);
		
		wp_send_json_success(array(
			'message' => __('Status changed successfully', 'ninja-split-testing')), 
		200);
	}
	

	/**
	 * Getting full analytics data for a campaign
	 *
	 * @return void
	 */
	public function getAnalytics() 
	{
		$campaign_id = intval($_REQUEST['campaign_id']);
		$campaign_url_id = false;
		
		if(isset($_REQUEST['campaign_url_id']) && !empty($_REQUEST['campaign_url_id'])) {
			$campaign_url_id = intval($_REQUEST['campaign_url_id']);
		}
		
		$data = array(
			'visitors_by_pages' => $this->getVisitorStatByPage($campaign_id),
			'stat_by_day' => $this->getCampaignDayStat($campaign_id, $campaign_url_id),
			'pages' => $this->getPages($campaign_id)
		);
		wp_send_json_success($data, 200);
	}

	
	/**
	 * Getting campaign statistics for every day
	 *
	 * @param [int] $campaign_id
	 * @param [int] $campaign_url_id
	 * 
	 * @return $stats
	 */
	private function getCampaignDayStat($campaign_id, $campaign_url_id) {
		$query = ninjaDB(Helper::getDbTableName('analytics'))
			->select(array('date(created_at) as date', 'COUNT(*) as records'))
			->where('campaign_id', $campaign_id);
		
		if($campaign_url_id) {
			$query->where('campaign_url_id', $campaign_url_id);
		}
		
		$stats = $query->groupBy('date(created_at)')
			->get();
		
		return $stats;
	}


	/**
	 * Getting visitor status for every pages of a campaign
	 *
	 * @param [int] $campaign_id
	 * 
	 * @return $stat
	 */
	private function getVisitorStatByPage($campaign_id) {
		$stat = ninjaDB(Helper::getDbTableName('analytics'))
					->select(array('COUNT(*) as records', 'campaign_url_id'))
					->where('campaign_id', $campaign_id)
					->groupBy('campaign_url_id')
					->get();
		return $stat;
	}
	

	/**
	 * Getting testing pages for a specic Campaign
	 *
	 * @param [int] $campaign_id
	 * 
	 * @return void
	 */
	private function getPages($campaign_id) {
		return ninjaDB(Helper::getDbTableName('urls'))
				->where('campaign_id', $campaign_id)
				->get();
	}

	/**
	 * Sending Error Response with a Message
	 * 
	 * @param [String] $message
	 * 
	 * @return void
	 */
	private function responseError($message = 'Something is wrong, Please try again') 
	{
		wp_send_json_error( array(
			'message' => $message
		), 423);
		die();
	}
	
}