<?php namespace Dev3gntw\Auth\Models\Values;

use Dev3gntw\Core\Models\Base;
use \Dev3gntw\Auth\Models\Master\Users;
use DB;

class User extends Users {

	public function getValue($id){
		$user = new $this;
		$user = $user->where('id', $id)->first();
		return $user->first_name.' '.$user->last_name;
	}

	public function getOptions($field=null){
		$source = new $this;
		$output = array();
		$itens = $source->addSelect(DB::raw(" CONCAT(`first_name`, ' ', `last_name`) as Name"))->addSelect('id')->orderBy("Name")->get()->toArray();
		foreach ($itens as $item) {
			$output[$item['id']] = trim($item['Name']);
		}
		return $output;
	}

}