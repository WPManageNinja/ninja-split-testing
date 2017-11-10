<?php

namespace  NinjaABClass\classes;

class Queries
{
	public static function insert($table, $data) 
	{
		$now = date('Y-m-d H:i:s');

		$data['created_at'] = $now;
		$data['updated_at'] = $now;

		$insertId = ninjaDB($table)->insert($data);

		return $insertId;
	}

	public static function update($table, $data, $id = null) 
	{
		if($id == null) {
			$id = $data['id'];
		}
		
		$data['updated_at'] = date('Y-m-d H:i:s');
		$result = ninjaDB($table)->where('id', $id)->update($data);
		
		return $result;
	}

	public static function deleteCampaign($id)
	{
		ninjaDB('nst_campaign_analytics')->where('campaign_id', $id)->delete();
		ninjaDB('nst_campaign_urls')->where('campaign_id', $id)->delete();
		ninjaDB('nst_campaigns')->where('id', $id)->delete();
	}

	public static function find($table, $id) 
	{
		return ninjaDB($table)->find($id);
	}
	
	public static function get_where($table, $column_name, $id) 
	{
		return ninjaDB($table)->where($column_name, $id)->get();
	}

	public static function getAll($table) 
	{
		return $data = ninjaDB($table)->get();
	}

	public static function getSingle($table, $id) 
	{
		return ninjaDB($table)->find($id);
	}
	
}