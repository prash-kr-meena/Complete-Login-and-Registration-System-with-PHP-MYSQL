<?php 
	# --> databse and utilities are included on the edit_profile page. so no need here
include_once 'resource/send-email-gmail.php';

if ( isset($_POST['deactivate_btn'], $_POST['token']) ) {
	if( validate_token( $_POST['token']) ) {  # ie . if token is true then only work
		# so teh request is genuine so now validate the form and after validation process the form
		#---------------------------------------------- PROCESS THE FORM ------------------------------------------------------
		if(empty($form_errors_3)){
			$id = $_POST['id'];

			try{
				# 	1. retrive user information id --> so as to get his username and his email address (to send mail with his name on it ofcource)
				$sqlQuery_1= "SELECT * 
							FROM register.users
							WHERE id = :id";
				$statement_1 = $db->prepare($sqlQuery_1);
				$statement_1->execute( array(':id'=>$id) );
				if( $row = $statement_1->fetch() ){
					$username = $row['username'];
					$email = $row['email'];

					try{
						#	2. deactivate the account.
						$deactivate_query= "UPDATE register.users
									SET activated = :activated	
									WHERE id = :id";
						$deactivate_user = $db->prepare($deactivate_query);
						$deactivate_user->execute( array(':activated'=>'0', ':id'=>$id) );

						if($deactivate_user->rowcount()==1){
							#	3. Insert record into the trash table.
							try{
								$insert_query= "INSERT INTO  register.trash (user_id, deleted_at)
								VALUES ( :id, now() ) ";
								$insert_record = $db->prepare($insert_query);
								$insert_record->execute( array(':id'=>$id) );

								if ($insert_record->rowcount() == 1) {
									#	4. notify the user via E-mail and display conformation email.
									//prepare email body
									$mail_body = '<html>
													<body style="background-color:#CCCCCC; color:#000; font-family: Arial, Helvetica, sans-serif;
													                line-height:1.8em;">
														<h2>User Authentication System </h2>
														<p>Dear '.$username.'<br><br>you have requseted to deactivate your account , your account information will be kept for 14 days, if you wish to continue using this system login within the next 14 days time period to reactivate your account or it will be parmanently deleted.</p>
														<p><a href="http://localhost/login.php"> login</a></p>
														<p><strong>&copy;2016 Authentication System </strong></p>
													</body>
											</html>';

									$mail->addAddress($email, $username); #  $email is necessary BUT $username is optional..
									$mail->Subject = "message from USER AUTHENTICATION SYSTEM : account deactivate";
									$mail->Body = $mail_body;

									// error handiling for PHPmailer
									if ($mail->Send()) {
										# popupMessage($title, $text, $type, $page)
										$result = popupMessage("Hey {$username}!!",'your account has been deactivated.<br>Please check your email for more infomation !','success','index.php');
									}else{
										$result = popupMessage("E-mail sending FAILED!!",$mail->ErrorInfo,'error','signup.php');
									}	
								}else{
										$result_3 = flashMessage("no record inserted into the trash table!");
								}

							}catch(PDOException $ex){
								$result_3 = flashMessage("could not complete the INSERT operation please try again !");
							}
						}else{
							$result_3 = flashMessage("not able to  deactivate user !!");
						}
					}catch(PDOException $ex){
						$result_3 = flashMessage("could not complete the DEACTIVATE operation please try again !");
					}

				}else{
						$result_3 = flashMessage("not able to fetch the data from database !");			
				}
			}catch(PDOException $ex){
				$result_3 = popupMessage("ERROR !!",'an error occured !!','error','profile.php');	
			}
		}
	}else{
		$result_3 = popupMessage("HACKER ALERT !!",'this request originates from an unknown source !','error','profile.php');	
	}
} 

?>