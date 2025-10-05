<?php
include 'connect.php';


if(isset($_POST['displaySend'])){
    $table = '<table class="table my-5 rounded-top">
    <thead class ="rounded-top table-dark  ">
      <tr>
        <th scope="col">Client Name</th>
        <th scope="col">Address</th>
      </tr>
    </thead>';
   $var = " ";
    $sql = "Select CONCAT(first_name,' ',last_name) 
    as name ,address as address, gender as ctr from client
    

    
    ";
    $result = mysqli_query($con, $sql);
   
    while($row = mysqli_fetch_assoc($result)){
      
        $name = $row['name'];
        $address = $row['address']; //tb client 
        $ctr = $row['ctr']; // tb brgy officaial and appointment and brgay off sched
      
        $table.=' <tr>
        
        <td>'.$name.'</td>
        <td>'.$address.'</td>
  
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
