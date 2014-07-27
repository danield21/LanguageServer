<!DOCTYPE>
<html>
	<head>
<?php
	session_start();
	require_once '../php/all.php';
	function match_word($word) {
		if(!isset($_POST['filter_word']) || !is_string($_POST['filter_word']) || trim($_POST['filter_word']) == '') {
			return true;
		}
		return strpos($word->word(), $_POST['filter_word']) !== false;
	}
	function match_language($word) {
		if(!isset($_POST['filter_language']) || !is_array($_POST['filter_language'])) {
			return true;
		}
		foreach($_POST['filter_language'] as $filter) {
			if($filter == $word->language_id()) {
				return true;
			}
		}
		return false;
	}
	function match_category($word) {
		$categories = get_category_by_word($word);
		if(!isset($_POST['filter_category']) || !is_array($_POST['filter_category'])) {
			return true;
		}
		foreach($_POST['filter_category'] as $filter) {
			foreach($categories as $category) {
				if($filter == $category->id()) {
					return true;
				}
			}
		}
		return false;
	}
	$status = status();
	if($status) {
		if(isset($_POST['change_word'])) {
			if($_POST['change_word'] == 'add' && isset($_POST['word'])) {
				var_dump($_FILES);
				$pri_ext = (!empty($_FILES['primary_sound']['name'])) ? end(explode(".", $_FILES["primary_sound"]["name"])) : '';
				$sec_ext = (!empty($_FILES['secondary_sound']['name'])) ? end(explode(".", $_FILES["secondary_sound"]["name"])) : '';
				$pic_ext = (!empty($_FILES['picture']['name'])) ? end(explode(".", $_FILES["picture"]["name"])) : '';
				$id = add_word($_POST['word'], $pri_ext, $sec_ext, $pic_ext, $_POST['language']);
				if($id !== false) {
					if($pri_ext !== '') {
						move_uploaded_file($_FILES["primary_sound"]["tmp_name"], "../sounds/" . $id . '.' . $pri_ext);
					}
					if($pri_ext !== $sec_ext && $sec_ext !== '') {
						move_uploaded_file($_FILES["secondary_sound"]["tmp_name"], "../sounds/" . $id . '.' . $sec_ext);
					}
					if($pic_ext !== '') {
						move_uploaded_file($_FILES["picture"]["tmp_name"], "../images/" . $id . '.' . $pic_ext);
					}
				}
			}
			if($_POST['change_word'] === 'delete' && isset($_GET['id'])) {
				$delete_word = get_by_word_id($_GET['id']);
				if($delete_word->is_init() && delete_word($delete_word->id())) {
					$location = "../sounds/" . $delete_word->id() . '.' . $delete_word->primary_sound();
					if($delete_word->primary_sound() !== '' && file_exists($location)) {
						unlink($location);
					}
					$location = "../sounds/" . $delete_word->id() . '.' . $delete_word->secondary_sound();
					if($delete_word->secondary_sound() !== '' && file_exists($location)) {
						unlink($location);
					}
					$location = "../images/" . $delete_word->id() . '.' . $delete_word->picture();
					if($delete_word->picture() !== '' && file_exists($location)) {
						unlink($location);
					}
				}
			}
			if($_POST['change_word'] === 'edit' && isset($_GET['id']) && isset($_POST['word'])) {
				$edit_word = get_by_word_id($_GET['id']);
				echo $edit_word->word();
				$pri_ext = (!empty($_FILES['primary_sound']['name'])) ? end(explode(".", $_FILES["primary_sound"]["name"])) : '';
				$sec_ext = (!empty($_FILES['secondary_sound']['name'])) ? end(explode(".", $_FILES["secondary_sound"]["name"])) : '';
				$pic_ext = (!empty($_FILES['picture']['name'])) ? end(explode(".", $_FILES["picture"]["name"])) : '';
				if(
					edit_word($_GET['id'], $_POST['word'],
					($pri_ext !== '') ? $pri_ext : $edit_word->primary_sound(),
					($sec_ext !== '') ? $sec_ext : $edit_word->secondary_sound(),
					($pic_ext !== '') ? $pic_ext : $edit_word->picture(),
					$_POST['language'])
				) {
				
					$location = "../sounds/" . $edit_word->id() . '.' . $edit_word->primary_sound();
					if($pri_ext !== '') {
						if($edit_word->primary_sound() !== '' && file_exists($location)) {
							unlink($location);
						}
						move_uploaded_file($_FILES["primary_sound"]["tmp_name"], "../sounds/" . $edit_word->id() . '.' . $pri_ext);
					}
					
					$location = "../sounds/" . $edit_word->id() . '.' . $edit_word->secondary_sound();
					if($pri_ext !== $sec_ext && $sec_ext !== '') {
						if($edit_word->secondary_sound() !== '' && file_exists($location)) {
							unlink($location);
						}
						move_uploaded_file($_FILES["secondary_sound"]["tmp_name"], "../sounds/" . $edit_word->id() . '.' . $sec_ext);
					}
					
					$location = "../images/" . $edit_word->id() . '.' . $edit_word->picture();
					if($pic_ext !== '') {
						if($edit_word->picture() !== '' && file_exists($location)) {
							unlink($location);
						}
						move_uploaded_file($_FILES["picture"]["tmp_name"], "../images/" . $edit_word->id() . '.' . $pic_ext);
					}
				}
			}
		}
		if(isset($_POST['change_is'])) {
			if($_POST['change_is'] === 'add') {
				add_word_to_category($_POST['word_id'], $_POST['category_id']);
			}
			if($_POST['change_is'] === 'delete') {
				delete_word_from_category($_POST['word_id'], $_POST['category_id']);
			}
		}
	}
	$word_list = get_all_words();
	$language_list = get_all_languages();
	$category_list = get_all_categories();
