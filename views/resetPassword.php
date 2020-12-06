<?php
require_once 'header.php';
// Initialize the session

// Check if the user is logged in, if not then redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
 
// Include config file
require_once "../DAL/ConnectionManager.php";
 
// Define variables and initialize with empty values
$new_password = $confirm_password = "";
$new_password_err = $confirm_password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate new password
    if(empty(trim($_POST["new_password"]))){
        $new_password_err = "Please enter the new password.";     
    } elseif(strlen(trim($_POST["new_password"])) < 5){
        $new_password_err = "Password must have atleast 5 characters.";
    } else{
        $new_password = trim($_POST["new_password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm the password.";
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($new_password_err) && ($new_password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
        
    // Check input errors before updating the database
    if(empty($new_password_err) && empty($confirm_password_err)){
        // Prepare an update statement
        $sql = "UPDATE Users SET Password = ? WHERE ID = ?";
        $sql1 = "UPDATE Users SET ConfirmPassword = ? WHERE ID = ?";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "si", $param_password, $param_id);
            
            // Set parameters
            $param_password = password_hash($new_password, PASSWORD_DEFAULT);
            $param_id = $_SESSION["id"];
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Password updated successfully. Destroy the session, and redirect to login page
                session_destroy();
                header("location: login.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
        if($stmt1 = mysqli_prepare($link, $sql1)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt1, "si", $param_password, $param_id);
            
            // Set parameters
            $param_password = password_hash($new_password, PASSWORD_DEFAULT);
            $param_id = $_SESSION["id"];
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt1)){
                // Password updated successfully. Destroy the session, and redirect to login page
                session_destroy();
                header("location: login.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt1);
        }
    }
    
    // Close connection
    mysqli_close($link);
}
?>
 
 <div class="grid-container">
    <div class="registration-container">
    <div class="form-header">
        <h2>Reset Password:</h2>
    </div>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
        <div class="form-container">
            <div class="grid-x grid-margin-x grid-padding-y grid-margin-x">
                <div class="cell medium-3" style="text-align:right">
                    <label>New Password:</label>
                </div>
                <div class="cell medium-8">
                    <input type="password" name="new_password" value="<?php echo $new_password; ?>">
                    <span class="validation"><?php echo $new_password_err; ?></span>
                </div>
            </div>
            <div class="grid-x grid-margin-x grid-margin-y">
                <div class="cell medium-3" style="text-align:right">
                    <label>Confirm Password:</label>
                </div>
                <div class="cell medium-8">
                    <input type="password" name="confirm_password">
                    <span class="validation"><?php echo $confirm_password_err; ?></span>
                </div>
            </div>
            <div class="grid-x">
                <div class="cell medium-6">
                    <button type="submit" class="btnSubmit">Reset</button>
                    <button type="button" class="btnSubmit" onclick="location.href='home.php'">Cancel</button>                
                </div>
            </div>
        </div>       
    </form>
</div>
</div>

<?php
require_once 'footer.php';

