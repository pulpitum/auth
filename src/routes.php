<?php
	Route::get('/login', array('as' => 'login',  'uses' => 'Pulpitum\Auth\Controllers\IndexController@getLogin'));
	Route::get('/admin/login', array('as' => 'login',  'uses' => 'Pulpitum\Auth\Controllers\IndexController@getLogin'));
	
	Route::post('/login', array('as' => 'postLogin',  'uses' => 'Pulpitum\Auth\Controllers\IndexController@postLogin', "before"=>"csrf"));
	Route::post('/admin/login', array('as' => 'postLogin',  'uses' => 'Pulpitum\Auth\Controllers\IndexController@postLogin', "before"=>"csrf"));

	Route::get('/logout', array('as' => 'logout',  'uses' => 'Pulpitum\Auth\Controllers\IndexController@getLogout'));

	Route::get('/recover', array('as' => 'recover',  'uses' => 'Pulpitum\Auth\Controllers\IndexController@getRecover'));
	Route::post('/recover', array('as' => 'postRecover',  'uses' => 'Pulpitum\Auth\Controllers\IndexController@postRecover', "before"=>"csrf"));
	
	Route::get('/reset', array('as' => 'reset',  'uses' => 'Pulpitum\Auth\Controllers\IndexController@getReset'));
	Route::post('/reset', array('as' => 'postReset',  'uses' => 'Pulpitum\Auth\Controllers\IndexController@postReset', "before"=>"csrf"));
?>