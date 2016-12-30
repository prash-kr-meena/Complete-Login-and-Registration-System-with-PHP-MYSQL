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

<div class="container">
	<section class="col col-lg-7">
		<?php  if (isset($result) ) echo $result;  ?>	
		<?php if (!empty($form_errors) )  echo show_errors($form_errors);  ?>
	</section>
</div>
<!--                                      ---  all have id ending with 4  --                                 -->


<?php if(!isset($_GET['id']) ) :?>
	<?php echo Not_authorized('You are not authorized to view this page.', 'profile.php','Back'); ?> 
<?php elseif( isset($_GET['user_id'])  && ($id !== $_SESSION['id']) ) :?>
	<?php echo Not_authorized('Oops.. nothing to see here!', 'profile.php','Back'); ?> 
	
<?php elseif( isset($_GET['user_id'])  && ($id === $_SESSION['id']) ) : ?> 

<h2>Password Reset Form</h2><hr>

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
        	<input type="hidden" name="token" value="<?php if(function_exists('_token')) echo _token(); ?>" >
  			<button type="submit" class="btn btn-primary pull-right" name="sbt">Reset password</button>
		</form>
	</section>
	<p><a href="login.php">Back</a></p>
</div>
<?php else: ?>
	<p>Sorrey something went wrorng...! </p>
<?php endif ?>

<?php  include_once 'partials/footers.php'; ?>
<!----  </body> is already into the fppter file  -- -->
<!-- **********************************************   HTML PART   *******************************************************-->