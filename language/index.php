<!DOCTYPE>
<html>
	<head>
<?php
	session_start();
	require_once 'php/all.php';
	$current_language = new Language;
	if(isset($_GET['language_id'])) {
		$current_language = get_by_language_id($_GET['language_id']);
	}
	$list_of_languages = get_all_languages();
?>
		<link rel="stylesheet" type="text/css" href="style.css">
		<title><?php
	echo ($current_language->is_init()) ? $current_language->language() : "Welcome to Language Helper";
?></title>
	</head>
	<body>
		<div id="background">
			<header>
				<a href="<?php echo ROOT?>">
					<img src="logo.png" alt="Some type of logo" class="logo" border="0">
				</a>
				<nav>
					<section id="left_menu" class="menu">
						<div class="menu_title">
							<a href="<?php echo ROOT?>">Choose Language</a>
						</div>
						<section class="sub_menu">
<?php
foreach($list_of_languages as $language) {
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
								<a href="admin/">Admin Account</a>
							</p>
							<p>
								<a href="admin/categories.php">Category Options</a>
							</p>
							<p>
								<a href="admin/languages.php">Language Options</a>
							</p>
							<p>
								<a href="admin/words.php">Word Options</a>
							</p>
<?php
		if(status() === 2) {
?>
							<p>
								<a href="admin/users.php">User Options</a>
							</p>
<?php
		}
	}
?>
							<p>
								<a href="logout.php">Logout</a>
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
if(!$current_language->is_init()) {?>
				Choose one of the following languages:
<?php 
	foreach($list_of_languages as $language) {
?>
				<br />
				<a href="?language_id=<?php echo $language->id(); ?>">
					<?php echo $language->language() . "\n"; ?>
				</a>
<?php
	}
} else {
	$num = count(get_words(new Category, $current_language));
	echo 'There';
	echo ($num == 1) ? ' is ' : ' are ';
	echo $num;
	echo ($num == 1) ? ' word' : ' words';
	echo ' in ' . $current_language->language();
?>
				<br />
				What would you like to do?
				<br>
				<table class="choose">
					<tr>
						<td>
							<a href="study_words.php?language_id=<?php echo $current_language->id(); ?>">
								Study Words
							</a>
						</td>
						<td>
							<a href="practice_words.php?language_id=<?php echo $current_language->id(); ?>">
								Practice Words
							</a>
						</td>
					</tr>
				</table>
<?php
}
?>
			</section>
		</div>
	</body>
</html>