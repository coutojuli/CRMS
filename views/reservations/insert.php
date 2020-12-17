<?php

require_once '../header.php';
require_once '../../DAL/ConnectionManager.php';

// Check existence of id parameter before processing further
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    $carid =  trim($_GET["id"]);
    var_dump($carid);
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
                $_SESSION["carname"] = $name;
                $_SESSION["carprice"] = $price;

                if ($availability == "Not Available"){
                    $error = "Error";
                   // header("location: reservations.php");
                    header("location: http://localhost/N01348498_PhpProject/views/cars/cars.php?error=".$error);
                }
            }
                else{
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
}

// Define variables and initialize with empty values
$name =  $carname = $pickup = $dropoff = $location = $insurance = $price = "";
$name_err = $car_err = $pickup_err = $dropoff_err = $location_err = $insurance_err = $price_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validations
    $carid = trim($_POST["id"]);

    $input_name = trim($_POST["name"]);
    if(empty($input_name)){
        $name_err = "Please enter a customer name.";
    } else{
        $name = $input_name;
    }
    
    $input_car = trim($_POST["car"]);
    if(empty($input_car)){
        $car_err = "Please enter a car name.";     
    } else{
        $carname = $input_car;
    }

    $now = strtotime((new DateTime())->format('Y-m-d'));
    $pickupdate = strtotime(trim($_POST["pickup"]));
    $dropoffdate = strtotime(trim($_POST["dropoff"]));

    $input_pickup = trim($_POST["pickup"]);
    if(empty($input_pickup)){
        $pickup_err = "Please enter a pickup date";     
    } else if ($pickupdate < $now){
        $pickup_err = "Pick up date cannot be less than today date";    
    }else if ($pickupdate > $dropoffdate){
        $pickup_err = "Pick up date cannot be less than dropoff date";
    }else{
        $pickup = $input_pickup;
        $pickup_err = "";
    }

    $input_dropoff = trim($_POST["dropoff"]);
    if(empty($input_dropoff)){
        $dropoff_err = "Please enter a dropoff date.";
    }else if ($dropoffdate < $now){
        $dropoff_err = "Drop off date cannot be less than today date";
    }else if ($dropoffdate < $pickupdate){
        $dropoff_err = "Drop off date cannot be less than pickup date";
    }else{
        $dropoff = $input_dropoff;
        $dropoff_err = "";
    }


    $input_location = trim($_POST["location"]);
    if(empty($input_location)){
        $location_err = "Please enter the location";  
    }else{
        $location = $input_location;
    }

    $input_insurance = trim($_POST["insurance"]);
    if(empty($input_insurance)){
        $insurance_err = "Please enter the insurance.";     
    } else{
        $insurance =  $input_insurance;
    }

    $input_price = trim($_POST["price"]);
    if(empty($input_price)){
        $price_err = "Please enter the reservation price";     
    } else{
        $price = $input_price;
    }

    // Check input errors before inserting in database
    if(empty($name_err) && empty($car_err) && empty($pickup_err) && empty($dropoff_err) && empty($location_err) && empty($insurance_err) && 
    empty($price_err)){

        // Prepare an insert statement
        $sql = "INSERT INTO Reservations (CarID, CustomerName, CarName, Pickup, DropOff, Location, Insurance, Price) VALUES (?,?, ?, ?, ?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssssss",  $param_carid, $param_name, $param_car, $param_up, $param_off, $param_location, $param_insurance, 
            $param_price);
            
            // Set parameters
            $param_carid = $carid;
            $param_name = $name;
            $param_car = $carname;
            $param_up = $pickup;
            $param_off = $dropoff;
            $param_location = $location;
            $param_insurance = $insurance;
            //$param_price = $carprice;

            //Set date
            $expected_price ="";
            $date1 = new DateTime($pickup);
            $date2 = new DateTime("$dropoff");
            $interval = $date1->diff($date2);
            $days = $interval->days;
            $expected_price = $days * $price;
            $param_price = $expected_price;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_close($stmt);
                // Records created successfully. Redirect to landing page
                $sql1 = "UPDATE Cars SET Availability=? WHERE ID=?";   
                if($stmt = mysqli_prepare($link, $sql1)){
                    // Bind variables to the prepared statement as parameters
                    mysqli_stmt_bind_param($stmt,"si",$param_av,$param_carid);
                    $param_av = "Not Available";
                    $param_carid = $_POST['id'];
                    
                    if(mysqli_stmt_execute($stmt)){
                        header("location: reservations.php");
                        exit();
                    }
                }

            } else{
                echo "Something went wrong. Please try again later.";
            }
            
        }
         
       // Close statement      
       mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
}
?>

