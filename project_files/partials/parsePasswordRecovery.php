<?php 

include_once 'resource/utilities.php';
include_once 'resource/send-email-gmail.php';

if (isset($_POST['recovery_btn'], $_POST['token'])) {

	if ( validate_token($_POST['token']) ) {

		// =================================  validate the form ==========================================
		$form_errors = array();

		# error due to empty field    --->  check_empty_fields($required_fields_array) --> requires an array
		$required_fields_array = array('email'); 
		$form_errors = array_merge(  $form_errors, check_empty_fields($required_fields_array)  );

		# check the email is valid or not   --> check_email($data)  -> data is an array associative array
		$form_errors = array_merge(  $form_errors, check_email($_POST)  ); # $post contains all the valuse send form the form (in associative array form..), now well it contains ,just email

		// ===================================== validation over =========================================

		// ====================================== now process the form ===================================
		if ( empty($form_errors) ){
			# collect form data
			$email = $_POST['email'];
			try{
				$sqlQuery = "SELECT * FROM register.users WHERE email=:email";
				$statement = $db->prepare($sqlQuery);
				$statement->execute( array(':email'=>$email) );
				if ( $row = $statement->fetch() ) {  #  ie, if  data is fetched   ie. user exists and we have to send him the reset link to his website
					$id = $row['id'];
					$email = $row['email'];

					$encode_id = base64_encode("encodeuserid{$email}");
					# prepare email body
					//prepare email body
					$mail_body = '<html>
									<body style="background-color:#CCCCCC; color:#000; font-family: Arial, Helvetica, sans-serif; line-height:1.8em;">
										<h2>User Authentication System</h2>
										<p>Dear '.$username.'<br><br>To reset your login password ,please click on the link below:</p>
										<p><a href="http://localhost/forgot_password.php?id='.$encode_id.'"> Reseet Password</a></p>
										<p><strong>&copy;2016 Authentication Solution</strong></p>
									</body>
								</html>';
					$mail->addAddress($email,$username);
					$mail->Subject="Password recovery message from User Authentication";
					$mail->Body = $mail_body;

					// error handling for PHPMailer 
					if ( $mail->Send() ) {
						# popupMessage($title, $text, $type, $page)
						$result = popupMessage("Password Recovery",'Password reset link send successfully, please check your email !','success','login.php');
					}else{
						$result = popupMessage("E-mail sending FAILED!!",$mail->ErrorInfo,'error','password_recovery.php');
					}
				}else{
					$result =  flashMessage("No user with this email exist") ;
				}
			}catch(PDOException $ex){
				$result =  flashMessage("Something went wrong WHILE GETTING THE DATA FROM THE DATABASE --> {$ex->getMessage()}") ;
			}
		}
		//======================================== process ends ==========================================	
	}else{
		$result = popupMessage("HACKER ALERT !!",'this request originates from an unknown source !','error','index.php');	
	}
}
?>