<?php
function getSwapDays($sheet){
	$file = fopen($sheet,"r");
	$header = fgetcsv($file);
	$swap_date = array();
	$swap_day = array();
	while(! feof($file)){
		$row = fgetcsv($file);
		if($row[0]!=""){
			$date_split = explode("-", $row[1]);
			$date = $date_split[2]."-".$date_split[1]."-".$date_split[0];
			$cur_day = $row[2];
			$new_day = $row[3];
			array_push($swap_day,$new_day);
			array_push($swap_date,date('Y-m-d',strtotime($date)));
		}
	}
	fclose($file);
	return [$swap_date,$swap_day];
}


function getAcadCal($sheet){
	$file = fopen($sheet,"r");
	$header = fgetcsv($file);
	$events = array();
	while(! feof($file)){
		$row = fgetcsv($file);
		if($row[0]!=""){
			$event = $row[1];
			$start = $row[2];
			$end = $row[3];
			
			$date_split = explode("-", $row[2]);
			$date = $date_split[2]."-".$date_split[1]."-".$date_split[0];
			
			$date_split = explode("-", $row[3]);
			$end = $date_split[2]."-".$date_split[1]."-".$date_split[0];
			while($date<=$end){
				if(array_key_exists($date, $events)){
					$events[$date] = $events[$date]."<br>".$event;	
				}
				else{
					$events[$date] = $event;
				}
				$date = date("y-m-d",strtotime("+1 day",strtotime($date)));
			}
		}
	}
	fclose($file);
	return $events;
}

function printArr($arr){
	foreach ($arr as $key => $value) {
		// echo $key." *** ".$value[0]. " *** ".$value[1]."<br>";
		echo $key."  ***  ";
		print_r($value);
		echo "<br>";
	}
}

$events = getAcadCal("time_tables/acad_cal.csv");
// printArr($events);

?>