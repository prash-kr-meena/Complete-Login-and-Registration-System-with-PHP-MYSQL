<?php 
	$page_title = 'User Authentication System';
	include_once 'partials/headers.php';


 	include_once 'resource/Database.php' ;
 	//include_once 'resource/session.php' ; // to start the session. (dont need it ,everywhere as $_SESSION is a global variable so , its needed in the login file only where the session has to start)

?>

    <div class="container">
    	<div class="flag" >
        	 <h1  >User Authentication System</h1>		<!-- NOTE : session is deleted each time we close the browser, or if 																			you login from the incognito mode -->
        	 <p class="lead">							<!-- isset($_COOKIE['authenticationSystem']) -->
        	 <!-- -->
			     <?php if( isset($_SESSION['username']) || isset($_COOKIE['authenticationSystem'])) :?>
				      <p class="lead">you are loged in as {<?php echo $_SESSION['username'] ?>} <a href="logout.php">logout</a> </p>
				      <p><?php  echo base64_decode($_COOKIE['authenticationSystem']) ;?></p>
			     <?php else: ?>
			     	<p class="lead">You are not currently signed in <a href="login.php">login</a> Not a member yet? <a href="signup.php">signup</a></p>
              <!-- now we dont need the logot file, i can call the logout function from here -->
			     <?php endif ?>

			<p class="lead">Please give us <a href="feedback.php">feed-back</a>.</p>

			<a href="test.php">test</a>
			</p>
     	</div>

    </div><!-- /.container -->

<?php 
	include_once 'partials/footers.php';
?>
