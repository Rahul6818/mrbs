<?php

// $Id: header.inc 2784 2013-11-21 10:48:22Z cimorrison $


// Print the page header
function print_theme_header($day, $month, $year, $area, $room)
{
  global $mrbs_company, $mrbs_company_logo, $mrbs_company_url, $mrbs_company_more_info,
         $search_str, $locale_warning;
  global $tbl_entry, $tbl_room, $tbl_area;
  global $PHP_SELF, $HTTP_HOST, $QUERY_STRING;
  global $view_week_number, $weekstarts, $times_along_top, $periods, $enable_periods;
  global $auth, $max_level;
  global $default_language_tokens, $disable_automatic_language_changing, $override_locale;
  global $select_options;
  global $ajax_refresh_rate;
  global $main_table_cell_border_width, $main_cell_height;
  global $timetohighlight;
  
  $page = basename($PHP_SELF, ".php");
  $user = getUserName();
  $is_admin = (authGetUserLevel($user) >= $max_level);
  $user_level = authGetUserLevel($user);
  // Need to set the timezone before we can use date()
  get_area_settings($area);

  // If we dont know the right date then make it up 
  if (!$day)
  {
    $day   = date("d");
  }
  if (!$month)
  {
    $month = date("m");
  }
  if (!$year)
  {
    $year  = date("Y");
  }
  if (!isset($search_str))
  {
    $search_str = "";
  }
  
  http_headers();
  echo DOCTYPE;
?>

<html>
  <head>
  <link rel="icon" type="image/png" href="images/favicon.ico">
  <meta charset="<?php echo get_charset(); ?>">
  <title><?php echo get_vocab("IIT Delhi, MRBS") ?></title>
  
  <?php
  require_once "style.inc";
  require_once "js.inc";
  ?>
  
  </head>
  
  <?php
  // Put the filename in as a class to aid styling.   
  // (Use a class rather than id to avoid specificity problems)
  echo "<body class=\"non_js ".htmlspecialchars($page)."\">\n";
   
    // Add a class of "js" so that we know if we're using JavaScript or not
    // and remove the non_js class (it's sometimes useful to know that we're
    // not running JavaScript)
  ?>
    <script type="text/javascript">
      //<![CDATA[
      $('body').addClass('js').removeClass('non_js');
      //]]>
    </script> 

    <div class="screenonly">

<?php // show a warning if this is using a low version of php
  if (substr(phpversion(), 0, 1) == 3)
  {
    echo "<h1>" . get_vocab("not_php3") . "</h1>\n";
  }
  if (!empty($locale_warning))
  {
    echo "[Warning: ".$locale_warning."]";
  }
?>

  <table id="banner">
    <tr id="company">
    <td id="company"><?php echo "<a href='index.php'>". get_vocab("Home")."</a>";?></td>
      <td id="company">
        <div>
          <?php
          echo "<div id=\"logo\">\n";
          if (!empty($mrbs_company_logo))
          {
            echo "<img src=\"$mrbs_company_logo\" align=\"middle\">";
          }
          if (!empty($mrbs_company_url))
          {
            echo "<a href=\"$mrbs_company_url\" target='_blank'><span>".$mrbs_company."</span></a><br>";
            
          }
          ?>
        </div>

      </td>
      <?php
      // For session protocols that define their own logon box...
      if (function_exists('PrintLogonBox'))
      {
        echo "<td>\n";
        echo "<div id=\"logon_box\">\n";
        PrintLogonBox(); 
        echo "</div>\n";
        echo "</td>\n";
      }
      ?>
      </tr>
      </table>
      
      <table id = "banner">
      <tr id="company" >
      <?php
      $query_str = "day=$day&amp;month=$month&amp;year=$year";
      if (!empty($area))
      {
        $query_str .= "&amp;area=$area";
      }
      if (!empty($room))
      {
        $query_str .= "&amp;room=$room";
      }
      
      echo "<td style='background-color: #525252;'>\n";
      echo "<a href=\"help.php?$query_str\">" . get_vocab("help") . "</a>\n";
      echo "</td>\n";
      
      echo "<td style='background-color: #525252;'>\n";
      echo "<a href=\"admin.php?$query_str\">" . get_vocab("rooms") . "</a>\n";
      echo "</td>\n";
      
      echo "<td style='background-color: #525252;'>\n";
      echo "<a href=\"report.php?$query_str\">" . get_vocab("Advanced Search") . "</a>\n";
      echo "</td>\n";
      ?>
      
      <td  style='background-color: #525252;'>
        <form id="header_search" method="get" action="search.php">
          <div>
            <?php echo get_vocab("search") ?>
            <input type="search" name="search_str" value="<?php echo htmlspecialchars($search_str) ?>" required>
            <input type="hidden" name="day"        value="<?php echo $day        ?>">
            <input type="hidden" name="month"      value="<?php echo $month      ?>">
            <input type="hidden" name="year"       value="<?php echo $year       ?>">
            <?php
            if (!empty($area))
            {
              echo "<input type=\"hidden\" name=\"area\" value=\"$area\">\n";
            }
            if (!empty($room))
            {
              echo "<input type=\"hidden\" name=\"room\" value=\"$room\">\n";
            }
            ?>
          </div>
        </form>
      </td>

      <td style='background-color: #525252;' id="company">
        <form action="day.php" method="get" id="Form1">
          <div>
            <?php
            // Give the form id as the optional fi y"  style="background-color: #525'fth parameter because we want
            // the form to be automatically submitted when the datepicker is closed
            genDateSelector("", $day, $month, $year, "Form1");
            if (!empty($area))
            {
              echo "<input type=\"hidden\" name=\"area\" value=\"$area\">\n";
            }
            if (!empty($room))
            {
              echo "<input type=\"hidden\" name=\"room\" value=\"$room\">\n";
            }
            // Although the datepicker will automatically go to the new date when
            // the date is changed, we still need a submit button because there
            // are occasions when you want to go to the date without changing it -
            // for example when you've been on a Search or Report page
            echo "<input type=\"submit\" value=\"" . get_vocab("goto") . "\">\n";
            ?>
           </div>
        </form>
        </td>

      <td style='background-color: #525252;'>
        <?php
        // Provide a link to the list of bookings awaiting approval
        // (if there are any enabled areas where we require bookings to be approved)

        $approval_somewhere = some_area('approval_enabled', TRUE);
        // if ($approval_somewhere && (authGetUserLevel($user) >= 1))
        if ($approval_somewhere)
        {
          $sql_approval_enabled = some_area_predicate('approval_enabled');
          // Find out how many bookings are awaiting approval
          // (but only for areas where approval is required)
          // echo "required: ".$sql_approval_enabled."<br>";
          $sql = "SELECT COUNT(*)
                    FROM $tbl_entry E, $tbl_room R,";
          if($user_level!=0){$sql .=" mrbs_users U,";} 
                  $sql.="$tbl_area A
                   WHERE (status != 0)
                     AND E.room_id = R.id
                     AND R.area_id = A.id
                     AND R.disabled = 0
                     AND A.disabled = 0
                     AND $sql_approval_enabled";
          if ($user_level==0 )
          {
            // Ordinary users can only see their own
            $sql .= " AND create_by='" . sql_escape($user) . "'";
          }
          if($user_level>0)
          {
            $sql.=" AND U.name ='".$user ."' AND U.area_id = A.id";
          }
          $n_outstanding = sql_query1($sql);
          // echo "pending: ".$n_outstanding."<br>";
          if ($n_outstanding < 0)
          {
            trigger_error(sql_error(), E_USER_WARNING);
            fatal_error(FALSE, get_vocab("fatal_db_error"));
          }
          echo "<div id=\"n_outstanding\"" .
               (($n_outstanding > 0) ? " class=\"outstanding\"" : '') .
               ">\n";
          echo "<a href=\"pending.php?day=$day&amp;month=$month&amp;year=$year&amp;area=$area" . 
               ((!empty($room)) ? "&amp;room=$room" : "") . 
               "\">$n_outstanding " . get_vocab("Pending Bookings") . "</a>\n";
          echo "</div></td>\n";
          echo "<td style='background-color: #525252;'><a href='timetable.php'>Upload Time Table</a>";
        }
        ?>
      </td>
    </tr>
  </table>
</div>

<div id="contents">
<?php

} // end of print_theme_header()

