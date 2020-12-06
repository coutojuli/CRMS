<?php
require_once '../header.php';
require_once '../../DAL/ConnectionManager.php';
 
// Define variables and initialize with empty values
$name = $type = $level = $bag = $passenger = $transmission = $price = $availability = $interior = $radio = $air = "";
$door = $wheel = $control = $mirror = $bluetooth = "";
$name_err = $type_err = $level_err = $bag_err = $passenger_err = $transmission_err = $price_err = $availability_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validations
    $input_name = trim($_POST["name"]);
    if(empty($input_name)){
        $name_err = "Please enter a car name.";
    } else{
        $name = $input_name;
    }
    
    $input_type = trim($_POST["type"]);
    if(empty($input_type)){
        $type_err = "Please enter a type.";     
    } else{
        $type = $input_type;
    }

    $input_level = trim($_POST["level"]);
    if(empty($input_level)){
        $level_err = "Please enter the car level";     
    } else{
        $level = $input_level;
    }

    $input_bag = trim($_POST["bag"]);
    if(empty($input_bag)){
        $bag_err = "Please enter the bag capacity.";
    }elseif(!ctype_digit($input_bag)){
        $bag_err = "Please enter a positive integer value.";  
    } else{
        $bag = $input_bag;
    }

    $input_passenger = trim($_POST["passenger"]);
    if(empty($input_level)){
        $passenger_err = "Please enter the passenger capacity";  
    }elseif(!ctype_digit($input_passenger)){
        $passenger_err = "Please enter a positive integer value.";     
    } else{
        $passenger = $input_passenger;
    }

    $input_transmission = trim($_POST["transmission"]);
    if(empty($input_transmission)){
        $transmission_err = "Please enter the transmission type.";     
    } else{
        $transmission = $input_transmission;
    }

    $input_price = trim($_POST["price"]);
    if(empty($input_price)){
        $price_err = "Please enter the reservation price";     
    } else{
        $price = $input_price;
    }

    $input_availability = trim($_POST["availability"]);
    if(empty($input_availability)){
        $availability_err = "Please enter the car availability";     
    } else{
        $availability = $input_availability;
    }

    if(!isset($_POST['interior'])) {
        $interior = "No";    
    } else{
        $interior = $input_interior;
    }
    
    if(!isset($_POST['radio'])) {
        $radio = "No";   
    } else{
        $radio = "Yes";
    }

    if(!isset($_POST['air'])) {
        $air = "No";    
    } else{
        $air = "Yes";
    }

    if(!isset($_POST['door'])) {
        $door = "No";        
    } else{
        $door = "Yes";
    }

    if(!isset($_POST['wheel'])) {
        $wheel = "No";   
    } else{
        $wheel = "Yes";
    }

    if(!isset($_POST['control'])) {
        $control = "No";     
    } else{
        $control = "Yes";
    }

    if(!isset($_POST['mirror'])) {
        $mirror = "No";       
    } else{
        $mirror = "Yes";
    }

    if(!isset($_POST['bluetoof'])) {
        $bluetooth = "No";      
    } else{
        $bluetooth = "Yes";
    }
    
    // Check input errors before inserting in database
    if(empty($name_err) && empty($type_err) && empty($level_err) && empty($bag_err) && empty($passenger_err) && empty($transmission_err) && 
    empty($price_err) && empty($availability_err)){

        // Prepare an insert statement
        $sql = "INSERT INTO Cars (Name, Type, Level, BagCapacity, PassengerCapacity, Transmission, Price, Availability, LeatherInterior, Radio, AirConditioning, PowerLockDoor, TiltSteeringWheel,
        CruiseControl, PowerMirrors, Bluetooth ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssssssssssssss", $param_name, $param_type, $param_level, $param_bag, $param_passenger, $param_transmission, 
            $param_price, $param_availability, $param_interior, $param_radio, $param_air, $param_door, $param_wheel, $param_control, 
            $param_mirror, $param_bluetooth);
            
            // Set parameters
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
            $param_mirror = $mirror;
            $param_bluetooth = $bluetooth;
            
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
                header("location: cars.php");
                exit();
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
                <h2>Car Insert</h2>
            </div>
            <div class=" tabs-content" data-tabs-content="example-tabs">
                <div class="tabs-panel is-active" id="insert-browser">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div>
                            <div class="grid-x grid-margin-x grid-padding-y">
                                <div class="cell medium-2">
                                    <label>Vehicle Name:</label>
                                </div>
                                <div class="cell medium-4">
                                    <input type="text" name="name" value="<?php echo $name; ?>">
                                    <span class="validation"><?php echo $name_err;?></span>
                                </div>
                                <div class="cell medium-2">
                                    <label>Vehicle Type:</label>
                                </div>
                                <div class="cell medium-4">
                                    <select name="type" value="<?php echo $type; ?>">
                                        <option value="SUV">SUV</option>
                                        <option value="Truck">Truck</option>
                                        <option value="Van">Van</option>
                                    </select>
                                    <span class="validation"><?php echo $type_err;?></span>
                                </div>
                            </div>
                            <div class="grid-x grid-margin-x grid-margin-y">
                                <div class="cell medium-2">
                                    <label>Vehicle Level:</label>
                                </div>
                                <div class="cell medium-4">
                                    <select name="level" value="<?php echo $level; ?>">>
                                        <option value="Compact">Compact</option>
                                        <option value="Intermediate">Intermediate</option>
                                        <option value="Standard">Standard</option>
                                        <option value="Full Size">Full Size</option>
                                        <option value="Premium">Premium</option>
                                        <option value="Elite">Elite</option>
                                        <option value="Crossover">Crossover</option>
                                        <option value="Jeep">Jeep</option>
                                    </select>
                                    <span class="validation"><?php echo $level_err;?></span>
                                </div>
                                <div class="cell medium-2">
                                    <label>Bag Capacity:</label>
                                </div>
                                <div class="cell medium-4">
                                <input type="text" name="bag" value="<?php echo $bag; ?>">
                                <span class="validation"><?php echo $bag_err;?></span>
                                </div>
                            </div>
                            <div class="grid-x grid-margin-x grid-margin-y">
                                <div class="cell medium-2">
                                    <label>Passenger Capacity:</label>
                                </div>
                                <div class="cell medium-4">
                                <input type="text" name="passenger" value="<?php echo $passenger; ?>">
                                <span class="validation"><?php echo $passenger_err;?></span>
                                </div>
                                <div class="cell medium-2">
                                    <label>Transmission:</label>
                                </div>
                                <div class="cell medium-4">
                                    <select name="transmission" value="<?php echo $transmission; ?>">
                                        <option value="Manual">Manual</option>
                                        <option value="Automatic">Automatic</option>
                                    </select>
                                    <span class="validation"><?php echo $transmission_err;?></span>
                                </div>
                            </div>
                            <div class="grid-x grid-margin-x grid-margin-y">
                                <div class="cell medium-2">
                                    <label>Reservation Price:</label>
                                </div>
                                <div class="cell medium-4">
                                <input type="text" name="price" value="<?php echo $price; ?>">
                                <span class="validation"><?php echo $price_err;?></span>
                                </div>
                                <div class="cell medium-2">
                                    <label>Availability:</label>
                                </div>
                                <div class="cell medium-4">
                                <input type="text" name="availability" value="<?php echo $availability; ?>">
                                <span class="validation"><?php echo $availability_err;?></span>
                                </div>
                            </div>
                            <fieldset>
                                <div class="grid-x grid-padding-y grid-margin-y">
                                    <div class="medium-11 cell">
                                        <legend>Vehicle Features:</legend><br>
                                        <input id="interior" name="interior" type="checkbox"><label for="interior">Leather
                                            Interior</label>                                 
                                        <input id="radio" name="radio" type="checkbox"><label for="radio">AM/FM Stereo
                                            Radio</label>
                                        <input id="air" name="air" type="checkbox"><label for="conditioning">Air
                                            Conditioning</label>
                                        <input id="door" name="door" type="checkbox"><label for="doors">Power Lock
                                            Doors</label>
                                        <input id="control" name="control" type="checkbox"><label for="control1">Cruise
                                            Control</label>
                                        <input id="wheel" name="wheel" type="checkbox"><label for="wheel">Tilt Steering
                                            Wheel</label>
                                        <input id="mirror" name="mirror" type="checkbox"><label for="mirrors">Power
                                            Mirrors</label>
                                        <input id="bluetooth" name="bluetooth" type="checkbox"><label for="bluetooth">Bluetooth</label>
                                    </div>
                                </div><br>
                                <div class="grid-x">
                                    <div class="cell medium-3">
                                        <button type="submit" class="btnSubmit">Create</button>
                                        <button type="clear" class="btnSubmit">Clear</button>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </form>
                </div>
            </div><br>
            <p>
                <a href="update.php">Edit</a> |
                <a href="cars.php">Go back to List</a>
            </p>
        </div>
    </div>
</div>

<?php
require_once '../footer.php';
