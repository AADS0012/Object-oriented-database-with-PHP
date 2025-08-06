<?php
/* $server_name = "";
$db_username = "";
$db_password = "";
$db_name = ""; */

// creating data base
/* try {
  $conn = new PDO("mysql:host=" . $server_name . ";dbname=" . $db_name, $db_username, $db_password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "CREATE DATABASE IF NOT EXISTS $db_name";
  // use exec() because no results are returned
  $conn->exec($sql);
  // echo "Database created successfully<br>";
} catch (PDOException $e) {
  echo $sql . "<br>" . $e->getMessage();
} */

class Db extends PDO
{
  private $servername;
  private $db_username;
  private $db_password;
  private $dbname;
  public $conn;

  function __construct($servername, $db_username, $db_password, $dbname)
  {
    $this->servername = $servername;
    $this->db_username = $db_username;
    $this->db_password = $db_password;
    $this->dbname = $dbname;
    // Establish connection
    $this->conn = new PDO("mysql:host=" . $this->servername . ";dbname=" . $this->dbname, $this->db_username, $this->db_password);
    // set the PDO error mode to exception
    $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  }

  /* 
  connect to data base
  if error return a message in the console
  */
  public function connect_database()
  {
    try {
      $server = new PDO("mysql:host=" . $this->servername . ";dbname=" . $this->dbname, $this->db_username, $this->db_password);
      // set the PDO error mode to exception
      $server->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      // echo "Connected successfully";
      return $server;
    } catch (PDOException $e) {
      report_error("Connection fiald: ", $e->getMessage());
    }
  }

  /*
  creating table for data base
  if error return a message in the console
  */
  public function create_table($query)
  {
    try {
      // use exec() because no results are returned
      $this->conn->exec($query);
      // echo "Table created successfully";
    } catch (PDOException $e) {
      report_error($query . "\t Error is:", $e->getMessage());
    }

    $this->conn = null;
  }

  /* 
  CRUD Operation (Create, Read, Update, Delete)
  Insert
  Select
  Select All
  Update
  Delete
  $op must be lower case

  $op parameters =[
    insert,
    delete,
    update,
    select,
    select_all
  ]

  $fetch_mode must be a var in the PDO class and fetch type

  if error return a message in the console browser
  */

  public function CRUD_operation($op, $query, $dataArr = [], $fetch_mode = PDO::FETCH_ASSOC)
  {
    try {
      // prepareing
      $stmt = $this->connect_database()->prepare($query);

      $stmt->execute($dataArr);
      switch ($op) {
        case 'insert':
        case 'delete':
        case 'update':
          return $stmt;
        case 'select':
          $req = $stmt->fetch($fetch_mode);
          return $req;
        case 'select_all':
          $req = $stmt->fetchAll($fetch_mode);
          return $req;
        default:
          report_error($query . "\t Error is:", "undefined mission CRUD");
          break;
      }
    } catch (PDOException $e) {
      report_error($query . "\t Error is:", $e->getMessage());
    }
  }
}

// for example
/* $db = new Db($server_name, $db_username, $db_password, $db_name);
$db->connect_database(); */

// create users table for example
/* $users_table_query = 'CREATE TABLE IF NOT EXISTS `users` (
`id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
`username` VARCHAR(50) NOT NULL,
`password` VARCHAR(50) NOT NULL,
`mobile` VARCHAR(12) NOT NULL,
`email` VARCHAR(50) NOT NULL)';
$db->create_table($users_table_query); */

// for example
// ////// example 1
# parameters
/* 
$key = $_POST['key'];
$password = $_POST['password'];
$dataArr = [':key' => $key, ':password' => $password];

# sql
$query = "SELECT * FROM `users` WHERE (username = :key OR mobile =:key OR email =:key) AND (`password` =:password) LIMIT 1";

$db->connect_database();

$result = $db->CRUD_operation('select', $query, $dataArr);
if ($result) {
  print_r($result);
} 

// ////// example 2
#parameters
$username = prepare_input($_POST['username']);
$mobile = prepare_input($_POST['mobile']);
$email = prepare_input($_POST['email']);
$password = prepare_input($_POST['password']);
$dataArr = [':username' => $username, ':password' => $password, ':mobile' => $mobile, ':email' => $email];
#sql
$query = "INSERT INTO `users` SET username=:username, `password`=:password, `mobile`=:mobile, email=:email";

$db->connect_database();

$result = $db->CRUD_operation('insert', $query, $dataArr);

if ($result) {
    header('Location: ../');
}

*/


// throw an error in the console browser
function report_error($message, $e)
{
  echo "<script>console.error($message \n " . $e . ");</script>";
}