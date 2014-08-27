<?php
	$table = new CategoriesTable(IP, USER, PASSWORD, DATABASE);
	
	if($setting->min_admin <= $current_user->admin) {
		if(isset($_POST["add"]["change"])) {
			$addedCat = new Category($_POST["add"]);
			$table->add($addedCat);
		}
		if(isset($_POST["item"])) {
			foreach($_POST["item"] as $item) {
				if(isset($item["change"])) {
					$targetedCat = new Category($item);
					if($item["change"] === "edit") {
						$table->edit($target);
					}
					if($item["change"] === "delete") {
						$table->delete($target);
					}
				}
			}
		}
		if(isset($_POST["child"])) {
			foreach($_POST["child"] as $parent => $child) {
				if(isset($child["change"])) {
					$parentCat = $table->get_by_id($parent);
					$childCat = $table->get_by_id($child["value"]);
					$table->add_relationship($childCat, $parentCat);
				}
			}
		}
		if(isset($_POST["parent"])) {
			foreach($_POST["parent"] as $child => $parent) {
				if(isset($parent["change"])) {
					$parentCat = $table->get_by_id($parent["value"]);
					$childCat = $table->get_by_id($child);
					$table->add_relationship($childCat, $parentCat);
				}
			}
		}
		if(isset($_POST["remove"])) {
			foreach($_POST["remove"] as $child => $parent) {
				$table->delete_relationship(
							new Category(["category_id" => $child]),
							new Category(["category_id" => $parent])
						);
			}
		}
	}
	
	$list = $table->get_all();
?>
				<script src="../script/jquery.js"></script>
				<script>
					function makeAddable() {
						if($("#addbox").is(":checked")) {
							$("#noAdd").css("display", "none");
							$("#add").css("display", "initial");
						} else {
							$("#noAdd").css("display", "initial");
							$("#add").css("display", "none");
						}
					}
					function makeEditable(id) {
						if($("#editbox" + id).is(":checked")) {
							if($("#deletebox" + id).is(":checked")) {
								$("#deletebox" + id).prop('checked', false);
								makeDeletable(id);
							}
							$("#stay" + id).css("display", "none");
							$("#edit" + id).css("display", "initial");
						} else {
							$("#stay" + id).css("display", "initial");
							$("#edit" + id).css("display", "none");
							
							$("#addchildbox" + id).prop('checked', false);
							$("#addparentbox" + id).prop('checked', false);
							createRelation("child", id);
							createRelation("parent", id);
						}
					}
					function makeDeletable(id) {
						if($("#deletebox" + id).is(":checked")) {
							if($("#editbox" + id).is(":checked")) {
								$("#editbox" + id).prop('checked', false);
								makeEditable(id);
							}
							$("#stay" + id + " .category").css("text-decoration", "line-through");
							$("#stay" + id + " .description").css("text-decoration", "line-through");
							
							$("#addchildbox" + id).prop('checked', false);
							$("#addparentbox" + id).prop('checked', false);
							createRelation("child", id);
							createRelation("parent", id);
							
						} else {
							$("#stay" + id + " .category").css("text-decoration", "none");
							$("#stay" + id + " .description").css("text-decoration", "none");
						}
					}
					function createRelation(type, id) {
						if($("#add" + type + "box" + id).is(":checked")) {
							$("#add" + type + "for" + id).css("display", "none");
							$("#" + type + "options" + id).css("display", "initial");
						} else {
							$("#" + type + "options" + id).css("display", "none");
							$("#add" + type + "for" + id).css("display", "initial");
						}
					}
					function removeRelation(parent, child, check) {
						$("input:checkbox.parent" + parent + "child" + child).prop("checked", check);
						if(check) {
							$("div.parent" + parent + "child" + child).css("text-decoration", "line-through");
						} else {
							$("div.parent" + parent + "child" + child).css("text-decoration", "none");
						}
					}
				</script>
				<form id="category" method="post" action="./?setting=<?php echo $setting->key; ?>">
					<input type="submit" value="Save Changes">
						<table>
							<tr class="lowlight">
								<td class="fields">
									<div id="add" style="display: none;">
										<div class="category">
											<p class="name">Title</p>
											<input type="text" name="add[category]"/>
										</div>
										<div class="description">
											<p class="name">Description</p>
											<input type="text" name="add[description]"/>
										</div>
									</div>
									<div id="noAdd">
										<div class="category">
											Category
										</div>
										<div class="description">
											Description goes here
										</div>
									</div>
								</td>
								<td class="boxes">
									<label for="addbox">
										<input type="checkbox" value="add" name="add[change]" onchange="makeAddable()" id="addbox"/>
										Add
									</label>
								</td>
							</tr>
