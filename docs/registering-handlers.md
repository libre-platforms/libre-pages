# Registering Handlers
Registering handlers for requests is simple and straight forward.
The file `routes.php` is intended for the registration of all these handlers.

Handlers may be registered for the HTTP verbs `GET`, `POST`, `PUT`, `DELETE`.

As an example, let's register a handler for the path `/` via the HTTP verb `GET`:

```php
$router->get('/', function($request, $response) {
  $response->write('Hello World!');
  return $response;
});
```

As shown, to register a handler, you call a method corresponding to the name of the HTTP verb for which you want to register a new handler.
The first parameter of that handler registration method is the `route` (type `string`), which has to be matched by the requested path.
The second parameter of that method is the `handler` (type `callable`) of that route.
