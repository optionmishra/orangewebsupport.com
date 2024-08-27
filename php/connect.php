<?php
$usname = "orangedb_user";
$db_password = "Y9M3qNU6c@QCve";
$db_name = "orange_db";
$db_host = "35.154.100.160";

$link = mysqli_connect($db_host, $usname, $db_password);
mysqli_select_db($link, $db_name);


