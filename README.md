# LibrePages
LibrePages is a free software project to provide a simple set of tools to easily build web applications using PHP.
Its focus does not lie on the development of fullstack applications, but on REST API development.

## Documentation

- [Routing](#routing)
- [Testing](#testing)

### Routing
Every web applicatttion needs some kind of routing mechanism.
Even if just use raw folder and file structures.
However, having endpoints like `/users` is nicer than `/users.php`.
Such endpoints also allow for more resource driven endpoint development, as you can just naturally go into more detail about the resource (e.g. `/users/1/email`).
The router of LibrePages is very simple and easily extendable, in case it does not provide all the HTTP verbs you need.

You may register all routes of your application in the file `routes.php`.
This file automatically receives an instance of the class `Pages\Router`.
That automatic instance is also going to be used by the script `index.php` to process incoming requests.

The following listing showcases the API of the `Pages\Router` class, which is used for route registration.

### Testing
Writing tests is fairly simple.
All you have to do is to add a `.php` file to the folder `tests`.
I.e. the file `tests/FooTest.php` should define the class `FooTest` in the namespace `Tests`.
This naming is mandatory for the autoloader to properly function.

Your test suite (class) should inherit from `Pages\TestCase`.
This allows you to use core testing functionalities:

- assertions
- expecting exceptions
- parameterized testing
- test(-suite) setup/teardown

These features are probably not all you would like to have, but everything you __need__ in order to test your code.
In case you think you need more functionality, just add it to the `Pages\TestCase` class.

#### Registering tests
All methods starting with `test` (like `test_accumulates_properly`) are considered to be tests.
All methods of a test suite will be retrieved via reflection.
Therefore, they will be executed in order of declaration (in case test order that matters for your case).

#### Running tests
To run all your tests, just use `php test.php`.
The script `test.php` can be considered a simple test runner.

By adding the `--color` option to the command, the test result (__success__/__pass__ or __fail__) will be highlighted.

You can also run all tests automatically on file, if you use the script `test-watch.sh`.
__Note:__ This script requires `inotifywait` in order to watch for file changes.
So, make sure `inotify-tools` is installed on your machine.

The following sections describe the methods which `Pages\TestCase` provides.

#### before\_test(): void
This method is executed before every test.
It should be used to perform necessary setup before each test.

Override this method to use it.

#### after\_test(): void
This method is executed after every test.
It should be used to perform necessary teardown after each test.

Override this method to use it.

#### before\_test\_suite(): void
This method is run before the first test of the test suite is run.
One good usecase would be the initialization of the test suite with test data, which cannot be assigned in a static context.

Override this method to use it.

#### after\_test\_suite(): void
This method is run after the last test of the test suite is run.

Override this method to use it.

#### assert($condition[, string $failed\_message]): void
Via the `assert` method, you can add an assertion to the current test.

```php
function test_assertion() {
  $this->assert(1 === 1, 'One should be one!');
}
```

#### expect_exception(string $type[, string $message])
If this method is called, the test is expected to throw an exception of the provided type (name).
The thrown exception has to match the given type and not be of a derived type!

This method also disables regular assertions in a test, as the test's goal is the throw of the expected exception.

```php
function test_throws() {
  $this->expect_exception(\Exception::class);
  throw new \Exception('Works!');
}
```

#### parameterized(string $test_name, array $data)
This method marks a test as parameterized.
The `$data` array should be structured like

```php
[
  ['foo', 'bar', 'foo bar'],
  ['hello', 'world', 'hello world']
]
```

So, the test can be defined like

```php
function test_concat($word1, $word2, $result) {
  $this->assert(some_concat($word1, $word2) === $result, 'Expected concatonation to work!');
}
```

A suitable place to perform the `parameterized` test registration is in the method `before_test_suite`.

#### run(): array
Runs/executes the test suite and returns an array, containing the results of the assertions made in the defined tests.

This method will be executed auttomatically by the test runner.

## License
LibrePages is free software: you can redistribute it and/or modify
it under the terms of the GNU Affero General Public License as
published by the Free Software Foundation, either version 3 of the
License, or (at your option) any later version.

A copy of the license may be found in the file [`COPYING`](./COPYING) or online
([version 3 of the GNU Affero General Public License](https://www.gnu.org/licenses/agpl-3.0.en.html))