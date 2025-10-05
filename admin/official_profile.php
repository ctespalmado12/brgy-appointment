<?php

include('../class/Appointment.php');

$object = new Appointment;

if(!$object->is_login())
{
    header("location:".$object->base_url."");
}

if($_SESSION['type'] != 'Official')
{
    header("location:".$object->base_url."");
}

$object->query = "
    SELECT * FROM brgy_official
    WHERE brgy_official_id = '".$_SESSION["admin_id"]."'
    ";

$result = $object->get_result();

include('header.php');

?>

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800">Profile</h1>

                    <!-- DataTales Example -->
                    
                    <form method="post" id="profile_form" enctype="multipart/form-data">
                        <div class="row"><div class="col-md-10"><span id="message"></span><div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <div class="row">
                                    <div class="col">
                                        <h6 class="m-0 font-weight-bold text-danger">Profile</h6>
                                    </div>
                                    <div clas="col" align="right">
                                        <input type="hidden" name="action" value="official_profile" />
                                        <input type="hidden" name="hidden_id" id="hidden_id" />
                                        <button type="submit" name="edit_button" id="edit_button" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i> Edit</button>
                                        &nbsp;&nbsp;
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <!--<div class="row">
                                    <div class="col-md-6">!-->
                                        <span id="form_message"></span>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>Email Address <span class="text-danger">*</span></label>
                                                    <input type="text" name="email_address" id="email_address" class="form-control" required data-parsley-type="email" data-parsley-trigger="keyup" />
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Password <span class="text-danger">*</span></label>
                                                    <input type="password" name="password" id="password" class="form-control" required  data-parsley-trigger="keyup" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>Name <span class="text-danger">*</span></label>
                                                    <input type="text" name="fullname" id="fullname" class="form-control" required data-parsley-trigger="keyup" />
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Phone No. <span class="text-danger">*</span></label>
                                                    <input type="text" name="phone_no" id="phone_no" class="form-control" required  data-parsley-trigger="keyup" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>Address </label>
                                                    <input type="text" name="address" id="address" class="form-control" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>Speciality <span class="text-danger">*</span></label>
                                                    <input type="text" name="position" id="position" class="form-control" required  data-parsley-trigger="keyup" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Image <span class="text-danger">*</span></label>
                                            <br />
                                            <input type="file" name="profile_image" id="profile_image" />
                                            <div id="uploaded_image"></div>
                                            <input type="hidden" name="hidden_profile_image" id="hidden_profile_image" />
                                        </div>
                                    <!--</div>
                                </div>!-->
                            </div>
                        </div></div></div>
                    </form>
                <?php
                include('footer.php');
                ?>

<script>
$(document).ready(function(){

    <?php
    foreach($result as $row)
    {
    ?>
    $('#hidden_id').val("<?php echo $row['brgy_official_id']; ?>");
    $('#email_address').val("<?php echo $row['email_address']; ?>");
    $('#password').val("<?php echo $row['password']; ?>");
    $('#fullname').val("<?php echo $row['fullname']; ?>");
    $('#phone_no').val("<?php echo $row['phone_no']; ?>");
    $('#address').val("<?php echo $row['address']; ?>");
    $('#position').val("<?php echo $row['position']; ?>");
    
    $('#uploaded_image').html('<img src="<?php echo $row["profile_image"]; ?>" class="img-thumbnail" width="100" /><input type="hidden" name="hidden_profile_image" value="<?php echo $row["profile_image"]; ?>" />');

    $('#hidden_profile_image').val("<?php echo $row['profile_image']; ?>");
    <?php
    }
    ?>

    $('#profile_image').change(function(){
        var extension = $('#profile_image').val().split('.').pop().toLowerCase();
        if(extension != '')
        {
            if(jQuery.inArray(extension, ['png','jpg']) == -1)
            {
                alert("Invalid Image File");
                $('#profile_image').val('');
                return false;
            }
        }
    });

    $('#profile_form').parsley();

	$('#profile_form').on('submit', function(event){
		event.preventDefault();
		if($('#profile_form').parsley().isValid())
		{		
			$.ajax({
				url:"profile_action.php",
				method:"POST",
				data:new FormData(this),
                dataType:'json',
                contentType:false,
                processData:false,
				beforeSend:function()
				{
					$('#edit_button').attr('disabled', 'disabled');
					$('#edit_button').html('wait...');
				},
				success:function(data)
				{
					$('#edit_button').attr('disabled', false);
                    $('#edit_button').html('<i class="fas fa-edit"></i> Edit');

                    $('#email_address').val(data.email_address);
                    $('#password').val(data.password);
                    $('#fullname').val(data.fullname);
                    $('#phone_no').val(data.phone_no);
                    $('#address').text(data.address);
                    $('#position').text(data.position);
                    if(data.profile_image != '')
                    {
                        $('#uploaded_image').html('<img src="'+data.profile_image+'" class="img-thumbnail" width="100" />');

                        $('#user_profile_image').attr('src', data.profile_image);
                    }

                    $('#hidden_profile_image').val(data.profile_image);
						
                    $('#message').html(data.success);

					setTimeout(function(){

				        $('#message').html('');

				    }, 5000);
				}
			})
		}
	});

});
</script>