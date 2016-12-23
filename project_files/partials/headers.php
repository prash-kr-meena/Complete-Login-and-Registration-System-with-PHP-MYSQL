<!-- IT CONTAINS THE HEADER AND THE NAVIGATION (so it will be present everywheere )-->
<?php include_once 'resource/session.php'; # --> started the session here, now we will be able to set the session variables?>
<?php  include_once 'resource/utilities.php';; //its not ==> ../resource/utilities because, its the header of login page , so the header page is currently on the login page, and from the login page the address, of the utilities page is this only.WELL NOTE CAREFULLY IT IS IMPORTANT TO MENTION IT HERE AS ITS NOT JUST FOR THE LOGIN PAGE ITS THE HEADER OF ALL THE PAGES, Ie. INDEX (the main part which is loaded bydefault every time)--> and it does not have this file so we have to do that here
# if you see we already have included this file in  the login page, already so it is not necessary to use it here, well
# NO ERROR BECASUE its include_once--> thats what it do , it does not produce error in for more than one apperence ?>

<!DOCTYPE html>
<html>
<head lang="en">
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title> <?php if ( isset($page_title) ) echo $page_title; ?> </title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet">
    <!-- sweet alert -->
    <script src="js/sweetalert.min.js"></script>
    <link rel="stylesheet" type="text/css" href="css/sweetalert.css">  

</head>
<body>

<!-- ####################################    from the bootstrap template  ##############################################-->
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">

        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="index.php">User Authentication System </a>
        </div>

        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">

          <!-- WHAT IF I CREATE TWO DIFFERENT NAVIGATION BAR FILES, ie one for loged in user and one for non loged in users  -> THAT WILL BE PAIN IN THE ASS, AS THEN WE HAVE TO INCLUDE THE NAVIGATION BAR IN EACH PAGE, AND ALSO WE HAVE TO CHECK THE CONDITION FOR THAT -> REAL PAIN IN ASS  -->

          <!--what guard actually does is it guards the user from the cookie highjacking attack and from the specific time of inactivity by the user ==> they get automatically logedout -->
          <i class="hide">  <?php echo guard() #--> this will be hidden this is just for debugging purpose ?> </i>
          <!--AND WHAT isCookieValid() function do is it checks is there any cookie previously set or not , if yes then it decode the data of this cookie and then checks is this decoded data matches to any id in our database ,==> so if it maches then by using this id , we collect other information of the user, and we again starts the session by setting the session variables and set the isValid variable to true
          # but if any of the above thing goes wrong the isValid variable is set to  -->

          <!-- NOTE : You will observe that the job of the isCookieValid() function is to check for the cookie and if there is cookie then analize it to confirm it is genuine and if it is then start the session by setting up the session variables for session[id] and session[username] ( this session variable then are used to show the navigation bar and othe things )and if not genuine they will not do any thing-->
          <!-- now the whole purpose is to set the session variables now in case the user has just logined and now as he didnot close the browser too.. ie his session is going on , so his session is already set, so there is no need of checking the cookie(supposing that he had checked the remember me functionality)-->
          <!--SO BEST IS TO EXECUTE THSI FUNCTION WHEN THE SESSION VARIABLES ARE NOT SET EITEHER OF THEM as at login both with id and with loin is created !-->

          <?php if ( !isset($_SESSION['username']) ) {# ie call this function when the session is not set
            isCookieValid($db) ;# which is set when he logins
          }# after the first login the it will send you to the index page,which it will go through this headers page always?>
          <!--well  if you see the value of isValid is immaterial, if it is valid then the session will already be set by the isCookieValid() function, SO YOU CAN REMOVE IT TO , -->
          <?php if( isset($_SESSION['username']) ) :#--> does not produced error? see above why 
          # NOTE: THIS will be set on the time of login (ie. when ever he logins to get in here and not come to the headers-> which is a part of all the file/pages --> headers by the remember me functionality) BUT IF he comes with the remember me functionallity it will not be set so the above isCookieValid() function will handel with that ?>  

              <li><a href="profile.php">My profile</a></li>
              <li><a href="logout.php">Logout</a></li>
          <?php else: ?>
              <li ><a href="login.php">Login</a></li>
              <li><a href="signup.php">Sign-up</a></li>
              <li><a href="feedback.php">Feedback</a></li>
          <?php endif ?>

            
          </ul>
        </div><!--/.nav-collapse -->

      </div>
    </nav>
    <!-- ########################################   END the bootstrap template  ##########################################-->