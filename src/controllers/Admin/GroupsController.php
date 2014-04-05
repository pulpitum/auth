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

class GroupsController extends BackendController {

	protected $entidade;
	protected $group_rules = array( 'name' => array('required', 'min:3', 'max:16', 'alpha') );

	public function __construct(){
    	$this->entidade = $this->getEntidade();
    	parent::__construct();
  	}
	
	public function getIndex(){
		return $this->theme->of('core::core', array('data' => $this->entidade ))->render();
    }

    public function getView($id){
    	$model = $this->entidade->find($id);
    	return $this->theme->of('lactiweb::view', array("model" => $model ))->render();
    }

    public function getEdit($id){
    	$model = $this->entidade->find($id);
    	$return = $this->entidade->actionsUrl();
    	return $this->theme->of('core::forms.edit', array("model" => $model, "update_rules"=>$this->group_rules, "create_rules"=>$this->group_rules, "title"=>$this->getName(), "entidade"=>$this->entidade, "return"=>$return['list']['as'] ))->render();
    }

    public function postEdit($id){

		$permissionsValues = Input::get('permissions');
        $groupname = Input::get('name');
        $permissions = $this->_formatPermissions($permissionsValues);

		$validator = Validator::make(Input::all(), $this->group_rules);
        
        if($validator->fails())
        {
            Session::flash('warning', trans('Confirme os dados inseridos'));
	    	return Redirect::to(URL::previous())->withErrors($validator)->withInput();
        }

		$return = Input::get("return");
        try
        {

            $group = Sentry::getGroupProvider()->findById($id);
            $group->name = $groupname;
            $group->permissions = $permissions;

            $permissions = (empty($permissions)) ? '' : json_encode($permissions);
            // delete permissions in db
            DB::table('groups')
                ->where('id', $id)
                ->update(array('permissions' => $permissions));

            if($group->save())
            {
            	Session::flash('success', trans('auth::messages.group_success'));
            	return Redirect::route($return);
            }
            else 
            {
            	Session::flash('warning', trans('auth::messages.group_try'));
            	return Redirect::to(URL::previous())->withInput();
            }
            
        }
        catch (\Cartalyst\Sentry\Groups\NameRequiredException $e) {}
        catch (\Cartalyst\Sentry\Groups\GroupExistsException $e)
        {
        	Session::flash('warning', trans('auth::messages.group_exists'));
        	return Redirect::to(URL::previous())->withInput();
        }
		return Redirect::route($return);
    }

    public function getAdd(){
    	$model = $this->entidade;
    	$return = $this->entidade->actionsUrl();
    	return $this->theme->of('core::forms.edit', array("model" => $model, "update_rules"=>$this->group_rules, "create_rules"=>$this->group_rules, "title"=>$this->getName(), "entidade"=>$this->entidade, "return"=>$return['list']['as'] ))->render();
    }

    public function postAdd(){

		$permissionsValues = Input::get('permissions');
		$groupname = Input::get('name');
		$permissions = $this->_formatPermissions($permissionsValues);
		$validator = Validator::make(Input::all(), $this->group_rules);
        
        if($validator->fails())
        {
            Session::flash('warning', trans('Confirme os dados inseridos'));
	    	return Redirect::to(URL::previous())->withErrors($validator)->withInput();
        }
		try
        {
            // create group
            Sentry::getGroupProvider()->create(array(
                'name' => $groupname,
                'permissions' => $permissions,
            ));
            Session::flash('success', trans('auth::messages.group_success'));
        }
        catch (\Cartalyst\Sentry\Groups\NameRequiredException $e) {}
        catch (\Cartalyst\Sentry\Groups\GroupExistsException $e)
        {
		    Session::flash('warning', trans('auth::messages.group_exists'));
		    return Redirect::to(URL::previous())->withInput();
        }
		$return = Input::get("return");
		return Redirect::route($return);

    }

    public function getDelete($id){
		try
        {
            $group = Sentry::getGroupProvider()->findById($id);
            $group->delete();
			Session::flash('success', trans('auth::messages.group_delete-success'));
			return Redirect::to(URL::previous());
        }
        catch (\Cartalyst\Sentry\Groups\GroupNotFoundException $e)
        {
		    Session::flash('warning', trans('auth::messages.group_not-found'));
			return Redirect::to(URL::previous());
        }
    }

	protected function _formatPermissions($permissionsValues)
    {
        $permissions = array();
        if(!empty($permissionsValues))
        {
            foreach($permissionsValues as $key => $permission)
            {
               $permissions[$permission] = 1;
            }
        }
        return $permissions;
    }

}

?>