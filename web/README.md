
List of libraries used:
-------------------------

**Symfony - Dispatcher, Routing**
I use some Symfony libs to impement the basic core of a Restfull Server, i choose symfony libs because most of the modules are unplugged.

**Firebase - JWT** 
 I use this lib to implement the authentication of some services, I choose this lib because it's simple and unplugged and widely used.

**Doctrine - DBAL**
I choose this lib because is a powerful database abstraction layer, simple and unplugged.

**Pimple - Dependency Injection** 
 I choose this lib because is a small Dependency Injection Container for PHP.

**Monolog - Log**
I choose this because is a established and simple lib for logs.

**PHP UNIT / GUZZLE - TESTS**
I choose use GUZZLE with PHPUNIT, I use GUZZLE  as HTTPCLIENT to test my endpoints, it's a simple and powerful lib.
Another change I would do, is use a separate database just to run the tests.

**APIDOCJS - Documentation API**
I Choose this js lib because it's so simple to install and useful for create apis docs according to block comments.
I decide not use phpdocumentor, because it generate a lot of code and has lots of dependencies.

**Next Steps:**
----------------
I didn't have time to do all the things i wanted in the project. Next steps:

- I would implement some kind of migration script. Ex. Doctrine/Migration LIB
- Create a script to install the application in one line
- Make more Tests, to coverage all the code.
- Use a separated database to run Unit Tests.

Improve the following points:
- DependencyInjection
- Use environment variables to save some string configurations.
