<?php namespace Pulpitum\Auth\Models\Master;

use Pulpitum\Core\Models\Helpers\Tools;
use Pulpitum\Auth\Models\Master\Groups;
use Pulpitum\Core\Models\Base;
use Sentry;

class Messages extends Base {

	protected $table = 'users_messages';
	protected $primaryKey = 'id';
	protected $fillable = array();
	protected $modelName = 'messages';
	protected $tabs = array( "dados"=> array("label"=>"Mensagens", 'type'=>"section") );
	protected $sections = array( "dados"=>array("label"=>"Nova Mensagem","tab"=>"dados") );

	public $timestamps = true;

	public function __construct(){
		parent::__construct();
		$this->setColumnsList();
		$this->getFillableFields();
		$this->setCheckboxFields();
		$this->setEntidadeTitle( 'auth::messages.mensagens' );
		
	}


	public static function countMessages(){
		$user_id = Sentry::getUser()->id;
		$query = Messages::where("id_user", '=', $user_id)->where("read","=",0)->count();
		return !is_null($query) ? $query : 0;
	}

    /**
     * specialQuery
     * 
     * @access public
     *
     * @return mixed Value.
     */
	public function specialQuery(){
		$user_id = Sentry::getUser()->id;
		$query = $this->where("id_user", '=', $user_id);
		return $query;
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
			'list'		=> array("path"=>'messages', "as"=>"Messages", "controller"=>"Pulpitum\Auth\Controllers\MessagesController@getIndex", "method"=>"get", "permission"=>"messages", "addToMenu"=>false, "toMenu"=>"admin", "reference"=>"messages", "label"=>"Mensagens", "parent"=>"auth", 'weight'=>0),
			'delete' 	=> array("path"=>'messages/delete/{id}', "as"=>"DeleteMessages", "controller"=>"Pulpitum\Auth\Controllers\MessagesController@getDelete", "method"=>"get", "permission"=>"messages-delete"),
			'add' 		=> array("path"=>'messages/add', "as"=>"AddMessages", "controller"=>"Pulpitum\Auth\Controllers\MessagesController@getAjaxAdd", "method"=>"get", "permission"=>"messages-add"),
			'view' 		=> array("path"=>'messages/{id}', "as"=>"GetMessages", "controller"=>"Pulpitum\Auth\Controllers\MessagesController@getView", "method"=>"get", "permission"=>"messages-view", "class"=>"ajax_request"),
			'post-add'	=> array("path"=>'messages/add', "as"=>"AddMessages", "controller"=>"Pulpitum\Auth\Controllers\MessagesController@postAjaxAdd", "method"=>"post", "permission"=>"messages-add")
		);
	}

	public function getActionOption($action, $option){
		$actionsUrl = $this->actionsUrl();
		return isset( $actionsUrl[$action][$option] ) ? $actionsUrl[$action][$option] : '';
	}

	public function actionsListBtn(){
		return array(
			"addlinha" => array('url' => 'AddMessages','data-model'=>'messages', 'class'=> 'ajax_request', 'label' => 'auth::core.send', 'permission'=>"messages-add")
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