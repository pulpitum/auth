<?php

Route::filter('basicAuth', function()
{
    if(!Sentry::check())
    {
        // save the attempted url
        Session::put('attemptedUrl', URL::current());

        return Redirect::route('login');
    }

    View::share('currentUser', Sentry::getUser());
});

Route::filter('notAuth', function()
{
    if(Sentry::check())
    {
        $url = Session::get('attemptedUrl');

        if(!isset($url))
        {
            $url = URL::route('dash');
        }
        Session::forget('attemptedUrl');

        return Redirect::to($url);
    }
});


Route::filter('hasPermissions', function($route, $request, $userPermission = null)
{
    if (Route::currentRouteNamed('EditUser') && Sentry::getUser()->id == Request::segment(3) ||
        Route::currentRouteNamed('PostEditUser') && Sentry::getUser()->id == Request::segment(3) ||
        Route::currentRouteNamed('GetUser') && Sentry::getUser()->id == Request::segment(2))
    {
    }
    else
    {
        if($userPermission === null)
        {
            $permissions = Config::get('auth::permissions');
            if(!isset($permissions[Route::current()->getName()])){
                Session::flash('warning', trans('auth::messages.access_denied'));
                return Redirect::route('dash');
            }
            $permission = $permissions[Route::current()->getName()];
        }
        else
        {
            $permission = $userPermission;
        }

        if(!Sentry::getUser()->hasAccess($permission))
        {
            Session::flash('warning', trans('auth::messages.access_denied'));
            return Redirect::route('dash');
        }
    }
});

Route::filter('csrf', function()
{
    if (Session::token() != Input::get('_token'))
    {
        Session::flash('danger', trans('auth::messages.form_incorrect'));
        return Redirect::to(URL::previous());
    }
});