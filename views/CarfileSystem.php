<?php
require_once 'header.php';
require_once '../DAL/ConnectionManager.php';

//2. Change the file location
$uploads_dir = $tmp_name = $name = "";

if (isset($_FILES["fileUpload"]) && $_FILES["fileUpload"]["error"] == 0) { 
    $uploads_dir = '/fileFolder';
    $tmp_name = $_FILES["fileUpload"]["tmp_name"];
    $name = $_FILES["fileUpload"]["name"];
    move_uploaded_file($tmp_name, $_SERVER['DOCUMENT_ROOT']."/N01348498_PhpProject/$uploads_dir/$name");

  //3. Opening the uploaded file (r:read from the beginning) ; feof: file end of file
    $path = $_SERVER['DOCUMENT_ROOT']."/N01348498_PhpProject/$uploads_dir/$name";

    $err = "";
    $fileids = "";
    $delrols = "";
    
    if(file_exists($path)){
        // Attempt to open the file
        $file = fopen($path, "r");

        //while its not the end of file: get line
        $count =0;
        $fileContent = "";
        while(!feof($file)){
            $line = fgets($file);
            
            if ($line != "" && $line != "\n")
            {
                $count = $count + 1;
                $split = explode("," , $line);

            //    echo "Split Line " . $count . "\n". $line . "\n";
            $fileContent =  $fileContent . "\n" .$line . "\n";

                //1. Getting variables
                
                $command = array_key_exists(0,$split) ? $split[0] : '' ;
                if($command === "DELETE"){
                    $id = array_key_exists(1,$split) ? $split[1] : '' ;
                    $param_id = $id;
                }else{                     
                    $name = array_key_exists(1,$split) ? $split[1] : '' ;
                    $type = array_key_exists(2,$split) ? $split[2] : '' ;
                    $level = array_key_exists(3,$split) ? $split[3] : '' ;
                    $bag = array_key_exists(4,$split) ? $split[4] : '' ;
                    $passenger = array_key_exists(5,$split) ? $split[5] : '' ;
                    $transmission = array_key_exists(6,$split) ? $split[6] : '' ;
                    $price = array_key_exists(7,$split) ? $split[7] : '' ;
                    $availability = array_key_exists(8,$split) ? $split[8] : '' ;
                    $interior = array_key_exists(9,$split) ? $split[9] : '' ;
                    $radio = array_key_exists(10,$split) ? $split[10] : '' ;
                    $air = array_key_exists(11,$split) ? $split[11] : '' ;
                    $door = array_key_exists(12,$split) ? $split[12] : '' ;
                    $wheel = array_key_exists(13,$split) ? $split[13] : '' ;
                    $control = array_key_exists(14,$split) ? $split[14] : '' ;
                    $mirrors = array_key_exists(15,$split) ? $split[15] : '' ;
                    $bluetooth = array_key_exists(16,$split) ? $split[16] : '' ;       
                
                    $param_name = $name;
                    $param_type = $type;
                    $param_level = $level;
                    $param_bag = $bag;
                    $param_passenger = $passenger;
                    $param_transmission = $transmission;
                    $param_price = $price;
                    $param_availability = $availability;
                    $param_interior = $interior;
                    $param_radio = $radio;
                    $param_air = $air;
                    $param_door = $door;
                    $param_wheel = $wheel;
                    $param_control = $control;
                    $param_mirror = $mirrors;
                    $param_bluetooth = $bluetooth;
                }

                if($command === "UPDATE"){
                    $id = array_key_exists(17,$split) ? $split[17] : '' ;
                    $param_id = $id;
                }

                if (isset($id)){
                    if($command === "DELETE"){
                        $fileids = $fileids;
                        $delrols = ($delrols == "") ? $param_id : $delrols . "," . $param_id;
                    }else if ($fileids == ""){
                        $fileids = $id;
                    }else{
                        $fileids = $fileids.",".$id ;
                    }
                }
                else if ($command === "INSERT"){
                    $sql1 = "SELECT MAX(ID) FROM Cars";
                    if($stmt = mysqli_prepare($link, $sql1)){
                        if(mysqli_stmt_execute($stmt)){
                            $result = mysqli_stmt_get_result($stmt);
                            if(isset($result)){                   
                                if(mysqli_num_rows($result) > 0){
                                    while($row = mysqli_fetch_array($result)){
                                        $insid =  ($row['MAX(ID)'] + 1);
                                        if ($fileids == ""){
                                            $fileids = $insid;
                                        }else{
                                            $fileids = $fileids.",".$insid ;
                                        }                                      
                                    }
                                }
                            }
                        }
                    }
                }
                if($command === "INSERT"){
                    $sql = "INSERT INTO Cars (Name, Type, Level, BagCapacity, PassengerCapacity, Transmission, Price, Availability, LeatherInterior, Radio, AirConditioning, PowerLockDoor, TiltSteeringWheel,
                    CruiseControl, PowerMirrors, Bluetooth ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
         
                    if($stmt = mysqli_prepare($link, $sql)){
                        // Bind variables to the prepared statement as parameters
                        mysqli_stmt_bind_param($stmt, "ssssssssssssssss", $param_name, $param_type, $param_level, $param_bag, $param_passenger, $param_transmission, 
                        $param_price, $param_availability, $param_interior, $param_radio, $param_air, $param_door, $param_wheel, $param_control, 
                        $param_mirror, $param_bluetooth);

                        $err = !mysqli_stmt_execute($stmt) ? "Insert Statement: Error on line ".$count." Insert statement could not be executed. Please try again.\n" : $err ;                  
                    }
                }
                else if($command === "UPDATE"){
                    $sql = "UPDATE Cars SET Name=?, Type=?, Level=?, BagCapacity=?, PassengerCapacity=?, Transmission=?, Price=?, Availability=?, LeatherInterior=?, 
                    Radio=?, AirConditioning=?, PowerLockDoor=?, TiltSteeringWheel=?, CruiseControl=?, PowerMirrors=?, Bluetooth=? WHERE ID=?";   
                     
                    if($stmt = mysqli_prepare($link, $sql)){
                        // Bind variables to the prepared statement as parameters
                        mysqli_stmt_bind_param($stmt, "ssssssssssssssssi", $param_name, $param_type, $param_level, $param_bag, $param_passenger, $param_transmission, 
                        $param_price, $param_availability, $param_interior, $param_radio, $param_air, $param_door, $param_wheel, $param_control, 
                        $param_mirror, $param_bluetooth,$param_id);
                        
                        $err = !mysqli_stmt_execute($stmt) ? "Update Statement: Error on line ".$count." Update statement could not be executed for the given ID.\n" : $err ;  
                    }
                }

                else if($command === "DELETE"){
                    $sql = "DELETE FROM Cars WHERE ID = ?";
    
                    if($stmt = mysqli_prepare($link, $sql)){
                        // Bind variables to the prepared statement as parameters
                        mysqli_stmt_bind_param($stmt, "i", $param_id); 
                        
                        if(mysqli_stmt_execute($stmt)){
                            $result = mysqli_stmt_get_result($stmt);
                        } else{
                            $err = ("Delete Statement: Error on line ".$count.": No records were found with the given ID. \n");
                        }                      
                    }                
                }
            }
        }
    }else{
    echo "ERROR: File does not exist.";
}
}
fclose($file);
?>
           
