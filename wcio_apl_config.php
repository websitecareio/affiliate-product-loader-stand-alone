<?php
// Database connection
$dbHost = "DBHOST";
$dbName = "DATABASE";
$dbUsername = "USERNAME";
$dbPassword = "PASSWORD";

try {
      $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=utf8", $dbUsername, $dbPassword);
} catch (PDOException $e) {
      die("Error connecting to database");
}
?>
