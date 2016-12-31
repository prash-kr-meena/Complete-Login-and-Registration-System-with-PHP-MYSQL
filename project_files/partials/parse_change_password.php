<?php 
	# --> databse and utilities are included on the edit_profile page. so no need here

if ( isset($_POST['change_btn'], $_POST['token']) ) {
	if( validate_token( $_POST['token']) ) {  # ie . if token is true then only work
		# so teh request is genuine so now validate the form and after validation process the form
		# ======================================================  VALIDATE  ===================================================
		$form_errors_2 = array();# for storing form errors

		#error due to empty fields 
		$required_fields_array = array('current_password', 'new_password', 'confirm_password');
		$form_errors_2 = array_merge( $form_errors_2, check_empty_fields($required_fields_array) );

		# error due to minimum length
		$fields_to_check_length = array('new_password'=>6, 'confirm_password'=>6);
		$form_errors_2 = array_merge( $form_errors_2,check_min_length($fields_to_check_length) );

		# =====================================================================================================================

		#---------------------------------------------- PROCESS THE FORM ------------------------------------------------------
		if(empty($form_errors_2)){
			$current_password = $_POST['current_password'];
			$new_password = $_POST['new_password'];
			$confirm_password = $_POST['confirm_password'];
			$id = $_POST['id'];

			if ($new_password === $confirm_password) {
				# now check whether this password is same as the saved in the database...
				try{
					$sqlQuery = "SELECT * FROM register.users WHERE id=:id ";
					$statement = $db->prepare($sqlQuery);
					$statement->execute( array(':id'=>$id) );
					
					if ($row = $statement->fetch()) { # the user exist now take its password and check if it matches with the input
						$password = $row['password'];

						if ( password_verify($current_password, $password) ) { # ie. the password matches
							$new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
							try{
								$sqlQuery= "UPDATE register.users
											SET password = :new_password
											WHERE id = :id";
								$statement = $db->prepare($sqlQuery);
								$statement->execute( array(':new_password'=>$new_hashed_password, ':id'=>$id) );
								if ($statement->rowcount() == 1 ) {
									$result_2 = popupMessage("UPDATED",'the password has been successfully updated !', 'success', 'profile.php');
								}else{
									$result_2 = popupMessage("SORRY",'there was some error in updating your password !', 'error', '#'); # ie at same page
								}

							}catch(PDOexception $ex){
								echo flashMessage("something went wrong, WHILE UPDATING THE DATA INTO THE DATABASE -->".$ex->getMessage());
							}
						}else{ # password does not match
							$result_2 = flashMessage("current password does not matches!!");
						}

					}else{  # the user does not exist
						redirectTO("logout");
					}
					#----------------------------------------------------------------------------------------------------------------------
				}catch(PDOexception $ex){
					echo flashMessage("something went wrong, WHILE SELECTING THE DATA FROM THE DATABASE -->".$ex->getMessage());
				}
			}else{
				$result_2 = flashMessage("confirm password does not matches!!");
			}
		}
	}else{
		$result_2 = popupMessage("HACKER ALERT !!",'this request originates from an unknown source !','error','profile.php');	
	}
} 

?>