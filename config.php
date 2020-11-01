<?php

/*Server setting */
// Report all errors
error_reporting(0);

// Same as error_reporting(E_ALL);
ini_set("error_reporting", 0);


// Set to true for localserver
$local = false;


if ($local) {
    define("DBHOST", "Localhost");
    define("DBUSER", "root");
    define("DBPASSWORD", "");
    define("DBDATABASE", "portfolio");
}
//Real database info
else{
    define("DBHOST", "test");
    define("DBUSER", "test");
    define("DBPASSWORD", "test");
    define("DBDATABASE", "test");
}

