<?PHP
function readCSV($csvFile){
    $file_handle = fopen($csvFile, 'r');
    $newArray= array();
    fgetcsv($file_handle, 1024);
    $i=0;
    while (!feof($file_handle) ) {
        $line_of_text[] = fgetcsv($file_handle, 1024);
     

        if (!array_key_exists($line_of_text[$i][1],$newArray)){
        	
      $newArray[$line_of_text[$i][1]]=$line_of_text[$i][0];
  }
   else{

$newArray[$line_of_text[$i][1]]=$newArray[$line_of_text[$i][1]].",".$line_of_text[$i][0];
      
   }
   $i++;
    }
    fclose($file_handle);
    return $newArray;
}


// Set path to CSV file
$csvFile = 'pvt_labels_pids_with_leaf_sorted.csv';

$csv = readCSV($csvFile);
/*echo '<pre>';
print_r($csv);
echo '</pre>';
*/
$file = fopen("boost.csv","w");
$keys=array_keys($csv);
$arrayWrite=array();
foreach ($keys as $key)
  {
  	$arrayWrite[0]="INSERT INTO `shopclue_cart`.`clues_upsell_leaf_boost_ids` (`leaf_id`, `boosted_pids`) VALUES ('";
  	$arrayWrite[1]=$key."','";
    $arrayWrite[2]=$csv[$key]."');";

  	
 fputcsv($file,$arrayWrite);
  }

fclose($file); 

?>