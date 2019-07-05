<?php
  declare(strict_types=1);
  namespace Framework;

  /**
   * @property string $method
   * @property string $path
   * @property array $params
   * @property array $files
   * @property array $cookies
   * @property bool $https
   * @property string $server_name
   * @property int $server_port
   */
  class Request {
    protected $_data = [];

    function __construct(array& $request_data) {
      $this->_data = $request_data;
    }

    function __get(string $key) {
      return $this->_data[$key] ?? null;
    }

    static function from_current_request(array& $params = []) {
      $request_data = [
        'method' => $_SERVER['REQUEST_METHOD'],
        'path' => strlen($_SERVER['REQUEST_URI']) > 1 ? \rtrim($_SERVER['REQUEST_URI'], '/') : $_SERVER['REQUEST_URI'],
        'params' => &$params,
        'https' => isset($_SERVER['HTTPS']) || false,
        'server_name' => $_SERVER['SERVER_NAME'],
        'server_port' => (int)$_SERVER['SERVER_PORT'],
        'files' => $_FILES,
        'cookies' => $_COOKIE,
      ];

      return new Request($request_data);
    }
  }