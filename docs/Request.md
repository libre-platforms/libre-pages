# Framework\\Request
A wrapper around the incoming request.
It is intended as a bridge between the actual data fields (like `$_POST` or `$_SERVER`);
Therefore, testability of code gets easier.

## Fields

- `method` the HTTP verb of the request
- `path` the 
