<?php

include('../class/Appointment.php');

$object = new Appointment;

if(isset($_POST["action"]))
{
	if($_POST["action"] == 'fetch')
	{
		$output = array();

		if($_SESSION['type'] == 'Admin')
		{
			$order_column = array('brgy_official.fullname', 'brgy_official_schedule.schedule_date', 'brgy_official_schedule.schedule_day', 'brgy_official_schedule.schedule_start_time', 'brgy_official_schedule.schedule_end_time', 'brgy_official_schedule.average_consulting_time');
			$main_query = "
			SELECT * FROM brgy_official_schedule 
			INNER JOIN brgy_official 
			ON brgy_official.brgy_official_id = brgy_official_schedule.brgy_official_id 
			";

			$search_query = '';

			if(isset($_POST["search"]["value"]))
			{
				$search_query .= 'WHERE brgy_official.fullname LIKE "%'.$_POST["search"]["value"].'%" ';
				$search_query .= 'OR brgy_official_schedule.schedule_date LIKE "%'.$_POST["search"]["value"].'%" ';
				$search_query .= 'OR brgy_official_schedule.schedule_day LIKE "%'.$_POST["search"]["value"].'%" ';
				$search_query .= 'OR brgy_official_schedule.schedule_start_time LIKE "%'.$_POST["search"]["value"].'%" ';
				$search_query .= 'OR brgy_official_schedule.schedule_end_time LIKE "%'.$_POST["search"]["value"].'%" ';
				$search_query .= 'OR brgy_official_schedule.average_consulting_time LIKE "%'.$_POST["search"]["value"].'%" ';
			}
		}
		else
		{
			$order_column = array('schedule_date', 'schedule_day', 'schedule_start_time', 'schedule_end_time', 'average_consulting_time');
			$main_query = "
			SELECT * FROM brgy_official_schedule 
			";

			$search_query = '
			WHERE brgy_official_id = "'.$_SESSION["admin_id"].'" AND 
			';

			if(isset($_POST["search"]["value"]))
			{
				$search_query .= '(schedule_date LIKE "%'.$_POST["search"]["value"].'%" ';
				$search_query .= 'OR schedule_day LIKE "%'.$_POST["search"]["value"].'%" ';
				$search_query .= 'OR schedule_start_time LIKE "%'.$_POST["search"]["value"].'%" ';
				$search_query .= 'OR schedule_end_time LIKE "%'.$_POST["search"]["value"].'%" ';
				$search_query .= 'OR average_consulting_time LIKE "%'.$_POST["search"]["value"].'%") ';
			}
		}

		if(isset($_POST["order"]))
		{
			$order_query = 'ORDER BY '.$order_column[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' ';
		}
		else
		{
			$order_query = 'ORDER BY brgy_official_schedule.brgy_official_schedule_id DESC ';
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
			if($_SESSION['type'] == 'Admin')
			{
				$sub_array[] = html_entity_decode($row["fullname"]);
			}
			$sub_array[] = $row["schedule_date"];

			$sub_array[] = $row["schedule_day"];

			$sub_array[] = date("g:i A", strtotime($row["schedule_start_time"]));

			$sub_array[] = date("g:i A", strtotime($row["schedule_end_time"]));

			$sub_array[] = $row["average_consulting_time"] . ' Minute';

			$status = '';
			if($row["schedule_status"] == 'Active')
			{
				$status = '<button type="button" name="status_button" class="btn btn-primary btn-sm status_button" data-id="'.$row["brgy_official_schedule_id"].'" data-status="'.$row["schedule_status"].'">Active</button>';
			}
			else
			{
				$status = '<button type="button" name="status_button" class="btn btn-danger btn-sm status_button" data-id="'.$row["brgy_official_schedule_id"].'" data-status="'.$row["schedule_status"].'">Inactive</button>';
			}

			$sub_array[] = $status;

			$sub_array[] = '
			<div align="center">
			<button type="button" name="edit_button" class="btn btn-warning btn-circle btn-sm edit_button" data-id="'.$row["brgy_official_schedule_id"].'"><i class="fas fa-edit"></i></button>
			&nbsp;
			<button type="button" name="delete_button" class="btn btn-danger btn-circle btn-sm delete_button" data-id="'.$row["brgy_official_schedule_id"].'"><i class="fas fa-times"></i></button>
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

		$brgy_official_id = '';

		if($_SESSION['type'] == 'Admin')
		{
			$brgy_official_id = $_POST["brgy_official_id"];
		}

		if($_SESSION['type'] == 'Official')
		{
			$brgy_official_id = $_SESSION['admin_id'];
		}

		$data = array(
			':brgy_official_id'			=>	$brgy_official_id,
			':schedule_date'			=>	$_POST["schedule_date"],
			':schedule_day'				=>	date('l', strtotime($_POST["schedule_date"])),
			':schedule_start_time'		=>	$_POST["schedule_start_time"],
			':schedule_end_time'		=>	$_POST["schedule_end_time"],
			':average_consulting_time'	=>	$_POST["average_consulting_time"]
		);

		$object->query = "
		INSERT INTO brgy_official_schedule 
		(brgy_official_id, schedule_date, schedule_day, schedule_start_time, schedule_end_time, average_consulting_time) 
		VALUES (:brgy_official_id, :schedule_date, :schedule_day, :schedule_start_time, :schedule_end_time, :average_consulting_time)
		";

		$object->execute($data);

		$success = '<div class="alert alert-success">Brgy Official Schedule Added Successfully</div>';

		$output = array(
			'error'		=>	$error,
			'success'	=>	$success
		);

		echo json_encode($output);

	}

	if($_POST["action"] == 'fetch_single')
	{
		$object->query = "
		SELECT * FROM brgy_official_schedule 
		WHERE brgy_official_schedule_id = '".$_POST["brgy_official_schedule_id"]."'
		";

		$result = $object->get_result();

		$data = array();

		foreach($result as $row)
		{
			$data['brgy_official_id'] = $row['brgy_official_id'];
			$data['schedule_date'] = $row['schedule_date'];
			$data['schedule_start_time'] = $row['schedule_start_time'];
			$data['schedule_end_time'] = $row['schedule_end_time'];
			$data['average_consulting_time'] = $row['average_consulting_time'];
		}

		echo json_encode($data);
	}

	if($_POST["action"] == 'Edit')
	{
		$error = '';

		$success = '';

		$brgy_official_id = '';

		if($_SESSION['type'] == 'Admin')
		{
			$brgy_official_id = $_POST["brgy_official_id"];
		}

		if($_SESSION['type'] == 'Official')
		{
			$brgy_official_id = $_SESSION['admin_id'];
		}

		$data = array(
			':brgy_official_id'			=>	$brgy_official_id,
			':schedule_date'			=>	$_POST["schedule_date"],
			':schedule_start_time'		=>	$_POST["schedule_start_time"],
			':schedule_end_time'		=>	$_POST["schedule_end_time"],
			':average_consulting_time'	=>	$_POST["average_consulting_time"]
		);

		$object->query = "
		UPDATE brgy_official_schedule 
		SET brgy_official_id = :brgy_official_id, 
		schedule_date = :schedule_date, 
		schedule_start_time = :schedule_start_time, 
		schedule_end_time = :schedule_end_time, 
		average_consulting_time = :average_consulting_time    
		WHERE brgy_official_schedule_id = '".$_POST['hidden_id']."'
		";

		$object->execute($data);

		$success = '<div class="alert alert-success">Brgy Official Schedule Data Updated Successfully Updated</div>';

		$output = array(
			'error'		=>	$error,
			'success'	=>	$success
		);

		echo json_encode($output);

	}

	if($_POST["action"] == 'change_status')
	{
		$data = array(
			':schedule_status'		=>	$_POST['next_status']
		);

		$object->query = "
		UPDATE brgy_official_schedule 
		SET schedule_status = :schedule_status 
		WHERE brgy_official_schedule_id = '".$_POST["id"]."'
		";

		$object->execute($data);

		echo '<div class="alert alert-success">Brgy Official Schedule Status change to '.$_POST['next_status'].'</div>';
	}

	if($_POST["action"] == 'delete')
	{
		$object->query = "
		DELETE FROM brgy_official_schedule 
		WHERE brgy_official_schedule_id = '".$_POST["id"]."'
		";

		$object->execute();

		echo '<div class="alert alert-success">Brgy Official Schedule has been Deleted</div>';
	}
}

?>