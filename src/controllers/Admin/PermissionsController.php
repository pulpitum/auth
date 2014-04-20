<?php namespace Pulpitum\Auth\Controllers;

use Pulpitum\Core\Controllers\BackendController as BackendController;
use Input;
use Sentry;
use Redirect;
use Config;
use Response;
use DB;
use Request;
use Route;
use Validator;
use Session;
use URL;
use Pulpitum\Auth\Models\Master\Permissions as PermissionProvider;

class PermissionsController extends BackendController {

	protected $entidade;

	protected $permission_rules = array(
        'name' => array('required', 'min:3', 'max:100'),
        //'value' => array('required', 'alpha_dash', 'min:3', 'max:100'),
        'description' => array('required', 'min:3', 'max:255')
    );

	public function __construct(){
    	$this->entidade = new PermissionProvider;
    	parent::__construct();
  	}
	
	public function getIndex(){
		return $this->theme->of('core::core', array('data' => $this->entidade ))->render();
    }
/*
    public function getView($id){
    	$model = $this->entidade->find($id);
    	return $this->theme->of('lactiweb::view', array("model" => $model ))->render();
    }*/

    public function getEdit($id){
    	$model = $this->entidade->find($id);
    	$return = $this->entidade->actionsUrl();
    	return $this->theme->of('core::forms.edit', array("model" => $model, "update_rules"=>$this->permission_rules, "create_rules"=>$this->permission_rules, "title"=>$this->getName(), "entidade"=>$this->entidade, "return"=>$return['list']['as'] ))->render();
    }

    /*public function getAdd(){
    	$model = $this->entidade;
    	$return = $this->entidade->actionsUrl();
    	return $this->theme->of('auth::forms.edit', array("model" => $model, "title"=>$this->getName(), "entidade"=>$this->entidade, "return"=>$return['list']['as'] ))->render();
    }

    public function postAdd(){

    	try
        {
			$validator = Validator::make(Input::all(), $this->permission_rules);
	        
	        if($validator->fails())
	        {
	            Session::flash('warning', trans('Confirme os dados inseridos'));
		    	return Redirect::to(URL::previous())->withInput();
	        }

            // create permission
            $permission = $this->entidade->createPermission(Input::all());
        }
        catch (\Pulpitum\Auth\Models\Exceptions\NameRequiredException $e) {}
        catch (\Pulpitum\Auth\Models\Exceptions\ValueRequiredException $e) {}
        catch (\Pulpitum\Auth\Models\Exceptions\PermissionExistsException $e) {
		    Session::flash('warning', trans('auth::messages.permissions_exists'));
		    return Redirect::to(URL::previous())->withInput();
        }
		$return = Input::get("return");
		return Redirect::route($return);
    }*/

    public function postEdit($id){

		$validator = Validator::make(Input::all(), $this->permission_rules);
        
        if($validator->fails())
        {
            Session::flash('warning', trans('Confirme os dados inseridos'));
	    	return Redirect::to(URL::previous())->withErrors($validator)->withInput();
        }
 		try
        {
            // Find the permission using the permission id
            $permission = $this->entidade->findById($id);
            $permission->fill(Input::all());

            // Update the permission
            if($permission->save())
            {
            	$return = Input::get("return");
				return Redirect::route($return);
            }
            else 
            {
	            Session::flash('warning', trans('auth::messages.permissions_update-fail'));
		    	return Redirect::to(URL::previous())->withInput();
            }
        }
        catch (\Pulpitum\Auth\Models\Exceptions\PermissionExistsException $e)
        {
            Session::flash('warning', trans('auth::messages.permissions_exists'));
	    	return Redirect::to(URL::previous())->withInput();
        }
    }


/*
    public function getDelete($id){
 		try
        {
            $permission = $this->entitdade->findById($id);
            $permission->delete();
        }
        catch (\Pulpitum\Auth\Models\Exceptions\PermissionNotFoundException $e)
        {
            Session::flash('warning', trans('auth::messages.not-found'));
	    	return Redirect::to(URL::previous());
        }
    }*/

}

?>