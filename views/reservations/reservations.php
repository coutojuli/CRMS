<?php
require_once '../header.php';
require_once '../../DAL/ConnectionManager.php';

?>
    <div class="grid-container">
        <div class="grid-x">
            <img src="../../images/banner-reservations.jpg" alt="Cars Banner" style="width: 100%;">
        </div><br>
        <div class="grid-container">
            <ul class="tabs" data-tabs id="example-tabs">
                <li class="tabs-title is-active"><a href="#search-browser" aria-selected="true">Reservations</a></li>
                <?php                           
                    if (isset($_SESSION['email'])){
                        if ($_SESSION['role'] == "Admin"){
                            echo '<li class="tabs-title"><a href="#search-forms">File System</a></li>';
                        }
                    }
                ?>                        
            </ul>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="GET">
                        <div class="grid-x grid-padding-x grid-padding-y">                       
                             <div class="cell medium-5">
                                <input type="search" name="search" id="search">
                            </div>
                            <div class="cell medium-3">
                                <select name="filter" id="filter">
                                    <option value="CustomerName">Email</option>
                                    <option value="CarName">Car Name</option>
                                    <option value="Pickup">Pickup Date</option>
                                    <option value="DropOff">DropOff Date</option>
                                    <option value="Location">Location</option>
                                    <option value="Insurance">Insurance</option>                                    
                                    <option value="Price">Reservation Price</option>
                                </select>
                            </div>
                            <div class="cell medium-4">
                                <button type="submit" class="btnSubmit">Search</button>
                                
                                <?php                    
                                    if (isset($_SESSION['email'])){                                      
                                        if ($_SESSION['role'] == "Admin"){
                                            echo '<button type="button" class="btnSubmit" onclick="location.href=\'insert.php\'">Create</button>';
                                        }
                                    }
                                ?>                              
                            </div>
                        </div>
                    </form><br>
                    <div class="grid-x fluid">
                            <div class="cell auto table-scroll">
                            <?php
                                if (isset($_SESSION['email'])){ 
                                    if ($_SESSION['role'] == "Admin"){
                                        if(empty($_GET["search"])){ 
                                            $sql = "SELECT * FROM Reservations";
                                            $result = mysqli_query($link, $sql);
                                        }
                                        else{
                                            $search = $_GET["search"];
                                            $filter = $_GET["filter"];
                                            $sql = "SELECT * FROM Reservations WHERE $filter LIKE ?";
                                            if($stmt = mysqli_prepare($link, $sql)){
                                                // Bind variables to the prepared statement as parameters
                                                mysqli_stmt_bind_param($stmt, "s", $param_search);
                                                
                                                // Set parameters
                                                $param_search = "%".$search."%";
                                                if(mysqli_stmt_execute($stmt)){
                                                    $result = mysqli_stmt_get_result($stmt);
                                                }
                                            }                                   
                                        } 
                                    }
                                    elseif ($_SESSION['role'] == "User"){
                                        $email = $_SESSION["email"]; 
                                        $sql1 = "SELECT * FROM Reservations WHERE CustomerName = '$email'";
                                        
                                        if($stmt = mysqli_prepare($link, $sql1)){
                                            // Attempt to execute the prepared statement
                                            if(mysqli_stmt_execute($stmt)){
                                                $result = mysqli_stmt_get_result($stmt);
                                            }
                                        }
                                    }
                                    if(isset($result)){                   
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
                                                    echo '<a href="details.php?id='. $row['ID'] .'">Details</a>';
                                                    if (isset($_SESSION['email'])){ 
                                                        if ($_SESSION['role'] == "Admin" || $_SESSION['role'] == "User"){
                                                            echo '| <a href="update.php?id='. $row['ID'] .'">Edit</a>'; 
                                                            echo '| <a href="delete.php?id='. $row['ID'] .'">Delete</a>'; 
                                                        }
                                                    }
                                                    echo "</td>";
                                                    echo "</tr>";
                                                }
                                                echo "</tbody>";
                                                echo "</table>";
                                                // Free result set
                                            mysqli_free_result($result);
                                        } else{
                                            echo "ERROR: Not able to execute search. Please try again.";
                                        }
                                    }
                                    mysqli_close($link); 
                                }                                                                                               
                            ?>
                            </div>
                        </div>
                        <?php                    
                            if (isset($_SESSION['email'])){
                                if ($_SESSION['role'] == "Admin"){
                                    echo '<div class="tabs-panel" id="search-forms">';
                                    echo '<form action="../fileSystem.php" method="GET" enctype="multipart/form-data">';
                                    echo '<div class="grid-x grid-padding-y grid-margin-y">';
                                    echo '<div class="cell medium-1">>';
                                    echo '<label for="fileUpload" class="btnSubmit">Upload File</label>';
                                    echo '<input type="file" id="fileUpload" name="fileUpload" class="show-for-sr">';
                                    echo '</div>';
                                    echo '<button type="submit" class="btnSubmit" style="width:145px">Execute Actions</button>';
                                    echo '</div>';                  
                                    echo '</div>';             
                                    echo '</form>';   
                                    echo '</div>';
                                }
                            }
                        ?>                 
                </div>
            </div>
        </div>
    </div>
</div>    
<?php
require_once '../footer.php';
