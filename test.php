<?php
 /*
// json response array
$response = array("error" => FALSE);

$latitudeFrom = 28.5245787;
$longitudeFrom = 77.206615;
$latitudeTo = 28.4513871;
$longitudeTo = 77.0719039;

function haversineGreatCircleDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius)
{  echo "hi";
  // convert from degrees to radians
  $latFrom = deg2rad($latitudeFrom);
  $lonFrom = deg2rad($longitudeFrom);
  $latTo = deg2rad($latitudeTo);
  $lonTo = deg2rad($longitudeTo);

  $latDelta = $latTo - $latFrom;
  $lonDelta = $lonTo - $lonFrom;

  $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
    cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
  $cal = $angle * $earthRadius;
  print_r($cal);
  return $cal;
}
$distance = acos(SIN($latFrom)*SIN($latTo)+COS($latFrom)*COS($latTo)*COS($longTo-$longFrom))*6371;

$distance = (3958*3.1415926*sqrt(($latTo-$latFrom)*($latTo-$latFrom) + cos($latTo/57.29578)*cos($latFrom/57.29578)*($longTo-$longFrom)*($longTO-$longFrom))/180);


*/
?>