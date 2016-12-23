<?php 	$page_title = 'profile';
		include_once 'partials/headers.php';?>

<?php include_once 'resource/utilities.php'; # just for the function of flashMessage()?>
<?php include_once 'resource/Database.php';# for database connection to get the other information of the user, 	?>

<?php ########################################  IF TRIES TO OPEN WITH PATH   ############################################### 
	if ( !isset($_SESSION['username']) ) { # is no session is set for the user, then show him this message,
		$result = "<p class=\"lead\">You are not authorized to view this page. Please ";
		$result.= "<a href=\"login.php\">login</a>";
		$result.= ",if not a member please ";
		$result.= "<a href=\"signup.php\">signup</a>";
	}else{

  # as i have the user session active (ie  how he is able to login ) 
	$id = $_SESSION['id'];	# it was already set,(IF THE USER DOES NOT DIRECTLY TRIES TO OPEN BY THE PATH,--> so for  path case we provide a security)--> ABOVE
	$username = $_SESSION['username'];

	$fingerprint = $_SESSION['fingerprint'];
	$lastActive = $_SESSION['lastActive'];
	$timeYouLogedIn = time();

	# for email and other data we have to use the sql statement
	try{
		$sqlQuery = "SELECT email,join_date FROM register.users WHERE id = :id ";
		$statement = $db->prepare($sqlQuery);
		$statement->execute( array(':id'=>$id) );
		if ($row = $statement->fetch()) {# ie, if some  data is pulled from the database
			$email = $row[0];
			$join_date = $row[1];
		}else{
			# function flashMessage($message,$color='red')--> red is by default
			flashMessage('Sorry there was some error loading your data!');
		}

	}catch(PDOException $ex){
		flashMessage("something went wrong while --> FETCHING YOUR DATA FROM THE DATABASE,-->{$ex->getMessage()}");
	}
}

?>


<div class="container" style="border: 2px solid red">
	<h1>Profile</h1>
	<?php if ( isset($result) ) {
		flashMessage($result);
	} ?>

	<section class="col col-lg-7" style="border: 5px dotted  blue"><!--this section is to hold the coverpage and the profile pic of the user-->
		<div style="border: 1px solid green">
			<span>jflaksjfkljslklsfslk</span> 
		</div>
	</section>

	</br></br></br>

	<section class="col col-lg-7" style="border: 5px dotted green"><!--this section is to show the user information -->
		<div class="form-group" style="border: 1px solid red">
			<span>	username : <?php  echo $id ?>	</span></br>
			<span>	email : <?php echo $username ?>	</span></br>
			<span>	join date : <?php echo $join_date ?>	</span></br>

			<?php 	$date = new DateTime(); # convert it to the date time format
					$date->setTimestamp($lastActive);		
					$date_string = date_format($date,'U=Y-m-d H:i:s');
			?>
			<span>	last active : <?php  echo $lastActive ?>--> this is in timestamp <?php echo $date_string; ?> 	</span></br>
			<span>	fingerprint : <?php echo $fingerprint ?></span></br>
			<span>	time You Loged In : <?php echo $timeYouLogedIn ?>	</span></br>
		</div>
	</section>
	
</div>



<?php include_once 'partials/footers.php'; ?>