<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="">
		<meta name="author" content="">
		<title>Barangay Rosario Appointment System</title>

	    <!-- Custom styles for this page -->
	    <link href="vendor/bootstrap/bootstrap.min.css" rel="stylesheet">

	    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

	    <link rel="stylesheet" type="text/css" href="vendor/parsley/parsley.css"/>

	    <link rel="stylesheet" type="text/css" href="vendor/datepicker/bootstrap-datepicker.css"/>

	    <!-- Custom styles for this page -->
    	<link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
	    <style>
            body {
            background: url("./images/bg-up.png") no-repeat center center fixed;
            -webkit-background-size: cover;
            -moz-background-size: cover;
            background-size: cover;
            -o-background-size: cover;

            }

            .vertical-center {
                min-height: 100%;  /* Fallback for browsers do NOT support vh unit */
                min-height: 100vh; /* These two lines are counted as one :-)       */

                display: flex;
                align-items: center;
            }

            #logo{   
				width: 256px;
				height: 256px;
            }

	    </style>
	</head>
	<body>
	    <div class="container-fluid">
        <div class="vertical-center">
            <div class="container">
                <div class="row justify-content-md-center">
                    <div class="col col-md-4">
                        <span id="message"></span>
                        <div class="card">
                            <div class="card-header font-weight-bold">
                                <div class="d-flex justify-content-center align-items-center flex-column mt-4">
                                    <img src="./img/logo.png" alt="brgy-rosario-logo" id="logo" class="">
                                    <h1 class="h3 mb-3 mt-3 fw-normal text-center">Barangay Rosario Appointment System</h1>
                                </div>
                            </div>
                            <div class="card-body">
                                <a href="login.php" class="btn btn-primary btn-block my-2">Login</a>
                                <a href="register.php" class="btn btn-primary btn-block my-2">Register</a>
                                <br>
                                <a href="schedule.php" class="btn btn-primary btn-block my-2">Schedule</a>
                                <a href="admin/index.php" class="btn btn-primary btn-block my-2">Admin Login</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

<?php

include('footer.php');

?>