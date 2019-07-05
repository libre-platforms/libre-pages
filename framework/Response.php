<?php
  namespace Framework;

  class Response {
    protected $_status_code = StatusCode::HTTP_OK;

    protected $_content = '';

    function& status_code(?int $status_code = null) {
      if ($status_code) {
        $this->_status_code = $status_code;
        return $this;
      } else {
        return $this->_status_code;
      }
    }

    function& write(string $content) {
      $this->_content .= $content;
      return $this;
    }

    function __toString() {
      \http_response_code($this->_status_code);
      return $this->_content;
    }
  }