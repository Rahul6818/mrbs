<?php

function dbConnect(){
	$servername = "localhost";
	$username = "mrbs";
	$password = "mrbs-password";
	// Create connection
	$conn = new mysqli($servername,$username, $password,'mrbs');

	// Check connection
	if ($conn->connect_error) {
    	die("Connection failed: " . $conn->connect_error);
	}
	echo "Connected successfully <br>";
	return $conn;
}

function getHolidays($holiday_sheet){  // sheet with column1->S.No and column2-> date of holiday
	$file = fopen($holiday_sheet,"r");
	$header = fgetcsv($file);

	$holidays = array();
	while(! feof($file)){
		$row = fgetcsv($file);
		if($row[1]!=""){
			$date_split = explode("-", $row[1]);
			$date = $date_split[2]."-".$date_split[1]."-".$date_split[0];
			// $mk_date = strtotime($raw_date);
  			// print $raw_date.", ".$mk_date."<br>";
  			array_push($holidays,[$date,$row[2]]);
  			// print_r([$date,$row[2]]);
  			// echo "<br>";
  		}
  	}
	fclose($file);
	return $holidays;
}

function getSwapDays($sheet){
	$file = fopen($sheet,"r");
	$header = fgetcsv($file);
	$swap_days = array();
	while(! feof($file)){
		$row = fgetcsv($file);
		if($row[0]!=""){
			$date_split = explode("-", $row[1]);
			$date = $date_split[2]."-".$date_split[1]."-".$date_split[0];
			$cur_day = $row[2];
			$new_day = $row[3];
			// echo $date."   ".$new_day."<br>";
			array_push($swap_days,[$date,$new_day]);
		}
	}
	fclose($file);
	return $swap_days;
}
//getHolidays("time_tables/holidays.csv");
//getSwapDays("time_tables/daySwap.csv");
function loadHolidays($holidays){
	// $timezone = 'Asia/Kolkata';
	date_default_timezone_set('Asia/Kolkata');
	$conn = dbConnect();
	foreach ($holidays as $holiday){
		$date = explode("-",$holiday[0]);
		$day = $date[2];
		$month = $date[1];
		$year = $date[0];
		$timestamp = mktime(12, 0, 0, $month, $day, $year);
		$des = str_replace("'","\'",$holiday[1]);
		echo $holiday[0].", ".date('d-m-y',$timestamp).", ".date('l dS \o\f F Y h:i:s A',$timestamp)."<br>";
		$sql = "INSERT INTO `mrbs_holidays`(`date`,`description`) VALUES('$timestamp','$des')";
		$query = $conn->query($sql);
	}
	$conn->close();
}
loadHolidays(getHolidays("time_tables/holidays.csv"));