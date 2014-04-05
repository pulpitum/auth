<?php 

namespace Pulpitum\Auth;

use Illuminate\Support\ServiceProvider;
use Menu;


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
		
		//Menu::addItem( array( 'text' =>  trans('auth::core.auth'), 'URL' => '#', 'reference'=>"auth", 'parent' => 'configuration', 'weight' => 2, 'hasChilds'=>true ) )->toMenu( 'admin' );
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		
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
