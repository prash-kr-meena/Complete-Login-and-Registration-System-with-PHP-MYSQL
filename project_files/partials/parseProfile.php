<?php 
if (isset($_SESSION['username']) ) {
	# as i have the user session active (ie  how he is able to login ) --> from the isCookieValid() function present after  guard() function in headers
			$id = $_SESSION['id'];	# it was already set,(IF THE USER DOES NOT DIRECTLY TRIES TO OPEN BY THE PATH,--> so for  path case we provide a security)--> ABOVE
			$username = $_SESSION['username'];

			$fingerprint = $_SESSION['fingerprint'];
			$lastActive = $_SESSION['lastActive'];
			$timeYouLogedIn = time();

			# for email and other data we have to use the sql statement
			try{
				$sqlQuery = "SELECT * 
							FROM register.users 
							WHERE id = :id ";
				$statement = $db->prepare($sqlQuery);
				$statement->execute( array(':id'=>$id) );

				if ($row = $statement->fetch()) {# ie, if some  data is pulled from the database
					$email = $row['email'];
					$join_date = $row['join_date'];
				}else{
					# function flashMessage($message,$color='red')--> red is by default
					echo flashMessage('Sorry there was some error loading your data!');
					//echo "11111111";
				}
			}catch(PDOException $ex){
				echo flashMessage("something went wrong while --> FETCHING YOUR DATA FROM THE DATABASE,-->{$ex->getMessage()}",'red');
				//echo "0000000000000";
			}
}

 ?>