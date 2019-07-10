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

  /**
   * Represents an incoming request to the server.
   *
   * @property string $method
   * @property string $path
   * @property array $params
   * @property array $files
   * @property array $cookies
   * @property array $headers
   * @property array $query
   * @property array $body
   * @property bool $https
   * @property string $server_name
   * @property int $server_port
   */
  class Request implements \ArrayAccess {
    protected $_data = [];

    function __construct(array& $request_data) {
      $this->_data = $request_data;
    }

    function __get(string $key) {
      return $this->_data[$key] ?? null;
    }

    function offsetExists($key) {
      return isset($this->_data[$key]);
    }

    function offsetGet($key) {
      return $this->_data[$key];
    }

    function offsetSet($key, $value) {
      $this->_data[$key] = $value;
    }

    function offsetUnset($key) {
      unset($this->_data[$key]);
    }

    /**
     * Constructs a Request object from the given data of the current request.
     * 
     * @return Request
     */
    static function from_current_request(array& $params = []) {
      $path_info = $_SERVER['PATH_INFO'] ?? '/';
      $files = [];

      foreach ($_FILES as $field_name => $file) {
        $files[$field_name] = new RequestFile($file);
      }

      $body = [];

      foreach ($_POST as $k => $v) {
        if (!is_string($v)) {
          continue;
        } else if (strlen($v) > 0) {
          $body[$k] = $v;
        }
      }

      $request_data = [
        'method' => $_SERVER['REQUEST_METHOD'],
        'path' => strlen($path_info) > 1 ? \rtrim($path_info, '/') : $path_info,
        'params' => &$params,
        'https' => isset($_SERVER['HTTPS']) || false,
        'server_name' => $_SERVER['SERVER_NAME'],
        'server_port' => (int)$_SERVER['SERVER_PORT'],
        'files' => $files,
        'cookies' => $_COOKIE,
        'headers' => getallheaders(),
        'query' => $_GET,
        'body' => $body,
      ];

      return new Request($request_data);
    }
  }