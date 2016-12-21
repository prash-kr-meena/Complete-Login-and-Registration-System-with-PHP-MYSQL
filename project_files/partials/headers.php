<!-- IT CONTAINS THE HEADER AND THE NAVIGATION (so it will be present everywheere )-->
<?php include_once 'resource/session.php' ?>
<!DOCTYPE html>
<html>
<head lang="en">
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title> <?php if ( isset($page_title) ) echo $page_title; ?> </title>

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

          <!-- WHAT IF I CREATE TWO DIFFERENT NAVIGATION BAR FILES, ie one for loged in user and one for non loged in users  -> THAT WILL BE PAIN IN THE ASS, AS THEN WE HAVE TO INCLUDE THE NAVIGATION BAR IN EACH PAGE, AND ALSO WE HAVE TO CHECK THE CONDITION FOR THAT -> REAL PAIN IN ASS  -->
          <?php if( !isset($_SESSION['username']) ) :// ie. when user is not loged in?>
              <li ><a href="login.php">Login</a></li>
              <li><a href="signup.php">Sign-up</a></li>
              <li><a href="feedback.php">Feedback</a></li>
          <?php else: ?>
              <li><a href="#">My profile</a></li>
              <li><a href="logout.php">Logout</a></li>
          <?php endif ?>

            
          </ul>
        </div><!--/.nav-collapse -->

      </div>
    </nav>
