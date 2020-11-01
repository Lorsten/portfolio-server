<?php
/*
 Class used for connecting to the database aswell as making sql request for retriving and posting data in the database.
 */
class mainClass
{
  private $dbHost = DBHOST;
  private $db = DBDATABASE;
  private $dbuser = DBUSER;
  private $dbPassword = DBPASSWORD;
  public $connect;
  private $query;

  //Making the connection with PDO to the database in the constructor
  public function __construct()
  {
    try {
      $this->connect = new PDO(
        "mysql:host=" . $this->dbHost . ";dbname=" . $this->db,
        $this->dbuser,
        $this->dbPassword
      );
      $this->connect->exec("set names utf8");
    } catch (PDOException $expection) {
      echo "connection failed " . $expection->getMessage();
    }
  }
  function getColumnName($table)
  {
    $this->query = "SELECT
     COLUMN_name
    from INFORMATION_SCHEMA.COLUMNS
    where TABLE_NAME= :table";
    $result = $this->connect->prepare($this->query);
    $result->execute(['table' => $table]);
    if ($data = $result->fetchAll(\PDO::FETCH_ASSOC)) {
      return $data;
    }
  }

  //Method for retriving data based on the route path used
  function retriveData($table)
  {
    //Creating the sql query
    $this->query = "SELECT * from " . $table . "";
    $result = $this->connect->prepare($this->query);
    $result->execute();
    $data = $result->fetchAll(\PDO::FETCH_ASSOC);

    // Return the data by encoding it with json first
    return $data;
  }
  //Method for retriving data based on the route path and with the iD in the url parameter
  function getDataByID($table, $tableID, $id)
  {
    $this->query = "SELECT * from " . $table . " where " . $tableID . " = :id";
    $result = $this->connect->prepare($this->query);
    $result->execute(['id' => $id]);
    if ($data = $result->fetchAll(\PDO::FETCH_ASSOC)) {
      return json_encode($data);
    }
    return false;
  }

  // Method for Inserting data into the website table
  function insertIntoDbWebsite($titel, $url, $desc)
  {
    $this->query = "INSERT
                        into 
                             website(titel_website, url, description)
                        Values
                             (:titel, :url, :desc)";

    $result = $this->connect->prepare($this->query);
    if ($result->execute(['titel' => $titel, 'url' => $url, 'desc' => $desc])) {
      $data = $result->fetchAll(\PDO::FETCH_ASSOC);
      return json_encode($data);
    }
    return false;
  }
  // Insert data into Education table
  function insertIntoDbEducation($educational_institution, $course_program, $start_date,  $end_date)
  {
    $this->query = "INSERT
                        into 
                             education(educational_institution, course_program, start_date, end_date)
                        Values
                             (:educational_institution, :course_program, :start_date, :end_date)";

    $result = $this->connect->prepare($this->query);
    if ($result->execute([
      'educational_institution' => $educational_institution,
      'course_program' => $course_program, 'start_date' => $start_date,
      'end_date' => $end_date
    ])) {

      $data = $result->fetchAll(\PDO::FETCH_ASSOC);
      return json_encode($data);
    }
    return false;
  }
  // Insert data into workplace table
  function insertIntoDbWork($titel, $workplace, $start_date,  $end_date)
  {
    $this->query = "INSERT
                        into 
                             workplace(titel,workplace, start_date, end_date)
                        Values
                             (:titel, :workplace, :start_date, :end_date)";

    $result = $this->connect->prepare($this->query);
    if ($result->execute([
      'titel' => $titel,
      'workplace' => $workplace, 'start_date' => $start_date,
      'end_date' => $end_date
    ])) {
      $data = $result->fetchAll(\PDO::FETCH_ASSOC);
      return json_encode($data);
    }
    return false;
  }

  function UpdateTableWebsite($ID, $titel, $url, $desc)
  {
    $this->query = "UPDATE
      website
      SET 
             titel_website = :titel_website, url = :url, description = :desc
      WHERE
            website_ID = :id";
    $result = $this->connect->prepare($this->query);
    if ($result->execute(['id' => $ID, 'titel_website' => $titel, 'url' => $url, 'desc' => $desc])) {
      return true;
    }
    return false;
  }
  function UpdateTableEducation($ID, $educational_institution, $program, $start_date, $end_date)
  {
    $this->query = "UPDATE
      education
      SET 
             educational_institution = :educational_institution, course_program = :course_program, start_date = :start_date, end_date = :end_date
      WHERE
           education_ID = :id";
    $result = $this->connect->prepare($this->query);
    if ($result->execute(['id' => $ID, 'educational_institution' => $educational_institution, 'course_program' =>
    $program, 'start_date' => $start_date, 'end_date' => $end_date])) {
      return true;
    }
    return false;
  }
  function UpdateTableWork($ID, $titel, $workplace, $start_date, $end_date)
  {
    $this->query = "UPDATE
      workplace
      SET 
             titel = :titel, workplace = :workplace, start_date = :start_date, end_date = :end_date
      WHERE
           work_ID = :id";
    $result = $this->connect->prepare($this->query);
    if ($result->execute(['id' => $ID, 'titel' => $titel, 'workplace' => $workplace, 'start_date' => $start_date, 'end_date' => $end_date])) {
      return true;
    }
    return false;
  }

  //Method for deleting data from a table based on the variables $table for the table and $table_ID for the id
  function deleteDataByID($table, $table_id, $ID)
  {
    $this->query = "DELETE  from " . $table . " WHERE " . $table_id . " = :id";
    $result = $this->connect->prepare($this->query);
    if ($result->execute(['id' => $ID])) {
      return true;
    }
    return false;
  }

  function createUser($username, $password)
  {
    $this->query = "INSERT
    into 
         account(username, password)
    Values
         (:user, :password)";

    $result = $this->connect->prepare($this->query);
    if ($result->execute([
      'user' => $username,
      'password' => $password
    ])) {

      $data = $result->fetchAll(\PDO::FETCH_ASSOC);
      return json_encode($data);
    }
    return false;
  }



  //Method for gettig user info from the table
  function getUsername($username)
  {
    $this->query = "SELECT * from account WHERE username = :user";
    $result = $this->connect->prepare($this->query);
    $result->execute(['user' => $username]);
    if ($data = $result->fetchAll(\PDO::FETCH_ASSOC)) {
      return $data;
    }
    return false;
  }
}
