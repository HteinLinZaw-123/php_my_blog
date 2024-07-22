<?php
    // PDO
try{
    $server_name = "localhost";
    $dbname = "my_blog";
    $dbuser = "root";
    $dbpassword = "";

    // Data Source Income
    $dsn = "mysql:host=$server_name;dbname=$dbname";
    $conn = new PDO($dsn,$dbuser,$dbpassword);

    $conn -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Connection Success"; 

}catch(PDOException $e){
    die("Connection Fail".$e->getMessage());
}

?>