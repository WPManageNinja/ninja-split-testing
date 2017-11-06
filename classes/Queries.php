<?php

namespace  NinjaABClass\classes;

class Queries
{
	public static function store($table, $data) {

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

		$insertId = ninjaDB($table)->insert($data);
		wp_send_json_success(array('id' => $insertId), 200);

		exit;
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
}