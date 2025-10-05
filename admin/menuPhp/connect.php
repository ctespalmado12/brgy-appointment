

<?php

$con = new mysqli('localhost:3306', 'root', 'root!', 'brgy_appointment');

//check if connected to database
if (!$con) {
    die(mysqli_error($con));
}


?>