<?php 	include_once 'partials/headers.php'; ?>
<?php 	include_once 'partials/parsePasswordRecovery.php'; ?>

<div class="container">
	<section class="col col-lg-7">
		<h2>Password Recovery</h2><hr>
	
		<div >
			<?php if(isset($result))  echo $result; ?>
			<?php if (!empty($form_errors) )  echo show_errors($form_errors);  ?>
		</div>
		<div class="container">
			<p >To reset password reset link, please enter your email address in the form below</p>
		</div>	
		<form action="" method="post">
			<div class="form-group">
	    		<label for="emailField6">Email Address</label>
				<input type="text" class="form-control" name="email" id="emailField6" placeholder="email">
				<input type="hidden" name="token" value="<?php if(function_exists('_token')) echo _token(); ?>" >
	  		</div>
			<button type="submit" name="recovery_btn" class="btn btn-primary pull-right">Recover Password</button>
		</form>
	</section>
	<a href="login.php">Back</a>
</div>
<?php 	include_once 'partials/footers.php'; ?>
