<?php
require_once 'header.php';
//session_start();
 
// Check if the user is already logged in, if yes then redirect him to home page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: home.php");
    exit;
}
 
require_once '../DAL/ConnectionManager.php';

// Define variables and initialize with empty values
$email = $password = "";
$email_err = $password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if email is empty
    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter email.";
    } else{
        $email = trim($_POST["email"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate credentials
    if(empty($email_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT ID, Email, Password,FirstName,LastName,Role FROM Users WHERE Email = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            
            // Set parameters
            $param_email = $email;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);
                
                // Check if username exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $email, $hashed_password,$firstName,$lastName,$role);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            // Password is correct, so start a new session
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["email"] = $email;  
                            $_SESSION["name"] = $firstName." ".$lastName;  
                            $_SESSION["role"] = $role;                            
                            
                            // Redirect user to home page
                            header("location: home.php");
                        } else{
                            // Display an error message if password is not valid
                            $password_err = "The password you entered was not valid.";
                        }
                    }
                } else{
                    // Display an error message if username doesn't exist
                    $email_err = "No account found with that email.";
                }
            } else{
                echo "Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($link);
}
?>

<div class="grid-container">
    <div class="registration-container">
    <div class="form-header">
        <h2>Login</h2>
    </div>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-container">
            <div class="grid-x grid-margin-x grid-padding-y grid-margin-x">
                <div class="cell medium-3" style="text-align:right">
                    <label>Email:</label>
                </div>
                <div class="cell medium-8">
                    <input type="text" name="email" value="<?php echo $email; ?>">
                    <span class="validation"><?php echo $email_err; ?></span>
                </div>
            </div>
            <div class="grid-x grid-margin-x grid-margin-y">
                <div class="cell medium-3" style="text-align:right">
                    <label>Password:</label>
                </div>
                <div class="cell medium-8">
                    <input type="password" name="password">
                <span class="validation"><?php echo $password_err; ?></span>
                </div>
            </div>
            <div class="grid-x">
                <div class="cell medium-6">
                    <button type="submit" class="btnSubmit">Login</button> 
                </div>
            </div>
        </div>       
    </form>
</div>
</div>

<?php
require_once 'footer.php';