?>
		<link rel="stylesheet" type="text/css" href="../style.css">
		<title><?php
	echo ($status) ? "Administrator" : "Access Denied";
?></title>
	</head>
	<body>
		<div id="background">
			<div id="test"></div>
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
				<form action="words.php" method="post">
					<table id="filter_options" class="highlight">
						<thead>
							<tr>
								<th>
									Filter by:
								</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>
									Word: <input type="text" name="filter_word">
								</td>
							</tr>
							<tr>
								<td>
									Language:
								</td>
							</tr>
								<td>
<?
foreach($language_list as $language) {
?>
									<input type="checkbox" value="<?php
	echo $language->id();
?>" name="filter_language[]" id="<?
	echo $language->language();
?>">
									<label for="<?php echo $language->language(); ?>"><?php echo $language->language(); ?></label>
<?
}
?>
								</td>
							</tr>
							<tr>
								<td>
									Category:
								</td>
							</tr>
							<tr>
								<td>
<?
foreach($category_list as $category) {
?>
									<input type="checkbox" value="<?php
	echo $category->id();
?>" name="filter_category[]" id="<?
	echo $category->category();
?>">
									<label for="<?php echo $category->category(); ?>"><?php echo $category->category(); ?></label>
<?
}
?>
								</td>
							</tr>
						</tbody>
						<tfoot>
							<tr>
								<td>
									<input type="submit" value="Filter" name="filter">
								</td>
							</tr>
						</tfoot>
					</table>
				</form>
