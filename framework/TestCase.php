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

    function expect_exception(?string $type = null) {
      if ($type !== null) {
        if (!is_subclass_of($type, \Throwable::class)) {
          throw new \Exception('You may only expect exceptions of types derived by \\Throwable!');
        }
      }
      $this->expected_exception = [$type, debug_backtrace()[0]];
    }

    private function reset_for_next_test() {
      $this->expected_exception = null;
      $this->assertions = [];
    }

    function before_test() { }
    function after_test() { }
    function before_test_suite() { }
    function after_test_suite() { }

    function assert($condition, string $failed_message = '') {
      $trace = debug_backtrace()[0];
      $this->assertions[] = [$condition, $failed_message, $trace];
    }

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

    function run() {
      $tests = $this->get_tests();
      $suite_result = [];

      if (count($tests) === 0) {
        return;
      }

      $this->before_test_suite();

      foreach ($tests as $test) {
        $test_result = [];

        $this->before_test();

        $this->reset_for_next_test();

        try {
          $test->invoke($this);

          if ($this->expected_exception !== null) {
            $exception_type = $this->expected_exception[0];
            $expected_trace = $this->expected_exception[1];
            $test_result[] = [false, "Missing Exception! Expected exception of type {$exception_type}!", $expected_trace];
          } else {
            $test_result = $this->assertions;
          }
        } catch (\Throwable $ex) {
          $exception_type = get_class($ex);
          if ($this->expected_exception === null) {
            $test_result[] = [false, "Encountered unexpected exception of type {$exception_type}!", null];
          } else {
            if (!is_subclass_of($ex, $this->expected_exception[0])) {
              $test_result[] = [false, "Mismatched exception type! Expected {$this->expected_exception[0]} but got {$exception_type}!", $this->expected_exception[0]];
            }
          }
        }

        $this->after_test();

        $suite_result[$test->name] = $test_result;
      }

      $this->after_test_suite();

      return $suite_result;
    }
  }