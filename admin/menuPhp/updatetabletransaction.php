<?php
include 'connect.php';

if(isset($_POST['displaySend'])){
    $table = '<table class="table my-5 rounded-top">
    <thead class ="rounded-top table-dark  ">
      <tr>
        <th scope="col">Appointment Number</th>
        <th scope="col">Client Name</th>
        <th scope="col">Barangay Official Name</th>
        <th scope="col">Appointment Date</th>
        <th scope="col">Appointment Time</th>
        <th scope="col">Purpose</th>
      </tr>
    </thead>';

    $sql = "Select CONCAT(first_name,' ',last_name) 
    as name, brgyOffSched.schedule_date as appdate,cl.client_id as client_id, app.appointment_id as appointment_id, cl.first_name as firstname,  broff.fullname as brgy_name, app.appointment_time as apptime, app.reason_for_appointment as reason
    FROM appointment app
    JOIN brgy_official broff ON broff.brgy_official_id = app.brgy_official_id
    JOIN client cl ON cl.client_id = app.client_id
    JOIN brgy_official_schedule as brgyOffSched ON brgyOffSched.brgy_official_schedule_id = app.brgy_official_schedule_id 

    
    ";
    $sqlBrgyClearance = "Select CONCAT(first_name,' ',last_name) 
    as name, brgyOffSched.schedule_date as appdate,cl.client_id as client_id, app.appointment_id as appointment_id, cl.first_name as firstname,  broff.fullname as brgy_name, app.appointment_time as apptime, app.reason_for_appointment as reason
    FROM appointment app
    JOIN brgy_official broff ON broff.brgy_official_id = app.brgy_official_id
    JOIN client cl ON cl.client_id = app.client_id
    JOIN brgy_official_schedule as brgyOffSched ON brgyOffSched.brgy_official_schedule_id = app.brgy_official_schedule_id where app.reason_for_appointment = 'Brgy Clearance'

    
    ";
    $sqlBrgyCedula = "Select CONCAT(first_name,' ',last_name) 
    as name, brgyOffSched.schedule_date as appdate,cl.client_id as client_id, app.appointment_id as appointment_id, cl.first_name as firstname,  broff.fullname as brgy_name, app.appointment_time as apptime, app.reason_for_appointment as reason
    FROM appointment app
    JOIN brgy_official broff ON broff.brgy_official_id = app.brgy_official_id
    JOIN client cl ON cl.client_id = app.client_id
    JOIN brgy_official_schedule as brgyOffSched ON brgyOffSched.brgy_official_schedule_id = app.brgy_official_schedule_id where app.reason_for_appointment = 'Cedula'

    
    ";
    $sqlBP = "Select CONCAT(first_name,' ',last_name) 
    as name, brgyOffSched.schedule_date as appdate,cl.client_id as client_id, app.appointment_id as appointment_id, cl.first_name as firstname,  broff.fullname as brgy_name, app.appointment_time as apptime, app.reason_for_appointment as reason
    FROM appointment app
    JOIN brgy_official broff ON broff.brgy_official_id = app.brgy_official_id
    JOIN client cl ON cl.client_id = app.client_id
    JOIN brgy_official_schedule as brgyOffSched ON brgyOffSched.brgy_official_schedule_id = app.brgy_official_schedule_id where app.reason_for_appointment = 'Business Permit'

    
    ";
    $sqlCI = "Select CONCAT(first_name,' ',last_name) 
    as name, brgyOffSched.schedule_date as appdate,cl.client_id as client_id, app.appointment_id as appointment_id, cl.first_name as firstname,  broff.fullname as brgy_name, app.appointment_time as apptime, app.reason_for_appointment as reason
    FROM appointment app
    JOIN brgy_official broff ON broff.brgy_official_id = app.brgy_official_id
    JOIN client cl ON cl.client_id = app.client_id
    JOIN brgy_official_schedule as brgyOffSched ON brgyOffSched.brgy_official_schedule_id = app.brgy_official_schedule_id where app.reason_for_appointment = 'Certificate of Indigency'

    
    ";
    $sqlCID = "Select CONCAT(first_name,' ',last_name) 
    as name, brgyOffSched.schedule_date as appdate,cl.client_id as client_id, app.appointment_id as appointment_id, cl.first_name as firstname,  broff.fullname as brgy_name, app.appointment_time as apptime, app.reason_for_appointment as reason
    FROM appointment app
    JOIN brgy_official broff ON broff.brgy_official_id = app.brgy_official_id
    JOIN client cl ON cl.client_id = app.client_id
    JOIN brgy_official_schedule as brgyOffSched ON brgyOffSched.brgy_official_schedule_id = app.brgy_official_schedule_id where app.reason_for_appointment = 'Certificate of Identification'

    
    ";
    $sqlCR = "Select CONCAT(first_name,' ',last_name) 
    as name, brgyOffSched.schedule_date as appdate,cl.client_id as client_id, app.appointment_id as appointment_id, cl.first_name as firstname,  broff.fullname as brgy_name, app.appointment_time as apptime, app.reason_for_appointment as reason
    FROM appointment app
    JOIN brgy_official broff ON broff.brgy_official_id = app.brgy_official_id
    JOIN client cl ON cl.client_id = app.client_id
    JOIN brgy_official_schedule as brgyOffSched ON brgyOffSched.brgy_official_schedule_id = app.brgy_official_schedule_id where app.reason_for_appointment = 'Certificate of Residency'

    
    ";
   
    $sqlFB = "Select CONCAT(first_name,' ',last_name) 
    as name, brgyOffSched.schedule_date as appdate,cl.client_id as client_id, app.appointment_id as appointment_id, cl.first_name as firstname,  broff.fullname as brgy_name, app.appointment_time as apptime, app.reason_for_appointment as reason
    FROM appointment app
    JOIN brgy_official broff ON broff.brgy_official_id = app.brgy_official_id
    JOIN client cl ON cl.client_id = app.client_id
    JOIN brgy_official_schedule as brgyOffSched ON brgyOffSched.brgy_official_schedule_id = app.brgy_official_schedule_id where app.reason_for_appointment = 'File a Blotter'

    
    ";
    $sqlEduc = "Select CONCAT(first_name,' ',last_name) 
    as name, brgyOffSched.schedule_date as appdate,cl.client_id as client_id, app.appointment_id as appointment_id, cl.first_name as firstname,  broff.fullname as brgy_name, app.appointment_time as apptime, app.reason_for_appointment as reason
    FROM appointment app
    JOIN brgy_official broff ON broff.brgy_official_id = app.brgy_official_id
    JOIN client cl ON cl.client_id = app.client_id
    JOIN brgy_official_schedule as brgyOffSched ON brgyOffSched.brgy_official_schedule_id = app.brgy_official_schedule_id where app.reason_for_appointment = 'Submission of Educational Assistance Requirements'

    
    ";




    
    if($_POST['res'] == 'Brgy Clearance'){

        $result = mysqli_query($con, $sqlBrgyClearance);
    }else if($_POST['res'] == 'Cedula'){

        $result = mysqli_query($con, $sqlBrgyCedula);
    }else if($_POST['res'] == 'Business Permit'){
       $result = mysqli_query($con, $sqlBP);
        
    }else if($_POST['res'] == 'Certificate of Indigency'){
        
        $result = mysqli_query($con, $sqlCI);
    }else if($_POST['res'] == 'Certificate of Identification'){
        $result = mysqli_query($con, $sqlCID);
        
    }else if($_POST['res'] == 'Certificate of Residency'){
        $result = mysqli_query($con, $sqlCR);
        
        
        
    }else if($_POST['res'] == 'File a Blotter'){
        $result = mysqli_query($con, $sqlFB);
        
        
        
    }else if($_POST['res'] == 'Submission of Educational Assistance Requirements'){
        $result = mysqli_query($con, $sqlEduc);
      

    }else{
        //else code
    }
   
    while($row = mysqli_fetch_assoc($result)){
        $app_id = $row['appointment_id'];// tb appointment
        $clientName = $row['name']; //tb client 
        $brgyoff = $row['brgy_name']; // tb brgy officaial and appointment and brgay off sched
        $appDate = $row['appdate']; // client tb and appointment
        $appTime = $row['apptime'];


      $appTime = date("g:i a", strtotime($appTime));
      $appDate =date('F d Y', strtotime($appDate));
    

        $purpose = $row['reason'];
        $table.=' <tr>
        
        <td>'.$app_id.'</td>
        <td>'.$clientName.'</td>
        <td>'.$brgyoff.'</td>
        <td>'.$appDate.'</td>
        <td>'.$appTime.'</td>
        <td>'.$purpose.'</td>
        <td>
    
        </td>
      </tr>';
    
     
    }


    $table.='</table>';
    echo $table;

    if (mysqli_num_rows($result)==0){
      echo '<div class="container-fluid text-center">
      <img class="text-center" src="../../images/no-data.png" alt="" style="width:200px; hieght:200px;">
      <h5>No available data...</h5>
    </div>';
    
  
    }


}



?>