<?php
/**
 * This file is part of LibrePages.
 *
 * LibrePages is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * LibrePages is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with LibrePages.  If not, see <https://www.gnu.org/licenses/>.
 * 
 * @author    Jörn Neumeyer <contact@joern-neumeyer.de>
 * @copyright 2019 Jörn Neumeyer
 */

namespace Tests;

use Pages\{
  Request, Response, TestCase, Validation
};

/**
 * Test for the defined validators.
 */
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
  
  function test_valid_float_passes() {
    $val = Validation::body('foo')->is_float();
    $this->request->body['foo'] = 2.3;
    $val($this->request, $this->response, $this->dummy_next);
    $this->assert(!isset($this->request->validation_errors['foo']), 'Expected valid field not to be marked in the validation errors!');
  }
  
  function test_invalid_float_fails() {
    $val = Validation::body('foo')->is_float();
    $this->request->body['foo'] = '123';
    $val($this->request, $this->response, $this->dummy_next);
    $this->assert(isset($this->request->validation_errors['foo']), 'Expected invalid field to be marked in the validation errors!');
  }
  
  function test_valid_int_passes() {
    $val = Validation::body('foo')->is_int();
    $this->request->body['foo'] = 2;
    $val($this->request, $this->response, $this->dummy_next);
    $this->assert(!isset($this->request->validation_errors['foo']), 'Expected valid field not to be marked in the validation errors!');
  }
  
  function test_invalid_int_fails() {
    $val = Validation::body('foo')->is_int();
    $this->request->body['foo'] = 2.3;
    $val($this->request, $this->response, $this->dummy_next);
    $this->assert(isset($this->request->validation_errors['foo']), 'Expected invalid field to be marked in the validation errors!');
  }
  
  function test_valid_numeric_passes() {
    $val = Validation::body('foo')->is_numeric();
    $this->request->body['foo'] = '23';
    $val($this->request, $this->response, $this->dummy_next);
    $this->assert(!isset($this->request->validation_errors['foo']), 'Expected valid field not to be marked in the validation errors!');
  }
  
  function test_invalid_numeric_fails() {
    $val = Validation::body('foo')->is_numeric();
    $this->request->body['foo'] = '45a';
    $val($this->request, $this->response, $this->dummy_next);
    $this->assert(isset($this->request->validation_errors['foo']), 'Expected invalid field to be marked in the validation errors!');
  }
  
  function test_valid_array_passes() {
    $val = Validation::body('foo')->is_array();
    $this->request->body['foo'] = [1];
    $val($this->request, $this->response, $this->dummy_next);
    $this->assert(!isset($this->request->validation_errors['foo']), 'Expected valid field not to be marked in the validation errors!');
  }
  
  function test_invalid_array_fails() {
    $val = Validation::body('foo')->is_array();
    $this->request->body['foo'] = '45a';
    $val($this->request, $this->response, $this->dummy_next);
    $this->assert(isset($this->request->validation_errors['foo']), 'Expected invalid field to be marked in the validation errors!');
  }

  function test_valid_request_is_not_rejected() {
    $val = Validation::query('foo');
    $this->request->query['foo'] = 123;
    $val($this->request, $this->response, $this->dummy_next);
    $response = Validation::reject_on_error($this->request, $this->response, $this->dummy_next);
    $this->assert($this->response->status() === 200, 'Expected response code to keep default value!');
    $this->assert($response === ($this->dummy_next)(), 'Expected response to be dummt_next return value!');
  }

  function test_invalid_request_is_rejected() {
    $val = Validation::query('foo');
    $val($this->request, $this->response, $this->dummy_next);
    Validation::reject_on_error($this->request, $this->response, $this->dummy_next);
    $this->assert($this->response->status() === 422, 'Expected response code to keep default value!');
  }
}