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

	public static function delete($table, $id)
	{
		ninjaDB($table)->where('id',$id)->delete();
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

	public static function getCampaignAnalytics($table, $campaign_id)
	{
		$active = ninjaDB($table)
	                 ->where('campaign_id', $campaign_id)
	                 ->where('status', 'active')
	                 ->select(array('target_url','traffic_split_amount', 'visit_counts'))
	                 ->get();

        $inactive = ninjaDB($table)
	                 ->where('campaign_id', $campaign_id)
	                 ->where('status', 'inactive')->get();

		$data = [
			'totalActivePages'   => count($active),
			'totalInactivePages' => count($inactive),
			'activePageData'	 => $active
		];

		return $data;
	}
	
	public static function deleteCampaign($id)
	{
		ninjaDB(Helper::getDbTableName('analytics'))
			        ->where('campaign_id', $id)
			        ->delete();
		ninjaDB(Helper::getDbTableName('urls'))
		        ->where('campaign_id', $id)
		        ->delete();
		ninjaDB(Helper::getDbTableName('campaigns'))
		        ->where('id', $id)
		        ->delete();
	}
}