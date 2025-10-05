<?php

include('../class/Appointment.php');

$object = new Appointment;

if(!$object->is_login())
{
    header("location:".$object->base_url."admin");
}

if($_SESSION['type'] != 'Admin')
{
    header("location:".$object->base_url."");
}

include('header.php');

?>

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800">Brgy Official Management</h1>

                    <!-- DataTales Example -->
                    <span id="message"></span>
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                        	<div class="row">
                            	<div class="col">
                            		<h6 class="m-0 font-weight-bold text-primary">Brgy Official List</h6>
                            	</div>
                            	<div class="col" align="right">
                            		<button type="button" name="add_official" id="add_official" class="btn btn-success btn-circle btn-sm"><i class="fas fa-plus"></i></button>
                            	</div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="brgy_official" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Image</th>
                                            <th>Email Address</th>
                                            <th>Password</th>
                                            <th>Fullname</th>
                                            <th>Phone No.</th>
                                            <th>Position</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                <?php
                include('footer.php');
                ?>

<div id="officialModal" class="modal fade">
  	<div class="modal-dialog">
    	<form method="post" id="official_form">
      		<div class="modal-content">
        		<div class="modal-header">
          			<h4 class="modal-title" id="modal_title">Add Brgy Official</h4>
          			<button type="button" class="close" data-dismiss="modal">&times;</button>
        		</div>
        		<div class="modal-body">
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
                                <label>Fullname <span class="text-danger">*</span></label>
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
                                <label>Position <span class="text-danger">*</span></label>
                                <input type="text" name="position" id="position" class="form-control" required  data-parsley-trigger="keyup" />
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Profile Image <span class="text-danger">*</span></label>
                        <br />
                        <input type="file" name="profile_image" id="profile_image" />
                        <div id="uploaded_image"></div>
                        <input type="hidden" name="hidden_profile_image" id="hidden_profile_image" />
                    </div>
        		</div>
        		<div class="modal-footer">
          			<input type="hidden" name="hidden_id" id="hidden_id" />
          			<input type="hidden" name="action" id="action" value="Add" />
          			<input type="submit" name="submit" id="submit_button" class="btn btn-success" value="Add" />
          			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        		</div>
      		</div>
    	</form>
  	</div>
</div>

<div id="viewModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modal_title">View Brgy Official Details</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" id="official_details">
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){

	var dataTable = $('#brgy_official').DataTable({
		"processing" : true,
		"serverSide" : true,
		"order" : [],
		"ajax" : {
			url:"official_action.php",
			type:"POST",
			data:{action:'fetch'}
		},
		"columnDefs":[
			{
				"targets":[0, 1, 2, 4, 5, 6, 7],
				"orderable":false,
			},
		],
	});

	$('#add_official').click(function(){
		
		$('#official_form')[0].reset();

		$('#official_form').parsley().reset();

    	$('#modal_title').text('Add Brgy Official');

    	$('#action').val('Add');

    	$('#submit_button').val('Add');

    	$('#officialModal').modal('show');

    	$('#form_message').html('');

	});

	$('#official_form').parsley();

	$('#official_form').on('submit', function(event){
		event.preventDefault();
		if($('#official_form').parsley().isValid())
		{		
			$.ajax({
				url:"official_action.php",
				method:"POST",
				data: new FormData(this),
				dataType:'json',
                contentType: false,
                cache: false,
                processData:false,
				beforeSend:function()
				{
					$('#submit_button').attr('disabled', 'disabled');
					$('#submit_button').val('wait...');
				},
				success:function(data)
				{
					$('#submit_button').attr('disabled', false);
					if(data.error != '')
					{
						$('#form_message').html(data.error);
						$('#submit_button').val('Add');
					}
					else
					{
						$('#officialModal').modal('hide');
						$('#message').html(data.success);
						dataTable.ajax.reload();

						setTimeout(function(){

				            $('#message').html('');

				        }, 5000);
					}
				}
			})
		}
	});

	$(document).on('click', '.edit_button', function(){

		var brgy_official_id = $(this).data('id');

		$('#official_form').parsley().reset();

		$('#form_message').html('');

		$.ajax({

	      	url:"official_action.php",

	      	method:"POST",

	      	data:{brgy_official_id:brgy_official_id, action:'fetch_single'},

	      	dataType:'JSON',

	      	success:function(data)
	      	{

	        	$('#email_address').val(data.email_address);

                $('#email_address').val(data.email_address);
                $('#password').val(data.password);
                $('#fullname').val(data.fullname);
                $('#uploaded_image').html('<img src="'+data.profile_image+'" class="img-fluid img-thumbnail" width="150" />')
                $('#hidden_profile_image').val(data.profile_image);
                $('#phone_no').val(data.phone_no);
                $('#address').val(data.address);
                $('#position').val(data.position);

	        	$('#modal_title').text('Edit Brgy Official');

	        	$('#action').val('Edit');

	        	$('#submit_button').val('Edit');

	        	$('#officialModal').modal('show');

	        	$('#hidden_id').val(brgy_official_id);

	      	}

	    })

	});

	$(document).on('click', '.status_button', function(){
		var id = $(this).data('id');
    	var status = $(this).data('status');
		var next_status = 'Active';
		if(status == 'Active')
		{
			next_status = 'Inactive';
		}
		if(confirm("Are you sure you want to "+next_status+" it?"))
    	{

      		$.ajax({

        		url:"official_action.php",

        		method:"POST",

        		data:{id:id, action:'change_status', status:status, next_status:next_status},

        		success:function(data)
        		{

          			$('#message').html(data);

          			dataTable.ajax.reload();

          			setTimeout(function(){

            			$('#message').html('');

          			}, 5000);

        		}

      		})

    	}
	});

    $(document).on('click', '.view_button', function(){
        var brgy_official_id = $(this).data('id');

        $.ajax({

            url:"official_action.php",

            method:"POST",

            data:{brgy_official_id:brgy_official_id, action:'fetch_single'},

            dataType:'JSON',

            success:function(data)
            {
                var html = '<div class="table-responsive">';
                html += '<table class="table">';

                html += '<tr><td colspan="2" class="text-center"><img src="'+data.profile_image+'" class="img-fluid img-thumbnail" width="150" /></td></tr>';

                html += '<tr><th width="40%" class="text-right">Email Address</th><td width="60%">'+data.email_address+'</td></tr>';

                html += '<tr><th width="40%" class="text-right">Password</th><td width="60%">'+data.password+'</td></tr>';

                html += '<tr><th width="40%" class="text-right">Fullname</th><td width="60%">'+data.fullname+'</td></tr>';

                html += '<tr><th width="40%" class="text-right">Phone No.</th><td width="60%">'+data.phone_no+'</td></tr>';

                html += '<tr><th width="40%" class="text-right">Address</th><td width="60%">'+data.address+'</td></tr>';

                html += '<tr><th width="40%" class="text-right">Position</th><td width="60%">'+data.position+'</td></tr>';

                html += '</table></div>';

                $('#viewModal').modal('show');

                $('#official_details').html(html);

            }

        })
    });

	$(document).on('click', '.delete_button', function(){

    	var id = $(this).data('id');

    	if(confirm("Are you sure you want to remove it?"))
    	{

      		$.ajax({

        		url:"official_action.php",

        		method:"POST",

        		data:{id:id, action:'delete'},

        		success:function(data)
        		{

          			$('#message').html(data);

          			dataTable.ajax.reload();

          			setTimeout(function(){

            			$('#message').html('');

          			}, 5000);

        		}

      		})

    	}

  	});



});
</script>