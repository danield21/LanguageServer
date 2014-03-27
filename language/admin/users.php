<!DOCTYPE>
<html>
	<head>
<?php
	session_start();
	require_once '../php/all.php';!
	$status = status();
	if($status) {
		if(isset($_POST['change'])) {
			if($_POST['change'] == 'add') {
				add_user($_POST['username'], $_POST['first_name'], $_POST['last_name']);
				change_password($_POST['username'], $_POST['password'], $_POST['confirm']);
				change_status($_POST['username'], $_POST['admin']);
			}
			if($_POST['change'] == 'edit' && isset($_GET['username'])) {
				change_password($_GET['username'], $_POST['password'], $_POST['confirm']);
				if(isset($_POST['admin'])) {
					change_status($_GET['username'], $_POST['admin']);
				}
			}
			if($_POST['change'] == 'delete' && isset($_GET['username'])) {
				delete_user($_GET['username']);
			}
		}
	}
	$language_list = get_all_languages();
	$list = get_all_accounts();
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
				<section id="column1">
					<table>
						<form action="users.php" method="post">
							<tr class="lowlight">
								<th>
									Username
								</th>
								<th>
									Password
								</th>
								<th>
									First Name
								</th>
								<th>
									Last Name
								</th>
								<th>
									Admin
								</th>
								<th>
									Add
								</th>
							</tr>
							<tr class="lowlight">
								<td>
									<input type="text" name="username">
								</td>
								<td>
									<table>
										<tr>
											<td>
												Password:
											</td>
											<td>
												<input type="password" name="password">
											</td>
										</tr>
										<tr>
											<td>
												Confirm:
											</td>
											<td>
												<input type="password" name="confirm">
											</td>
										</tr>
									</table>
								</td>
								<td>
									<input type="text" name="first_name">
								</td>
								<td>
									<input type="text" name="last_name">
								</td>
								<td>
									<input type="number" name="admin" max="2" min="0" value="0">
								</td>
								<td>
									<input type="submit" value="add" name="change">
								</td>
							</tr>
						</form>
						<tr class="lowlight">
							<th>
								Username
							</th>
							<th>
								Change Password
							</th>
							<th>
								First Name
							</th>
							<th>
								Last Name
							</th>
							<th>
								Admin
							</th>
							<th>
								Action
							</th>
						</tr>
<?php
	$odd = true;
	foreach($list as $item) {
?>
						<form action="users.php?username=<?php echo $item['username'] . "\n";?>" method="post">
							<tr class="<? echo ($odd) ? 'highlight' : 'lowlight'; ?>">
								<td>
									<?php echo $item['username'] . "\n";?>
								</td>
								<td>
									<table>
										<tr>
											<td>
												Password:
											</td>
											<td>
												<input type="password" name="password">
											</td>
										</tr>
										<tr>
											<td>
												Confirm:
											</td>
											<td>
												<input type="password" name="confirm">
											</td>
										</tr>
									</table>
								</td>
								<td>
									<?php echo $item['first_name'] . "\n";?>
								</td>
								<td>
									<?php echo $item['last_name'] . "\n";?>
								</td>
								<td>
									<?
										if(isset($item['admin'])) {
										?><input type="number" value="<?php echo $item['admin']; ?>" max="2" min="0" name="admin"><?
										} else {
										?>N/A<?
										}?>
								</td>
								<td>
									<table>
										<tr>
											<td>
												<input type="submit" value="delete" name="change">
											</td>
										</tr>
										<tr>
											<td>
												<input type="submit" value="edit" name="change">
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</form>
<?php
		$odd=!$odd;
	}
?>
					</table>
				</section>
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