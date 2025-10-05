

<?php
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$DB_HOST = $_ENV[$DB_HOST];
$DB_USER = $_ENV[$DB_USER];
$DB_PASS = $_ENV[$DB_PASS];
$DB_NAME = $_ENV[$DB_NAME];

$con = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

//check if connected to database
if (!$con) {
    die(mysqli_error($con));
}


?>