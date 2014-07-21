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
					</table>
				</section>
