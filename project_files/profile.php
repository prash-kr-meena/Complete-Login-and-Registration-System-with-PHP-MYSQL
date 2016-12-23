<?php 	$page_title = 'profile';
		include_once 'partials/headers.php';?>

<?php include_once 'resource/utilities.php'; # just for the function of flashMessage()?>
<?php include_once 'resource/Database.php';# for database connection to get the other information of the user, 	?>



	<?php if( !isset($_SESSION['username']) ) : ?>

		<!--####################################  IF TRIES TO OPEN WITH PATH   ###########################################-->
		<div class="container" align="center" style="padding-top: 20%" >
			<p class="lead">You are not authorized to view this page. Please <a href="login.php">login</a> ,if not a member 					please <a href="signup.php">signup</a>! </p>
			<p class="lead">HACK US !  and please give us <a href="feedback.php">feedback</a>  on our security! </p>
		</div>
		
	<?php else : ?>

		<?php 
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
					echo flashMessage('Sorry there was some error loading your data!');
					//echo "11111111";
				}
			}catch(PDOException $ex){
				echo flashMessage("something went wrong while --> FETCHING YOUR DATA FROM THE DATABASE,-->{$ex->getMessage()}",'red');
				//echo "0000000000000";
			}
		?>
	<!-- ======================================= HTML for loged in user STARTS ========================================= -->
		<div class="container" style="border: 2px solid red">
			<h1>Profile</h1>

			<section class="col col-lg-7" style="border: 5px dotted  blue"><!--this section is to hold the coverpage and the profile pic of the user-->
				<div style="border: 1px solid green">
					<span>jflaksjfkljslklsfslk</span> 
				</div>
			</section>

			</br></br></br>

			<section class="col col-lg-7" style="border: 5px dotted green">
				<div class="form-group" style="border: 1px solid red">
					<span>	id : <?php  echo $id ?>	</span></br>
					<span>	username : <?php  echo $username ?>	</span></br>
					<span>	email : <?php echo $email ?>	</span></br>
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
<?php endif  ?>


<?php include_once 'partials/footers.php'; ?>