<?php
require_once 'include/DB_Functions.php';
$db = new DB_Functions();
 
// json response array
$response = array("error" => FALSE);
 
if (isset($_GET['employee_id']) && !empty($_GET['employee_id']) && isset($_GET['password']) && !empty($_GET['password'])) {
 
    // receiving the post params
    $employee_id = $_GET['employee_id'];
    $password = $_GET['password'];
 
    // get the user by email and password
    $user = $db->getUserByEmployeeIdAndPassword($employee_id, $password);
    $status = $db->logIn($employee_id);
    
    if ($user != false) {
        // use is found
        $response["error"] = (object)array();
        $response["status"] = "Success";
        $response["code"] = "200";
        $response["message"] = "Successfully logged in";
        //$response["uid"] = $user["unique_id"];
        $response["user"]["employee_name"] = $user["employee_name"];
        $response["user"]["employee_id"] = $user["employee_id"];
        $response["user"]["email"] = $user["email"];
        $response["user"]["access_as"] = $user["access_as"];
        $response["user"]["created_at"] = $user["created_at"];
        $response["user"]["updated_at"] = $user["updated_at"];
        echo json_encode($response);
    } else {
        // user is not found with the credentials
        $response["status"] = "Failed";
        //$response["error"]["error"] = TRUE;
        $response["error"]["error_code"] = "406" ;
        $response["error"]["error_msg"] = "Login credentials are wrong. Please try again!";
        $response["user"] = (object)array();
        echo json_encode($response);
    }
} else {
    // required post params is missing
    $response["status"] = "Failed";
    //$response["error"]["error"] = TRUE;
    $response["error"]["error_code"] = "400" ;
    $response["error"]["error_msg"] = "Bad request : Required parameters email or password is missing!";
    $response["user"] = (object)array();
    echo json_encode($response);
}
?>
