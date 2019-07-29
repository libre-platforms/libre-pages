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

namespace Tests;

use Framework\{
  Request, Response, TestCase, Validation
};

class ValidationTest extends TestCase {
  private $request;
  private $response;
  private $dummy_next;

  function before_test_suite() {
    $this->response = new Response();
    $this->dummy_next = function() { };
  }

  function before_test() {
    $this->request = new Request();
  }

  function test_marks_missing_body_field() {
    $val = Validation::body('foo');
    $val($this->request, $this->response, $this->dummy_next);
    $this->assert(isset($this->request->validation_errors['foo']), 'Expected missing field to be present in validation errors!');
  }
}