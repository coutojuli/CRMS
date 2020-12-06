<?php
require_once '../header.php';
require_once '../../DAL/ConnectionManager.php';

// Check existence of id parameter before processing further
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    
    // Prepare a select statement
    $sql = "SELECT * FROM Cars WHERE ID = ?";
    
    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        
        // Set parameters
        $param_id = trim($_GET["id"]);
        
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
    
            if(mysqli_num_rows($result) == 1){
                /* Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                
                // Retrieve individual field value
                $name = $row["Name"];
                $type = $row["Type"];
                $level = $row["Level"];
                $bag = $row["BagCapacity"];
                $passenger = $row["PassengerCapacity"];
                $transmission = $row["Transmission"];
                $price = $row["Price"];
                $availability = $row["Availability"];
                $interior = $row["LeatherInterior"];
                $radio = $row["Radio"];
                $air = $row["AirConditioning"];
                $door = $row["PowerLockDoor"];
                $wheel = $row["TiltSteeringWheel"];
                $control = $row["CruiseControl"];
                $mirror = $row["PowerMirrors"];
                $bluetooth = $row["Bluetooth"];

            } else{
                // URL doesn't contain valid id parameter. Redirect to error page
                header("location: cars.php");
                exit();
            }
            
        } else{
            echo "Something went wrong. Please try again later.";
        }
    }
     
    // Close statement
    mysqli_stmt_close($stmt);
    
    // Close connection
    mysqli_close($link);
} else{
    // URL doesn't contain id parameter. Redirect to error page
    header("location: cars.php");
    exit();
}

?>
<div class="grid-container">
    <div class="grid-x fluid">
        <div class="cell auto table-scroll">
            <div class="form-header">
                <h2>Car Details</h2>
            </div>
                
            <table class="hover">
                <tbody>
                    <tr>
                        <input type="hidden" name="id" value="<?php echo $param_id; ?>"/>
                        <td style="text-align:center;">
                            Name
                        </td>
                        <td style="text-align:left">
                        <?php echo $row["Name"]; ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:center">
                            Type
                        </td>
                        <td style="text-align:left">
                        <?php echo $row["Type"]; ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:center">
                            Level
                        </td>
                        <td style="text-align:left">
                        <?php echo $row["Level"]; ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:center">
                            Bag Capacity
                        </td>
                        <td style="text-align:left">
                        <?php echo $row["BagCapacity"]; ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:center">
                        Passenger Capacity
                        </td>
                        <td style="text-align:left">
                        <?php echo $row["PassengerCapacity"]; ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:center">
                            Transmission
                        </td>
                        <td style="text-align:left">
                        <?php echo $row["Transmission"]; ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:center">
                            Reservation Price
                        </td>
                        <td style="text-align:left">
                            <?php echo $row["Price"]; ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:center">
                            Availability
                        </td>
                        <td style="text-align:left">
                        <?php echo $row["Availability"]; ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:center">
                            Leather Interior
                        </td>
                        <td style="text-align:left">
                        <?php echo $row["LeatherInterior"]; ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:center">
                            Radio
                        </td>
                        <td style="text-align:left">
                        <?php echo $row["Radio"]; ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:center">
                            Air Conditioning
                        </td>
                        <td style="text-align:left">
                        <?php echo $row["AirConditioning"]; ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:center">
                            Power Lock Door
                        </td>
                        <td style="text-align:left">
                        <?php echo $row["PowerLockDoor"]; ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:center">
                            Cuise Control
                        </td>
                        <td style="text-align:left">
                        <?php echo $row["CruiseControl"]; ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:center">
                            Tilt Steering Wheel
                        </td>
                        <td style="text-align:left">
                        <?php echo $row["TiltSteeringWheel"]; ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:center">
                            Power Mirrors
                        </td>
                        <td style="text-align:left">
                        <?php echo $row["PowerMirrors"]; ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:center">
                            Bluetooth
                        </td>
                        <td style="text-align:left">
                        <?php echo $row["Bluetooth"]; ?>
                        </td>
                    </tr>
                </tbody>
            </table>
            <p>
                
                <a href="cars.php">Go back to List</a>
                <?php                    
                    if (isset($_SESSION['email'])){
                        if ($_SESSION['role'] == "Admin"){
                            echo '| <a href="update.php?id='. $row['ID'] .'">Edit</a>'; 
                            echo '| <a href="delete.php?id='. $row['ID'] .'">Delete</a>';
                        }
                    }
                ?>
            </p>
        </div>
    </div>
</div>

<?php
require_once '../footer.php';
