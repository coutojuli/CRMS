<?php
require_once '../header.php';
require_once '../../DAL/ConnectionManager.php';

if (isset($_GET['error'])){
    $err = "ERROR: Reservation for the chosen car was not possible. Car is not available. Please choose another car.";
}
?>
<div class="grid-container">
        <div class="grid-x">
            <img src="../../images/banner-cars.jpg" alt="Cars Banner" style="width: 100%;">
        </div><br>
        <div class="grid-container">
            <ul class="tabs" data-tabs id="example-tabs">
                <li class="tabs-title is-active"><a href="#search-browser" aria-selected="true">Car List</a>
                </li>
                <?php                           
                    if (isset($_SESSION['email'])){
                        if ($_SESSION['role'] == "Admin"){
                            echo '<li class="tabs-title"><a href="#search-forms">File System</a></li>';
                        }
                    }
                ?>                   
            </ul>
            <div class=" tabs-content" data-tabs-content="example-tabs">
                <div class="tabs-panel is-active" id="search-browser">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="GET">
                        <div class="grid-x grid-padding-x grid-padding-y">                       
                             <div class="cell medium-5">
                                <input type="search" name="search" id="search">
                            </div>
                            <div class="cell medium-3">
                                <select name="filter" id="filter">
                                    <option value="Name">Name</option>
                                    <option value="Type">Type</option>
                                    <option value="Level">Level</option>
                                    <option value="BagCapacity">Bag Capacity</option>
                                    <option value="PassengerCapacity">Passenger Capacity</option>
                                    <option value="Transmission">Transmission</option>                                    
                                    <option value="Price">Reservation Price</option>
                                    <option value="Availability">Availability</option>
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
                                if(empty($_GET["search"])){ 
                                    $sql = "SELECT * FROM Cars";
                                    $result = mysqli_query($link, $sql);
                                }
                                else{
                                    $search = $_GET["search"];
                                    $filter = $_GET["filter"];
                                    $sql = "SELECT * FROM Cars WHERE $filter LIKE ?";
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
                                if(isset($result)){                   
                                    if(mysqli_num_rows($result) > 0){
                                        if (!(empty($err))){
                                            echo "<span style='color:red;'>" . $err . "</span><br>";
                                        }
                                        echo '<table class="hover">';
                                            echo "<thead>";
                                                echo "<tr>";
                                                    echo "<th>Name</th>";
                                                    echo "<th>Type</th>";
                                                    echo "<th>Level</th>";
                                                    echo "<th>Passenger Capacity</th>";
                                                    echo "<th>Reservation Price</th>";
                                                    echo "<th>Availability</th>";
                                                    echo "<th>Action</th>";
                                                echo "</tr>";
                                            echo "</thead>";
                                            echo "<tbody>";
                                            
                                            while($row = mysqli_fetch_array($result)){
                                                echo "<tr>";
                                                echo "<td>" . $row['Name'] . "</td>";
                                                echo "<td>" . $row['Type'] . "</td>";
                                                echo "<td>" . $row['Level'] . "</td>";
                                                echo "<td>" . $row['PassengerCapacity'] . "</td>";
                                                echo "<td>" . $row['Price'] . "</td>";
                                                echo "<td>" . $row['Availability'] . "</td>";
                                                echo "<td>";                                      
                                                echo '<a href="details.php?id='. $row['ID'] .'">Details</a>';
                                                if (isset($_SESSION['email'])){
                                                        echo '| <a href="../reservations/insert.php?id='. $row['ID'] .'">Reserve</a>'; 
                                                        if ($_SESSION['role'] == "Admin"){
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
                                        // } else{
                                        // echo "<p class='lead'><em>No car records were found.</em></p>";
                                        // }
                                    } else{
                                        echo "ERROR: Not able to execute search. Please try again.";
                                    }
                                }                                             
                                // Close connection
                                mysqli_close($link); 
                            ?>
                        </div>
                    </div>
                </div>
                <?php                    
                    if (isset($_SESSION['email'])){
                        if ($_SESSION['role'] == "Admin"){
                            echo '<div class="tabs-panel"  id="search-forms">';
                                echo '<form action="../fileSystem.php" method="POST" enctype="multipart/form-data">';
                                    echo '<div class="grid-x grid-padding-y grid-margin-y">';
                                         echo '<div class="cell medium-1">';
                                            echo '<label for="fileUpload" class="btnSubmit">Upload File</label>';
                                            echo '<input type="file" id="fileUpload" name="fileUpload" class="show-for-sr">';
                                         echo '</div>';
                                         echo '<div class="cell medium-2">';
                                            echo '<button type="submit" class="btnSubmit" style="width:145px">Submit File</button>';
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
<?php
require_once '../footer.php';



