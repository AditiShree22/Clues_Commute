<?php
 
require_once 'include/DB_Functions.php';
$db = new DB_Functions();
 
// json response array
$response = array("error" => FALSE);
 
if (isset($_GET['employee_id']) && !empty($_GET['employee_id']) && isset($_GET['password']) && !empty($_GET['password'])) {
 
    // receiving the post params
    $employee_id= $_GET['employee_id'];
    $password = $_GET['password'];

    // check if user is already existed with the same email
    if ($db->isUserExisted($employee_id)) {
        // user already existed
         $response["status"] = "Failed";
        $response["error"]["error_code"] = "1001";
        $response["error"]["error_msg"] = "User already existed with " . $employee_id;
        $response["user"] = (object)array();
        echo json_encode($response);
    } 
    //inserted

   //  else if(!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/", $email)){
   //              $response["error"] = TRUE;
   //              $response["error_msg"] = "Invalid email- " . $email;
   //              echo json_encode($response);

   // }

    // else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        
    //     list ($user, $domain) = explode('@', $email);
    //     $isGmail = ($domain == 'shopclues.com');
    //     if(!$isGmail){
    //          $response["error"] = TRUE;
    //          $response["error_msg"] = "Invalid email- " . $email;
    //          echo json_encode($response);
    //     }

    // }
    //   else if($match!=0){

    //          $response["error"] = TRUE;
    //          $response["error_msg"] = "Invalid email- " . $email;
    //          echo json_encode($response);

    // }

    else {
        // create a new user
        $user = $db->storeUser($employee_id, $password);
        if ($user) {
            // user stored successfully
          //  $response["error"] = FALSE;
            $response["status"] = "Success";
            $response["code"] = "200";
            $response["message"] = "Registration successful";
           // $response["uid"] = $user["unique_id"];
            $response["user"]["employee_name"] = $user["employee_name"];
            $response["user"]["employee_id"] = $user["employee_id"];
            $response["user"]["email"] = $user["email"];
            $response["user"]["access_as"] = $user["access_as"];
            $response["user"]["created_at"] = $user["created_at"];
            $response["user"]["updated_at"] = $user["updated_at"];
            $response["error"] = (object)array();
            

            echo json_encode($response);
        } else {
            // user failed to store
            $response["status"] = "Failed";
           // $response["error"]["error"] = TRUE;
            $response["error"]["error_code"] = "409";
            $response["error"]["error_msg"] = "Conflict : Unknown error occurred in registration!";
            $response["user"] = (object)array();
            echo json_encode($response);
        }
    }
}else {
    $response["status"] = "Failed";
   // $response["error"]["error"] = TRUE;
    $response["error"]["error_code"] = "400" ;
    $response["error"]["error_msg"] = "Bad Request : required parameters (employee_id or password) is missing!";
    $response["user"] = (object)array();
    echo json_encode($response);
}
?>
