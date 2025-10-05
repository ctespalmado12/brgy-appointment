<?php

include('../class/Appointment.php');

$object = new Appointment;

if(!$object->is_login())
{
    header("location:".$object->base_url."");
}

if($_SESSION['type'] != 'Admin')
{
    header("location:".$object->base_url."");
}


$object->query = "
SELECT * FROM admin
WHERE admin_id = '".$_SESSION["admin_id"]."'
";

$result = $object->get_result();

include('header.php');

?>

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800">Profile</h1>

                    <!-- DataTales Example -->
                    
                    <form method="post" id="profile_form" enctype="multipart/form-data">
                        <div class="row"><div class="col-md-8"><span id="message"></span><div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <div class="row">
                                    <div class="col">
                                        <h6 class="m-0 font-weight-bold text-primary">Profile</h6>
                                    </div>
                                    <div clas="col" align="right">
                                        <input type="hidden" name="action" value="admin_profile" />
                                        <button type="submit" name="edit_button" id="edit_button" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i> Update</button>
                                        &nbsp;&nbsp;
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <!--<div class="row">
                                    <div class="col-md-6">!-->
                                        <div class="form-group">
                                            <label>Admin Name</label>
                                            <input type="text" name="username" id="username" class="form-control" required data-parsley-pattern="/^[a-zA-Z0-9 \s]+$/" data-parsley-maxlength="175" data-parsley-trigger="keyup" />
                                        </div>
                                        <div class="form-group">
                                            <label>Admin Email Address</label>
                                            <input type="text" name="email_address" id="email_address" class="form-control" required  data-parsley-type="email" data-parsley-trigger="keyup" />
                                        </div>
                                        <div class="form-group">
                                            <label>Password</label>
                                            <input type="password" name="password" id="password" class="form-control" required data-parsley-maxlength="16" data-parsley-trigger="keyup" />
                                        </div>
                                        <div class="form-group">
                                            <label>Brgy Name</label>
                                            <input type="text" name="brgy_name" id="brgy_name" class="form-control" required  data-parsley-trigger="keyup" />
                                        </div>
                                        <div class="form-group">
                                            <label>Brgy Address</label>
                                            <textarea name="brgy_address" id="brgy_address" class="form-control" required ></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label>Brgy Contact No.</label>
                                            <input type="text" name="brgy_contact_no" id="brgy_contact_no" class="form-control" required  data-parsley-trigger="keyup" />
                                        </div>
                                        <div class="form-group">
                                            <label>Brgy Logo</label><br />
                                            <input type="file" name="brgy_logo" id="brgy_logo" />
                                            <span id="uploaded_brgy_logo"></span>
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
    $('#email_address').val("<?php echo $row['email_address']; ?>");
    $('#password').val("<?php echo $row['password']; ?>");
    $('#username').val("<?php echo $row['username']; ?>");
    $('#brgy_name').val("<?php echo $row['brgy_name']; ?>");
    $('#brgy_address').val("<?php echo $row['brgy_address']; ?>");
    $('#brgy_contact_no').val("<?php echo $row['brgy_contact_no']; ?>");
    <?php
        if($row['brgy_logo'] != '')
        {
    ?>
    $("#uploaded_brgy_logo").html("<img src='<?php echo $row["brgy_logo"]; ?>' class='img-thumbnail' width='100' /><input type='hidden' name='hidden_brgy_logo' value='<?php echo $row['brgy_logo']; ?>' />");

    <?php
        }
        else
        {
    ?>
    $("#uploaded_brgy_logo").html("<input type='hidden' name='hidden_brgy_logo' value='' />");
    <?php
        }
    }
    ?>

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

                    if(data.error != '')
                    {
                        $('#message').html(data.error);
                    }
                    else
                    {

                        $('#email_address').val(data.email_address);
                        $('#password').val(data.password);
                        $('#username').val(data.username);

                        $('#brgy_name').val(data.brgy_name);
                        $('#brgy_address').val(data.brgy_address);
                        $('#brgy_contact_no').val(data.brgy_contact_no);

                        if(data.brgy_logo != '')
                        {
                            $("#uploaded_brgy_logo").html("<img src='"+data.brgy_logo+"' class='img-thumbnail' width='100' /><input type='hidden' name='hidden_brgy_logo' value='"+data.brgy_logo+"'");
                        }
                        else
                        {
                            $("#uploaded_brgy_logo").html("<input type='hidden' name='hidden_brgy_logo' value='"+data.brgy_logo+"'");
                        }

                        $('#message').html(data.success);

    					setTimeout(function(){

    				        $('#message').html('');

    				    }, 5000);
                    }
				}
			})
		}
	});

});
</script>