<?php
function getRCap($sheet){
	$file = fopen($sheet,"r");
	$header = fgetcsv($file);
	$roomCap = array();
	while(! feof($file)){
		$row = fgetcsv($file);
		if($row[0]!=""){
			$r = str_replace("\n", "",$row[0]);
			$r = str_replace(" ", "",$r);
			$roomCap[$r] = intval($row[1]);
		}
	} 

	fclose($file);
	return $roomCap;
}

function getCStr($sheet){
	$file = fopen($sheet,"r");
	$header = fgetcsv($file);
	$courseCap = array();
	while(! feof($file)){
		$row = fgetcsv($file);
		if($row[0]!=""){
			$c = str_replace("\n", "",$row[2]);
			$c = str_replace(" ", "",$c);
			$courseCap[$c] = intval($row[3]);
		}
	}

	fclose($file);
	return $courseCap;
}

function genMatrix($roomFile, $courseFile){
	$roomCap = getRCap($roomFile);
	$courseCap = getCStr($courseFile);
	$matrix = array();
	foreach ($roomCap as $r => $rs) {
		$min = 100;
		$max = -100;
		foreach ($courseCap as $c => $cs) {
			$matrix[$c][$r] = $cs/$rs;
			$min = min($min,$cs/$rs);
			$max = max($min,$cs/$rs);
		}
		// print $r." -- ". $min." -- ".$max."<br>";
	}
	return $matrix;
}

function printArr($arr){
	foreach ($arr as $key => $value) {
		// echo $key." *** ".$value[0]. " *** ".$value[1]."<br>";
		echo $key."  ***  ";
		print_r($value);
		echo "<br>";
	}
}
// print_r(getRCap("time_tables/roomCap.csv"));
// print_r(getCStr("time_tables/courseCap.csv"));

function allotPlan($matrix){
	$alloted = array();
	$freeSlots = array();
	$temp;
	foreach ($matrix as $course => $rooms) {
		$alloted[$course] = False;
		$temp = $course;
	}
	foreach ($matrix[$temp] as $room => $value) {
		$freeSlots[$room] = [1,2,3,4,5];
	}
	foreach ($matrix as $course => $rooms) {
		if(!$alloted[$course]){
			foreach ($matrix[$course] as $room => $value) {
				if(count($freeSlots[$room])>0 and ($value>0.8 and $value<=1)){
					$alloted[$course] = [$room,$freeSlots[$room][0]];
					unset($freeSlots[$room][0]);
					$freeSlots[$room] = array_values($freeSlots[$room]);
					// var_dump($freeSlots[$room]);
					// echo count($freeSlots[$room]).", ";
				}
			}
		}
	}
	return $alloted;
}
$matrix = genMatrix("time_tables/roomCap.csv","time_tables/courseCap.csv");
// printArr($matrix["CVL774"]);
$t = allotPlan($matrix);
// print count($t)."<br>";
// printArr(allotPlan($matrix));

?>