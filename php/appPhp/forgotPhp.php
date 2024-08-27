<?php 
include_once "connect.php";

$emailTxt = $_POST['emailTxt'];
$mobileTxt = $_POST['mobileTxt'];

//$sql = "SELECT * FROM web_user WHERE email='$emailTxt' AND class='$classTxt' AND mobile='$mobileTxt'";
$sql = "SELECT * FROM web_user WHERE email='$emailTxt' AND mobile='$mobileTxt' AND status='1'";

$query = mysqli_query($link,$sql);
$numrows = mysqli_num_rows($query);
$login_counter = mysqli_num_rows($query);

if ($login_counter > 0) {
	$data = mysqli_fetch_array($query);
	$emailId = $data["email"];
	$passTxt = $data["password"];
	print "phpResult=Success|$emailId|$passTxt";

}else {
	print "phpResult=The login details don't match our records.";
}
?>