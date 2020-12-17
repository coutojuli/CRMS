<?php
require_once '../header.php';
require_once '../../DAL/ConnectionManager.php';

// Process delete operation after confirmation

if(isset($_GET["id"]) && !empty($_GET["id"])){

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
                
                $name = $row["CustomerName"];
                $carid = $row["CarID"];
                $car = $row["CarName"];
                $pickup = $row["Pickup"];
                $dropoff = $row["DropOff"];
                $location = $row["Location"];
                $insurance = $row["Insurance"];
                $price = $row["Price"];

                   // Close statement
                   mysqli_stmt_close($stmt);   
                }
            }
        }
        
        $now = strtotime((new DateTime())->format('Y-m-d'));
        $pickup = strtotime($pickup);
        $dropoff = strtotime($dropoff);

        if ($now > $dropoff){
            $err = "Error Deleting Reservation | Dropoff date is before today | Action denied.";
            header("location: reservations.php?err=$err");
            exit();                     
        } 
        else if($pickup > $now){
        // can delete
        //  Prepare a delete statement
            $sql = "DELETE FROM Reservations WHERE id = ?";
            
            if($stmt = mysqli_prepare($link, $sql)){
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "i", $param_id);
                
                // Set parameters
                $param_id = trim($_GET["id"]);
                
                // Attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt)){
                    mysqli_stmt_close($stmt);
                    // Records created successfully. Redirect to landing page
                    $sql1 = "UPDATE Cars SET Availability=? WHERE ID=?";   
                    if($stmt = mysqli_prepare($link, $sql1)){
                        // Bind variables to the prepared statement as parameters
                        mysqli_stmt_bind_param($stmt,"si",$param_av,$param_carid);
                        $param_av = "Available";
                        $param_carid = $carid;
                        
                        if(mysqli_stmt_execute($stmt)){
                            header("location: reservations.php");
                            exit();
                        }
                    }

                    if(mysqli_stmt_execute($stmt)){
                        // Records deleted successfully. Redirect to landing page

                        header("location: reservations.php");
                        exit();
                    } else{
                        echo "Something went wrong. Please try again later.";
                    }
                }
            
            // Close statement
            mysqli_stmt_close($stmt);
            
            // Close connection
            mysqli_close($link);
            } 
    }else{
        // Check existence of id parameter
        if(empty(trim($_GET["id"]))){
            // URL doesn't contain id parameter. Redirect to error page
            header("location: reservations.php");
            exit();
        }
    }
}
?>
<div class="grid-container">
    <div class="form-header">
        <h2>Are you sure you want to delete this reservation?</h2>
    </div>
    <div class=" tabs-content" data-tabs-content="example-tabs">
        <div class="tabs-panel is-active" id="delete-browser">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="grid-x grid-padding-x grid-padding-y">
                    <div class="alert alert-danger fade in">
                        <input type="hidden" name="id" value="<?php echo trim($_GET["id"]); ?>"/>
                            <button type="submit" class="btnSubmit">Delete</button>
                    </div>
                </div>
            </form>
        </div>   
    </div>      
    <a href="reservations.php">Go back to Reservation List</a>               
</div>
<?php
require_once '../footer.php';
