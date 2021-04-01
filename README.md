# BileMo
This project was made to validate my training as a web PHP/Symfony developer.  
The fake company BileMo provides to other platforms its catalog of high quality mobile phones.  
Only users registered can access to API features.  
The APi also offers its users the opportunity to consult and manage their customers' data.  
The requests require a JSON format.  

## Getting start
### Prerequisites

Installation of BileMo API requires:

  *  Symfony 5.2.4
  *  PHP version 7.4.12
  *  MySQL version 5.7
  *  Apache Server 2.4.46
  *  Composer 2.0.10
  *  Doctrine/ORM 2.8.2

Dependency used for the project:
  *  zircote/swagger-php 3.1
  *  jms/serializer-bundle: 3.9.1
  *  willdurand/hateoas-bundle: 2.2
  *  lexik/jwt-authentication-bundle: 2.11.2

### Installation

 1. Copy the link on GitHub and clone it on your local repository
   ```git clone https://github.com/EdwigeGC/BileMo.git```
 2. Open your terminal and run:
   ``` composer install```
 3. Create database: 
  ```php bin/console doctrine:database:create```
 4. Open file .env and write username and password for DATABASE_URL:
```DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=5.7"```
 5. Fill the database with fixtures:
```php bin/console make:migration```
```php bin/console doctrine:migration:migrate```
```php bin/console doctrine:fixtures:load```

## Features

Only registered users can access to API's features:
  *  Get list of the product
  *  Get the details of a product
  *  Get the list of the customers linked to a user
  *  Get the details of a customer linked to a user
  *  Add a customer
  *  Delete a user
  *  Authenticate

## Test API

  1. Authenticate with the user test to generate a token (it will be valid during 1 hour):   
      Request: ```POST http://yourlocal/api/login_check```  
      Header:  
      ```Content-Type: application/json  ```   
      Boby:  
      ```{
           "username":"webshop@mail.fr",  
           "password":"pass1"  
         }```  
}
  2. Copy this token in Authorisation -> Type: Bearer Token  
  3. Now you can try a request! Check the documentation (link below)   


## Documentation   

http://localhost:8888/swagger/index.html
