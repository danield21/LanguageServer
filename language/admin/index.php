<!DOCTYPE>
<html>
	<head>
<?php
	session_start();
	require_once '../php/all.php';!
	$status = status();
	$language_list = get_all_languages();
?>
		<link rel="stylesheet" type="text/css" href="../style.css">
		<title><?php
	echo ($status) ? "Administrator" : "Access Denied";
?></title>
	</head>
	<body>
		<div id="background">
			<header>
				<a href="<?php echo ROOT?>">
					<img src="../logo.png" alt="Some type of logo" class="logo" border="0">
				</a>
				<nav>
					<section id="left_menu" class="menu">
						<div class="menu_title">
							<a href="<?php echo ROOT?>">Choose Language</a>
						</div>
						<section class="sub_menu">
<?php
foreach($language_list as $language) {
?>
							<p>
								<a href="?language_id=<?php echo $language->id(); ?>">
									<?php echo $language->language(). "\n"; ?>
								</a>
							</p>
<?php
}
?>
						</section>
					</section>
					<section id="right_menu" class="menu">
<?php
if(isset($_SESSION['language_server_user'])) {
?>
						<div class="menu_title">
							<?php echo $_SESSION['language_server_user'];?>
						</div>
						<section class="sub_menu">
<?php
	if(status()) {
?>
							<p>
								<a href="./">Admin Account</a>
							</p>
							<p>
								<a href="./categories.php">Category Options</a>
							</p>
							<p>
								<a href="./langugaes.php">Language Options</a>
							</p>
							<p>
								<a href="./words.php">Word Options</a>
							</p>
							<p>
								<a href="./users.php">User Options</a>
							</p>
<?php
	}
?>
							<p>
								<a href="../logout.php">Logout</a>
							</p>
						</section>
<?php
} else {
?>
						<div class="menu_title">
							Login
						</div>
						<section class="sub_menu">
<?php
	if(isset($_GET['bad_password']) && $_GET['bad_password']) {
?>
							<p class="bad">Username/Password is invalid</p>
<?php
	}
?>
							<form action="login.php?redirect=<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
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
if($status) {
?>
				<p>
					<a href="categories.php">Category Options</a>
				</p>
				<p>
					<a href="langugaes.php">Language Options</a>
				</p>
				<p>
					<a href="words.php">Word Options</a>
				</p>
				<p>
					<a href="users.php">User Options</a>
				</p>
<?php
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