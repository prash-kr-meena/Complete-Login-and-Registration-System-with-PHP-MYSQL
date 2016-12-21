<?php

//  --> NOT A BETTER WAY   $db = new PDO('mysql:host = localhost; dbname = register','root', ''); 

//inside pdo we have to give DSN string (ie. data source name ) which contains the driver for the specific 
//database software,here its for mysql and database name (here, register) then username --> root and password which we have not set

$dsn = 'mysql:host = localhost';
$username = 'root';
$password = '';

$host = 'localhost' ;// i added this later on the training time of electronics nikaten  after i observed the 
$dbname = 'register';// above 1st statement , i see that it has one less variable,

// but before this the application was working how , by chossing the database later(which you have to do manually in each SQL statement, eg.--> INSERT INTO register.users (username, password, email, join_date) ->(in the signup page.)

// well i have tried this and its not working this way . CHECK OUT the PDO  pdf  you have , this is the same given in that BUT its not working . --> don't know 
// SO DOING THE OLD WAY, ie selecting  manually..

try{
//NOTE: this string should be the same as provided ie. if we change the pattern or even if we mess with the space,it will fail our connection, this is BECAUSE IT IS A DRIVER, SO --> DON'T MESS WITH IT DUDE
	
//	=====>>		$db = new PDO("mysql:host =$host; dbname =$dbname,$password,$username"); 
// this will do the magic of always automatically choosing  the database, do this if you dont want to change the databases(here register)->(NOTE: i am talking about databases NOT  TABLES , that you can have many more and you can choose any of them.)
//but FUCK its not working ---> so using old one		


$db = new PDO($dsn,$username,$password); 	// now we dont want to show our errors to the public (if 													 any error  ocuurs in the connection to the database),so 													for that we use the try-catch block and we use the 															PDO-exception for handling that exception 
$db ->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
//the error mode for PDO is by default set to ERROR MOD silent , set it to exception  for more informative 			detail >check by changing the password or changing the driver name 
//  echo 'connected to the register database'; 	//   ---> just for testing
}catch (PDOException $ex){
echo 'connetion failed<br/>'.$ex->getMessage(); //so if anything goes wrong then instead of showing the full 														detailed error we show this message to the user
// echo $ex->getMessage();  OR
}

