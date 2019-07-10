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

  declare(strict_types=1);
  namespace Framework;

  class Validation {
    protected $_request_data_index;
    protected $_field_name;
    protected $_expected_type;
    protected $_required = true;

    private function __construct(string $request_data_index, string $field_name) {
      $this->_request_data_index = $request_data_index;
      $this->_field_name = $field_name;
    }

    function __invoke(Request &$request, Response &$response, callable &$next) {
      $foo = $this->_request_data_index;

      if (!isset($request->{$foo}[$this->_field_name])) {
        if ($this->_required) {
          $request->validation_errors[$this->_field_name] = $request->validation_errors[$this->_field_name] ?? [];
          $request->validation_errors[$this->_field_name][] = "Missing required field '{$this->_field_name}'!";
        }
        return $next($request, $response);
      }

      $field_value = $request->{$foo}[$this->_field_name];

      if ($this->_expected_type) {
        $type_checker = null;
        switch ($this->_expected_type) {
          case 'int':
            $type_checker = 'is_int';
            break;
          case 'string':
            $type_checker = 'is_string';
            break;
          case 'array':
            $type_checker = 'is_array';
            break;
            case 'float':
            $type_checker = 'is_float';
            break;
          case 'numeric':
            $type_checker = 'is_numeric';
            break;
        }
        if (!$type_checker($field_value)) {
          $request->validation_errors[$this->_field_name] = $request->validation_errors[$this->_field_name] ?? [];
          $request->validation_errors[$this->_field_name][] = "Expected field '{$this->_field_name}' to be of type '{$this->_expected_type}'!";
        }
      }

      return $next($request, $response);
    }

    /**
     * Checks if the given value is of the passed type.
     * 
     * @return Validation
     */
    private function& is_type(string $type) {
      $this->_expected_type = $type;
      return $this;
    }

    /**
     * Checks if the given value is of type string.
     * 
     * @return Validation
     */
    function& is_string() {
      return $this->is_type('string');
    }

    /**
     * Checks if the given value is of type int.
     * 
     * @return Validation
     */
    function& is_int() {
      return $this->is_type('int');
    }

    /**
     * Checks if the given value is of type float.
     * 
     * @return Validation
     */
    function& is_float() {
      return $this->is_type('float');
    }

    /**
     * Checks if the given value is of type array.
     * 
     * @return Validation
     */
    function& is_array() {
      return $this->is_type('array');
    }

    /**
     * Checks if the given value is numeric.
     * 
     * @return Validation
     */
    function& is_numeric() {
      return $this->is_type('numeric');
    }

    /**
     * Creates a validation for a field passed as a query parameter.
     * 
     * @return Validation
     */
    static function query(string $field_name) {
      return new self('query', $field_name);
    }

    /**
     * Creates a validation for a field passed via the called route.
     * 
     * @return Validation
     */
    static function param(string $field_name) {
      return new self('params', $field_name);
    }

    /**
     * Creates a validation for a field passed in the request body.
     * 
     * @return Validation
     */
    static function body(string $field_name) {
      return new self('body', $field_name);
    }

    /**
     * Rejects the request with a 422 status, if any error in the request validation occurred.
     * 
     * @return Response
     */
    static function reject_on_error(Request &$request, Response &$response, callable &$next) {
      if (isset($request->validation_errors)) {
        if ($request->validation_errors !== []) {
          return $response
            ->status(422)
            ->json(['errors' => $request->validation_errors]);
        }
      }
      return $next($request, $response);
    }
  }