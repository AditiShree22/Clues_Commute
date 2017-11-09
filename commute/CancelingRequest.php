<?php
 
require_once 'include/DB_Functions.php';
$db = new DB_Functions();
 
// json response array
$response = array("error" => FALSE);
 
if (isset($_GET['acceptor_id']) && isset($_GET['sender_id'])) {
 
    // receiving the post params
    $acceptor_id= $_GET['acceptor_id'];
    $sender_id = $_GET['sender_id'];

    $type = $db->fetchFromUsersAccessType($acceptor_id);

    if($type === "driver"){
        if ($db->updateStatus($acceptor_id, $sender_id, -1)) {
        // user already existed
        $response["response"] = TRUE;
        echo json_encode($response);
    } 

    else {
            // user failed to store
            $response["error"] = TRUE;
            $response["error_msg"] = "Updation Unsuccessful!";
            echo json_encode($response);
        }
    }
    elseif ($type === "passenger") {
        if ($db->updateStatus($sender_id, $acceptor_id, -1)) {
        // user already existed
        $response["response"] = TRUE;
        
        echo json_encode($response);
    } 

    else {
            // user failed to store
            $response["error"] = TRUE;
            $response["error_msg"] = "Updation Unsuccessful!";
            echo json_encode($response);
        }
    }

    else{
        $response["error"] = TRUE;
        $response["error_msg"] = "Could not get access type of acceptor!";
        echo json_encode($response);
    }

    }
else {
    $response["error"] = TRUE;
    $response["error_msg"] = "Required parameters are missing!";
    echo json_encode($response);
}
?>
