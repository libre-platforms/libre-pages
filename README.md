# LibrePages
LibrePages is a free software project to provide a simple set of tools to easily build web applications using PHP.
Its focus does not lie on the development of fullstack applications, but on REST API development.

## Documentation

- [Routing](#routing)
- [Testing](#testing)

### Routing

### Testing
Writing tests is fairly simple.
All you have to do is to add a `.php` file to the folder `tests`.
I.e. the file `tests/FooTest.php` should define the class `FooTest` in the namespace `Tests`.
This naming is mandatory for the autoloader to properly function.

Your test suite (class) should inherit from `Framework\TestCase`.
This allows you to use core testing functionalities:

- assertions
- expecting exceptions
- test(-suite) setup/teardown

These features are probably not all you would like to have, but everything you __need__ in order to test your code.
In case you think you need more functionality, just add it to the `Framework\TestCase` class.

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

#### before_test()
This method is executed before every test.
It should be used to perform necessary setup before each test.

#### after_test()
This method is executed after every test.
It should be used to perform necessary teardown after each test.

#### before_test_suite()
This method is run before the first test of the test suite is run.
One good usecase would be the initialization of the test suite with test data, which cannot be assigned in a static context.

#### after_test_suite()
This method is run after the last test of the test suite is run.

#### $this->assert($condition[, $failed_message])
Via the `assert` method, you can add an assertion to the current test.


## License
LibrePages is free software: you can redistribute it and/or modify
it under the terms of the GNU Affero General Public License as
published by the Free Software Foundation, either version 3 of the
License, or (at your option) any later version.

A copy of the license may be found in the file [`COPYING`](./COPYING) or online
([version 3 of the GNU Affero General Public License](https://www.gnu.org/licenses/agpl-3.0.en.html))