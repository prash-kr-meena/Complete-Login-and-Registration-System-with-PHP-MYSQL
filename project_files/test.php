<?php include_once 'partials/headers.php' ;


echo showProperl("jfslkj", "snfans", "sklsfjksjksjk", "12:42:23");

function showProperl($sender_name, $sender_email, $message, $send_date){
	$result =	"<div class='container'>
					<section class='col col-lg-7' >

						<div class='alert alert-success'>
							<span>	
								<b> {$sender_name}</b>
							</span> 
							<span>
								<b> {$send_date} </b>
							</span>
						</div>

						<div class='alert alert-success'>
							<span>	
								<b> {} </b>
							</span> 
							<span>
								<b> {$message} </b>
							</span>
						</div>
				</section>
			</div> " ;

	return $result;

}
?>

<?php
echo '</br></br></br>';
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
////////////////////////

echo '<br><br><br>';

		$decryptData = base64_decode('Nzg1OTczOTI3MzU5ODM3');// i picked it from the chrome , by searching for the cookie, with the name authenticationSystem in a coockie for the link authentication_system.com--> as i have set the virtual host to oopen up the project with this site name, and configure the xamp apache config file, 
		echo "----===>".$decryptData."<====-----<br>";

		$break_1 = explode(7859739, $decryptData);
		echo "----==>".$break_1[0]."<--->".$break_1[1]."====-----<br>";

		$break_2 = explode(7359837, $break_1[1]);
		echo "----===>".$break_2[0]."<--->".$break_2[1]."<====-----<br>";

		$decryptId = $break_2[0];
		echo "----===>".$decryptId."<====-----";
