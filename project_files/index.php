<?php 
	$page_title = 'User Authentication System';
	include_once 'partials/headers.php';

 
 	include_once 'resource/Database.php' ;
 	//include_once 'resource/session.php' ; // to start the session. (dont need it ,everywhere as $_SESSION is a global variable so , its needed in the login file only where the session has to start)

?>

    <div class="container">
    	<div class="flag" >
        	 <h1  >User Authentication System</h1><hr><!-- NOTE : session is deleted each time we close the browser, or if 																			you login from the incognito mode -->

        	 </br></br></br></br></br></br></br></br></br></br> <!-- add an image here -->
        	 
        	 <p class="lead">							<!-- isset($_COOKIE['authenticationSystem']) -->
        	 <!--BUT NOTE: in this case when we logout the session is distroyed (in both the case either he has choosed the remember me funcionality or not -> see the LOGOUT.PHP file ) 
        	 THE ERROR IS BECAUSE , even after hte logout is done ,the cookie is set,so one of the condition becomes true, and now it want to display the username, which is no longer exists as the session is destroyed so for that we have to set the session when the cookie is not deleted,OR UNSET  -->

			     <?php if( isset($_SESSION['username']) ) : # NO need to worry about the cookie setup on here as the cookie set is checked and handled in header only , so if the cookie is set it starts the session there only ,so no need of any more condition in here ;) ?>

				      <p class="lead">you are loged in as {<?php echo $_SESSION['username'] ?>} see your profile : 
				      	<a href="profile.php">profile</a>
				      	</br>
				      	<a href="logout.php">logout</a> </p>
			     <?php else: ?>
			     	<p class="lead">You are not currently signed in <a href="login.php">login</a> Not a member yet? <a href="signup.php">signup</a></p>
			     	<p><?php # echo !extension_loaded('openssl')?"not loaded":"loaded"; ?></p>
              <!-- now we dont need the logot file, i can call the logout function from here -->
			     <?php endif ?>

			<p class="lead">Please give us <a href="feedback.php">feed-back</a>.</p>

			<a href="test.php">test</a>
			</p>
     	</div>

    </div><!-- /.container -->
    
<?php include_once 'partials/footers.php'; ?>
