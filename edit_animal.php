<html>
	<head>
		<title>Edit Animal - Adoption Management</title>
		<meta charset="UTF-8"/>
	</head>
	<body>

    <h1>Edit Animal</h1>

    <?php
        /*** DATABASE  ***/
        // Database connection
        require 'dbConnection.php';

        /** SESSION  ***/
        session_start();
        $valid_session = require 'check_session.php';

        /** USER AUTHENTICATION ***/
        // check if user logged in
        $valid_login = require 'check_login.php';

        // if valid login or valid session (user logged in previously)
        if($valid_login || $valid_session) {

            // check information is supplied
            if (!isset($_GET['animalid']) || empty($_GET['animalid'])) {
                echo "Error: Animal ID not supplied.";
                $db->close();
                exit;
            }

            // set id to variable
            $animal_id = $_GET['animalid'];

            // decalre animal types
            $animalTypesArray = array('Dog', 'Cat', 'Bird');
            $animalSexArray = array('Male', 'Female');
            $animalDesexedArray = array('Yes', 'No');

            // if submit button pressed in form in html
            if (isset($_POST['submit'])) {

                $submit = $_POST['submit'];

                // if submit value is cancel
                if ($submit == "Cancel") {
                    $db->close();
                    header('location: home.php');
                    exit; // exit php code
                }   		

                if (!isset($_POST['name']) || empty($_POST['name'])) {
                    echo "Error: Name not supplied.";
                    $db->close();
                    exit;
                }

                if (!isset($_POST['animal_type']) || empty($_POST['animal_type'])) {
                    echo "Error: Animal Type not supplied.";
                    $db->close();
                    exit;
                }

                if (!isset($_POST['adoption_fee']) || empty($_POST['adoption_fee'])) {
                    echo "Error: Adoption Fee not supplied.";
                    $db->close();
                    exit;
                }

                if (!isset($_POST['sex']) || empty($_POST['sex'])) {
                    echo "Error: Sex not supplied.";
                    $db->close();
                    exit;
                }

                if (!isset($_POST['desexed']) || empty($_POST['desexed'])) {
                    echo "Error: Desexed value not supplied.";
                    $db->close();
                    exit;
                }

                // prepare variables
                $name = $_POST['name'];
                $type = $_POST['animal_type'];
                $fee = $_POST['adoption_fee'];
                $sex = $_POST['sex'];
                $desexed = $_POST['desexed'];

                // convert desexed values of yes/no back to 1 and 0
                if($desexed == "Yes") {
                    $desexed = 1;
                } else {
                    $desexed = 0;
                }

                // prepare update query
                $query_update_animal_details = "UPDATE animal 
                SET name = ?,
                    animal_type = ?,
                    adoption_fee = ?,
                    sex = ?,
                    desexed = ?
                WHERE animalid = ?";

                // run query
                $stmt_update_animal_details = $db->prepare($query_update_animal_details);
                $stmt_update_animal_details->bind_param("ssisii", $name, $type, $fee, $sex, $desexed, $animal_id);
                $stmt_update_animal_details->execute();
    
                // determine query result
                $update_animal_stmt_affected_rows = $stmt_update_animal_details->affected_rows;

                // close query
                $stmt_update_animal_details->close();
    
                // if rows were changed (success)
                if($update_animal_stmt_affected_rows ==1) {
                    echo "Successfully Updated Animal<br>";
                    echo "<a href=\"home.php\">Back to Animal List</a>";
                    echo "<br><hr>";
                } else { 
                    echo "Failed to Update Animal<br>";
                    echo "<a href=\"home.php\">Back to Animal List</a>";
                    echo "<br><hr>";
                }
            } else {
                
                // prepare get animal query
                $query_animal_details = "SELECT * FROM animal WHERE animalid = ?";
                $stmt_animal_details = $db->prepare($query_animal_details);
                $stmt_animal_details->bind_param("i", $animal_id);
                $stmt_animal_details->execute();

                // store results
                $result = $stmt_animal_details->get_result();
                $stmt_animal_details->close(); // close when finished

                $row = $result->fetch_assoc();

                $name = $row['name'];
                $type = $row['animal_type'];
                $fee = $row['adoption_fee'];
                $sex = $row['sex'];
                $desexed = $row['desexed'];

                // replace 0 and 1 with no and yes
                if ($desexed == 0) {
                    $desexed = "No";
                } else if ($desexed == 1) {
                    $desexed = "Yes";
                } else {
                    $desexed = "Unknown";
                }

                $result->free(); // finished with result
            }
        } else {
            header ("Location: login.php");
        }
       
    $db->close(); // finished with db
?>        
        <form action="" method="POST">
            <table>
                <tr>
                    <td>Animal Name:</td>
                    <td><input type="text" name="name" value=<?php echo "$name";?>></td>
                </tr>
                <tr>
                    <td>Type:</td>
                    <td>
                        <select name="animal_type">
                            <?php 
                                // loop through array
                                foreach($animalTypesArray as $key => $value) {

                                    // if value of current array item is the same as the animal type
                                    if ($value == $type) {
                                        echo "<option value=\"$value\" selected>$value</option> "; // set option as selected
                                    } else {
                                        echo "<option value=\"$value\" >$value</option> "; // do not set option as selected
                                    } 
                                } // end loop
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Adoption Fee ($): </td>
                    <td><input type="text" name="adoption_fee" value=<?php echo "$fee";?>></td>
                </tr>
                <tr>
                    <td>Sex: </td>
                    <td>
                        <select name="sex">
                            <?php 
                                // loop through array
                                foreach($animalSexArray as $key => $value) {

                                    // if value of current array item is the same as the animal sex
                                    if($value == $sex) {
                                        echo "<option value=\"$value\" selected>$value</option> "; // set option as selected
                                    } else {
                                        echo "<option value=\"$value\" >$value</option> "; // do not set option as selected
                                    }
                                } // end loop
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Desexed?</td>
                    <td>
                        <select name="desexed">
                            <?php
                                // loop through array
                                foreach($animalDesexedArray as $key => $value) {

                                    // if value of current array item is the same as the desexed value of the animal
                                    if($value == $desexed) {
                                        echo "<option value=\"$value\" selected>$value</option> "; // set option as selected
                                    } else {
                                        echo "<option value=\"$value\" >$value</option> "; // do not set option as selected
                                    }
                                } // end loop
                            ?>
                        </select>
                    </td>
                </tr>
            </table>
            <br>
            <input type="hidden" name="animalid" value=<?php echo "$animal_id";?>>
            <input type="submit" name="submit" value="Update">
            <input type="submit" name="submit" value="Cancel">
        </form>
        <?php 
            /* should not need to check login as the php above checks
            so if code has reached this step, the user is logged in */
            include 'footer_logged_in.php';
        ?>
    </body>
</html>