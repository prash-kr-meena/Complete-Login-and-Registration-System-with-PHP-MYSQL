<?php 
	$page_title = 'User Authentication System';
	include_once 'partials/headers.php';


 	include_once 'resource/Database.php' ;
 	include_once 'resource/session.php' ; // to start the session. 

?>
    <div class="container">
    	<div class="flag" style='border:1px solid green' >
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

<?php 
	include_once 'partials/footers.php';
?>
