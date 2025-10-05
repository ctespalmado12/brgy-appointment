<?php

//verify.php

include('header.php');

include('class/Appointment.php');

$object = new Appointment;

if(isset($_GET["code"]))
{
	$object->query = "
	UPDATE client 
	SET email_verify = 'Yes' 
	WHERE verification_code = '".$_GET["code"]."'
	";

	$object->execute();

	$_SESSION['success_message'] = '<div class="alert alert-success">Your email has been verified. Now you can log in to the system.</div>';

	header('location:login.php');
}

include('footer.php');

?>