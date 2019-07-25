<?php
  namespace Tests;

  use Framework\TestCase;

  class ExampleTest extends TestCase {
    function test_success() {
      $this->assert(true, 'This test assertion should succeed!');
    }

    function test_fail() {
      $this->assert(false, 'This test assertion fails!');
    }

    function test_invalid_exception() {
      $this->expect_exception(\Exception::class);
      throw new \ErrorException();
    }

    function test_exception() {
      $this->expect_exception(\ErrorException::class);
      throw new \ErrorException();
    }

    function test_missing_exception() {
      $this->expect_exception(\ErrorException::class);
    }
  }