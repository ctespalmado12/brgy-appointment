<?php

//login.php

include('header.php');

?>

<div class="container">
	<div class="row justify-content-md-center">
		<div class="col col-md-6">
			<span id="message"></span>
			<div class="card">
				<div class="card-header">Register</div>
				<div class="card-body">
					<form method="post" id="client_register_form">
						<div class="form-group">
							<label>Email Address<span class="text-danger">*</span></label>
							<input type="text" name="email_address" id="email_address" class="form-control" required autofocus data-parsley-type="email" data-parsley-trigger="keyup" />
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
							<input type="hidden" name="action" value="client_register" />
							<input type="submit" name="client_register_button" id="client_register_button" class="btn btn-primary" value="Register" />
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

	$('#client_register_form').parsley();

	$('#client_register_form').on('submit', function(event){

		event.preventDefault();

		if($('#client_register_form').parsley().isValid())
		{
			$.ajax({
				url:"action.php",
				method:"POST",
				data:$(this).serialize(),
				dataType:'json',
				beforeSend:function(){
					$('#client_register_button').attr('disabled', 'disabled');
				},
				success:function(data)
				{
					$('#client_register_button').attr('disabled', false);
					$('#client_register_form')[0].reset();
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