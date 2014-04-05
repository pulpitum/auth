<?php
	Route::get('/login', array('as' => 'login',  'uses' => 'Dev3gntw\Auth\Controllers\IndexController@getLogin'));
	Route::post('/login', array('as' => 'postLogin',  'uses' => 'Dev3gntw\Auth\Controllers\IndexController@postLogin', "before"=>"csrf"));

	Route::get('/logout', array('as' => 'logout',  'uses' => 'Dev3gntw\Auth\Controllers\IndexController@getLogout'));

	Route::get('/recover', array('as' => 'recover',  'uses' => 'Dev3gntw\Auth\Controllers\IndexController@getRecover'));
	Route::post('/recover', array('as' => 'postRecover',  'uses' => 'Dev3gntw\Auth\Controllers\IndexController@postRecover', "before"=>"csrf"));
	
	Route::get('/reset', array('as' => 'reset',  'uses' => 'Dev3gntw\Auth\Controllers\IndexController@getReset'));
	Route::post('/reset', array('as' => 'postReset',  'uses' => 'Dev3gntw\Auth\Controllers\IndexController@postReset', "before"=>"csrf"));
?>