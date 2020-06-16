<html>
	<head>
		<title>Log Out</title>
		<meta charset='UTF-8'>
	</head>
	<body>
		<?php
			session_start();
			$valid_session = require('check_session.php');
			
			if ($valid_session) {
				$old_user = $_SESSION['valid_user'];
				unset($_SESSION['valid_user']);
				session_destroy();		
			}
			
			if (!empty($old_user)) {
				echo 'Logged Out<br>';
                echo "<a href=\"home.php\"/><p>Home</p></a>";
			}
			else {
				echo 'You were not logged in, and so have not been logged out.<br>';
			}
			
		?>
	</body>
</html>