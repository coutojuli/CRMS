<?php
require_once 'header.php';
require_once '../DAL/ConnectionManager.php';

//Valiable Initialization
$email = $password = $confirmPassword = $firstName = $lastName = $role = "";
$email_err = $password_err = $confirm_password_err = $firstName_err = $lastName_err =  "";

//Form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
   
    //Email Validation
    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter a email.";
    } else{
         $sql = "SELECT ID FROM Users WHERE Email = ?";

         if($statement = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($statement, "s", $param_email);
            
            // Set parameters
            $param_email = trim($_POST["email"]);

            if(mysqli_stmt_execute($statement)){
                mysqli_stmt_store_result($statement);
                
                if(mysqli_stmt_num_rows($statement) == 1){
                    $email_err = "This email is already taken.";
                } else{
                    $email = trim($_POST["email"]);
                }
            } else{
                echo "Something went wrong. Please try again later.";
            }

            mysqli_stmt_close($statement);
        }
    }
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 5){
        $password_err = "Password must have atleast 5 characters.";
    } else{
        $password = trim($_POST["password"]);
    }
    
     // Validate confirm password
     if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }

    //First Name and Last Name validation
    if(empty(trim($_POST["firstName"]))){
        $firstName_err = "Please enter a first name.";
    } else{
        $firstName = trim($_POST["firstName"]);
    }
    if(empty(trim($_POST["lastName"]))){
        $lastName_err = "Please enter a first name.";
    } else{
        $lastName = trim($_POST["lastName"]);
    }

    //Default Role: User
    $role = "User";

    // Check input errors before inserting in database
    if(empty($email_err) && empty($password_err) && empty($confirm_password_err  && empty($firstName_err)  && empty($lastName_err))){
        // Prepare an insert statement
        $sql = "INSERT INTO Users (Email, Password, ConfirmPassword, FirstName, LastName, Role) VALUES (?, ?, ?, ?, ?, ?)";
         
        if($statement = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($statement, "ssssss", $param_email, $param_password, $param_confirm, $param_firstName, $param_lastName, $param_role);
            
            // Set parameters
            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT);
            $param_confirm = password_hash($confirm_password, PASSWORD_DEFAULT);
            $param_firstName = $firstName;
            $param_lastName = $lastName;
            $param_role = $role;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($statement)){
                // Redirect to login page
                header("location: login.php");
            } else{
                echo "Something went wrong. Please try again later.";
            }
            // Close statement
            mysqli_stmt_close($statement);
        }
    }
      // Close connection
      mysqli_close($link);
    }
?>

<div class="grid-container">
    <div class="registration-container">
    <div class="form-header">
        <h2>Registration</h2>
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
                    <input type="password" name="password" value="<?php echo $password; ?>">
                    <span class="validation"><?php echo $password_err; ?></span>
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
            <div class="grid-x grid-margin-x grid-margin-y">
                <div class="cell medium-3" style="text-align:right">
                    <label>First Name:</label>
                </div>
                <div class="cell medium-8">
                    <input type="text" name="firstName" value="<?php echo $firstName; ?>">
                    <span class="validation"><?php echo $firstName_err; ?></span>
                </div>
            </div>
            <div class="grid-x grid-margin-x grid-margin-y">
                <div class="cell medium-3" style="text-align:right">
                    <label>Last Name:</label>
                </div>
                <div class="cell medium-8">
                    <input type="text" name="lastName" value="<?php echo $lastName; ?>">
                    <span class="validation"><?php echo $lastName_err; ?></span>
                </div>
            </div><br>
            <div class="grid-x">
                <div class="cell medium-5">
                    <button type="submit" class="btnSubmit">Register</button>
                    <button type="reset" class="btnSubmit">Clear</button>
            </div>
        </div>
        
    </form>
</div>
</div>
</div>

<?php
require_once 'footer.php';

