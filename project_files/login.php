<?php  
include_once "resource/session.php";
include_once "resource/Database.php";	
include_once "resource/utilities.php";

include_once 'partials/parseLogin.php'; #contain all login validation, processing , security
?>

<!-- **********************************************   HTML PART   *******************************************************-->
<?php 	$page_title = 'Login form';
		include_once 'partials/headers.php'; 	

		?>
<!----  <body> is already into the header file  -- -->
<h2>Login form</h2><hr>

<div class="container">
	<section class="col col-lg-7">
		<?php  if (isset($result) ) echo $result;  ?>	
		<?php if (!empty($form_errors) )  echo show_errors($form_errors);  ?>
	</section>
</div>

<!--                                      ---  all have id ending with 1  --                                  -->
<div class="container">
<section class="col col-lg-7" >
	<form action="" method="post" >
  		<div class="form-group">
    		<label for="usernameField1">Username:</label>
    		<input type="text" class="form-control" name="username"  id="usernameField1" placeholder="Username">
  		</div>
  		<div class="form-group">
    		<label for="password1">Password:</label>
    		<input type="password" class="form-control" name="password" id="password1" placeholder="Password">
  		</div>
  		<div class="checkbox">
    		<label>
      		<input type="checkbox" name="remember">Remember me. 
    		</label>

    		<button type="submit" class="btn btn-primary pull-right" name="login_sbt">Sign in</button>
  		</div>	
  		</br>
  		<a href="forgot_password.php">Forgot password ?</a>
	</form>
</section>
<p><a href="index.php">Back</a></p>
</div>

<?php  include_once 'partials/footers.php'; ?>
<!----  </body> is already into the fppter file  -- -->
<!-- **********************************************   HTML PART   *******************************************************-->