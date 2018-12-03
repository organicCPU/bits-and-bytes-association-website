<?php

define("baseLoaded", 1);

require_once "../lib/config.php";
require_once $_SERVER['SERVER_PATH'] . "lib/auth.php";
require_once $_SERVER['SERVER_PATH'] . "lib/process.php";
require $_SERVER['SERVER_PATH'] . 'lib/imageResize.php';


if(!isset($_SESSION['login_user']))
{
    exit();
}
else
{
  global $myID;
  $myID = (getUsers($_SESSION['login_user'])[1][0]['UID']);
}

function client_upload_path($original_filename, $prefix = 'original_', $suffix = '') {
    $current_folder = dirname(__FILE__);
    // Build an array of paths segment names to be joins using OS specific slashes.
    $path_segments = [$_SERVER['CLIENT_PATH'], 'assets', 'img', 'user', $prefix . basename($original_filename) . $suffix];
    
    // The DIRECTORY_SEPARATOR constant is OS specific.
    return join(DIRECTORY_SEPARATOR, $path_segments);
 }
     // file_upload_path() - Safely build a path String that uses slashes appropriate for our OS.
    // Default upload path is an 'uploads' sub-folder in the current folder.
    function file_upload_path($original_filename, $prefix = 'original_', $suffix = '') {
        $current_folder = dirname(__FILE__);
        
        // Build an array of paths segment names to be joins using OS specific slashes.
        $path_segments = [$_SERVER['SERVER_PATH'] . 'assets', 'img', 'user', $prefix . basename($original_filename) . $suffix];
        
        // The DIRECTORY_SEPARATOR constant is OS specific.
        return join(DIRECTORY_SEPARATOR, $path_segments);
     }
 
     // valid_file() - Checks the mime-type & extension of the uploaded file for "image-ness".
     function valid_file($temporary_path, $new_path) {
         $allowed_mime_types      = ['image/gif', 'image/jpeg', 'image/png', 'application/pdf'];
         $allowed_file_extensions = ['gif', 'jpg', 'png', 'pdf'];
         
         $actual_file_extension   = pathinfo($new_path, PATHINFO_EXTENSION);
         $actual_mime_type        = getimagesize($temporary_path)['mime'];
         
         $file_extension_is_valid = in_array($actual_file_extension, $allowed_file_extensions);
         $mime_type_is_valid      = in_array($actual_mime_type, $allowed_mime_types);

         return $file_extension_is_valid && $mime_type_is_valid;
     }
 
 //lazy train doot doot
     function valid_resize($path) {
         $allowed_mime_types      = ['image/gif', 'image/jpeg', 'image/png'];
         $allowed_file_extensions = ['gif', 'jpg', 'png'];
         
         $actual_file_extension   = pathinfo($path, PATHINFO_EXTENSION);
         $actual_mime_type        = getimagesize($path)['mime'];
         
         $file_extension_is_valid = in_array($actual_file_extension, $allowed_file_extensions);
         $mime_type_is_valid      = in_array($actual_mime_type, $allowed_mime_types);
         
         return $file_extension_is_valid && $mime_type_is_valid;
     }
 
     $image_upload_detected = isset($_FILES['image']) && ($_FILES['image']['error'] === 0);
     $upload_error_detected = isset($_FILES['image']) && ($_FILES['image']['error'] > 0);
 
     if ($image_upload_detected) { 
         $image_filename        = $_FILES['image']['name'];
         $temporary_image_path  = $_FILES['image']['tmp_name'];
         $new_image_path        = file_upload_path($image_filename);
         if (valid_file($temporary_image_path, $new_image_path)) {
             move_uploaded_file($temporary_image_path, $new_image_path);
             //part 4
             if ((valid_resize($new_image_path))) //very lazy code xd
             {
                 $image50 = new \Gumlet\ImageResize($new_image_path);
                 $image500 = new \Gumlet\ImageResize($new_image_path);
                
                 $image50->resizeToWidth(50, $allow_enlarge = true);
                 $image50->save(file_upload_path(substr($image_filename, 0, (strlen($image_filename)-1 - strlen(pathinfo($new_image_path, PATHINFO_EXTENSION)))), "original_", "_thumbnail." . pathinfo($new_image_path, PATHINFO_EXTENSION)));
 
 //substr($image_filename, 0, (strlen($image_filename) - strlen(pathinfo($new_image_path, PATHINFO_EXTENSION))))
                 
                 $image500->resizeToWidth(500, $allow_enlarge = true);
                 $image500->save(file_upload_path(substr($image_filename, 0, (strlen($image_filename)-1 - strlen(pathinfo($new_image_path, PATHINFO_EXTENSION)))), "original_", "_medium." . pathinfo($new_image_path, PATHINFO_EXTENSION)));

                 global $newpath;

                 $newpath = client_upload_path(substr($image_filename, 0, (strlen($image_filename)-1 - strlen(pathinfo($new_image_path, PATHINFO_EXTENSION)))), "original_", "_medium." . pathinfo($new_image_path, PATHINFO_EXTENSION));

                 associateImage($myID, $newpath);
             }
         }
     }

    if (isset($_GET["DELET"]))
    {
        $var = filter_input(INPUT_GET, "DELET", FILTER_SANITIZE_SPECIAL_CHARS);
        
        if($var == true)
        {
            associateImage($myID, null);
        }
    }
?>

 <!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" href="<?=$_SERVER['CLIENT_PATH']?>/assets/img/icons/favicon.ico" type="image/x-icon">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?=$_SERVER['CLIENT_PATH']?>/assets/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">  
    <link rel="stylesheet" href="<?=$_SERVER['CLIENT_PATH']?>/assets/css/main.css">
    <title>Upload Headshot</title>
</head>
<body>

<?php
    include $_SERVER['SERVER_PATH'] . "/header.php";
?>

    <div class="container-fluid">
    <h1>Upload Headshot</h1>
    <form method='post' enctype='multipart/form-data'>
         <label for='image'>Image Filename:</label>
         <input type='file' name='image' id='image'>
         <input type='submit' name='submit' value='Upload Image' class="form-control-file">
     </form>
     <?php if(isset(getUsers($_SESSION['login_user'])[1][0]['DisplayPicture'])) :?>
     <img src = "<?=getUsers($_SESSION['login_user'])[1][0]['DisplayPicture']?>"/>
     <a type="submit" class="btn btn-danger" name="command" value="Delete" href="<?=$_SERVER['CLIENT_PATH']?>/post/image.php?DELET=true"/>DELET</a>
     <?php else : ?>
    <p>No display image saved for this user.</p>
     <?php endif ?>
    </div>
    <?php
    
    ?>

<?php
    include $_SERVER['SERVER_PATH'] . "/footer.php";
?>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="<?=$_SERVER['CLIENT_PATH']?>/assets/js/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="<?=$_SERVER['CLIENT_PATH']?>/assets/js/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="<?=$_SERVER['CLIENT_PATH']?>/assets/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>
</html>