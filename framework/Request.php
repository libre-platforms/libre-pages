<?php
  namespace Framework;

  class Request {
    protected $_method;
    protected $_path;
    protected $_params;

    function __construct(array& $request_data) {
      $this->_method = $request_data['method'];
      $this->_path = $request_data['path'];
    }

    function method() {
      return $this->_method;
    }

    function path() {
      return $this->_path;
    }

    function &params() {
      return $this->_params;
    }

    static function from_current_request() {
      $request_data = [
        'method' => $_SERVER['REQUEST_METHOD'],
        'path' => strlen($_SERVER['REQUEST_URI']) > 1 ? \rtrim($_SERVER['REQUEST_URI'], '/') : $_SERVER['REQUEST_URI'],
      ];

      return new Request($request_data);
    }
  }