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
		else if (!array_key_exists("method", $data) or !array_key_exists("params", $data)) {
			$response["status"] = RESPONSE_ERROR;
			$response["status-code"] = STATUS_BAD_REQUEST;
		}
		else {
			// Add or update URL entry
			if ($data["method"] == "publish") {
				$database[$data["params"]["id"]] = $data["params"]["url"];
				$response["status"] = RESPONSE_SUCCESS;
				$response["status-code"] = STATUS_OK;				
			}
			// Add URL entry if ID doesn't exist
			else if ($data["method"] == "add") {
				if (array_key_exists($data["params"]["id"], $database)) {
					$response["status"] = RESPONSE_ERROR;
					$response["message"] = MESSAGE_ALREADY_EXISTS;
					$response["status-code"] = STATUS_FORBIDDEN;					
				}
				else {
					$database[$data["params"]["id"]] = $data["params"]["url"];
					$response["status"] = RESPONSE_SUCCESS;
					$response["status-code"] = STATUS_CREATED;				
				}
			}
			// Delete URL entry
			else if ($data["method"] == "delete") {
				if (array_key_exists($data["params"]["id"], $database)) {
					unset($database[$data["params"]["id"]]);
					$response["status"] = RESPONSE_SUCCESS;
					$response["status-code"] = STATUS_OK;					
				}
				else {
					$database[$data["params"]["id"]] = $data["params"]["url"];
					$response["status"] = RESPONSE_SUCCESS;
					$response["message"] = MESSAGE_ALREADY_DELETED;
					$response["status-code"] = STATUS_OK;				
				}
			}
			// Write database back to file
			if ($response["status"] == RESPONSE_SUCCESS) {
				if (!file_put_contents("database.json", json_encode($database))){
					$response["status"] = RESPONSE_ERROR;
					$response["status-code"] = STATUS_SERVER_ERROR;
				}
			}
		}

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
		header("Location: " . $database[$id], true, 307);
		exit();
	}
?>