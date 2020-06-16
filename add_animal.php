<html>
	<head>
		<title>Add Animal - Adoption Management</title>
		<meta charset="UTF-8"/>
	</head>
	<body>

    <h1>Add Animal</h1>

    <?php
        /*** DATABASE ***/

        // Database connection
        require 'dbConnection.php';

        /*** SESSION ***/
        session_start();
        $valid_session = require 'check_session.php';

        /*** USER LOGIN */

        // check if user logged in
        $valid_login = require 'check_login.php';

        // if valid login (user has logged in) or 
        // valid session (user has logged in previously)
        if($valid_login || $valid_session ) {
             
            // decalre animal types
            $animalTypesArray = array('Dog', 'Cat', 'Bird');
            $animalSexArray = array('Male', 'Female');
            $animalDesexedArray = array('Yes', 'No');

            // if submit button pressed in form in html
            if (isset($_POST['submit'])) {

                $submit = $_POST['submit'];

                // if the submit button value is cancel 
                if ($submit == "Cancel") {
                    $db->close();
                    header('location: home.php');
                    exit;
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

                // prepare insert query
                $query_add_animal = "INSERT INTO animal (name, animal_type, adoption_fee, sex, desexed)
                                        VALUES (?, ?, ?, ?, ?)";

                // run query
                $stmt_add_animal = $db->prepare($query_add_animal);
                $stmt_add_animal->bind_param("ssisi", $name, $type, $fee, $sex, $desexed);
                $stmt_add_animal->execute();
            
                // determine query result
                $add_animal_stmt_affected_rows = $stmt_add_animal->affected_rows;

                // close query
                $stmt_add_animal->close();
            
                // if rows were changed (success)
                if($add_animal_stmt_affected_rows ==1) {
                    echo "Successfully Added Animal<br>";
                    echo "<a href=\"home.php\">Back to Animal List</a>";
                    echo "<br><hr>";
                } else { 
                    echo "Failed to Add Animal<br>";
                    echo "<a href=\"home.php\">Back to Animal List</a>";
                    echo "<br><hr>";
                }

            }
        } 
        // else if user not logged in or not valid sesssion
        else {
            header( "Location: login.php" ); // redirect to login
        }

        $db->close(); // finished with db

    ?>        
        <form action="" method="POST">
            <table>
                <tr>
                    <td>Animal Name:</td>
                    <td><input type="text" name="name"></td>
                </tr>
                <tr>
                    <td>Type:</td>
                    <td>
                        <select name="animal_type">
                            <?php 
                                // loop through array
                                foreach($animalTypesArray as $key => $value) {
                                    echo "<option value=\"$value\" >$value</option> "; 
                                } // end loop
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Adoption Fee ($): </td>
                    <td><input type="text" name="adoption_fee" ></td>
                </tr>
                <tr>
                    <td>Sex: </td>
                    <td>
                        <select name="sex">
                            <?php 
                                // loop through array
                                foreach($animalSexArray as $key => $value) {
                                    echo "<option value=\"$value\" >$value</option> "; 
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
                                    echo "<option value=\"$value\" >$value</option> "; 
                                } // end loop
                            ?>
                        </select>
                    </td>
                </tr>
            </table>
            <br>
            
            <input type="submit" name="submit" value="Add">
            <input type="submit" name="submit" value="Cancel">
        </form>
        <?php 
            /* should not need to check login as the php above checks
            so if code has reached this step, the user is logged in */
            include 'footer_logged_in.php';
        ?>
    </body>
</html>