<?php namespace NinjaABClass\classes;

class Redirect {
	
	private $all_redirect_posts_transient_key = 'ninja_split_testing_all_active_post_ids';
	
	public function setCookie($key, $value, $days = 7) {
		return setcookie( $key, $value, time() + ( $days * DAY_IN_SECONDS ) );
	}
	
	public function getCookie($key) {
		if(isset($_COOKIE[$key])) {
			return $_COOKIE[$key];
		}
		return false;
	}
	
	public function deleteCookie($key) {
		unset( $_COOKIE[$key] );
		return setcookie( $key, '', time() - ( 60 * 60 ) );
	}
	
	public function checkIfUseCookie($campaign) {
		$status = false;
		if($campaign->cookie_status == 'yes') {
			$status = true;
		}
		return apply_filters('nst_use_cookie_for_returning_user', $status, $campaign);
	}
	
	public function checkAbTesting() {
		// check for singular posts
		global $wp_query;
		// Match for singular posts
		if($wp_query->is_singular) {
			$post_id = $wp_query->post->ID;
			// check if the post is in any campaign records
			$campaign = $this->getCampaign( $post_id );
			if(!$campaign)
				return;
			
			if($use_cookie = $this->checkIfUseCookie($campaign)) {
				$cookie_name = $this->getCookieKeyFormCampaign($campaign);
				if( $pre_redirectUrlId = $this->getCookie($cookie_name) ) {
					do_action('ninja_ab_returning_user_visiting_page', $pre_redirectUrlId, $campaign);
				}
			}
			
			if($campaign) {
				$urls = ninjaDB(Helper::getDbTableName('urls'))
							->where('campaign_id', $campaign->id)
							->where('status', 'active')
							->get();
				
				if(count($urls)) {
					$selected_url = $this->getRedirectUrl($urls, $campaign);
					$selected_url = apply_filters('ninja_ab_selected_url_for_redirect', $selected_url, $campaign);
					do_action('ninja_ab_before_redirect_to_selected_url', $selected_url, $campaign);
					if($use_cookie) {
						$this->setCookie( $cookie_name, $selected_url->id, $campaign->cookie_validity );
					}
					$this->setCookie('nst_currently_redirect_to', $selected_url->target_url, 1);
					$this->redirect($selected_url->target_url);
				}
			}
		}
		return;
	}
	
	private function getRedirectUrl($urls, $campaign) {
		$urlWeights = [];
		foreach ($urls as $index => $url) {
			$urlWeights[$index] = $url->traffic_split_amount;
		}
		$selectedIndex = $this->getRandomWeightedElement($urlWeights);
		return $urls[$selectedIndex];
	}
	
	private function getRandomWeightedElement($weightedValues) {
		$rand = mt_rand(1, (int) array_sum($weightedValues));
		foreach ($weightedValues as $key => $value) {
			$rand -= $value;
			if ($rand <= 0) {
				return $key;
			}
		}
	}
	
	public function logRedirect($url, $campaign) {
		$browser = new Browser();
		if($browser->isRobot())
			return;
		
		$analyticsData = array(
			'campaign_id' => $campaign->id,
			'campaign_url_id' => $url->id,
			'ip_address' => Helper::getRealIpAddr(),
			'platform' => $browser->getPlatform(),
			'browser' => $browser->getBrowser(),
			'user_id' => get_current_user_id(),
			'is_unique' => 1,
			'created_at' => date('Y-m-d H:i:s'),
			'updated_at' => date('Y-m-d H:i:s')
		); 
		
		$analyticsData = apply_filters('ninja_ab_insert_analytics_data', $analyticsData, $url, $campaign);
		$insert_id = ninjaDB(Helper::getDbTableName('analytics'))
					->insert($analyticsData);
		return $insert_id;
	}

	/**
	 * @param $post_id
	 *
	 * @return bool|mixed
	 */
	public function getCampaign( $post_id ) {
		
		if ( false === ( $campaignPosts = get_transient( $this->all_redirect_posts_transient_key ) ) ) {
			$campaignPosts = $this->campaignPostIdsFromDb();
			set_transient($this->all_redirect_posts_transient_key, $campaignPosts, 172800);
		}
		if(in_array($post_id, $campaignPosts)) {
			return ninjaDB( Helper::getDbTableName( 'campaigns' ) )
				->where( 'post_id', $post_id )
				->where( 'status', 'active' )
				->first();
		}
		return false;
	}
	
	private function campaignPostIdsFromDb() {
		$campaigns = ninjaDB(Helper::getDbTableName('campaigns'))
						->where('post_id', '!=', null)
						->where('status', 'active')
						->get();
		$postIds = array();
		foreach ($campaigns as $campaign) {
			$postIds[] = $campaign->post_id;
		}
		return $postIds;
	}
	
	public function redirectForReturningUser($pre_redirectUrl_id, $campaign) {
		$url = ninjaDB(Helper::getDbTableName('urls'))
				->where('campaign_id', $campaign->id)
				->find($pre_redirectUrl_id);
		
		if($url) {
			add_filter('ninja_ab_insert_analytics_data', function($analyticsData) {
				$analyticsData['is_unique'] = 0;
				return $analyticsData;
			});
			$this->logRedirect($url, $campaign);
			do_action('ninja_ab_before_redirect_returning_user', $url, $campaign);
			$this->setCookie('nst_currently_redirect_to', $url->target_url, 1);
			$this->redirect($url->target_url);
			exit();
		}
	}
	
	private function getCookieKeyFormCampaign($campaign) {
		return 'nst_'.$campaign->cookie_key_prefix.'_'.$campaign->id;
	}
	
	
	private function redirect($target_url, $status = 302) {
		// check if the target redirect and the page is same url, Otherwise it will loop in a black hope
		global $wp;
		$current_url = home_url(add_query_arg(array(),$wp->request));
		if($current_url != $target_url) {
			wp_redirect($target_url, $status);
			exit;
		}
		return;
	}
}