<?php
/*This function takes the file ID from an uploaded file referenced in the welcome.php page and attempts to deletes the reference to the file from the MySQL Database and removes the file path locally on the server

Known Bug:
    1)The delete button functions by removes the file from the SQL Database rendering the file to be unable to be downloaded but the file is still visable/downloadable within the table located in Welcome.php. This is due to a permissions error with PHP attempting to use the DELETE statement within the SQL Database.  
    2) Due to permission conficts between PHP and Nginx, this php file currently cannot delete any files saved locally on the server.
*/
        include("welcome.php");

if(isset($_REQUEST["file"])){
    // Get parameters
    $file = urldecode($_REQUEST["file"]); // Decode URL-encoded string

    /* Check if the file name includes illegal characters
    like "../" using the regular expression */
    if(preg_match('/^[^.][-a-z0-9_.]+[a-z]$/i', $file)){
        $userfolder = $user_data["USERNAME"];
        $filepath = "/var/www/stuffmyfiles.cf/testing/$userfolder/" . $file;
        $userID = $user_data["USER_ID"];
        $query = "Select * from FILE_INFO where FIUSER_ID = '$userID' AND FNAME = '$file'";
        $result = mysqli_query($link, $query);
        $row = mysqli_fetch_assoc($result);
        $fileID = $row["FILE_ID"];
        $query2 = "DELETE FROM FILE_INFO WHERE FILE_ID = $fileID";
        echo $query2;

        if(file_exists($filepath)) {
                mysql_query($link, $query2);
                unlink($filepath);
            die();

        } else {
            http_response_code(404);
               die();
        }
    } else {
       // die("Invalid file name!");
    }
}


?>
