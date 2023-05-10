<?php
$server = 'sic5531.encs.concordia.ca:3306';
$username1 = 'sic55311';
$password = 'Tiger72';
$database = 'sic55311';

try{
    $conn = new PDO("mysql:host=$server; dname=$database;", $username1, $password); 
} catch (PDOExeception $e) {
    die('Connection Failed: ' . $e->getMeesage());
}

$con = mysqli_connect($server,$username1,$password,$database);
if ($con->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }