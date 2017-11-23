<?php
// API for passenger to search drivers with active rides

require_once 'include/DB_Functions.php';
$db = new DB_Functions();
 
// json response array
$response = array("error" => FALSE);
 
if (isset($_GET['source']) && !empty($_GET['source']) && isset($_GET['destination']) && !empty($_GET['destination']) /* && isset($_GET['created_at'])*/) {
 
    // receiving the post params
    $source = $_GET['source'];
    $destination = $_GET['destination'];
    //$created_at = $_GET['created_at'];
    $earthRadius = 6371;
    $source1 = str_replace(' ', '', $source);
    $destination1 = str_replace(' ', '', $destination);

if (isset($source1) && isset($destination1)) {
        
        //CALLING MAPS API FOR GEOCODE
    $source_points = $db->getLatLong($source1);
    //$dest_points = $db->getLatLong($destination1);

          $latFrom =  $source_points['results'][0]['geometry']['location']['lat'];
           $longFrom = $source_points['results'][0]['geometry']['location']['lng'];
           // $latTo = $dest_points['results'][0]['geometry']['location']['lat'];
            // $longTo = $dest_points['results'][0]['geometry']['location']['lng'];

    $active_rides = $db->getActiveRides();
    
    for ($i=0; $i<count($active_rides); $i++) {
        
        $intermediate_points["intermediate_points"][$i] = $active_rides[$i][5];
       
           // for ($j=0; $j < count($intermediate_points["intermediate_points"]); $j++) { 
        $coordinate = json_decode($intermediate_points["intermediate_points"][$i],true);
                   
                     for ($k=0; $k < count($coordinate); $k++) { 
                        $latTo = $coordinate[$k]['lat'];
                        $longTo = $coordinate[$k]['lng'];
                        
       $distance = $db->haversineGreatCircleDistance($latFrom, $longFrom, $latTo, $longTo, $earthRadius);      //print_r($distance);echo "\n"; die();
                   
                             if ($distance <= 5.000000000000) {
                            // $driver[] = array($active_rides[$i][1]." ".$active_rides[$i][4]); 
                           
       $drivers[] = array("Driver_id"=>$active_rides[$i][1],"Ride_name"=>$active_rides[$i][4]);
                                     
                                     break;
                              }

                        // break;
                     }
            // }
     }
         
   //print_r($intermediate_points["intermediate_points"][0]);
     
        $response["status"] = "Success";
        $response["code"] = "200";
        $response["response"]["Near by Drivers"] = $drivers;
        $response["error"] = (object)array();
        echo json_encode($response);

     }
     else {
        // user is not found with the credentials
        $response["status"] = "Failed";
        $response["error"]["error_code"] = "406" ;
        $response["error"]["error_msg"] = "One or more parameters is not correct. Please try again!";
        $response["response"] = (object)array();
        echo json_encode($response);
    }
} else {
    // required post params is missing
    $response["status"] = "Failed";
    $response["error"]["error_code"] = "400" ;
    $response["error"]["error_msg"] = "Bad request : Required parameters are missing!";
    $response["response"] = (object)array();
    echo json_encode($response);
}

?>
 
