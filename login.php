<html>
	<head>
		<title>Login - Adoption Management</title>
		<meta charset="UTF-8"/>
	</head>
	<body>
		<h1>Log In</h1>
		
<?php
	echo <<< END
	<form method="post" action="home.php">
	<p>Username: <input type="text" name="username"></p>
	<p>Password: <input type="password" name="password"></p>
	<p><input type="submit" name="submit" value="Log In"></p>
	</form>	
END;

	include 'footer_logged_out.php';
?>
</body>
</html>