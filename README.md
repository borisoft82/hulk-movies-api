
# Movies API Project

The Movie API project represents a database of movies that can be created by the user. The user has the possibility to register, log in and log out within the application. In addition to the above, the user can create a category and movie as well as filter categories and movies according to predefined parameters.

CORS middleware and JWT token are implemented on each API endpoint. Users have the possibility to manipulate only their articles of movies, ie. they do not have the possibility to manipulate the articles of other users' movies within their account.

Constants and Services are grouped by model or resource folders so that you can clearly see that approach to the organization of the structure while the other classes are in the same folder (Filters, Repositories, ...) for different models or resources and under different names as another possible approach.

Each user has the possibility to add his movie to his favorites, on the basis of which he can follow his favorite movies and also remove them from the list of favorites

- The project is based on some SOLID, DRY and KISS principles to keep the application scalable and stable for upgrades
- The Repository Pattern (Design Pattern) was used in order to manage the data access logic from a centralized location
- Service Classes were used to focus on managing specific business operations
- Enums and Constants were used for http status messages and log messages
- Observer was used as a central place where I've grouped event listeners for a particular model
- Policies were used to organize authorization logic around a particular model or resource
- Traits were used for reusing group of methods in different classes

### Exceptions (try catch blocks)
In order to avoid repetition of try-catch blocks inside controllers a global exception handler (App\Exceptions\Handler.php) is responsible for handling all exceptions. All exceptions are mapped as exception => method key value pair where every method handles its own exception.

### Policies
Regarding the authorization for certain resource routes I created my own way of applying policies, although Laravel has policies and gates that are applied as standard. I was a little creative :)

### Filters
The filters are made so that it is possible to search by combining:
- **Relations**
    - Along with resource search it's possible to include relation or relations related to that resource and have insight into data from relations
    - Rules
        - include=relation -> including only one relation
        - include=relation1,relation2 -> including more relations

    **Example:** http://localhost:8000/api/v1/movies/filter?include=user or http://localhost:8000/api/v1/movies/filter?include=user,category
- **Columns** (fields from database)
    - It's possible to filter by predifined columns for specific resource or model. It's also possible to filter by assigning multiple values ​​separated by a comma
    - Rules
        - filter[title]=\*keyword\* -> search for a keyword within a paragraph and return the entire paragraph (title, description, ...)
        - filter[title]=keyword -> search for existing keyword only 

    **Example:** http://localhost:8000/api/v1/movies/filter?include=user,category&filter[id]=1,3&filter[title]=*adipisci*
- **Sorting**
    - It's possible to sort every predifined sortable column with desc ans asc order. You just have to put the minus sign in front of value if you want descending order and remove it for ascending order.
    - Rules
        - sort=column -> sorting by ascending
        - sort=-column -> sorting by descending

    **Example:** http://localhost:8000/api/v1/movies/filter?include=user,category&filter[id]=1,3&filter[title]=*adipisci*&sort=-id
- **Date range** 
    - It's also possible to filter by date or between two dates. By filtering between two dates you just have to seperate dates by comma.
    - Rules
        - filter[createdAt]=date, filter[updatedAt]=date -> filtering by specific date
        - filter[createdAt]=date1,date2, filter[updatedAt]=date1,date2 -> filtering between two dates

    **Example:** http://localhost:8000/api/v1/movies/filter?include=user,category&filter[id]=1,3&filter[title]=*adipisci*&filter[createdAt]=2024-07-04,2024-07-06&sort=id

It's also possible to search movies for specific user.
**Example:** http://localhost:8000/api/v1/movies/1/filter?include=user&sort=-title

**NOTE!**
When defining values ​​for filters, set the values ​​based on the seeded data.

## Installation

Install.: 
XAMPP (https://www.apachefriends.org/), 
Composer (https://getcomposer.org/download/)

Create MySQL database in phpMyAdmin then open your CLI and run these commands one by one:

```bash
  git clone <project>

  composer install

  cp .env.example .env 
  
  (put your db credentials inside .env)

  php artisan key:generate

  php artisan jwt:secret

  php artisan migrate --seed

  php artisan serve


```
    
## Running PHPUnit Tests

Given that the application is simple and that it's about very simple functionalities the sqlite database was used for the purpose of testing.

To run tests, run the following command

```bash
  php artisan config:clear
  php artisan test

  or

  php artisan config:clear
  ./vendor/bin/phpunit
```
## Running Postman Tests

Refresh and seed database running the following command:

```bash
  php artisan cache:clear
```
Download Postman from https://www.postman.com/ then install and import environment and collections files from:

```bash
  ./z-postman-collections (folder is in the root of the project)
```
Inside your Postman choose <b>Movies API Reporting</b> environment and copy/paste the Bearer token (you will take token from response by user logging via collection <b>Auth -> Login user</b>) inside token variable row in the column <b>Current value</b> like below and <b>Save</b>:

```bash
  Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjgwMDAvYXBpL3YxL2xvZ2luIiwiaWF0IjoxNjg4MDM1MjgyLCJleHAiOjE2ODgwMzg4ODIsIm5iZiI6MTY4ODAzNTI4MiwianRpIjoiNFJwNUd1dGdJTWMzWjJ1MiIsInN1YiI6IjExIiwicHJ2IjoiMjNiZDVjODk0OWY2MDBhZGIzOWU3MDFjNDAwODcyZGI3YTU5NzZmNyJ9.uf7QsEz_vrreHbx-wZ4LE7Y0w0Mpu-25FK7K9jn6J1I
```

Now you can test every request from the collections just by clicking <b>Send</b> request button.