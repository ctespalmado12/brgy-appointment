<?php

//index.php

include('class/Appointment.php');

$object = new Appointment;

if(isset($_SESSION['client_id']))
{
	header('location:dashboard.php');
}

$object->query = "
SELECT * FROM brgy_official_schedule 
INNER JOIN brgy_official 
ON brgy_official.brgy_official_id = brgy_official_schedule.brgy_official_id
WHERE brgy_official_schedule.schedule_date >= '".date('Y-m-d')."' 
AND brgy_official_schedule.schedule_status = 'Active' 
AND brgy_official.off_status = 'Active' 
ORDER BY brgy_official_schedule.schedule_date ASC
";

$result = $object->get_result();

include('header.php');

?>
		      	<div class="card">
		      		<form method="post" action="result.php">
			      		<div class="card-header text-primary"><h3><b>Schedule List</b></h3></div>
			      		<div class="card-body">
		      				<div class="table-responsive">
		      					<table class="table table-striped table-bordered">
		      						<tr>
		      							<th>Brgy Official Name</th>
		      							<th>Appointment Date</th>
		      							<th>Appointment Day</th>
		      							<th>Available Time</th>
		      							<th>Action</th>
		      						</tr>
		      						<?php
		      						foreach($result as $row)
		      						{
		      							echo '
		      							<tr>
		      								<td>'.$row["fullname"].'</td>
		      								<td>'.$row["schedule_date"].'</td>
		      								<td>'.$row["schedule_day"].'</td>
		      								<td>'.$row["schedule_start_time"].' - '.$row["schedule_end_time"].'</td>
		      								<td><button type="button" name="get_appointment" class="btn btn-dark btn-sm get_appointment" data-id="'.$row["brgy_official_schedule_id"].'">Get Appointment</button></td>
		      							</tr>
		      							';
		      						}
		      						?>
		      					</table>
		      				</div>
		      			</div>
		      		</form>
		      	</div>
		    

<?php

include('footer.php');

?>

<script>

$(document).ready(function(){
	$(document).on('click', '.get_appointment', function(){
		var action = 'check_login';
		var brgy_official_schedule_id = $(this).data('id');
		$.ajax({
			url:"action.php",
			method:"POST",
			data:{action:action, brgy_official_schedule_id:brgy_official_schedule_id},
			success:function(data)
			{
				window.location.href=data;
			}
		})
	});
});

</script>