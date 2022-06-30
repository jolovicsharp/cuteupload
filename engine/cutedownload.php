<?php
session_start();
include_once 'database.php';
if(isset($_SESSION['token1']) && isset($_POST['t1'])) { //security
    $token1 = $_SESSION['token1']; //security
    $t1 = $_POST['t1'];//security
    if($token1 !== $t1) { //security
    header("Location: ../index.php"); //security
    }
}
if(isset($_POST['cutecode'])) { //checking if user tryped code for download
    $cutecode = htmlspecialchars(stripslashes(trim($_POST['cutecode'])));
    $stmt = $conn->prepare("SELECT * FROM file WHERE cutecode=?"); //checks if code is in base.
    $stmt->execute([$cutecode]);
    $files = $stmt->fetchAll();
    if(empty($files)) {
        $_SESSION['mistake'] = "Cutecode is invalid, no file found."; //if array is empty - no file with that code found in base
        header("Location: ../index.php"); //return error to main page.
    }
    else {
    foreach($files as $file) {
        $file_name = $file['file_name']; //pulling file info from database
        $filesize = $file['file_size']; //pulling file info from database
        $md5hash = $file['md5hash']; //pulling file info from database
        $sha1hash = $file['sha1hash']; //pulling file info from database
        $description = $file['description']; //pulling file info from database
        $password = $file['file_password']; //pulling file info from database
        $current_folder = $file['current_folder']; //pulling file info from database
        $encrypted_file = $file['encrypted_file_name']; //pulling file info from database
        $file_password = $file['file_password']; //pulling file info from database
    }
    }
}
else {
    header("Location: ../index.php"); //if user didn't type cutecode and clicked "check file" return him to main page
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<style>
        .cent1 {
            text-align: center;
        }
        .cent {
            text-align: start;
        }
        #info {
        background-color: #ee0979;
        color: white;
        /*text-align: center;*/
        text-align: start;
        margin: 35px;
        }
        .titl {
            text-align: center;
        }
        .titls {
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
    <title>Document</title>
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Catamaran:100,200,300,400,500,600,700,800,900">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:100,100i,300,300i,400,400i,700,700i,900,900i">
</head>
<body>
<div id="container" class="">
        <div id="info" class=" shadow-lg p-3 mb-5 rounded ml-3 d-inline-flex flex-column justify-content-center align-items-center">
            <div id="" class="test p-3">
            <div class="titl">    
            <span class="titl"><b>CODE : <span id="code"><?php echo $cutecode; ?></b></span></span><br>
            <small class="titls">Share this code further</small><br><br></div>
            <hr>
            <div class="cent">
            <span>🍬 File name : <?php echo $file_name;?></span><br>
            <span>🍬 File size : <?php echo $filesize;?></span><br>
            <span>🍬 MD5 Hash : <?php echo $md5hash;?></span><br>
            <span>🍬 SHA1 Hash : <?php echo $sha1hash;?></span><br>
            <?php if(!empty($description)) { ?><span class="description"><?php echo "🍬 File description : " . $description;}?></span>
        </div>
        <hr>
        <?php
        $_SESSION['cutecode'] = $cutecode;
        ?>
        <?php if(empty($password)) { ?>
        <div class="titl">    
            <span class="titl"><b>Download file - this file isn't password protected</b></span></span><br>
        </div>
        <div class="cent1">
            <br>
                <a href="downloadgenerator.php" class="btn btn-primary border-2 border-white rounded">HERE <i class="bi bi-cloud-arrow-down-fill"></i></a>
        </div>
        <?php } else { ?>
            <div class="titl">    
            <span class="titl"><b>Download file - type password to download</b></span></span><br>
        </div>
        <div class="cent1">
            <?php $_SESSION['file_password'] = $file_password;?>
            <br><form method="get" action="downloadgenerator.php">
                <input class="form-control w-30 mb-3" type="text" name="filepass">
                <input type="submit" class="btn btn-primary border-2 border-white rounded" value="Download here"></input>
                <?php //if(isset($_SESSION['mistake'])) { echo "<p>" . $_SESSION['mistake'] . "</p>"; unset($_SESSION['mistake']);}?>
            </form>
            </div>
            <?php }?>
        </div>
        </div>
</div>
</body>
</html>