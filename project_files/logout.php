<?php  
include_once "resource/session.php";
include_once 'resource/utilities.php';

include_once 'partials/headers.php';# these are only added because of the echo the sctipt, (which needs header file as the linkage to the sweet alert files, are in the headers only)

//redirectTO('index'); 
# popupMessage($title, $text, $type, $page)
echo popupMessage("Logout Successfull",'Hope you enjoyed it :)','success','index.php');	
session_destroy();

include_once "partials/footers.php";
?>