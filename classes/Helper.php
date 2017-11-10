<?php
/**
 * Created by PhpStorm.
 * User: jewel
 * Date: 10/28/17
 * Time: 2:36 PM
 */

namespace NinjaABClass\classes;


class Helper {
	
	public static function getDbTableName($table_key) 
	{
		$tables = array(
			'campaigns' => 'nst_campaigns',
			'urls' => 'nst_campaign_urls',
			'analytics' => 'nst_campaign_analytics'
		);
		
		if(isset($tables[$table_key])) {
			return $tables[$table_key];
		}
		throw new \Exception($table_key.__(' is not a valid table name', 'ninja_ab') );
	}


	public static function getRealIpAddr() 
	{
		if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) { //check ip from share internet
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) { //to check ip is pass from proxy
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}

		return $ip;
	}

	public static function getCampaignsTableName()
	{
		return 'nst_campaigns';
	}

	public static function getCampaignUrlsTableName()
	{
		return 'nst_campaign_urls';
	}

	public static function getCampaignAnalyticsTableName()
	{
		return 'nst_campaign_analytics';
	}

	public static function getAssetsDirUrl() 
	{
		return plugin_dir_url( __DIR__ );
	}

	public static function getViewsDir() 
	{
		return plugin_dir_path( __DIR__ ) . 'views/';
	}

	public static function getViewFile( $file ) 
	{
		return static::getViewsDir() . $file . '.php';
	}

	public static function getAssetDirUrl() 
	{
		return static::getAssetsDirUrl() . 'assets/';
	}

	public static function getAssetImage( $file ) 
	{
		return static::getAssetDirUrl() . 'img/' . $file;
	}

	public static function loadViewFile($file, $data = []) 
	{
		extract($data);
		ob_start();
		require static::getViewFile($file);
		$return = ob_get_contents();
		ob_clean();
		return $return;
	}

	public static function dd() 
	{
		array_map(function ($x) {
			var_dump($x);
		}, func_get_args());

		die();
	}
	
}