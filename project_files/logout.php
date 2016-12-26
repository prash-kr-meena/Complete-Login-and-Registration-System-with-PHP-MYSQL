<?php  
include_once "resource/session.php";
include_once 'resource/utilities.php';

include_once 'partials/headers.php';

unset($_SESSION['username']);
unset($_SESSION['id']);

unset($_SESSION['fingerPrint']); 	
unset($_SESSION['lastActive']);

session_regenerate_id(true); 
session_destroy();


if ( isset($_COOKIE['authenticationSystem']) ) {
	
	unset($_COOKIE['authenticationSystem']) ;
	setcookie('authenticationSystem',null, -1, '/');
}else{
}

echo popupMessage("Logout Successfull",'Hope you enjoyed it :)','success','index.php');	

include_once "partials/footers.php";
?>	