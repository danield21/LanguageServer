<!DOCTYPE>
<html>
	<head>
<?php
	session_start();
	require_once 'php/all.php';
	$lt = new LanguagesTable(IP, USER, PASSWORD, DATABASE);
	$ct = new CategoriesTable(IP, USER, PASSWORD, DATABASE);
	$wt = new WordsTable(IP, USER, PASSWORD, DATABASE);
	$current_language = new Language;
	if(isset($_GET['language_id'])) {
		$current_language = $lt->get_by_id($_GET['language_id']);
	}
	$language_list = $lt->get_all();
?>
		<link rel="stylesheet" type="text/css" href="style.php">
		<title><?php
	echo ($current_language->is_valid()) ? $current_language->language : "Welcome to Language Helper";
?></title>
	</head>
	<body>
			<header>
				<a href="<?php echo ROOT?>">
					<img src="logo.png" alt="Some type of logo" class="logo" border="0">
				</a>
				<nav>
					<section class="left_menu">
						<div>
							<a href="./">Home</a>
						</div>
						<div>
							<p>Language</p>
							<section>
<?php
foreach($language_list as $language) {
?>
								<a href="?language_id=<?php echo $language->id; ?>">
									<?php echo $language->language. "\n"; ?>
								</a>
<?php
}
?>
							</section>
						</div>
<?php
if($current_language->is_valid())
{
?>
						<div>
							<p>Activity</p>
							<section>
								<a href="study_words.php?language_id=<?php echo $current_language->id; ?>">
									Study&nbsp;Words
								</a>
								<a href="practice_words.php?language_id=<?php echo $current_language->id; ?>">
									Practice&nbsp;Words
								</a>
							</section>
						</div>
<?php
}
?>
					</section>
					<section class="right_menu">
						<div>
<?php
if(isset($_SESSION['language_server_user'])) {
?>
							<p><?php echo $_SESSION['language_server_user'];?></p>
							<section>
								<a href="admin/">Admin&nbsp;Account</a>
								<a href="admin/categories.php">Category&nbsp;Options</a>
								<a href="admin/languages.php">Language&nbsp;Options</a>
								<a href="admin/words.php">Word&nbsp;Options</a>
								<a href="admin/users.php">User&nbsp;Options</a>
								<a href="logout.php">Logout</a>
							</section>
<?php
} else {
?>
							<p>Login</p>
							<section>
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
						</div>
					</section>
				</nav>
			</header>
		<div id="background">
			<section id="body_content">
				<div></div>
<!--Body goes here-->
			</section>
		</div>
	</body>
</html>
