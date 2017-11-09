<?php namespace  NinjaABClass\classes;

class AdminHooks {

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
		$this->enqueue_nst_assets();
		echo Helper::loadViewFile('show_main_page');
	}

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


	public function ajax_routes() 
	{
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
			'store-campaign-testing-page' 	=> 'createNewTestPage',
			'get-all-testing-page' 			=> 'getAllTestingPage',
			'update-testing-page-status'	=> 'updateTestingPageStatus',
			'update-testing-page'	        => 'updateTestingPage'
		);

		$requested_route = $_REQUEST['target_action'];

		if ( isset( $valid_routes[ $requested_route ] ) ) {
			$this->{$valid_routes[ $requested_route ]}();
		}

		wp_die();
	}

	
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
		
		$campaignId = Queries::insert('nst_campaigns', $campaign_data);
		
		wp_send_json_success(array(
			'id' => $campaignId,
			'message' => __('Campaign added successfully', 'ninja-split-testing')
		), 200);
	}


	public function getAllCampaign() 
	{
		$data = Queries::getAll('nst_campaigns');
		wp_send_json_success($data, 200);
	}


	public function getCampaignByID() 
	{
		$data = Queries::getSingle(
			'nst_campaigns', 
			intval($_REQUEST['campaign_id'])
		);

		wp_send_json_success($data, 200);
	}


	public function getAllPostAndPages() 
	{
		$searchString = sanitize_text_field($_REQUEST['search_string']);
		$postTypes = get_post_types( array( ) );
		
		$args = [
			'post_type' => $_REQUEST['post_type']
		];

		$data = get_posts($args);

		wp_send_json_success($data, 200);
	}

	public function getAllPostTypes() 
	{
		$args = array('public' => true);
		$data = array('post_types' => get_post_types($args));

		wp_send_json_success($data, 200);
	}

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

		$page_data = array(
			'title' => sanitize_text_field($_REQUEST['title']),
			'target_post_id' => intval($_REQUEST['target_post_id']),
            'target_url' => sanitize_text_field($_REQUEST['target_url']),
            'traffic_split_amount' => intval($_REQUEST['traffic_split_amount']),
			'campaign_id' => intval($_REQUEST['campaign_id']),
			'user_id' => get_current_user_id(),
			'status' => 'active'
		);
		
		$pageId = Queries::insert('nst_campaign_urls', $page_data);
		$campaign_page = Queries::find('nst_campaign_urls', $pageId);
		
		wp_send_json_success(array(
			'message' => __('New Test Page successfully added', 'ninja-split-testing'),
			'test_id' => $pageId,
			'test_page' => $campaign_page
		), 200);
	}

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
		$page_data = array(
			'title' => sanitize_text_field($_REQUEST['title']),
			'target_post_id' => intval($_REQUEST['target_post_id']),
			'target_url' => sanitize_text_field($_REQUEST['target_url']),
			'traffic_split_amount' => intval($_REQUEST['traffic_split_amount']),
			'campaign_id' => intval($_REQUEST['campaign_id']),
			'user_id' => get_current_user_id(),
			'status' => 'active'
		);

		$pageId = Queries::update('nst_campaign_urls', $page_data, $page_id);
		
		wp_send_json_success(array(
			'message' => __('Test successfully updated', 'ninja-split-testing'),
			'test_id' => $pageId
		), 200);
	}

	public function getAllTestingPage() 
	{
		$campaign_id = intval($_REQUEST['campaign_id']);
		$pages = Queries::get_where('nst_campaign_urls', 'campaign_id', $campaign_id);
		wp_send_json_success(array(
			'pages' => $pages
		), 200);
	}

	public function updateTestingPageStatus() 
	{
		Queries::updateTestingPageStatus(
			'nst_campaign_urls', 
			intval($_REQUEST['update_status']['id']),
			sanitize_text_field($_REQUEST['update_status']['status'])
		);

		wp_send_json_success(array(
			'message' => __('Status changed successfully', 'ninja-split-testing')), 
		200);
	}
	
	private function responseError($message = 'Something is wrong, Please try again') 
	{
		wp_send_json_error( array(
			'message' => $message
		), 423);
		die();
	}
}