<?php
	$table = new CategoriesTable(IP, USER, PASSWORD, DATABASE);
	
	if($admin) {
		if(isset($_POST["add"]["change"])) {
			echo (new Category($_POST["add"]))->category;
		}
		foreach($_POST["item"] as $item) {
			if(isset($item["change"])) {
				if($item["change"] === "edit") {
					echo (new Category($item))->category;
				}
				if($item["change"] === "delete") {
					echo (new Category($item))->category;
				}
			}
		}
	}
	
	$list = $table->get_all();
?>
				<script>
					function makeAddable() {
						if(document.getElementById("add").checked) {
						} else {
						}
					}
					function makeEditable(id) {
						if(document.getElementById("editbox" + id).checked) {
							document.getElementById("deletebox" + id).checked = false;
							document.getElementById("stay" + id).style.display = "none";
							document.getElementById("edit" + id).style.display = "initial";
							document.getElementById("delete" + id).style.display = "none";
						} else {
							document.getElementById("stay" + id).style.display = "initial";
							document.getElementById("edit" + id).style.display = "none";
							document.getElementById("delete" + id).style.display = "none";
						}
					}
					function makeDeletable(id) {
						if(document.getElementById("deletebox" + id).checked) {
							document.getElementById("editbox" + id).checked = false;
							document.getElementById("stay" + id).style.display = "none";
							document.getElementById("edit" + id).style.display = "none";
							document.getElementById("delete" + id).style.display = "initial";
						} else {
							document.getElementById("stay" + id).style.display = "initial";
							document.getElementById("edit" + id).style.display = "none";
							document.getElementById("delete" + id).style.display = "none";
						}
					}
				</script>
				<form id="category" method="post" action="./?setting=<?php echo $option->key; ?>">
					<input type="submit" value="Save Changes">
						<table>
<?php
	$odd = true;
	foreach($list as $item) {
		$num_direct_children = count($table->get_direct_children($item));
		$children = $table->get_all_children($item);
		$num_direct_parents = count($table->get_direct_parents($item));
		$parents = $table->get_all_parents($item);
?>
							<tr class="<?php echo ($odd) ? 'highlight' : 'lowlight';?>">
								<td class="fields">
									<div id="stay<?php echo $item->id; ?>">
										<div class="category">
											<?php echo $item->category . "\n";?>
										</div>
										<div class="description">
											<?php echo $item->description . "\n";?>
										</div>
									</div>
									<div id="edit<?php echo $item->id; ?>" style="display: none;">
										<p class="name">Title</p>
										<input type="text" name="item[<?php echo $item->id; ?>][category]" value="<?php echo $item->category; ?>"/>
										<p class="name">Description</p>
										<input type="text" name="item[<?php echo $item->id; ?>][description]" value="<?php echo $item->description; ?>"/>
										<div class="relationship">
											<div style="float: left;">
												<p class="name">Parents:</p>
<?php
			if(isset($parents[0])) {
				foreach($parents as $parent) {
?>
												<div class="parent<?php echo $item-id; ?>child<?php echo $parent->id?>">
													<?php echo $parent->category; ?>
													<input type="checkbox">
												</div>
<?php
				}
			} else {
				echo "none";
			}
?>
											</div>
											<div style="float: right;">
												<p class="name">Children:</p>
<?php
			if(isset($children[0])) {
				foreach($children as $child) {
?>
												<div class="parent<?php echo $item-id; ?>child<?php echo $child->id?>">
													<?php echo $child->category; ?>
													<input type="checkbox">
												</div>
<?php
				}
			} else {
				echo "none";
			}
?>
											</div>
										</div>
									</div>
									<div id="delete<?php echo $item->id; ?>" style="display: none; text-decoration:line-through;">
										<div class="category">
											<?php echo $item->category . "\n";?>
										</div>
										<div class="description">
											<?php echo $item->description . "\n";?>
										</div>
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
