 <?php
 
class DB_Functions {
 
    private $conn;
 
    // constructor
    function __construct() {
        require_once 'include/DB_connect.php';
        // connecting to database
        $db = new Db_Connect();
        $this->conn = $db->connect();
    }
 
    // destructor
    function __destruct() {    
    }
 
    /**
     * Storing new user
     * returns user details
     */
    public function storeUser($employee_id, $password) {
        $uuid = uniqid('', true);
        $hash = $this->hashSSHA($password);
        $encrypted_password = $hash["encrypted"]; // encrypted password
        $salt = $hash["salt"]; // salt

     //Fetching employee's details from MAIN table
        $stmt1 = $this->conn->prepare("SELECT * FROM main WHERE employee_id = ?");
        $stmt1->bind_param("s", $employee_id);
 
        if ($stmt1->execute()) {
            $data_main = $stmt1->get_result()->fetch_assoc();
            $stmt1->close();            
        }

        else
            echo "NO ENTRY FOUND";
 
     //Insert into USERS table
        $stmt = $this->conn->prepare("INSERT INTO users (unique_id, employee_id, email, encrypted_password, salt, created_at, updated_at, employee_name, address, gender, designation, zone_id, mobile_number, owns_vehicle, number_of_seats, vehicle_number, latitude, longitude) VALUES (?, ?, ?, ?, ?, NOW(), NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? ,?)");
        $stmt->bind_param("sssssssssisiisdd", $uuid, $employee_id, $data_main['email'], $encrypted_password, $salt, $data_main['employee_name'], $data_main['address'], $data_main['gender'], $data_main['designation'], $data_main['zone_id'], $data_main['mobile_number'], $data_main['owns_vehicle'], $data_main['number_of_seats'], $data_main['vehicle_number'], $data_main['latitude'], $data_main['longitude']);
        $result = $stmt->execute();
        $stmt->close();
 
        // check for successful store
        if ($result) {
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE employee_id = ?");
            $stmt->bind_param("s", $employee_id);
            $stmt->execute();
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();
 
            return $user;
        } 
        else {
             return false;
        }
    }
 
    /**
     * Get user by employee id and password (Used for login)
     */
    public function getUserByEmployeeIdAndPassword($employee_id, $password) {
 
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE employee_id = ?");
        $stmt->bind_param("s", $employee_id);
 
        if ($stmt->execute()) {
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();
 
            // verifying user password
            $salt = $user['salt'];
            $encrypted_password = $user['encrypted_password'];
            $hash = $this->checkhashSSHA($salt, $password);

            // check for password equality
            if ($encrypted_password == $hash) {
                // user authentication details are correct

                return $user;
            }
        } else {
            return NULL;
        }
    }
 public function logIn($employee_id)
    {
        $stmt = $this->conn->prepare("UPDATE users SET status = 1 WHERE employee_id = ?");
        $stmt->bind_param("s", $employee_id);
      if ($stmt->execute()) 
      {
       $stmt->store_result();
         $stmt->close();
         return true;
         }
         else
         return false;
    }
    /**
     * Check user is existed or not (Used in Registration)
     */
    public function isUserExisted($employee_id) {
        $stmt = $this->conn->prepare("SELECT email from users WHERE employee_id = ?");
        $stmt->bind_param("s", $employee_id);
        $stmt->execute();
        $stmt->store_result();
 
        if ($stmt->num_rows > 0) {
            // user existed 
            $stmt->close();
            return true;
        } else {
            // user not existed
            $stmt->close();
            return false;
        }
    }
 
    /**
     * Encrypting password
     * @param password
     * returns salt and encrypted password
     */
    public function hashSSHA($password) {
 
        $salt = sha1(rand());
        $salt = substr($salt, 0, 10);
        $encrypted = base64_encode(sha1($password . $salt, true) . $salt);
        $hash = array("salt" => $salt, "encrypted" => $encrypted);
        return $hash;
    }
 
    /**
     * Decrypting password
     * @param salt, password
     * returns hash string
     */
    public function checkhashSSHA($salt, $password) {
 
        $hash = base64_encode(sha1($password . $salt, true) . $salt);
        return $hash;
    }

