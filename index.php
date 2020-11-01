<?php
/*
header("Access-Control-Allow-Origin: *");


header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
*/
include_once('config.php');
include_once('Router.php');
include_once('RequestClass.php');

// included for creating a user account
include_once('Authenticate.php');


/*
Making an instance of the classes requestClass and Router
*/


$request = new requestClass();
$router = new Router($request);

//Make an instance of auth and call the method createUser to create an account
/*
$validate = new auth();
$validate->createUser("username","password");
*/
/* 
Routes for GET method for all tables
*/
$router->get('/website', function ($request) {
    $request->displayData("website","website_ID" );
});

$router->get('/work', function ($request) {
    $request->displayData("workplace","work_ID");
});

$router->get('/education', function ($request) {
    $request->displayData("education", "education_ID");
});

/* 
Routes for POST method for all the different tables
*/
$router->post('/work', function ($request) {
    $request->insertData('work');
});

$router->post('/education', function ($request) {
    $request->insertData('education');
});

$router->post('/website', function ($request) {
    $request->insertData('website');
});
/* 
Routes for UPDATE method for all the different tables
*/
$router->put('/website', function ($request) {
    $request->UpdateData('website');
});

$router->put('/education', function ($request) {
    $request->UpdateData('education');
});

$router->put('/work', function ($request) {
    $request->UpdateData('work');
});

/* 
Routes for DELETE method for all the different tables
*/

$router->delete('/website', function ($request) {
   $request->deleteData('website','website_ID');
});

$router->delete('/work', function ($request) {
    $request->deleteData('workplace','work_ID');
 });

 $router->delete('/education', function ($request) {
    $request->deleteData('education','education_ID');
 });
 



//Route for authenticating a login request
$router->post('/Authenticate', function () {
    $validate = new auth();
    $user = json_decode(file_get_contents("php://input"));
    if($validate->getUsername($user->username)){
        $validate->getPassword($user->password);
    }
     
});
