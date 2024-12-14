<?php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'web');
define('DB_PASSWORD', 'web');
define('DB_NAME', 'web');
 
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
 
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>