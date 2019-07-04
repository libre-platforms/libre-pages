<?php
  namespace Framework;

  class Response {
    protected $_status_code = StatusCode::HTTP_OK;

    function& status_code(?int $status_code = null) {
      if ($status_code) {
        $this->_status_code = $status_code;
        return $this;
      } else {
        return $this->_status_code;
      }
    }

    function __toString() {
      return (string)$this->status_code();
    }
  }