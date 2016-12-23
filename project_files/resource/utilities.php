
<?php 
include_once 'Database.php';# added due to the checkDuplicasy() function ,but it does not working so added $db instead of this ===> THE REASON WHY ITS NOT WORKING IS THAT WHENWVER A FUNCTION IS CALLED IT DOES NOT GO THROUGH ALL THE SCRIPT OF THE utilities.php file IT SIMPLY GOES TO THE FUNCTION ADN PROCESS , AND NEVER READS THIS STATEMENT , SO DUE TO THIS ===> IT PRODUCES ERROR , WHAT MAY BE THE SOLUTION IS YOU INCLUDE THIS STATEMENT INSIDE THE FUNCTION DEFINATION


###########################################################################################################################
/*	@param : $required_fields_array --> an array containing the list of all required fields
	@return : array containing all errors	, and then this array will be merged with the error array that is declared in 			the signup page.
*/

function check_empty_fields($required_fields_array){
	#initialize an array to store any error message from the form
	$form_errors = array();

	#loop through the  required fileds array (for checking a condition)
	foreach ($required_fields_array as $key ) {
		if ( !isset($_POST[$key]) || $_POST[$key]==NULL) { //or i can check that by if( empty($_POST[$key]) ){ #CODE ...}
			$form_errors[] = $key." is a required field";
		}
	}	
	return $form_errors;	
}

###########################################################################################################################
/*	@param : $fields_to_check_length  --> an array containing the name of fields (ie key of the $_POST array) for which we 												want to check min required length eg. array('username' =>4, 'email' =>12)
	@return : array containgin all errors. which will be merged finally

*/
function check_min_length($fields_to_check_length){#NOTE : IT SHOULD CHECK LENGTH ONLY WHEN THERE IS SOME VALUE IN THE 														FIELD AND NOT WHEN FIELDS ARE EMPTY, ie. if EMPTY THEN ONLY THE REQUIRED 													FIELDS ERROR SHOULD COME AND NOT THE MINIMUM LENGTH ERROR SHOULD COME !!!
	#initialize an array to store any error message from the form
	$form_errors = array();
	
	#loop through the  required fileds array (for checking a condition)
	foreach ($fields_to_check_length as $name_of_the_field => $minimum_length_required	 ) {
		if (  isset($_POST[$name_of_the_field]) && $_POST[$name_of_the_field] != NULL ) {
			if ( strlen( trim( $_POST[$name_of_the_field]) ) < $minimum_length_required) {//trim : to trim out all the spaces
				$form_errors[] = $name_of_the_field . " is too short, must be {$minimum_length_required} characters long";
			}
		}else{# do nothing
		}
	}
	return $form_errors;

}

###########################################################################################################################
/*	@param :	$data ,store a key value pair array where key is the name of the html form control in, this case 'email' 					and value is the input entered by the user
	@return : array ,containing email error

*/
function check_email($data){
	#initialize an array to store any error message from the form
	$form_errors = array();

	$key = 'email';

	#check if the key emai exist in data array
	if( array_key_exists($key, $data) ){		// array_key_exist is a predefined function ,
		# check if the email field has a value ie its non empty,
		if ($_POST[$key] != NULL) {
		  	# remove all the illegal character from the email ==>  SANITIZE 
		  $_POST[$key] = filter_var($_POST[$key],FILTER_SANITIZE_EMAIL); # it filters out the ( and ) in all these special 														characters above the no's  ie --> ! @ # $ % ^ & * ( ) _ + 
		  	//  it also filters  "  ,  /  >  <  ;  :   \    ---> these are all the one on the   keyboard
		  	
		  	# check if input is valid email address  ===> VALIDATE
		  	if ( filter_var( $_POST[$key],FILTER_VALIDATE_EMAIL ) == false ) {
		  		$form_errors[] = "{  {$_POST[$key]}  } is not a valid email address";
		  	}
		  }  
	}
	return $form_errors;
}

###########################################################################################################################
/*	@param : 	$form_errors_array  --> the array holding all errors which we want to loop through
	@return : 	string , list containing all error message
*/
function show_errors($form_errors_array){
	$no_of_errors = count($form_errors_array);

	$errors = "<div class='alert alert-danger'>There "; 

	if ($no_of_errors==1) {
		$errors.= "was $no_of_errors error";				// to do the grammer ie    1 error  and  3 errors
	}else{
		$errors.= "were $no_of_errors errors";
	}
	
	foreach ($form_errors_array as $the_error) {
		$errors.= "<li>{$the_error}</li>";
		}
	$errors.="</div>" ;
	return $errors;

}

