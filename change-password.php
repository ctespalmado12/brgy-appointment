<?php

//login.php
require_once(__DIR__ . '/vendor/autoload.php');
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

include('header.php');
include('class/Appointment.php');

$object = new Appointment;

$jwt = $_GET["code"];

if(strlen($jwt) === 0)
{
    header("Location: login.php");
}
else
{
	$key = '71f1d003-f38b-4191-8d8b-32c9e9a7c8c4-b8cb9b1b-1903-4203-b358-c9675136749e';
    $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
	$decoded_array = (array) $decoded;

	$data = array(
		':email_address'	=>	$decoded_array['aud'],
	);

	$object->query = "
	SELECT * 
	FROM client 
	WHERE email_address = :email_address
	";

	$object->execute($data);

	if($object->row_count() > 0)
	{
		$result = $object->statement_result();

		foreach($result as $row) 
		{
			if(hash("sha256", $row["password"]) != $decoded_array["tok"])
			{
				header("Location: login.php");
			}
		}

	}
}

?>

<div class="container">
	<div class="row justify-content-md-center">
		<div class="col col-md-6">
			<span id="message"></span>
			<div class="card">
				<div class="card-header">Change Password</div>
				<div class="card-body">
					<form method="post" id="change_pass_form">
						<div class="form-group">
							<label>New Password<span class="text-danger">*</span></label>
							<input type="password" name="new-password" id="new-password" class="form-control" required  data-parsley-trigger="keyup" />
						</div>
						<div class="form-group text-center">
							<input type="hidden" name="action" value="change-password" />
							<input type="hidden" name="email_address" value="<?php echo $decoded_array["aud"]; ?>" />
							<input type="submit" name="change_pass_btn" id="change_pass_btn" class="btn btn-primary" value="Change Password" />
						</div>

						<div class="form-group text-center">
							<p><a href="login.php">Login</a></p>
						</div>
					</form>
				</div>
			</div>
			<br />
			<br />
		</div>
	</div>
</div>

<?php

include('footer.php');

?>

<script>

$(document).ready(function(){

	$('#change_pass_form').parsley();

	$('#change_pass_form').on('submit', function(event){

		event.preventDefault();

		if($('#change_pass_form').parsley().isValid())
		{
			$.ajax({
				url:"action.php",
				method:"POST",
				data:$(this).serialize(),
				dataType:'json',
				beforeSend:function(){
					$('#change_pass_btn').attr('disabled', 'disabled');
				},
				success:function(data)
				{
					$('#change_pass_btn').attr('disabled', false);
					$('#change_pass_form')[0].reset();
					if(data.error !== '')
					{
						$('#message').html(data.error);
					}
					if(data.success != '')
					{
						$('#message').html(data.success);
					}
				}
			});
		}

	});

});

</script>