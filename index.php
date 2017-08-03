<?php
	include_once("strings.php");

	// Load URL database
	$database = json_decode(file_get_contents("database.json"), true);
	$action = $_GET["action"];

	if ($action == "api") {
		// Retrieve JSON from POST data
		$data = json_decode(file_get_contents("php://input"), true);
		if ($data == null)
			$data = array();

		$response = array();

		// Authenticate by checking provided secret
		if (!array_key_exists("secret", $data) or $data["secret"] != file_get_contents("secret")) {
			$response["status"] = RESPONSE_ERROR;
			$response["message"] = MESSAGE_BAD_AUTH;
			$response["status-code"] = STATUS_UNAUTHORIZED;
		}
		else
			$response["status"] = RESPONSE_SUCCESS;
			$response["status-code"] = STATUS_OK;

		// Return response as JSON
		echo json_encode($response);
	}

	else if ($action == "forward") {
		// Check forwarding ID
		if (isset($_GET["id"]) and array_key_exists($_GET["id"], $database))
			$id = $_GET["id"];
		else
			$id = "_default";

		// Forward browser
		header("Location: " . $database[$id], true, 301);
		exit();
	}
?>