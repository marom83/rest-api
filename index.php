<?php

include("../connection.php");
$db = new dbObj();
$connection =  $db->getConnstring();
$request_method=$_SERVER["REQUEST_METHOD"];
//which method

switch($request_method)
{
  case 'GET':
   // GET with id
   if(!empty($_GET["id"]))
   {
    $id=intval($_GET["id"]);
    get_employeesid($id);
   }
   else
   {
     get_employees(); //all employees
   }
   break;
 case 'POST':
  // Insert new employeee with POST
  insert_employee();
  break;

 case 'PUT':
   // Update an employee (with id) and PUT method
   $id=intval($_GET["id"]);
   update_employee($id);
   break;
 case 'DELETE':
   // Delete an employee with ID, DELETE method
   $id=intval($_GET["id"]);
   delete_employee($id);
   break;
 default:
  // Invalid Request Method
    header("HTTP/1.0 405 Method Not Allowed");
    break;
} 



function get_employees()
{
  global $connection;
  $query="SELECT * FROM employee";
  $response=array();
  $result=mysqli_query($connection, $query);
  while($row=mysqli_fetch_array($result))
  {
    $response[]=$row;
  }
  header('Content-Type: application/json'); //send the header
  echo json_encode($response); //data in JSON format
}

function get_employeesid($id=0)
{
  global $connection;
  $query="SELECT * FROM employee";
  if($id != 0)
  {
    $query.=" WHERE id=".$id." LIMIT 1"; //get employee with a given id
  }
  $response=array();
  $result=mysqli_query($connection, $query);
  while($row=mysqli_fetch_array($result))
  {
    $response[]=$row;
  }
  header('Content-Type: application/json'); //header
  echo json_encode($response); //in JSON format
}

function insert_employee()
 {
  global $connection;
   
    $data = json_decode(file_get_contents('php://input'), true); //getting data from the client
    $employee_name=$data["employee_name"]; //separate them
    $employee_salary=$data["employee_salary"];
    $employee_age=$data["employee_age"];
    echo $query="INSERT INTO employee SET employee_name='".$employee_name."', employee_salary='".$employee_salary."', employee_age='".$employee_age."'";
    if(mysqli_query($connection, $query))
    {
       $response=array(
             'status' => 1,
             'status_message' =>'Employee Added Successfully.'
              );
    }
    else
    {
       $response=array(
             'status' => 0,
             'status_message' =>'Employee Addition Failed.'
             );
    }
    header('Content-Type: application/json');
    echo json_encode($response); //response with header 
                         }
function delete_employee($id)
{
   global $connection;
  $query="DELETE FROM employee WHERE id=".$id;
   if(mysqli_query($connection, $query))
   {
     $response=array(
      'status' => 1,
       'status_message' =>'Employee Deleted Successfully.'
      );
   }
   else
   {
      $response=array(
         'status' => 0,
         'status_message' =>'Employee Deletion Failed.'
      );
   }
   header('Content-Type: application/json');
   echo json_encode($response);
}
                  

function update_employee($id)
 {
   global $connection;
   $post_vars = json_decode(file_get_contents("php://input"),true);
   $employee_name=$post_vars["employee_name"];
   $employee_salary=$post_vars["employee_salary"];
   $employee_age=$post_vars["employee_age"];
   $query="UPDATE employee SET employee_name='".$employee_name."', employee_salary='".$employee_salary."', employee_age='".$employee_age."' WHERE id=".$id;
   if(mysqli_query($connection, $query))
   {
      $response=array(
         'status' => 1,
         'status_message' =>'Employee Updated Successfully.'
      );
    }
    else
    {
        $response=array(
            'status' => 0,
           'status_message' =>'Employee Updation Failed.'
        );
    }
    header('Content-Type: application/json');
    echo json_encode($response);
}                  
         
?>