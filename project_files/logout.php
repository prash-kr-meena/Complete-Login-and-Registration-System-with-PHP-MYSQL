<?php  
include_once "resource/session.php";
include_once 'resource/utilities.php';

include_once 'partials/headers.php';# these are only added because of the echo the sctipt, (which needs header file as the linkage to the sweet alert files, are in the headers only)

//redirectTO('index'); 
# popupMessage($title, $text, $type, $page)
echo popupMessage("Logout Successfull",'Hope you enjoyed it :)','success','index.php');	

if ( isset($_COOKIE['authenticationSystem']) ) {
	unset($_COOKIE['authenticationSystem']);# unset the cookie and then destroy the session(ie now he has no more REMEMBER FUNCTIONALITY)
	session_destroy();
}else{
	session_destroy();//just siply destroy the session (WHEN HE DOES NOT SELECTED THE REMEMBER F=ME FUNCTIONALITY)
}

include_once "partials/footers.php";
?>