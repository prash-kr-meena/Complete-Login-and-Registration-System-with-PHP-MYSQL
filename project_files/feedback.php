<!-- NOTE: I HAVE DONE THE VALIDATION ON THE FORM BUT I HAVEN'T CHECK ANY CONDITION WHETHER THIS USER OR THIS EMAIL IS IN OUR DATABASE , IE ANY ONE WITH AN VALID EMAIL ADDRESS CAN GIVE FEEDBACK TILL HE SATISFIES THE CALIDATION VONDITIONS -->


<?php  
include_once 'resource/Database.php';
include_once 'resource/session.php';// only needed when i do auto complete by using the user session(to get his details)
include_once 'resource/utilities.php';
////////////////////////////////////////////
include_once 'partials/parseFeedback.php';

?>
<!-- **********************************************   HTML PART   *******************************************************-->

<?php 	$page_title = 'Feedback form';
		include_once 'partials/headers.php'; 	?>

<h2>Feed-back form</h2><hr>

<div class="container" >
	<section class="col col-lg-7" >
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
	$result =	"<div class='container' >
					<section class='col col-lg-7'  >

						<div class='alert alert-success' >
							<span >	
								<b> {$sender_name}</b>
							</span> 
							&nbsp;&nbsp;&nbsp;
							<span >
								<b> {$send_date} </b>
							</span>
						</div>

						<div class='alert alert-success' >
							<span >	
								<b> {}</b>
							</span> 
							&nbsp;&nbsp;&nbsp;
							<span >
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