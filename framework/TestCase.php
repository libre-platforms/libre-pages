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
        if (!is_a($type, \Throwable::class)) {
          throw new \Exception('You may only expect exceptions of types derived by \\Throwable!');
        }
      }
      $this->expected_exception = $type;
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
      $this->assertions[] = [$condition, $failed_message];
    }

    function run() {
      $ref = new \ReflectionObject($this);
      $methods = $ref->getMethods();
      $tests = [];

      foreach ($methods as $method) {
        if (\strpos($method->name, 'test') === 0) {
          $tests[] = $method;
        }
      }

      if (count($tests) === 0) {
        return;
      }

      $result = [
        'test_count' => 0,
        'assertion_count' => 0,
      ];

      $this->before_test_suite();

      foreach ($tests as $test) {
        $this->before_test();

        $this->reset_for_next_test();

        $failed_assertion_messages = [];

        try {
          $test->invoke($this);
          foreach ($this->assertions as $assertion) {
            if (!$assertion[0]) {
              $failed_assertion_messages[] = $assertion[1];
            }
          }
        } catch (\Throwable $ex) {
          if ($this->expected_exception === null) {
            $exception_type = gettype($ex);
            $failed_assertion_messages[] = "Encountered unexpected exception of type {$exception_type}!";
          } else {
            if (!is_a($ex, $this->expected_exception)) {

            }
          }
        }

        $this->after_test();
      }

      $this->after_test_suite();
    }
  }