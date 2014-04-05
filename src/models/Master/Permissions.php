<?php namespace Pulpitum\Auth\Models\Master;

use Pulpitum\Core\Models\Base;
use Pulpitum\Core\Models\Helpers\Tools;

class Permissions extends Base {

	protected $table = 'permissions';
	protected $primaryKey = 'id';
    protected $modelName = 'permissions';
    protected $fillable = array('name', 'value', 'description');
    protected $guarded = array('id');
    protected $tabs = array("dados"=> array("label"=>"Permissões", 'type'=>"section") );
	protected $sections = array("dados"=> array("label"=> "", "hideInCreate"=>false, "hideInUpdate"=>false, "tab"=>"dados") );

    public $timestamps = false;    

    public function __construct(){
        parent::__construct();
        $this->setColumnsList();
        $this->setEntidadeTitle( 'auth::core.permissions' );
        $this->setCheckboxFields();
    }  

    /**
     * Return the identifiant of the permission
     * @return int id of the permission
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Return the name of the permission
     * @return string name of the permission
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Return the value of the permission
     * @return string value of the permission
     */
    public function getValue()
    {
        return $this->value;
    }


    public function getOptions()
    {
        return $this->All()->lists('name', 'value');
    }
    /**
     * Return the name of the permission
     * @return string name of the permission
     */
    public function getNameByValue($value){
    	$query = $this->where('value', '=', $value)->first();
        if(!is_null($query))
    	   return $query->name;
        else
            return "";
    } 

    /**
     * Create permission
     * @param  array $attributes
     * @return Permission permission object
     */
    public function createPermission($attributes)
    {
    	$permission = new $this;
        $permission->fill($attributes)->save();
        return $permission;
    }

	public function findById($id)
    {
        if(!$permission = $this->find($id))
        {
            throw new PermissionNotFoundException("A permission could not be found with ID [$id].");
        }

        return $permission;
    }

    /**
     * Saves the permission.
     *
     * @param  array  $options
     * @return bool
     */
    public function save(array $options = array())
    {
        $this->validate();

        return parent::save($options);
    }

    /**
     * Validate permissions
     * @return bool
     */
    public function validate()
    {
        if(!$name = $this->getName())
        {
            throw new NameRequiredException("A name is required for a permission, none given.");
        }

        if(!$value = $this->getValue())
        {
            throw new ValueRequiredException("A value is required for a permission, none given.");
        }

        // Check if the permission already exists
        $query = $this->newQuery();
        $persistedPermission = $query->where('value', '=', $value)->first();

        if($persistedPermission and $persistedPermission->getId() != $this->getId())
        {
            throw new PermissionExistsException("A permission already exists with value [$value], values must be unique for permissions.");
        }

        return true;
    }



    /**
     * Return description of the permission
     * @return string description of the permission
     */
    public function getDescription()
    {
        return $this->description;
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
			'list'		=> array("path"=>'permissions', "as"=>"Permissions", "controller"=>"Pulpitum\Auth\Controllers\PermissionsController@getIndex", "method"=>"get", "permission"=>"permissions", "addToMenu"=>true, "toMenu"=>"admin", "reference"=>"permissions", "label"=>"Permissões", "parent"=>"auth", 'weight'=>2),
			'edit'		=> array("path"=>'permissions/edit/{id}', "as"=>"EditPermissions", "controller"=>"Pulpitum\Auth\Controllers\PermissionsController@getEdit", "method"=>"get", "permission"=>"permissions-edit"),
			//'delete' 	=> array("path"=>'permissions/delete/{id}', "as"=>"DeletePermissions", "controller"=>"Pulpitum\Auth\Controllers\PermissionsController@getDelete", "method"=>"get", "permission"=>"permissions-delete"),
			//'add' 		=> array("path"=>'permissions/add', "as"=>"AddPermissions", "controller"=>"Pulpitum\Auth\Controllers\PermissionsController@getAdd", "method"=>"get", "permission"=>"permissions-add"),
			//'view' 		=> array("path"=>'permissions/{id}', "as"=>"GetPermissions", "controller"=>"Pulpitum\Auth\Controllers\PermissionsController@getView", "method"=>"get", "permission"=>"permissions-view"),
			'post' 		=> array("path"=>'permissions/edit/{id}', "as"=>"EditPermissions", "controller"=>"Pulpitum\Auth\Controllers\PermissionsController@postEdit", "method"=>"post", "permission"=>"permissions-edit"),
			//'post-add'	=> array("path"=>'permissions/add', "as"=>"AddPermissions", "controller"=>"Pulpitum\Auth\Controllers\PermissionsController@postAdd", "method"=>"post", "permission"=>"permissions-add")
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