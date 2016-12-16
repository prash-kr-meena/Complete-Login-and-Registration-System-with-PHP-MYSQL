<?php 
include_once "resource/Database.php";		// soul purpose of this is to make the connection to the database and if there is any error(exception) then it will show it.
// well we have done that in the index page also so, well if you have an error it will show at that time BUT NOTE : you have to add this everywhere you try to intereact to the database (any where you wanna use SQL statements)

if( isset($_POST['signup_sbt']) ){

	$username = $_POST['username'];	//the method post is acutally an associative array of the value we passed 
	$password = $_POST['password'];
	$hashed_password = password_hash($password,PASSWORD_DEFAULT); # immediately hassing the password we got
	$email = $_POST['email'];

	try{
				// BEACAUSE OF THE NEW ADDITION OF THE $database VARIABLE --> INSERT INTO register.users ... so on ..  <--- we dont need the specify the register database while writing the SQL statement.
		// BUT AS ITS NOT WORKING WE HAVE TO SPECIFY THAT 
		$sqlInsert = "INSERT INTO register.users (username, password, email, join_date) #these are the names from the
				  	VALUES  (:username, :password,:email, now() ) ";							#  database
				#  and the values with ':' are place holders for values which we pass on the execution time ( from the execute 		function, --> this helps in the protection from the SQL INJECTION )

		$statement = $db->prepare($sqlInsert);
		$statement->execute(array(':username'=>$username,':password'=>$hashed_password,':email'=>$email ));

		if($statement->rowcount()==1){
			 $result = "<p style='padding: 20px; color: green;' >Registration Successfull.</p>";
		}

	}catch(PDOException $ex){
		$result = "<p style='padding:20px; color:red;' >An error occured:".$ex->getMessage()."</p>";#appending 
		//-->$result = "<p style='padding:20px; color:red;' >An error occured:$ex->getMessage()</p>";
	}

}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Register page</title>
</head>
<body>
<h2>User Authentication System </h2><hr/>
	
<!-- <pre>	<?php var_dump($_POST); ?>	</pre> -->

<?php if( isset($result) ) 		echo " $result";?>

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