<!DOCTYPE>
<html>
	<head>
<?php
	session_start();
	require_once '../php/all.php';!
	$status = status();
	if($status) {
		if(isset($_POST['change_is'])) {
			if($_POST['change_is'] == 'add' && isset($_POST['child']) && isset($_POST['parent'])) {
				add_relationship($_POST['child'], $_POST['parent']);
			}
			if($_POST['change_is'] == 'delete' && isset($_POST['child']) && isset($_POST['parent'])) {
				echo $_POST['child'] . " " . $_POST['parent'];
				delete_relationship($_POST['child'], $_POST['parent']);
			}
		}
		if(isset($_POST['change_cat'])) {
			if($_POST['change_cat'] == 'add' && isset($_POST['category'])) {
				add_category($_POST['category'], $_POST['description']);
			}
			if($_POST['change_cat'] == 'delete' && isset($_GET['id'])) {
				delete_category($_GET['id']);
			}
			if($_POST['change_cat'] == 'edit' && isset($_GET['id']) && isset($_POST['category'])) {
				edit_category($_GET['id'], $_POST['category'], $_POST['description']);
			}
		}
	}
	$language_list = get_all_languages();
	$list = get_all_categories();
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
						<form action="categories.php" method="post">
							<tr>
								<th>ID</th>
								<th>Category</th>
								<th>Description</th>
								<th style="text-align:right;">Act</th>
								<th style="text-align:left;">ion</th>
							</tr>
							<tr class="lowlight">
								<td>
								</td>
								<td>
									<input type="text" name="category">
								</td>
								<td>
									<input type="text" name="description">
								</td>
								<td>
									<input type="submit" value="add" alt="add" name="change_cat">
								</td>
								<td>&nbsp;</td>
							</tr>
						</form>
<?php
	$odd = true;
	foreach($list as $item) {
?>
						<form action="categories.php?id=<?php echo $item->id(); ?>" method="post">
							<tr class="<? echo ($odd) ? 'highlight' : 'lowlight'; ?>">
								<td>
									<?php echo $item->id() . "\n";?>
								</td>
								<td>
									<input type="text" value="<?php echo $item->category();?>" name="category">
								</td>
								<td>
									<input type="text" value="<?php echo $item->description();?>" name="description">
								</td>
								<td>
									<input type="submit" value="delete" name="change_cat">
								</td>
								<td>
									<input type="submit" value="edit" name="change_cat">
								</td>
							</tr>
						</form>
<?php
		$odd=!$odd;
	}
?>
					</table>
				</section>
				<section id="column2">
					<table>
						<form method="post" action="categories.php">
							<tr>
								<th>
									Child
								</th>
								<th>&nbsp;</th>
								<th>
									Parent
								</th>
								<th>Action</th>
							</tr>
							<tr class="lowlight">
								<td>
									<select name="child">
<?php
	foreach($list as $item) {
?>
										<option value="<?php echo $item->id();?>"><?php echo $item->category();?></option>
<?php
	}
?>
									</select>
								</td>
								<td>is</td>
								<td>
									<select name="parent">
<?php
	foreach($list as $item) {
?>
										<option value="<?php echo $item->id();?>"><?php echo $item->category();?></option>
<?php
	}
?>
									</select>
								</td>
								<td>
									<input type="submit" value="add" name="change_is">
								</td>
							</tr>
						</form>
<?php
	$odd = true;
	foreach($list as $item) {
		$parents = get_all_parent_categories($item);
		$parent = get_direct_parent_categories($item);
		if(count($parent)) {
			unset($parents[0]);
			foreach($parents as $pars) {
?>
						<form action="categories.php" method="post">
							<tr class="<? echo ($odd) ? 'highlight' : 'lowlight'; ?>">
								<td>
									<?php echo $item->category() . "\n";?>
									<input type="hidden" name="child" value="<?php echo $item->id();?>">
								</td>
								<td>is</td>
								<td>
									<?php echo $pars->category() . "\n";?>
								</td>
									<input type="hidden" name="parent" value="<?php echo $pars->id();?>">
								<td>
<?php
				$copy=false;
				foreach($parent as $par) {
					if($par->equals($pars)) {
?>
									<input type="submit" value="delete" name="change_is">
<?php
						$copy=true;
					}
				}
				if(!$copy) {
?>
									<input type="button" value="delete">
<?php
				}
?>
								</td>
							</tr>
						</form>
<?php
				$odd = !$odd;
			}
		}
	}
?>
						</form>
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