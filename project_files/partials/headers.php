<!-- IT CONTAINS THE HEADER AND THE NAVIGATION (so it will be present everywheere )-->
<?php include_once 'resource/session.php'; # --> started the session here, now we will be able to set the session variables?>
<?php  include_once 'resource/utilities.php';; //its not ==> ../resource/utilities because, its the header of login page , so the header page is currently on the login page, and from the login page the address, of the utilities page is this only.WELL NOTE CAREFULLY IT IS IMPORTANT TO MENTION IT HERE AS ITS NOT JUST FOR THE LOGIN PAGE ITS THE HEADER OF ALL THE PAGES, Ie. INDEX (the main part which is loaded bydefault every time)--> and it does not have this file so we have to do that here
# if you see we already have included this file in  the login page, already so it is not necessary to use it here, well
# NO ERROR BECASUE its include_once--> thats what it do , it does not produce error in for more than one apperence ?>

<!DOCTYPE html>
<html>
<head lang="en">
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title><?php if( isset($page_title) ) echo $page_title; ?></title>
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet">
    <!-- sweet alert -->
    <script src="js/sweetalert.min.js"></script>
    <link rel="stylesheet" type="text/css" href="css/sweetalert.css">  
</head>
<body>

<!-- ####################################    from the bootstrap template  ##############################################-->
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="index.php">User Authentication System </a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
          <i class="hide"><?php echo guard() ?></i>
          <?php if ( !isset($_SESSION['username']) ) isCookieValid($db) ; ?>
          <?php if( isset($_SESSION['username']) ) :?> 
              <li><a href="profile.php">My profile</a></li>
              <li><a href="logout.php">Logout</a></li>
          <?php else: ?>
              <li ><a href="login.php">Login</a></li>
              <li><a href="signup.php">Sign-up</a></li>
              <li><a href="feedback.php">Feedback</a></li>
          <?php endif ?>
          </ul>
        </div>
      </div>
    </nav>
    <!-- ######################################## END the bootstrap template##########################################-->