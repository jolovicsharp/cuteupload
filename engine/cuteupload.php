<?php
session_start();
include_once 'database.php';
include_once 'enc.php';
include_once 'functions.php';
define("UPLOAD_DIR",dirname(__DIR__, 1) . DIRECTORY_SEPARATOR . "cuteupload" . DIRECTORY_SEPARATOR); //cuteupload is default upload directory
if(isset($_POST['submit']) && isset($_FILES["file"]))
{
    if(isset($_POST['description'])) {
        if(strlen(nl2br($_POST['description'])) > 20) {
            
            $_SESSION['error'] = "Too long descrition, max is 20 chars.\n";
            header("Location: ../index.php");
            $description = "";
        }
        else {
            $description = nl2br(htmlspecialchars(stripslashes(trim($_POST['description'])))); //protection and multiline
        }
    }
    else {
        $description = "";
    }
    if(isset($_POST['password'])) {
        $password = htmlspecialchars(stripslashes(trim($_POST['password']))); //protection for unallowed characters
        if(strlen($password) > 25) {
            echo "That file doesen't exist";
            $_SESSION['error'] = "Too long password, max is 25 chars.\n";
            header("Location: ../index.php");
        }
        else {
        }
    }
    else {
        $password = "";
    }
if($_SESSION['token'] !== $_POST['t']) { //protection
    header("Location: ../index.php");
}
 else {
    if(isset($_FILES['file'])){
    $max_file_size = 25000000; //max file_size in this case 25mb
    $current_folder = generate_string($permitted_chars,128) . DIRECTORY_SEPARATOR; //generates random folder name
    $uploadfile=$_FILES["file"]["tmp_name"]; //file content
    $size_of_file = filesize($uploadfile); //size of file in bytes.
    if(filesize($uploadfile) > $max_file_size) {
       $_SESSION["error"] = "File name is too big, 25MB max";
       header("Location: ../index.php");
    }
    $readable_file_size = readableBytes($size_of_file); // converts bytes to readable file size
    $original_file_name=$_FILES["file"]["name"]; //original file name
    $ext = pathinfo($original_file_name, PATHINFO_EXTENSION); //get extension
    $encrypted_file_name = hash('sha384', $original_file_name); //encrypted file name
    $file_without_extension = basename($original_file_name, "." . $ext); //file without extension
    $md5file = md5_file($uploadfile); //md5 hash of file - useful for scanning for viruses
    $shafile = sha1_file($uploadfile); //sha1 hash of file
    mkdir(UPLOAD_DIR.$current_folder); // creating random folder we generated eariler
    $current_folder = UPLOAD_DIR . $current_folder;
    $encrypted_with_extension = $encrypted_file_name . "." . $ext; //encrypted_with_extension
    move_uploaded_file($_FILES["file"]["tmp_name"], "$current_folder".$original_file_name); //FILE UPLOAD
    encryptFile("$current_folder".$original_file_name); // encrypts file with AES encryption
    rename($current_folder.$original_file_name,$current_folder.$encrypted_file_name . "." . $ext); //change file name to encrypted string
    //encryptFile($current_folder.$encrypted_file_name . "." . $ext);
    define("CUTE_CODE",strtoupper(generate_string($permitted_chars,21))); //generating code for downloading file
    copy("index.php",$current_folder."index.php");
    $sql = "INSERT INTO file (file_name,file_size,md5hash,cutecode,sha1hash,original_file_name,encrypted_file_name,current_folder,extension,file_password,description,upload_time) VALUES (?,?,?,?,?,?,?,?,?,?,?,UNIX_TIMESTAMP())";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$original_file_name,$readable_file_size,$md5file,CUTE_CODE,$shafile,$original_file_name,$encrypted_with_extension,$current_folder,$ext,$password,$description]);
 }
 else {
    header("Location: ../index.php");
 }
}
} else {
    header("Location: ../index.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        /* very bad css */
        #info {
        background-color: #ee0979;
        color: white;
        /*text-align: center;*/
        margin: 35px;
        }
        .titl {
            text-align: center;
        }
        #sticky-footer {
            position: absolute;
            bottom: 0;
            width: 100%;
        }
        * {
            font-family: "Lato";
        }
    body {
        background-color: white;
        color: #ee0979;
    }
    .test {
        background-color: #ee0979;
        color: white;
    }
    @media only screen and (max-width: 768px) {
        #info {
            width: 100%;
            margin: 0;
            text-align: center;
        }
        }
    </style>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Brand</title>
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Catamaran:100,200,300,400,500,600,700,800,900">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:100,100i,300,300i,400,400i,700,700i,900,900i">
</head>
<body>
    <script>
        function copyText(){
  const text = document.getElementById('code').innerText
  const btnText = document.getElementById('btn') //small javascript - copy to clipboard
  navigator.clipboard.writeText(text);
  btnText.innerText = "Copied to clipboard."
}


   </script>
    <div id="container" class="">
        <div id="info" class=" shadow-lg p-3 mb-5 rounded ml-3 d-inline-flex flex-column justify-content-center align-items-center">
            <div id="" class="test p-3">
            <div class="titl">    
            <span class="titl"><b>SHARE YOUR CODE : <span id="code"><?php echo CUTE_CODE; ?></b></span></span><br>
            <small>Copy to clipboard by pressing the button</small><br><br>
            <button class="btn btn-primary border-1 border-white rounded" id="btn" onclick="copyText()">Copy to clipboard <i class="bi bi-clipboard"></i></button><br>
        </div>
                <hr>
                <span>🍬 File name : <?php echo $original_file_name;?></span><br> <!-- echoing file info -->
                <span>🍬 File size : <?php echo $readable_file_size;?></span><br> <!-- echoing file info -->
                <span>🍬 MD5 hash of your file : <?php echo $md5file;?></span><br> <!-- echoing file info -->
                <span>🍬 SHA-1 hash of your file : <?php echo $shafile;?></span><br> <!-- echoing file info -->
                <span>🍬 Its a : <?php echo strtoupper($ext);?> file.</span><br> <!-- echoing file info -->
                <?php if(!empty($description)) {
                ?>
                <span>🍬 Description of your file : <b><?php echo $description;?></b></span><br> <!-- echoing file info -->
                <?php }?>
                <?php if(!empty($password)) {
                ?>
                <span>🍬 This file is protected by password : <b><?php echo $password;?></b></span><br> <!-- echoing file info -->
                <?php }?>
            </div>
        </div>
    </div>
    <footer id="sticky-footer" class="mt-2 p-1 flex-shrink-0 py-2 bg-primary text-white-50">
        <div class="container text-center">
            <p>Coded by Dušan Jolović with love. jolovicsharp@gmail.com</p>
          <small>Copyright &copy; Cuteupload</small>
        </div>
</body>
</html>