<?php
if($status) {
?>
				<section id="column1">
					<table>
						<form action="words.php" method="post" enctype="multipart/form-data">
							<tr class="lowlight">
								<th>
								</th>
								<th>
									Word
								</th>
							</tr>
							<tr class="lowlight">
								<td>
								</td>
								<td>
									<input type="text" name="word">
								</td>
							</tr>
							<tr class="lowlight">
								<th>
									Primary Sound
								</th>
								<th>
									Secondary Sound
								</th>
							</tr>
							<tr class="lowlight">
								<td>
									<input type="file" name="primary_sound">
								</td>
								<td>
									<input type="file" name="secondary_sound">
								</td>
							</tr>
							<tr class="lowlight">
								<th>
									Picture
								</th>
								<th>
									Language
								</th>
							</tr>
							<tr class="lowlight">
								<td>
									<input type="file" name="picture">
								</td>
								<td>
									<select name="language">
<?php
	foreach($language_list as $language) {
?>
										<option value="<?php echo $language->id();?>">
											<?php echo $language->language() . "\n"; ?>
										</option>
<?php
	}
?>
									</select>
								</td>
							</tr>
							<tr class="lowlight">
								<th>Add</th>
								<th>&nbsp;</th>
							</tr>
							<tr class="lowlight">
								<td>
									<input type="submit" value="add" alt="add" name="change_word">
								</td>
								<td>&nbsp;</td>
							</tr>
						</form>
<?php
	$odd = true;
	foreach($word_list as $item) {
		if(!match_word($item) || !match_language($item) || !match_category($item)) {
			continue;
		}
		
?>
						<form action="words.php?id=<?php echo $item->id(); ?>" method="post" enctype="multipart/form-data">
							<tr class="<? echo ($odd) ? 'highlight' : 'lowlight'; ?>">
								<th>
									ID
								</th>
								<th>
									Word
								</th>
							</tr>
							<tr class="<? echo ($odd) ? 'highlight' : 'lowlight'; ?>">
								<td>
									<? echo $item->id() . "\n"; ?>
								</td>
								<td>
									<input type="text" name="word" value="<? echo $item->word(); ?>">
								</td>
							</tr>
							<tr class="<? echo ($odd) ? 'highlight' : 'lowlight'; ?>">
								<th>
									Primary Sound
								</th>
								<th>
									Secondary Sound
								</th>
							</tr>
							<tr class="<? echo ($odd) ? 'highlight' : 'lowlight'; ?>">
								<td>
									<audio controls="controls">
										<source src="<?php echo '../sounds/' . $item->id() . '.' . $item->primary_sound();?>" type="audio/wav">
									</audio>
									<br>
									<input type="file" name="primary_sound">
								</td>
								<td>
									<audio controls="controls">
										<source src="<?php echo '../sounds/' . $item->id() . '.' . $item->secondary_sound();?>" type="audio/wav">
									</audio>
									<br>
									<input type="file" name="secondary_sound">
								</td>
							</tr>
							<tr class="<? echo ($odd) ? 'highlight' : 'lowlight'; ?>">
								<th>
									Picture
								</th>
								<th>
									Language
								</th>
							</tr>
							<tr class="<? echo ($odd) ? 'highlight' : 'lowlight'; ?>">
								<td>
									<img src="<?php echo '../images/' . $item->id() . '.' . $item->picture();?>" width="100px">
									<input type="file" name="picture">
								</td>
								<td>
									<select name="language">
<?php
		foreach($language_list as $language) {
?>
										<option value="<?php echo $language->id(); ?>"<?
		if($language->id() ==$item->language_id()) {
			echo ' selected="selected"';
		}
?>>
											<?php echo $language->language() . "\n"; ?>
										</option>
<?php
		}
?>
									</select>
								</td>
							</tr>
							<tr class="<? echo ($odd) ? 'highlight' : 'lowlight'; ?>">
								<th>Edit</th>
								<th>Delete</th>
							</tr>
							<tr class="<? echo ($odd) ? 'highlight' : 'lowlight'; ?>">
								<td>
									<input type="submit" value="edit" name="change_word">
								</td>
								<td>
									<input type="submit" value="delete" name="change_word">
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
						<tr>
							<th>
								Word
							</th>
							<th>&nbsp;</th>
							<th>
								Category
							</th>
							<th>
								Action
							</th>
						</tr>
						<form method="post" action="words.php">
							<tr class="lowlight">
								<td>
									<select name="word_id">
<?php
	foreach($word_list as $item) {
?>
										<option value="<?php echo $item->id();?>"><?php echo $item->word();?></option>
<?php
	}
?>
									</select>
								</td>
								<td>is</td>
								<td>
									<select name="category_id">
<?php
	foreach($category_list as $category) {
?>
										<option value="<?php echo $category->id();?>"><?php echo $category->category();?></option>
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
	foreach($word_list as $item) {
		$cats = get_category_by_word($item);
		if(!match_word($item) || !match_language($item) || !match_category($item)) {
			continue;
		}
		foreach($cats as $cat) {
?>
						<tr class="<? echo ($odd) ? 'highlight' : 'lowlight'; ?>">
							<td>
								<?php echo $item->word() . "\n"; ?>
							</td>
							<td>is</td>
							<td>
								<?php echo $cat->category() . "\n"; ?>
							</td>
							<td>
								<form method="post" action="words.php">
									<input type="hidden" value="<?php echo $item->id();?>" name="word_id">
									<input type="hidden" value="<?php echo $cat->id();?>" name="category_id">
									<input type="submit" value="delete" name="change_is">
								</form>
							</td>
						</tr>
<?php
			$odd=!$odd;
		}
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