    // Verifying employee 
    public function isEmployeeVerified($employee_id, $email, $unique_id, $created_at, $updated_at = NULL){

        if ($updated_at!=NULL){

        $stmt = $this->conn->prepare("SELECT * from users WHERE email = ? AND employee_id = ? AND unique_id = ? AND created_at = ? AND updated_at = ? AND active='0'");
        $stmt->bind_param("sssss", $email, $employee_id, $unique_id, $created_at, $updated_at);
    }
    else{

        $stmt = $this->conn->prepare("SELECT * from users WHERE email = ? AND employee_id = ? AND unique_id = ? AND created_at = ? AND active='0'");
        $stmt->bind_param("ssss", $email, $employee_id, $unique_id, $created_at);
    }
 
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
            
        // user existed 
        $stmt = $this->conn->prepare("UPDATE users SET active = '1' WHERE employee_id = ?");
        $stmt->bind_param("s", $employee_id);
        $result = $stmt->execute();
        $stmt->close();   
        return true;
        } 

    else {
            // user not existed
            $stmt->close();
            return false;
        }
    }

    //Fetching data of user
    public function dataFetch($email) {
        $stmt = $this->conn->prepare("SELECT * FROM users where email = ?");
        $stmt->bind_param("s", $email);
 
        if ($stmt->execute()) {
            $data = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            return $data;
        } else {
            $stmt->close();
            return NULL;
        }
    }

    //Fetching Drivers in same zone
    public function getSameZoneDrivers($employee_id){ 
        
        $stmt = $this->conn->prepare("SELECT zone_id FROM users where employee_id = ?");
        $stmt->bind_param("s", $employee_id);
        $stmt->execute();
        $data= $stmt->get_result()->fetch_assoc()["zone_id"];
        $stmt->close();     

        $stmt = $this->conn->prepare("SELECT employee_id, employee_name, address, designation, owns_vehicle, mobile_number, gender FROM users where zone_id= ? AND owns_vehicle = 1 AND employee_id != ? AND visibility_as_driver = 1");
        $stmt->bind_param("ss", $data, $employee_id);
 
        if ($stmt->execute()) {

                $data = $stmt->get_result()->fetch_all();
                //print_r($data);                         
                $stmt->close();
                return $data;
        }              
        else
            return false;
    }   

    //Fetching people in same zone
    public function getSameZonePeople($employee_id){ 

        $stmt = $this->conn->prepare("SELECT zone_id FROM users where employee_id = ?");
        $stmt->bind_param("s", $employee_id);
        $stmt->execute();
        $data= $stmt->get_result()->fetch_assoc()["zone_id"];
        $stmt->close();
        
        $stmt = $this->conn->prepare("SELECT employee_id, employee_name, address, designation, owns_vehicle, mobile_number, gender FROM users where zone_id= ? AND employee_id != ? AND visibility_as_passenger = 1 AND owns_vehicle = 0");
        $stmt->bind_param("ss", $data, $employee_id);
 
        if ($stmt->execute()) {

                $data = $stmt->get_result()->fetch_all();
                $stmt->close();
                return $data;
        }
                       
        else
            return false;
    }
                              
    public function storeRequest($driver_id, $passenger_id, $sent_by){

        $stmt = $this->conn->prepare("INSERT INTO request (driver_id, passenger_id, sent_by, created_at,message) VALUES(?, ?, ?, NOW(), 'Booking a ride')");
        $stmt->bind_param("sss", $driver_id, $passenger_id, $sent_by);
        $result = $stmt->execute();
        $stmt->close();
 
        // check for successful store
        if ($result) {
            $stmt = $this->conn->prepare("SELECT * FROM request WHERE driver_id = ? AND passenger_id = ? AND sent_by = ?");
            $stmt->bind_param("sss", $driver_id, $passenger_id, $sent_by);
            $stmt->execute();
            $request_row = $stmt->get_result()->fetch_all();
            //print_r($request_row);
            $stmt->close();
            if($request_row){
             return $request_row;
            }else{
             return false;
            }
        } else {
            return false;
        }
    }   

    public function doRequestExisted($driver_id, $passenger_id) {
        $stmt = $this->conn->prepare("SELECT * from request WHERE driver_id = ? AND passenger_id= ?");
        $stmt->bind_param("ss", $driver_id, $passenger_id);
        $stmt->execute();
        $stmt->store_result();
 
        if ($stmt->num_rows > 0) {
            // request existed 
            $stmt->close();
            return true;
        } else {
            // user not existed
            $stmt->close();
            return false;
        }
    }

    public function getAllReceivedRequest($employee_id) {

        $stmt = $this->conn->prepare("SELECT driver_id , passenger_id FROM request WHERE (driver_id = ? OR passenger_id = ?) AND (sent_by != ?) AND status = 0 ");
        $stmt->bind_param("sss", $employee_id, $employee_id, $employee_id);
 
        if ($stmt->execute()) {

                $received_rows = $stmt->get_result()->fetch_all();
                $stmt->close();
        }
     
     //This also tells the index that we need 
        if($received_rows){
            if ($received_rows[0][0]== $employee_id)
            $is_driver = true;
        else 
            $is_driver = false;

        $size = count($received_rows);
        $a=array();

        for ($i= 0; $i<$size; $i++){

            $this->fetchFromMain($received_rows[$i][$is_driver]);
            array_push($a,$this->fetchFromMain($received_rows[$i][$is_driver]));
        }
        return $a;
       } else{
            return false;
       }  
    }
    
    public function getCurrentRideData($driver_id,$route_id)
    {
    $stmt = $this->conn->prepare("SELECT driver_id, current_loc_lat , current_loc_long , destination_lat , destination_long FROM route WHERE driver_id= ? AND route_id= ?");
        $stmt->bind_param("ss", $driver_id , $route_id);
 
        if ($stmt->execute()) {

                $data = $stmt->get_result()->fetch_all();
               // print_r($data);                         
                $stmt->close();
                return $data;
        }
                       
        else
            return false;
    }
    
    public function fetchFromMain($employee_id){

        $stmt1 = $this->conn->prepare("SELECT * FROM main WHERE employee_id = ?");
        $stmt1->bind_param("s", $employee_id);
 
        if ($stmt1->execute()) {
            $data_main = $stmt1->get_result()->fetch_assoc();
            $stmt1->close();
            //print_r($data_main);
            return $data_main;
        }

        else
            return false;

    }

    public function acceptedRequests($employee_id)
    {
        $stmt = $this->conn->prepare("SELECT driver_id, passenger_id FROM request WHERE (driver_id = ? OR passenger_id = ? ) AND (status = 1) ");
        $stmt->bind_param("ss", $employee_id, $employee_id);

        if($stmt->execute()){

            $accepted_rows = $stmt->get_result()->fetch_all();
            $stmt->close();
            //return $accepted_rows;
        }
        if($accepted_rows){
            if ($accepted_rows[0][0]== $employee_id)
            $is_driver = true;
        else 
            $is_driver = false;

        //This also tells the index that I need 

        //echo $is_driver;

        $size = count($accepted_rows);
        $a=array();

        for ($i= 0; $i<$size; $i++){

            $this->fetchFromMain($accepted_rows[$i][$is_driver]);
            array_push($a,$this->fetchFromMain($accepted_rows[$i][$is_driver]));

        }

        return $a;
    } else{
        return false;
    }
        

    }

    public function sentRequests($employee_id)
    {
        $stmt = $this->conn->prepare("SELECT driver_id, passenger_id FROM request WHERE sent_by = ? AND status = 0 ");
        $stmt->bind_param("s", $employee_id);

        if($stmt->execute()){

            $sent_rows = $stmt->get_result()->fetch_all();
            //print_r($sent_rows);
            $stmt->close();
            //return $accepted_rows;
        }

        if($sent_rows){
           if ($sent_rows[0][0]== $employee_id)
            //show passenger details
            $is_driver = true;
        else 
            //show driver details
            $is_driver = false;

        $size = count($sent_rows);
        $a=array();

        for ($i= 0; $i<$size; $i++){
            $this->fetchFromMain($sent_rows[$i][$is_driver]);
            array_push($a,$this->fetchFromMain($sent_rows[$i][$is_driver]));
        }

        return $a; 
       }
        else return null;
    }

    public function storeInRouteTable($driver_id, $string_route, $d_latitude, $d_longitude)
    {
        //CHECKING IF ENTRY ALREADY EXISTS
        $stmt = $this->conn->prepare("SELECT * from route WHERE driver_id = ?");
        $stmt->bind_param("s", $driver_id);
        $stmt->execute();
        $stmt->store_result();
 
        if ($stmt->num_rows > 0) {
            // user existed 
            $stmt = $this->conn->prepare("UPDATE route SET route = ? WHERE driver_id = ?");
            $stmt->bind_param("ss", $string_route, $driver_id);

        } else {
            // user not existed
            $stmt = $this->conn->prepare("INSERT INTO route (driver_id, route, current_latitude, current_longitude, d_latitude, d_longitude) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssdddd", $driver_id, $string_route, $d_latitude, $d_longitude, $d_latitude, $d_longitude);
        }

        $result = $stmt->execute();
        $stmt->close();

        if ($result) {
            return true;
        } 
        else {
            return false;
        }
    }

    public function fetchOnePassengerFromRoute($driver_id)
    {
        $stmt = $this->conn->prepare("SELECT route FROM route WHERE driver_id = ?");
        $stmt->bind_param("s", $driver_id);

        if($stmt->execute()){
            $routeFetched = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            }

        if($routeFetched){
           $flag = 0;
           $passenger_list = explode(",", $routeFetched['route']);
          // print_r($passenger_list);die();

           for($i = 0; $i<count($passenger_list); $i++){
           // print_r($passenger_list[$i]);die();
            $stmt1 = $this->conn->prepare("SELECT status FROM request WHERE passenger_id = ?");
            $stmt1->bind_param("s", $passenger_list[$i]);

            if($stmt1->execute()){
                $status = $stmt1->get_result()->fetch_assoc();
                //print_r($status);
                //print_r($status['status']);
                $stmt1->close();
             
                if($status['status']==1 || $passenger_list[$i]=='shopclues'){
                    $flag = 1;
                    //print_r($passenger_list);die();
                    return $passenger_list[$i];
                }
            }
        else 
            return false;
        }
        if ($flag == 0){
            return false;
        }
        }

        else{
            return false;
        }
      }


    public function acceptRequest($driver_id,$passenger_id) {
    
    $stmt = $this->conn->prepare("UPDATE request SET status = 1 WHERE ( passenger_id = ? OR driver_id = ? ) OR ( driver_id = ? OR passenger_id = ?)");
        $stmt->bind_param("ssss", $employee_id, $requestee_id, $employee_id, $requestee_id);
        if ($stmt->execute()) 
        {
           $stmt->store_result();
             $stmt->close();
             return true;
             }
        else
             return false;
    
    }
 
    public function cancelRequest($sender_id,$receiver_id) {
    
    $stmt = $this->conn->prepare("UPDATE request SET status = -1 WHERE driver_id = ? AND passenger_id = ?");
     $stmt->bind_param("ss", $driver_id, $passenger_id);
      if ($stmt->execute()) 
      {
       $stmt->store_result();
         $stmt->close();
         return true;
         }
         else
         return false;
    
    }

    public function undoSentRequest($employee_id, $requestee_id)
    {
        $stmt = $this->conn->prepare("UPDATE request SET status = -1 WHERE sent_by = ? AND status = 0 AND ( passenger_id = ? OR driver_id = ? )");
        $stmt->bind_param("sss", $employee_id, $requestee_id, $requestee_id);
        if ($stmt->execute()) 
        {
           $stmt->store_result();
             $stmt->close();
             return true;
             }
        else
             return false;
    }

    public function updateDriverLatLong($driver_id,$latitude, $longitude)
    {
        $stmt = $this->conn->prepare("UPDATE route SET d_latitude = ?, d_longitude = ? WHERE driver_id = ?");
        $stmt->bind_param("dds", $latitude, $longitude, $driver_id);
      if ($stmt->execute()) 
      {
       $stmt->store_result();
         $stmt->close();
         return true;
         }
         else
         return false;
    }

    public function updateLatLong($driver_id, $current_lat, $current_long)
     {
     $stmt = $this->conn->prepare("UPDATE route SET current_latitude = ? , current_longitude = ? WHERE driver_id = ?");
     $stmt->bind_param("dds", $current_lat, $current_long, $driver_id);
      if ($stmt->execute()) 
      {
       $stmt->store_result();
        $stmt->close();
         return true;
         }
        else
        {
         $stmt->close();
         return false;
     }
     }

    public function fetchFromRoute($driver_id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM route WHERE driver_id = ?");
     $stmt->bind_param("s", $driver_id);
      if ($stmt->execute()) 
      {
       $result = $stmt->get_result()->fetch_assoc();
         $stmt->close();
         return $result;
         }
         else
         return false;
    }

    public function updateAccess($employee_id, $access_as)
    {
        $stmt = $this->conn->prepare("UPDATE users SET access_as = ? WHERE employee_id = ?");
        $stmt->bind_param("ds", $access_as, $employee_id);
      if ($stmt->execute()) 
      {
       $stmt->store_result();
         $stmt->close();
         return true;
         }
         else
         return false;
    }

    public function updateStatus($driver_id, $passenger_id, $new_status)
    {
        $request = $this->fetchFromRequest($driver_id, $passenger_id);
        if($request){
            $current_status = $request['status'];
           // print_r($current_status);

            if($current_status==1){

                $stmt = $this->conn->prepare("UPDATE request SET status = ? WHERE driver_id = ? AND passenger_id = ? AND status = 1 ");
                $stmt->bind_param("dss", $new_status, $driver_id, $passenger_id);
                if ($stmt->execute()) 
                {
              //$stmt->store_result();
                    $stmt->close();
                 //print_r($check);
                    return true;
                 }
            else
                 return false;
            }
            elseif ($current_status == 0) {
                
                //ACCEPTING OR REJECTING A REQUEST
                $stmt = $this->conn->prepare("UPDATE request SET status = ? WHERE driver_id = ? AND passenger_id = ? AND status = 0 ");
                $stmt->bind_param("dss", $new_status, $driver_id, $passenger_id);
                if ($stmt->execute()) 
                {
                    if($new_status == 1){

                        $stmt1 = $this->conn->prepare("UPDATE users SET visibility_as_passenger = 0 WHERE employee_id = ? ");
                        $stmt1->bind_param("s", $passenger_id);
                        $stmt1->execute();
                        $stmt1->close();

                        $stmt2 = $this->conn->prepare("UPDATE users SET number_of_seats = number_of_seats - 1 WHERE employee_id = ? AND number_of_seats > 0");
                        $stmt2->bind_param("s", $driver_id);
                        $stmt2->execute();
                        $stmt2->close();

                        $stmt3 = $this->conn->prepare("SELECT * FROM users WHERE employee_id = ?");
                        $stmt3->bind_param("s", $driver_id);
                        if ($stmt3->execute()) 
                        {
                            $result = $stmt3->get_result()->fetch_assoc();
                            $stmt3->close();
                           // return $result;
                         }
                         //else
                            //return false;
                        // if($stmt3->execute()){
                        //     // $res = $stmt3->store_result();
                        //     // print_r($res);
                        //     $seats_status = $stmt3->get_result()->fetch_assoc();
                        //     print_r($seats_status);
                        //     $stmt3->close();


                        // }else{
                        //     echo "\nIN ELSE\n";
                        //     return flase;
                        // }

                        if($result['number_of_seats'] == 0){
                            $stmt4 = $this->conn->prepare("UPDATE users SET visibility_as_driver = 0 WHERE employee_id = ? AND number_of_seats = 0");
                        $stmt4->bind_param("s", $driver_id);
                        $stmt4->execute();
                        $stmt4->close();
                        }
                    }

                    if ($new_status == -1){
               
                        $stmt1 = $this->conn->prepare("UPDATE users SET visibility_as_passenger = 1 WHERE employee_id = ? ");
                        $stmt1->bind_param("s", $passenger_id);
                        $stmt1->execute();
                        $stmt1->close();
                     
                        $stmt2 = $this->conn->prepare("UPDATE users SET number_of_seats = number_of_seats + 1 AND visibility_as_driver = 1 WHERE employee_id = ? AND number_of_seats < 4");
                        $stmt2->bind_param("s", $driver_id);
                        $stmt2->execute();
                        $stmt2->close();
                    }

                    $stmt->close();
                    return true;
                 }
            else
                 return false;

            }

            else{
                //STATE MACHINE CHECK::: Since request has not be accepted, it cannot be completed!!
                return false;
            }   
        }
        else{

            //No request found corresponding to given driver and passenger
            return false;
        }
    }

    public function fetchFromRequest($driver_id, $passenger_id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM request WHERE driver_id = ? AND passenger_id = ?");
        $stmt->bind_param("ss", $driver_id, $passenger_id);
        if ($stmt->execute()) 
        {
            $result = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            return $result;
         }
         else
            return false;
    }

    public function fetchFromUsersAccessType($employee_id)
    {
        $stmt = $this->conn->prepare("SELECT access_as FROM users WHERE employee_id = ?");
        $stmt->bind_param("s", $employee_id);
        if ($stmt->execute()) 
        {
            $result = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            $type = $result['access_as'];
            
            if($type == 1){
                //access_as = 1 => DRIVER
                return ("driver");
            }elseif($type == 0){
            //access_as = 0 => PASSENGER
                return ("passenger");   
            }
            
         }
         else
            return false;
    }

    public function test($passenger_id)
    {
        $stmt = $this->conn->prepare("SELECT driver_id, route FROM route");

        $flag = 0;
        $flag1 = 0;

        if ($stmt->execute()) 
        {
            $result = $stmt->get_result()->fetch_all();
            $stmt->close();
            //print_r($result);

            foreach ($result as $value) {
              
                 $route_array = explode(',', $value[1]);
                print_r($route_array);
                 foreach ($route_array as $pass_id) {
                    // echo("\nThis is each pass_id\n");
                    print_r($pass_id);
                     if($pass_id === $passenger_id){
                  echo "\nTHE MATCHING PASSENGER ID \n".$pass_id;
                        $flag = 1;
                        return $value[0];
                        break;
                     }
                 }
                 if($flag==0){
                    return false;
                 }
                 if($flag == 1){
                    $flag1 = 1;
                    break;
                 }
             }

             if($flag1==0){
                return false;
             }
         }
         else
            return false;
    }

    public function getDriverIdFromRoutesTable($passenger_id){
        $stmt = $this->conn->prepare("SELECT driver_id, route FROM route");
        if($stmt->execute()){
            $result = $stmt->get_result()->fetch_all();
            $stmt->close();
            //print_r($result);die();

            foreach ($result as $value) {
                // print_r($value[1]);
                 $route_array = explode(',', $value[1]);
                //print_r($route_array);die();
                 foreach ($route_array as $pass_id) {
                    //print_r($pass_id);
                     if($pass_id === $passenger_id){
                        return $value[0];
                        break;
                     }
                 }
             }
        }
        else{
            return false;
        }
    }

 

  public function createDriverRide($employee_id, $source, $destination, $ride_name, $intermediate_points, $vehicle_type, $vehicle_number, $number_of_seats)
     {
     $stmt = $this->conn->prepare("INSERT INTO rides (employee_id, source, destination, ride_name, intermediate_points, vehicle_type, vehicle_number, number_of_seats, created_at) VALUES(?, ?, ?, ?, ?, ?, ?, ?, NOW())");
     $stmt->bind_param("sssssisi", $employee_id, $source, $destination, $ride_name, $intermediate_points, $vehicle_type, $vehicle_number, $number_of_seats);
      if ($stmt->execute()) 
      {
       $stmt->store_result();
        $stmt->close();
         return true;
         }
        else
        {
         $stmt->close();
         return false;
     }
    }

