<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="">
		<meta name="author" content="">
		<title>Barrangay Rosario Appointment System</title>

	    <!-- Custom styles for this page -->
	    <link href="vendor/bootstrap/bootstrap.min.css" rel="stylesheet">

	    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

	    <link rel="stylesheet" type="text/css" href="vendor/parsley/parsley.css"/>

	    <link rel="stylesheet" type="text/css" href="vendor/datepicker/bootstrap-datepicker.css"/>

	    <!-- Custom styles for this page -->
    	<link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
	    <style>
	    	.border-top { border-top: 1px solid #e5e5e5; }
			.border-bottom { border-bottom: 1px solid #e5e5e5; }

			.box-shadow { box-shadow: 0 .25rem .75rem rgba(0, 0, 0, .05); }
			#logo{   
				width: 60px;
				height: 60px; 
			}
	    </style>
	</head>
	<body>
		<div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-warning border-bottom box-shadow justify-content-between">
			<div class="d-flex flex-row align-items-center gap">
				<img src="./img/logo.png" alt="brgy-rosario-logo" id="logo" class="">
		    	<h5 class="my-0 mx-2 mr-md-auto font-weight-normal"><a href="index.php" class="text-dark" style="text-decoration: none;">Barangay Rosario Appointment System</a></h5>
		    </div>
			<div class="d-flex flex-column flex-md-row align-items-center">
				<?php
				if(!isset($_SESSION['client_id']))
				{
				?>
				<div class="col text-right">
					<a href="login.php" class="btn btn-primary" style="text-decoration: none;">Login</a>
				</div>
				<div class="col text-right">
					<a href="admin" class="btn btn-secondary">Admin</a>
				</div>
				<?php
				}
				?>
			</div>
	    </div>
	    <br />
	    <br />
	    <div class="container-fluid">