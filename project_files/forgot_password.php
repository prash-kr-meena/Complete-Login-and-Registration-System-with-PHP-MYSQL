<?php  
include_once 'resource/Database.php';
include_once 'resource/session.php';
include_once 'resource/utilities.php';

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


if ( isset($_POST['sbt']) ) {  //ie. if the password reset form is submitted then first validate this form and then if its 										valid process the form.
//									 good thing is we will use the same utilities function to validate our signin form.

	#initialize the array to hold errors
	$form_errors= array();

	// ============================================= form validation BEGINS  ==============================================
	$required_fields = array('email', 'new_password','confirm_password');
	#error due to empty fields
	$form_errors = array_merge( $form_errors, check_empty_fields($required_fields) );
	#error due to minimum length  ->>> not required, for the email as we have to check that from the database, BUT 
	# IT IS REQUIRED FOR THE PASSWORDS 
	$fields_to_check_length = array('new_password'=>6, 'confirm_password'=>6);
	$form_errors = array_merge( $form_errors,check_min_length($fields_to_check_length) );
	# error due to invalid email address
	$form_errors = array_merge( $form_errors,check_email($_POST) );

	// ==========================================   validation ENDS ========================================================

	// ============================================= form processing BEGINS ================================================

	if ( empty($form_errors) ) { 	//ie. if no errors then process the form.
		// all good no error , so now collect the form data
		$email = $_POST['email'];
		$new_password =  $_POST['new_password'];
		$confirm_password =  $_POST['confirm_password'];

		// check if any user with this email exists in the database  --> using the sql statement
		// if exist then allow him to change the password, but if not then donot allow him to change, show error
		try{
			$sqlQuery = "SELECT * FROM register.users WHERE email=:email";// here :eamil is the key of the array in execute fun.
			$statement = $db->prepare($sqlQuery);
			$statement->execute( array(':email'=> $email ) );

			if($row = $statement->fetch() ) { # $statement->fetch()  --> fetches the data from the databse as an 							associative array , so $row is an associative array with the keys as the column 								names in the database, and values as the data in the data stored in the database..

				$id = $row['id'];						# storing the data from the databases  for further checking 
				$hashed_password = $row['password'];	# for further verification and processing
				$username = $row['username'];



				if ( $new_password == $confirm_password ) {# ie both the password are equal,NOTE:THEIR LENGTH ARE ALREADY 	
					$hashed_password = password_hash($new_password,PASSWORD_DEFAULT);						//	CHECKED
							# now put this password into the database,
					try{
						$sqlQuery = "UPDATE register.users 
									SET password = (:password)
									WHERE email = :email ";
						$statement = $db->prepare($sqlQuery);
						$statement->execute( array(':password'=>$hashed_password, ':email'=>$email ) );
						$message = 'Password Successfully changed';		#to pass into flashMessage function.
						$color = 'green';

					}catch(PDOException $ex){  #to pass into flashMessage function. 
						$message = "something went wrong! --> while inserting the new_password {$ex->getMessage()}";		
						$color = 'red'; // no need to specify, as red is the default
					}

				}else{// ie if no such user exist in the database,
					$message = 'Passwords does not matche! Please re-enter the password.';
					$color = 'red'; 
					}
			}else{
				$message = "User with { {$email} } email does not exist";
				$color = 'red'; 
			}
		}catch(PDOException $ex){
			$message = "Something went wrong when searching for the user into database ! {$ex->getMessage()}";
			$color = 'red'; 
			}

	}else{// if errors exist in the form then show the errors
		// done in the form html body 
		}
	// =================================================  processing ENDS =================================================
}



?>
<!DOCTYPE html>
<html>
<head>
	<title>forgot password?</title>
</head>
<body>

<h2>User Authentication System </h2><hr>

<?php  if ( !empty($form_errors) )  echo show_errors($form_errors) ?>
<?php  if (isset($message) ) echo flashMessage($message, $color);  ?>

<h3>Password Reset Form</h3>
<form action="forgot_password.php" method="post">
	<table>
		<tr>
			<td>E-mail: </td>			<td><input type="text" name="email" placeholder="email"></td>
		</tr>
		<tr>
			<td>New-Password: </td>		<td><input type="password" name="new_password" placeholder="Password"></td>
		</tr>
		<tr>
			<td>Confirm-Password: </td>	<td><input type="password" name="confirm_password" placeholder="Confirm Password"></td>
		</tr>
		<tr>
			<td></td>					<td><input style='float:right;' type="submit" name="sbt" value="Reset password" ></td>
		</tr>
	</table>
</form>
<a href="login.php">Back</a>

</body>
</html>