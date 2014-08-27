<?php
	$table = new AccountsTable(IP, USER, PASSWORD, DATABASE);
	
	if($setting->min_admin <= $current_user->admin) {
		if(isset($_POST["add"]["change"])) {
			$newitem = new Account($_POST["add"]);
			if(($_POST["add"]["password"] != "") && ($_POST["add"]["password"] === $_POST["add"]["confirm"])) {
				$table->add($newitem, $_POST["add"]["password"]);
			} else {
				echo "Warning: Passwords for " . $newitem->user . " did not match";
			}
		}
		if(isset($_POST["item"])) {
			foreach($_POST["item"] as $item) {
				$newitem = new Account($item);
				if(isset($item["change"])) {
					if($item["change"] === "edit") {
						$table->edit($newitem);
					}
					if($item["change"] === "delete") {
						$table->delete($newitem);
					}
				}
				if(isset($item["change_password"])) {
					if(($item["password"] === $item["confirm"])) {
						echo $table->change_password($newitem, $item["password"]) ?
							"Password for " . $newitem->user . " has been successfully changed" :
							"Password for " . $newitem->user . " failed to change";
					} else {
						echo "Warning: Passwords for " . $newitem->user . " did not match";
					}
				}
			}
		}
	}
	
	$list = $table->get_all();
	$admin_levels = ["Normal", "Moderator", "Administrator"];
?>
				<script src="../script/jquery.js"></script>
				<script src="../script/javascript.js"></script>
				<script src="../script/search.js"></script>
				<form id="category" method="post" action="./?setting=<?php echo $setting->key; ?>">
					<input type="submit" value="Save Changes">
						<table>
							<tr class="lowlight">
								<td class="fields">
									<div id="add">
										N/A
									</div>
									<div id="noAdd" style="display: none;">
										<table>
											<tr>
												<td>Username</td>
												<td>
													<input type="text" name="add[username]">
												</td>
											</tr>
											<tr>
												<td>First Name</td>
												<td><input type="text" name="add[first_name]"></td>
											</tr>
											<tr>
												<td>Last Name</td>
												<td><input type="text" name="add[last_name]"></td>
											</tr>
											<tr>
												<td>Level</td>
												<td>
													<select name="add[admin]">
<?php
		foreach($admin_levels as $value => $level) {
?>
														<option value="<?php echo $value; ?>"><?php echo $level; ?></option>
<?php
		}
?>
													</select>
												</td>
											</tr>
											<tr>
												<td>Password:</td>
												<td><input type="password" name="add[password]"></td>
											<tr>
											</tr>
												<td>Confirm:</td>
												<td><input type="password" name="add[confirm]"></td>
											</tr>
										</table>
									</div>
								</td>
								<td class="boxes">
									<div>
										<label for="addbox">
											<input type="checkbox" value="add" name="add[change]" id="addbox" onchange="makeAddable()">
											add
										</label>
									</div>
								</td>
							</tr>
<?php
	$odd = true;
	foreach($list as $item) {
?>
							<tr class="<?php echo (($odd) ? 'high' : 'low') . 'light';?>">
								<td class="fields">
									<div id="stay<?php echo $item->id; ?>">
										<?php echo $item->user . "\n";?>
									</div>
									<div id="edit<?php echo $item->id; ?>" style="display: none;">
										<table>
											<tr>
												<td>Username</td>
												<td>
													<input type="hidden" value="<?php echo $item->id; ?>" name="item[<?php echo $item->id; ?>][account_id]">
													<input type="text" value="<?php echo $item->user?>" name="item[<?php echo $item->id; ?>][username]">
												</td>
											</tr>
											<tr>
												<td>First Name</td>
												<td><input type="text" value="<?php echo $item->first_name?>" name="item[<?php echo $item->id; ?>][first_name]"></td>
											</tr>
											<tr>
												<td>Last Name</td>
												<td><input type="text" value="<?php echo $item->last_name?>" name="item[<?php echo $item->id; ?>][last_name]"></td>
											</tr>
											<tr>
												<td>Level</td>
												<td>
													<select name="item[<?php echo $item->id; ?>][admin]">
<?php
		foreach($admin_levels as $value => $level) {
?>
														<option value="<?php echo $value; ?>"<?php if($value === $item->admin) echo " selected=\"selected\""?>><?php echo $level; ?></option>
<?php
		}
?>
													</select>
												</td>
											</tr>
											<tr>
												<td><label for="passwordbox<?php echo $item->id; ?>">Change<br/>Password<br/><input type="checkbox" id="passwordbox<?php echo $item->id; ?>" name="item[<?php echo $item->id; ?>][change_password]" onchange="makePassword(<?php echo $item->id; ?>)"></label></td>
												<td id="password<?php echo $item->id; ?>" style="display:none;">
													<table>
														<tr>
															<td>Password:</td>
															<td><input type="password" name="item[<?php echo $item->id; ?>][password]"></td>
														<tr>
														</tr>
															<td>Confirm:</td>
															<td><input type="password" name="item[<?php echo $item->id; ?>][confirm]"></td>
														</tr>
													</table>
												</td>
											</tr>
										</table>
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
					function makeAddable() {
						if($("#addbox").is(":checked")) {
							$("#add").css("display", "none");
							$("#noAdd").css("display", "initial");
						} else {
							$("#add").css("display", "initial");
							$("#noAdd").css("display", "none");
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
							if($("#passwordbox" + id).is(":checked")) {
								$("#passwordbox" + id).prop('checked', false);
								makePassword(id);
							}
						}
					}
					function makeDeletable(id) {
						if($("#deletebox" + id).is(":checked")) {
							if($("#editbox" + id).is(":checked")) {
								$("#editbox" + id).prop('checked', false);
								makeEditable(id);
							}
							$("#stay" + id).css("text-decoration", "line-through");
						} else {
							$("#stay" + id).css("text-decoration", "none");
						}
					}
					
					function makePassword(id) {
						if($("#passwordbox" + id).is(":checked")) {
							$("#password" + id).css("display", "initial");
						} else {
							$("#password" + id).css("display", "none");
						}
					}
				</script>