<?php
	if(isset($header)) {
		if(!$current_category->is_valid()) {
			require 'categories.php';
		} else	{
?>
					<script src="script/javascript.js"></script>
					<input type="button" onclick="javascript:window.print()" value="Print">
					<table class="word_list">
						<tr>
<?php
			$word_list = $wt->get_by_filter($current_category, $current_language);
			$i = 0;
			foreach($word_list as $word) {
				$i++;
?>
							<td>
								<table class="word">
									<tr>
										<td class="word_pic">
											<img src="images/<?php
												echo $word->picture_file();?>" alt="<?php
												echo $word->word;?>" onmouseover="playSound('sounds/<?php
												echo $word->pr_sound_file(); ?>', 'sounds/<?php
												echo $word->sec_sound_file(); ?>')" onclick="playSound('sounds/<?php
												echo $word->pr_sound_file();?>', 'sounds/<?php echo $word->sec_sound_file(); ?>')">
										</td>
									</tr>
									<tr>
										<td>
											<?php echo $word->word. "\n";?>
										</td>
									</tr>
								</table>
							</td>
<?php
				if($i % 5 == 0) {
?>
						</tr>
						<tr>
<?php
				}
			}
?>
						</tr>
					</table>
					<span id="sound"></span>
					<input type="button" onclick="javascript:window.print()" value="Print">
<?php
		}
	}
?>
