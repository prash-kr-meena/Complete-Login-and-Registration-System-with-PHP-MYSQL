<?php
include_once "resource/session.php";
include_once "resource/Database.php";	
include_once "resource/utilities.php";

  $sqlQuery = "SELECT username FROM  register.users ";
			$statement = $db->prepare($sqlQuery);
			//var_dump($statement->execute());
			$username = 'prashant';

			echo 'IT ALWAYS PICS FORM THE TOP TO THE BOTTOM </br></br>';

			$statement->execute();
			while($row = $statement->fetch() ){
				echo '</br>';
				print_r($row);
				if ($username == $row['username']) {
					break;
				}
			}
			echo'hi there</br></br></br>';
////////////////////////////////////////////////////////////////

			// $statement->execute();			---> due to this the statement runs for the 1st time BUT 
			while($row = $statement->fetch() ){ #--> it also runs the sql query, it (SO THERE IS NO NEED TO FOR THE 													EXECUTE() FUNCTION ALWAYS ), due to this it runs the second time
				echo '</br>';
				print_r($row);						#  |
				if ($username == $row['username']) {# | THIS IS  WRONG WE NEED the execute statement (is necessary)	
					break;						# feth() function does not do the execution (it just fetches teh output 								produced by the statement execution.)
				}
			}
			echo'hi there</br></br></br>';

////////////////////////////////////////   testing the scope of the variable   //////////////////////////////////////////////
			$a= 1;
			{
				echo $a;
				{
					echo $a;	
					{
						echo $a;
					}
				}

			}
			echo '</br></br></br>';
////////////////////////////////////////   testing the scope of the variable   //////////////////////////////////////////////
			try{
				$connect = new PDO('mysql:host = localhost; dbname = register','root', ''); 
				echo 'connected';
			}catch(PDOException $ex){
				echo $ex->getMessage();
			}
////////////////////////////////////////   testing the scope of the variable   //////////////////////////////////////////////
			$sqlQuery = "SELECT * FROM register.users ";
			$statement = $db->prepare($sqlQuery);	
			//echo 'hi';
			$statement->execute();
			while( $row = $statement->fetch() ){
				echo '</br><<pre>';
				print_r($row);
				echo '</pre>';
			}
			




?>


