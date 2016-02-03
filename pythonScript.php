<?php
	require "defaultincludes.inc";
	// require_once "mrbs_sql.inc";

ini_set('max_execution_time', 3000);
// date_default_timezone_set('Asia/Kolkata');
// connect to database and return a connection to server
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

# loading data read by python script
function loadPythonData(){
	exec('python pdfReader.py',$output,$return);
	$roomList = array();
	$roomSchedule = array();
	for($i=0;$i<count($output);){
		$room = $output[$i];
		array_push($roomList,$room);
		$dayWiseSlot = array(); // store schedule of all 7 days for a room
		for($j=$i+1;$j<($i+8);$j++){
			$daySchedule =  str_replace("'","",substr($output[$j],1,-1));
			if(strlen($daySchedule)>0){
				$slots = explode("],",$daySchedule);
				$dayTT = array();        // store the schedule for a day
				foreach ($slots as $slot) {
					$slot = str_replace("]","",(str_replace("[","", $slot)));
					$slot = explode(",", $slot);
					$ext = (str_replace(" ","",substr($slot[0],-2)));
					$slot[0] = str_replace("PM","",str_replace("AM","",$slot[0]));
					$s_e = explode(' - ',$slot[0]);
					$course = $slot[1];
					$start = date("G:i", strtotime($s_e[0].$ext));
					$end = date("G:i", strtotime($s_e[1].$ext));
					array_push($dayTT,[$course,$start,$end]);
				}
				if(($j-$i)==1){$dayWiseSlot['Mon'] = $dayTT;}
				else if(($j-$i)==2){$dayWiseSlot['Tue'] = $dayTT;}
				else if(($j-$i)==3){$dayWiseSlot['Wed'] = $dayTT;}
				else if(($j-$i)==4){$dayWiseSlot['Thu'] = $dayTT;}
				else if(($j-$i)==5){$dayWiseSlot['Fri'] = $dayTT;}
				else if(($j-$i)==6){$dayWiseSlot['Sat'] = $dayTT;}
				else if(($j-$i)==7){$dayWiseSlot['Sun'] = $dayTT;}					
				
			}
		}
		$roomSchedule[$room]= $dayWiseSlot;
		$i = $i + 8;
	}
	return [$roomList,$roomSchedule];// return list of rooms and schedule corresponding to each room
}


// get area code for a room
function getAreaCode($room){
	$l = strlen($room);
	$ac = str_replace(" ","",substr($room,0,$l-3));
	return $ac;
}


// get list of area code from database
function loadAreaCode(){
	$conn = dbConnect();
	$sql = "select id,area_code from mrbs_area where 1";
	if ($conn->query($sql) == TRUE) {
		$res = $conn->query($sql);
	} else {
	   	echo "Error: " . $sql . "<br>" . $conn->error;
	}	
	$codes = array();
	while($row = mysqli_fetch_array($res))
	{
		$codes[$row[1]] = $row[0];
		// echo $row[1]." - ".$row[0];
	}
	return $codes;
}

# store timetable corresponding to each room
function storeRooms($rooms){
	$conn = dbConnect();
	$codes = loadAreaCode();
	foreach ($rooms as $room) {	
	 	$AreaCode = $codes[getAreaCode($room)];
	 	// echo "Room: ".$room.", Code: ".$AreaCode."<br>";
	 	if($AreaCode==0){$AreaCode=8;}
	 	$sql = "insert into mrbs_room(`area_id`,`room_name`,`capacity`) values('$AreaCode','$room',150)";
	 	if ($conn->query($sql) == TRUE) {
	     	echo "New record created successfully";
	 	} else {
	     	echo "Error: " . $sql . "<br>" . $conn->error;
	 	}	
	 }
}

