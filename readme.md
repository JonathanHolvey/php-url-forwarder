# PHP URL forwarder for QR codes

This script forwards visitors of pre-defined URLs used in print and other immutable media to customisable destinations that can be changed at any time. A simple API allows the destinations to be updated automatically, at any time.

Requests are forwarded with a HTTP 307 redirect, meaning browsers shouldn't cache the relationship between the requested and destination URLs. If you make a change, the new destination should be available straight away.


## Installation and setup

Download the repository and extract it to the root of the domain or subdomain intended to host the URL forwarder. When using the API, it is recommended that a SSL encrypted server is used to keep authentication secure.

Create or edit `database.json` in the server root to define your URLs as ID-destination pairs:

```json
{
	"1": "http://example.com/the-destination-url",
	"test": "http://example.com/another-destination",
	"_default": "http://example.com"
}
```

The ID can be a string of any length, but they must be unique. `_default` is reserved for use when the requested ID doesn't exist, or an ID isn't provided.

Request URLs are formatted with the ID following the first slash after the domain. For example, if the forwarder is installed at `qr.examle.com`, then, based on the example `database.json` file above:

| ID     | Request                      | Destination                              |
| :----- | :--------------------------- | :--------------------------------------- |
| `1`    | `http://qr.example.com/1`    | `http://example.com/the-destination-url` |
| `test` | `http://qr.example.com/test` | `http://example.com/another-destination` |
| `a5d`  | `http://qr.example.com/a5d`  | `http://example.com`                     |

The ID `a5d` doesn't exist, so the default destination is used instead.

## API

### Authentication

To use the API you must set a secret on the server that can be used for authentication. Anyone with access to the secret will be able to modify your destination URLs, so make sure you keep it safe.

Create a file called `secret` in the server root and save your secret key in it. It is recommended that you use a long secret, to minimise the risk of brute force attacks.

*Note that no provision has been made in this project for detecting or defending against brute force, DDOS or other attacks.*

### Request structure

API calls must be made with a POST request to the API endpoint `https://example.com/api`. The payload should be a JSON encoded object, providing your authentication secret, the method you are using and any parameters it requires:

```json
{
	"secret": "KrYowrAo0YGHP1ukuZjmj3sfGU7JDVO6UQJAEYbHl07twMNUxKO",
	"method": "publish",
	"params": {
		"id": "a5d",
		"url": "http://example.com/new-destination"
	}
}
```

### Methods

**publish**: Updates an existing URL if its ID exists, or creates a new one if it doesn't.  

**add**: Adds a new URL, only if its ID doesn't already exist.  

**update**: Changes the destination of an existing URL.  

**delete**: Removes a URL from the database.  

All methods require the params `id` and `url`, except for the `delete` method which only requires `id`.

### Responses

The response of an API call consists of a JSON object containing details of how the request was handled. For example:

```json
{
	"status": "error",
	"status-code": 403,
	"message": "The ID provided already exists and cannot be replaced using the 'add' method. Use 'publish' instead."
}
```
