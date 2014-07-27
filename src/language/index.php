<!DOCTYPE>
<html>
	<head>
<?php
	session_start();
	require_once 'php/all.php';
	$admin = status();
	
	$lt = new LanguagesTable(IP, USER, PASSWORD, DATABASE);
	$ct = new CategoriesTable(IP, USER, PASSWORD, DATABASE);
	$wt = new WordsTable(IP, USER, PASSWORD, DATABASE);
	
	$current_language = new Language;
	$current_category = new Category;
	
	
	$language_list = $lt->get_all();
	$category_list = $ct->get_all();
	
	$header = 0;
	
	if(isset($_GET['language_id'])) {
		$current_language = $lt->get_by_id($_GET['language_id']);
	}
	if(isset($_GET['category_id'])) {
		$current_category = $ct->get_by_id($_GET['category_id']);
	}
	
	if($current_language->is_valid() && isset($_GET['activity'])) {
		$temp_cats = array();
		foreach($category_list as $cat) {
			$word = $wt->get_by_filter($cat, $current_language);
			if(isset($word[0])) {
				$temp_cats[] = $cat;
			}
		}
		$category_list = $temp_cats;
	}
?>
		<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
		<link rel="stylesheet" type="text/css" href="style.php">
		<title><?php
	echo ($current_language->is_valid()) ? $current_language->language : "Welcome to Language Helper";
?></title>
	</head>
	<body>
		<div id="background">
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
								<a href="./?language_id=<?php echo $current_language->id; ?>&activity=study">
									Study&nbsp;Words
								</a>
								<a href="./?language_id=<?php echo $current_language->id; ?>&activity=practice">
									Practice&nbsp;Words
								</a>
							</section>
						</div>
<?php
	if(isset($_GET['activity'])) {
?>
						<div>
							<p>Category</p>
							<section>
<?php
		foreach($category_list as $category) {
?>
								<a href="./?language_id=<?php echo $current_language->id; ?>&activity=<?php echo $_GET['activity'];?>&category_id=<?php echo $category->id?>"><?php echo $category->category?></a>
<?php
		}
?>
							</section>
						</div>
<?php
	}
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
								<a href="./settings/">Settings</a>
<?php
	$aot = new AccountOptionsTable(IP, USER, PASSWORD, DATABASE);
	$options = $aot->get_min($admin);
	foreach($options as $option) {
		echo "\t\t\t\t\t\t\t\t" . $option->get_link('settings') . "\n";
	}
?>
								<a href="./logout.php">Logout</a>
							</section>
<?php
} else {
?>
							<p>Login</p>
							<section>
<?php
	if(isset($_GET['bad_password']) && $_GET['bad_password']) {
?>
								<p class="bad">Username/Password is invalid</p>
<?php
	}
?>
								<form action="./login.php?redirect=<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
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
				<div></div>
<?php
if(!$current_language->is_valid()) {?>
				Choose one of the following languages:
<?php 
	foreach($language_list as $language) {
?>
				<br />
				<a href="?language_id=<?php echo $language->id; ?>">
					<?php echo $language->language . "\n"; ?>
				</a>
<?php
	}
} elseif(!isset($_GET['activity'])) {
	$num = count($wt->get_by_language($current_language));
	echo 'There';
	echo ($num == 1) ? ' is ' : ' are ';
	echo $num;
	echo ($num == 1) ? ' word' : ' words';
	echo ' in ' . $current_language->language;
?>
				<br />
				What would you like to do?
				<br>
				<table class="activities">
					<tr>
						<td>
							<a href="./?language_id=<?php echo $current_language->id; ?>&activity=study">
								Study Words
							</a>
						</td>
						<td>
							<a href="./?language_id=<?php echo $current_language->id; ?>&activity=practice">
								Practice Words
							</a>
						</td>
					</tr>
				</table>
<?php
} else {
	switch($_GET['activity']) {
		case 'study':
			if(!file_exists('study.php') || !include 'study.php') {
				echo 'Not Found. Please try again.';
				log_info('File study.php is not found');
			}
			break;
		case 'practice':
			if(!file_exists('practice.php') || !include 'practice.php'){
				echo 'Not Found. Please try again.';
				log_info('File practice.php is not found');
			}
			break;
		default:
			echo 'Not Found';
			break;
	}
}
?>
			</section>
		</div>
	</body>
</html>
