<?php
/*Index.php is the first screen a user sees when a user visits stuffmyfiles.cf
index.php is where a user is allowed to sign in if they have already created an account and provides links to get in contact with the developers through 'Contact Us' ND create an account if they do not have one already with the 'Sign Up' link.
when a user enters their credentials to sign in the page calls the SQL database and checks if the username they entered matched a username table inside the database. if so it then checks if the password attribute inside the User Table matchs what the user has entered as a parameter if so, a session cookie is established on the user's browser and the user is redirected to the Welcome.php page. 
if the user has entered parameters that do not match any tables located inside the SQL database, or has entered no values all before clicking the login button the user is asked to either fill out the input forms or that they have entered a wrong username or password 
*/
session_start();
        include("config.php");
        include("functions.php");

        if($_SERVER['REQUEST_METHOD'] == "POST")
        {
                //something was posted
                $username = $_POST['USERNAME'];
                $password = $_POST['PASSWORD'];

                if(!empty($username) && !empty($password) && !is_numeric($username))
                {
                        echo "you have entered values for username and password";
                        //read from database
                        $query = "select * from USER_INFO where USERNAME = '$username' limit 1";
                        $result = mysqli_query($link, $query);
                        
                        if($result)
                        {
                                if($result && mysqli_num_rows($result) > 0)
                                {
                                        $user_data = mysqli_fetch_assoc($result);
                                        echo "user name found checking for password match";             
                                        //check for password
                                        if($user_data['PASSWORD'] === $password)
                                        {
                                                
                                                $_SESSION['USER_ID'] = $user_data['USER_ID'];
                                           
                                                
                                                echo "All systems go, Captain, preparing for redirect";
                                                header("Location: welcome.php");
                                                die;
                                        }
                                }
                                else {
                                   echo "user name not found";
                                }
                        }
                        
                        echo "wrong username or password! error 1";
                }
                else
                {
                        echo "wrong username or password! error 2";
                }
        }

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>StuffMyFiles Log in</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>


    <div class="bigwrapper">
        <h2>Login</h2>
        <div class="inBigWrapper">
            <br><br>

            <form method="post">
                <div class="container">
                    <label for="USERNAME"><b>Username</b></label>
                    <input type="text" placeholder="Enter Username" name="USERNAME" required>

                    <label for="PASSWORD"><b>Password</b></label>
                    <input type="password" placeholder="Enter Password" name="PASSWORD" required>

                    <button type="submit">Login</button>

                    &nbsp;

                    <a class="gr" href="contact.html">Contact Us</a>
                    &nbsp;
                    <a class="gr" href="register.php">Sign Up</a>

                </div>


            </form>

        </div>
    </div>
</body>


</html>
