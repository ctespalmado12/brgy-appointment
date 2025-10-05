<?php

include('../class/Appointment.php');

$object = new Appointment;

if(isset($_POST["action"]))
{
	if($_POST["action"] == 'fetch')
	{
		$order_column = array('first_name', 'last_name', 'email_address', 'phone_no', 'email_verify');

		$output = array();

		$main_query = "
		SELECT * FROM client ";

		$search_query = '';

		if(isset($_POST["search"]["value"]))
		{
			$search_query .= 'WHERE first_name LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR last_name LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR email_address LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR phone_no LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR email_verify LIKE "%'.$_POST["search"]["value"].'%" ';
		}

		if(isset($_POST["order"]))
		{
			$order_query = 'ORDER BY '.$order_column[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' ';
		}
		else
		{
			$order_query = 'ORDER BY client_id DESC ';
		}

		$limit_query = '';

		if($_POST["length"] != -1)
		{
			$limit_query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
		}

		$object->query = $main_query . $search_query . $order_query;

		$object->execute();

		$filtered_rows = $object->row_count();

		$object->query .= $limit_query;

		$result = $object->get_result();

		$object->query = $main_query;

		$object->execute();

		$total_rows = $object->row_count();

		$data = array();

		foreach($result as $row)
		{
			$sub_array = array();
			$sub_array[] = $row["first_name"];
			$sub_array[] = $row["last_name"];
			$sub_array[] = $row["email_address"];
			$sub_array[] = $row["phone_no"];
			$status = '';
			if($row["email_verify"] == 'Yes')
			{
				$status = '<span class="badge badge-success">Yes</span>';
			}
			else
			{
				$status = '<span class="badge badge-danger">No</span>';
			}
			$sub_array[] = $status;
			$sub_array[] = '
			<div align="center">
			<button type="button" name="view_button" class="btn btn-info btn-circle btn-sm view_button" data-id="'.$row["client_id"].'"><i class="fas fa-eye"></i></button>
			<button type="button" name="edit_button" class="btn btn-warning btn-circle btn-sm edit_button" data-id="'.$row["client_id"].'"><i class="fas fa-edit"></i></button>
			<button type="button" name="delete_button" class="btn btn-danger btn-circle btn-sm delete_button" data-id="'.$row["client_id"].'"><i class="fas fa-times"></i></button>
			</div>
			';
			$data[] = $sub_array;
		}

		$output = array(
			"draw"    			=> 	intval($_POST["draw"]),
			"recordsTotal"  	=>  $total_rows,
			"recordsFiltered" 	=> 	$filtered_rows,
			"data"    			=> 	$data
		);
			
		echo json_encode($output);

	}

	/*if($_POST["action"] == 'Add')
	{
		$error = '';

		$success = '';

		$data = array(
			':email_address'	=>	$_POST["email_address"]
		);

		$object->query = "
		SELECT * FROM brgy_official 
		WHERE email_address = :email_address
		";

		$object->execute($data);

		if($object->row_count() > 0)
		{
			$error = '<div class="alert alert-danger">Email Address Already Exists</div>';
		}
		else
		{
			$profile_image = '';
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
			else
			{
				$character = $_POST["fullname"][0];
				$path = "../images/". time() . ".png";
				$image = imagecreate(200, 200);
				$red = rand(0, 255);
				$green = rand(0, 255);
				$blue = rand(0, 255);
			    imagecolorallocate($image, 230, 230, 230);  
			    $textcolor = imagecolorallocate($image, $red, $green, $blue);
			    imagettftext($image, 100, 0, 55, 150, $textcolor, '../font/arial.ttf', $character);
			    imagepng($image, $path);
			    imagedestroy($image);
			    $profile_image = $path;
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
					':position'				=>	$object->clean_input($_POST["position"]),
					':status'				=>	'Active',
					':added_on'				=>	$object->now
				);

				$object->query = "
				INSERT INTO brgy_official 
				(email_address, password, fullname, profile_image, phone_no, address, position, status, added_on) 
				VALUES (:email_address, :password, :fullname, :profile_image, :phone_no, :address, :position, :status, :added_on)
				";

				$object->execute($data);

				$success = '<div class="alert alert-success">Brgy Official Added</div>';
			}
		}

		$output = array(
			'error'		=>	$error,
			'success'	=>	$success
		);

		echo json_encode($output);

	}*/

	if($_POST["action"] == 'fetch_single')
	{
		$object->query = "
		SELECT * FROM client 
		WHERE client_id = '".$_POST["client_id"]."'
		";

		$result = $object->get_result();

		$data = array();

		foreach($result as $row)
		{
			$data['email_address'] = $row['email_address'];
			$data['password'] = $row['password'];
			$data['first_name'] = $row['first_name'];
			$data['last_name'] = $row['last_name'];
			$data['gender'] = $row['gender'];
			$data['address'] = $row['address'];
			$data['phone_no'] = $row['phone_no'];
			if($row['email_verify'] == 'Yes')
			{
				$data['email_verify'] = '<span class="badge badge-success">Yes</span>';
			}
			else
			{
				$data['email_verify'] = '<span class="badge badge-danger">No</span>';
			}
		}

		echo json_encode($data);
	}

	if($_POST["action"] == 'Edit')
	{
		$error = '';

		$success = '';

		$data = array(
			':email_address'	=>	$_POST["email_address"],
			':client_id'		=>	$_POST['hidden_id']
		);

		$object->query = "
		SELECT * FROM client 
		WHERE email_address = :email_address 
		AND client_id != :client_id
		";

		$object->execute($data);

		if($object->row_count() > 0)
		{
			$error = '<div class="alert alert-danger">Email Address Already Exists</div>';
		}
		else
		{

			if($error == '')
			{
				$data = array(
					':email_address'		=>	$object->clean_input($_POST["email_address"]),
					':password'				=>	$_POST["password"],
					':first_name'			=>	$object->clean_input($_POST["first_name"]),
					':last_name'			=>	$object->clean_input($_POST["last_name"]),
					':address'				=>	$object->clean_input($_POST["address"]),
					':phone_no'				=>	$object->clean_input($_POST["phone_no"]),
				);

				$object->query = "
				UPDATE client  
				SET email_address = :email_address, 
				password = :password, 
				first_name = :first_name,
				last_name = :last_name,
				address = :address, 
				phone_no = :phone_no 
				WHERE client_id = '".$_POST['hidden_id']."'
				";

				$object->execute($data);

				$success = '<div class="alert alert-success">Client Data Updated</div>';
			}			
		}

		$output = array(
			'error'		=>	$error,
			'success'	=>	$success
		);
		
		header('Location: client.php?success=1');

	}

	// if($_POST["action"] == 'change_status')
	// {
	// 	$data = array(
	// 		':status'		=>	$_POST['next_status']
	// 	);

	// 	$object->query = "
	// 	UPDATE brgy_official 
	// 	SET status = :status 
	// 	WHERE brgy_official_id = '".$_POST["id"]."'
	// 	";

	// 	$object->execute($data);

	// 	echo '<div class="alert alert-success">Class Status change to '.$_POST['next_status'].'</div>';
	// }

	if($_POST["action"] == 'delete')
	{
		$object->query = "
		DELETE FROM client 
		WHERE client_id = '".$_POST["id"]."'
		";

		$object->execute();

		echo '<div class="alert alert-success">Client Data Deleted</div>';
	}
}

?>