<?php  
include_once 'resource/send-email-gmail.php';

if (isset($_POST['signup_sbt'], $_POST['token'] ) ) { ## does both validation and data processing 

	if ( validate_token($_POST['token']) ) {
		// form validation BEGINS:==================================================================================================

		#initialize an array to store any error message from the form
		$form_errors = array();
		
		#form validation
		$required_fields = array('username','email','password'); // these are the name of the fields in the html form which forms the key in the associative array (here $_POST)

		//call the function to check empty field and merge the return data into form_error array
	    $form_errors = array_merge($form_errors, check_empty_fields($required_fields));

	    //Fields that requires checking for minimum length ,HERE we declared an array from ourself, here we are defining the minimum length of the element with respect to its key, so we can excess through its key and chek its length.
	    $fields_to_check_length = array('username' => 4, 'password' => 6, 'email'=>12);

	    //call the function to check minimum required length and merge the return data into form_error array
	    $form_errors = array_merge($form_errors, check_min_length($fields_to_check_length));

	    //email validation / merge the return data into form_error array
	    $form_errors = array_merge($form_errors, check_email($_POST));

		// form validation ENDS:==================================================================================================

	    #############################################   FORM PROCESSING AND ERROR SHOWING   ####################################

		# check if the error array is empty or not , if yes then process the form data, and insert record
		if (empty($form_errors)) {
			$username = $_POST['username'];	//the method post is acutally an associative array of the value we passed 
			$password = $_POST['password'];
			$hashed_password = password_hash($password,PASSWORD_DEFAULT); # immediately hassing the password we got
			$email = $_POST['email'];

			# NOW , BEFORE CREATING THE USER (ie. entering the user data into the database) WE HAVE TO CHECK WHETHER THIS USERNAME IS TAKEN OR NOT IF IT DOES ,THEN SHOW MESSAGE, "sorry this username is already taken"

	 		# checkDuplicasy($input, $columnName, $databaseName, $tableName, $db)
			$arrayReturned = checkDuplicasy($username, 'username', 'register', 'users', $db);//returns an array of 'status' and 																				'message' key and their value
			if ($arrayReturned['status'] == false ) {//ie no duplicasy for username found in the database, 
				#checking email duplicasy
				$arrayReturned = checkDuplicasy($email, 'email', 'register', 'users', $db);
				if ($arrayReturned['status'] == false ) {//ie no duplicasy for email found in the database	
					try{
						$sqlInsert = "INSERT INTO register.users (username, password, email, join_date) 
										VALUES  (:username, :password, :email, now() ) ";

						$statement = $db->prepare($sqlInsert);
						$statement->execute( array(':username'=>$username,':password'=>$hashed_password,':email'=>$email ) );

						if($statement->rowcount()==1){ # ie if one row is changed theb ...
							$user_id = $db->lastInsertId();
							$encode_id = base64_encode("encodeUserid{$user_id}");
							//prepare email body
							$mail_body = '<html>
											<body style="background-color:#CCCCCC; color:#000; font-family: Arial, Helvetica, sans-serif;
											                line-height:1.8em;">
												<h2>User Authentication System </h2>
												<p>Dear '.$username.'<br><br>Thank you for registering, please click on the link below to
												confirm your email address</p>
												<p><a href="http://localhost/activate.php?id='.$encode_id.'"> Confirm Email</a></p>
												<p><strong>&copy;2016 Authentication System </strong></p>
											</body>
									</html>';

							$mail->addAddress($email, $username); #  $email is necessary BUT $username is optional..
							$mail->Subject = "message from USER AUTHENTICATION SYSTEM.";
							$mail->Body = $mail_body;

							// error handiling for PHPmailer
							if ($mail->Send()) {
								# popupMessage($title, $text, $type, $page)
								$result = popupMessage("Hey {$username}!!",'Hurray, registration successfull.<br>Please check your email for conformation link!','success','login.php');
							}else{
								$result = popupMessage("E-mail sending FAILED!!",$mail->ErrorInfo,'error','signup.php');
							}	
						
						}else{
							$result = flashMessage("Signup unsuccessfull");
						}

					}catch(PDOException $ex){ // thsi will be the error from the conection and not from the user
						$result = flashMessage("An error occured: WHILE INSERTING THE FORM DATA INTO THE DATABASE==>".$ex->getMessage());
					}
				}else{	$result =  flashMessage($arrayReturned['message']);	}

			}else{# here we dont care what is the status of the array( either true OR exception), we have to print the 				message in any case, SO
				$result =  flashMessage($arrayReturned['message']);
			}
		} // so if there will be an error then it will be checked and displayed in the html BODY element

	}else{
			$result = popupMessage("HACKER ALERT !!",'this request originates from an unknown source !','error','index.php');	
	}
}else{			//activation code
	if(isset($_GET['id'])) {
		$encoded_id = $_GET['id'];
		$decode_id = base64_decode($encoded_id);
		$user_id_array = explode("encodeuserid", $decode_id);
		$id = $user_id_array[1];

		$sql = "UPDATE register.users SET activated =:activated WHERE id=:id AND activated='0'"; # so if the account has been already updated then this script will not work

		$statement = $db->prepare($sql);
		$statement->execute(array(':activated' => "1", ':id' => $id));

		if ($statement->rowCount() == 1) {
			$result = "<div class=\"container\"  style=\"padding-top:25%\"><h2>Email Confirmed </h2>
			<p class='lead' style=\"padding-top:6px\">Your email address has been verified, you can now <a href=\"login.php\">login</a> with your email and password.</p></div>";
		} else {
			$result = "<div class=\"container\" style=\"padding-top:30%\"><p class='lead'>No changes made please contact site admin,
		    if you have not confirmed your email before</p></div>";
		}
	}
}
?>