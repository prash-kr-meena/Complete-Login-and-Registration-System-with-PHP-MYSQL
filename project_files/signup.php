<?php 
include_once "resource/Database.php";		// soule purpose of this is to make the connection to the database and if there is any error(exception) then it will show it.
// well we have done that in the index page also so, well if you have an error it will show at that time BUT NOTE : you have to add this everywhere you try to intereact to the database (any where you wanna use SQL statements)

// process the form
include_once "resource/utilities.php";

if (isset($_POST['signup_sbt'])) { ## does both validation and data processing 

	#initialize an array to store any error message from the form
	$form_errors = array();
	
	#form validation
	$required_fields = array('username','email','password'); // these are the name of the fields in the html form which forms the key in the associative array (here $_POST)

	//call the function to check empty field and merge the return data into form_error array
    $form_errors = array_merge($form_errors, check_empty_fields($required_fields));

    //Fields that requires checking for minimum length ,HERE we declared an array from ourself, here we are defining the minimum length of the element with respect to its key, so we can excess through its key and chek its length.
    $fields_to_check_length = array('username' => 4, 'password' => 6, 'email'=>12);

    //call the function to check minimum required length and merge the return data into form_error array
    $form_errors = array_merge($form_errors, check_min_length($fields_to_check_length));

    //email validation / merge the return data into form_error array
    $form_errors = array_merge($form_errors, check_email($_POST));

    #############################################   FORM PROCESSING AND ERROR SHOWING   ####################################

	# check if the error array is empty or not , if yes then process the form data, and insert record
	if (empty($form_errors)) {

	$username = $_POST['username'];	//the method post is acutally an associative array of the value we passed 
	$password = $_POST['password'];
	$hashed_password = password_hash($password,PASSWORD_DEFAULT); # immediately hassing the password we got
	$email = $_POST['email'];

	try{
				// BEACAUSE OF THE NEW ADDITION OF THE $database VARIABLE --> INSERT INTO register.users ... so on ..  <--- we dont need the specify the register database while writing the SQL statement.
		// BUT AS ITS NOT WORKING WE HAVE TO SPECIFY THAT 
		$sqlInsert = "INSERT INTO register.users (username, password, email, join_date) #these are the names from the
				  	VALUES  (:username, :password,:email, now() ) ";							#  database
				#  and the values with ':' are place holders for values which we pass on the execution time ( from the execute 		function, --> this helps in the protection from the SQL INJECTION )										actually they are the keys , for the associative array in the execute function.

		$statement = $db->prepare($sqlInsert);
		$statement->execute( array(':username'=>$username,':password'=>$hashed_password,':email'=>$email ) );

		if($statement->rowcount()==1){ # ie if onw row is changed theb ...
			 $result = "<p style='padding: 10px; color: green; border:0.5px solid grey' >Registration Successfull.</p>";
		}

	}catch(PDOException $ex){ // thsi will be the error from the conection and not from the user
		$result = "<p style='padding:10px; color:red; border:0.5px solid grey' >An error occured:".$ex->getMessage()."</p>";

		//-->$result = "<p style='padding:20px; color:red;' >An error occured:$ex->getMessage()</p>";
		//notice the -->    ured:".$ex->getMessage()."</       AND       red:$ex->getMessage()</
	}

		
	} // so if there will be an error then it will be checked and displayed in teh html BODY element

	
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Register page</title>
</head>
<body>
<h2>User Authentication System </h2><hr/>

<?php if( isset($result) ) 		echo " $result";?>
<?php if (!empty($form_errors) )  echo show_errors($form_errors);    ?>

<form action="" method="post" accept-charset="utf-8">
<table>
	<tr>
		<td>Username:</td>	<td><input type="text" name="username" value=""></td>
	</tr>
	<tr>
		<td>Password:</td>	<td><input type="password" name="password" value=""></td>   
	</tr>
	<tr>
		<td>E-mail:</td>	<td><input type="text" name="email" value=""></td>   <!--afterward use the email type for authentication-->
	</tr>
	<tr>
		<td></td>			<td><input style="float:right ;" type="submit" name="signup_sbt" value="Register"></td>
	</tr>
</table>
</form>

<p><a href="index.php">Back</a></p>
</body>
</html>