<?php
 
require_once 'include/DB_Functions.php';
$db = new DB_Functions();
 
// json response array
$response = array("error" => FALSE);
 
if (isset($_GET['employee_id'])) {
 
 
    // receiving the post params
    $passenger_id= $_GET['employee_id'];

    // check if user is already existed with the same email
    $val = $db->getDriverIdFromRoutesTable($passenger_id);
    // echo("\nValue is  \n");
    // print_r($val);

    $result = $db->fetchFromRoute($val);
    $passenger_data = $db->fetchFromMain($passenger_id);
    $shopclues_data = $db->fetchFromMain('shopclues');

    //print_r($shopclues_data);

    if($result && $passenger_data){
        $response['response']= array("driver_id" => $result['driver_id'], 'current_latitude' => $result['current_latitude'], 'current_longitude' => $result['current_longitude'], 'passenger_latitude' => $passenger_data['latitude'], 'passenger_longitude' => $passenger_data['longitude'], 'shopclues_latitude' => $shopclues_data['latitude'], 'shopclues_longitude' => $shopclues_data['longitude']);
        
        echo(json_encode($response));
    }

    else{
        $response["error"] = TRUE;
    $response["error_msg"] = "Driver_id does not exist in Route Table";
    echo json_encode($response);
    }
    // if($val){
    //     print_r($val);
    // }
    

    // else {
    //         // user failed to store
    //         $response["error"] = TRUE;
    //         $response["error_msg"] = "Updation Unsuccessful!";
    //         echo json_encode($response);
    //     }
    }
else {
    $response["error"] = TRUE;
    $response["error_msg"] = "Required parameters (driver_id or passenger_id) is missing!";
    echo json_encode($response);
}
?>