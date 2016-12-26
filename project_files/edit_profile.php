<?php 	$titel = 'edit profile' ;
		include_once 'partials/headers.php' ; 

		include_once 'resource/Database.php'; # --> for data base connection in try catch (--> parseEditProfile.php)
		include_once 'resource/utilities.php';# for flash message function
		//include_once 'partials/parseEditProfile.php';
?>

<?php 	
	if ( isset($_SESSION['username']) ) {  #--> ie if the user seession is set, then only he will be able to change, it
		#$user_id = $_GET[0]; 		# --> it will give me undefined offset
		$user_id = $_GET['user_id'];# --> due to this the user can not directly come to the editpage--> well if they are loged in then they can come --> but as we are not taking their ID from the session variable (as they are only taken by the decoded value pased by the edit profile link ) so if the user is loged in and he put the address with the value ,eg. edit_profile?user_id=8=-8043209  THEN MUCH CHANCES ARE THAT HE WILL GET AFCAURCE A WRONG ID -=>
		# BUT FOR MORE SECURITY (--> if he got lucky and he found the id form our database --> now all the query are based upon this id only so carefull)WE WILL CHECK WHETHER FOR THIS ID THE USER NAME IS THE SAME OR NOT,-->(ie. the username is equal to the one whose session is set or not ! )
		$id = base64_decode($user_id);
		#echo $id;	
		if ($id === $_SESSION['id']) {
			# it means the same user is comming through the right path(ALTHOUGH WHAT THE USER CAN DO AFTER HE LOGES If he can copy that encrypted link value which is shown then he can write the path and put the value too..==> HE WILL BE ABLE TO DIRECTLY COME TO THE EDIT PROFILE PAGE WITHOUT CLICKING THE LINK)==> as in this case the encrypted id after the decode will match the value of the id which is present in the session
			try{
				$sqlQuery = "SELECT *
							FROM register.users 
							WHERE id = :id";
				$statement = $db->prepare($sqlQuery);
				$statement->execute( array(':id'=>$id) );

				if ($row = $statement->fetch()) {
					$username = $row['username'];
					$password = $row['password'];
					$email = $row['email'];
				}
			}catch(PDOexception $ex){#flashMessage($message,$color='red')-->returns the string   --> by default red
				echo flashMessage("something went wrong, WHILE COLLECTING THE DATA FROM THE DATABASE -->".$ex->getMessage());

			}

		}else{} #--> do nothing they will be shown the message of autherization later
	} else{} #--> do nothing they will be shown the message of autherization later
?>


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
		<section class="col col-lg-7"><!--now making it form so that it can fubmet the cahnged values-->
			<form action="" method="post" >
		  		<div class="form-group">
		    		<label for="usernameField1">E-mail:</label>
		    		<input type="text" class="form-control" name="username"  id="usernameField1" placeholder="Username" value="<?php echo $email; ?> ">
		  		</div>
		  		<div class="form-group">
		    		<label for="password1">Username:</label>
		    		<input type="type='text" class="form-control" name="password" id="password1" placeholder="Password" value="<?php echo $username;?>">
		  		</div>
		  		<div class="form-group">
		    		<label for="password1">Password:</label>
		    		<input type="text" class="form-control" name="password" id="password1" placeholder="Password" value="<?php echo $password;?>" >
		  		</div>
		    		<button type="submit" class="btn btn-primary pull-right" name="edit_sbt">Save changes</button>
		  		</div>	
			</form>
		</section>
	</div>
<?php endif ?>



