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
    $sqlday = "Select CONCAT(first_name,' ',last_name) 
    as name, brgyOffSched.schedule_date as appdate,cl.client_id as client_id, app.appointment_id as appointment_id, cl.first_name as firstname,  broff.fullname as brgy_name, app.appointment_time as apptime, app.reason_for_appointment as reason
    FROM appointment app
    JOIN brgy_official broff ON broff.brgy_official_id = app.brgy_official_id
    JOIN client cl ON cl.client_id = app.client_id
    JOIN brgy_official_schedule as brgyOffSched ON brgyOffSched.brgy_official_schedule_id = app.brgy_official_schedule_id where brgyOffSched.schedule_date = CURDATE()

  ";
    $sqlweek = "
    Select CONCAT(first_name,' ',last_name) 
    as name, brgyOffSched.schedule_date as appdate,cl.client_id as client_id, app.appointment_id as appointment_id, cl.first_name as firstname,  broff.fullname as brgy_name, app.appointment_time as apptime, app.reason_for_appointment as reason
    FROM appointment app
    JOIN brgy_official broff ON broff.brgy_official_id = app.brgy_official_id
    JOIN client cl ON cl.client_id = app.client_id
    JOIN brgy_official_schedule as brgyOffSched ON brgyOffSched.brgy_official_schedule_id = app.brgy_official_schedule_id
    WHERE  brgyOffSched.schedule_date BETWEEN CURDATE()-7 AND CURDATE()

    
    ";
    $sqlmonth = "Select CONCAT(first_name,' ',last_name) 
    as name, brgyOffSched.schedule_date as appdate,cl.client_id as client_id, app.appointment_id as appointment_id, cl.first_name as firstname,  broff.fullname as brgy_name, app.appointment_time as apptime, app.reason_for_appointment as reason
    FROM appointment app
    JOIN brgy_official broff ON broff.brgy_official_id = app.brgy_official_id
    JOIN client cl ON cl.client_id = app.client_id
    JOIN brgy_official_schedule as brgyOffSched ON brgyOffSched.brgy_official_schedule_id = app.brgy_official_schedule_id where

    brgyOffSched.schedule_date >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
    
    ";
    $sqlyear = "Select CONCAT(first_name,' ',last_name) 
    as name, brgyOffSched.schedule_date as appdate,cl.client_id as client_id, app.appointment_id as appointment_id, cl.first_name as firstname,  broff.fullname as brgy_name, app.appointment_time as apptime, app.reason_for_appointment as reason
    FROM appointment app
    JOIN brgy_official broff ON broff.brgy_official_id = app.brgy_official_id
    JOIN client cl ON cl.client_id = app.client_id
    JOIN brgy_official_schedule as brgyOffSched ON brgyOffSched.brgy_official_schedule_id = app.brgy_official_schedule_id where

    YEAR (brgyOffSched.schedule_date)= YEAR (CURDATE());
    
    
    
    ";
    if ($_POST['res'] == 'All'){

        $result = mysqli_query($con, $sql);
    }
    else if($_POST['res'] == 'Daily'){

        $result = mysqli_query($con, $sqlday);
    }else if($_POST['res'] == 'Weekly'){
        $result = mysqli_query($con, $sqlweek);
        
    }
    else if($_POST['res'] == 'Monthly'){
        
        $result = mysqli_query($con, $sqlmonth);
    }
    else if($_POST['res'] == 'Annually'){
        $result = mysqli_query($con, $sqlyear);
        
    }else{
       // $result = mysqli_query($con, $sql);

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