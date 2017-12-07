<?php
 
require_once 'include/DB_Functions.php';
$db = new DB_Functions();
 
// json response array
$response = array("error" => FALSE);
 
if (isset($_GET['employee_id']) && !empty($_GET['employee_id'])) {
 
    // receiving the post params
    $passenger_id= $_GET['employee_id'];

    $val = $db->fetchDriverFromRequest($passenger_id);

    $result = $db->fetchFromRoute($val['driver_id']);

    $passenger_data_origin = $db->getLatLong($val['pick_up_location']);
    $passenger_data_destination = $db->getLatLong($val['drop_off_location']);

    if($result && $val && $passenger_data_origin && $passenger_data_destination){

        $response["error"] = (object)array();
        $response["status"] = "Success";
        $response["code"] = "200";
		$response["message"] = "Your Location";
		$response["data"]["driver_id"] = $val["driver_id"];
		$response["data"]["current_latitude"] = $result["current_latitude"];
		$response["data"]["current_longitude"] = $result["current_longitude"];
        $response["data"]["passenger_pick_up_latitude"] = $passenger_data_origin['results'][0]['geometry']['location']['lat'];
	    $response["data"]["passenger_pick_up_longitude"] = $passenger_data_origin['results'][0]['geometry']['location']['lng'];
        $response["data"]["passenger_drop_off_latitude"] = $passenger_data_destination['results'][0]['geometry']['location']['lat'];
        $response["data"]["passenger_drop_off_longitude"] = $passenger_data_destination['results'][0]['geometry']['location']['lng'];

       echo(json_encode($response));
    }
     else{
     	$response["status"] = "Failed";
        $response["error"]["error_code"] = "406";
        $response["error"]["error_msg"] = "Driver_id does not exist in Route Table";
        $response["user"] = (object)array();
    echo json_encode($response);
    }
    else {
    	$response["status"] = "Failed";
        $response["error"]["error_code"] = "400";
        $response["error"]["error_msg"] = "Required parameters (driver_id or passenger_id) is missing!";
        $response["user"] = (object)array();
    echo json_encode($response);
}
?>