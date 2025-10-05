<?php

//action.php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

include('class/Appointment.php');
require_once(__DIR__ . '/vendor/autoload.php');

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

//read the value from .env
$apiKey = $_ENV['SENDINBLUE_API_KEY'];
// var_dump($_ENV['SENDINBLUE_API_KEY']);
$credentials  = SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKey('api-key', $apiKey);
$apiInstance = new SendinBlue\Client\Api\TransactionalEmailsApi(new GuzzleHttp\Client(), $credentials);

$object = new Appointment;

if (isset($_POST["action"])) {
	if ($_POST["action"] == 'check_login') {
		if (isset($_SESSION['client_id'])) {
			echo 'dashboard.php';
		} else {
			echo 'login.php';
		}
	}

	if ($_POST['action'] == 'client_register') {
		$error = '';

		$success = '';

		$data = array(
			':email_address'	=>	$_POST["email_address"]
		);

		$object->query = "
		SELECT * FROM client 
		WHERE email_address = :email_address
		";

		$object->execute($data);

		if ($object->row_count() > 0) {
			$error = '<div class="alert alert-danger">Email Address Already Exists</div>';
		} else {
			$verification_code = md5(uniqid());
			$data = array(
				':email_address'		=>	$object->clean_input($_POST["email_address"]),
				':password'				=>	$_POST["password"],
				':first_name'			=>	$object->clean_input($_POST["first_name"]),
				':last_name'			=>	$object->clean_input($_POST["last_name"]),
				':gender'				=>	$object->clean_input($_POST["gender"]),
				':address'				=>	$object->clean_input($_POST["address"]),
				':phone_no'				=>	$object->clean_input($_POST["phone_no"]),
				':added_on'				=>	$object->now,
				':verification_code'	=>	$verification_code,
				':email_verify'			=>	'No'
			);

			$object->query = "
			INSERT INTO client 
			(email_address, password, first_name, last_name, gender, address, phone_no, added_on, verification_code, email_verify) 
			VALUES (:email_address, :password, :first_name, :last_name, :gender, :address, :phone_no, :added_on, :verification_code, :email_verify)
			";

			$object->execute($data);

			$sendSmtpEmail = new \SendinBlue\Client\Model\SendSmtpEmail([
				'subject' => 'Brgy Rosario Appointment Email Verification',
				'sender' => ['name' => 'Admin', 'email' => 'admin@brgyrosario.com'],
				'replyTo' => ['name' => 'Admin', 'email' => 'admin@brgyrosario.com'],
				'to' => [['name' => $_POST["first_name"] . $_POST["last_name"], 'email' => $_POST["email_address"]]],
				'htmlContent' => '<html><body><p>To verify your email address, please click on this <a href="' . $object->base_url . 'verify.php?code=' . $verification_code . '"><b>link</b></a>.</p>
				<p>Sincerely,</p>
				<p>Brgy Rosario</p></body></html>'
			]);

			try {
				$result = $apiInstance->sendTransacEmail($sendSmtpEmail);
				if ($result) {
					$success = '<div class="alert alert-success">Registration Successful. Please verify your email address.</div>';
				} else {
					$error = '<div class="alert alert-danger">Registration Failed. Please try again.</div>';
				}
				$success = '<div class="alert alert-success">Please Check Your Email for email Verification</div>';
			} catch (Exception $e) {
				$error = '<div class="alert alert-danger">Registration Failed. Please try again.</div>';
			}
		}

		$output = array(
			'error'		=>	$error,
			'success'	=>	$success
		);
		echo json_encode($output);
	}

	if ($_POST['action'] == 'client_login') {
		$error = '';

		$data = array(
			':email_address'	=>	$_POST["email_address"]
		);

		$object->query = "
		SELECT * FROM client 
		WHERE email_address = :email_address
		";

		$object->execute($data);

		if ($object->row_count() > 0) {

			$result = $object->statement_result();

			foreach ($result as $row) {
				if ($row["email_verify"] == 'Yes') {
					if ($row["password"] == $_POST["password"]) {
						$_SESSION['client_id'] = $row['client_id'];
						$_SESSION['client_name'] = $row['first_name'] . ' ' . $row['last_name'];
					} else {
						$error = '<div class="alert alert-danger">Wrong Password</div>';
					}
				} else {
					$error = '<div class="alert alert-danger">Please first verify your email address</div>';
				}
			}
		} else {
			$error = '<div class="alert alert-danger">Wrong Email Address</div>';
		}

		$output = array(
			'error'		=>	$error
		);

		echo json_encode($output);
	}

	if ($_POST['action'] == 'fetch_schedule') {
		$output = array();

		$order_column = array('brgy_official.fullname', 'brgy_official.position', 'brgy_official_schedule.schedule_date', 'brgy_official_schedule.schedule_day', 'brgy_official_schedule.schedule_start_time');

		$main_query = "
		SELECT * FROM brgy_official_schedule 
		INNER JOIN brgy_official 
		ON brgy_official.brgy_official_id = brgy_official_schedule.brgy_official_id 
		";

		$search_query = '
		WHERE brgy_official_schedule.schedule_date >= "' . date('Y-m-d') . '" 
		AND brgy_official_schedule.schedule_status = "Active" 
		AND brgy_official.off_status = "Active" 
		';

		if (isset($_POST["search"]["value"])) {
			$search_query .= 'AND ( brgy_official.fullname LIKE "%' . $_POST["search"]["value"] . '%" ';
			$search_query .= 'OR brgy_official.position LIKE "%' . $_POST["search"]["value"] . '%" ';
			$search_query .= 'OR brgy_official_schedule.schedule_date LIKE "%' . $_POST["search"]["value"] . '%" ';
			$search_query .= 'OR brgy_official_schedule.schedule_day LIKE "%' . $_POST["search"]["value"] . '%" ';
			$search_query .= 'OR brgy_official_schedule.schedule_start_time LIKE "%' . $_POST["search"]["value"] . '%") ';
		}

		if (isset($_POST["order"])) {
			$order_query = 'ORDER BY ' . $order_column[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'] . ' ';
		} else {
			$order_query = 'ORDER BY brgy_official_schedule.schedule_date ASC ';
		}

		$limit_query = '';

		if ($_POST["length"] != -1) {
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

		foreach ($result as $row) {
			$sub_array = array();

			$sub_array[] = $row["fullname"];

			$sub_array[] = $row["position"];

			$sub_array[] = $row["schedule_date"];

			$sub_array[] = $row["schedule_day"];

			$sub_array[] = date("g:i A", strtotime($row["schedule_start_time"]));

			$sub_array[] = '
			<div align="center">
			<button type="button" name="get_appointment" class="btn btn-primary btn-sm get_appointment" data-brgy_official_id="' . $row["brgy_official_id"] . '" data-brgy_official_schedule_id="' . $row["brgy_official_schedule_id"] . '">Get Appointment</button>
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

	if ($_POST['action'] == 'edit_profile') {
		$data = array(
			':password'			=>	$_POST["password"],
			':first_name'		=>	$_POST["first_name"],
			':last_name'		=>	$_POST["last_name"],
			':gender'			=>	$_POST["gender"],
			':address'			=>	$_POST["address"],
			':phone_no'			=>	$_POST["phone_no"]
		);

		$object->query = "
		UPDATE client  
		SET password = :password, 
		first_name = :first_name, 
		last_name = :last_name, 
		gender = :gender, 
		address = :address, 
		phone_no = :phone_no
		WHERE client_id = '" . $_SESSION['client_id'] . "'
		";

		$object->execute($data);

		$_SESSION['success_message'] = '<div class="alert alert-success">Profile Data Updated</div>';

		echo 'done';
	}

	if ($_POST['action'] == 'make_appointment') {
		$object->query = "
		SELECT * FROM client 
		WHERE client_id = '" . $_SESSION["client_id"] . "'
		";

		$client_data = $object->get_result();

		$object->query = "
		SELECT * FROM brgy_official_schedule 
		INNER JOIN brgy_official 
		ON brgy_official.brgy_official_id = brgy_official_schedule.brgy_official_id 
		WHERE brgy_official_schedule.brgy_official_schedule_id = '" . $_POST["brgy_official_schedule_id"] . "'
		";

		$brgy_official_schedule_data = $object->get_result();

		$html = '
		<h4 class="text-center">Client Details</h4>
		<table class="table">
		';

		foreach ($client_data as $client_row) {
			$html .= '
			<tr>
				<th width="40%" class="text-right">Client Name</th>
				<td>' . $client_row["first_name"] . ' ' . $client_row["last_name"] . '</td>
			</tr>
			<tr>
				<th width="40%" class="text-right">Contact No.</th>
				<td>' . $client_row["phone_no"] . '</td>
			</tr>
			<tr>
				<th width="40%" class="text-right">Address</th>
				<td>' . $client_row["address"] . '</td>
			</tr>
			';
		}

		$html .= '
		</table>
		<hr />
		<h4 class="text-center">Appointment Details</h4>
		<table class="table">
		';
		foreach ($brgy_official_schedule_data as $brgy_official_schedule_row) {
			$html .= '
			<tr>
				<th width="40%" class="text-right">Brgy Official Name</th>
				<td>' . $brgy_official_schedule_row["fullname"] . '</td>
			</tr>
			<tr>
				<th width="40%" class="text-right">Appointment Date</th>
				<td>' . $brgy_official_schedule_row["schedule_date"] . '</td>
			</tr>
			<tr>
				<th width="40%" class="text-right">Appointment Day</th>
				<td>' . $brgy_official_schedule_row["schedule_day"] . '</td>
			</tr>
			<tr>
				<th width="40%" class="text-right">Available Time</th>
				<td>' . date("g:i A", strtotime($brgy_official_schedule_row["schedule_start_time"])) . ' - ' . date("g:i A", strtotime($brgy_official_schedule_row["schedule_end_time"])) . '</td>
			</tr>
			';
		}

		$html .= '
		</table>';
		echo $html;
	}

	if ($_POST['action'] == 'book_appointment') {
		$error = '';
		$data = array(
			':client_id'			=>	$_SESSION['client_id'],
			':brgy_official_schedule_id'	=>	$_POST['hidden_brgy_official_schedule_id']
		);

		$object->query = "
		SELECT * FROM appointment 
		WHERE client_id = :client_id 
		AND brgy_official_schedule_id = :brgy_official_schedule_id
		";

		$object->execute($data);

		if ($object->row_count() > 0) {
			$error = '<div class="alert alert-danger">You have already applied for appointment for this day, try for other day.</div>';
		} else {
			$object->query = "
			SELECT * FROM brgy_official_schedule 
			WHERE brgy_official_schedule_id = '" . $_POST['hidden_brgy_official_schedule_id'] . "'
			";

			$schedule_data = $object->get_result();

			$object->query = "
			SELECT COUNT(appointment_id) AS total FROM appointment 
			WHERE brgy_official_schedule_id = '" . $_POST['hidden_brgy_official_schedule_id'] . "' 
			";

			$appointment_data = $object->get_result();

			$total_brgy_official_available_minute = 0;
			$average_consulting_time = 0;
			$total_appointment = 0;

			foreach ($schedule_data as $schedule_row) {
				$end_time = strtotime($schedule_row["schedule_end_time"] . ':00');

				$start_time = strtotime($schedule_row["schedule_start_time"] . ':00');

				$total_brgy_official_available_minute = ($end_time - $start_time) / 60;

				$average_consulting_time = $schedule_row["average_consulting_time"];
			}

			foreach ($appointment_data as $appointment_row) {
				$total_appointment = $appointment_row["total"];
			}

			$total_appointment_minute_use = $total_appointment * $average_consulting_time;

			$appointment_time = date("H:i", strtotime('+' . $total_appointment_minute_use . ' minutes', $start_time));

			$status = '';

			$appointment_number = $object->Generate_appointment_no();

			if (strtotime($end_time) > strtotime($appointment_time . ':00')) {
				$status = 'Booked';
			} else {
				$status = 'Waiting';
			}

			$data = array(
				':brgy_official_id'			=>	$_POST['hidden_brgy_official_id'],
				':client_id'				=>	$_SESSION['client_id'],
				':brgy_official_schedule_id' =>	$_POST['hidden_brgy_official_schedule_id'],
				':appointment_number'		=>	$appointment_number,
				':reason_for_appointment'	=>	$_POST['reason_for_appointment'],
				':appointment_time'			=>	$appointment_time,
				':app_status'				=>	'Booked'
			);

			$object->query = "
			INSERT INTO appointment 
			(brgy_official_id, client_id, brgy_official_schedule_id, appointment_number, reason_for_appointment, appointment_time, app_status) 
			VALUES (:brgy_official_id, :client_id, :brgy_official_schedule_id, :appointment_number, :reason_for_appointment, :appointment_time, :app_status)
			";

			$object->execute($data);

			$_SESSION['appointment_message'] = '<div class="alert alert-success">Your Appointment has been <b>' . $status . '</b> with Appointment No. <b>' . $appointment_number . '</b></div>';
		}
		echo json_encode(['error' => $error]);
	}

	if ($_POST['action'] == 'fetch_appointment') {
		$output = array();

		$order_column = array('appointment.appointment_number', 'brgy_official.fullname', 'brgy_official_schedule.schedule_date', 'appointment.appointment_time', 'brgy_official_schedule.schedule_day', 'appointment.app_status');

		$main_query = "
		SELECT * FROM appointment  
		INNER JOIN brgy_official 
		ON brgy_official.brgy_official_id = appointment.brgy_official_id 
		INNER JOIN brgy_official_schedule 
		ON brgy_official_schedule.brgy_official_schedule_id = appointment.brgy_official_schedule_id 
		
		";

		$search_query = '
		WHERE appointment.client_id = "' . $_SESSION["client_id"] . '" 
		';

		if (isset($_POST["search"]["value"])) {
			$search_query .= 'AND ( appointment.appointment_number LIKE "%' . $_POST["search"]["value"] . '%" ';
			$search_query .= 'OR brgy_official.fullname LIKE "%' . $_POST["search"]["value"] . '%" ';
			$search_query .= 'OR brgy_official_schedule.schedule_date LIKE "%' . $_POST["search"]["value"] . '%" ';
			$search_query .= 'OR appointment.appointment_time LIKE "%' . $_POST["search"]["value"] . '%" ';
			$search_query .= 'OR brgy_official_schedule.schedule_day LIKE "%' . $_POST["search"]["value"] . '%" ';
			$search_query .= 'OR appointment.app_status LIKE "%' . $_POST["search"]["value"] . '%") ';
		}

		if (isset($_POST["order"])) {
			$order_query = 'ORDER BY ' . $order_column[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'] . ' ';
		} else {
			$order_query = 'ORDER BY appointment.appointment_id ASC ';
		}

		$limit_query = '';

		if ($_POST["length"] != -1) {
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

		foreach ($result as $row) {
			$sub_array = array();

			$sub_array[] = $row["appointment_number"];

			$sub_array[] = $row["fullname"];

			$sub_array[] = $row["schedule_date"];

			$sub_array[] = date("g:i A", strtotime($row["appointment_time"]));

			$sub_array[] = $row["schedule_day"];

			$status = '';

			if ($row["app_status"] == 'Booked') {
				$status = '<span class="badge badge-warning">' . $row["app_status"] . '</span>';
			}

			if ($row["app_status"] == 'In Process') {
				$status = '<span class="badge badge-primary">' . $row["app_status"] . '</span>';
			}

			if ($row["app_status"] == 'Completed') {
				$status = '<span class="badge badge-success">' . $row["app_status"] . '</span>';
			}

			if ($row["app_status"] == 'Cancel') {
				$status = '<span class="badge badge-danger">' . $row["app_status"] . '</span>';
			}

			$sub_array[] = $status;

			//$sub_array[] = '<a href="download.php?id='.$row["appointment_id"].'" class="btn btn-danger btn-sm" target="_blank"><i class="fas fa-file-pdf"></i> PDF</a>';

			$sub_array[] = $row["reason_for_appointment"];

			$sub_array[] = $row["brgy_official_comment"];

			$sub_array[] = '<button type="button" name="cancel_appointment" class="btn btn-danger btn-sm cancel_appointment" data-id="' . $row["appointment_id"] . '"><i class="fas fa-times"></i></button>';

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

	if ($_POST['action'] == 'cancel_appointment') {
		$data = array(
			':app_status'			=>	'Cancel',
			':appointment_id'	=>	$_POST['appointment_id']
		);
		$object->query = "
		UPDATE appointment 
		SET app_status = :app_status 
		WHERE appointment_id = :appointment_id
		";
		$object->execute($data);
		echo '<div class="alert alert-success">Your appointment has been canceled!</div>';
	}

	if ($_POST['action'] == 'forgot-password') {
		$error = "";

		$data = array(
			':email_address'	=>	$_POST["email_address"]
		);

		$object->query = "
		SELECT * FROM client 
		WHERE email_address = :email_address
		";

		$object->execute($data);

		if ($object->row_count() < 1) {
			$error = '<div class="alert alert-danger">Email Address Not Found!</div>';
		} else if ($object->row_count() > 0) {

			$result = $object->statement_result();

			foreach ($result as $row) {
				if ($row["email_verify"] == 'Yes') {
					$currentUnixTime = (new DateTime())->getTimestamp();
					$key = '71f1d003-f38b-4191-8d8b-32c9e9a7c8c4-b8cb9b1b-1903-4203-b358-c9675136749e';
					$payload = [
						'iss' => 'brgy-rosario-admin',
						'aud' => $_POST["email_address"],
						'tok' => hash('sha256', $row["password"]),
						'iat' => $currentUnixTime,
						'exp' => $currentUnixTime + 1800
					];

					$jwt = JWT::encode($payload, $key, 'HS256');

					$sendSmtpEmail = new \SendinBlue\Client\Model\SendSmtpEmail([
						'subject' => 'Brgy Rosario Appointment Forgot Password Link',
						'sender' => ['name' => 'Admin', 'email' => 'admin@brgyrosario.com'],
						'replyTo' => ['name' => 'Admin', 'email' => 'admin@brgyrosario.com'],
						'to' => [['name' => $row["first_name"] . $row["last_name"], 'email' => $row["email_address"]]],
						'htmlContent' => '<html><body><p>To change your password, please click on this <a href="' . $object->base_url . 'change-password.php?code=' . $jwt . '"><b>link</b></a>.</p>
						<p>Sincerely,</p>
						<p>Brgy Rosario</p></body></html>'
					]);

					try {
						$result = $apiInstance->sendTransacEmail($sendSmtpEmail);
						if ($result) {
							$success = '<div class="alert alert-success">Please check your email for the password reset link.</div>';
						} else {
							$error = '<div class="alert alert-danger">Sending Email Failed. Please try again.</div>';
						}
						$success = '<div class="alert alert-success">Please Check Your Email for the password reset link.</div>';
					} catch (Exception $e) {
						$error = '<div class="alert alert-danger">Sending Email Failed. Please try again.</div>';
					}
				} else {
					$error = '<div class="alert alert-danger">Please first verify your email address</div>';
				}
			}
		}

		$output = array(
			'error'		=>	$error,
			'success'	=>	$success
		);

		header("Location: forgot-password.php");
	}

	if ($_POST['action'] == 'change-password') {
		$error = "";
		$success = "";

		$data = array(
			':email_address'	=>	$_POST["email_address"],
			':password'			=>	$_POST["new-password"]
		);

		$object->query = "
		UPDATE client  
		SET password = :password 
		WHERE email_address = :email_address
		";

		$object->execute($data);

		$_SESSION['success_message'] = '<div class="alert alert-success">Password Updated!</div>';
		$success = '<div class="alert alert-success">Password Updated!</div>';

		$output = array(
			'error'		=>	$error,
			'success'	=>	$success
		);

		echo json_encode($output);
	}
}
