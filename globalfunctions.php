<?php


function token_user($Token) {

	global $Mysql;

	$getToken = $Mysql->query("SELECT * FROM session_tokens WHERE Token='$Token'");
	$rsToken = $getToken->fetch_assoc();

	$TokenUser = $rsToken['UserId'];

	return $TokenUser;

}


function checkPermission($PermissionId) {

	global $Mysql;

	$CurrentToken = $_SESSION['javelin_token'];
	$UserId = token_user($CurrentToken);

	$getPermissions = $Mysql->query("SELECT * FROM users_permissions WHERE UserId='$UserId' AND PermissionId='$PermissionId'");

	if($getPermissions->num_rows==0) {

		return false;

	}
	else {
		return true;
	}


}


?>