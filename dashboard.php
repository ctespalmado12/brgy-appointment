<?php

//dashboard.php



include('class/Appointment.php');

$object = new Appointment;

include('header.php');

?>

<div class="container-fluid">
	<?php
	include('navbar.php');
	?>
	<br />
	<div class="card">
		<div class="card-header"><h4>Brgy Official Schedule List</h4></div>
			<div class="card-body">
				<div class="table-responsive">
		      		<table class="table table-striped table-bordered" id="appointment_list_table">
		      			<thead>
			      			<tr>
			      				<th>Brgy Official Name</th>
			      				<th>Position</th>
			      				<th>Appointment Date</th>
			      				<th>Appointment Day</th>
			      				<th>Available Time</th>
			      				<th>Action</th>
			      			</tr>
			      		</thead>
			      		<tbody></tbody>
			      	</table>
			    </div>
			</div>
		</div>
	</div>

</div>

<?php

include('footer.php');

?>

<div id="appointmentModal" class="modal fade">
  	<div class="modal-dialog">
    	<form method="post" id="appointment_form">
      		<div class="modal-content">
        		<div class="modal-header">
          			<h4 class="modal-title" id="modal_title">Make Appointment</h4>
          			<button type="button" class="close" data-dismiss="modal">&times;</button>
        		</div>
        		<div class="modal-body">
        			<span id="form_message"></span>
                    <div id="appointment_detail"></div>
                    <div class="form-group">
                    	<label><b>Reason for Appointment</b></label>
						<select required class="form-select custom-select" aria-label="Reason for appointment" id="appointment-reason">
							<option value="" selected hidden disabled>Select Purpose</option>
							<option value="Brgy Clearance">Brgy Clearance</option>
							<option value="Cedula">Cedula</option>
							<option value="Business Permit">Business Permit</option>
							<option value="Certificate of Indigency">Certificate of Indigency</option>
							<option value="Certificate of Identification">Certificate of Identification</option>
							<option value="Certificate of Residency">Certificate of Residency</option>
							<option value="File a Blotter">File a Blotter</option>
							<option value="Submission of Educational Assistance Requirements">Submission of Educational Assistance Requirements</option>
							<option value="Others">Others</option>
						</select>
                    	<textarea name="reason_for_appointment" id="reason_for_appointment" class="form-control my-2" rows="5"></textarea>
                    </div>
        		</div>
        		<div class="modal-footer">
          			<input type="hidden" name="hidden_brgy_official_id" id="hidden_brgy_official_id" />
          			<input type="hidden" name="hidden_brgy_official_schedule_id" id="hidden_brgy_official_schedule_id" />
          			<input type="hidden" name="action" id="action" value="book_appointment" />
          			<input type="submit" name="submit" id="submit_button" class="btn btn-success" value="Book" />
          			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        		</div>
      		</div>
    	</form>
  	</div>
</div>


<script>
	document.addEventListener("DOMContentLoaded", () => {
		renderOtherAppointmentReasons();
		document.getElementById('appointment-reason').addEventListener('change', handleAppointmentReasonChange);
	});

	function getSelectedAppointmentPurpose() {
		let selected_appointment_purpose = document.getElementById('appointment-reason').value;
		return selected_appointment_purpose;
	}

	function renderOtherAppointmentReasons() {
		const selected = getSelectedAppointmentPurpose();
		if(selected === "Others") {
			document.getElementById('reason_for_appointment').style.display = 'block';
			const textArea = document.getElementById('reason_for_appointment');
			textArea.required = true;
			textArea.placeholder = 'Please specify the reason for appointment.';
			textArea.value = '';
			textArea.focus();
		} else {
			document.getElementById('reason_for_appointment').style.display = 'none';
		}
	}

	function handleAppointmentReasonChange() {
		const selected = getSelectedAppointmentPurpose();
		let textArea = document.getElementById('reason_for_appointment');
		textArea.value = selected;
		renderOtherAppointmentReasons();
	}

</script>

<script>

$(document).ready(function(){

	var dataTable = $('#appointment_list_table').DataTable({
		"processing" : true,
		"serverSide" : true,
		"order" : [],
		"ajax" : {
			url:"action.php",
			type:"POST",
			data:{action:'fetch_schedule'}
		},
		"columnDefs":[
			{
                "targets":[5],				
				"orderable":false,
			},
		],
	});

	$(document).on('click', '.get_appointment', function(){

		var brgy_official_schedule_id = $(this).data('brgy_official_schedule_id');
		var brgy_official_id = $(this).data('brgy_official_id');

		$.ajax({
			url:"action.php",
			method:"POST",
			data:{action:'make_appointment', brgy_official_schedule_id:brgy_official_schedule_id},
			success:function(data)
			{
				$('#appointmentModal').modal('show');
				$('#hidden_brgy_official_id').val(brgy_official_id);
				$('#hidden_brgy_official_schedule_id').val(brgy_official_schedule_id);
				$('#appointment_detail').html(data);
			}
		});

	});

	$('#appointment_form').parsley();

	$('#appointment_form').on('submit', function(event){

		event.preventDefault();

		if($('#appointment_form').parsley().isValid())
		{
			console.log(this);

			$.ajax({
				url:"action.php",
				method:"POST",
				data:$(this).serialize(),
				dataType:"json",
				beforeSend:function(){
					$('#submit_button').attr('disabled', 'disabled');
					$('#submit_button').val('wait...');
				},
				success:function(data)
				{
					console.log(data);
					$('#submit_button').attr('disabled', false);
					$('#submit_button').val('Book');
					if(data.error != '')
					{
						console.log(data);
						$('#form_message').html(data.error);
					}
					else
					{	
						window.location.href="appointment.php";
					}
				}
			})

		}

	})

});

</script>