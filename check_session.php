<?php
	// if value assigned in session array
	if (isset($_SESSION['valid_user'])) {
		return true;
	}
	else {
		return false;
	}	
?>
