<?php 
include_once "resource/Database.php";		// soule purpose of this is to make the connection to the database and if there is any error(exception) then it will show it.
// well we have done that in the index page also so, well if you have an error it will show at that time BUT NOTE : you have to add this everywhere you try to intereact to the database (any where you wanna use SQL statements)

// process the form
include_once "resource/utilities.php";

include_once "partials/parseSignup.php";
?>

<!-- **********************************************   HTML PART   *******************************************************-->
<!-- <body> is already into the header file-->
<?php 	$page_title = 'Signup form';
		include_once 'partials/headers.php'; 	?>

<!----  <body> is already into the header file  -- -->
<h2>Sign-up Form </h2><hr/>

<div class="container">
	<section class="col col-lg-7">
		<?php  if (isset($result) ) echo $result;  ?>	
		<?php if (!empty($form_errors) )  echo show_errors($form_errors);  ?>
	</section>
</div>
<!--                                      ---  all have id ending with 2  --                                 -->

<div class="container" >

	<section class="col col-lg-7">

		<form action="" method="post" >
			<div class="form-group">
    			<label for="emailField2">E-mail:</label>
    			<input type="text" class="form-control" name="email"  id="emailField2" placeholder="E-mail">
  			</div>
  			<div class="form-group">
    			<label for="usernameField2">Username:</label>
    			<input type="text" class="form-control" name="username"  id="usernameField2" placeholder="Username">
  			</div>
  			<div class="form-group">
    			<label for="password2">Password:</label>
    			<input type="password" class="form-control" name="password" id="password2" placeholder="Password">
  			</div>
        <input type="hidden" name="token" value="<?php echo _token() ?>">
  			<button type="submit" class="btn btn-primary pull-right" name="signup_sbt">Sign up</button>
		</form>
	</section>
	<p><a href="index.php">Back</a></p>
</div>

<?php  include_once 'partials/footers.php'; ?>
<!----  </body> is already into the fppter file  -- -->
<!-- **********************************************   HTML PART   *******************************************************-->