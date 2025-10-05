<?php

//appointment_action.php

include('../class/Appointment.php');

$object = new Appointment;

if(isset($_POST["action"]))
{
	if($_POST["action"] == 'fetch')
	{
		$output = array();

		if($_SESSION['type'] == 'Admin')
		{
			$order_column = array('appointment.appointment_number', 'client.first_name', 'brgy_official.fullname', 'brgy_official_schedule.schedule_date', 'appointment.appointment_time', 'brgy_official_schedule.schedule_day', 'appointment.app_status');
			$main_query = "
			SELECT * FROM appointment  
			INNER JOIN brgy_official 
			ON brgy_official.brgy_official_id = appointment.brgy_official_id 
			INNER JOIN brgy_official_schedule 
			ON brgy_official_schedule.brgy_official_schedule_id = appointment.brgy_official_schedule_id 
			INNER JOIN client 
			ON client.client_id = appointment.client_id 
			";

			$search_query = '';

			if($_POST["is_date_search"] == "yes")
			{
			 	$search_query .= 'WHERE brgy_official_schedule.schedule_date BETWEEN "'.$_POST["start_date"].'" AND "'.$_POST["end_date"].'" AND (';
			}
			else
			{
				$search_query .= 'WHERE ';
			}

			if(isset($_POST["search"]["value"]))
			{
				$search_query .= 'appointment.appointment_number LIKE "%'.$_POST["search"]["value"].'%" ';
				$search_query .= 'OR client.first_name LIKE "%'.$_POST["search"]["value"].'%" ';
				$search_query .= 'OR client.last_name LIKE "%'.$_POST["search"]["value"].'%" ';
				$search_query .= 'OR brgy_official.fullname LIKE "%'.$_POST["search"]["value"].'%" ';
				$search_query .= 'OR brgy_official_schedule.schedule_date LIKE "%'.$_POST["search"]["value"].'%" ';
				$search_query .= 'OR appointment.appointment_time LIKE "%'.$_POST["search"]["value"].'%" ';
				$search_query .= 'OR brgy_official_schedule.schedule_day LIKE "%'.$_POST["search"]["value"].'%" ';
				$search_query .= 'OR appointment.app_status LIKE "%'.$_POST["search"]["value"].'%" ';
			}
			if($_POST["is_date_search"] == "yes")
			{
				$search_query .= ') ';
			}
			else
			{
				$search_query .= '';
			}
		}
		else
		{
			$order_column = array('appointment.appointment_number', 'client.first_name', 'brgy_official_schedule.schedule_date', 'appointment.appointment_time', 'brgy_official_schedule.schedule_day', 'appointment.app_status');

			$main_query = "
			SELECT * FROM appointment 
			INNER JOIN brgy_official_schedule 
			ON brgy_official_schedule.brgy_official_schedule_id = appointment.brgy_official_schedule_id 
			INNER JOIN client 
			ON client.client_id = appointment.client_id 
			";

			$search_query = '
			WHERE appointment.brgy_official_id = "'.$_SESSION["admin_id"].'" 
			';

			if($_POST["is_date_search"] == "yes")
			{
			 	$search_query .= 'AND brgy_official_schedule.schedule_date BETWEEN "'.$_POST["start_date"].'" AND "'.$_POST["end_date"].'" ';
			}
			else
			{
				$search_query .= '';
			}

			if(isset($_POST["search"]["value"]))
			{
				$search_query .= 'AND (appointment.appointment_number LIKE "%'.$_POST["search"]["value"].'%" ';
				$search_query .= 'OR client.first_name LIKE "%'.$_POST["search"]["value"].'%" ';
				$search_query .= 'OR client.last_name LIKE "%'.$_POST["search"]["value"].'%" ';
				$search_query .= 'OR brgy_official_schedule.schedule_date LIKE "%'.$_POST["search"]["value"].'%" ';
				$search_query .= 'OR appointment.appointment_time LIKE "%'.$_POST["search"]["value"].'%" ';
				$search_query .= 'OR brgy_official_schedule.schedule_day LIKE "%'.$_POST["search"]["value"].'%" ';
				$search_query .= 'OR appointment.app_status LIKE "%'.$_POST["search"]["value"].'%") ';
			}
		}

		if(isset($_POST["order"]))
		{
			$order_query = 'ORDER BY '.$order_column[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' ';
		}
		else
		{
			$order_query = 'ORDER BY appointment.appointment_id DESC ';
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

		$object->query = $main_query . $search_query;

		$object->execute();

		$total_rows = $object->row_count();

		$data = array();

		foreach($result as $row)
		{
			$sub_array = array();

			$sub_array[] = $row["appointment_number"];

			$sub_array[] = $row["first_name"] . ' ' . $row["last_name"];

			if($_SESSION['type'] == 'Admin')
			{
				$sub_array[] = $row["fullname"];
			}
			$sub_array[] = $row["schedule_date"];

			$sub_array[] = date("g:i A", strtotime($row["appointment_time"]));

			$sub_array[] = $row["schedule_day"];

			$status = '';

			if($row["app_status"] == 'Booked')
			{
				$status = '<span class="badge badge-warning">' . $row["app_status"] . '</span>';
			}

			if($row["app_status"] == 'In Process') //in process
			{
				$status = '<span class="badge badge-primary">' . $row["app_status"] . '</span>';
			}

			if($row["app_status"] == 'Completed') //completed
			{
				$status = '<span class="badge badge-success">' . $row["app_status"] . '</span>';
			}

			if($row["app_status"] == 'Cancel')
			{
				$status = '<span class="badge badge-danger">' . $row["app_status"] . '</span>';
			}

			$sub_array[] = $status;

			$sub_array[] = '
			<div align="center">
			<button type="button" name="view_button" class="btn btn-info btn-circle btn-sm view_button" data-id="'.$row["appointment_id"].'"><i class="fas fa-eye"></i></button>
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

	if($_POST["action"] == 'fetch_single')
	{
		$object->query = "
		SELECT * FROM appointment 
		WHERE appointment_id = '".$_POST["appointment_id"]."'
		";

		$appointment_data = $object->get_result();

		foreach($appointment_data as $appointment_row)
		{

			$object->query = "
			SELECT * FROM client 
			WHERE client_id = '".$appointment_row["client_id"]."'
			";

			$client_data = $object->get_result();

			$object->query = "
			SELECT * FROM brgy_official_schedule 
			INNER JOIN brgy_official 
			ON brgy_official.brgy_official_id = brgy_official_schedule.brgy_official_id 
			WHERE brgy_official_schedule.brgy_official_schedule_id = '".$appointment_row["brgy_official_schedule_id"]."'
			";

			$brgy_official_schedule_data = $object->get_result();

			$html = '
			<h4 class="text-center">Client Details</h4>
			<table class="table">
			';

			foreach($client_data as $client_row)
			{
				$html .= '
				<tr>
					<th width="40%" class="text-right">Client Name</th>
					<td>'.$client_row["first_name"].' '.$client_row["last_name"].'</td>
				</tr>
				<tr>
					<th width="40%" class="text-right">Contact No.</th>
					<td>'.$client_row["phone_no"].'</td>
				</tr>
				<tr>
					<th width="40%" class="text-right">Address</th>
					<td>'.$client_row["address"].'</td>
				</tr>
				';
			}

			$html .= '
			</table>
			<hr />
			<h4 class="text-center">Appointment Details</h4>
			<table class="table">
				<tr>
					<th width="40%" class="text-right">Appointment No.</th>
					<td>'.$appointment_row["appointment_number"].'</td>
				</tr>
			';
			foreach($brgy_official_schedule_data as $brgy_official_schedule_row)
			{
				$html .= '
				<tr>
					<th width="40%" class="text-right">Brgy Official Name</th>
					<td>'.$brgy_official_schedule_row["fullname"].'</td>
				</tr>
				<tr>
					<th width="40%" class="text-right">Appointment Date</th>
					<td>'.$brgy_official_schedule_row["schedule_date"].'</td>
				</tr>
				<tr>
					<th width="40%" class="text-right">Appointment Day</th>
					<td>'.$brgy_official_schedule_row["schedule_day"].'</td>
				</tr>
				
				';
			}

			$html .= '
				<tr>
					<th width="40%" class="text-right">Appointment Time</th>
					<td>'.$appointment_row["appointment_time"].'</td>
				</tr>
				<tr>
					<th width="40%" class="text-right">Reason for Appointment</th>
					<td>'.$appointment_row["reason_for_appointment"].'</td>
				</tr>
			';

			if($appointment_row["app_status"] != 'Cancel')
			{
				if($_SESSION['type'] == 'Admin')
				{
					if($appointment_row['client_come_into_office'] == 'Yes')
					{
						if($appointment_row["app_status"] == 'Completed')
						{
							$html .= '
								<tr>
									<th width="40%" class="text-right">Client come into office</th>
									<td>Yes</td>
								</tr>
								<tr>
									<th width="40%" class="text-right">Brgy Official Comment</th>
									<td>'.$appointment_row["brgy_official_comment"].'</td>
								</tr>
							';
						}
						else
						{
							$html .= '
								<tr>
									<th width="40%" class="text-right">Client come into office</th>
									<td>
										<select name="client_come_into_office" id="client_come_into_office" class="form-control" required>
											<option value="">Select</option>
											<option value="Yes" selected>Yes</option>
										</select>
									</td>
								</tr
							';
						}
					}
					else
					{
						$html .= '
							<tr>
								<th width="40%" class="text-right">Client come into office</th>
								<td>
									<select name="client_come_into_office" id="client_come_into_office" class="form-control" required>
										<option value="">Select</option>
										<option value="Yes">Yes</option>
									</select>
								</td>
							</tr
						';
					}
				}

				if($_SESSION['type'] == 'Official')
				{
					if($appointment_row["client_come_into_office"] == 'Yes')
					{
						if($appointment_row["app_status"] == 'Completed')
						{
							$html .= '
								<tr>
									<th width="40%" class="text-right">Brgy Official Comment</th>
									<td>
										<textarea name="brgy_official_comment" id="brgy_official_comment" class="form-control" rows="8" required>'.$appointment_row["brgy_official_comment"].'</textarea>
									</td>
								</tr
							';
						}
						else
						{
							$html .= '
								<tr>
									<th width="40%" class="text-right">Brgy Official Comment</th>
									<td>
										<textarea name="brgy_official_comment" id="brgy_official_comment" class="form-control" rows="8" required></textarea>
									</td>
								</tr
							';
						}
					}
				}
			
			}

			$html .= '
			</table>
			';
		}

		echo $html;
	}

	if($_POST['action'] == 'change_appointment_status')
	{
		if($_SESSION['type'] == 'Admin')
		{
			$data = array(
				':app_status'					=>	'In Process',
				':client_come_into_office'		=>	'Yes',
				':appointment_id'				=>	$_POST['hidden_appointment_id']
			);

			$object->query = "
			UPDATE appointment 
			SET app_status = :app_status, 
			client_come_into_office = :client_come_into_office 
			WHERE appointment_id = :appointment_id
			";

			$object->execute($data);

			echo '<div class="alert alert-success">Appointment Status change to In Process</div>';
		}

		if($_SESSION['type'] == 'Official')
		{
			if(isset($_POST['brgy_official_comment']))
			{
				$data = array(
					':app_status'							=>	'Completed',
					':brgy_official_comment'			=>	$_POST['brgy_official_comment'],
					':appointment_id'					=>	$_POST['hidden_appointment_id']
				);

				$object->query = "
				UPDATE appointment 
				SET app_status = :app_status, 
				brgy_official_comment = :brgy_official_comment 
				WHERE appointment_id = :appointment_id
				";

				$object->execute($data);

				echo '<div class="alert alert-success">Appointment Completed</div>';
			}
		}
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