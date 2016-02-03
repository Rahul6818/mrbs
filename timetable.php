
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<?php

// $Id: admin.php 2838 2014-05-22 09:54:02Z cimorrison $

require "defaultincludes.inc";
// $user = getUserName();
// 

print_header($day, $month, $year, isset($area) ? $area : "", isset($room) ? $room : "");
checkAuthorised();

// Also need to know whether they have admin rights
$user = getUserName();
$is_admin = (authGetUserLevel($user) >= 2);
?>

<br>

<form action='time_table_upload.php' method='post' enctype="multipart/form-data" id="timetable" novalidate="novalidate">
<!-- <b> Semseter Duration </b>
<br>
  Starting Date : 
  <input type="date" name="sem_start" />
  Ending Date : 
  <input type="date" name="sem_end" />
<br>
<br> -->
<b> Class Time Table</b> (upload CSV format file only) <b>:</b>
  <input type="file" name="class_time_table" id="class_time_table" />
  <div id="w_ctt" color="red" style="display:none;color:red;">Please choose a file.</div>
<br>
<br>
<b> Tutorial Time Table</b> (upload CSV format file only) <b>:</b>
  <input type="file" name="tut_time_table" id="tut_time_table"/>
  <div id="w_ttt" color="red" style="display:none;color:red;">Please choose a file.</div>
<br>
<br>
<b> Minor-1 Time Table </b>
<!-- <br> -->
  <!-- Starting Date : 
  <input type="date" name="m1_start" />
  Ending Date : 
  <input type="date" name="m1_end" />
  <br><br> -->
  (upload CSV format file only) :
  <input type="file" name="m1_time_table" id="m1_time_table" />
  <div id="w_m1tt" color="red" style="display:none;color:red;">Please choose a file.</div>
<br>
<br>
<b> Minor-2 Time Table  </b>
<!-- <br>
  Starting Date : 
  <input type="date" name="m2_start" />
  Ending Date : 
  <input type="date" name="m2_end" />
  <br><br> -->
  (upload CSV format file only) :
  <input type="file" name="m2_time_table" id="m2_time_table"/>
  <div id="w_m2tt" color="red" style="display:none;color:red;">Please choose a file.</div>
<br>
<br>
<b> Major-Exam Time Table </b>
<!-- <br>
  Starting Date : 
  <input type="date" name="m_start" />
  Ending Date : 
  <input type="date" name="m_end" />
  <br><br> -->
   (upload CSV format file only) :
  <input type="file" name="m_time_table" id="m_time_table" />
  <div id="w_mtt" color="red" style="display:none;color:red;">Please choose a file.</div>
<br>
<br>
<b> Holidays Details </b> (upload CSV format file only) :
  <input type="file" name="holidays" id="holidays"/>
  <div id="w_h" color="red" style="display:none;color:red;">Please choose a file.</div>
<br>
<br>
<b> Day Swapping </b> (upload CSV format file only) :
  <input type="file" name="day_swap" id="day_swap"/>
  <div id="w_ds" color="red" style="display:none;color:red;">Please choose a file.</div>
<br>
<br>
<br>
<input type="submit" name = "submit" value="Submit" id="submit">
</form>
 
<?php
output_trailer();
?>
<script type="text/javascript">
$(document).ready(function(){
  $("#submit").click(function(){
    var ctt = $("#class_time_table").val();
    var ttt = $("#tut_time_table").val();
    var m1_tt = $("#m1_time_table").val();
    var m2_tt = $("#m2_time_table").val();
    var m_tt = $("#m_time_table").val();
    var holi = $("#holidays").val();
    var ds = $("#day_swap").val();

    var go = true
    if(ctt.length==0){
      $("#w_ctt").css("display","block");
      go = false;
    }  
    if(ttt.length==0){
      $("#w_ttt").css("display","block");
      go = false;
    }  
    if(m1_tt.length==0){
      $("#w_m1tt").css("display","block");
      go = false;
    }  
    if(m2_tt.length==0){
      $("#w_m2tt").css("display","block");
      go = false;
    }  
    if(m_tt.length==0){
      $("#w_mtt").css("display","block");
      go = false;
    }  
    if(holi.length==0){
      $("#w_h").css("display","block");
      go = false;
    }  
    if(ds.length==0){
      $("#w_ds").css("display","block");
      go = false;
    }
    var file = $('input[type="file"]').val();
    var exts = ['csv','CSV'];
    if(file){
      var get_ext = file.split('.');
      get_ext = get_ext.reverse();
      if($.inArray(get_ext[0].toLowerCase(),exts)>-1){
        // alert("CSV file uploaded");
      }else{
        alert("Please upload CSV file only");
        go = false;
      }

    }
    // alert(go);
    if(!go){
      // document.getElementById("timetable").submit()
      // alert("chutiyapa");
        $("form").submit(function(event){
          event.preventDefault();
          // alert("Submit prevented");
      });
    }

  });
});
</script>