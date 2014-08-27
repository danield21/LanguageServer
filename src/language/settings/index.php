<!DOCTYPE>
<html>
	<head>
<?php
	session_start();
	require_once '../php/all.php';
	$header = 0;
	
	$at = new AccountsTable(IP, USER, PASSWORD, DATABASE);
	$current_user = new Account;
	if(isset($_SESSION['language_server_user']) && isset($_SESSION['language_server_key'])) {
		$current_user = $at->get_by_id($_SESSION['language_server_user'], $_SESSION['language_server_key']);
	}
	
	$st = new SettingsTable('settings.xml');
	$settings = $st->get_min($current_user->admin);
?>
		<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
		<link rel="stylesheet" type="text/css" href="./style.php">
		<title><?php
	echo ($current_user->is_valid()) ? "Settings" : "Access Denied";
?></title>
	</head>
	<body>
		<div id="background">
			<header>
				<a href="../">
					<img src="../logo.png" alt="Some type of logo" class="logo" border="0">
				</a>
				<nav>
					<section class="left_menu">
						<div>
							<a href="../">Home</a>
						</div>
					</section>
					<section class="right_menu">
						<div>
<?php
if($current_user->is_valid()) {
?>
							<p><?php echo $current_user->user;?></p>
							<section>
								<a href="./">Settings</a>
<?php
	foreach($settings as $setting) {
		echo "\t\t\t\t\t\t\t\t" . $setting->get_link() . "\n";
	}
?>
								<a href="../logout.php">Logout</a>
							</section>
<?php
} else {
?>
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
						</div>
					</section>
				</nav>
			</header>
			<section id="body_content">
				<div id="head_space"></div>
<?php
if($current_user->is_valid()) {
	if(!isset($_GET['setting'])) {
?>
				<table class="option">
<?php
		foreach($settings as $setting) {
?>
					<tr>
						<th style="padding:5pt;">
							<?php echo $setting->get_link() . "\n";?>
						</th>
					</tr>
<?php
		}
	} else {
		$found = false;
		foreach($settings as $setting) {
			if($setting->key === $_GET['setting']) {
				if(!file_exists($setting->filename()) || !include $setting->filename()) {
					echo 'Not Found. Please try again.';
					log_info('File ' . $setting->filename() . ' is not found');
				}
				$found = true;
				break;
			}
		}
		if(!$found) {
?>
				<p class="bad">Access Denied</p>
<?php
		}
	}
} else {
?>
				<p class="bad">Please Login</p>
<?php
}
?>
			</section>
		</div>
	</body>
</html>
