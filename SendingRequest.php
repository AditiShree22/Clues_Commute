 
 <?php  // API TO SEND REQUEST
       
	require_once 'include/DB_Functions.php';
       	$db = new DB_Functions(); 

	// json response array  
      $response = array("error" => FALSE);
      $jsonArr=json_decode(file_get_contents('php://input'),true);
      									 
      if (isset($jsonArr['requester_id']) /*&& !empty($jsonArr['requester_id'])*/ && isset($jsonArr['requestee_id']) /*&& !empty($jsonArr['requestee_id'])*/)
      {
          // receiving the parameters
          $requester_id = $jsonArr['requester_id'];
          $requestee_id = $jsonArr['requestee_id'];
          
          //checking access type
          if($db->fetchFromUsersAccessType($requester_id)==="passenger"){
	
	//FOR ACCESS_TYPE = DRIVER	  
            // IF  request already exist in REQUEST table
            if ($db->doRequestExisted($requestee_id,$requester_id)){ 

                  $response["status"] = "Failed";
                  $response["error"]["error_code"] = "1001";
                  $response["error"]["error_msg"] = "Request has been already sent";
                  $response["user"] = (object)array();
                  echo json_encode($response);
              }
              // store request in database
             
              else if ($db->doRequestExisted($requestee_id,$requester_id) != true)
                 {
 $request_row = $db->storeRequest($requestee_id, $requester_id, $requester_id);
             
                   $response["error"] = (object)array();
                   $response["status"] = "Success";
                   $response["code"] = "200";
                   $response["message"] = "Your request has been sent successfully";
                   $response["user"]["request_id"] = $request_row[0][0];
                   $response["user"]["driver_id"] = $request_row[0][1];
                   $response["user"]["passenger_id"] = $request_row[0][2];
                   $response["user"]["sent_by"] = $request_row[0][3];
                   $response["user"]["created_at"] = $request_row[0][8];

                    echo json_encode($response);
              }
              
              else {
                  $response["status"] = "Failed";
                  $response["error"]["error_code"] = "409";
                  $response["error"]['error_msg'] = "Request Cannot be Sent";
                  $response["user"] = (object)array();
                  echo json_encode($response);
              }

          }

          else{
		  //FOR ACCESS TYPE = PASSENGER
            //if ($db->doRequestExisted($requestee_id,$requester_id)){ 

                  $response["status"] = "Failed";
                  $response["error"]["error_code"] = "406" ;
                  $response["error"]["error_msg"] = "Not a passenger! Request cannot be sent";
                  $response["user"] = (object)array();
                  echo json_encode($response);
             /* }
              
              else if ($db->doRequestExisted($requester_id,$requestee_id) != true)
                 {
                  $request_row = $db->storeRequest($requester_id, $requestee_id, $requester_id);
                   $response["error"] = (object)array();
                   $response["status"] = "Success";
                   $response["code"] = "200";
                   $response["message"] = "Your request has been sent successfully";
                   $response["user"]["request_id"] = $request_row[0];
                   $response["user"]["driver_id"] = $request_row[1];
                   $response["user"]["passenger_id"] = $request_row[2];
                   $response["user"]["sent_by"] = $request_row[3];
                   $response["user"]["created_at"] = $request_row[8];

                   echo json_encode($response);
              }
              
              else {
                  $response["status"] = "Failed";
                  $response["error"]["error_code"] = "409";
                  $response["error"]['error_msg'] = "Request Cannot be Sent";
                  $response["user"] = (object)array();
                  echo json_encode($response);
              } */

          }
      	
      } 
         else {
           $response["status"] = "Failed";
           $response["error"]["error_code"] = "400";
           $response["error"]["error_msg"] = "Bad Request : required parameters are missing!";
           $response["user"] = (object)array();
          echo json_encode($response);
          }
?>

