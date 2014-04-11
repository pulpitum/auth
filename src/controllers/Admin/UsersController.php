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
use URL;
use Session;
use Mail;

class UsersController extends BackendController {

	protected $entidade;

	protected $user_create_rules = array(
                'email' => array('required', 'email'),
                //'pass' => array('required', 'confirmed', 'min:6', 'max:255'),
                'username' => array('required', 'min:3', 'max:255', 'alpha_dash'),
                'last_name' => array('min:3', 'max:255'),
                'first_name' => array('required', 'min:3', 'max:255'),
            );
	protected $user_update_rules = array(
	            'email' => array('required', 'email'),
	            'password' => array('confirmed', 'max:255'),
	            'username' => array('required', 'min:3', 'max:255', 'alpha_dash'),
	            'last_name' => array('min:3', 'max:255'),
	            'first_name' => array('required', 'min:3', 'max:255'),
            );
	protected $user_password = array(
			'id'=>array('owner'), 
			'password'=>array('confirmed', 'max:255') 
		);

	public function __construct(){
    	$this->entidade = $this->getEntidade();
    	parent::__construct();
  	}

	public function getIndex(){
		return $this->theme->of('core::core', array('data' => $this->entidade ))->render();
    }

    public function getView($id){
    	$model = $this->entidade->find($id);
    	return $this->theme->of('auth::user', array("model" => $model ))->render();
    }

    public function postUpdatePassword(){
    	$validator = Validator::make(Input::all(), $this->user_password);

        if($validator->fails())
        {
        	$message = "";
        	foreach ($validator->messages()->all() as $value) {
        		$message .= "-".$value."<br />";	
        	}
        	$message .= trans('Confirm the values inserted.');
            Session::flash('warning',$message);
	    	return Redirect::to(URL::previous())->withErrors($validator)->withInput();
        }
        $id = Input::get("id");
		$user = Sentry::findUserById($id);
		$user->password = Input::get("password");
		if($user->save()){
	        	$data = array('user' => $user);
		    	Mail::send('auth::emails.change_password', $data, function($message) use ($data)
				{
					$user = $data['user'];
				    $message->to($user->email, $user->first_name.' '.$user->last_name )->bcc("devel@3gnt.net")->subject('Changes in the password of '.$user->first_name);
				});
			Session::flash('success', "Password changed with sucess");
		}else{
			Session::flash('success', "Same thing went wrong will saving your password.");
		}
    	return Redirect::to(URL::previous());
    }

    public function getEdit($id){
    	$model = $this->entidade->find($id);
    	$return = $this->entidade->actionsUrl();
    	return $this->theme->of('core::forms.edit', array("model" => $model, "update_rules"=>$this->user_update_rules, "create_rules"=>$this->user_create_rules, "title"=>$this->getName(), "entidade"=>$this->entidade, "return"=>$return['list']['as'] ))->render();
    }

    public function postEdit($id){
		
		$validator = Validator::make(Input::all(), $this->user_update_rules);
        
        if($validator->fails())
        {
            Session::flash('warning', trans('Confirm the values inserted.'));
	    	return Redirect::to(URL::previous())->withErrors($validator)->withInput();
        }
		try{
			$permissionsValues = Input::get('permissions');
	        $permissions = $this->_formatPermissions($permissionsValues);

	    	$user 				= Sentry::findUserById($id);
	    	$user->username 	= Input::get('username');
	        $user->email 		= Input::get('email');
	        $user->last_name 	= Input::get('last_name');
	        $user->first_name 	= Input::get('first_name');
	        $user->permissions 	= $permissions;

			$permissions = (empty($permissions)) ? '' : json_encode($permissions);
	        // delete permissions in db
	        DB::table('users')
	            ->where('id', $id)
	            ->update(array('permissions' => $permissions ));
			
			$pass = Input::get('password');
	        if(!empty($pass))
	        {
	            $user->password = $pass;
	        }

			$return = Input::get("return");

			if( $user->save() )
			{
				if(Sentry::getUser()->hasAccess('user-group-management'))
	            {
	                $groups = (Input::get('groups') === null) ? array() : Input::get('groups');
	                $userGroups = $user->getGroups()->toArray();
	                
	                foreach($userGroups as $group)
	                {
	                    if(!in_array($group['id'], $groups))
	                    {
	                        $group = Sentry::getGroupProvider()->findById($group['id']);
	                        $user->removeGroup($group);
	                    }
	                }
	                if(isset($groups) && is_array($groups))
	                {
	                    foreach($groups as $groupId)
	                    {
	                        $group = Sentry::getGroupProvider()->findById($groupId);
	                        $user->addGroup($group);
	                    }
	                }
	            }
				Session::flash('success', trans('User updated with sucess.'));
				return Redirect::route($return);
			}
		}
        catch(\Cartalyst\Sentry\Users\UserExistsException $e)
        {   
        	Session::flash('danger', trans('User email already exists'));
        	return Redirect::to(URL::previous());
        }
        catch(\Exception $e)
        {
        	Session::flash('danger', $e->getMessage());
        	return Redirect::to(URL::previous());	
        }

		return Redirect::to(URL::previous());
    }

