<!DOCTYPE html>
<html>
<head lang="en">
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>User Authentication - Homepage</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet">
</head>
<body>
 <!-- ####################################################################################################################-->

<?php include_once 'resource/Database.php' ?>
<?php include_once 'resource/session.php'  // to start the session. ?> 

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
          <a class="navbar-brand" href="#">Project name</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="index.php">Home</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="#contact">Contact</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

    <div class="container">
	
      <div class="flag">
        <h1  >User Authentication System </h1>
        <p class="lead">
				<?php if( !isset($_SESSION['username']) ) :?>
				<p class="lead">You are not currently signed in <a href="login.php">login</a> Not a member yet? <a href="signup.php">signup</a></p>
			<?php else: ?>
				<p class="lead">you are loged in as {<?php echo $_SESSION['username'] ?>} <a href="logout.php">logout</a></p>
			<?php endif ?>

			<p class="lead">Please give us <a href="feedback.php">feed-back</a>.</p>

			<a href="test.php">test</a>
		</p>
      </div>

    </div><!-- /.container -->
<!-- ###################################################  END  ##########################################################-->

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>

</body>
</html>