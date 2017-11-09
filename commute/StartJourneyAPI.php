<?php
 
require_once 'include/DB_Functions.php';

$curl = curl_init();
$db = new DB_Functions();

$response = array("error" => FALSE);
 
// json response array 
if (isset($_GET['employee_id'])) {
 
    $employee_id= $_GET['employee_id'];
    $waypoints = "waypoints=optimize:true|";
    $driver_details = $db->fetchFromMain($employee_id);
    $str_origin = "origin=".$driver_details['latitude'].",".$driver_details['longitude'];
    //echo $str_origin;
    $str_destination = "destination=28.451387,77.071904";
    $result = $db->acceptedRequests($employee_id);

    if($result){
        for ($i=0; $i<count($result); $i++)
        {
            //DETAILS OF PASSENGERS
            $response['response'][$i] = array("employee_id"=>$result[$i]['employee_id'],"latitude"=>$result[$i]['latitude'], "longitude"=>$result[$i]['longitude']);
            $waypoints .= $result[$i]['latitude'].",".$result[$i]['longitude']."|";
        }

    // echo("\n DETAILS OF PASSENGERS\n\n");
    // print_r($response);

    $parameters = $str_origin."&".$str_destination."&".$waypoints;
        //echo $parameters;
    //$output = "json";
    
     //CALLING MAPS API FOR WAYPOINTS OPTIMIZATION
    $url = "https://maps.googleapis.com/maps/api/directions/json?".$parameters;
    //print_r($url);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $result_from_google_api = curl_exec($curl);
    //print_r($result_from_google_api);
    curl_close($curl);

    $x=json_decode($result_from_google_api,true);
    $waypoints_order=$x["routes"][0]["waypoint_order"];
    //print_r ($waypoints_order);

    $string_route = null;

    //echo("\n ORDERED ARRAY FOR ROUTE\n\n");
    for ($i=0; $i<count($waypoints_order); $i++){
        $string_route .= $response['response'][$waypoints_order[$i]]['employee_id'].",";
    }

    $string_route .= "shopclues";
    //echo ($string_route);

    //SAVING OPTIMIZED ROUTE (EMPLOYEE IDS)
    $success = $db->storeInRouteTable($employee_id, $string_route, $driver_details['latitude'], $driver_details['longitude']);

    if($success){
        $response["response"] = TRUE;
        //echo json_encode($response);
    }
    else{
        $response["error"] = TRUE;
        $response["error_msg"] = "Data did not store in Routes table";
    }
        
        echo json_encode($response);
    }
    else{
        $response["error"] = TRUE;
            $response["error_msg"] = "No Accepted Request";
            echo json_encode($response);
    }
    }

    else{
        $response["error"] = TRUE;
    $response["error_msg"] = "Required parameter (employee_id) is missing!";
    echo json_encode($response);
    }
?>
