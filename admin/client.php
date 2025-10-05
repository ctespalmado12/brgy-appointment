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
                    <h1 class="h3 mb-4 text-gray-800">Client Management</h1>

                    <!-- DataTales Example -->
                    <span id="message"></span>
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                        	<div class="row">
                            	<div class="col">
                            		<h6 class="m-0 font-weight-bold text-primary">Client List</h6>
                            	</div>
                            	<div class="col" align="right">
                            	</div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="client" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th>Email Address</th>
                                            <th>Contact No.</th>
                                            <th>Email Verification Status</th>
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

<div id="clientModal" class="modal fade">
  	<div class="modal-dialog">
    	<form method="post" id="client_form" action="client_action.php">
      		<div class="modal-content">
        		<div class="modal-header">
          			<h4 class="modal-title" id="modal_title">Edit Client</h4>
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
                                <label>First Name <span class="text-danger">*</span></label>
                                <input type="text" name="first_name" id="first_name" class="form-control" required data-parsley-trigger="keyup" />
                            </div>
                            <div class="col-md-6">
                                <label>Last Name <span class="text-danger">*</span></label>
                                <input type="text" name="last_name" id="last_name" class="form-control" required data-parsley-trigger="keyup" />
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label>Address </label>
                                <input type="text" name="address" id="address" class="form-control" />
                            </div>
                            <div class="col-md-6">
                                <label>Phone No. <span class="text-danger">*</span></label>
                                <input type="text" name="phone_no" id="phone_no" class="form-control" required  data-parsley-trigger="keyup" />
                            </div>
                        </div>
                    </div>
        		</div>
        		<div class="modal-footer">
          			<input type="hidden" name="hidden_id" id="hidden_id" />
          			<input type="hidden" name="action" id="action" value="Edit" />
          			<input type="submit" name="submit" id="submit_button" class="btn btn-success" value="Edit" />
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
                <h4 class="modal-title" id="modal_title">View Client Details</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" id="client_details">
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){

	var dataTable = $('#client').DataTable({
		"processing" : true,
		"serverSide" : true,
		"order" : [],
		"ajax" : {
			url:"client_action.php",
			type:"POST",
			data:{action:'fetch'}
		},
		"columnDefs":[
			{
				"targets":[5],
				"orderable":false,
			},
		],
	});

    $(document).on('click', '.view_button', function(){

        var client_id = $(this).data('id');

        $.ajax({

            url:"client_action.php",

            method:"POST",

            data:{client_id:client_id, action:'fetch_single'},

            dataType:'JSON',

            success:function(data)
            {
                var html = '<div class="table-responsive">';
                html += '<table class="table">';

                html += '<tr><th width="40%" class="text-right">Email Address</th><td width="60%">'+data.email_address+'</td></tr>';

                html += '<tr><th width="40%" class="text-right">Full Name</th><td width="60%">'+data.first_name+' '+data.last_name+'</td></tr>';

                html += '<tr><th width="40%" class="text-right">Contact No.</th><td width="60%">'+data.phone_no+'</td></tr>';

                html += '<tr><th width="40%" class="text-right">Address</th><td width="60%">'+data.address+'</td></tr>';

                html += '<tr><th width="40%" class="text-right">Gender</th><td width="60%">'+data.gender+'</td></tr>';

                html += '<tr><th width="40%" class="text-right">Email Verification Status</th><td width="60%">'+data.email_verify+'</td></tr>';

                html += '</table></div>';

                $('#viewModal').modal('show');

                $('#client_details').html(html);

            }

        })
    });

	$(document).on('click', '.delete_button', function(){

    	var id = $(this).data('id');

    	if(confirm("Are you sure you want to remove it?"))
    	{

      		$.ajax({

        		url:"client_action.php",

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

    $(document).on('click', '.edit_button', function(){

        var client_id = $(this).data('id');

        $('#client_form').parsley().reset();

        $('#form_message').html('');

        $.ajax({

            url:"client_action.php",

            method:"POST",

            data:{client_id:client_id, action:'fetch_single'},

            dataType:'JSON',

            success:function(data)
            {

                $('#email_address').val(data.email_address);
                $('#password').val(data.password);
                $('#first_name').val(data.first_name);
                $('#last_name').val(data.last_name);
                $('#address').val(data.address);
                $('#phone_no').val(data.phone_no);

                $('#modal_title').text('Edit Client');

                $('#action').val('Edit');

                $('#submit_button').val('Edit');

                $('#clientModal').modal('show');

                $('#hidden_id').val(client_id);

            }

        })

    });



});
</script>