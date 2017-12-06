<?php
 
require_once 'include/DB_Functions.php';

$curl = curl_init();
$db = new DB_Functions();

$response = array("error" => FALSE);
 

if (isset($_GET['employee_id']) && !empty($_GET['employee_id'])) {
 
    $employee_id= $_GET['employee_id'];
    $waypoints = "waypoints=optimize:true|";
    $sensor = "sensor=false";
    $driver_details = $db->fetchFromRides($employee_id);
    
    // fetch source and destination of active ride only

    $source = "origin=".$driver_details['source'];
    $destination = "destination=".$driver_details['destination'];
    $str_origin = str_replace(' ', '', $source);
    $str_destination = str_replace(' ', '', $destination);
    //DETAILS OF PASSENGERS
    $result = $db->acceptedRequests($employee_id);
   
    
     if ($result){
        for ($i=0; $i<count($result); $i++)
        {
            // fetch passenger_id , pick up and drop off location from request table 
            $array = $db->fetchFromRequest($employee_id, $result[$i]['employee_id']);
            
            $response['response'][$i] = array("employee_id"=>$result[$i]['employee_id'],"pick_up_location"=>$array['pick_up_location'], "drop_off_location"=>$array['drop_off_location']);

            $pick_up = str_replace(' ', '', $array['pick_up_location']);
            $drop_off = str_replace(' ', '', $array['drop_off_location']);
          
        	$waypoints .= $pick_up."|".$drop_off."|";
            
        	// store pick up and drop off location of accepted request 
        }

    //CALLING MAPS API FOR WAYPOINTS OPTIMIZATION
    $parameters = $str_origin."&".$str_destination."&".$waypoints;
    
    $url = "https://maps.googleapis.com/maps/api/directions/json?".$parameters;
   
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $result_from_google_api = curl_exec($curl);
    
    curl_close($curl);

    $x=json_decode($result_from_google_api,true);
    $waypoints_order=$x["routes"][0]["waypoint_order"];
    
    $string_route = array();

    $waypoints = explode("|", $waypoints);
    unset($waypoints[0]);
    //print_r($waypoints);die();
   // ORDERED ARRAY FOR ROUTE
    
    foreach ($waypoints_order as $key) {
        
    	$string_route[]= $waypoints[$key+1];
    	
    }
     
     $string_route = implode(",", $string_route);
     
     $string_route .= ",".$driver_details['destination'];
    //print_r($string_route);die();
      //SAVING OPTIMIZED ROUTE (EMPLOYEE IDS)

      // use geocode API to convert origin and destination of driver into lat lng for 
     $LatLng = $db->getLatLong($str_origin);

         $latFrom =  $LatLng['results'][0]['geometry']['location']['lat'];
         $longFrom = $LatLng['results'][0]['geometry']['location']['lng'];
         

     $success = $db->storeInRouteTable($employee_id, $string_route, $latFrom, $longFrom);
     

     if($success){
   
        $response["error"] = (object)array();
        $response["status"] = "Success";
        $response["code"] = "200";
		$response["message"] = "Ride Started";
        echo json_encode($response);
    }
    else{
    	$response["status"] = "Failed";
        $response["error"]["error_code"] = "";
        $response["error"]["error_msg"] = "Data did not store in Routes table";
        $response["user"] = (object)array();
        echo json_encode($response);
    }
        
        
    }
    else{
    	    $response["status"] = "Failed";
            $response["error"]["error_code"] = "";
            $response["error"]["error_msg"] = "No Accepted Request";
            $response["user"] = (object)array();
            echo json_encode($response);
    }
    }

    else{
    	$response["status"] = "Failed";
        $response["error"]["error_code"] = "";
        $response["error"]["error_msg"] = "Required parameter (employee_id) is missing!";
        $response["user"] = (object)array();
        echo json_encode($response);
    }
?>
