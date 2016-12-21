<?php 


if ( isset($_POST['feedback_sbt']) ) {

// form validation  BEGINS:==================================================================================================
# initialize error array to store all the errors
$form_errors = array();
# error due to empty fields
$required_fields_array = array('username','email','textarea');
$form_errors = array_merge($form_errors,check_empty_fields($required_fields_array));
# error due to minimum length
$fields_to_check_length = array('username'=>4, 'email'=>12,'textarea'=>6);
$form_errors = array_merge($form_errors,check_min_length($fields_to_check_length));
# error due to invalid email
$form_errors = array_merge($form_errors,check_email($_POST));

// ==========================================================================================================================

//////////////////////////////////////////////////// PROCESSING  //////////////////////////////////////////////////////////
# form processing ,ie store data into the feedback table.
if ( empty($form_errors) ) { //ie. there is no error --> go ahead and process the form
	# storing the form data
	$user = $_POST['username'];
	$email = $_POST['email'];	# storing the form data for further process and verification.
	$message = $_POST['textarea'];

	# storing this data into the feedback table into the register database
	try{

		$sqlQuery = " INSERT INTO register.feedback (sender_name, sender_email, message, send_date) 
				 		VALUES (:Sender_Name, :Sender_Email, :message, now())";
		$statement = $db->prepare($sqlQuery);
		$statement->execute( array(':Sender_Name'=>$user, ':Sender_Email'=>$email, ':message'=>$message ) );

		if ($statement->rowcount()==1) {
			$result = flashMessage("Successfully submited !", 'green');
	 	}
	 	
	 	$GLOBALS['statement'] = $statement;

	}catch(PDOException $ex){
		$result = flashMessage("An error occured: DURING STORING THE FEEDBACK DATA ==> {$ex->getMessage()}");# here we dont specify the color as , it will be picker bydefault
	}

}else{	// there was some errors, show them
	}	//done in the body element of the form
	
}

?>