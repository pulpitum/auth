auth
====

Pulpitum authentication package

To add the auth package, you have to add the folowing to the composer.json.

In Repositories:

    {
      "type": "vcs",
      "name": "pulpitum/auth",
      "url": "https://github.com/pulpitum/auth.git"
    }
    
And in the require

    "pulpitum/auth": "dev-master"
    
    
  
Then run

    composer update

The you have to insert the following into app/config/app.php
    'Pulpitum\Auth\AuthServiceProvider',
