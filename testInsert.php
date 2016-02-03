<?php
// $html = "http://localhost/mrbs/web/edit_entry.php";
// $dom = new DOMDocument;
// $dom->loadHTMLFile($html);
// $forms = $dom->getElementsByTagName('label');
// echo "Count: ".count($forms)."<br>";
// $inputes = array();
// foreach ($forms as $form) {
//         echo "form : ".($form->getAttribute('action'))." ".($form->getAttribute('id'))."<br>";
//         $children = $form->childNodes;
//         foreach ($children as $child) {
//         	echo "ID : ".($child->getNodePath())."<br>";
//         }
// }
// $html = $dom->saveHTML();



$servername = "localhost";
$username = "mrbs";
$password = "mrbs-password";

// Create connection
$conn = new mysqli($servername,$username, $password,'mrbs');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
echo "Connected successfully";
$sql = "insert into mrbs_entry(`start_time`,`end_time`,`room_id`,`create_by`,`name`) values('10:00','13:00',3,'bob','COL776')";
if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
$conn->close();
?>




<?php
// Set the default timezone to use. Available as of PHP 5.1
date_default_timezone_set('UTC');

// Prints: July 1, 2000 is on a Saturday
echo "July 1, 2000 is on a " . date("l", mktime(0, 0, 0, 7, 1, 2000))."<br>";

// Prints something like: 2006-04-05T01:02:03+00:00
echo mktime(1, 2, 3, 4, 5, 2006);
?>