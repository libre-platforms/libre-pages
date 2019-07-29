<?php
//  Copyright (C) 2019 Jörn Neumeyer
//
//  This file is part of LibrePages.
//
//  LibrePages is free software: you can redistribute it and/or modify
//  it under the terms of the GNU Affero General Public License as published by
//  the Free Software Foundation, either version 3 of the License, or
//  (at your option) any later version.
//
//  LibrePages is distributed in the hope that it will be useful,
//  but WITHOUT ANY WARRANTY; without even the implied warranty of
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//  GNU Affero General Public License for more details.
//
//  You should have received a copy of the GNU Affero General Public License
//  along with LibrePages.  If not, see <https://www.gnu.org/licenses/>.
  namespace Framework;

  class TestCase {
    private $expected_exception = null;
    private $assertions = [];
    private $parameterized_tests = [];

    /**
     * Expectes an assertion to be thrown in the current test.
     * 
     * @param string $type The class type of the exception which is expected to be thrown.
     */
    function expect_exception(string $type, string $message = 'Expected Exception!') {
      if (!is_subclass_of($type, \Throwable::class)) {
        throw new \Exception('You may only expect exceptions of types derived by \\Throwable!');
      }
      $this->expected_exception = [$type, $message];
    }

    /**
     * Resets the internal state of the test suite, to prepare it for the next test to be run.
     */
    private function reset_for_next_test() {
      $this->expected_exception = null;
      $this->assertions = [];
    }

    /**
     * Runs before every test in the suite is run.
     * This method may be used to perform testing setup.
     */
    function before_test() { }

    /**
     * Runs after every test in the suite is run.
     * This method may be used to perform test clean up.
     */
    function after_test() { }

    /**
     * Runs before before the test suite is run.
     */
    function before_test_suite() { }

    /**
     * Runs after the test suite has run.
     */
    function after_test_suite() { }

    /**
     * Asserts the given condition to evaluate to true.
     * 
     * @param string $failed_message The message to be displayed, if the assertion fails.
     */
    function assert($condition, string $failed_message = 'Assertion failed!') {
      $this->assertions[] = [$condition, $failed_message];
    }

    function parameterized(string $test_name, array $data) {
      $this->parameterized_tests[$test_name] = $data;
    }

    /**
     * Retrieves all available test methods the the current test suite.
     * 
     * @return array
     */
    private function get_tests() {
      $ref = new \ReflectionObject($this);
      $methods = $ref->getMethods();
      $tests = [];

      foreach ($methods as $method) {
        if (\strpos($method->name, 'test') === 0) {
          $tests[] = $method;
        }
      }

      return $tests;
    }

    /**
     * Runs the current test suite and returns a collection of the run tests and the result of their assertions.
     * 
     * @return array
     */
    function run() {
      $tests = $this->get_tests();
      $suite_result = [];

      $run_test = function($test, $args = []) {
        try {
          $test->invoke($this, ...$args);

          if ($this->expected_exception !== null) {
            return [false, "Missing Exception! Expected exception of type {$this->expected_exception[0]}!"];
          } else {
            return;
          }
        } catch (\Throwable $ex) {
          $exception_type = get_class($ex);
          if ($this->expected_exception === null) {
            return [false, "Encountered unexpected exception of type {$exception_type}! Message: {$ex->getMessage()};"];
          } else {
            if (!is_a($ex, $this->expected_exception[0])) {
              return [false, "Mismatched exception type! Expected {$this->expected_exception[0]} but got {$exception_type}! Message: ".$this->expected_exception[1]];
            }
          }
        }
      };

      if (count($tests) === 0) {
        return $suite_result;
      }

      $this->before_test_suite();

      foreach ($tests as $test) {
        $this->reset_for_next_test();
        $test_result = [];
        if (isset($this->parameterized_tests[$test->name])) {
          $data = $this->parameterized_tests[$test->name];
          foreach ($data as $entry) {
            $this->before_test();
            $result_buffer = $run_test($test, $entry);
            $this->after_test();
            if ($result_buffer) {
              $test_result[]  = $result_buffer;
            } else {
              $test_result = array_merge($test_result, $this->assertions);
            }
            $this->reset_for_next_test();
          }
        } else {
          $this->before_test();
          $result_buffer = $run_test($test);
          $this->after_test();
          if ($result_buffer) {
            $test_result[] = $result_buffer;
          } else {
            $test_result = $this->assertions;
          }
        }

        $suite_result[$test->name] = $test_result;
      }

      $this->after_test_suite();

      return $suite_result;
    }
  }