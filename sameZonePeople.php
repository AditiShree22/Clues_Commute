<?php
 
require_once 'include/DB_Functions.php';
$db = new DB_Functions();
$response = array("error" => FALSE);

// json response array 
if (isset($_GET['employee_id']) && !empty($_GET['employee_id'])) {
 
    // receiving the params
    $employee_id= $_GET['employee_id'];

    if($db->fetchFromUsersAccessType($employee_id)==="driver"){
    $result = $db->getSameZonePeople($employee_id);

    if($result){
       $response["error"] = (object)array();
       $response["status"] = "success";
       $response["code"] = "200";
       $response["message"] = "Successful response";

      for ($i=0; $i<count($result); $i++)
        {
            //employee_id, employee_name, address, designation, owns_vehicle, mobile_number, gender 

            $response["response"][$i] = (object)array("employee_id"=>$result[$i][0],"employee_name"=>$result[$i][1],"address"=>$result[$i][2],"designation"=>$result[$i][3],"owns_vehicle"=>$result[$i][4],"mobile_number"=>$result[$i][5],"gender"=>$result[$i][6]);
        }
    echo json_encode($response);  
    }
    else{    
            $response["status"] = "Failed";
            $response["error"]['error_code'] = "204";
            $response['error']["error_msg"] = "No Employee Lives in Your Zone";
            $response["response"] = (object)array();
            echo json_encode($response);
        }
    }
    else{    
            $response["status"] = "Failed";
            $response["error"]['error_code'] = "406";
            $response['error']["error_msg"] = "Not a Driver! Can't access";
            $response["response"] = (object)array();
            echo json_encode($response);
        }
    }

    else{  
        $response["status"] = "Failed";
        $response["error"]['error_code'] = "400";
        $response['error']["error_msg"] = "Bad Request : Required parameter (employee_id) is missing!";
        $response["response"] = (object)array();
        echo json_encode($response);
   }
?>
