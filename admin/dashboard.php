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

                include('header.php');

                ?>

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800">Dashboard</h1>

                    <!-- Content Row -->
                    <div class="row row-cols-5">
                        
                        <div class="col mb-4" style="cursor:pointer;">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body" onclick="menuModal(0)">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Today Total Appointment</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $object->get_total_today_appointment(); ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col mb-4" style="cursor:pointer;">
                            <div class="card border-left-secondary shadow h-100 py-2">
                                <div class="card-body" onclick="menuModal(1)">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                                Yesterday Total Appointment</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $object->get_total_yesterday_appointment(); ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col mb-4" style="cursor:pointer;">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body"  onclick="menuModal(2)">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Last 7 Days Total Appointment</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $object->get_total_seven_day_appointment(); ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col mb-4" style="cursor:pointer;">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body"  onclick="menuModal(3)">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Total Appointments As of Today</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $object->get_total_appointment(); ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col mb-4" style="cursor:pointer;">
                            <div class="card border-left-danger shadow h-100 py-2">
                                <div class="card-body"  onclick="menuModal(4)">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                                Total Registered Client</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $object->get_total_client(); ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <script>

                       
                                function menuModal(menuNum){
                                //console.log("awit "+menuNum);

                                 var num = menuNum;
                                 localStorage.setItem("myValue", num);

                                 if(num == 0){

                                     window.location.href = "menuPhp/menu0.php";
                                    }else if(num==1){
                                        
                                        window.location.href = "menuPhp/menu1.php";
                                 }
                                    else if(num==2){
                                        
                                        window.location.href = "menuPhp/menu2.php";
                                 }
                                    else if(num==3){
                                        
                                        window.location.href = "menuPhp/menu3.php";
                                 }
                                    else if(num==4){
                                        
                                        window.location.href = "menuPhp/menu4.php";
                                 }
                        
                                   
                                }
                             


                            
                    </script>

                <?php
                include('footer.php');
                ?>