<?php
if (isset($_POST['submit'])) {
	$dir = "./pastes/";
	foreach (glob($dir."*") as $file) {
		if (filemtime($file) < time() - 2592000) { // 30 days
    		unlink($file);
    }
}
	if (strlen($_POST['text']) >= 64000) {
		echo "<p>This paste is more than 500kb.</p>";
	} else {
		if (isset($_POST['key'])) {
			$key = $_POST['key'];
			if (strpos($key, 'php') !== false) {
				echo "<p>That key is not allowed.</p>";
			} else {
				$charSet = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
				$charLength = strlen($charSet);
				$extension = '';
				for ($i = 0; $i < 7; $i++) { $extension .= $charSet[rand(0, $charLength - 1)]; }
   				$fileName = '' . $extension . '.' . $key . '.txt';
				$fileOpen = fopen("./pastes/" . $fileName, 'w');
				$fileText = $_POST['text'];
				fwrite($fileOpen, $fileText);
				fclose($fileOpen);
				# YOU WILL NEED TO MODIFY HEADER LOCATIONS TO REDIRECT TO THE APPROPRIATE PATH.
				header('Location: /pastes/' . $fileName);
				exit();
			}
		} else {
			$charSet = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$charLength = strlen($charSet);
			$extension = '';
			for ($i = 0; $i < 7; $i++) { $extension .= $charSet[rand(0, $charLength - 1)]; }
   			$fileName = '' . $extension . '.txt';
			$fileOpen = fopen("./pastes/" . $fileName, 'w');
			$fileText = $_POST['text'];
			fwrite($fileOpen, $fileText);
			fclose($fileOpen);
			# YOU WILL NEED TO MODIFY HEADER LOCATIONS TO REDIRECT TO THE APPROPRIATE PATH.
			header('Location: /pastes/' . $fileName);
			exit();
		}
	}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Text Hosting Script</title>
</head>
<body>
	<h3>Text Hosting</h3>
	<form action="#" method="post" style="height:100%">
		<textarea type="text" rows="20" cols="4" size="50" name="text" style="width:100%;height:95%"></textarea><br><br>
		<input name="key" placeholder="text key [optional]" type="text" style="height:100%; width:20%"><br>
		<input name="submit" type="submit" style="height:100%; width:20%" value="create paste">
	</form>
</body>
</html>
