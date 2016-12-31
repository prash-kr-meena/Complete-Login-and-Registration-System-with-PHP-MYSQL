
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
/*	@param :	$data ,store a key value pair array where key is the name of the html form control in, this case 'email' 									and value is the input entered by the user
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
###########################################################################################################################

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
			$status = false;# ie no duplicasy is found so user can go ahead and process further
			$message = NULL;# as we dont require any message (WE JUST WANT HIM TO GO FURTHER.)
		}
	}catch(PDOException $ex){
		$status = 'exception';
		$message = "An error occoured : DURING THE CHECKING OF DUPLICASY OF {$columnName} IN {$databaseName}->{$tableName} ==> {$ex->getMessage()}";
	}
	$returnThis = array('status'=>$status, 'message'=>$message);
	return $returnThis;
}

######################################       checks duplicasy for the field from other users data     ########################################
function checkDuplicasy_filterMe($input, $columnName, $databaseName, $tableName, $db){
	# A BETTER WAY TO DO THIS --> just a littel change in the sql statement above
	try{					# FEEL LIKE THE ERROR WAS DUE TO SCOPE RULE (but i am not geting it , why it worked , in other files, ) EVEN THOUGH I HAVE INCLUDED THE DATABASE.PHP FILE ( see if there is error in including the file)
		$sqlQuery = "SELECT {$columnName}
					FROM {$databaseName}.{$tableName}
					WHERE {$columnName}=:input 
					AND id != :id";

		$statement = $db->prepare($sqlQuery);

		$statement->execute( array(':input'=>$input, ':id'=>$_SESSION['id'] ) );
		if($statement->rowcount()==1){
			$status = true;
			$message = "Sorry this {$columnName} is already taken ";
		}else{
			$status = false;# ie no duplicasy is found so user can go ahead and process further
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
  					timer: 6000,
  					type: '{$type}',
  					showConfirmButton: false
				});
	  				setTimeout(function(){
	    				window.location.href='{$page}'; 
	  				}, 5000);
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
# i could have stored the username and password in the remember me functionality then storing id , BUT IT WOULD BE NOT SAFE AS THESE REMEMBER ME FUNCTIONALITY STORES DATA INTO THE , COOKIES IN THE LOCAL SUSTEM OF THE USER
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
				echo popupMessage('Oops..',"this id does not exists in our database",'error','index.php');
				echo popupMessage('try this!',"try deleting your cookies for this site..",'error','index.php');
				$isValid = false;
				redirectTO("logout");
			}
		}catch(PDOException $ex){
			echo popupMessage('Oops..', "something went wrong ,WHILE CHECKING THE USER'S ID IN THE DATABASE,".$ex->getMessage(),'error','index.php');
		}# end try-catch
	}// end if
	return $isValid;		# by default it will return false, and returns true if the cookie is set and its valid too..
	# so for users that are not loged in it will return FALSE value
}
###########################################################################################################################

function guard(){
$isValid = true;
$inactive = 60*30;  # <-- just for testing period  3 minuits..

$fingerPrint = md5(  $_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT']  ); // this information will be takenevery time the user opens the website,(ie we get the ip and the the browser data , of every one who opens our website,)
#--> NOTE : this server 'REMOTE_ADDR' can be used to count the no of users, on our website -->  what we can do is make a table in which we store the different ip's of the people and we count these ips this will give the no of different ip users visited on our website

/* THE REASON OF USING THE FINGERPRINT IS TO PROTECT THE WEBSITE FROM THE SESSION HIGHJACKING,--> SEE HOW ==>
 suppose if we have not done this fingerprint verification ,--> and only the verifaication is that any cookie of named authentication system is set or not and if the data in it matches our database,or not,---> but what a hacker can do is  , after a man signin the website--> his cookie for that website is set for 30 days, say , and if he didnot logout his cookie remians there  --> so the hacker can steel this cookie for the website and, paste,it in his system and go to the site, --> eventually he has opened the users site whose cookie was created there---> this is because, all the conditions are true, the user never loged out and the browser found the cookie with the same data as it needed, even if the system is changed!
*/
															#  isset($_SESSION['username']) -->just to double check
 #this is to secure sesssion highjacking ie. our session is going on (when we loged in) -> so the session variables are also set, ie session[fingerprint] is set , but due to steel of the cookie now the session will be active over there also  as the person never loged out of the website,-> but now we can trac its system information , ie. this is the same pc or not
	if ( isset($_SESSION['fingerPrint']) && ($fingerPrint != $_SESSION['fingerPrint']) && isset($_SESSION['username']) ) {
		$isValid = false;
		redirectTO('logout');
	}elseif(isset($_SESSION['lastActive']) && (time()-$_SESSION['lastActive'] > $inactive) && isset($_SESSION['username'])){
		$isValid = false;

		#$timeOfLogingOut = $_SESSION['lastActive']; # so when the user logs out that time wil be captured and it will be stored in the database --> to show last active time,--> to their friends, after they loged out 

		# NOTE: ITS NOT THAT ACTIVE --> 1. this will work only if the the user is loged out due to the limit of inactivity , and not by if he presses his logout buttton , or if any one uses the above hacking method (--> in these two the user will be loged out but the time  will not be saved into the database,)--> this is because i am coding it in here , so i have TO MAKE A FUNCTION THAT HANDELS THAT LOGOUT AND SIMULTANIOUSLY SAVE THE TIME....
		
		# 2.
		redirectTO('logout');
	}else{# this variable will not be set for the user which not loged in --> because for them there does not exist such variable--> but this does not give error (when loading the index page when the user is not signed in)--> theis is becasuse  in the header file (in which the guard function is called every time, -> note also every time the session file goes attached to it ,)--> ie session is started and now we can set these session variable

		$_SESSION['lastActive'] = time(); # NOTE : (on click ,every time the page loads,--> adn this is in the header,so)that if the above two conditons are not true , then the last active time is always set to the current time,
		# that is when the user is loged out(not by pressing the logout button but by any of these two conditions , ) automatically the last inactivetime is set to the time of the system currently (EXample:   if the last active time is greater than the inactve time and we dont do any thing, after an our we refresh the page, --> NOW  this is the time when the script gets run and now THE LAST ACTIVE TIME WILL BE OF ONE HOUR LATE ie. THE TIME OF SYSTEM when the page is loaded)

		$_SESSION['fingerPrint'] = $fingerPrint;# now this will also be set for the first time when some one opens the site , and will be keep track of untill he logouts , 

		echo dateTime1($_SESSION['lastActive']);
		echo "  hello ";#--> it does not show up as it is in a hidden class (YOU CAN SEE THEM IN THE SOURCE CODE)
	}

