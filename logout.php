<?php
/*
This logout function occurs when the user pushes the log out button inside welcome.php. This function it kills the cookie located on the browser and stops any active php functions from continuing by destroys the current instance of the session information 
Then the function redirects to the login screen and dies so any code running is not allowed to continue running
This function works because after it redirects the user to the login screen, the user can not manually type in there broswer to visit welcome.php  
*/
session_start(); //// Initialize the session.
//setcookie(session_name(), '', 100);
// clears the browser cache meaning browser would have to obtain cache again before redirecting to login screen. to save on time, I removed this
session_unset();
session_destroy();
$_SESSION = array(); // Unset all of the session variables.

//redirect to login page using header function
        header("Location: index.php");
        die; //so code doesn't continue
?>
