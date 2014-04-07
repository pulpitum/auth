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
