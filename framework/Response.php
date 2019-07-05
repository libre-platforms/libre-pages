<?php
  declare(strict_types=1);
  namespace Framework;

  class Response {
    protected $_status_code = 200;
    protected $_content_type = 'text/html';
    protected $_content = '';
    protected $_view;
    protected $_view_evaluator;
    protected $_view_data;
    protected $_cookies = [];

    function set_view_evaluator(\Closure $evaluator) {
      $this->_view_evaluator = $evaluator;
    }

    function& status(?int $status_code = null) {
      if ($status_code) {
        $this->_status_code = $status_code;
        return $this;
      } else {
        return $this->_status_code;
      }
    }

    function& content_type(?string $content_type = null) {
      if ($content_type) {
        $this->_content_type = $content_type;
        return $this;
      } else {
        return $this->_content_type;
      }
    }

    function& write(string $content) {
      $this->_content .= $content;
      return $this;
    }

    function& json($json) {
      return $this->content_type('application/json')->write(json_encode($json));
    }

    function& view(array $view, array $data = []) {
      $this->_view = $view;
      $this->_view_data = $data;
      return $this;
    }

    function& set_cookie(string $name, string $value, array $options = []) {
      $expires = $options['expires'] ?? 0;
      $path = $options['path'] ?? '';
      $domain = $options['domain'] ?? '';
      $secure = $options['secure'] ?? false;
      $http_only = $options['httponly'] ?? false;

      $this->_cookies[$name] = [$value, $expires, $path, $domain, $secure, $http_only];
      return $this;
    }

    function send() {
      \http_response_code($this->_status_code);
      header('Content-Type: '.$this->_content_type);
      foreach ($this->_cookies as $name => $cookie) {
        setcookie($name, ...$cookie);
      }
      if ($this->_view) {
        $eval = $this->_view_evaluator;
        $eval($this->_view, $this->_view_data);
      } else {
        print $this->_content;
      }
    }
  }