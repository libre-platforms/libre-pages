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
  namespace Pages;

  /**
   * Represents an incoming request to the server.
   */
  class Request {
    /** @var string $method */
    public $method = '';

    /** @var string $path */
    public $path = '';

    /** @var array $params */
    public $params = [];

    /** @var array $files */
    public $files = [];

    /** @var array $cookies */
    public $cookies = [];

    /** @var array $headers */
    public $headers = [];

    /** @var array $query */
    public $query = [];

    /** @var array $body */
    public $body = [];

    /** @var array $validation_errors */
    public $validation_errors = [];

    /** @var bool $https */
    public $https = false;

    /** @var string $server_name */
    public $server_name = '';

    /** @var int $server_port */
    public $server_port = 0;
    
    /** @var ?\PDO $pdo */
    public $pdo = null;

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

      $request = new Request;

      $request->method = $_SERVER['REQUEST_METHOD'];
      $request->path = strlen($path_info) > 1 ? \rtrim($path_info, '/') : $path_info;
      $request->params = &$params;
      $request->https = isset($_SERVER['HTTPS']);
      $request->server_name = $_SERVER['SERVER_NAME'];
      $request->server_port = (int)$_SERVER['SERVER_PORT'];
      $request->files = $files;
      $request->cookies = &$_COOKIE;
      $request->headers = getallheaders();
      $request->query = &$_GET;
      $request->body = $body;

      return $request;
    }
  }