###########################################################################################################################
function flashMessage($message,$color='red'){ //now this will act bydefault if we dont specify the second argument as now we are doing it in that way , now we call it each time , not as previously , only callin once
	if( $color === 'red' ){// means its identical
			$data = "<div class='alert alert-danger'>{$message}</div>";
	}else{
			$data = "<div class='alert alert-success'>{$message}</div>";
	}
	return $data;
}

function redirectTO($page){
	header("location: {$page}.php");
}

#################################################### PARAMETERS FOR DUPLICASY  #############################################

# parameters : 1. is for which column we have to check the duplicacy, 

# 2. name of the database and table in which you want to check duplicasy (why database name,--> because in my working database connection i am not abale to specify the database,---> BUT THIS GIVES ME ADVANTAGE, TO HAVE ONLY ONE DATABASE CONNECTION STATEMENT AND USE IT EVERY WHERE TO MAKE THE CONNECTION TO THE DATABASE, ie. $db --> so i can include the database.php file in here) BUT, WHAT I HAVE TO DO IS I HAVE TO ALWAYS GIVE THE NAME OF THE DATABASE(eg.register)	

# 3. the value that user inputs , 

# 4. the database string,(well we can include the database file here BUT what if we have to work with different databases,)---> BUT DUE TO THE POINT #2  IT IS NOT REQUIRED,

function checkDuplicasy($input, $columnName, $databaseName, $tableName, $db){ // $db --> PDO object
	try{					# FEEL LIKE THE ERROR WAS DUE TO SCOPE RULE (but i am not geting it , why it worked , in other files, ) EVEN THOUGH I HAVE INCLUDED THE DATABASE.PHP FILE ( see if there is error in including the file)
		$sqlQuery = "SELECT {$columnName}
					FROM {$databaseName}.{$tableName}
					WHERE {$columnName}=:input";

		$statement = $db->prepare($sqlQuery);

		$statement->execute( array(':input'=>$input) );
		if($statement->rowcount()==1){
			$status = true;
			$message = "Sorry this {$columnName} is already taken ";
		}else{
			$status = false;# ie no duplicasy if found so user can fo ahead and process further
			$message = NULL;# as we dont require any message (WE JUST WANT HIM TO GO FURTHER.)
		}
	}catch(PDOException $ex){
		$status = 'exception';
		$message = "An error occoured : DURING THE CHECKING OF DUPLICASY OF {$columnName} IN {$databaseName}->{$tableName} ==> {$ex->getMessage()}";
	}
	$returnThis = array('status'=>$status, 'message'=>$message);
	return $returnThis;
}

###########################################################################################################################
function welcomeMessage($username){ # scripts included above
$message ="<script type='text/javascript'>
				swal({
  					title: 'Welcome {$username}!',
  					text: 'Its good to have you here',
  					timer: 3000,
  					type: 'success',
  					showConfirmButton: false
				});
	  				setTimeout(function(){
	    				window.location.href='index.php'; 
	  				}, 2000);
				</script>";

	//$message = "$username";
	return $message;
}

###########################################################################################################################
function popupMessage($title, $text, $type, $page){ # scripts included above
$message ="<script type='text/javascript'>
				swal({
  					title: '{$title}',
  					text: '{$text}',
  					timer: 4000,
  					type: '{$type}',
  					showConfirmButton: false
				});
	  				setTimeout(function(){
	    				window.location.href='{$page}'; 
	  				}, 3000);
				</script>";

	//$message = "$username";
	return $message;
}

###########################################################################################################################
function confirmLogout(){
	/*$message ="<script type='text/javascript'>
				swal({
				  title: 'Are you sure?',
				  text: 'You want to log out ?',
				  type: 'warning',
				  showCancelButton: true,
				  confirmButtonColor: '#DD6B55',
				  confirmButtonText: 'Yes, Logout',
				  closeOnConfirm: false
				},
				function(){
					swal({
				  		title: 'Come back',
				  		text: 'successfully loged out ',
				  		timer: 5000,
				  		type: 'success'
				  		}),
					".session_destroy().",
					window.location.href='index.php'
				}	);
				</script>";
*/

	$message = "<script type='text/javascript'>
					swal({
		  				title: 'Are you sure?',
		  				text: 'You want to log out ?',
		  				type: 'warning',
		  				
		  				showCancelButton: true,
		  				confirmButtonColor: '#DD6B55',
		  				
		  				confirmButtonText: 'Yes, Logout',
		  				cancelButtonText: 'cancel',
		  				
		  				closeOnConfirm: false,
		  				closeOnCancel: false
						},

					function(isConfirm){
		  				if (isConfirm) {
		    				swal({
				  				title: 'Come later',
				  				text: 'You are successfully loged out ',
				  				type: 'success',
				  				timer: 6000
				  				}),
							".session_destroy()."
		  				} else {
		    				swal({
				  				title: 'Hurray!!',
				  				text: 'Your are currently loged in :)',
				  				type: 'success',
				  				timer: 6000
				  			})
		  				}
						});
				</script>";

	return $message;
}

