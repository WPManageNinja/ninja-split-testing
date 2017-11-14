<?php

namespace  NinjaABClass\classes;

class Queries
{
	/**
	 * Inserting data into given table of a database
	 *
	 * @param [String] $table
	 * @param [array] $data
	 * 
	 * @return $insertId
	 */
	public static function insert($table, $data) 
	{
		$now = date('Y-m-d H:i:s');

		$data['created_at'] = $now;
		$data['updated_at'] = $now;

		$insertId = ninjaDB($table)->insert($data);

		return $insertId;
	}


	/**
	 * Updating data of a given table by ID
	 *
	 * @param [String] $table
	 * @param [array] $data
	 * @param [int] $id
	 * 
	 * @return $result
	 */
	public static function update($table, $data, $id = null) 
	{
		if($id == null) {
			$id = $data['id'];
		}
		
		$data['updated_at'] = date('Y-m-d H:i:s');
		$result = ninjaDB($table)->where('id', $id)->update($data);
		
		return $result;
	}


	/**
	 * Deleting a data from given table by ID
	 *
	 * @param [String] $table
	 * @param [int] $id
	 * 
	 * @return $data
	 */
	public static function delete($table, $id)
	{
		ninjaDB($table)->where('id',$id)->delete();
	}


	/**
	 * Finding a data from given table by ID
	 *
	 * @param [String] $table
	 * @param [int] $id
	 * 
	 * @return $data
	 */
	public static function find($table, $id) 
	{
		return ninjaDB($table)->find($id);
	}

	
	/**
	 * Getting data from given table by column_name and ID
	 *
	 * @param [String] $table
	 * @param [String] $column_name
	 * @param [int] $id
	 * 
	 * @return $data
	 */
	public static function get_where($table, $column_name, $id) 
	{
		return ninjaDB($table)->where($column_name, $id)->get();
	}


	/**
	 * Getting all data from given table
	 * 
	 * @param [String] $table
	 * 
	 * @return $data
	 */
	public static function getAll($table) 
	{
		return $data = ninjaDB($table)->get();
	}


	/**
	 * Getting single data from given table by ID
	 * 
	 * @param [String] $table
	 * @param [int] $id
	 * 
	 * @return $data
	 */
	public static function getSingle($table, $id) 
	{
		return ninjaDB($table)->find($id);
	}


	/**
	 * Deleting Campaign by ID
	 * 
	 * @param [String] $id
	 * 
	 * @return void
	 */
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