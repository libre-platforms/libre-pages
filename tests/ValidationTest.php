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
    $this->assert(isset($this->request->validation_errors['foo']), 'Expected missing body field to be present in validation errors!');
  }

  function test_does_not_mark_present_body_field() {
    $val = Validation::body('foo');
    $this->request->body['foo'] = 'bar';
    $val($this->request, $this->response, $this->dummy_next);
    $this->assert(!isset($this->request->validation_errors['foo']), 'Expected present body field not to be marked in the validation errors!');
  }

  function test_marks_missing_param_field() {
    $val = Validation::param('foo');
    $val($this->request, $this->response, $this->dummy_next);
    $this->assert(isset($this->request->validation_errors['foo']), 'Expected missing param field to be present in validation errors!');
  }

  function test_does_not_mark_present_param_field() {
    $val = Validation::param('foo');
    $this->request->params['foo'] = 'bar';
    $val($this->request, $this->response, $this->dummy_next);
    $this->assert(!isset($this->request->validation_errors['foo']), 'Expected present param field not to be marked in the validation errors!');
  }

  function test_marks_missing_query_field() {
    $val = Validation::query('foo');
    $val($this->request, $this->response, $this->dummy_next);
    $this->assert(isset($this->request->validation_errors['foo']), 'Expected missing query field to be present in validation errors!');
  }

  function test_does_not_mark_present_query_field() {
    $val = Validation::query('foo');
    $this->request->query['foo'] = 'bar';
    $val($this->request, $this->response, $this->dummy_next);
    $this->assert(!isset($this->request->validation_errors['foo']), 'Expected present query field not to be marked in the validation errors!');
  }

  function test_valid_string_passes() {
    $val = Validation::body('foo')->is_string();
    $this->request->body['foo'] = 'bar';
    $val($this->request, $this->response, $this->dummy_next);
    $this->assert(!isset($this->request->validation_errors['foo']), 'Expected valid field not to be marked in the validation errors!');
  }

  function test_invalid_string_fails() {
    $val = Validation::body('foo')->is_string();
    $this->request->body['foo'] = 1;
    $val($this->request, $this->response, $this->dummy_next);
    $this->assert(isset($this->request->validation_errors['foo']), 'Expected invalid field to be marked in the validation errors!');
  }
}