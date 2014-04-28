<?php
	Route::get('/login', 		array('as' => 'login',  'uses' => 'Pulpitum\Auth\Controllers\IndexController@getLogin'));
	Route::get('/admin/login', 	array('as' => 'loginAdmin',  'uses' => 'Pulpitum\Auth\Controllers\IndexController@getLogin'));
	
	Route::post('/login', 		array('as' => 'postLogin',  'uses' => 'Pulpitum\Auth\Controllers\IndexController@postLogin', "before"=>"csrf"));
	Route::post('/admin/login', array('as' => 'postLoginAdmin',  'uses' => 'Pulpitum\Auth\Controllers\IndexController@postLogin', "before"=>"csrf"));

	Route::get('/logout', 		array('as' => 'logout',  'uses' => 'Pulpitum\Auth\Controllers\IndexController@getLogout'));

	Route::get('/recover', 		array('as' => 'recover',  'uses' => 'Pulpitum\Auth\Controllers\IndexController@getRecover'));
	Route::post('/recover', 	array('as' => 'postRecover',  'uses' => 'Pulpitum\Auth\Controllers\IndexController@postRecover', "before"=>"csrf"));
	
	Route::get('/reset', 		array('as' => 'reset',  'uses' => 'Pulpitum\Auth\Controllers\IndexController@getReset'));
	Route::post('/reset', 		array('as' => 'postReset',  'uses' => 'Pulpitum\Auth\Controllers\IndexController@postReset', "before"=>"csrf"));

	Route::get('/admin/profile', 	array('as' => 'profile',  'uses' => 'Pulpitum\Auth\Controllers\Admin\UsersController@getProfile',"before"=>"basicAuth"));
	Route::get('user', 				array('as' => 'user_profile',  'uses' => 'Pulpitum\Auth\Controllers\IndexController@getUser',"before"=>"basicAuth"));


	if(class_exists("Sentry"))
		$list = Sentry::findAllUsers();
	else{
		$users = new \Pulpitum\Auth\Models\Master\Users;
		$list = $users->where("activated",1)->get();
	}
	foreach ($list as $user) {
		if($user->activated == 0)
			continue;
		$slug = S::dasherize($user->identifier);
		Route::get($slug, array('as' => "user_".$user->id, 'uses' => 'Pulpitum\Auth\Controllers\IndexController@getPublicUserProfile'));
	}
?>