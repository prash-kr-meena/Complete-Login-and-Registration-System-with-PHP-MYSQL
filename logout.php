<?php  
include_once "resource/session.php";
include_once 'resource/utilities.php';

include_once 'partials/headers.php';# these are only added because of the echo the sctipt, (which needs header file as the linkage to the sweet alert files, are in the headers only)

//redirectTO('index'); 
# popupMessage($title, $text, $type, $page)
//echo popupMessage("Logout Successfull",'Hope you enjoyed it :)','success','index.php');	

unset($_SESSION['username']); // well session destroy will do this same job , but this is for extra security conformation
unset($_SESSION['id']);

session_regenerate_id(true); # --> its to be safe from the session highjacking attack, stelling the session cookies, mostly-> when using public system services
	# WHAT IT DOES is , IT REPLACES THE current session id with a new one ,

# session_regenerate_id() --> should come later teh session destroy, --> ofcource then only it will generate the session id
session_destroy();


if ( isset($_COOKIE['authenticationSystem']) ) {
	
	unset($_COOKIE['authenticationSystem']) ;	//it will unset the cookies,BUT FOR MORE SURITY, WE DESTROY ITS TIME , as WELL AS SET ITS DATA TO NULL,
	setcookie('authenticationSystem',null, -1, '/');

	//echo popupMessage("if",'Hope you enjoyed it :)','success','index.php');	
}else{
	
	//echo popupMessage("else",'Hope you enjoyed it :)','success','index.php');	
}

echo popupMessage("Logout Successfull",'Hope you enjoyed it :)','success','index.php');	

include_once "partials/footers.php";
?>	