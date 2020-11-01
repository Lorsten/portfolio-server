<?php

//Class for retriving user data from JSON and url  and also displaying the data based on the right condition

include_once('mainClass.php');

class requestClass
{
    private $db;
    private $data;
    private $preCleanData;

    public function __construct()
    {
        $this->request = $_SERVER['REQUEST_METHOD'];
        $this->db = new mainClass();
        $this->getData();
    }
    //Method for retriving data with either file_get_contents or with $_GET
    function getData()
    {
        //if POST or PUT get the data wiht file_get_contents
        if ($this->request === "POST" || $this->request === "PUT") {
            $this->preCleanData = JSON_decode(file_get_contents("php://input"));
            //else get the ID from $_get
        } else if ($this->request === "GET" || $this->request === "DELETE") {
            if (isset($_GET['ID'])) {
                $this->data = $_GET['ID'];
                return;
            } else {
                return;
            }
        }
        $this->cleanData();
    }


    //Method for cleaning the data by stripping tags
    function cleanData()
    {
        //Create and enmpty object 
        $this->data = new stdClass();
        //Using a foreach loop to iterate through the object
        foreach ($this->preCleanData as $key => $value) {
            if (strlen($value) == 0) {
                return $this->data = null;
            }
            $this->data->$key = strip_tags($value);
        }
        return $this->data;
    }

    //Method for displaying data 
    function displayData($table, $tableID = "")
    {
        if (!is_null($this->data)) {
            echo $this->db->getDataByID($table, $tableID, $this->data);
        } else {
            if (empty($this->db->retriveData($table))) {
                echo json_encode($this->db->getColumnName($table));
            } else {
                echo json_encode($this->db->retriveData($table));
            }
        }
    }
    //Method for deleting data from a table based on it's ID
    function deleteData($table, $tableID)
    {
        //Check if property data is not null
        if (!is_null($this->data)) {
            if ($this->db->deleteDataByID($table, $tableID, $this->data)) {
                echo json_encode(
                    array("message" => "Data deleted")
                );
            }
            else{
                http_response_code("400");
                echo json_encode(
                    array("message" => "Unable to delete data")
                );
            }
        }
        // else Display error
        else {
            http_response_code("400");
            echo json_encode(
                array("message" => "Unable to delete data!")
            );
        }
    }
    //Method for inserting data based on the table name
    function insertData($table)
    {
        //Check if data is not null add the data depending on the table name
        if (!is_null($this->data)) {
            if ($table === 'website') {
                if ($this->db->insertIntoDbWebsite(
                    $this->data->titel_website,
                    $this->data->url,
                    $this->data->description
                )) {
                    echo json_encode(
                        array("message" => "Website added!")
                    );
                } else {
                    http_response_code("400");
                    echo json_encode(
                        array("message" => "Unable to add data to tabel website!")
                    );
                }
            } else if ($table === 'education') {
                if ($this->db->insertIntoDbEducation(
                    $this->data->educational_institution,
                    $this->data->course_program,
                    $this->data->start_date,
                    $this->data->end_date
                )) {
                    echo json_encode(
                        array("message" => "Education added!")
                    );
                } else {
                    http_response_code("400");
                    echo json_encode(
                        array("message" => "Unable to add data to tabel education!")
                    );
                }
            } else if ($table === 'work') {
                if ($this->db->insertIntoDbWork(
                    $this->data->titel,
                    $this->data->workplace,
                    $this->data->start_date,
                    $this->data->end_date
                )) {
                    echo json_encode(
                        array("message" => "workplace added!")
                    );
                } else {
                    http_response_code("400");
                    echo json_encode(
                        array("message" => "Unable to add data to tabel work!")
                    );
                }
            }
        } else {
            http_response_code("400");
            echo json_encode(
                array("message" => "One or more input is empty")
            );
        }
    }

    //Method for updating data same as insert method execpt using put
    function UpdateData($table)
    {
        if (!is_null($this->data)) {
            if ($table === 'website') {
                if ($this->db->UpdateTableWebsite(
                    $this->data->website_ID,
                    $this->data->titel_website,
                    $this->data->url,
                    $this->data->description
                )) {
                    http_response_code("200");
                    echo json_encode(
                        array("message" => "Website updated!")
                    );
                } else {
                    http_response_code("400");
                    echo json_encode(
                        array("message" => "Unable to update tabel website")
                    );
                }
            } else if ($table === 'education') {
                if ($this->db->UpdateTableEducation(
                    $this->data->education_ID,
                    $this->data->educational_institution,
                    $this->data->course_program,
                    $this->data->start_date,
                    $this->data->end_date
                )) {
                    http_response_code("200");
                    echo json_encode(
                        array("message" => "Education updated!")
                    );
                } else {
                    http_response_code("400");
                    echo json_encode(
                        array("message" => "Unable to update tabel education!")
                    );
                }
            } else if ($table === 'work') {
                if ($this->db->UpdateTableWork(
                    $this->data->work_ID,
                    $this->data->titel,
                    $this->data->workplace,
                    $this->data->start_date,
                    $this->data->end_date
                )) {
                    http_response_code("200");
                    echo json_encode(
                        array("message" => "Work updated")
                    );
                } else {
                    http_response_code("400");
                    echo json_encode(
                        array("message" => "Error updating tabel work")
                    );
                }
            }
        } else {
            http_response_code("400");
            echo json_encode(
                array("message" => "One or more input is empty")
            );
        }
    }
}
