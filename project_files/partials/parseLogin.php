<?php 
# conditions to pass the login security validation and processing
if ( isset($_POST['login_sbt'], $_POST['token']) ) {  //ie. if the login form is submitted then first validate this form and then if its 										valid process the form.
	// good thing is we will use the same utilities function to validate our signin form.
	if ( validate_token($_POST['token']) ) { #   validate_token( $_POST['token'] ) === true    -> no need to write this  if executes only for true..
		// do the stuff what we were doing first

		#initialize the array to hold errors
		$form_errors= array();

		# validate begins: =============================  for login form  =============================================
		$required_fields = array('username', 'password');
		#error due to empty fields
		$form_errors = array_merge( $form_errors, check_empty_fields($required_fields) );
		#error due to minimum length
		$fields_to_check_length = array('username'=>4, 'password'=>6);
		$form_errors = array_merge( $form_errors,check_min_length($fields_to_check_length) );
		#validation ends: =============================================================================================

		if ( empty($form_errors) ) { 	//ie. if no errors then process the form.
			// all good no error , so now collect the form data
			$user = $_POST['username'];
			$password =  $_POST['password'];
			#$remember = $_POST['remember']; this is wrong ,it will give exception when it is not set, SO
			isset( $_POST['remember'] )? $remember = 'yes' : $remember=''; # empty

			try{
				// check if the user exists in the database  --> using the sql statement
				$sqlQuery = "SELECT * FROM register.users WHERE username=:username";// here :username is the key of the array in execute fun.
				$statement = $db->prepare($sqlQuery);
				$statement->execute( array(':username'=> $user ) );

				if($row = $statement->fetch() ) { # $statement->fetch()  --> fetches the data from the databse as an associative array , so 								$row is an associative array with the keys as the column names in the database, and values as the data in the data stored in the database..

					$id = $row['id'];						# storing the data from the databases  for further checking 
					$hashed_password = $row['password'];	# for further verification and processing
					$username = $row['username'];
					$activated = $row['activated'];
					if ($activated === "1") {
						if ( password_verify($password, $hashed_password) ) {  # password_verify() --> inbuilt function for comparing the hashed pasword by the nohashed															 password -> entered by the user
						// IF VERIFIED , we have to start the SESSION FOR THIS USER..
						$_SESSION['id'] = $id;
						$_SESSION['username'] = $username;

						##	ADDING A LITTEL MORE SECURITY TO THE WEBSITE ##
						# md5(str);			$_SERVER['REMOTE_ADDR']---> returns the ip of the user,		$_SERVER['HTTP_USER_AGENT'] ---> gives the browser information 
						$fingerPrint = md5(  $_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT']  ); # hashing these informatins --> this informatino will be taken when the user loges in to the website..
						$_SESSION['lastActive'] = time();# we have created two more session variables, to track our users , with their acitve time and their machine identity, ie. there ip and their browser information
						$_SESSION['fingerPrint'] = $fingerPrint; # there might be a case in which the $fingerprint statement does not work --> or something goes wrong, so for that case in utilities, we also check that the seeion for the user is also set or not


						if ( $remember === "yes") {
								rememberMe($id); # sending the userid to set the cookie(id is encrypted format)
							}	
						# popupMessage($title, $text, $type, $page)
						$result =  popupMessage("Welcome {$username}!",'Its good to have you here','success','index.php');	
						
						// $result =  welcomeMessage($username);
						# but this will not show up untill we placed the setTImeout function in our sccript, (BECAUSE: what was hapenning before is that the php gets executed but the js does not)
						# this gives time to run javascript to run.

						//redirectTO('index');  NOW NOT NEEDED

						}else{// ie if no such user exist in the database,
							$result = flashMessage("Invalid username or password !"); # actually here the username is true but the 	password is wrong
						}
					}else{
						$result = flashMessage("Please activate your account first !");
					}
					
				}else{
					$result = flashMessage("Invalid username or password !");// actually here the username does not exist
				}
			}catch(PDOException $ex){
				$result = flashMessage("Something went wrong while searching for the user in database!");
			}
		}else{// if errors exist in the form then show teh errors
			// done in the form html body
		}

	}else {
		# throw an error
		$result = popupMessage("HACKER ALERT !!",'this request originates from an unknown source !','error','index.php');	
	}
}

?>