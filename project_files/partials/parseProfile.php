<?php 
if (isset($_SESSION['username']) ) {
	# as i have the user session active (ie  how he is able to login ) --> from the isCookieValid() function present after  guard() function in headers
	$id = $_SESSION['id'];	# it was already set,(IF THE USER DOES NOT DIRECTLY TRIES TO OPEN BY THE PATH,--> so for  path case we provide a security)--> ABOVE ==> this will be set either by the direct sign in or by the cookie signin (ie rememberme functionality)

	#  $username = $_SESSION['username'];      ----> this is the reason why  the name in the profile page, is not changing , because the name ie set in the session variable is not set.. so , we have to change that..., so now collecting this from the database, directly , it does not matter with the id as we are not allowing himm to change the userid

	$fingerprint = $_SESSION['fingerPrint']; # NOTE: these two below session variables will hold their values in the case only when the user has loged in ie. ehen he entered the login page by fist loging into the website but they will not hold values if the user will come through the remember me functionality..
	# THE REASON FOR THAT : when the user login through the cookie -> the main job is done by the isCookieValid() function, now if the cookie exists it will decode it and take the data of the user (who has saved the cookie) and then sets the session variables (above the header file it includes the session variable ) NOTE ; THAT these session variables are just for session[id] and session[username] and no session variable for the keys ==> fingerprint and lastActive has been set so now at the login through cookie these variables are unset so are not defined, and ----> this will give error if we try to work with them
	# note the previous session variables are deleted when the browser was closed

	#now note before this isCookieValid()  function is called when we loged in by cookie method, there is one funcition guard() whcih is called first --> now in this guard function a fingerprint variable is created and this finger print variable holds the encrypted value of the system's ip and its browser information,so now it compares this finger print variable with the stored value in the session variable session['fingerprint']--> but note at this time when we loged through the cookie method there is no such session variable set only the two varables are and NOTE THESE VARIABLES ARE ALSO SET LATER ON AFTER THSI STATEMENT IS EXECUTED --> so in the cookie login-->both of its conditions in the if and ifelse are gona fail---(as there is no session variabels set neither sesssion[fingerprint]  nor session['lastactive']) and finally its gona go to the else condition and creates/set the session variable for lastActive to the current time of the system --> in timestamp value

	#notice there is no need of taking this finger print to the database, as the soul purpose is to check the system information bw the session period, --> ie bw that session period there should not be any change in the system information (after the session going to destroy session id will be regenerated), so if the session starts the system information will be taken (as firstly it would not be set--> in the case of cookie login, but it will be set in the else condition --> I HAVE ADDED THAT NOW) --> now onwards both of these variables are set ie now session[fingerprint] and session[lastActive]--> both are set with  the current system information and current system data==> and now if the page is loaded conditions will be checked (not as the last time when no one is set and directly goes to the else condition),this time according to the conditions (if the value is set for the session[fingerprint] which willl surely be set after the first else condition run)->if the value stored in the variable $fingerprint is same as the previously stored value in the session[fingerprint]-->then its no error all good(but is there is then we logout) , and if the lastActive time in the session variabel[lastActive] is greater then the specific tiem we --> (we will logout ) but if not then we finally goes to the else condition and now here we set the lastActive time again (so every time its get updated as we refresh the browser, so if the inactivity goes longer it automatically deactivates) and the system information is also reassigned well at this time its UNNECESSSARY -- as the system informatin has not been changed(cause if it would it would logout just after the if condition)--> but it is just for sace for the first time initialization of the system information (cause it protects the session of a user,-> and every user have their different systems on ehich we have to secure there sessions,)=> so first time its system is registered and then it keeps track of its system value in any case the system value changes be the users session it will detect it and it will automatically logout him...


	# NOW THE BIG QUSTION ==> how to do this session highjacking --> and is this session highjacking or cookie higjacking,-or same, --> i think its different , 
	# HOW TO CHANGE THE SYSTEM BW THE SESSION OF A USER

	# now also this application is prone to cookie highjacking --> because in cookie we just have  an id that will be automatically converted and the whole process will be done by the function isCookieValid();
	#==>because i have not added any type of condition that (OR DATA INSIDE THE COOKIE) this system cookie should not workd in this system or not...

	# is this cookie highjacking is possible for other possible sites, that i steel cookies from some ones and run that cookie on mine ==> i guss the best way to secre the cookie is that not allowing one cookie to work on any other system, ie save the cookie with the system information in it

	$lastActive = $_SESSION['lastActive'];#--> note that this variable is set but not the fingerprint variable, see above
	$timeYouLogedIn = time();#-->no use as when ever i referesh it is initialized again and both of this variable ie, is the session variabel and this have the same time (as session get initialized in the guard funcion -> when initialized AND this gets initialized here)

	# for email and other data we have to use the sql statement
	try{
		$sqlQuery = "SELECT * 
					FROM register.users 
					WHERE id = :id ";
		$statement = $db->prepare($sqlQuery);
		$statement->execute( array(':id'=>$id) );

		if ($row = $statement->fetch()) {# ie, if some  data is pulled from the database
			$email = $row['email'];
			$username = $row['username'];
			$join_date = $row['join_date'];

			# searching for the users profile with his name, --> but extension can be any of them
			$extensions = array('jpg', 'png', 'gif', 'bmp');
			for ($i=0; $i<3 ; $i++) { 
				$userPic = "uploads/".$username.".".$extensions[$i];
				if (file_exists($userPic)) {
					$found = true;
					break;
				}# -> so as soon as i found the file in the uploads folder i break out of the loop and check that if i have found then uplod that file else upload the default file
			}
			$default = "uploads/default.jpg";

			if ( isset($found) && $found === true  ) {
				$userProfile = $userPic; # now if i have found that image store its name int this variable
			}else
				$userProfile = $default;
			
		}else{
			# function flashMessage($message,$color='red')--> red is by default
			echo flashMessage('Sorry there was some error loading your data!');
			//echo "11111111";
		}
	}catch(PDOException $ex){
		echo flashMessage("something went wrong while --> FETCHING YOUR DATA FROM THE DATABASE,-->{$ex->getMessage()}",'red');
		//echo "0000000000000";
	}
	$encodedId = base64_encode("24792024278".$id); # encoding hardly
}// adn if not then he is trying to execess the profile directly--> handeled it

 ?>