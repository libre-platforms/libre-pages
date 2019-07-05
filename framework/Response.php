<?php
  namespace Framework;

  class Response {
    protected $_status_code = StatusCode::HTTP_OK;
    protected $_content_type = 'text/html';

    protected $_content = '';

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

    function __toString() {
      \http_response_code($this->_status_code);
      header('Content-Type:'.$this->_content_type);
      return $this->_content;
    }
  }