public function getLatLong($address)
    {
  $curl = curl_init();
   $region = "India";

    $url = "http://maps.google.com/maps/api/geocode/json?address=$address&sensor=false&region=$region";

     curl_setopt($curl, CURLOPT_URL, $url);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
       $result_from_google_api = curl_exec($curl);
        curl_close($curl);
         $x=json_decode($result_from_google_api,true);

    return $x ; 

    }

public function getIntermediatePoints($origin, $destination)
   { 
     $curl = curl_init();
    // Origin of route
     $str_origin = "origin=".$origin;

    // Destination of route
     $str_dest = "destination=".$destination;

    // Sensor enabled
     $sensor = "sensor=false";

    // Building the parameters to the web service
     $parameters = $str_origin."&".$str_dest."&".$sensor;
      
    $url = "https://maps.googleapis.com/maps/api/directions/json?".$parameters;

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $result_from_google_api = curl_exec($curl);
    curl_close($curl);
    //print_r($result_from_google_api);
    $y=json_decode($result_from_google_api,true);

    return $y ;

     }
/*public function getIntermediatePoints($origin_latitude, $origin_longitude, $dest_latitude, $dest_longitude)
   {
     $curl = curl_init();
    // Origin of route
     $str_origin = "origin=".$origin_latitude.",".$origin_longitude;

    // Destination of route
     $str_dest = "destination=".$dest_latitude.",".$dest_longitude;

    // Sensor enabled
     $sensor = "sensor=false";

    // Building the parameters to the web service
     $parameters = $str_origin."&".$str_dest."&".$sensor;

    $url = "https://maps.googleapis.com/maps/api/directions/json?".$parameters;
    //print_r($url);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $result_from_google_api = curl_exec($curl);
    curl_close($curl);
    $y=json_decode($result_from_google_api,true);
    return $y ;

     }*/
     public function getRideByEmployeeId($employee_id) {
 
        $stmt = $this->conn->prepare("SELECT * FROM rides WHERE employee_id = ?");
        $stmt->bind_param("s", $employee_id);
 
        if ($stmt->execute()) {
            $rides = $stmt->get_result()->fetch_all();
            $stmt->close();
                return $rides;
            
        } else {
            return NULL;
        }
    }
    public function updateRideStatus($id, $active_status)
    {
        $stmt = $this->conn->prepare("UPDATE rides SET active_status = ? WHERE id = ?");
        $stmt->bind_param("di", $active_status, $id);
      if ($stmt->execute()) 
      {
       $stmt->store_result();
         $stmt->close();
         return true;
         }
         else
         return false;
    }
    public function getActiveRides() {
 
        $stmt = $this->conn->prepare("SELECT * FROM rides WHERE active_status = 1");
 
        if ($stmt->execute()) {
            $active_rides = $stmt->get_result()->fetch_all();
            $stmt->close();
           // print_r($active_rides);
                return $active_rides;
            
        } else {
            return NULL;
        }
 }
    public function haversineGreatCircleDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius)
{   
 // print_r($latitudeFrom.$longitudeFrom.$latitudeTo.$longitudeTo);   
  // convert from degrees to radians
  $latFrom = deg2rad($latitudeFrom);
  $lonFrom = deg2rad($longitudeFrom);
  $latTo = deg2rad($latitudeTo);
  $lonTo = deg2rad($longitudeTo);

  $latDelta = $latTo - $latFrom;
  $lonDelta = $lonTo - $lonFrom;
  
  $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
    cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
 
  return $angle * $earthRadius;
}
public function logOut($employee_id/*, $status*/)
    {
        $stmt = $this->conn->prepare("UPDATE users SET status = 0 WHERE employee_id = ?");
        $stmt->bind_param("s",/* $status,*/ $employee_id);
      if ($stmt->execute()) 
      {
       $stmt->store_result();
         $stmt->close();
         return true;
         }
         else
         return false;
    }
   }  
?>
