					<table class="categories">
<?php
if(isset($category_list) && isset($current_language) && isset($_GET['activity'])) {
	foreach($category_list as $cat) {
		$word = $wt->get_by_filter($cat, $current_language);
?>
						<tr>
							<th>
								<a href="?language_id=<?php echo $current_language->id; ?>&activity=<?php echo $_GET['activity'];?>&category_id=<?php echo $cat->id; ?>">
									<?php echo $cat->category;?>
								</a>
							</th>
						</tr>
						<tr>
							<td>
<?php
		shuffle($word);
		for($i = 0; (isset($word[$i])) && ($i < 5); ++$i) {
?>
								<img src="<?php echo 'images/' . $word[$i]->id . '.' . $word[$i]->picture;?>" alt="<?php echo $word[$i]->word; ?>">
<?php
		}
?>
							<td>
						</tr>
<?php
	}
}
?>
					</table>
