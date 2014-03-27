<!DOCTYPE>
<html>
	<head>
<?php
	session_start();
	require_once 'php/all.php';
	$current_language = new Language;
	$current_category = new Category;
	if(isset($_GET['language_id'])) {
		$current_language = get_by_language_id($_GET['language_id']);
	}
	if(isset($_GET['category_id'])) {
		$current_category = get_by_category_id($_GET['category_id']);
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
								<a href="admin/langugaes.php">Language Options</a>
							</p>
							<p>
								<a href="admin/words.php">Word Options</a>
							</p>
							<p>
								<a href="admin/users.php">User Options</a>
							</p>
<?php
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
	if(!$current_category->is_init()) {
		$cats = get_all_categories();
		foreach($cats as $cat) {
?>
				<br/>
				<a href="?language_id=<?php echo $current_language->id(); ?>&category_id=<?php echo $cat->id(); ?>"><?php echo $cat->category();?></a>
				(<?php echo $num = count(get_words($cat, $current_language));?>)
<?php
		}
	} else	{
?>
				<form name="practice">
					<input type="button" onclick="" value="Submit">
					<table class="word_list">
						<tr>
<?php
	$word_list = get_words($current_category, $current_language);
	shuffle($word_list);
	$i = 0;
	foreach($word_list as $word) {
	$i++;
?>
							<td>
								<table class="word">
									<tr>
										<td class="word_pic">
											<img src="images/<?php echo $word->id() . '.' . $word->picture();?>">
										</td>
									</tr>
									<tr>
										<td>
											<input type="text" name="<?php echo $word->id(); ?>">
										</td>
									</tr>
								</table>
							</td>
<?php
		if($i % 5 == 0) {
		?>
						</tr>
						<tr>
		<?php
		}
	}
?>
						</tr>
					</table>
					<input type="button" onclick="" value="Submit">
				</form>
<?php
	}
}
?>
			</section>
		</div>
	</body>
</html>