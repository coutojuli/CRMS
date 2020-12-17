<?php
require_once '../header.php';
require_once '../../DAL/ConnectionManager.php';

// Process delete operation after confirmation
if(isset($_POST["id"]) && !empty($_POST["id"])){

    // Prepare a delete statement
    $sql = "DELETE FROM Cars WHERE id = ?";
    
    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        
        // Set parameters
        $param_id = trim($_POST["id"]);
        
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            // Records deleted successfully. Redirect to landing page
            header("location: cars.php");
            exit();
        } else{
            echo "Something went wrong. Please try again later.";
        }
    }
     
    // Close statement
    mysqli_stmt_close($stmt);
    
    // Close connection
    mysqli_close($link);
} else{
    // Check existence of id parameter
    if(empty(trim($_GET["id"]))){
        // URL doesn't contain id parameter. Redirect to error page
        header("location: cars.php");
        exit();
    }
}
?>
<div class="grid-container">
    <div class="form-header">
        <h2>Are you sure you want to delete the car with ID <?php echo trim($_GET["id"]);?>?</h2>
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
</div>
<?php
require_once '../footer.php';
