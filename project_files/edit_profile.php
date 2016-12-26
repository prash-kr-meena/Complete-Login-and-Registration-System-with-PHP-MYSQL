<?php 	$titel = 'edit profile' ;
		include_once 'partials/headers.php' ; 

		include_once 'resource/Database.php'; # --> for data base connection in try catch (--> parseEditProfile.php)
		include_once 'resource/utilities.php';# for flash message function


		include_once 'partials/parseEditProfile.php';
?>
<!--###################################################     the HTML PART  ###########################################################-->
<?php if( !isset($_SESSION['username']) || ($id != $_SESSION['id']) ) :?>
	<div class="container" align="center" style="padding-top: 30%" >
		<section">
				<p class="lead">You are not authorized to view this page. Please <a href="login.php">login</a> ,if not a member 					please <a href="signup.php">signup</a>! </p>
				<p class="lead">HACK US !  and please give us <a href="feedback.php">feedback</a>  on our security! </p>
		</section>
	</div>
<?php else : ?>
	<h2>Edit-Profile</h2><hr>
	<div class="container">

		<section class="col col-lg-7">
			<?php  if (isset($result) ) echo $result;      # these are for successfull AND unsuccessful  messages  ?>	
			<?php if (!empty($form_errors) )  echo show_errors($form_errors); # these are for error messages  ?>
		</section>

		<section class="col col-lg-7"><!--now making it form so that it can fubmet the cahnged values-->
			<form action="#" method="post" >
		  		<div class="form-group">
		    		<label for="emailField5">E-mail:</label>
		    		<input type="text" class="form-control" name="email" id="emailField5"  value="<?php echo $email;?>">
		  		</div>
		  		<div class="form-group">
		    		<label for="usernameField5">Username:</label>
		    		<input type="type='text" class="form-control" name="username" id="usernameField5"  value="<?php echo $username;?>">
		  		</div>
		  		<div class="form-group">
		    		<label for="password5">Password:</label>
		    		<input type="text" class="form-control" name="password" id="password5" value="<?php echo $password;?>" >
		  		</div>
		    		<button type="submit" class="btn btn-primary pull-right" name="edit_sbt">Save changes</button>
		  		</div>	
			</form>
		</section>

	</div>
<?php endif ?>



