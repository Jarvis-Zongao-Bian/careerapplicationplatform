<?php

/**
 * @var string $host
 * @var string $username
 * @var string $password
 * @var string $dbname
 * @var string $dsn
 * @var array $options
 */

// Configuration of database connection

$host = "sic5531.encs.concordia.ca";
$username = "sic55311";
$password = "Tiger72";
$dbname = "sic55311";
$dsn = "mysql:host=$host;dbname=$dbname";
$options = array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
);