<?php

//profile.php



include('class/Appointment.php');

$object = new Appointment;

$object->query = "
SELECT * FROM client 
WHERE client_id = '".$_SESSION["client_id"]."'
";

$result = $object->get_result();

include('header.php');

?>

<div class="container-fluid">
	<?php include('navbar.php'); ?>

	<div class="row justify-content-md-center">
		<div class="col col-md-6">
			<br />
			<?php
			if(isset($_GET['action']) && $_GET['action'] == 'edit')
			{
			?>
			<div class="card">
				<div class="card-header">
					<div class="row">
						<div class="col-md-6">
							Edit Profile Details
						</div>
						<div class="col-md-6 text-right">
							<a href="profile.php" class="btn btn-secondary btn-sm">View</a>
						</div>
					</div>
				</div>
				<div class="card-body">
					<form method="post" id="edit_profile_form">
						<div class="form-group">
							<label>Email Address<span class="text-danger">*</span></label>
							<input type="text" name="email_address" id="email_address" class="form-control" required autofocus data-parsley-type="email" data-parsley-trigger="keyup" readonly />
						</div>
						<div class="form-group">
							<label>Password<span class="text-danger">*</span></label>
							<input type="password" name="password" id="password" class="form-control" required  data-parsley-trigger="keyup" />
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>First Name<span class="text-danger">*</span></label>
									<input type="text" name="first_name" id="first_name" class="form-control" required  data-parsley-trigger="keyup" />
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Last Name<span class="text-danger">*</span></label>
									<input type="text" name="last_name" id="last_name" class="form-control" required  data-parsley-trigger="keyup" />
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Gender<span class="text-danger">*</span></label>
									<select name="gender" id="gender" class="form-control">
										<option value="Male">Male</option>
										<option value="Female">Female</option>
										<option value="Other">Other</option>
									</select>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Contact No.<span class="text-danger">*</span></label>
									<input type="text" name="phone_no" id="phone_no" class="form-control" required  data-parsley-trigger="keyup" />
								</div>
							</div>
						</div>
						<div class="form-group">
							<label>Complete Address<span class="text-danger">*</span></label>
							<textarea name="address" id="address" class="form-control" required data-parsley-trigger="keyup"></textarea>
						</div>
						<div class="form-group text-center">
							<input type="hidden" name="action" value="edit_profile" />
							<input type="submit" name="edit_profile_button" id="edit_profile_button" class="btn btn-primary" value="Edit" />
						</div>
					</form>
				</div>
			</div>

			<br />
			<br />
			

			<?php
			}
			else
			{

				if(isset($_SESSION['success_message']))
				{
					echo $_SESSION['success_message'];
					unset($_SESSION['success_message']);
				}
			?>

			<div class="card">
				<div class="card-header">
					<div class="row">
						<div class="col-md-6">
							Profile Details
						</div>
						<div class="col-md-6 text-right">
							<a href="profile.php?action=edit" class="btn btn-secondary btn-sm">Edit</a>
						</div>
					</div>
				</div>
				<div class="card-body">
					<table class="table table-striped">
						<?php
						foreach($result as $row)
						{
						?>
						<tr>
							<th class="text-right" width="40%">Full Name</th>
							<td><?php echo $row["first_name"] . ' ' . $row["last_name"]; ?></td>
						</tr>
						<tr>
							<th class="text-right" width="40%">Email Address</th>
							<td><?php echo $row["email_address"]; ?></td>
						</tr>
						<tr>
							<th class="text-right" width="40%">Password</th>
							<td><?php echo $row["password"]; ?></td>
						</tr>
						<tr>
							<th class="text-right" width="40%">Address</th>
							<td><?php echo $row["address"]; ?></td>
						</tr>
						<tr>
							<th class="text-right" width="40%">Contact No.</th>
							<td><?php echo $row["phone_no"]; ?></td>
						</tr>
						<tr>
							<th class="text-right" width="40%">Gender</th>
							<td><?php echo $row["gender"]; ?></td>
						</tr>
						<?php
						}
						?>	
					</table>					
				</div>
			</div>
			<br />
			<br />
			<?php
			}
			?>
		</div>
	</div>
</div>

<?php

include('footer.php');


?>

<script>

$(document).ready(function(){

<?php
	foreach($result as $row)
	{

?>
$('#email_address').val("<?php echo $row['email_address']; ?>");
$('#password').val("<?php echo $row['password']; ?>");
$('#first_name').val("<?php echo $row['first_name']; ?>");
$('#last_name').val("<?php echo $row['last_name']; ?>");
$('#gender').val("<?php echo $row['gender']; ?>");
$('#phone_no').val("<?php echo $row['phone_no']; ?>");
$('#address').val("<?php echo $row['address']; ?>");

<?php

	}

?>

	$('#edit_profile_form').parsley();

	$('#edit_profile_form').on('submit', function(event){

		event.preventDefault();

		if($('#edit_profile_form').parsley().isValid())
		{
			$.ajax({
				url:"action.php",
				method:"POST",
				data:$(this).serialize(),
				beforeSend:function()
				{
					$('#edit_profile_button').attr('disabled', 'disabled');
					$('#edit_profile_button').val('wait...');
				},
				success:function(data)
				{
					window.location.href = "profile.php";
				}
			})
		}

	});

});

</script>