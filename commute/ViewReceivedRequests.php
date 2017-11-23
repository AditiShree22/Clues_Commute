<?php
 
require_once 'include/DB_Functions.php';
$db = new DB_Functions();

$response = array("error" => FALSE);
 
// json response array 
if (isset($_GET['employee_id']) && !empty($_GET['employee_id'])) {
 
    // receiving the post params
    $employee_id= $_GET['employee_id'];
    

    //echo ($employee_id);

    $result = $db->getAllReceivedRequest($employee_id);

    //print_r($result);
    if($result){
             $response["status"] = "Success";
             $response["code"] = "200";
             $response["message"] = "Received Request"; 
             $response["error"] = (object)array();
        for ($i=0; $i<count($result); $i++)
        {

            $response["response"][$i] = array("employee_id"=>$result[$i]['employee_id'],"employee_name"=>$result[$i]['employee_name'],"email"=>$result[$i]['email'],"mobile_number"=>$result[$i]['mobile_number'],"gender"=>$result[$i]['gender']);
            

        }
    echo json_encode($response);
    }
    else{
         $response["status"] = "Failed";
         $response["error"]["error_code"] = "204" ;
         $response["error"]["error_msg"] = "No request received !";
         $response["response"] = (object)array();
            echo json_encode($response);
        }
    }

    else{
    $response["status"] = "Failed";
    $response["error"]["error_code"] = "400" ;
    $response["error"]["error_msg"] = "Bad request : Required parameter is missing!";
    $response["response"] = (object)array();
    $response["error_msg"] = "Required parameter (employee_id) is missing!";
    echo json_encode($response);
    }
?>
