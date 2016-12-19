<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>Homepage</title>
</head>
<body>
<h2>User Authentication System </h2><hr>
 
<?php include_once 'resource/Database.php' ?>
<?php include_once 'resource/session.php'  // to start the session. ?> 

<?php if( !isset($_SESSION['username']) ) :?>
	<p>You are not currently signed in <a href="login.php">login</a> Not a member yet? <a href="signup.php">signup</a></p>
<?php else: ?>
<p>you are loged in as {<?php echo $_SESSION['username'] ?>} <a href="logout.php">logout</a></p>
<?php endif ?>

<p>Please give us <a href="feedback.php">feed-back</a>.</p>

<a href="test.php">test</a>

</body>
</html>