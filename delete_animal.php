<html>
	<head>
		<title>Delete Animal - Adoption Management</title>
		<meta charset="UTF-8"/>
	</head>
	<body>

    <h1>Delete Animal</h1>

    <?php
        /*** DATABASE ***/
        // Database connection
        require 'dbConnection.php';

        /*** SESSION ***/
        // session control
        session_start();
        $valid_session = require 'check_session.php';

        /** USER AUTHENTICATION ***/
        // check if user logged in
        $valid_login = require 'check_login.php';

        // if valid login or valid session (logged in previously)
        if($valid_login || $valid_session) {

            // check information is supplied
            if (!isset($_GET['animalid']) || empty($_GET['animalid'])) {
                echo "Error: Animal ID not supplied.";
                $db->close();
                exit;
            }

            // set id to variable
            $animal_id = $_GET['animalid'];

            // if submit button pressed in form in html
            if (isset($_POST['submit'])) {

                $submit = $_POST['submit'];

                // if submit button value is cancel
                if ($submit == "Cancel") {
                    $db->close();
                    header('location: home.php');
                    exit;
                }		

            // prepare delete query
            $query_delete_animal = "DELETE FROM animal WHERE animalid = ?";

            // run query
            $stmt_delete_animal = $db->prepare($query_delete_animal);
            $stmt_delete_animal->bind_param("i", $animal_id);
            $stmt_delete_animal->execute();
            
            // determine query result
            $delete_animal_stmt_affected_rows = $stmt_delete_animal->affected_rows;

            // close query
            $stmt_delete_animal->close();
            
            // if rows were changed (success)
            if($delete_animal_stmt_affected_rows ==1) {
                echo "Successfully Delete Animal<br>";
                echo "<a href=\"home.php\">Back to Animal List</a>";
                echo "<br><hr>";
            } else { 
                echo "Failed to Delete Animal<br>";
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


                // display output within php to prevent errors upon deletion 
                // error if trying to show data that has now been deleted
                echo <<<END
                <form action="" method="POST">
                <table>
                    <tr>
                        <td>Animal Name:</td>
                        <td>$name</td>
                    </tr>
                    <tr>
                        <td>Animal Type:</td>
                        <td>$type</td>
                    </tr>
                    <tr>
                        <td>Adoption Fee: </td>
                        <td>$$fee</td>
                    </tr>
                    <tr>
                        <td>Sex: </td>
                        <td>$sex</td>
                    </tr>
                    <tr>
                        <td>Desexed?</td>
                        <td>$desexed</td>
                    </tr>
                </table>
                <br>
                <input type="hidden" name="animalid" value="$animal_id">
                <input type="submit" name="submit" value="Delete">
                <input type="submit" name="submit" value="Cancel">
                </form>
END;

                $result->free(); // finished with result
            }
        } else {
            header ("Location: login.php");
        }
        $db->close(); // finished with db

        // if code reaches this stage, the user is already logged in
        include 'footer_logged_in.php';
    ?>        
        
    </body>
</html>