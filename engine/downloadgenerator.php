<?php
session_start();
include_once "database.php";
include_once "functions.php";
include_once "enc.php";
define("UPLOAD_DIR",dirname(__DIR__, 1) . DIRECTORY_SEPARATOR . "cuteupload" . DIRECTORY_SEPARATOR); //upload dir
if(isset($_SESSION['cutecode'])) { //session from last page cutecode
$cutecode = $_SESSION['cutecode'];
$stmt = $conn->prepare("SELECT * FROM file WHERE cutecode=?"); //gets info about file with given code
$stmt->execute([$cutecode]);
$files = $stmt->fetchAll();
foreach($files as $file) {
    $file_name = $file['file_name'];
    $filesize = $file['file_size'];
    $md5hash = $file['md5hash'];
    $sha1hash = $file['sha1hash'];
    $description = $file['description'];
    $password = $file['file_password'];
    $current_folder = $file['current_folder'];
    $encrypted_file = $file['encrypted_file_name'];
    $file_password = $file['file_password'];
    $file_path = decryptFile($current_folder,$encrypted_file); //decrypts the file
    rename($current_folder. $encrypted_file,$current_folder.$file_name); //renames file to original file name
    $file_download = $current_folder. $file_name; // location of file to be downloaded
}
if(file_exists($file_download) && empty($file_password)) { //IF FILE IS WITHOUT PASSWORD PROTECTION
    header('Content-Description: File Transfer'); //file download headers
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename='.basename($file_download)); //file download headers
    header('Content-Transfer-Encoding: binary'); //file download headers
    header('Expires: 0'); //file download headers
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0'); //file download headers
    header('Pragma: public'); //file download headers
    header('Content-Length: ' . filesize($file_download)); //file download headers
    readfile($file_download);
    encryptFile($file_download); //every time someone hits download button file is encrypted again after being downloaded
    $newFolderName = generate_string($permitted_chars,128); //renaming folder so file becomes untraceable
    $newFolderName = UPLOAD_DIR . $newFolderName . DIRECTORY_SEPARATOR; //renaming folder so file becomes untraceable
    rename($current_folder . $file_name,$current_folder.$encrypted_file); //renaming file name to hashed version
    rename($current_folder, $newFolderName); //renaming folder so file becomes untraceable
    $sql1 = $conn->prepare("UPDATE file SET current_folder=? WHERE cutecode=?"); //log new folder into database
    $sql1->execute([$newFolderName,$cutecode]);
    exit;
}
elseif(file_exists($file_download) && isset($_GET['filepass'])) { //IF FILE HAS PASSWORD PROTECTION
    if($_GET['filepass'] == $file_password) { //checking if password is correct
        header('Content-Description: File Transfer'); //file download headers
        header('Content-Type: application/octet-stream'); //file download headers
        header('Content-Disposition: attachment; filename='.basename($file_download)); //file download headers
        header('Content-Transfer-Encoding: binary'); //file download headers
        header('Expires: 0'); //file download headers
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0'); //file download headers
        header('Pragma: public'); //file download headers
        header('Content-Length: ' . filesize($file_download)); //file download headers
        readfile($file_download);
        encryptFile($file_download); //encrypts the file again.
        $newFolderName = generate_string($permitted_chars,128);  //renaming folder so file becomes untraceable
        $newFolderName = UPLOAD_DIR . $newFolderName . DIRECTORY_SEPARATOR; //renaming folder so file becomes untraceable
        rename($current_folder . $file_name,$current_folder.$encrypted_file); //renaming file back to hashed version
        rename($current_folder, $newFolderName); //renaming folder so file becomes untraceable
        $sql1 = $conn->prepare("UPDATE file SET current_folder=? WHERE cutecode=?"); //log new folder into database
        $sql1->execute([$newFolderName,$cutecode]);
    exit;
    }
    else {
        encryptFile($file_download); //if password isn't correct we will encrypt file anyway
        $newFolderName = generate_string($permitted_chars,128);
        $newFolderName = UPLOAD_DIR . $newFolderName . DIRECTORY_SEPARATOR;
        rename($current_folder . $file_name,$current_folder.$encrypted_file);
        rename($current_folder, $newFolderName);
        $sql1 = $conn->prepare("UPDATE file SET current_folder=? WHERE cutecode=?");
        $sql1->execute([$newFolderName,$cutecode]);
        $_SESSION['mistake'] = "Password of file isn't correct."; //if password isn't correct back to the main page
        header("Location: ../index.php"); //if password isn't correct back to the main page
    }
}
else {
    header("Location ../index.php");
}
}
else {
    header("Location ../index.php");
}
?>