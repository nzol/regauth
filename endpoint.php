jsonCallback(
	{
		"jsonReturns":
		[
			{
				"returnData": "<?php

session_start();

include("setup.php");
include("globalsfunctions.php");

$Mysql = new mysqli($Host,$Username,$Password,$Database);



// when ever the script is run, the following will run to check token status. 

if(!isset($_SESSION['regauth_token'])) {

	$newToken = substr(str_shuffle("abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz12345678901234567890"), 0,31);

	$addToken = $Mysql->query("INSERT INTO $Tokens_Table (Token,UserId) VALUES ('$newToken','0')");
 
	$_SESSION['regauth_token']=$newToken;	

}
else {

	$Action = $_GET['Action'];

	switch($Action) {

	case "checkSignIn":

		$TokenUser = token_user($_SESSION['regauth_token']);

		if($TokenUser=="0") {
			echo "guest";
		}
		else {
			echo "registered";
		}

	break;

	case "printToken":

		echo $_SESSION['regauth_token'];

	break;

	case "printTokenUser":

		$TokenUser = token_user($_SESSION['regauth_token']);
		echo $TokenUser;

	break;

	case "signIn":


		$EmailAddress = $_GET['EmailAddress'];
		$Password = $_GET['Password'];

		$getUser = $Mysql->query("SELECT * FROM $Users_Table WHERE EmailAddress='$EmailAddress' AND Password='$Password'");

		if($getUser->num_rows==0) {

			echo "failed";

		}

		else {

			$Token = $_SESSION['regauth_token'];
			$rsUser = $getUser->fetch_assoc();

			$UserId = $rsUser['Id'];

			$updateToken = $Mysql->query("UPDATE $Tokens_Table SET UserId='$UserId'");

			echo "success";

		}

	break;

	case "signOut":

		$getMyToken = $_SESSION['regauth_token'];

		// delete token

		$delToken = $Mysql->query("DELETE $Tokens_Table WHERE Token='$getMyToken'");

		echo "success";


	break;


	case "checkPermissionHeld":

		$UserId = $_GET['UserId'];
		$PermissionName = $_GET['PermissionName'];

		$getPermission = $Mysql->query("SELECT * FROM $Permissions_Table WHERE UserId='$UserId' AND PermissionName='$PermissionName'");

		if($getPermission->num_rows==0) {
			echo "notheld";
		}
		else {
			echo "held";
		}

	break;

	
	}

}

?>
"

			}

		]
	}
);