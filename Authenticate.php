<?php

// Class for authenticate the userlogin

include_once('mainClass.php');

class auth
{

    private $password;
    private $username;
    private $db;
    private $hasedPassword;
    private $data;

    public function __construct()
    {

        $this->db = new mainClass();
    }

    //Method for getting the password
    function getPassword($password)
    {
        $this->password = $password;
        if (strlen($this->password) > 5) {
            $this->hasedPassword =$this->data[0]["password"];
            if($this->veryifyPassword()){
                http_response_code(200);
                echo json_encode(
                    array("message" => "User exists!")
                );
                return true;
            }

        }
        //Display error if password doesn't match
        http_response_code(400);
        echo json_encode(
            array("message" => "Password is wrong!")
        );
        return false;
    }
    //Method for getting the username
    function getUsername($username)
    {
        $this->username = $username;
        if (strlen($this->username) > 6) {
            if ($this->data = $this->db->getUsername($this->username)) {
                return true;
            } 
        }
        http_response_code(400);
        echo json_encode(
            array("message" => "Username doesn't exists!")
        );
        return false;
    }
    //Method used only for creating a user account for the first time because the password will be hashed before storing it in the database. Call this method outside of any route
    function createUser($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
        if(strlen($this->username) > 6 && strlen($this->password) > 6){
            $this->MakeHashPassword();
            if($this->db->createUser($this->username, $this->hasedPassword)){
                echo json_encode(
                    array("message" => "User created")
                );
            }
            else{
                echo json_encode(
                    array("message" => "Error creating user")
                );
            }
        }
    }

    //Method for hashing the password
    function MakeHashPassword()
    {
        // using password_hash to hash the password before storing it in the database
        $this->hasedPassword = password_hash($this->password, PASSWORD_DEFAULT);

    }
    // Method for validating the password using the function password_verify() taking the password and the retrived hashed password from the database.
    function veryifyPassword(){
        if(password_verify($this->password, $this->hasedPassword)){
            return true;
        }
        else{
            return false;
        }

    }
}
