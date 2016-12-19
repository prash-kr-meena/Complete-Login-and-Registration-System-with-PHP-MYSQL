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

		$sqlQuery = "INSERT INTO register.feedback (sender_name, sender_email, message, send_date)
				 	VALUES (:Sender_Name, :Sender_Email, :Message, now())";
		$statement = $db->prepare($sqlQuery);
		$statement->execute( array(':Sender_Name'=>$user, ':Sender_Email'=>$email, ':Message'=>$message ) );

		if ($statement->rowcount()==1) {
	 		$result = "<p style='padding: 10px; color: green; border:0.5px solid grey' >Successfully submited.</p>";
	 	}
	 	
	 	$GLOBALS['statement'] = $statement;

	}catch(PDOException $ex){
		$result = "<p style='padding:10px; color:red; border:0.5px solid grey' >An error occured:".$ex->getMessage()."</p>";
	}

}else{	// there was some errors, show them
	}	//done in the body element of the form
	
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Feed-back page</title>
</head>
<body>
<h2>User Authentication System </h2><hr>

<?php if( isset($result) ) 		echo " $result";?>
<?php if( !empty($form_errors) )   echo show_errors($form_errors);  ?>

<h3>Feed-back form</h3>
<form action="" method="post">
	<table>
		<tr>
			<td>Username:</td>	<td><input type="text" placeholder="username" name="username"></td>
		</tr>
		<tr>
			<td>E-mail:</td>	<td><input type="text" placeholder="e-mail" name="email"></td>
		</tr>
		<tr>
			<td>Message:</td>	<td><textarea placeholder="leave your message here" name="textarea"></textarea></td>
		</tr>
		<tr>
			<td></td>			<td><input style='float:right;' type="submit"  name="feedback_sbt" value="submit"></td>
		</tr>
	</table>
</form>	

<a href="index.php">Back</a>
<!-- ===============================    showing the feedbacks down the feedback form     ================================-->
<?php while( $row = $GLOBALS['statement']->fetch() ) : ?>
	<table>
		<tr>
			<td><?php echo $row['email']; ?></td>		<td><?php echo $row['message']; ?></td>
		</tr>
		<tr>
			<td><?php echo $row['username']; ?></td>
		</tr>
	</table>	 
<?php  endwhile?>




</body>
</html>