	
<?php 	$titel = 'edit-profile' ;
		include_once 'partials/headers.php' ; 

		include_once 'resource/Database.php'; # --> for data base connection in try catch (--> parseEditProfile.php)
		include_once 'resource/utilities.php';# for flash message function


		include_once 'partials/parseEditProfile.php';
		include_once 'partials/parse_change_password.php';
		include_once 'partials/parse_deactivate.php';
?>
<!--###################################################     the HTML PART  ###########################################################-->
<?php if( !isset($_SESSION['username']) ) :?><!--user has not loged in so they are not authorized t see this page , THEY HAVE TO SIGN IN --> 
	<div class="container" align="center" style="padding-top: 30%" >
		<section>
			<p class="lead">You are not authorized to view this page. Please <a href="login.php">login</a> ,if not a member please <a href="signup.php">signup</a>! </p>
			<p class="lead">HACK US !  and please give us <a href="feedback.php">feedback</a>  on our security! </p>
		</section>
	</div>

<?php elseif( isset($_SESSION['username']) && !isset($_GET['user_id']) ) :?><!--ie if the user is logen in but they just pass the address of the edit_signup page, and 								not passed the value of the required field, ie when the value of this variable is not set ALTHOUGH  that of the user session is set..  --> 
	<!--Not_authorized($message,$page,$link_ame)--> 
	<?php echo Not_authorized('You are not authorized to view this page.', 'profile.php','Back'); ?> 

<?php elseif( isset($_SESSION['username']) &&  isset($_GET['user_id'])  && ($id != $_SESSION['id']) ) :?><!-- this is the case when the user is signed in AND he also 											sends the value of the requried field ie. the user_id from the URL too (which is then decoded and saved into $id variable)  BUT 											THIS  VALUE DOES NOT MATCHES THE current user id which is saved as a SESSION VARIABLE TO US --> 
	<!--Not_authorized($message,$page,$link_ame)--> 
	<?php echo Not_authorized('Oops.. nothing to see here!', 'profile.php','Back'); ?> 
	
<?php elseif( isset($_SESSION['username']) &&  isset($_GET['user_id'])  && ($id === $_SESSION['id']) ) : ?> <!--i could have used the else condition here, BUT IT ISMORE 										SECURE TO USE A IF(or else if ) CONDTION -> WHICH SPECIFICALLY FOLLOWS THE CONDITION AND THEN FURTHER PROCESS, rather than if all 										fails then it shows up ,, which if in any seniarion it fails then the else would run and they will be able to see the pages -->
	<h2>Edit-Profile</h2><hr>
	<div class="container">
		<section class="col col-lg-7">
			<?php  if (isset($result) ) echo $result;      # these are for successfull AND unsuccessful  messages  ?>	
			<?php if (!empty($form_errors) )  echo show_errors($form_errors); # these are for error messages  ?>
		</section>

		<section class="col col-lg-7"><!--now making it form so that it can fubmet the cahnged values-->
			<form action="#" method="post" enctype="multipart/form-data">
		  		<div class="form-group">
		    		<label for="emailField5">E-mail:</label>
		    		<input type="text" class="form-control" name="email" id="emailField5"  value="<?php echo $current_email;?>">
		  		</div>
		  		<div class="form-group">
		    		<label for="usernameField5">Username:</label>
		    		<input type="type='text" class="form-control" name="username" id="usernameField5"  value="<?php echo $current_username;?>">
		  		</div>
		  		<div class="form-group">
		          <label for="fileField5">Profile photo:</label>
		          <input type="file" name="avatar" id="fileField5" >
		          <input type="hidden" name="token" value="<?php echo _token() ?>">
		          <button type="submit" class="btn btn-primary pull-right" name="edit_sbt">Save changes</button>
		        </div>
			</form>
		</section>
	</div>

	<br>
	<!-- change password -->
	<div class="container">
	<h4>Change Password</h4><hr>
		<section class="col col-lg-7">
			<?php  if (isset($result_2) ) echo $result_2;      # these are for successfull AND unsuccessful  messages  ?>	
			<?php if (!empty($form_errors_2) )  echo show_errors($form_errors_2); # these are for error messages  ?>
		</section>
		<section class="col col-lg-7"><!--now making it form so that it can fubmet the cahnged values-->
			<form action="#" method="post" enctype="multipart/form-data">
		  		<div class="form-group">
		    		<label for="current_password7">Current password:</label>
		    		<input type="password" class="form-control" name="current_password" id="current_password7" placeholder="current password" value="" >
		    	</div>
		  		<div class="form-group">
		    		<label for="new_password7">New password:</label>
		    		<input type="password" class="form-control" name="new_password" id="new_password7" placeholder="new password" value="" >
		    	</div>	
		  		<div class="form-group">
		    		<label for="confirm_password7">Confirm password:</label>
		    		<input type="password" class="form-control" name="confirm_password" id="confirm_password7" placeholder="confirm password" value="" >
		  		</div>
		  			<input type="hidden" name="id" value="<?php if(isset($id)) echo $id; ?>">
		        	<input type="hidden" name="token" value="<?php echo _token() ?>">
		          	<button type="submit" class="btn btn-primary pull-right" name="change_btn">Change Password</button>
			</form>
		</section>
	</div>

	<br>
	<!-- deactivate your account -->
	<div class="container">
	<h4>Deactivate your account</h4><hr>
		<section class="col col-lg-7">
			<?php  if (isset($result_3) ) echo $result_3;  ?>	
			<?php if (!empty($form_errors_3) )  echo show_errors($form_errors_3);  ?>
		</section>
		<section class="col col-lg-7">
			<form action="#" method="post" enctype="multipart/form-data">
		  			<input type="hidden" name="id" value="<?php if(isset($id)) echo $id; ?>">
		        	<input type="hidden" name="token" value="<?php echo _token() ?>">
		          	<button onclick="return confirm('Do you really want to deactivate your account ??')" type="submit" class="btn btn-danger btn-block pull-right" name="deactivate_btn">Deactivate your account</button>
			</form>
		</section>
	</div>
	<?php  if (isset($toEcho) ) echo $toEcho;  # now the form will be loaded with thw new values and then after that the popup message will come   ?>	
<?php else: ?>
	<p>Sorrey something went wrorng...! </p>
<?php endif ?>



