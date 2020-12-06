<?php
require_once '../header.php';
require_once '../../DAL/ConnectionManager.php';

// Check existence of id parameter before processing further
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    
    // Prepare a select statement
    $sql = "SELECT * FROM Reservations WHERE ID = ?";
    
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
                    $name = $row["CustomerName"];
                    $carid = $row["CarID"];
                    $car = $row["CarName"];
                    $pickup = $row["Pickup"];
                    $dropoff = $row["DropOff"];
                    $location = $row["Location"];
                    $insurance = $row["Insurance"];
                    $price = $row["Price"];

            } else{
                // URL doesn't contain valid id parameter. Redirect to error page
                header("location: reservations.php");
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
    header("location: reservations.php");
    exit();
}

?>
<div class="grid-container">
    <div class="grid-x fluid">
        <div class="cell auto table-scroll">
            <div class="form-header">
                <h2>Reservation Details</h2>
            </div>
                
            <table class="hover">
                <tbody>
                    <tr>
                        <td style="text-align:center;">
                            Email:
                        </td>
                        <td style="text-align:left">
                        <?php echo $row["CustomerName"]; ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:center">
                            Car Name:
                        </td>
                        <td style="text-align:left">
                        <?php echo $row["CarName"]; ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:center">
                            Car ID:
                        </td>
                        <td style="text-align:left">
                        <?php echo $row["CarID"]; ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:center">
                            Pick Up Date:
                        </td>
                        <td style="text-align:left">
                        <?php echo $row["Pickup"]; ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:center">
                            Drop Off Date:
                        </td>
                        <td style="text-align:left">
                        <?php echo $row["DropOff"]; ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:center">
                        Location:
                        </td>
                        <td style="text-align:left">
                        <?php echo $row["Location"]; ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:center">
                            Insurance
                        </td>
                        <td style="text-align:left">
                        <?php echo $row["Insurance"]; ?>
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
                </tbody>
            </table>
            <p>
                <a href="reservations.php">Go back to Reservation List</a>
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
@require_once '../footer.php';
