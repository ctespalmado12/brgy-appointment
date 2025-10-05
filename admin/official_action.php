<?php

include('../class/Appointment.php');

$object = new Appointment;

if(isset($_POST["action"]))
{
	if($_POST["action"] == 'fetch')
	{
		$order_column = array('fullname', 'status');

		$output = array();

		$main_query = "
		SELECT * FROM brgy_official ";

		$search_query = '';

		if(isset($_POST["search"]["value"]))
		{
			$search_query .= 'WHERE email_address LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR fullname LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR phone_no LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR position LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR off_status LIKE "%'.$_POST["search"]["value"].'%" ';
		}

		if(isset($_POST["order"]))
		{
			$order_query = 'ORDER BY '.$order_column[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' ';
		}
		else
		{
			$order_query = 'ORDER BY brgy_official_id DESC ';
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
			$sub_array[] = '<img src="'.$row["profile_image"].'" class="img-thumbnail" width="75" />';
			$sub_array[] = $row["email_address"];
			$sub_array[] = $row["password"];
			$sub_array[] = $row["fullname"];
			$sub_array[] = $row["phone_no"];
			$sub_array[] = $row["position"];
			$status = '';
			if($row["off_status"] == 'Active')
			{
				$status = '<button type="button" name="status_button" class="btn btn-primary btn-sm status_button" data-id="'.$row["brgy_official_id"].'" data-status="'.$row["off_status"].'">Active</button>';
			}
			else
			{
				$status = '<button type="button" name="status_button" class="btn btn-danger btn-sm status_button" data-id="'.$row["brgy_official_id"].'" data-status="'.$row["off_status"].'">Inactive</button>';
			}
			$sub_array[] = $status;
			$sub_array[] = '
			<div align="center">
			<button type="button" name="view_button" class="btn btn-info btn-circle btn-sm view_button" data-id="'.$row["brgy_official_id"].'"><i class="fas fa-eye"></i></button>
			<button type="button" name="edit_button" class="btn btn-warning btn-circle btn-sm edit_button" data-id="'.$row["brgy_official_id"].'"><i class="fas fa-edit"></i></button>
			<button type="button" name="delete_button" class="btn btn-danger btn-circle btn-sm delete_button" data-id="'.$row["brgy_official_id"].'"><i class="fas fa-times"></i></button>
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

	if($_POST["action"] == 'Add')
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
					':email_address'		=>	$object->clean_input($_POST["email_address"]),
					':password'				=>	$_POST["password"],
					':fullname'				=>	$object->clean_input($_POST["fullname"]),
					':profile_image'		=>	$profile_image,
					':phone_no'				=>	$object->clean_input($_POST["phone_no"]),
					':address'				=>	$object->clean_input($_POST["address"]),
					':position'				=>	$object->clean_input($_POST["position"]),
					':off_status'			=>	'Active',
					':added_on'				=>	$object->now
				);

				$object->query = "
				INSERT INTO brgy_official 
				(email_address, password, fullname, profile_image, phone_no, address, position, off_status, added_on) 
				VALUES (:email_address, :password, :fullname, :profile_image, :phone_no, :address, :position, :off_status, :added_on)
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

	}

	if($_POST["action"] == 'fetch_single')
	{
		$object->query = "
		SELECT * FROM brgy_official 
		WHERE brgy_official_id = '".$_POST["brgy_official_id"]."'
		";

		$result = $object->get_result();

		$data = array();

		foreach($result as $row)
		{
			$data['email_address'] = $row['email_address'];
			$data['password'] = $row['password'];
			$data['fullname'] = $row['fullname'];
			$data['profile_image'] = $row['profile_image'];
			$data['phone_no'] = $row['phone_no'];
			$data['address'] = $row['address'];
			$data['position'] = $row['position'];
		}

		echo json_encode($data);
	}

	if($_POST["action"] == 'Edit')
	{
		$error = '';

		$success = '';

		$data = array(
			':email_address'		=>	$_POST["email_address"],
			':brgy_official_id'		=>	$_POST['hidden_id']
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
					':email_address'		=>	$object->clean_input($_POST["email_address"]),
					':password'				=>	$_POST["password"],
					':fullname'				=>	$object->clean_input($_POST["fullname"]),
					':profile_image'		=>	$profile_image,
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
			'error'		=>	$error,
			'success'	=>	$success
		);

		echo json_encode($output);

	}

	if($_POST["action"] == 'change_status')
	{
		$data = array(
			':off_status'		=>	$_POST['next_status']
		);

		$object->query = "
		UPDATE brgy_official 
		SET off_status = :off_status 
		WHERE brgy_official_id = '".$_POST["id"]."'
		";

		$object->execute($data);

		echo '<div class="alert alert-success">Status change to '.$_POST['next_status'].'</div>';
	}

	if($_POST["action"] == 'delete')
	{
		$object->query = "
		DELETE FROM brgy_official 
		WHERE brgy_official_id = '".$_POST["id"]."'
		";

		$object->execute();

		echo '<div class="alert alert-success">Brgy Official Data Deleted</div>';
	}
}

?>