<?php  
include_once "resource/session.php";
include_once "resource/Database.php";	
include_once "resource/utilities.php";

if ( isset($_POST['login_sbt']) ) {  //ie. if the login form is submitted then first validate this form and then if its 										valid process the form.
//									 good thing is we will use the same utilities function to validate our signin form.

	#initialize the array to hold errors
	$form_errors= array();

	# validate begins: =============================  for login form  =============================================
	$required_fields = array('username', 'password');
	#error due to empty fields
	$form_errors = array_merge( $form_errors, check_empty_fields($required_fields) );
	#error due to minimum length
	$fields_to_check_length = array('username'=>4, 'password'=>6);
	$form_errors = array_merge( $form_errors,check_min_length($fields_to_check_length) );
	#validation ends: =============================================================================================

	if ( empty($form_errors) ) { 	//ie. if no errors then process the form.
		// all good no error , so now collect the form data
		$user = $_POST['username'];
		$password =  $_POST['password'];

		// check if the user exists in the database  --> using the sql statement
		$sqlQuery = "SELECT * FROM register.users WHERE username=:username";// here :username is the key of the array in execute fun.
		$statement = $db->prepare($sqlQuery);
		$statement->execute( array(':username'=> $user ) );

		while($row = $statement->fetch() ) { # $statement->fetch()  --> fetches the data from the databse as an associative array , so $row is an associative array with the keys as the column names in the database, and values as the data in the data stored in the database..

			$id = $row['id'];						# storing the data from the databases  for further checking 
			$hashed_password = $row['password'];	# for further verification and processing
			$username = $row['username'];

			if ( password_verify($password, $hashed_password) ) {  # password_verify() --> inbuilt function for comparing 												the hashed pasword by the nohashed password -> entered by the user
				// IF VERIFIED , we have to start the SESSION FOR THIS USER..
				$_SESSION['id'] = $id;
				$_SESSION['username'] = $username;
				header("location: index.php"); #to redirect the user to the index page.
			}else{// ie if no such user exist in the database,
					$result ="<p style:'padding:10px; color:red; border:0.5px solid grey;'>Invalid username or password</p>";
			}

		}


		
	}else{// if errors exist in the form then show teh errors
		// done in the form html body
	}
	
}
?>


<!DOCTYPE html>
<html>
<head>
	<title>login page</title>
</head>
<body>
<h2>User Authentication System </h2><hr>

<?php if ( !empty($form_errors) )   echo show_errors($form_errors) ?>

<h3>Login Form</h3>
<form action="" method="post" accept-charset="utf-8">
<table>
	<tr>
		<td>Username:</td>	<td><input type="text" name="username" value=""></td>
	</tr>
	<tr>
		<td>Password:</td>	<td><input type="password" name="password" value=""></td>   
	</tr>
	<tr>
		<td></td>	<td><input style="float:right ;" type="submit" name="login_sbt" value="Signin"></td>
	</tr>
</table>
</form>

<p><a href="index.php">Back</a></p>
</body>
</html>