<?php
/*This php file is meant to upload the user's selected file. The first thing it does is makes sure the user is logged in and a connection to the SQL database is established. Next, it intiliazes where the user's folder is located, what the file name is called, the size of the file, and the image file type. 
the file can only be uploaded if it meets certain criteria: 
    the file doesn't already exist in the database, 
    the file is of certain file formats jpg, png, or pdf
    the file size is not greater than 12 MB
if the files do not meet these restrictions then the file echos a message to the user that the file is will not be uploaded 
otherwise the php file changes the file's permissions so that the file is readable, writeable and executiable, this is required because the web server has permission conflicts and otherwise would not allow the user to download, view, or work with the file as we intend for them to be able to.
next we obtain the user's id from the mysql database in order to establish a forign key relationship once the file is uploaded to the SQL database
lastly, the file is moved from buffer and uploaded to the web server inside the user's unique folder and the file is established in the SQL database. 
if something goes wrong an error message is reported to the user via an echo statement.
either way the user is then redirected back to the welcome.php page

all files are placed within the user's uninue folder within /var/www/stuffmyfiles.cf/testing/$username
"testing" is the name of the directory that contains all the folders for every user 
"$username" contains the name given to the user's folder which is subsequently also the username of the user


KNOWN BUGS:
    upload.php may redirect the user back to welcome.php without completing the upload 
    or 
    the site may linger in upload.php for an unnecessary amount of time without sending the buffer to the hostmachine and without any indication that the file wasn't sent
    To bypass this issue simply click on the upload button once again, another solutuon if the first once shows no promise is to refresh the page.
    The last step, if all else fails, is to try again after 600 secs have passed. 
    
    
     The PHP processor that handles our upload function has been set up to be forgiving in the amount of time it allows for an upload to take place: 600 secs until a function is ended. This is because the bandwidth of the server is limited and theoretically files uploaded can be throttled if the network the server is in is using up bandwidth for other devices or the host machine itself is using processing time for other processes.
*/

// Initialize the session
session_start();
        //if session can be accessed on any page it means we can place a user_id in as th>

        include("config.php");
        include("functions.php");
        //checking if there is a connection to the database
        $user_data = check_login($link);
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["USER_ID"]) || $_SESSION["USER_ID"] !== true){
  //  header("location: index.php");
        //echo "YOU ARE NOT LOGGED IN"; 
   // exit;
}


$userfolder = htmlspecialchars($user_data["USERNAME"]);

echo $userfolder;
$file_name = basename($_FILES["fileToUpload"]["name"]);
$today = date("Y-m-d"); //  MM DD, YYYY, h:m (am | pm)
$fileSize = $_FILES["fileToUpload"]["size"];
$userfolder = htmlspecialchars($user_data["USERNAME"]);
$target_dir = "testing/$userfolder/"; //specifies the directory where the file is going t>
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]); //specifies the path>
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION)); //holds the file >
//echo $uploadOk;


// Check if file already exists
if (file_exists($target_file)) {
  echo "Sorry, file already exists.";
  $uploadOk = 0;
}
// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "pdf"
&& $imageFileType != "txt" ) {
  //echo "Sorry, only JPG, PDF, PNG & TXT files are allowed.";
  $uploadOk = 0;
}
// Check file size
if ($_FILES["fileToUpload"]["size"] > 12000000) {
  //echo "Sorry, your file is too large.";
  $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
  echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} 
  else
  {
  chmod($target_file,0777);
        $query = "select USER_ID from USER_INFO where USERNAME = '$userfolder'";
         if ($stmt = mysqli_prepare($link, $query)) {
                                        /* execute query */
                                        mysqli_stmt_execute($stmt);
                                        /* store result */
                                        mysqli_stmt_store_result($stmt);
                                        /* close statement */
                                        $found = mysqli_stmt_num_rows($stmt);
                                        mysqli_stmt_close($stmt);
                                }
        $result = mysqli_query($link, $query);
        $row = mysqli_fetch_assoc($result);
        $user_id = $row["USER_ID"];
        if ((move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) && ($found == 1)) 
        {
                $query = "insert into FILE_INFO (FIUSER_ID, FNAME, FTYPE, DATE_ADDED, FILE_SIZE, FISPUBLIC, PATHTOFILE, CAPTION) values ('$user_id', '$file_name', '$imageFileType',Type', '$today', '$fileSize', NULL, 'target_dir', NULL)";
                mysqli_query($link, $query);
                header("Location: welcome.php");
                die;
      
        }
        else 
        {
                echo "Sorry, there was an error uploading your file.";
                header("Location: welcome.php");
                die;
        }
}

?>
