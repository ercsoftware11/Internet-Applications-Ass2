<?php

	// do not need any db connection as calls to this script are done 
	// through scrips that already have db setup - therefore
	// global db from another page will be used in db calls in this script

	// check username and password are set in POST Array
	if (isset($_POST['username']) || isset($_POST['password'])) {

		// check user name is set and is not inothing
		if (!isset($_POST['username']) || empty($_POST['username'])) {
			echo "Name not supplied";
			return false;
		}

		// check password is set and is not nothing
		if (!isset($_POST['password']) || empty($_POST['password'])) {
			echo "Password not supplied";
			return false;
		}

		// set username and password intro variables from array
		$name = $_POST['username'];
		$password = $_POST['password'];

		// prepare query
		$query_fetch_user = "SELECT count(*) FROM authorized_users 
        WHERE username=? AND password = sha1(?)";
		
		// prepare execution
		$stmt_fetch_user = $db->prepare($query_fetch_user);
		$stmt_fetch_user->bind_param("ss", $name, $password);
		$stmt_fetch_user->execute();
		
		// get results
		$result_fetch_user = $stmt_fetch_user->get_result();

		// close statement
		$stmt_fetch_user->close();

		// if couldn't fetch a result
		if (!$result_fetch_user) {
			echo "Couldn't check credentials";
			exit; // exit all php
		}
		
		// get data from result
		$row = $result_fetch_user->fetch_row();
		
		// free the results
        $result_fetch_user->free();
		
		// if number of rows of results > nothing - valid user
		if ($row[0] > 0) {
			$_SESSION['valid_user'] = $name;
			return true;
		}
		else {
			echo "Username and Password Incorrect<br>";
			echo "<a href=\"login.php\"/><p>Try again</p></a>";
			exit; // exit php code as do not want anything more to execute in other scripts
			return false; // return not needed however included to ensure no issues
		}		
	}	
	return false;
?>