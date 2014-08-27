<?php
	$ctable = new CategoriesTable(IP, USER, PASSWORD, DATABASE);
	$clist = $ctable->get_all();
	
	$ltable = new LanguagesTable(IP, USER, PASSWORD, DATABASE);
	$llist = $ltable->get_all();
	
	$table = new WordsTable(IP, USER, PASSWORD, DATABASE);
	$list = $table->get_all();
	
	if($setting->min_admin <= $current_user->admin) {
		if(isset($_POST["add"]["change"])) {
			$table->add(new Word($_POST["add"]));
		}
		if(isset($_POST["item"])) {
			foreach($_POST["item"] as $item) {
				if(isset($item["change"])) {
					if($item["change"] === "edit") {
						echo (new Word($item))->word;
					}
					if($item["change"] === "delete") {
						echo (new Word($item))->word;
					}
				}
			}
		}
	}
?>
				<script src="../script/jquery.js"></script>
				<script src="../script/javascript.js"></script>
				<script src="../script/search.js"></script>
<?php
	$num_orphaned_words = count($table->get_no_category());
	if($num_orphaned_words) {
?>
				<div id="orphan" class="warning">
					<p>Warning: There are <?php echo $num_orphaned_words;?> words that are not assigned any category</p>
					<p>In order to find which words are not used, use the "none" option in the category search area</p>
				</div>
<?
	}
?>
				<form name="searchBar">
					<table id="search">
						<tr>
							<th>Search</th>
						</tr>
						<tr>
							<td>
								Word: <input type="text" name="word"/>
							</td>
						</tr>
						<tr>
							<td>
								Language
							</td>
						</tr>
						<tr>
							<td>
								<table>
									<tr>
<?php
	$first = 1;
	foreach($llist as $key => $language) {
?>
										<td>
											<label for="searchLanguage<?php echo $language->id?>"><input type="radio" id="searchLanguage<?php echo $language->id?>" name="language" value="<?php echo $language->id; ?>"/><?php echo $language->language."\n"; ?>
										</td>
<?php
		if(($key % 5) === 4) {
?>
									</tr><tr>
<?php
		}
	}
?>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td>
								Categories
							</td>
						</tr>
						<tr>
							<td>
								<table>
									<tr>
<?php
	$search_cat = array_merge($clist, [new Category(["category_id" => "0", "category" =>"none", "description" => "Words that fit in none of these categories",])]);
	foreach($search_cat as $key => $category) {
?>
										<td>
											<label for="searchCategory<?php echo $category->id?>"><input type="radio" name="category" id="searchCategory<?php echo $category->id?>" value="<?php echo $category->id; ?>"/><?php echo $category->category; ?></label>
										</td>
<?php
		if(($key % 5) === 4) {
?>
									</tr><tr>
<?php
		}
	}
?>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<th>
								<input type="button" name="search" value="Search"/>
							</th>
						</tr>
					</table>
				</form>
				<form id="category" method="post" action="./?setting=<?php echo $setting->key; ?>">
					<input type="submit" value="Save Changes">
						<table>
<?php
	$odd = true;
	foreach($list as $item) {
?>
							<tr class="<?php echo (($odd) ? 'high' : 'low') . 'light word' . $item->id;?>">
								<td class="fields">
									<div id="stay<?php echo $item->id; ?>">
										<table>
											<tr>
												<td>
													<img src="../images/<?php echo $item->picture_file(); ?>" width="50pt" height="50pt" class="image"/>
													<img src="../images/NotFound404.svg" width="50pt" height="50pt" class="delete" style="display:none;"/>
												</td>
											</tr>
											<tr>
												<td style="text-align: center;" class="word">
													<?php echo $item->word . "\n";?>
												</td>
											</tr>
										</table>
										<div class="word">
										</div>
									</div>
									<div id="edit<?php echo $item->id; ?>" style="display: none;">
										<p class="name">Word</p>
										<input type="text" name="item[<?php echo $item->id; ?>][word]" value="<?php echo $item->word; ?>"/>
										<p class="name">Language</p>
										<p>
											<select name="item[<?php echo $item->id; ?>][language]">
<?php foreach($llist as $l) { ?>
												<option value="<?php echo $l->id; ?>" <?php if($l->id == $item->language_id) echo "selected=\"selected\"";?>><?php echo $l->language; ?></option>
<?php } ?>
											</select>
										</p>
										<p>
											<p class="name">Image</p>
											<img src="<?php echo '../images/' . $item->picture_file();?>">
											<input type="file" name="item[<?php echo $item->id; ?>][picture_file]">
										</p>
										<p>
											<p class="name">Primary Sound</p>
											<audio controls="controls">
												<source src="<?php echo '../sounds/' . $item->pr_sound_file();?>">
											</audio>
											<input type="file" name="item[<?php echo $item->id; ?>][primary_sound_file]">
										</p>
										<p>
											<p class="name">Secondary Sound</p>
											<audio controls="controls">
												<source src="<?php echo '../sounds/' . $item->sec_sound_file();?>">
											</audio>
											<input type="file" name="item[<?php echo $item->id; ?>][secondary_sound_file]">
										</p>
									</div>
								</td>
								<td class="boxes">
									<div>
										<label for="editbox<?php echo $item->id; ?>">
											<input type="checkbox" value="edit" name="item[<?php echo $item->id; ?>][change]" id="editbox<?php echo $item->id; ?>" onchange="makeEditable(<?php echo $item->id; ?>)">
											Edit
										</label>
									</div>
									<div>
										<label for="deletebox<?php echo $item->id; ?>">
											<input type="checkbox" value="delete" name="item[<?php echo $item->id; ?>][change]" id="deletebox<?php echo $item->id; ?>" onchange="makeDeletable(<?php echo $item->id; ?>)">
											Delete
										</label>
									</div>
								</td>
							</tr>
<?php
		$odd = !$odd;
	}
