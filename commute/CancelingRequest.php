<?php
 
require_once 'include/DB_Functions.php';
$db = new DB_Functions();
 
// json response array
$response = array("error" => FALSE);

$jsonArr=json_decode(file_get_contents('php://input'),true);
 
if (isset($jsonArr['acceptor_id']) && isset($jsonArr['sender_id'])) {
 
    // receiving the post params
    $acceptor_id= $jsonArr['acceptor_id'];
    $sender_id = $jsonArr['sender_id'];

    $type = $db->fetchFromUsersAccessType($acceptor_id);

    if($type === "driver"){
        if ($db->updateStatus($acceptor_id, $sender_id, -1)) {
        // user already existed
        $response["status"] = "Success";
        $response["code"] = "200";
        $response["message"] = "Request Rejected"; 
        $response["error"] = (object)array();
        echo json_encode($response);
    } 

    else {
            // user failed to store
            $response["status"] = "Failed";
            $response["error"]["error_code"] = "304" ;
            $response["error"]["error_msg"] = "Updation Unsuccessful!";
            $response["error_msg"] = "Updation Unsuccessful!";
            echo json_encode($response);
        }
    }
    elseif ($type === "passenger") {
        //if ($db->updateStatus($sender_id, $acceptor_id, -1)) {
        
        $response["status"] = "Forbidden !";
        $response["code"] = "403";
        $response["message"] = "As a passenger you cannot accept requests !"; 
        $response["error"] = (object)array();
        echo json_encode($response);
   /* } 

    else {
            // user failed to store
            $response["error"] = TRUE;
            $response["error_msg"] = "Updation Unsuccessful!";
            echo json_encode($response);
        }*/
    }

    else{
        $response["status"] = "Failed";
        $response["error"]["error_code"] = "412" ;
        $response["error"]["error_msg"] = "Could not get access type of acceptor!";
        $response["error"]["error_msg"] = "Could not get access type of acceptor!";
        echo json_encode($response);
    }

    }
else {
    $response["status"] = "Failed";
    $response["error"]["error_code"] = "400" ;
    $response["error"]["error_msg"] = "Required parameters are missing!";
    echo json_encode($response);
}
?>
