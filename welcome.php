<?php
/*
Welcome.php checks to make sure the user is logged in, and establishes a connection to the SQL Database  
Welcome.php is the home page unique to each user. This page is where the user can upload files, delete their files, move their files to different folders, sort their files, download their files, search for other users, share their files with other users, create additional folders, add other users as contacts, and view a list of their contacts. 
The page also is suppose to include a log out button to sign them out, a friendly message telling them how much data they are allowed to upload to their system and a useful storage bar that displays the amount of used space as percentage. 

Currently the page is only set up where the user is allowed to upload png, jpg, and pdf files under 12  MBs in size, one file at a time. A user can view/download any files they have uploaded through a table displaying their file's names, file size, data added, and file type. 
The user may also delete their files. 

Known Bug(s):
    1) see upload.php 
    2) see delete.php
    3) search button returns a blank screen because the search.php file was created but never populated due to time restraints requiring us to prioritize more important functions. 
*/

// Initialize the session
session_start();
        //if session can be accessed on any page it means we can place a user_id in as the value of session and check on every page if the user_id is legitimate  to know if user is>
        include("config.php");
        include("functions.php");
//      include("logout.php");
        //checking if there is a connection to the database
        $user_data = check_login($link);
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["USER_ID"]) || $_SESSION["USER_ID"] !== true){
  //  header("location: index.php");
        //echo "YOU ARE NOT LOGGED IN"; 
   // exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="shortcut icon" type="image/png" href="MainLogoSmall.png" />
    <link rel="stylesheet" href="style.css">
</head>

<body>
    &nbsp;
    &nbsp;
    &nbsp;

    <div class="search-container">
        <form action="search.php">
            <input type="text" placeholder="Search for Users" name="search" style="width:60%;">
            <button type="submit" style="width:33%; padding: 13px 20px;">Search</button>
        </form>
    </div>

    <p></p>

    <form action="upload.php" method="post" enctype="multipart/form-data" style="position: absolute;  bottom: 30px; right: 40px; text-align:center;">
        Select file to upload:
        <div class="image-upload">
            <label for="fileToUpload">
                <img src="Plus.png" style="height: 60px; width: 60px;">
            </label>
            <label for="uploadFileBtn">
                <img src="Arrow6.png" style="height: 60px; width: 60px;">
            </label>
            <input type="file" name="fileToUpload" id="fileToUpload">
            <input type="submit" value="Upload" name="submit" id="uploadFileBtn">

        </div>
    </form>
    <a href="logout.php" class="gr" style="position: fixed; top: 15px; right: 50px; ">Logout</a>

    <img src="MainLogo3.png" alt="logo" style="margin-top:30px; width:400px; height:200px; text-align:center;">

    <div>
        <h1 style="margin-top: 20px;">Hi, <b>racecar</b>. You are allocated 1 Gigabyte of storage</h1>
    </div>
    <table style="width: 50%; margin: 0px auto; bottom: 100px;">
        <tr>
            <th>Sort by:</th>
            <th>Name</th>
            <th>Date Added</th>
            <th>Size</th>
            <th>Type</th>
            <th></th>
        </tr>
        <?php
                            
                                $userID = $user_data["USER_ID"];
                                $query = "Select * from FILE_INFO where FIUSER_ID = '$userID'";
                                $result = mysqli_query($link, $query);
                                $numRow = mysqli_num_rows($result);
                                if($numRow > 0){
                                        while ($row = mysqli_fetch_array($result)){
                                                $download = '<a href="download.php?file=' . urlencode($row["FNAME"]) . '">Download</a>';
                                                $delete = '<a href="delete.php?file=' . urlencode($row["FNAME"]) . '">Delete</a>';
                                                echo "<tr><td>". $download . "</td><td>". $row["FNAME"] ."</td><td>" . $row["DATE_ADDED"] . "</td><td>" . $row["FILE_SIZE"] . "</td>"</td><td>" . $row["FTYPE"] . "</td><td>" . $delete .  "</td></tr>";
                                        }
                                
                                        echo "</table>";
                                }
                                else {
                                        echo "0 result";
                                }

     ?>

    </table>
    /*
    <!-- what it looks like when 3 files have been uploaded
    <table style="width: 50%; margin: 0px auto; bottom: 100px;">
        <tr>
            <th>Sort by:</th>
            <th>Name</th>
            <th>Date Added</th>
            <th>Size</th>
            <th>Type</th>
            <th></th>
        </tr>
        <tr>
            <td><a href="download.php?file=filed.jpg">Download</a></td>
            <td>filed.jpg</td>
            <td>2021-04-28</td>
            <td>40349</td>
            <td>jpg</td>
            <td><a href="delete.php?file=filed.jpg">Delete</a></td>
        </tr>
        <tr>
            <td><a href="download.php?file=Ch5.pdf">Download</a></td>
            <td>Ch5.pdf</td>
            <td>2021-04-30</td>
            <td>500132</td>
            <td>pdf</td>
            <td><a href="delete.php?file=Ch5.pdf">Delete</a></td>
        </tr>
        <tr>
            <td><a href="download.php?file=Gao1.jpg">Download</a></td>
            <td>Gao1.jpg</td>
            <td>2021-04-30</td>
            <td>693446</td>
            <td>jpg</td>
            <td><a href="delete.php?file=Gao1.jpg">Delete</a></td>
        </tr>
    </table>
     --> */



</body>

</html>
