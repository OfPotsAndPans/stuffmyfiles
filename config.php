<?php
/*
This file is for connecting to the SQL Database, it attempts to connect to the database then it checks if the site was able to connect and, if not, returns an error and informs the user something went wrong. 
*/

/* Database credentials are defined with our specific account username and password */
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'user'); //changed for security reasons
define('DB_PASSWORD', '********'); //removed for security reasons
define('DB_NAME', 'smf_DB'); //changed for security reasons
 
/* Attempt to connect to MySQL database */
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
//echo "Connected to MySQL Server"; 
// Check connection
if($link === false){
    echo "Oops! Something went wrong. Please try again later.";
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>