<?php
  namespace Framework;

  class Request {
    protected $_data = [];

    function __construct(array& $request_data) {
      $this->_data = $request_data;
    }

    function __get(string $key) {
      return $this->_data[$key] ?? null;
    }

    static function from_current_request(array& $params) {
      $request_data = [
        'method' => $_SERVER['REQUEST_METHOD'],
        'path' => strlen($_SERVER['REQUEST_URI']) > 1 ? \rtrim($_SERVER['REQUEST_URI'], '/') : $_SERVER['REQUEST_URI'],
        'params' => &$params,
        'https' => isset($_SERVER['HTTPS']) || false,
        'server_name' => $_SERVER['SERVER_NAME'],
        'server_port' => $_SERVER['SERVER_PORT'],
      ];

      return new Request($request_data);
    }
  }