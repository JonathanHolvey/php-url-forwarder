<?php
	// Load URL database
	$database = json_decode(file_get_contents("database.json"), true);

	// Check forwarding ID
	if (isset($_GET["id"]) and array_key_exists($_GET["id"], $database))
		$id = $_GET["id"];
	else
		$id = "_default";

	// Find destination URL
	$url = $database[$id];

	// Forward browser
	header("Location: " . $url, true, 301);
	exit();
?>