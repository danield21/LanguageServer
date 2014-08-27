<?php
	function log_info($message) {
		$author = (isset($_SESSION['language_server_user'])) ? $_SESSION['language_server_user'] : 'Unknown User';
		
		$time = date("l jS \of F Y h:i:s A");
		
		$entry = $time . "\nUser: " . $author . "\nAction: " . $message . "\n\n";
		$written = file_put_contents('../logs.txt', $entry, FILE_APPEND | LOCK_EX) !== false;
		if(!$written) {
			echo "Warning: Could not write to log file. Displaying logging here\n<br>" . $entry;
		}
		return $written;
	}
?>
