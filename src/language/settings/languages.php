<?php
	$table = new LanguagesTable(IP, USER, PASSWORD, DATABASE);
	
	if($admin) {
		if(isset($_POST["add"]["change"])) {
			$table->add(new Language($_POST["add"]));
		}
		foreach($_POST["item"] as $item) {
			if(isset($item["change"])) {
				if($item["change"] === "edit") {
					$table->edit(new Language($item));
				}
				if($item["change"] === "delete") {
					$table->delete(new Language($item));
				}
			}
		}
	}
	
	$list = $table->get_all();
?>
				<script>
					function makeAddable() {
						if(document.getElementById("add").checked) {
							document.getElementById("ai").style.display = 'none';
							document.getElementById("al").style.display = 'none';
							document.getElementById("ni").style.display = 'initial';
							document.getElementById("nl").style.display = 'initial';
						} else {
							document.getElementById("ai").style.display = 'initial';
							document.getElementById("al").style.display = 'initial';
							document.getElementById("ni").style.display = 'none';
							document.getElementById("nl").style.display = 'none';
						}
					}
					function makeEditable(id) {
						if(document.getElementById("edit" + id).checked) {
							document.getElementById("delete" + id).checked = false;
							document.getElementById("si" + id).style.display = 'initial';
							document.getElementById("sl" + id).style.display = 'none';
							document.getElementById("el" + id).style.display = 'initial';
							document.getElementById("di" + id).style.display = 'none';
							document.getElementById("dl" + id).style.display = 'none';
						} else {
							document.getElementById("sl" + id).style.display = 'initial';
							document.getElementById("el" + id).style.display = 'none';
						}
					}
					function makeDeletable(id) {
						if(document.getElementById("delete" + id).checked) {
							document.getElementById("edit" + id).checked = false;
							document.getElementById("si" + id).style.display = 'none';
							document.getElementById("sl" + id).style.display = 'none';
							document.getElementById("el" + id).style.display = 'none';
							document.getElementById("di" + id).style.display = 'initial';
							document.getElementById("dl" + id).style.display = 'initial';
						} else {
							document.getElementById("si" + id).style.display = 'initial';
							document.getElementById("sl" + id).style.display = 'initial';
							document.getElementById("di" + id).style.display = 'none';
							document.getElementById("dl" + id).style.display = 'none';
						}
					}
				</script>
				<form id="language" method="post" action="./?setting=<?php echo $option->key; ?>">
					<input type="submit" value="Save Changes">
					<table>
						<tr class="lowlight">
							<th class="id">ID</th>
							<th class="language">Language</th>
							<th class="action" style="text-align:right;">Act</th>
							<th class="action" style="text-align:left;">ion</th>
						</tr>
						<tr class="lowlight">
							<td>
								<div id="ai<?php echo $item->id; ?>" style="display:initial">
									N/A
								</div>
								<div id="ni<?php echo $item->id; ?>" style="display:none">
									<?echo count($list) + 1; ?>
								</div>
							</td>
							<td>
								<div id="al<?php echo $item->id; ?>" style="display:initial">
									N/A
								</div>
								<div id="nl<?php echo $item->id; ?>" style="display:none">
									<input type="text" name="add[language]" width="100%" value="Enter Language">
								</div>
							</td>
							<td>
								<label for="add">
									<input type="checkbox" value="add" name="add[change]" onchange="makeAddable()" id="add">
									Add
								</label>
							</td>
							<td>&nbsp;</td>
						</tr>
<?php
	$odd = true;
	foreach($list as $item) {
?>
						<tr id="item<?php echo $item->id; ?>" class="<?php echo ($odd) ? 'highlight' : 'lowlight';?>">
							<td>
								<div id="si<?php echo $item->id; ?>" style="display:initial">
									<?php echo $item->id . "\n";?>
								</div>
								<div id="di<?php echo $item->id; ?>" style="display:none; text-decoration:line-through;">
									<?php echo $item->id . "\n";?>
								</div>
								<input type="hidden" value="<?php echo $item->id; ?>" name="item[<?php echo $item->id; ?>][language_id]">
							</td>
							<td>
								<div id="sl<?php echo $item->id; ?>" style="display:initial">
									<?php echo $item->language . "\n";?>
								</div>
								<div id="el<?php echo $item->id; ?>" style="display:none">
									<input type="text" value="<?php echo $item->language;?>" name="item[<?php echo $item->id; ?>][language]">
								</div>
								<div id="dl<?php echo $item->id; ?>" style="display:none; text-decoration:line-through;">
									<?php echo $item->language . "\n";?>
								</div>
							</td>
							<td>
								<label for="edit<?php echo $item->id; ?>">
									<input type="checkbox" value="edit" name="item[<?php echo $item->id; ?>][change]" id="edit<?php echo $item->id; ?>" onchange="makeEditable(<?php echo $item->id; ?>)">
									Edit
								</label>
							</td>
							<td>
								<label for="delete<?php echo $item->id; ?>">
									<input type="checkbox" value="delete" name="item[<?php echo $item->id; ?>][change]" id="delete<?php echo $item->id; ?>" onchange="makeDeletable(<?php echo $item->id; ?>)">
									Delete
								</label>
							</td>
						</tr>
<?php
		$odd=!$odd;
	}
?>
					</table>
					<input type="submit" value="Save Changes">
				</form>
