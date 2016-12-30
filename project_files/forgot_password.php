<?php  
include_once 'resource/Database.php';
include_once 'resource/session.php';
include_once 'resource/utilities.php';

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
include_once 'partials/parseForgotPassword.php';
?>

<!-- **********************************************   HTML PART   *******************************************************-->

<?php 	$page_title = 'Forgot Password';
		include_once 'partials/headers.php'; 	?>
<!----  <body> is already into the header file  -- -->

<h2>Password Reset Form</h2><hr>

<div class="container">
	<section class="col col-lg-7">
		<?php  if (isset($result) ) echo $result;  ?>	
		<?php if (!empty($form_errors) )  echo show_errors($form_errors);  ?>
	</section>
</div>
<!--                                      ---  all have id ending with 4  --                                 -->
<div class="container" >

	<section class="col col-lg-7" >

		<form action="forgot_password.php" method="post" >
  			<div class="form-group" >
    			<label for="newPasswordField4">New-Password:</label>
    			<input type="password" class="form-control" name="new_password"  id="newPasswordField4" placeholder="New Password">
  			</div>
  			<div class="form-group">
    			<label for="confirmPasswordField4">Confirm-Password:</label>
    			<input type="password" class="form-control" name="confirm_password" id="confirmPasswordField4" placeholder="Confirm Password">
  			</div>
        <input type="hidden" name="user_id" value="<?php if(isset($id)) echo $id ;?>" >
  			<button type="submit" class="btn btn-primary pull-right" name="sbt">Reset password</button>
		</form>
	</section>
	<p><a href="login.php">Back</a></p>
</div>

<?php  include_once 'partials/footers.php'; ?>
<!----  </body> is already into the fppter file  -- -->
<!-- **********************************************   HTML PART   *******************************************************-->