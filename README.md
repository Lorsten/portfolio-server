# portfolio-server

## A REST-API server for retriving information for a portfolio page. The server is coded in PHP 

### The server used different routes for METHOD for

* index.php/work for the work table

* index.php/education for the education table

* index.php/website for the website table

### Each route allow POST, DELETE, PUT and GET. There's also a fourth route index.php/authenticate which only allows POST and is used for authenticating a userlogin

### To create a new account use the method createUser in the Auth class the method takes two params, username and password.

### To use the server change the database account details in the config.php file


#### Written by Olof Andersson


