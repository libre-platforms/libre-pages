<?php
  namespace Tests;

  use Framework\TestCase;

  class ExampleTest extends TestCase {
    function test_example() {
      $this->assert(true, 'This test assertion should succeed!');
    }

    function test_fail() {
      $this->assert(false, 'This test assertion should fail!');
    }
  }