<?php
	require_once "Word.php"
	class FileManager {
		private const $images_path = "images";
		private const $audio_path = "sounds";
		
		public function uploadImage($name, $file) {
			if($this->word->is_valid()) {
				return false;
			}
			if(!empty($file['name'])) {
				$this->word->picture = end(explode(".", $file['name']));
			}
		}
	}
?>