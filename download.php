<?php
/*php function downloads a user's uploaded file from welcome.php that is saved on the server by first: checking if the file exists, if not, dies, if so, the file meta data is set and the file is loaded into the php buffer and then passed to the user's machine with the flush and readfile commands once done the function dies and needs to be called again in order to be used 
this 
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



        // Process download
        if(file_exists($filepath)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename($filepath).'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            ob_clean();
            flush(); // Flush system output buffer
            readfile($filepath);
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
