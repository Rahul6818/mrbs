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
$i=0;
$conn = dbConnect();
while($i<1000000){
	print $i."<br>";
	$sql = "INSERT INTO test_table(name,entry_number) VALUES('Rahul Patidar','2012CS10244')";
	$conn->query($sql);
	$i=$i+1;
}
?>