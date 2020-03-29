<?php

//$con = mysql_connect("localhost","crm","");
$con = mysql_connect("108.179.252.184","cons0645_drawk","drawk");

if (!$con) {
  die('Could not connect: ' . mysql_error());
}
mysql_select_db("cons0645_crm", $con);

?>