###########################################################################################################################
/*	@param: user id
*/
function rememberMe($id){ # encrypted so the user id is not visible
	$encryptId = base64_encode("7859739".$id."7359837") ; # making it more secure
	/*this is just to make it more sewcure , adding no , so if it decodes it to then also he will see no. only and not able to know which no is it which will not be possible if we used strings, and the hacker goes into the cookie and decode it by base64_decode */

	//setcookie(NAME of the cookie,DATA to be stored in the cookie, TIME for which the cookie should be there, and then deleted,LOACATION
	setcookie('authenticationSystem',$encryptId,time()+60*60*24*100,"/");
}

###########################################################################################################################
/*	checked if the cookie used is the same with the encrypted cookie
	@param:	$db --> database connection list
	@return: bool, true if the user cookie is valid
*/
function isCookieValid($db){
	$isValid = false;
	if ( isset($_COOKIE['authenticationSystem']) ) {
		/*	DECODE cookie and extract the user id 
			--> then with that userid extract its all data,like  username,comments,profile..etc
		*/

		#$decryptId = base64_decode(authenticationSystem);	---> authenticationSystem  ==> is name of the cookie not the data of the cookie
		$decryptData = base64_decode($_COOKIE['authenticationSystem']);# now the data is decrypted but we dont have the id yet, we have the string of no that contains our ID too..

		$break_1 = explode(7859739, $decryptData);# got the other half of the string in which our id is 
		# ie now $break_1[0]= sill have --> 7859739		AND  break_1[1] will have the other half

		$break_2 = explode(7359837, $break_1[1]);# got the other half of the string in which our id is 
		# ie now $break_2[0]= sill have our ID	AND  break_2[1] will have --->7359837

		$decryptId = $break_2[0];

		/*CHECK WHETHER THE ID in the decrypt id IS IN OUR DATABASE OR NOT (or somebody have changed the cookie data and by 	chance it gets decoded into the id that previously exist into our database, --. chanced of that is very low, )
		MORE GOOD REASON --> is if we didnt check the id and started processing further ie. getting the users data , like ,USERNAME,PROFILE, etc.. THEN IT WILL GIVE ERROR THAT THE USER ID IS NOT VALID IT DOES NOT EXIST IN  THE TABLE
		*/

		# CHECK IF THE ID RETREVIED EXISTS IN THE DATABASE OR NOT
		try{
			$sqlQuery = "SELECT * 
						FROM register.users 
						WHERE id = :id";
			$statement=$db->prepare($sqlQuery);
			$statement->execute( array(':id'=>$decryptId) );

			if ($row = $statement->fetch()) {# ie ,user exists, the COOKIE IS A VALID COOKIE

				$username = $row['username'];
				$id = $row['id'];
				# now its  confirmed that the cookie is genuine , we got the genuine user id from it , and now we can start its session as NOTE: session of a user is always destroyed as soon as he/she closes the browser  ==> by cookie each time the browser opens(no matter how many times, untill the cookie is not died) the site the user is loged in automatically
				$_SESSION['id']= $id;
				$_SESSION['username']= $username; #   AND 

				$isValid = true;
					# ie ,user exists, the COOKIE IS A VALID COOKIE
			}else{# no such user exists, IE COOKIE IS INVALID
					# so cookie id is invalid and -->DESTROY THE SESSION AND LOGOUT THE USER
				echo popupMessage('Oops..',"this id does not exists in our database",'index.php');
				$isValid = false;
				signout();
			}
		}catch(PDOException $ex){
			echo popupMessage('Oops..', "something went wrong ,WHILE CHECKING THE USER'S ID IN THE DATABASE,".$ex->getMessage(),'error','index.php');
		}

		return $isValid;
	}
}
###########################################################################################################################


?>