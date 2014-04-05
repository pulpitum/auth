<?php namespace Pulpitum\Auth\Models\Master;

use Pulpitum\Core\Models\Base;
use Pulpitum\Core\Models\Helpers\Tools;

class Groups extends Base {

	protected 	$table = 'groups';
	protected 	$primaryKey = 'id';
	protected 	$modelName = 'groups';	
	protected 	$tabs = array("dados"=> array("label"=>"Dados Gerais", 'type'=>"section") );
	protected 	$sections = array("dados"=> array("label"=> "Dados", "tab"=>"dados"), "permissions" => array("label"=> "auth::form.permissoes", "tab"=>"dados") );

	public 		$timestamps = false;	

	public function __construct(){
		parent::__construct();
		$this->setColumnsList();
		$this->getFillableFields();
		$this->setCheckboxFields();
		$this->setEntidadeTitle( 'auth::core.groups' );
	}  

    public function getOptions()
    {
        return $this->All()->lists('name', 'id');
    }

    public function getNameByValue($value){
    	return $this->where("id", $value)->pluck("name");
    }

    /**
     * actionsUrl
     * 
     * @access public
     *
     * @return mixed Value.
     */
	public function actionsUrl(){
		return array(
			'list'		=> array("path"=>'groups', "as"=>"Groups", "controller"=>"Dev3gntw\Auth\Controllers\GroupsController@getIndex", "method"=>"get", "permission"=>"groups", "addToMenu"=>true, "toMenu"=>"admin", "reference"=>"groups", "label"=>"Grupos de Utilizador", "parent"=>"auth", 'weight'=>1),
			'edit'		=> array("path"=>'groups/edit/{id}', "as"=>"EditGroups", "controller"=>"Dev3gntw\Auth\Controllers\GroupsController@getEdit", "method"=>"get", "permission"=>"groups-edit"),
			'delete' 	=> array("path"=>'groups/delete/{id}', "as"=>"DeleteGroups", "controller"=>"Dev3gntw\Auth\Controllers\GroupsController@getDelete", "method"=>"get", "permission"=>"groups-delete"),
			'add' 		=> array("path"=>'groups/add', "as"=>"AddGroups", "controller"=>"Dev3gntw\Auth\Controllers\GroupsController@getAdd", "method"=>"get", "permission"=>"groups-add"),
			'view' 		=> array("path"=>'groups/{id}', "as"=>"GetGroups", "controller"=>"Dev3gntw\Auth\Controllers\GroupsController@getView", "method"=>"get", "permission"=>"groups-view"),
			'post' 		=> array("path"=>'groups/edit/{id}', "as"=>"EditGroups", "controller"=>"Dev3gntw\Auth\Controllers\GroupsController@postEdit", "method"=>"post", "permission"=>"groups-edit"),
			'post-add'	=> array("path"=>'groups/add', "as"=>"AddGroups", "controller"=>"Dev3gntw\Auth\Controllers\GroupsController@postAdd", "method"=>"post", "permission"=>"groups-add")
		);
	}

	public function getActionOption($action, $option){
		$actionsUrl = $this->actionsUrl();
		return isset( $actionsUrl[$action][$option] ) ? $actionsUrl[$action][$option] : '';
	}

	public function actionsListBtn(){
		return array(
			'accões' => array(
				"label" => 'core::all.accoes',
				"children" => array(
					"Add" => array('url' => 'AddGroups', 'class'=> '', 'label' => 'core::all.add')
				)
			)
		);
	}

    /**
     * updateField
     * 
     * @param mixed $id    Description.
     * @param mixed $field Description.
     * @param mixed $value Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
	public function updateField($id, $field, $value){
		if(empty($id) or empty($field) or empty($value)){
			return "False";
		}else{
			if( $this->where("id", $id)->update(array($field=>$value)) == 1){
				return "True";
			}
		}
	}
	
}