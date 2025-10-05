<?php

//forgot-password.php


include('header.php');

?>

<div class="container">
	<div class="row justify-content-md-center">
		<div class="col col-md-6">
			<span id="message"></span>
			<div class="card">
				<div class="card-header">Forgot Password</div>
				<div class="card-body">
					<form method="post" id="forgot_pass_form" action="action.php">
						<div class="form-group">
							<label>Email Address<span class="text-danger">*</span></label>
							<input type="text" name="email_address" id="email_address" class="form-control" required autofocus data-parsley-type="email" data-parsley-trigger="keyup" />
						</div>

						<div class="form-group text-center">
							<input type="hidden" name="action" value="forgot-password" />
							<input type="submit" name="forgot_pass_btn" id="forgot_pass_btn" class="btn btn-primary" value="Send Reset Link" />
						</div>

					</form>
				</div>
			</div>
			<br />
			<br />
		</div>
	</div>
</div>


<script>

$(document).ready(function(){

	$('#forgot_pass_form').parsley();

	$('#forgot_pass_form').on('submit', function(event){

		event.preventDefault();

		if($('#forgot_pass_form').parsley().isValid())
		{
			$.ajax({
				url:"action.php",
				method:"POST",
				data:$(this).serialize(),
				dataType:'json',
				beforeSend:function(){
					$('#forgot_pass_btn').attr('disabled', 'disabled');
				},
				success:function(data)
				{
					$('#forgot_pass_btn').attr('disabled', false);
					$('#forgot_pass_form')[0].reset();
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