<div class="grid-container">
    <div class="grid-x fluid">
        <div class="cell auto table-scroll">
            <div class="form-header">
                <h2>Create Reservation</h2>
            </div>
            <div class=" tabs-content" data-tabs-content="example-tabs">
                <div class="tabs-panel is-active" id="insert-browser">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <input type="hidden" name="id" value="<?php echo $carid; ?>"/>
                    <div class="grid-x grid-margin-x grid-padding-y">
                        <div class="cell medium-2">
                            
                            <label>Email:</label>
                        </div>
                        <div class="cell medium-4">
                        <input type="text" name="name" readonly value="<?php echo $_SESSION['email']; ?>">
                            <span class="validation"><?php echo $name_err;?></span>
                        </div>
                        <div class="cell medium-2">
                            <label>Car Name:</label>
                        </div>
                        <div class="cell medium-4">
                        <input type="text" name="car" readonly value="<?php echo $_SESSION["carname"] ?>">
                            <span class="validation"><?php echo $car_err;?></span>
                        </div>
                    </div>
                    <div class="grid-x grid-margin-x grid-margin-y">
                        <div class="cell medium-2">
                            <label>Pick-up Date:</label>
                        </div>
                        <div class="cell medium-4">
                        <input type="text" name="pickup" placeholder="2020-06-26" value="<?php echo $pickup; ?>">
                            <span class="validation"><?php echo $pickup_err;?></span>
                        </div>
                        <div class="cell medium-2">
                            <label>Drop-off Date:</label>
                        </div>
                        <div class="cell medium-4">
                            <input type="text" name="dropoff" placeholder="2020-06-27" value="<?php echo $dropoff; ?>">
                            <span class="validation"><?php echo $dropoff_err;?></span>
                        </div>
                    </div>
                    <div class="grid-x grid-margin-x grid-margin-y">
                        <div class="cell medium-2">
                            <label>Location:</label>
                        </div>
                        <div class="cell medium-4">
                            <select name="location" value="<?php echo $location; ?>">>
                                <option value="Downtown Store">Downtown Store</option>
                                <option value="Brampton Store">Brampton Store</option>
                                <option value="Markham Store">Markham Store</option>
                            </select>
                            <span class="validation"><?php echo $location_err;?></span>
                        </div>
                        <div class="cell medium-2">
                            <label>Insurance: </label>
                        </div>
                        <div class="cell medium-4">
                            <select name="insurance" value="<?php echo $insurance; ?>">>
                                <option value="Own Insurance">Own Insurance</option>
                                <option value="Company Insurance">Company Insurance</option>
                            </select>
                            <span class="validation"><?php echo $insurance_err;?></span>
                        </div>
                    </div>
                    <div class="grid-x grid-margin-x grid-margin-y">
                        <div class="cell medium-2">
                            <label>Reservation Price per Day: </label>
                        </div>
                        <div class="cell medium-4">
                            <input readonly type="text" name="price" value="<?php echo $_SESSION['carprice']; ?>">
                            <span class="validation"><?php echo $price_err;?></span>
                        </div><br>
                    </div>
                    <div class="grid-x grid-margin-x grid-margin-y">
                            <div class="cell medium-3">
                                <button type="submit" class="btnSubmit">Reserve</button>
                            </div>
                    </div>
                        </div>
                    </div>
                </form>
            </div>
        </div><br>
            <p>
                <a href="reservations.php">Go back to Reservation List</a>
            </p>
    </div>
</div>
<?php
@require_once '../footer.php';