return $isValid; # so for an unloged user it gets the value of TRUE automatically --> that is why it prints 1 in the source code

}
###########################################################################################################################
function dateTime1($dateTime){ # convert it to the date time format from the timestamp format
	$date = new DateTime(); 
	$date->setTimestamp($dateTime);		
	$date_string = date_format($date,'U=Y-m-d H:i:s');

	return $date_string;
}
#=--------------------------------------- another function for time conversion   -----------------------------------------
function dateTime2($dateTime){
	$date_string = strftime( "%d %b %y", strtotime($dateTime) );
	return $date_string;
	# what strtotime() does is it converts the timestamp to the human readable time
	# what strftime() --> does is it format this time inro more convinent way
	# ie. %b is for abbrevation of months, eg -> jan, feb etc.
	# and %d is for showing the 2 digits of the date, eg. 02, 04, 29 etc.
	# and %y is for showing the 4 digits of the year, eg. 2000, 2004, 1996 etc.
}

###########################################################################################################################
function Not_authorized($message,$page,$link_ame){
	$returnMesssage= "<div class=\"container\" align=\"center\" style=\"padding-top: 30%\" >
						<section>
							<p class=\"lead\"> {$message} </br> <a href=\"{$page}\" >{$link_ame} </a> </p>
							<p class=\"lead\">HACK US !  and please give us <a href=\"feedback.php\">feedback</a>  on our security! </p>
						</section>
					</div>";
	return $returnMesssage;
}
###########################################################################################################################
function isValidImage($file){
	$form_errors = array(); # empty array

	# finding the image extension by spliting it by period '.'
	$part = explode(".", $file);
	$ext = end($part);

	$ext = strtolower($ext); # so that there is no conflict in the types 
	switch ($ext) {
		case 'jpg':
		case 'png':
		case 'gif':
		case 'bmp':
		# case 'doc':  --> for example
		#--> if any of these will be true then i will return a empty array in the $form_errors  --> so finally there is no error
		return $form_errors;  
	}
	# otherwise we will report an error in the form error array
	$form_errors[] = " \" {$ext} \" is not a valid image file extension";
	return $form_errors;
}

function _token(){
	$randomToken = base64_encode(openssl_random_pseudo_bytes(32))."open ssl<br>"; # --> this will generate a random token for us ..   32 is the bytes  
	# another way
	//echo $randomToken = md5(uniqid(rand(),true))."md5"; # uniqid --> is a builtin function provided by php only  --> not much secure 
	$_SESSION['token'] = $randomToken;
	
	return $_SESSION['token'];
}

function validate_token($requestToken){
	if( isset($_SESSION['token']) && $requestToken === $_SESSION['token'] ){ # ie. if it is not set thet means request is coming from a third party (HACKER ALERT !)
		unset($_SESSION['token']);
		return true;
	}
	return false; # by default it will return false if we found thta the session variable is not set...

}

?>