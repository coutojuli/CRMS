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
                    $carid = array_key_exists(1,$split) ? $split[1] : '' ;
                    $name = array_key_exists(2,$split) ? $split[2] : '' ;
                    $car = array_key_exists(3,$split) ? $split[3] : '' ;
                    $pickup = array_key_exists(4,$split) ? $split[4] : '' ;
                    $dropoff = array_key_exists(5,$split) ? $split[5] : '' ;
                    $location = array_key_exists(6,$split) ? $split[6] : '' ;
                    $insurance = array_key_exists(7,$split) ? $split[7] : '' ;
                    $price = array_key_exists(8,$split) ? $split[8] : '' ;
                  
                
                    $param_carid = $carid;
                    $param_name = $name;
                    $param_car = $car;
                    $param_pickup = $pickup;
                    $param_dropoff = $dropoff;
                    $param_location = $location;
                    $param_insurance = $insurance;
                    $param_price = $price;

                }

                if($command === "UPDATE"){
                    $id = array_key_exists(9,$split) ? $split[9] : '' ;
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
                    $sql1 = "SELECT MAX(ID) FROM Reservations";
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
                    $sql = "INSERT INTO Reservations (CarID, CustomerName, CarName, Pickup, DropOff, Location, Insurance, Price)
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
         
                    if($stmt = mysqli_prepare($link, $sql)){
                        // Bind variables to the prepared statement as parameters
                        mysqli_stmt_bind_param($stmt, "ssssssss", $param_carid, $param_name, $param_car, $param_pickup, $param_dropoff, $param_location, 
                        $param_insurance, $param_price);

                        $err = !mysqli_stmt_execute($stmt) ? "Insert Statement: Error on line ".$count." Insert statement could not be executed. Please try again.\n" : $err ;                  
                    }
                }
                else if($command === "UPDATE"){
                    $sql = "UPDATE Reservations SET CarID=?, CustomerName=?, CarName=?, Pickup=?, DropOff=?, Location=?, Insurance=?, Price=? WHERE ID=?";   
                     
                    if($stmt = mysqli_prepare($link, $sql)){
                        // Bind variables to the prepared statement as parameters
                        mysqli_stmt_bind_param($stmt, "ssssssssi", $param_carid, $param_name, $param_car, $param_pickup, $param_dropoff, $param_location, 
                        $param_insurance, $param_price, $param_id);
                        
                        $err = !mysqli_stmt_execute($stmt) ? "Update Statement: Error on line ".$count." Update statement could not be executed for the given ID.\n" : $err ;  
                    }
                }

                else if($command === "DELETE"){
                    $sql = "DELETE FROM Reservations WHERE ID = ?";
    
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
        $sql = "SELECT * FROM Reservations WHERE ID IN ($fileids)";
    
        if($result = mysqli_query($link, $sql)){
            if(mysqli_num_rows($result) > 0){
                echo '<table class="hover">';
                    echo "<thead>";
                        echo "<tr>";
                        echo "<th>ID</th>";
                        echo "<th>Car ID</th>";
                        echo "<th>Email</th>";
                        echo "<th>Car Name</th>";
                        echo "<th>Price</th>";
                        echo "<th>Action</th>";
                        echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";
                  
                    while($row = mysqli_fetch_array($result)){
                        echo "<tr>";
                        echo "<td>" . $row['ID'] . "</td>";
                        echo "<td>" . $row['CarID'] . "</td>";
                        echo "<td>" . $row['CustomerName'] . "</td>";
                        echo "<td>" . $row['CarName'] . "</td>";
                        echo "<td>" . $row['Price'] . "</td>";
                        echo "<td>";                                                 
                        echo '<a href="cars/details.php?id='. $row['ID'] .'">Details</a>';
                        echo "</td>";
                        echo "</tr>";
                        
                    }
                    
                    echo "</tbody>";
                    echo "</table>";
                    echo "<p>Deleted Rows with Reservation ID: ". $delrols ."</p>";

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
                  