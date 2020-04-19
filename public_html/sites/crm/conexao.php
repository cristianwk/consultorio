<?php

//$con = mysql_connect("localhost","crm","");
//$con = mysql_connect("108.179.252.184","cons0645_drawk","drawk");
$con = mysql_connect("br540.hostgator.com.br","cons0645_drawk","drawk");

if (!$con) {
  die('Could not connect: ' . mysql_error());
}
mysql_select_db("cons0645_crm", $con);

?>