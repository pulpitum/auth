<?php 

namespace Pulpitum\Auth;

use Illuminate\Support\ServiceProvider;
use Menu;
use Sentry;
use URL;


class AuthServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('pulpitum/auth');
		include __DIR__.'/../../filters.php';
		include __DIR__.'/../../routes.php';

		if(class_exists("Menu") and Sentry::check()){
			Menu::addItem( array( 'text' => '<i class="icon-cog"></i> Account <b class="caret"></b>', 'URL' => '#', 'reference'=>"account", 'weight' => 0, 'hasChilds'=>true ) )->toMenu( 'profile' );
			Menu::addItem( array( 'text' => 'Settings', 'URL' => 'javascript:;', 'reference'=>"settings", 'parent'=>"account", 'weight' => 0, 'hasChilds'=>true ) )->toMenu( 'profile' );
			Menu::addItem( array( 'text' => 'Help', 'URL' => 'javascript:;', 'reference'=>"help", 'parent'=>"account", 'weight' => 0, 'hasChilds'=>true ) )->toMenu( 'profile' );
			
			Menu::addItem( array( 'text' => '<i class="icon-user"></i>'.Sentry::getUser()->firstname.' <b class="caret"></b>', 'URL' => '#', 'reference'=>"auth", 'weight' => 1, 'hasChilds'=>true ) )->toMenu( 'profile' );
			Menu::addItem( array( 'text' => 'Profile', 'URL' => URL::route('profile'), 'reference'=>"profile", 'parent'=>"auth",'weight' => 0, 'hasChilds'=>true ) )->toMenu( 'profile' );
			Menu::addItem( array( 'text' => 'Logout', 'URL' => URL::route('logout'), 'reference'=>"logout", 'parent'=>"auth", 'weight' => 1, 'hasChilds'=>true ) )->toMenu( 'profile' );
			Menu::setMenuType('bootstrap', 'profile', 'Pulpitum\Auth\Menu', "nav pull-right");
		}
		

	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$searchable = array("users"=>"Pulpitum\Auth\Models\Master\Users");
		if(!isset($this->app['searchable'])){
			$this->app['searchable'] = $searchable;
		}else{
			$this->app['searchable'] = array_merge($this->app['searchable'], $searchable);
		}
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}