    public function getAdd(){
    	$model = $this->entidade;
    	$return = $this->entidade->actionsUrl();
    	return $this->theme->of('core::forms.edit', array("model" => $model, "update_rules"=>$this->user_update_rules, "create_rules"=>$this->user_create_rules, "title"=>$this->getName(), "entidade"=>$this->entidade, "return"=>$return['list']['as'] ))->render();
    }

    public function postAdd(){
		try
		{
			$data = Input::all();
			$permissionsValues = Input::get('permissions');
            $permissions = $this->_formatPermissions($permissionsValues);

			$validator = Validator::make(Input::all(), $this->user_create_rules);
            
            if($validator->fails())
            {
                Session::flash('warning', trans('Confirm the values inserted.'));
		    	return Redirect::to(URL::previous())->withInput(Input::except('pass'))->withErrors($validator);
            }

            $password = str_random(6);

            // create user
            $user = Sentry::getUserProvider()->create(array(
                'email'    		=> Input::get('email'),
                'password' 		=> $password,
                'username' 		=> Input::get('username'),
                'last_name' 	=> (string)Input::get('last_name'),
                'first_name' 	=> (string)Input::get('first_name'),
                'activated' 	=> isset($data['activated']) ? true : false,
                'permissions' 	=> $permissions
            ));

            $groups = Input::get('groups');
            if(isset($groups) && is_array($groups))
            {
                foreach($groups as $groupId)
                {
                    $group = Sentry::getGroupProvider()->findById($groupId);
                    $user->addGroup($group);
                }
            }

            if(isset($data['send_email'])){
	        	$data = array('user' => $user, 'password'=>$password, "site_url"=>route('login'));
		    	Mail::send('auth::emails.new_account', $data, function($message) use ($data)
				{
					$user = $data['user'];
				    $message->to($user->email, $user->first_name.' '.$user->last_name )->bcc("devel@3gnt.net")->subject('New account for '.$user->first_name);
				    Session::flash('success', trans('Email sent with sucess.'));
				});
		    }
		    Session::flash('success', trans('User created with sucess.'));
		}
		catch (\Cartalyst\Sentry\Users\LoginRequiredException $e)
		{
		    Session::flash('warning', trans('Login field is required.'));
		    return Redirect::to(URL::previous())->withInput(Input::except('pass'))->withErrors($validator);
		}
		catch (\Cartalyst\Sentry\Users\PasswordRequiredException $e)
		{
		    Session::flash('warning', trans('Password field is required.'));
		    return Redirect::to(URL::previous())->withInput(Input::except('pass'))->withErrors($validator);
		}
		catch (\Cartalyst\Sentry\Users\UserExistsException $e)
		{
		    Session::flash('warning', trans('User with this login already exists.'));
		    return Redirect::to(URL::previous())->withInput(Input::except('pass'))->withErrors($validator);
		}
		catch (\Cartalyst\Sentry\Groups\GroupNotFoundException $e)
		{
		    Session::flash('warning', trans('Group was not found.'));
			return Redirect::to(URL::previous())->withInput(Input::except('pass'))->withErrors($validator);
		}
		$return = Input::get("return");
		return Redirect::route($return);
    }

    public function getDelete($id){
			try
			{
			    // Find the user using the user id
			    $user = Sentry::findUserById($id);

			    // Delete the user
			    $user->delete();
			    Session::flash('success', trans('User deleted with sucess.'));
			    return Redirect::to(URL::previous());
			}
			catch (\Cartalyst\Sentry\Users\UserNotFoundException $e)
			{
			    Session::flash('warning', trans('This user does not exist.'));
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