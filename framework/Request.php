<?php
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

    /**
     * Constructs a Request object from the given data of the current request.
     */
    static function from_current_request(array& $params = []) {
      $files = [];

      foreach ($_FILES as $field_name => $file) {
        $files[$field_name] = new RequestFile($file);
      }

      $request_data = [
        'method' => $_SERVER['REQUEST_METHOD'],
        'path' => strlen($_SERVER['PATH_INFO']) > 1 ? \rtrim($_SERVER['PATH_INFO'], '/') : $_SERVER['PATH_INFO'],
        'params' => &$params,
        'https' => isset($_SERVER['HTTPS']) || false,
        'server_name' => $_SERVER['SERVER_NAME'],
        'server_port' => (int)$_SERVER['SERVER_PORT'],
        'files' => $files,
        'cookies' => $_COOKIE,
        'headers' => getallheaders(),
      ];

      return new Request($request_data);
    }
  }