<div class="grid-container">
    <div class="grid-x fluid">
        <div class="cell auto table-scroll">
        <div class="form-header">
                <h2>Altered Rows</h2>
            </div>
        <?php
        echo $err;
        echo "<br>";
         $split = explode("," , $fileids);

        $sql = "SELECT * FROM Cars WHERE ID IN ($fileids)";
    
        if($result = mysqli_query($link, $sql)){
            if(mysqli_num_rows($result) > 0){
                echo '<table class="hover">';
                    echo "<thead>";
                        echo "<tr>";
                            echo "<th>Name</th>";
                            echo "<th>Type</th>";
                            echo "<th>Level</th>";
                            echo "<th>Passenger Capacity</th>";
                            echo "<th>Reservation Price</th>";
                            echo "<th>Availability</th>";
                            echo "<th>Action</th>";
                        echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";
                  
                    while($row = mysqli_fetch_array($result)){
                        echo "<tr>";
                        echo "<td>" . $row['Name'] . "</td>";
                        echo "<td>" . $row['Type'] . "</td>";
                        echo "<td>" . $row['Level'] . "</td>";
                        echo "<td>" . $row['PassengerCapacity'] . "</td>";
                        echo "<td>" . $row['Price'] . "</td>";
                        echo "<td>" . $row['Availability'] . "</td>";
                        echo "<td>";                                      
                        echo '<a href="details.php?id='. $row['ID'] .'">Details</a>';
                            if (isset($_SESSION['email'])){ 
                                if ($_SESSION['role'] == "Admin" || $_SESSION['role'] == "User"){
                                    echo '| <a href="update.php?id='. $row['ID'] .'">Edit</a>'; 
                                    echo '| <a href="delete.php?id='. $row['ID'] .'">Delete</a>'; 
                                }
                            }
                            echo "</td>";
                            echo "</tr>";
                        
                    }
                    
                    echo "</tbody>";
                    echo "</table>";
                    echo "<p>Deleted Rows with Car ID: ". $delrols ."</p>";
                    // Free result set
                    mysqli_free_result($result);
                } else{
                    echo "<p class='lead'><em>No car records were found.</em></p>";
                }
            }     
                
                // Close statement
                mysqli_stmt_close($stmt);    
            // Close connection
            mysqli_close($link);
         ?>
        </div>
    </div>
</div>
<?php
require_once 'footer.php';
                  



   
