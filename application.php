<?php

$err = "";
$has_err = false;

function generateRandomString($length = 10) {
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, $charactersLength - 1)];
	}
	return $randomString;
}

function writeToFile($data, $fileName) {
	$fileOpen = fopen("./pastes/" . $fileName, "w") or die("Unable to open file!");
	$fileText = $data;
	fwrite($fileOpen, $fileText);
	fclose($fileOpen);
}

if (isset($_POST['submit'])) {

	// Remove files from past 30 days.
	$dir = "./pastes/";
	foreach (glob($dir."*") as $file) {
		if (filemtime($file) < time() - 2592000) { // 30 days
    		unlink($file);
    	}
	}

	// Validate size of paste.
	if (strlen($_POST['text']) >= 64000) {
		$err = "Your paste is too long.";
        $has_err = true;
	} else {
		$key = strtolower($_POST["key"]);

        // Check for "php" in key.
        if (strpos($key, "php") !== false) {
            $err = "Your paste contains a forbidden word.";
            $has_err = true;
        }

        // Regex to check key for non-alphanumeric characters.
        if (preg_match('/[^A-Za-z0-9]/', $key)) {
            $err = "Your paste contains a forbidden word.";
            $has_err = true;
        }

        if ($has_err == false) {
            if ($key == "") {
                $extension = generateRandomString(8);
                $fileName = $extension . ".txt";
                writeToFile($_POST['text'], $fileName);
                header('Location: /pastes/' . $fileName);
                exit();
            } else {
                $extension = generateRandomString(8);
                $fileName = $extension . "." . $key . ".txt";
                writeToFile($_POST['text'], $fileName);
                header('Location: /pastes/' . $fileName);
                exit();
            }
        }
	}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Text Hosting Script</title>
	<meta name="robots" content="noindex,nofollow">
</head>
<body>
	<h3>Text Hosting</h3>
	<?php 
		if ($err != "") {
			echo "<p>" . $err . "</p>";
		}
	?>
	<form action="#" method="post" style="height:100%">
		<textarea type="text" rows="20" cols="4" size="50" name="text" style="width:100%;height:95%"></textarea><br><br>
		<input name="key" placeholder="text key [optional]" type="text" style="height:100%; width:20%"><br>
		<input name="submit" type="submit" style="height:100%; width:20%" value="create paste">
	</form>
</body>
</html>
