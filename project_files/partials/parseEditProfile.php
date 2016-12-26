
<?php 

	############################### 	for collecting the data from the databbase to show in the edit profile  page    #####################
	######################################################    if he passes the SECURITY     ##################################################

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




	############################################### 	for submiting the edit profile form   ##############################################
	########################################   its validation and processing to change into database   #####################################
	if ( isset($_POST['edit_sbt']) ) { # then only process the form

		$email = $_POST['email'];
		$username = $_POST['username'];
		$password = $_POST['password'];

		#------------------------------------------------		VALIDATION BEGINS 	----------------------------------------------------------
		$form_errors = array(); # to store all the array

		# errors due to empty fields
		$required_fields_array = array('email', 'username', 'password');
		$form_errors = array_merge( $form_errors, check_empty_fields($required_fields_array) );

		# error due to minimum length
		$fields_to_check_length = array('email'=>12,'username'=>4);
		$form_errors = array_merge( $form_errors,check_min_length($fields_to_check_length) );

		# error due to invalid email address
		$form_errors = array_merge($form_errors, check_email($_POST)); #  check email requires an array ie. key value pair

		#---------------------------------------------------	**	ENDS  **	------------------------------------------------------------

		#--------------------------------    after validation this data should not CLASH with another user     ------------------------------
		#---------------------------------------------- ie.	THERE IS NO ERROR IN THE FORM 		----------------------------------------------
		if ( empty($form_errors) ) {# if there was NO validation error in the form

			//checkDuplicasy($input, $columnName, $databaseName, $tableName, $db){ // $db --> PDO object we pass it because these function calls directly take them to their defination, so no file are added if we write them in the top of the utilities.php file but they will work if we include these file in the function defination

			# checking duplicasy for the email--> firstly
			$arrayReturned = checkDuplicasy($email, 'email', 'register', 'users', $db);
			if ($arrayReturned['status'] == false ) {# ie duplicasy was NOT found for EMAIL in the database 
					echo '000000';	
				$arrayReturned = checkDuplicasy($username, 'username', 'register', 'users', $db);
				if ($arrayReturned['status'] == false ) {# ie duplicasy was NOT found for USARNAME too.  ==> allow him to process 

					#-------------------------------- NO DUPLICASY SO PROCESS THE FORM NOW ----------------------------------------------------


					#----------------------------------------------	PROCESSING ENDS 	------------------------------------------------------
				
				}else{# ie duplicasy was found  for USERNAME
					# no matter what is the status is either true or exception it will show the message
					$result = flashMessage($arrayReturned['message']);# by default it is red
				}
				
			}else{# ie duplicasy was found  for EMAIL
				# no matter what is the status is either true or exception it will show the message
				$result = flashMessage($arrayReturned['message']);# by default it is red
			}

		}


	}



?>


