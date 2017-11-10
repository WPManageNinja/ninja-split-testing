<?php namespace NinjaABClass\classes;

class CampaignTransient {
	
	public static $transient_key = '_ninja_split_testing_all_active_post_ids';
	
	public static function getCampaign($post_id) {
		$campaignPosts = self::getCampaignIds();
		
		if(!$campaignPosts || !count($campaignPosts)) {
			return false;
		}
		
		if(in_array($post_id, $campaignPosts)) {
			return ninjaDB( Helper::getDbTableName( 'campaigns' ) )
				->where( 'post_id', $post_id )
				->where( 'status', 'active' )
				->first();
		}
		return false;
	}
	
	public static function getCampaignIds() {
		if ( false === ( $campaignPosts = self::getFromTransient() ) ) {
			$campaignPosts = self::getFromDB();
			self::setTransient($campaignPosts);
		}
		return $campaignPosts;
	}
	
	private static function getFromTransient() {
		return get_transient( self::$transient_key );
	}
	
	private static function getFromDB() {
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
	
	private static function setTransient($postIds) {
		if(is_array($postIds)) {
			return set_transient(self::$transient_key, $postIds, 172800); // for 2 days
		}
		return false;
	}
	
	public static function removeFromDB() {
		return delete_transient(self::$transient_key);
	}
	
	public static function rebuildTransient() {
		self::removeFromDB();
		$postIds = self::getFromDB();
		return self::setTransient($postIds);
	}
	
}