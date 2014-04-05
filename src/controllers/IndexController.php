<?php namespace Pulpitum\Auth\Controllers;

use Pulpitum\Core\Controllers\FrontendController as FrontendController;
use Input;
use Sentry;
use Redirect;
use Config;
use Response;
use DB;
use Request;
use Route;
use Theme;
use Validator;
use Url;
use Mail;
use Session;

class IndexController extends FrontendController {


	protected $login_rules = array('pass'  => 'required|min:6|max:255', 'email' => 'required|email');
	protected $reset_rules = array('pass'  => 'required|min:6|max:255|confirmed', 'code' => 'required');

    /**
    * Login page
    */
    public function getLogin()
    {
        return $this->theme->of('auth::login')->render();
    }

    /**
    * Login post authentication
    */
    public function postLogin()
    {
        try
        {

            $validator = Validator::make(Input::all(), $this->login_rules);

            if($validator->fails())
            {
                Session::flash('warning', trans('auth::messages.login_failed'));
		    	return Redirect::route('login')->withInput();
            }

            $credentials = array(
                'email'    => Input::get('email'),
                'password' => Input::get('pass'),
            );

            // authenticate user
            Sentry::authenticate($credentials, Input::get('remember'));
        }
        catch(\Cartalyst\Sentry\Throttling\UserBannedException $e)
        {
		    Session::flash('danger', trans('auth::messages.login_banned'));
		    return Redirect::route('login')->withInput();
        }
        catch (\RuntimeException $e)
        {
			Session::flash('warning', trans('auth::messages.login_failed'));
		    return Redirect::route('login')->withInput();
        }
        $url = Session::get('attemptedUrl');
        if(!isset($url))
        {
            $url = URL::route('home');
        }
        Session::forget('attemptedUrl');
        return Redirect::to($url);
    }

    /**
    * Logout user
    */
    public function getLogout()
    {
        Sentry::logout();
		Session::flash('success', trans('auth::messages.logout'));
        return Redirect::route('login');
    }

    /**
    * Access denied page
    */
    public function getAccessDenied()
    {
		Session::flash('success', trans('auth::messages.access_denied'));
        return Redirect::route('dash');
    }

    /**
     * getRecover
     * 
     * @access public
     *
     * @return mixed Value.
     */
    public function getRecover()
    {
    	$this->theme = Theme::uses('frontend')->layout('logout');
        return $this->theme->of('auth::recover')->render();
    } 

    public function postRecover()
    {
		try
		{
		    // Find the user using the user email address
		    $user = Sentry::findUserByLogin(Input::get('email'));

		    // Get the password reset code
		    $resetCode = $user->getResetPasswordCode();

		    // Now you can send this code to your user via email for example.
		    $recoverUrl = Url::route('reset', array('code'=>$resetCode));
        	$data = array('user' => $user, 'recoverUrl' => $recoverUrl);
	    	Mail::send('auth::emails.recover', $data, function($message) use ($data)
			{
				$user = $data['user'];
			    $message->to(Input::get('email'), $user->first_name.' '.$user->last_name )->subject('Forgoten Password');
			});		    
		}
		catch (\Exception $e)
		{
		    Session::flash('warning', trans('auth::messages.recover_email_error'));
		    return Redirect::route('recover');
		}

		Session::flash('success', trans('auth::messages.recover_email_sucess'));
    	return Redirect::route('login');
    }        

    /**
     * getReset
     * 
     * @access public
     *
     * @return mixed Value.
     */
    public function getReset()
    {

		try{
			if(Input::get('code') == '')
				throw new \Exception("error", 1);
			
		    // Find the user using the user id
		    $user = Sentry::findUserByResetPasswordCode(Input::get('code'));

		}
		catch (\Exception $e)
		{
		    Session::flash('warning', trans('auth::messages.reset_code_error'));
		    return Redirect::route('recover');
		}  

    	$this->theme = Theme::uses('frontend')->layout('logout');
        return $this->theme->of('auth::reset')->render();
    } 

    public function postReset()
    {

		$validator = Validator::make(Input::all(), $this->reset_rules);

        if($validator->fails())
        {
            Session::flash('warning', trans('auth::messages.reset_validation'));
	    	return Redirect::route('reset', array('code' => Input::get('code')));
        }    	

		try
		{    	
   			// Find the user using the user id
		    $user = Sentry::findUserByResetPasswordCode(Input::get('code'));
			// Check if the reset password code is valid
		    if ($user->checkResetPasswordCode(Input::get('code')))
		    {
		        // Attempt to reset the user password
		        if ($user->attemptResetPassword(Input::get('code'), Input::get('pass')))
		        {
		        	$data = array('user' => $user);
			    	Mail::send('auth::emails.reset', $data, function($message) use ($data)
					{
						$user = $data['user'];
					    $message->to($user->email, $user->first_name.' '.$user->last_name )->subject('Password Alterada com sucesso');
					});

		            Session::flash('success', trans('auth::messages.reset_sucess'));
			    	return Redirect::route('login');
		        }
		        else
		        {
		            Session::flash('warning', trans('auth::messages.reset_code_pass'));
			    	return Redirect::route('recover');
		        }
		    }
		    else
		    {
	            Session::flash('warning', trans('auth::messages.reset_code_error'));
		    	return Redirect::route('recover');
		    }

		}catch (\Exception $e)
		{
		    Session::flash('warning', trans('auth::messages.reset_code_error'));
		    return Redirect::route('recover');
		}
    }

}