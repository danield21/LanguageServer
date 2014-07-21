<!DOCTYPE>
<html>
	<head>
<?php
	session_start();
	require_once '../php/all.php';
	$admin = status();
	$aot = new AccountOptionsTable(IP, USER, PASSWORD, DATABASE);
	$options = $aot->get_min($admin);
?>
		<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
		<link rel="stylesheet" type="text/css" href="./style.php">
		<title><?php
	echo ($admin) ? "Administrator" : "Access Denied";
?></title>
	</head>
	<body>
		<div id="background">
			<header>
				<a href="<?php echo ROOT?>">
					<img src="../logo.png" alt="Some type of logo" class="logo" border="0">
				</a>
				<nav>
					<section class="left_menu">
						<div>
							<a href="../">Home</a>
						</div>
					</section>
					<section class="right_menu">
<?php
if(isset($_SESSION['language_server_user'])) {
?>
						<div>
							<p><?php echo $_SESSION['language_server_user'];?></p>
							<section>
								<a href="./">Settings</a>
<?php
	foreach($options as $option) {
		echo "\t\t\t\t\t\t\t\t" . $option->get_link() . "\n";
	}
?>
								<a href="../logout.php">Logout</a>
							</section>
						</div>
					</section>
<?php
} else {
?>
						<div>
							<p>Login</p>
<?php
	if(isset($_GET['bad_password']) && $_GET['bad_password']) {
?>
							<p class="bad">Username/Password is invalid</p>
<?php
	}
?>
							<section>
								<form action="../login.php?redirect=<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
									<table>
										<tr>
											<td>
												Username:
											</td>
											<td>
												<input type="text" name="user"/>
											</td>
										</tr>
										<tr>
											<td>
												Password:
											</td>
											<td>
												<input type="password" name="pass">
											</td>
										</tr>
										<tr>
											<td>
												<input type="submit" name="login" value="Login">
											</td>
											<td>&nbsp;</td>
										</tr>
									</table>
								</form>
							</section>
<?php
}
?>
					</section>
				</nav>
			</header>
			<section id="body_content">
				<div></div>
<?php
if($admin) {
	if(!isset($_GET['setting'])) {
?>
				<table class="option">
<?php
		foreach($options as $option) {
?>
					<tr>
						<th style="padding:5pt;">
							<?php echo $option->get_link() . "\n";?>
						</th>
					</tr>
<?php
		}
	} else {
		foreach($options as $option) {
			if($option->key === $_GET['setting']) {
				if((include $option->key . '.php') === false) {
					echo 'Not Found. Please try again.';
					log_info('File ' . $option->key . '.php is not found');
				}
				break;
			}
		}
	}
} else {
?>
				<p class="bad">Access Denied</p>
<?php
}
?>
			</section>
		</div>
	</body>
</html>
