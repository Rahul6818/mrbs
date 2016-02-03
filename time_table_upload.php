<?php

require "defaultincludes.inc";
$user = getUserName();
$is_admin = (authGetUserLevel($user) >= $required_level);

print_header($day, $month, $year, isset($area) ? $area : "", isset($room) ? $room : "");

echo "<br>";

	if(isset($_POST['sem_start'])){
		echo "Sem Start : ".$_POST['sem_start'];
	}



	///////////////////           class_time_table         /////////////////////////
	if(isset($_FILES['class_time_table'])){
      $errors= array();
      $file_name = $_FILES['class_time_table']['name'];
      $file_size =$_FILES['class_time_table']['size'];
      $file_tmp =$_FILES['class_time_table']['tmp_name'];
      $file_type=$_FILES['class_time_table']['type'];
      if($file_size > 2097152){
         $errors[]='File size must be excately 2 MB';
      }
      
      if(empty($errors)==true){
         move_uploaded_file($file_tmp,"time_tableU/".$file_name);
         echo "Successfully uploaded - Class Time Table<br>";
      }
      else{
         print_r($errors);
      }
   }

   ///////////////////           tut_time_table         /////////////////////////
   if(isset($_FILES['tut_time_table'])){
      $errors= array();
      $file_name = $_FILES['tut_time_table']['name'];
      $file_size =$_FILES['tut_time_table']['size'];
      $file_tmp =$_FILES['tut_time_table']['tmp_name'];
      $file_type=$_FILES['tut_time_table']['type'];
      if($file_size > 2097152){
         $errors[]='File size must be excately 2 MB';
      }
      
      if(empty($errors)==true){
         move_uploaded_file($file_tmp,"time_tableU/".$file_name);
         echo "Successfully uploaded - Tutorials Time Table<br>";
      }
      else{
         print_r($errors);
      }
   }

   ///////////////////////////// minor 1 time table ///////////////////////////////
   if(isset($_FILES['m1_time_table'])){
      $errors= array();
      $file_name = $_FILES['m1_time_table']['name'];
      $file_size =$_FILES['m1_time_table']['size'];
      $file_tmp =$_FILES['m1_time_table']['tmp_name'];
      $file_type=$_FILES['m1_time_table']['type'];
      if($file_size > 2097152){
         $errors[]='File size must be excately 2 MB';
      }
      
      if(empty($errors)==true){
         move_uploaded_file($file_tmp,"time_tableU/".$file_name);
         echo "Successfully uploaded - Minor:1 Time Table<br>";
      }
      else{
         print_r($errors);
      }
   }

   ///////////////////////////// minor 2 time table ///////////////////////////////
   if(isset($_FILES['m2_time_table'])){
      $errors= array();
      $file_name = $_FILES['m2_time_table']['name'];
      $file_size =$_FILES['m2_time_table']['size'];
      $file_tmp =$_FILES['m2_time_table']['tmp_name'];
      $file_type=$_FILES['m2_time_table']['type'];
      if($file_size > 2097152){
         $errors[]='File size must be excately 2 MB';
      }
      
      if(empty($errors)==true){
         move_uploaded_file($file_tmp,"time_tableU/".$file_name);
         echo "Successfully uploaded - Minor:2 Time Table<br>";
      }
      else{
         print_r($errors);
      }
   }



   ///////////////////////////// major time table ///////////////////////////////
   if(isset($_FILES['m_time_table'])){
      $errors= array();
      $file_name = $_FILES['m_time_table']['name'];
      $file_size =$_FILES['m_time_table']['size'];
      $file_tmp =$_FILES['m_time_table']['tmp_name'];
      $file_type=$_FILES['m_time_table']['type'];
      if($file_size > 2097152){
         $errors[]='File size must be excately 2 MB';
      }
      
      if(empty($errors)==true){
         move_uploaded_file($file_tmp,"time_tableU/".$file_name);
         echo "Successfully uploaded - Major Time Table<br>";
      }
      else{
         print_r($errors);
      }
   }


   ///////////////////////////// holidays time table ///////////////////////////////
   if(isset($_FILES['holidays'])){
      $errors= array();
      $file_name = $_FILES['holidays']['name'];
      $file_size =$_FILES['holidays']['size'];
      $file_tmp =$_FILES['holidays']['tmp_name'];
      $file_type=$_FILES['holidays']['type'];
      if($file_size > 2097152){
         $errors[]='File size must be excately 2 MB';
      }
      
      if(empty($errors)==true){
         move_uploaded_file($file_tmp,"time_tableU/".$file_name);
         echo "Successfully uploaded - Holidays Details<br>";
      }
      else{
         print_r($errors);
      }
   }

   ///////////////////////////// day Swap time table ///////////////////////////////
   if(isset($_FILES['day_swap'])){
      $errors= array();
      $file_name = $_FILES['day_swap']['name'];
      $file_size =$_FILES['day_swap']['size'];
      $file_tmp =$_FILES['day_swap']['tmp_name'];
      $file_type=$_FILES['day_swap']['type'];
      if($file_size > 2097152){
         $errors[]='File size must be excately 2 MB';
      }
      
      if(empty($errors)==true){
         move_uploaded_file($file_tmp,"time_tableU/".$file_name);
         echo "Successfully uploaded - Day Swapping Details<br>";
      }
      else{
         print_r($errors);
      }
   }
?>