?>
						<table>
					<input type="submit" value="Save Changes">
				</form>
				<script>
					$("form").keypress(function (e) {
						var charCode = e.charCode || e.keyCode || e.which;
						if (charCode  == 13) {
							searchAJAX();
							return false;
						}
					});
					
					createAJAX = function() {
						var AJAXcall = {
							type: "POST",
							url: '../php/RetrieveData.php',
							data: {
								type: "words"
							}
						};
						var isCat = (searchBar.category.value != "");
						var isLan = (searchBar.language.value != "");
						if(isCat && isLan) {
							if(searchBar.category.value != "0") {
								AJAXcall.data.filter = "both";
								AJAXcall.data.args = [searchBar.category.value, searchBar.language.value];
							} else {
								AJAXcall.data.filter = "ncat_ylan";
								AJAXcall.data.args = [searchBar.language.value];
							}
						}else if(isCat) {
							if(searchBar.category.value != "0") {
								AJAXcall.data.filter = "category";
								AJAXcall.data.args = [searchBar.category.value];
							} else {
								AJAXcall.data.filter = "no_category";
							}
						}else if(isLan) {
							AJAXcall.data.filter = "language";
							AJAXcall.data.args = [searchBar.language.value];
						} else {
							AJAXcall.data.filter = "all";
						}
						console.log(AJAXcall);
						return AJAXcall;
					};
					searchAJAX = function () {
						$.ajax(createAJAX()).done(
							function(msg) {
								var words = $.parseJSON(msg);
								$('tr.lowlight').css('display', 'none');
								$('tr.highlight').css('display', 'none');
								for(var i = 0; i < words.length; ++i) {
									if(removeDiacritics(words[i].word.toLowerCase()).indexOf(removeDiacritics(searchBar.word.value).toLowerCase()) > -1) {
										$('tr.word' + words[i].id).css('display', 'table-row');
									}
								}
							}
						)
					};
					$('input[name="search"]').click(searchAJAX);
					
					function makeAddable() {
						if($("#add").is(":checked")) {
						} else {
						}
					}
					function makeEditable(id) {
						if($("#editbox" + id).is(":checked")) {
							if($("#deletebox" + id).is(":checked")) {
								$("#deletebox" + id).prop('checked', false);
								$("#stay" + id + " .word").css("text-decoration", "none");
								$("#stay" + id + " .image").css("display", "initial");
								$("#stay" + id + " .delete").css("display", "none");
							}
							$("#stay" + id).css("display", "none");
							$("#edit" + id).css("display", "initial");
						} else {
							$("#stay" + id).css("display", "initial");
							$("#edit" + id).css("display", "none");
						}
					}
					function makeDeletable(id) {
						if($("#deletebox" + id).is(":checked")) {
							if($("#editbox" + id).is(":checked")) {
								$("#editbox" + id).prop('checked', false);
								$("#edit" + id).css("display", "none");
								$("#stay" + id).css("display", "initial");
							}
							$("#stay" + id + " .word").css("text-decoration", "line-through");
							$("#stay" + id + " .image").css("display", "none");
							$("#stay" + id + " .delete").css("display", "initial");
						} else {
							$("#stay" + id + " .word").css("text-decoration", "none");
							$("#stay" + id + " .image").css("display", "initial");
							$("#stay" + id + " .delete").css("display", "none");
						}
					}
					function createRelation(parent, child, check) {
						
					}
					function removeRelation(parent, child, check) {
					}
				</script>