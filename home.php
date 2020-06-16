<html>
	<head>
		<title>Home - Adoption Management</title>
		<meta charset="UTF-8"/>
	</head>
	<body>

    <h1>Adoption Management Dashboard</h1>
    
    <?php
        /**** DATABASE ****/

        // Database connection
        require 'dbConnection.php';

        /**** SESSION ****/

        // session control 
        session_start();
		$valid_session = require('check_session.php');

        /**** USER LOGIN ****/

        // check if user logged in
        $valid_login = require 'check_login.php';

        /* variable will be set to true if the session is valid (logged in) 
        OR if the user logged in.
        Variable is declared outside if statement so is global accesss */
        $user_logged_in = false;

        /**** PAGE ****/
        // show welcome message if necessary
        if ($valid_login || $valid_session) {
            $name = $_SESSION['valid_user'];
            echo "<p>Welcome, $name</p>";
            $user_logged_in = true;
        } else {
            $user_logged_in = false;
        }

        // Setup query SELECT * Query
        $query = "SELECT * FROM animal ORDER BY animal_type, name";
        $result = $db->query($query);
        $num_results = $result->num_rows;

        // create the table to display results
        create_table($user_logged_in);

        // loop through query results
        for ($i = 0; $i < $num_results; $i++) {

            // fetch values
            $row = $result->fetch_assoc();
            $id = $row['animalid'];
            $name = $row['name'];
            $type = $row['animal_type'];
            $fee = "$" . $row['adoption_fee'];
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

            // add results to table and pass if the user is logged in - to add button or not
            add_to_table($id, $name, $type, $fee, $sex, $desexed, $user_logged_in);            
        }

    // close results and database
    $result->free();
    $db->close();

    function create_table($user_logged_in) {
        if ($user_logged_in == true) {
            echo <<<END
            <table border="1">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Animal Type</th>
                        <th>Adoption Fee</th>
                        <th>Sex</th>
                        <th>Desexed?</th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
END;
        } else {
            echo <<<END
            <table border="1">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Animal Type</th>
                        <th>Adoption Fee</th>
                        <th>Sex</th>
                        <th>Desexed?</th>
                    </tr>
                </thead>
END;
        }
    }

    function add_to_table($id, $name, $type, $fee, $sex, $desexed, $user_logged_in) {
        // add to table row - table data
        echo "<tr>";
        echo "<td>$name</td>";
        echo "<td>$type</td>";
        echo "<td>$fee</td>";
        echo "<td>$sex</td>";
        echo "<td>$desexed</td>";

        // check if user is logged in
        if ($user_logged_in == true) {
                create_column_button("animalid", $id, "Edit", "edit_animal.php");
                create_column_button("animalid", $id, "Delete", "delete_animal.php");
        } 

        // finish table row
        echo "</tr>";
    }

    function create_column_button($hidden_name, $hidden_value, $button_text, $action_page) {
        // create table data (button)
        echo "<td>";
        echo "<form action=$action_page method=\"GET\">";
        echo "<input type=\"hidden\" name=$hidden_name value=$hidden_value>";					
        echo "<button type=\"submit\">$button_text</button>";
        echo "</form>";			
        echo "</td>";
    }


    // close table 
    echo "</table>";

    // write footer
    if ($user_logged_in) {
        include 'footer_logged_in.php';
    } else {
        include 'footer_logged_out.php';
    }

    // END PHP
    ?>

   
	</body>
</html>