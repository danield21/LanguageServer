<?php
	if(isset($header)) {
		if(!$current_category->is_valid()) {
			require 'categories.php';
		} else	{
?>
				<script>
function checkForm() {
	var form;
	form = document.practice;
	var array = getListWords();
	var words = form.word;
	var ids = form.wordID;
	for(var i = 0; i < words.length; ++i) {
		var result = checkWord(ids[i].value, words[i].value);
		if(result) {
			if(result.check === 1) {
				words[i].style.backgroundColor = "#00FF00";
				words[i].style.color = "#FFFFFF";
			} else if(result.check === 0) {
				words[i].style.backgroundColor = "#FFFF00";
				words[i].style.color = "#000000";
			} else if(result.check === -1) {
				words[i].style.backgroundColor = "#FF0000";
				words[i].style.color = "#FFFFFF";
			}
		}
	}
}
				</script>
				<script src="script/javascript.js"></script>
				<script src="script/list_of_words.php<?php
if($current_category->is_valid() || $current_language->is_valid()) {
	echo "?";
}
if($current_language->is_valid()){
	echo "language_id=" . $_GET['language_id'];
}
if($current_language->is_valid() && $current_category->is_valid()){
	echo "&";
}
if($current_category->is_valid()) {
	echo "category_id=" . $_GET['category_id'];
}?>"></script>

				<form name="practice">
					<input type="button" value="Submit" onclick="checkForm()">
					<table class="word_list">
						<tr>
<?php
		$word_list = $wt->get_by_filter($current_category, $current_language);
		shuffle($word_list);
		$i = 0;
		foreach($word_list as $word) {
		$i++;
?>
							<td>
								<table class="word">
									<tr>
										<td class="word_pic">
											<label for="word<?php echo $word->id;?>" >
												<img src="images/<?php echo $word->picture_file();?>">
											</label>
										</td>
									</tr>
									<tr>
										<td>
											<input type="text" name="word" id="word<?php echo $word->id;?>">
											<input type="hidden" name="wordID" value="<?php echo $word->id; ?>">
										</td>
									</tr>
									<tr>
										<td id="answer<?php echo $word->id; ?>"></td>
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
					<input type="button" value="Submit" onclick="checkForm()">
				</form>
<?php
	}
}
?>
