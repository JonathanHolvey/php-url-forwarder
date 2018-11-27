<?php
	define(RESPONSE_ERROR, "error");
	define(RESPONSE_SUCCESS, "success");

	define(MESSAGE_BAD_AUTH, "Authentication failed. The secret you provided could not be verified.");
	define(MESSAGE_ALREADY_EXISTS, "The ID provided already exists and cannot be replaced using the 'add' method. Use 'publish' instead.");
	define(MESSAGE_ALREADY_DELETED, "The ID provided does not exist in the database.");

	define(STATUS_OK, 200);
	define(STATUS_CREATED, 201);
	define(STATUS_BAD_REQUEST, 400);
	define(STATUS_UNAUTHORIZED, 401);
	define(STATUS_FORBIDDEN, 403);
	define(STATUS_SERVER_ERROR, 500);
?>
