<?php namespace NinjaABClass\classes;

class PluginActivation {
	
	public static function activate() {
		self::createCampaignsDBTable();
		self::createCampaignUrlsDBTable();
		self::createCampaignAnalyticsDBTable();
	}
	
	public static function createCampaignsDBTable() {
		global $wpdb;
		$table_name = $wpdb->prefix . Helper::getDbTableName('campaigns');
		if ( ! self::tableExists($table_name) ) {
			$sql = "CREATE TABLE $table_name (
				id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
				title VARCHAR (255) NOT NULL,
				cookie_key_prefix VARCHAR (255),
				cookie_validity int (5) DEFAULT 7,
				cookie_status ENUM('yes','no') DEFAULT 'yes',
				description TEXT,
				post_id int(11) NOT NULL,
				target_url VARCHAR (255) NULL,
				created_by int(11),
				status ENUM('active','draft','archived') DEFAULT 'draft',
				conditions TEXT,
				cache TEXT,
				created_at timestamp NULL,
				updated_at timestamp NULL,
				KEY post_id (post_id)
			)";
			
			self::runSQL($sql);
		}
	}
	
	public static function createCampaignUrlsDBTable() {
		global $wpdb;
		$table_name = $wpdb->prefix . Helper::getDbTableName('urls');
		if ( ! self::tableExists($table_name) ) {
			$sql = "CREATE TABLE $table_name (
				id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
				title VARCHAR (255) NOT NULL,
				campaign_id int(11) NULL,
				target_post_id int(11) NULL,
				target_url VARCHAR (255),
				status ENUM('active','inactive','archived') DEFAULT 'active',
				traffic_split_amount int(11) NULL,
				visit_counts int(11) DEFAULT 0,
				conditions TEXT,
				created_at timestamp NULL,
				updated_at timestamp NULL,
				KEY campaign_id (campaign_id)
			)";

			self::runSQL($sql);
		}
	}
	
	public static function createCampaignAnalyticsDBTable() {
		global $wpdb;
		$table_name = $wpdb->prefix . Helper::getDbTableName('analytics');
		if ( ! self::tableExists($table_name) ) {
			$sql = "CREATE TABLE $table_name (
				id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
				campaign_id int(11) NOT NULL,
				campaign_url_id int(11) NOT NULL,
				browser VARCHAR (255),
				ip_address VARCHAR (200),
				platform VARCHAR (200),
				city VARCHAR (200),
				state VARCHAR (200),
				country VARCHAR (200),
				user_id int(11) NULL,
				is_unique int(1) DEFAULT 1,
				created_at timestamp NULL,
				updated_at timestamp NULL,
				KEY campaign_url_id (campaign_url_id)
			)";

			self::runSQL($sql);
		}
	}
	
	
	private static function tableExists($table_name) {
		global $wpdb;
		return  $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" ) == $table_name;
	}
	
	private static function runSQL($sql) {
		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql. " $charset_collate;" );
	}
}