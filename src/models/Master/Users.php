<?php namespace Pulpitum\Auth\Models\Master;

use Pulpitum\Core\Models\Base;
use Pulpitum\Core\Models\Helpers\Tools;
use Sentry;
use Validator;

class Users extends Base {

	protected $table = 'users';
	protected $primaryKey = 'id';
	protected $fillable = array();
	protected $modelName = 'users';	

	public $timestamps = false;	
	
	protected $sections = array(
		"dados"				=> array("label"=> "Dados", "hideInCreate"=>false, "hideInUpdate"=>false,"tab"=>"dados") , 
		"administrativo"	=> array("label"=> "Administrativo", "hideInCreate"=>false, "hideInUpdate"=>false,"tab"=>"dados") , 
		"password" 			=> array("label"=> "auth::form.password", "hideInCreate"=>true, "hideInUpdate"=>false,"tab"=>"dados") , 
		"permissions"		=> array("label"=> "auth::form.permissoes", "hideInCreate"=>false, "hideInUpdate"=>false,"tab"=>"dados")  
	);
	protected $tabs = array(
		"dados"=> array("label"=>"Utilizador", 'type'=>"section"), 
	);

	public function __construct(){
		parent::__construct();
		$this->setColumnsList();
		$this->getFillableFields();
		$this->setCheckboxFields();
		$this->setEntityTitle( 'auth::core.users' );

		Validator::extend('owner', function($attribute, $value, $parameters){
		    return Sentry::check() && $value == Sentry::getUser()->getId();
		});

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
			'list'			=> array("path"=>'users', "as"=>"Users", "controller"=>"Pulpitum\Auth\Controllers\UsersController@getIndex", "method"=>"get", "permission"=>"users", "addToMenu"=>true, "toMenu"=>"admin", "reference"=>"users", "label"=>"Utilizadores", "parent"=>"auth", 'weight'=>0),
			'edit'			=> array("path"=>'users/edit/{id}', "as"=>"EditUsers", "controller"=>"Pulpitum\Auth\Controllers\UsersController@getEdit", "method"=>"get", "permission"=>"users-edit"),
			'delete' 		=> array("path"=>'users/delete/{id}', "as"=>"DeleteUsers", "controller"=>"Pulpitum\Auth\Controllers\UsersController@getDelete", "method"=>"get", "permission"=>"users-delete"),
			'add' 			=> array("path"=>'users/add', "as"=>"AddUsers", "controller"=>"Pulpitum\Auth\Controllers\UsersController@getAdd", "method"=>"get", "permission"=>"users-add"),
			'view' 			=> array("path"=>'users/{id}', "as"=>"GetUsers", "controller"=>"Pulpitum\Auth\Controllers\UsersController@getView", "method"=>"get", "permission"=>"users-view"),
			'post' 			=> array("path"=>'users/edit/{id}', "as"=>"EditUsers", "controller"=>"Pulpitum\Auth\Controllers\UsersController@postEdit", "method"=>"post", "permission"=>"users-edit"),
			'post-add'		=> array("path"=>'users/add', "as"=>"AddUsers", "controller"=>"Pulpitum\Auth\Controllers\UsersController@postAdd", "method"=>"post", "permission"=>"users-add"),
			'post-password'	=> array("path"=>'users/password', "as"=>"PostChangePassword", "controller"=>"Pulpitum\Auth\Controllers\UsersController@postUpdatePassword", "method"=>"post", "permission"=>"users-post-password")
			
		);
	}

	public function actionsListBtn(){
		return array(
			'imprimir' => array( 
				"label" => 'core::all.print',
				"children" => array(
					"List" => array('url' => 'datatables-print', 'class'=> 'print_pdf', 'label' => 'core::all.list', 'permission'=>"users-print")
				)
			),			
			'accoes' => array(
				"label" => 'core::all.accoes',
				"children" => array(
					"Add" => array('url' => 'AddUsers', 'class'=> '', 'label' => 'core::all.add', 'permission'=>"users-add")
				)
			)
		);
	}

	public function actionsEditBtn(){
		return array(
			'voltar' => array('url' => 'Users', 'class'=> '', 'label' => 'core::all.back'),
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