<?php  

if (isset($_POST['signup_sbt'])) { ## does both validation and data processing 

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

						# popupMessage($title, $text, $type, $page)
						$result = popupMessage("Hey {$username}!!",'Hurray, registration successfull','success','login.php');

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
}

?>