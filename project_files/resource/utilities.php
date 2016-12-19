<?php 
/*	@param : $required_fields_array --> an array containing the list of all required fields
	@return : array containing all errors	, and then this array will be merged with the error array that is declared in 			the signup page.
*/

function check_empty_fields($required_fields_array){
	#initialize an array to store any error message from the form
	$form_errors = array();

	#loop through the  required fileds array (for checking a condition)
	foreach ($required_fields_array as $key ) {
		if ( !isset($_POST[$key]) || $_POST[$key]==NULL) { //or i can check that by if( empty($_POST[$key]) ){ #CODE ...}
			$form_errors[] = $key."is a required field";
		}
	}	
	return $form_errors;	
}

/*	@param : $fields_to_check_length  --> an array containing the name of fields (ie key of the $_POST array) for which we 												want to check min required length eg. array('username' =>4, 'email' =>12)
	@return : array containgin all errors. which will be merged finally

*/
function check_min_length($fields_to_check_length){
	#initialize an array to store any error message from the form
	$form_errors = array();
	
	#loop through the  required fileds array (for checking a condition)
	foreach ($fields_to_check_length as $name_of_the_field => $minimum_length_required	 ) {
			if ( strlen( trim( $_POST[$name_of_the_field]) ) < $minimum_length_required) {//trim : to trim out all the spaces
				$form_errors[] = $name_of_the_field . "is too short, must be {$minimum_length_required} characters long";
			}
		}
	return $form_errors;

}

/*	@param :	$data ,store a key value pair array where key is the name of the html form control in, this case 'email' and 					value is the input entered by the user
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
		  		$form_errors[] = $_POST[$key]." is not a valid email address";
		  	}
		  }  
	}
	return $form_errors;
}

/*	@param : 	$form_errors_array  --> the array holding all errors which we want to loop through
	@return : 	string , list containing all error message
*/
function show_errors($form_errors_array){
	$errors = "<p style='color:red; padding:5px; border:0.5px solid grey '>There was ".count($form_errors_array)." error"; 
	$errors.= "<ul style='color:red;' >";	//  no ending  </ul>
	# loop through the error array and display all the elements
	foreach ($form_errors_array as $the_error) {
		$errors.= "<li>{$the_error}</li>";
		}
	$errors.="</ul> </p>" ;
	return $errors;

}

?>