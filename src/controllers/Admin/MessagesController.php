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
use View;
use Mail;

class MessagesController extends BackendController {

	protected $entidade;
	protected $rules = array( 'title' => array('required', 'min:3'), 'id_user'=>array("required"), 'message'=>array("required") );

	public function __construct(){
    	$this->entidade = $this->getEntidade();
    	parent::__construct();
  	}
	
	public function getIndex(){
		return $this->theme->of('core::core', array('data' => $this->entidade ))->render();
    }

    public function getView($id){
    	$model = $this->entidade->find($id);
    	return View::make('auth::message', array("model" => $model ))->render();
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

    public function getAjaxAdd(){
        $model = $this->entidade;
        $return = $this->entidade->actionsUrl();
        return View::make('core::forms.ajax', array("model" => $model,"update_rules"=>$this->rules, "create_rules"=>$this->rules, "title"=>$this->getName(), "entidade"=>$this->entidade, "return"=>isset($return['list']['as']) ? $return['list']['as'] : "" ))->render();
    }

    function postAjaxAdd(){
 
        //Valida os dados inseridos não existem.
        $primaryKey = $this->entidade->getPrimaryKey();
        $entidade = null;
        if(is_array($primaryKey)){
            $primaryKeyValues = array();
            foreach ($primaryKey as $key => $value) {
                $get = Input::get($value);
                if( !empty($get) )
                    $primaryKeyValues[] = $get;
            }

            if(count($primaryKeyValues) == count($primaryKey) )
                $entidade = $this->entidade->find($primaryKeyValues)->first();
        }else{
            $entidade = $this->entidade->find(Input::get($primaryKey));
        }

        if( is_null($entidade) or (isset($entidade) && $entidade->count() == 0) ){
            $entidade = $this->entidade;
        }

        $validator = Validator::make(Input::all(), $this->rules);
        
        if($validator->fails())
        {
            $response = array(
                'status' => 'error',
                'msg' => trans('lactiweb::form.error')." ".$validator->messages(),
            );
            return Response::json( $response );
        }
        
        $data = Input::all();
        $data["author"] = Sentry::getUser()->id;
        $entidade->fill($data);
        try{
            if( $entidade->save() )
            {
                $user = Sentry::findUserById($entidade->id_user);
                $data = array('user' => $user, "site_url"=>route('login'));
                Mail::send('auth::emails.newmessage', $data, function($message) use ($data)
                {
                    $user = $data['user'];
                    $message->to($user->email, $user->first_name.' '.$user->last_name )->bcc("devel@3gnt.net")->subject('Nova Mensagem');
                });
                $response = array(
                    'status' => 'success',
                    'msg' => trans('lactiweb::form.save-success'),
                );
            }else{
                $response = array(
                    'status' => 'error',
                    'msg' => trans('lactiweb::form.try'),
                );
            }
        }catch(\Illuminate\Database\QueryException $e){
            $response = array(
                'status' => 'error',
                'msg' => trans('lactiweb::form.try')." ".$e->getMessage(),
            );
        }
        return Response::json( $response );
    }

}

?>