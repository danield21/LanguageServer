<!DOCTYPE>
<html>
	<head>
<?php
	session_start();
	require_once '../php/all.php';
	$status = status();
	if($status) {
		if(isset($_POST['change'])) {
			if($_POST['change'] == 'add' && isset($_POST['language'])) {
				add_language($_POST['language']);
			}
			if($_POST['change'] == 'delete' && isset($_GET['id'])) {
				delete_language($_GET['id']);
			}
			if($_POST['change'] == 'edit' && isset($_GET['id']) && isset($_POST['language'])) {
				edit_language($_GET['id'], $_POST['language']);
			}
		}
	}
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
								<a href="./languages.php">Language Options</a>
							</p>
							<p>
								<a href="./words.php">Word Options</a>
							</p>
							
<?php
		if(status() === 2) {
?>
							<p>
								<a href="./users.php">User Options</a>
							</p>
<?php
		}
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
if($status) {
?>
				<section id="column1">
					<table>
						<form action="languages.php" method="post">
							<tr>
								<th>ID</th>
								<th>Language</th>
								<th style="text-align:right;">Act</th>
								<th style="text-align:left;">ion</th>
							</tr>
							<tr class="lowlight">
								<td>
								</td>
								<td>
									<input type="text" name="language">
								</td>
								<td>
									<input type="submit" value="add" alt="add" name="change">
								</td>
								<td>&nbsp;</td>
							</tr>
						</form>
<?php
	$odd = true;
	foreach($language_list as $item) {
?>
						<form action="languages.php?id=<?php echo $item->id(); ?>" method="post">
								<tr class="<? echo ($odd) ? 'highlight' : 'lowlight'; ?>">
								<td>
									<?php echo $item->id() . "\n";?>
								</td>
								<td>
									<input type="text" value="<?php echo $item->language();?>" name="language">
								</td>
								<td>
									<input type="submit" value="delete" name="change">
								</td>
								<td>
									<input type="submit" value="edit" name="change">
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