function getHolidays(){
	$conn = dbConnect();
	$sql = "select date from mrbs_holidays";
	if ($conn->query($sql) == TRUE) {
		$res = $conn->query($sql);
	} else {
	   	echo "Error: " . $sql . "<br>" . $conn->error;
	}	
	$holidays = array();
	while($row = mysqli_fetch_array($res))	{
		// echo $row[0]." , ".date('d-m-y',$raw[0])."<br>";
		// echo $row[0].", ".date('y-m-d',$row[0])."<br>";
		array_push($holidays, date('Y-m-d',$row[0]));
	}
	return $holidays;
}


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
			// echo date('Y-m-d',strtotime($date))."   ".$new_day."<br>";
			array_push($swap_day,$new_day);
			array_push($swap_date,date('Y-m-d',strtotime($date)));
		}
	}
	fclose($file);
	return [$swap_date,$swap_day];
}


// daywise timetable , schedule of each day
function sortDayWise($TimeTable){
	$dayWiseTT = array('Mon'=>[],'Tue'=>[],'Wed'=>[],'Thu'=>[],'Fri'=>[],'Sat'=>[],'Sun'=>[]);
	foreach ($TimeTable as $room => $schedule) {
		 // echo $room."<br>";
		foreach ($schedule as $day => $slots) {

			array_push($dayWiseTT[$day],[$room,$slots]);
		}
	}
	return $dayWiseTT;
}

//load room ids from database
function loadRoomIds(){
	$conn = dbConnect();
	$sql = "select id,room_name from mrbs_room";
 	$res = $conn->query($sql);
	$roomIds = array();
	while($row = mysqli_fetch_array($res)){
		$roomIds[$row[1]] = $row[0];
		// echo $row[1]." - ".$row[0]."<br>";
	}
	$conn->close();
	return $roomIds;
}

// store timetable in database give start and end date of classes
function storeTT($TimeTable,$start,$end){
	$conn = dbConnect();
	$holidays = getHolidays();
	$RoomIds = loadRoomIds();
	$date = $start;
	$swaps = getSwapDays("time_tables/daySwap.csv");
	// print "End: ".$end."<br>";
	while($end>=$date){
		if(in_array($date,$holidays)){
			echo "Holiday: ".$date."<br>";
			// continue;
		}
		// $conn = dbConnect();
		else{
			if(in_array($date,$swaps[0])){
				$i = array_search($date, $swaps[0]);
				$day = $swaps[1][$i];
				print "DaySwap: ".$date."<br>";
			}
			else{
				$day = 	date("D",strtotime($date));
			}
			$daySchedule = $TimeTable[$day];
			// print "<br> Before: ".$date.", ".$day."<br>";
			foreach ($daySchedule as $values) {
				$room = $values[0];
				$roomId = $RoomIds[$room];
				// print count($values[1])."<br>";
				foreach ($values[1] as $value) {
					// print "here..";
					$course = trim($value[0]," \t\n\r\0\x0B");
					$start_hour = date('H',strtotime($value[1]));
					$start_min = date('i',strtotime($value[1]));

					$end_hour = date('H',strtotime($value[2]));
					$end_min = date('i',(strtotime($value[2])))+date("i",strtotime('00:10:00'));

					$s = mktime($start_hour,$start_min,0,date("n",strtotime($date)),date("j",strtotime($date)),date("Y",strtotime($date)));
					$e = mktime($end_hour,$end_min,0,date("n",strtotime($date)),date("j",strtotime($date)),date("Y",strtotime($date)));
					$sql = "INSERT INTO `mrbs_entry`(`start_time`, `end_time`,`room_id`,`name`, `type`, `description`)
							VALUES ('$s','$e','$roomId','$course','I','Regular Class')";
					$conn->query($sql);	
				}
				
			}
		}
		$date = date("Y-m-d",strtotime("+1 day",strtotime($date)));
		
		// print "After: ".$date."<br>";
	}
	$conn->close();
}


$pyRes = loadPythonData();

$WTT = sortDayWise($pyRes[1]);

storeTT($WTT,'2015-10-1','2015-11-23');
// $holidays = getHolidays();
// echo in_array('2015-07-17', $holidays);
// getSwapDays("time_tables/daySwap.csv");

?>