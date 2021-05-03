<?php 
/*
This file is for account creation it makes sure user enters all fields when submit button is pressed then compares what user has entered is unique within the SQL database to prevent duplicate accounts if not a user table is created for the user in SQL with the user's username and password then redirects the user to the login screen to sign in. a series of statements can be echoed if the user enters invalid statements to inform the user why an account wasn't made   
this page relies heavily on the config file to establish SQL connection and the functions php to check if a user is already signed in locally

all users are created their very own folder within a user's directory located inside the Nginx site's directory: /var/www/stuffmyfiles.cf/testing/
"testing" is the name of the directory that contains all the folders for every user 

Known Bug:
    An account may be created and the user may be redirected to the login screen but attempts to login using index.php state invalid username or password. This has occured once and only once so the chances are rare but possible. further inspection shows that the username and password were sucessfully created within the SQL database with the exact parameters passed on account creation and user login yet the username or password is deemed invalid
    currently the chances of encountering this bug are 0.027% (1/37)
*/
session_start();

        include("config.php"); 
        include("functions.php");
    //check if user clicked on submit button
        if($_SERVER['REQUEST_METHOD'] == "POST")
        {
                //something was posted
                $username = $_POST['username'];
                $password = $_POST['password'];
                $passwordConfirm = $_POST['confirm_password'];

                if(!empty($username) && !empty($password) && !is_numeric($username) && !empty($passwordConfirm))
                {       
                        if( $password != $passwordConfirm){
                                printf("passwords do not match");
                        } 
                        else {
                                $query = "select * from USER_INFO where USERNAME = '$username'";

                                //search for username
                                if ($stmt = mysqli_prepare($link, $query)) {
                                        /* execute query */
                                        mysqli_stmt_execute($stmt);
                                        /* store result */
                                        mysqli_stmt_store_result($stmt);
                                        /* close statement */
                                        $found = mysqli_stmt_num_rows($stmt);
                                        mysqli_stmt_close($stmt);
                                }


                                if($found == 1) {
                                        //print error message that username is already taken
                                        printf("\n Username is already taken");
                                        echo chdir("testing");
                                        echo getcwd();
                                        //mkdir("testing");
                                }
                                else {
                                        $OneGbyte = 1024;
                                        //save to database
                                        $query = "insert into USER_INFO (USERNAME, PASSWORD, PATHTOFOLDER, MAX_STORAGE ) values ('$username','$password',NULL,'$OneGbyte')";
                                        mysqli_query($link, $query);
                                        
                                        //create user folder in host file system
                                        $folderName = "/var/www/stuffmyfiles.cf/testing/".$username;
                                        echo $folderName;
                                        //chdir("/userdatastorage");
                                        mkdir($folderName, 0777, true); //0755 read, exe, and write for owner read and exe for everyone else 
                                        //0777 grants universal all permissions was required due to conflicts between Nginx server allowing php scripts to run
                                        $folderPrivateName = $folderName."/Private";
                                        mkdir($folderPrivateName, 0777, true);
                                        $folderContactName = $folderName."/Contacts";
                                        mkdir($folderContactName, 0777, true);
                                        $folderPublicName = $folderName."/Public";
                                        mkdir($folderPublicName, 0777, true);
                                        // redirect user to login page b/c they can now log in
                                        header("Location: index.php");
                                        die;
                                }
                        }
                }else
                {
                        echo "Please do not leave any blank fields & make sure your username is not only numbers";
                }
        }
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="bigwrapper">
        <h2>Sign Up</h2>
        <p>Please fill this form to create an account.</p>
        <div class="reg-inbigwrapper">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="reg-container">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                    <span class="invalid-feedback"><?php echo $username_err; ?></span>


                    <label>Password</label>
                    <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
                    <span class="invalid-feedback"><?php echo $password_err; ?></span>


                    <label>Confirm Password</label>
                    <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
                    <span class="invalid-feedback"><?php echo $confirm_password_err; ?> </span>


                    <button type=" submit" value="Submit"> Submit </button>

                </div>
                <p>Already have an account? <a href="index.php" class="gr">Login here</a>.</p>
            </form>
        </div>
    </div>

</body>

</html>
