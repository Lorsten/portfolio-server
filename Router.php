<?php

include_once('RequestClass.php');
// Class for creating diferent routes based on HTTPMethod also calling requestClass in the constructor 
class Router
{

  private $request;
  private $route;
  // private array for all the HTTPMethod used
  private $HTTPMethods = array(
    'GET',
    'POST',
    'PUT',
    'DELETE'
  );

  // Call the class requestClass in the constructor to get  $_SERVER['REQUEST_METHOD'] and store it in the propertie request
  function __construct(requestClass $request)
  {
    $this->request = $request;
  }
  /*
   Using a magic function to create the route with the $name property from $HTTPMethods followed by the $arguments
   for example $router->get 
   */
  function __call($name, $arguments)
  {

    // Using list to split the params into an array, $name and the $arguments
    list($this->route, $method) = $arguments;

    // Check if the $name exist in the array of supported Methods
    if (!(in_array(strtoupper($name), $this->HTTPMethods))) {
      return http_response_code(405);
    }
    // if the name matches the HTTPMethod. Using strtoupper to match the __call name 
    if (strtoupper($name) == $this->request->request) {

      //Creating an array with the route as the index name and storing the objects 
      $this->{strtolower($name)}[$this->route] = $method;
    }
  }
  // Method for displaying error if this method is called
  function errorDisplay()
  {
    http_response_code(405);
  }
  /*
  Method for breaking up the url with explode to only show whats after the last /
  Using parse_url $_SERVER['REQUEST_URI'] to get the url

   */
  function seturl(){
    $url = parse_url($_SERVER['REQUEST_URI']);
    $result = explode('.php', $url['path']);
    return$result[1];
  }

  //Method for formating the rotue using rtrim
  private function formatRoute($route)
  {
    $result = rtrim($route, '/');
    if ($result === '') {
      return '/';
    }
    return $result;
  }
  // Resolve method
  function resolve()
  {
    /* Get the HTTPMethod name to match the created array in the __call method
       Get the url from the seturl method
       Create a variable with the METHOD(post for example) the url as it's index post['/']

    */
    $methodDictionary = $this->{strtolower($this->request->request)};
    $formatedRoute = $this->formatRoute($this->seturl());
    if(isset($methodDictionary[$formatedRoute])){
      $method = $methodDictionary[$formatedRoute];

    }
    else{
      $method = null;
    }
  

    // Check if the variable is not null. Call the method errordisplay for displaying error
    if (is_null($method)) {
      $this->errorDisplay();
      return;
    }
    // using the call_user_func_array to call a callaback with an array of params. Calling first the propert $methog followed by an array of class RequestClass with the request property.
    echo call_user_func_array($method,array($this->request));
  }

  // Call the method resolve in the destructor 
  function __destruct()
  {
    $this->resolve();
  }
}

