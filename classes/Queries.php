<?php

namespace  NinjaABClass\classes;

class Queries
{
	public static function insert($table, $data) {
		$data['created_at'] = date('Y-m-d H:i:s');
		$data['updated_at'] = date('Y-m-d H:i:s');
		$insertId = ninjaDB($table)->insert($data);
		return $insertId;
	}

	public static function update($table, $data, $id = null) {
		if($id == null) {
			$id = $data['id'];
		}
		
		$data['updated_at'] = date('Y-m-d H:i:s');
		$result = ninjaDB($table)->where('id', $id)->update($data);
		return $result;
	}

	public static function find($table, $id) {
		return ninjaDB($table)->find($id);
	}
	
	public static function get_where($table, $column_name, $id) {
		return ninjaDB($table)->where($column_name, $id)->get();
	}
	
	public static function storePostID($table, $data) {
		
		$id = $data['id'];
		unset($data['id']);

		ninjaDB($table)->where('id', $id)->update($data);

		wp_send_json_success(array(
			'message' => __('Updated successfully', 'ninja-split-testing')), 
		200);

		exit;
	}

	public static function getAllData($table) {
		
		$data = ninjaDB($table)->get();

		wp_send_json_success($data, 200);
		exit;
	}

	public static function getSingleData($table, $id) {
		
		$data = ninjaDB($table)->find($id);

		wp_send_json_success($data, 200);
		exit;
	}

	public static function storeTestingPage($table, $data) {

		if($data['id']) {
			$id = $data['id'];
			unset($data['id']);
			$data['updated_at'] = date('Y-m-d H:i:s');

			ninjaDB($table)->where('id', $id)->update($data);

			wp_send_json_success(array(
				'message' => __('Updated successfully', 'ninja-split-testing')), 
			200);

			exit;
		}

		$data['created_at'] = date('Y-m-d H:i:s');
		$data['updated_at'] = date('Y-m-d H:i:s');

		ninjaDB($table)->insert($data);

		wp_send_json_success(array(
			'message' => __('Added successfully', 'ninja-split-testing')), 
		200);

		exit;
	}

	public static function getAllTestingPage($table, $id) {

		$pages = ninjaDB($table)->where('campaign_id', $id)->get();

		wp_send_json_success($pages, 200);
		exit;
	}

	public static function updateTestingPageStatus($table, $data) {
		
		$changedStatus = [
			'status' 	 =>  $data['status'],
			'updated_at' =>   date('Y-m-d H:i:s')
		];

		ninjaDB($table)->where('id', $data['id'])->update($changedStatus);

		wp_send_json_success(array(
			'message' => __('Status changed successfully', 'ninja-split-testing')), 
		200);

		exit;
	}
}