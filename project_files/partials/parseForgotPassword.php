<?php 

include_once 'resource/utilities.php'; # for the last function below...
include_once 'resource/send-email-gmail.php';

if ( isset($_POST['sbt'], $_POST['token']) ) {  //ie. if the password reset form is submitted then first validate this form and then if its valid process the form.
									// good thing is we will use the same utilities function to validate our signin form.
	if ( validate_token($_POST['token']) ) {

		#initialize the array to hold errors
		$form_errors= array();

		// ============================================= form validation BEGINS  ==============================================
		$required_fields = array('new_password','confirm_password');
		#error due to empty fields
		$form_errors = array_merge( $form_errors, check_empty_fields($required_fields) );
		#error due to minimum length  ->>> not required, for the email as we have to check that from the database, BUT 
		# IT IS REQUIRED FOR THE PASSWORDS 
		$fields_to_check_length = array('new_password'=>6, 'confirm_password'=>6);
		$form_errors = array_merge( $form_errors,check_min_length($fields_to_check_length) );
		// ==========================================   validation ENDS ========================================================

		// ============================================= form processing BEGINS ================================================

		if ( empty($form_errors) ) { 	//ie. if no errors then process the form.
			// all good no error , so now collect the form data
			$id = $_POST['user_id'];  # now it is also comming from the form as a  hidden fype
			$new_password =  $_POST['new_password'];
			$confirm_password =  $_POST['confirm_password'];

			// check if any user with this email exists in the database  --> using the sql statement
			// if exist then allow him to change the password, but if not then donot allow him to change, show error
			try{
				$sqlQuery = "SELECT * FROM register.users WHERE id=:id";// here :eamil is the key of the array in execute fun.
				$statement = $db->prepare($sqlQuery);
				$statement->execute( array(':id'=> $id ) );

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
										WHERE id = :id ";
							$statement = $db->prepare($sqlQuery);
							$statement->execute( array(':password'=>$hashed_password, ':id'=>$id ) );
							// NOTE: i want to direct to the signup page automatically after the password is reset successfully
							# BUT if i used the function redirectTo(), then the below line wil not run as after the result is set it changed the page,it never get to display to the result .,ie if operation was successfull then 
							# it will directly change to the index page,WITHOUT SHOWING THE SUCCESS MESSAGE (but in the unsuccess full cases, it wil show all the error message ove there only, because at that time we are not redirection to any other page)
							#  SO
								# ---  >$result = flashMessage("Password Successfully changed !", 'green');
							# redirect to login page after successfull change
								#--->redirectTO(login);

							# popupMessage($title, $text, $type, $page)
							$result = popupMessage("Updated!",'password reset successfully!','success','login.php');	;
							
						}catch(PDOException $ex){  #to pass into flashMessage function. 
							$result = flashMessage("something went wrong! --> while inserting the new_password {$ex->getMessage()}"); # not specified the color !
						}

					}else{// ie if no such user exist in the database,
						$result = flashMessage("Passwords does not match! Please re-enter the password !");
						}
				}else{
					$result = flashMessage("No user with email { {$email} } exist");# not specified the color !
				}
			}catch(PDOException $ex){
				$result = flashMessage("Something went wrong when searching for the user into database ! {$ex->getMessage()}");
				# not specified the color !
				}

		}else{// if errors exist in the form then show the errors
			// done in the form html body 
			}
		// =================================================  processing ENDS =================================================
	}else{
		$result = popupMessage("HACKER ALERT !!",'this request originates from an unknown source !','error','index.php');	
	}

}elseif( isset($_GET['id']) ){
	$encoded_id = $_GET['id'];
	$decoded_id = base64_decode($encoded_id);

	if ( strpos($decoded_id, 'encodeuserid') != false) { # then it has this value in it --> now you can explode it  WITH NO WORRY..
			$decodedArray= explode('encodeuserid', $decoded_id );
			$id = $decodedArray['1']; 
		}else{ # just for the sake of initializing  so that the next if condition works fine
			$id = 'yiyiuyi'; # giving strings as id will nwver be strings .. we have no's as our id's in the databaseecho $id;
		}
	//echo $id;
}else{}

?>