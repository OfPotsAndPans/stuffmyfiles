<?php
/*This file contains a function that checks if user is logined in or not to the site if not it redirects the user back to the sign in screen this is needed so users can not access the welcome.php page without signing in with a valid account first 
this function relies on the config.php file so it can check credentials from the sql database. 
The check_login function is required to be within its own file because otherwise the function would enter an infinate loop and practically DDoS the entire system forcing the server to return 500 error messages
*/
include ('config.php');

//checks if user is logged in
function check_login($link) {
//if session user id exists, if the value is set.
        if(isset($_SESSION['USER_ID']))
        {
                $id = $_SESSION['USER_ID']; //set id to value of user_id
        //check if id is in database  
                $query = "select * from USER_INFO where USER_ID = '$id' limit 1";
        //Read from the database
                $result = mysqli_query($link, $query);
        //if result is positive and # of rows is greater than 0
                if($result && mysqli_num_rows($result) > 0)
                {
            //collect the data and assign it to user_data
            //uses "fetch associative array"
                        $user_data = mysqli_fetch_assoc($result);
                        return $user_data;
                }

        }
        //if it doesn't work
        //redirect to login page usering header function
        echo "check_login didn't work";
        header("Location: index.php");
        die; //so code doesn't continue≈

}
?>
