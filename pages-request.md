# Requests
The class `Pages\Request` exists, so it gets very easy to access all data related to a request.
Due to all of its data members being public, it is also very easy to set up `Pages\Request`
instances for testing, if the type of the request is hinted.

## Fields

- `string $method` Contains the HTTP verb used for the request in upper case (e.g. `GET` or `POST`)
- `string $path` Contains the path of the URI after the FQDN and port
- `array $params` Contains all parameters which have been defined in the route and fetched from the `$path`
- `array $files` Containes all files sent with the request. The key is the same as in the original form, but the value is an instance of `Pages\RequestFile`, which wraps the standard
- `array $cookies` Contains all cookies sent with the request
- `array $headers` Contains all request HTTP headers
- `array $query` Contains all values provided in the query string a.k.a. GET parameters
- `array $body` Contains all POST fields of a request
- `array $validation_errors` Conains all validation error which were found in the request
- `bool $https` Specifies whether the request is made over HTTPS or HTTP
- `string $server_name` Contains the server name (FQDN)
- `int $server_port` The port to which the request has been sent

## Methods

- `Pages\Request::from_current_request(array& $params = []): Pages\Request` This static helper builds an instance of `Pages\Request` based on the data of PHP's superglobals of the current request