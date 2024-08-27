<?php
	$usname="orangeweb_dbcoin";
	$db_password="German@2012";
	$db_name="orange_websupport_coin";
	$db_host = "localhost";

	$link=mysqli_connect($db_host, $usname, $db_password);
	mysqli_select_db($link,$db_name);
?>