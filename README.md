
PROJECT 
==============

Simple Recipes API.

Where you can: List, Create, Read, Update, Delete, Rate and Search Recipes

Recipes can be rated many times from 1-5.



API Documentation
------------------

http://apidoc.animon.com.br/
------------------



Services List
------------------

| Name   | Method      | URL                    | Protected |
| ---    | ---         | ---                    | ---       |
| List   | `GET`       | `/recipes`             | ✘         |
| List (pagination)  | `GET`       | `/recipes/page/{page}/per/{per}`             | ✘         |
| Create | `POST`      | `/recipes`             | ✓         |
| Get    | `GET`       | `/recipes/{id}`        | ✘         |
| Update | `PUT/PATCH` | `/recipes/{id}`        | ✓         |
| Delete | `DELETE`    | `/recipes/{id}`        | ✓         |
| Search   | `POST`      | `/recipes/search` | ✓         |
| Rate   | `POST`      | `/rate/{recipeId}/{rate}` | ✘         | 
| List Rates   | `GET`      | `/rates` | ✘         | 
| List Rate (Pagination)   | `GET`      | `/rates/page/{page}/per/{per}` | ✘         | 

This is a REST application PHP:
        
List of libraries used:
    
    Symfony - Dispatcher, Routing
    Firebase - JWT 
    Doctrine - DBAL
    Pimple - Dependency Injection 
    Monolog - Log
    PHP UNIT / GUZZLE - TESTS
    APIDOCJS - Documentation API
    

INSTALLING
==============

1 - Clone this repository.
- Checkout the branch called `dev`

        cd Docker/fernandohs1500-api-test/web
        git checkout -b dev
        
- Run `docker-compose up -d` to start the development environment.  
                

2 - Run composer 

    $ cd Docker/fernandohs1500-api-test/web
    $ composer install

- Visit `http://localhost` to see the contents of the web container and develop your application.

2 - Create Database Tables:

- [DDL_HELLOFRESH.TXT](https://github.com/fernandohs1500/todolist/files/2569632/DDL_HELLOFRESH.TXT)
        

 Obs: If you have some problem with nginx, Checkout the nginx config file and change if necessary:

    cd /etc/nginx/sites-avaliable/default 
    root /server/http/web/web
    

Comands
==============

Unit Test:
    
    $ cd Docker/fernandohs1500-api-test/web
    $ ./vendor/bin/phpunit --bootstrap vendor/autoload.php Test/
    
Generate API/DOC
    
    $ cd Docker/fernandohs1500-api-test
    $ apidoc -i web/src/Recipes/Controller/ -o api-doc/


DATABASE RELATIONSHIP
----------------------------

Tables:
    
    recipe
          id,name,prep_time,difficult,bol_vegetaria
    rate
          id,recipe_id,rate
    user
          id,login,passwd,date_create,nickname 
     

DDL SQL FILE: [DDL_HELLOFRESH.TXT](https://github.com/fernandohs1500/todolist/files/2569632/DDL_HELLOFRESH.TXT)

Example Usage
==============

    First we will get a list of all recives.
![1](https://user-images.githubusercontent.com/1281429/48314964-05de5480-e5b8-11e8-965e-562c1126d2f6.png)

    Oops! It has no recipes, let's Crete!
![2](https://user-images.githubusercontent.com/1281429/48314965-05de5480-e5b8-11e8-8235-51e16d683cc5.png)

    Creating a recipe:
![3](https://user-images.githubusercontent.com/1281429/48314966-05de5480-e5b8-11e8-9e0e-f74105811a5a.png)

    In order to create, we need to inform a token to create a recipe :
![4](https://user-images.githubusercontent.com/1281429/48314967-05de5480-e5b8-11e8-9860-10824cc9f6a0.png)

    Let's get a Token Authorization
![5](https://user-images.githubusercontent.com/1281429/48314968-0676eb00-e5b8-11e8-9826-10b3eccd0ea0.png)
![6](https://user-images.githubusercontent.com/1281429/48314969-0676eb00-e5b8-11e8-9227-016067121b09.png)

    Let's Create a recipe with token auth
![7](https://user-images.githubusercontent.com/1281429/48314970-070f8180-e5b8-11e8-8282-647a36d6b405.png)
Recipe Created! Recipe's ID : 2
![8](https://user-images.githubusercontent.com/1281429/48314971-070f8180-e5b8-11e8-996a-fcbee08d4aeb.png)

    Get a list of all recives (Without pagination)
![9](https://user-images.githubusercontent.com/1281429/48314972-070f8180-e5b8-11e8-8f50-b3939eb118f1.png)
![10](https://user-images.githubusercontent.com/1281429/48314973-07a81800-e5b8-11e8-9c7f-b0e738a7fc35.png)

    In order to manage the list of recipes, you can use pagination.
![11](https://user-images.githubusercontent.com/1281429/48314974-07a81800-e5b8-11e8-8b5b-e58e0172444b.png)
Result:
![12](https://user-images.githubusercontent.com/1281429/48314975-07a81800-e5b8-11e8-9719-61c4983cb736.png)

    Do you want a specific recipe? Getting a specific recipe:
![13](https://user-images.githubusercontent.com/1281429/48314977-0840ae80-e5b8-11e8-9c63-448a0e7e4400.png)
   Result:
![14](https://user-images.githubusercontent.com/1281429/48314978-0840ae80-e5b8-11e8-94bf-436e1e566202.png)

    Do you want to Update a Recipe? You can!
![15](https://user-images.githubusercontent.com/1281429/48314979-0840ae80-e5b8-11e8-8059-053081406541.png)
Result:
![16](https://user-images.githubusercontent.com/1281429/48314980-0840ae80-e5b8-11e8-9dae-640f1192ac3a.png)

    Delete a recipe:
![17](https://user-images.githubusercontent.com/1281429/48314981-08d94500-e5b8-11e8-978d-c1aa229ce3ea.png)
Result:
![18](https://user-images.githubusercontent.com/1281429/48314982-08d94500-e5b8-11e8-817c-0d0e178961a5.png)

    Let's rate a recipe!
![19](https://user-images.githubusercontent.com/1281429/48314983-08d94500-e5b8-11e8-909d-eb20eceac154.png)
Result:
![20](https://user-images.githubusercontent.com/1281429/48314984-0971db80-e5b8-11e8-9d56-0fc6299333b4.png)

    List of all Rates (Without pagination)
![21](https://user-images.githubusercontent.com/1281429/48314985-0971db80-e5b8-11e8-8937-42edf0475566.png)
Result:
![22](https://user-images.githubusercontent.com/1281429/48314986-0a0a7200-e5b8-11e8-9db0-10373e093f9d.png)

    In order to manage the list of rates, you can use pagination.
![23](https://user-images.githubusercontent.com/1281429/48314987-0a0a7200-e5b8-11e8-9bba-7139e7524452.png)
Result:
![24](https://user-images.githubusercontent.com/1281429/48314988-0a0a7200-e5b8-11e8-8e46-a1b900ec9599.png)

    Looking for some recipe? you can do a search
![25](https://user-images.githubusercontent.com/1281429/48314989-0aa30880-e5b8-11e8-8203-506b59a4e105.png)
Result:
![26](https://user-images.githubusercontent.com/1281429/48314990-0aa30880-e5b8-11e8-9052-907663968a37.png)


META
----------------------------

Fernando Henrique – [@fernandohs1500](https://www.linkedin.com/in/fernandohs1500/) – fernandimgts@gmail.com

[https://github.com/fernandohs1500/](https://github.com/fernandohs1500/)