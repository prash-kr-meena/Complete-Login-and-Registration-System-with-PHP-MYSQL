<?php 
include_once 'Database.php';# added due to the checkDuplicasy() function 


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

/*	@param : 	$form_errors_array  --> the array holding all errors which we want to loop through
	@return : 	string , list containing all error message
*/
function show_errors($form_errors_array){
	$no_of_errors = count($form_errors_array);

	$errors = "<p style='color:red; padding:5px; border:0.5px solid grey '>There "; 

	if ($no_of_errors==1) {
		$errors.= "was $no_of_errors error";				// to do the grammer ie    1 error  and  3 errors
	}else{
		$errors.= "were $no_of_errors errors";
	}
	
	$errors.= "<ul style='color:red;' >";	//  no ending  </ul>
	# loop through the error array and display all the elements
	foreach ($form_errors_array as $the_error) {
		$errors.= "<li>{$the_error}</li>";
		}
	$errors.="</ul> </p>" ;
	return $errors;

}

function flashMessage($message,$color='red'){ //now this will act bydefault if we dont specify the second argument as now we are doing it in that way , now we call it each time , not as previously , only callin once
	if( $color === 'red' ){// means its identical
			$data = "<p style='padding: 10px; color: red; border:0.5px solid grey' >{$message}</p>";
	}else{
			$data = "<p style='padding: 10px; color: green; border:0.5px solid grey' >{$message}</p>";
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

?>