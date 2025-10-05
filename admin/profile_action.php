<?php

include('../class/Appointment.php');

$object = new Appointment;

if($_POST["action"] == 'official_profile')
{
	sleep(2);

	$error = '';

	$success = '';

	$profile_image = '';

	$data = array(
		':email_address'	=>	$_POST["email_address"],
		':brgy_official_id'			=>	$_POST['hidden_id']
	);

	$object->query = "
	SELECT * FROM brgy_official 
	WHERE email_address = :email_address 
	AND brgy_official_id != :brgy_official_id
	";

	$object->execute($data);

	if($object->row_count() > 0)
	{
		$error = '<div class="alert alert-danger">Email Address Already Exists</div>';
	}
	else
	{
		$profile_image = $_POST["hidden_profile_image"];

		if($_FILES['profile_image']['name'] != '')
		{
			$allowed_file_format = array("jpg", "png");

	    	$file_extension = pathinfo($_FILES["profile_image"]["name"], PATHINFO_EXTENSION);

	    	if(!in_array($file_extension, $allowed_file_format))
		    {
		        $error = "<div class='alert alert-danger'>Upload valiid file. jpg, png</div>";
		    }
		    else if (($_FILES["profile_image"]["size"] > 2000000))
		    {
		       $error = "<div class='alert alert-danger'>File size exceeds 2MB</div>";
		    }
		    else
		    {
		    	$new_name = rand() . '.' . $file_extension;

				$destination = '../images/' . $new_name;

				move_uploaded_file($_FILES['profile_image']['tmp_name'], $destination);

				$profile_image = $destination;
		    }
		}

		if($error == '')
		{
			$data = array(
				':email_address'			=>	$object->clean_input($_POST["email_address"]),
				':password'				=>	$_POST["password"],
				':fullname'					=>	$object->clean_input($_POST["fullname"]),
				':profile_image'			=>	$profile_image,
				':phone_no'				=>	$object->clean_input($_POST["phone_no"]),
				':address'				=>	$object->clean_input($_POST["address"]),
				':position'				=>	$object->clean_input($_POST["position"])
			);

			$object->query = "
			UPDATE brgy_official  
			SET email_address = :email_address, 
			password = :password, 
			fullname = :fullname, 
			profile_image = :profile_image, 
			phone_no = :phone_no, 
			address = :address,
			position = :position 
			WHERE brgy_official_id = '".$_POST['hidden_id']."'
			";
			$object->execute($data);

			$success = '<div class="alert alert-success">Brgy Official Data Updated</div>';
		}			
	}

	$output = array(
		'error'					=>	$error,
		'success'				=>	$success,
		'email_address'	=>	$_POST["email_address"],
		'password'		=>	$_POST["password"],
		'fullname'			=>	$_POST["fullname"],
		'profile_image'	=>	$profile_image,
		'phone_no'		=>	$_POST["phone_no"],
		'address'		=>	$_POST["address"],
		'position'		=>	$_POST["position"],
	);

	echo json_encode($output);
}

if($_POST["action"] == 'admin_profile')
{
	sleep(2);

	$error = '';

	$success = '';

	$brgy_logo = $_POST['hidden_brgy_logo'];

	if($_FILES['brgy_logo']['name'] != '')
	{
		$allowed_file_format = array("jpg", "png");

	    $file_extension = pathinfo($_FILES["brgy_logo"]["name"], PATHINFO_EXTENSION);

	    if(!in_array($file_extension, $allowed_file_format))
		{
		    $error = "<div class='alert alert-danger'>Upload valiid file. jpg, png</div>";
		}
		else if (($_FILES["brgy_logo"]["size"] > 2000000))
		{
		   $error = "<div class='alert alert-danger'>File size exceeds 2MB</div>";
	    }
		else
		{
		    $new_name = rand() . '.' . $file_extension;

			$destination = '../images/' . $new_name;

			move_uploaded_file($_FILES['brgy_logo']['tmp_name'], $destination);

			$brgy_logo = $destination;
		}
	}

	if($error == '')
	{
		$data = array(
			':admin_email_address'			=>	$object->clean_input($_POST["admin_email_address"]),
			':admin_password'				=>	$_POST["admin_password"],
			':admin_name'					=>	$object->clean_input($_POST["admin_name"]),
			':brgy_name'				=>	$object->clean_input($_POST["brgy_name"]),
			':brgy_address'				=>	$object->clean_input($_POST["brgy_address"]),
			':brgy_contact_no'			=>	$object->clean_input($_POST["brgy_contact_no"]),
			':brgy_logo'				=>	$brgy_logo
		);

		$object->query = "
		UPDATE admin 
		SET email_address = :admin_email_address, 
		password = :admin_password, 
		username = :admin_name, 
		brgy_name = :brgy_name, 
		brgy_address = :brgy_address, 
		brgy_contact_no = :brgy_contact_no, 
		brgy_logo = :brgy_logo 
		WHERE admin_id = '".$_SESSION["admin_id"]."'
		";
		$object->execute($data);

		$success = '<div class="alert alert-success">Admin Data Updated</div>';

		$output = array(
			'error'					=>	$error,
			'success'				=>	$success,
			'admin_email_address'	=>	$_POST["admin_email_address"],
			'admin_password'		=>	$_POST["admin_password"],
			'admin_name'			=>	$_POST["admin_name"], 
			'brgy_name'			=>	$_POST["brgy_name"],
			'brgy_address'		=>	$_POST["brgy_address"],
			'brgy_contact_no'	=>	$_POST["brgy_contact_no"],
			'brgy_logo'			=>	$brgy_logo
		);

		echo json_encode($output);
	}
	else
	{
		$output = array(
			'error'					=>	$error,
			'success'				=>	$success
		);
		echo json_encode($output);
	}
}

?>