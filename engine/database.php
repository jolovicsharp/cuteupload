<?php 
$sName = "localhost"; //change this to your settings - MYSQL server
$uName = "root"; //change this to your settings - MYSQL user
$pass = ""; //change this to your settings - MYSQL pass
$db_name = "cuteupload"; //change this to your settings - DATABASE NAME

#creating database connection
try {
    $conn = new PDO("mysql:host=$sName;dbname=$db_name", 
                    $uName, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
  echo "Failed : ". $e->getMessage();
}