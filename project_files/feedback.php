<!-- NOTE: I HAVE DONE THE VALIDATION ON THE FORM BUT I HAVEN'T CHECK ANY CONDITION WHETHER THIS USER OR THIS EMAIL IS IN OUR DATABASE , IE ANY ONE WITH AN VALID EMAIL ADDRESS CAN GIVE FEEDBACK TILL HE SATISFIES THE CALIDATION VONDITIONS -->


<?php  
include_once 'resource/Database.php';
include_once 'resource/session.php';// only needed when i do auto complete by using the user session(to get his details)
include_once 'resource/utilities.php';


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
<!-- **********************************************   HTML PART   *******************************************************-->

<?php 	$page_title = 'Feedback form';
		include_once 'partials/headers.php'; 	?>

<h2>Feed-back form</h2><hr>

<div class="container" style="border: 2px solid red";>
	<section class="col col-lg-7" style="border: 2px solid green";>
		<?php  if (isset($result) ) echo $result;  ?>	
		<?php if (!empty($form_errors) )  echo show_errors($form_errors);  ?>
	</section>
</div>
<!--                                      ---  all have id ending with 3  --                                 -->

<div class="container" >

	<section class="col col-lg-7"  >

		<form action="" method="post">
			<div class="form-group">
    			<label for="emailField3">E-mail:</label>
    			<input type="text" class="form-control" name="email"  id="emailField3" placeholder="E-mail">
  			</div>
  			<div class="form-group" >
    			<label for="usernameField3">Username:</label>
    			<input type="text" class="form-control" name="username"  id="usernameField3" placeholder="Username">
  			</div>
  			<div class="form-group">
    			<label for="messageField3">Message:</label>
    			<textarea rows="7" class="form-control"  name="textarea" id="messageField3" placeholder="Leave your message here !"></textarea>
  			</div>
  			<button type="submit" class="btn btn-primary pull-right" name="feedback_sbt">Submit</button>
		</form>

	</section>

	<p><a href="index.php">Back</a></p>
</div>

</br>
</br>
<!-- NEED A SHOW FEEDBACK BUTTON  HERE-->
<!-- ===============================    showing the feedbacks down the feedback form     ================================-->

<?php 

	try{
		$sqlQuery = "SELECT * FROM register.feedback";
		$statement = $db->prepare($sqlQuery);
		$statement->execute();

		while ( $row = $statement->fetch() ) {
			$sender_name = $row['sender_name'];
			$sender_email = $row['sender_email'];
			$message = $row['message'];
			$send_date = $row['send_date'];

			$message = showProperly($sender_name, $sender_email, $message, $send_date);
			echo $message;
		}

	}catch(PDOException $ex){
		$message = "An error occoured : DURING THE COLLECTING FEEDBACK FROM register.feedback ==> {$ex->getMessage()}";
		echo $message;
	}


function showProperly($sender_name, $sender_email, $message, $send_date){
	$result =	"<div class='container' style='border: 2px solid red';>
					<section class='col col-lg-7' style='border: 2px solid green'; >

						<div class='alert alert-success' style='border: 2px solid red';>
							<span style='border: 2px solid blue';>	
								<b> {$sender_name}</b>
							</span> 
							&nbsp;&nbsp;&nbsp;
							<span style='border: 2px solid blue';>
								<b> {$send_date} </b>
							</span>
						</div>

						<div class='alert alert-success' style='border: 2px solid red';>
							<span style='border: 2px solid blue';>	
								<b> {}</b>
							</span> 
							&nbsp;&nbsp;&nbsp;
							<span style='border: 2px solid blue';>
								<b> {$message} </b>
							</span>
						</div>

				</section>
			</div> " ;

	return $result;

}



 ?>

<?php  include_once 'partials/footers.php'; ?>
<!----  </body> is already into the fppter file  -- -->

<!-- **********************************************   HTML PART   *******************************************************-->