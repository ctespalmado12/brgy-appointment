<?php

//download.php

include('class/Appointment.php');

$object = new Appointment;

require_once('class/pdf.php');

if(isset($_GET["id"]))
{
	$html = '<table border="0" cellpadding="5" cellspacing="5" width="100%">';

	$object->query = "
	SELECT brgy_name, brgy_address, brgy_contact_no, brgy_logo 
	FROM admin
	";

	$brgy_data = $object->get_result();

	foreach($brgy_data as $brgy_row)
	{
		$html .= '<tr><td align="center">';
		if($brgy_row['brgy_logo'] != '')
		{
			$html .= '<img src="'.substr($brgy_row['brgy_logo'], 3).'" /><br />';
		}
		$html .= '<h2 align="center">'.$brgy_row['brgy_name'].'</h2>
		<p align="center">'.$brgy_row['brgy_address'].'</p>
		<p align="center"><b>Contact No. - </b>'.$brgy_row['brgy_contact_no'].'</p></td></tr>
		';
	}

	$html .= "
	<tr><td><hr /></td></tr>
	<tr><td>
	";

	$object->query = "
	SELECT * FROM appointment 
	WHERE appointment_id = '".$_GET["id"]."'
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
		
		$html .= '
		<h4 align="center">Client Details</h4>
		<table border="0" cellpadding="5" cellspacing="5" width="100%">';

		foreach($client_data as $client_row)
		{
			$html .= '<tr><th width="50%" align="right">Client Name</th><td>'.$client_row["first_name"].' '.$client_row["last_name"].'</td></tr>
			<tr><th width="50%" align="right">Contact No.</th><td>'.$client_row["phone_no"].'</td></tr>
			<tr><th width="50%" align="right">Address</th><td>'.$client_row["address"].'</td></tr>';
		}

		$html .= '</table><br /><hr />
		<h4 align="center">Appointment Details</h4>
		<table border="0" cellpadding="5" cellspacing="5" width="100%">
			<tr>
				<th width="50%" align="right">Appointment No.</th>
				<td>'.$appointment_row["appointment_number"].'</td>
			</tr>
		';
		foreach($brgy_official_schedule_data as $brgy_official_schedule_row)
		{
			$html .= '
			<tr>
				<th width="50%" align="right">Brgy Official Name</th>
				<td>'.$brgy_official_schedule_row["fullname"].'</td>
			</tr>
			<tr>
				<th width="50%" align="right">Appointment Date</th>
				<td>'.$brgy_official_schedule_row["schedule_date"].'</td>
			</tr>
			<tr>
				<th width="50%" align="right">Appointment Day</th>
				<td>'.$brgy_official_schedule_row["schedule_day"].'</td>
			</tr>
				
			';
		}

		$html .= '
			<tr>
				<th width="50%" align="right">Appointment Time</th>
				<td>'.$appointment_row["appointment_time"].'</td>
			</tr>
			<tr>
				<th width="50%" align="right">Reason for Appointment</th>
				<td>'.$appointment_row["reason_for_appointment"].'</td>
			</tr>
			<tr>
				<th width="50%" align="right">Client come into office</th>
				<td>'.$appointment_row["client_come_into_office"].'</td>
			</tr>
			<tr>
				<th width="50%" align="right">Brgy Official Comment</th>
				<td>'.$appointment_row["brgy_official_comment"].'</td>
			</tr>
		</table>
			';
	}

	$html .= '
			</td>
		</tr>
	</table>';

	echo $html;

	$pdf = new Pdf();

	$pdf->loadHtml($html, 'UTF-8');
	$pdf->render();
	ob_end_clean();
	//$pdf->stream($_GET["id"] . '.pdf', array( 'Attachment'=>1 ));
	$pdf->stream($_GET["id"] . '.pdf', array( 'Attachment'=>false ));
	exit(0);

}

?>