<?php
	$odd = true;
	foreach($list as $item) {
		$num_direct_children = count($table->get_direct_children($item));
		$children = $table->get_all_children($item);
		$num_direct_parents = count($table->get_direct_parents($item));
		$parents = $table->get_all_parents($item);
		$not_relative = array();
		foreach($list as $parentchild) {
			if($parentchild->equals($item) || $parentchild->in_array($parents) || $parentchild->in_array($children)) {
				continue;
			}
			$not_relative[] = $parentchild;
		}
		
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
													<table>
														<tr>
															<td>
																<p style="display:inline;" id="addparentfor<?php echo $item->id;?>">Add parent:</p>
																<select style="display:none;" id="parentoptions<?php echo $item->id;?>" name="parent[<?php echo $item->id; ?>][value]">
<?php
			foreach($not_relative as $adopt) {
?>
																	<option value="<?php echo $adopt->id; ?>"><?php echo $adopt->category; ?></option>
<?php
			}
?>
																</select>
																<input type="checkbox" id="addparentbox<?php echo $item->id;?>" onchange="createRelation('parent', <?php echo $item->id;?>)" name="parent[<?php echo $item->id; ?>][change]">
															</td>
															<td>
																&nbsp;
															</td>
														</tr>
													</table>
<?php
			if(isset($parents[0])) {
				foreach($parents as $parent) {
?>
												<div class="parent<?php echo $parent->id; ?>child<?php echo $item->id; ?>">
													<label for="id<?php
echo $item->id; ?>parent<?php
echo $parent->id; ?>">
														<?php echo $parent->category; ?>
														<input class="parent<?php
echo $parent->id; ?>child<?php
echo $item->id; ?>" type="checkbox" onchange="removeRelation(<?php
echo $parent->id; ?>, <?php
echo $item->id; ?>, this.checked);" id="id<?php
echo $item->id; ?>parent<?php
echo $parent->id; ?>" name="remove[<?php echo $item->id;?>]" value="<?php echo $parent->id;?>">
													</label>
												</div>
<?php
				}
			} else {
?>
												none
<?php
			}
?>
											</div>
											<div style="float: right;">
												<p class="name">Children:</p>
													<table>
														<tr>
															<td>
																<p style="display:inline;" id="addchildfor<?php echo $item->id;?>">Add child:</p>
																<select style="display:none;" id="childoptions<?php echo $item->id;?>" name="child[<?php echo $item->id; ?>][value]">
<?php
			foreach($not_relative as $adopt) {
?>
																	<option value="<?php echo $adopt->id; ?>"><?php echo $adopt->category; ?></option>
<?php
			}
?>
																</select>
																<input type="checkbox" id="addchildbox<?php echo $item->id;?>" onchange="createRelation('child', <?php echo $item->id;?>)" name="child[<?php echo $item->id; ?>][change]">
															</td>
															<td>
																&nbsp;
															</td>
														</tr>
													</table>
<?php
			if(isset($children[0])) {
				foreach($children as $child) {
?>
												<div class="parent<?php echo $item->id; ?>child<?php echo $child->id?>">
													<label for="id<?php
echo $item->id; ?>child<?php
echo $child->id; ?>">
													<?php echo $child->category; ?>
													<input class="parent<?php
echo $item->id; ?>child<?php
echo $child->id; ?>" type="checkbox" onchange="removeRelation(<?php
echo $item->id; ?>, <?php
echo $child->id; ?>, this.checked);" id="id<?php
echo $item->id; ?>child<?php
echo $child->id; ?>">
													</label>
												</div>
<?php
				}
			} else {
?>
												none
<?php
			}
?>
											</div>
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
