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
   * Represents a server response.
   */
  class Response {
    protected $_status_code = 200;
    protected $_content_type = 'text/html';
    protected $_content = '';
    protected $_view;
    protected $_view_evaluator;
    protected $_view_data;
    protected $_cookies = [];

    /**
     * Sets the function needed to evaluate a view.
     */
    function& set_view_evaluator(\Closure $evaluator) {
      $this->_view_evaluator = $evaluator;
      return $this;
    }

    /**
     * Sets the status code of the response instance.
     */
    function& status(?int $status_code = null) {
      if ($status_code) {
        $this->_status_code = $status_code;
        return $this;
      } else {
        return $this->_status_code;
      }
    }

    /**
     * Sets/gets the Content-Type of the surrent request.
     */
    function& content_type(?string $content_type = null) {
      if ($content_type) {
        $this->_content_type = $content_type;
        return $this;
      } else {
        return $this->_content_type;
      }
    }

    /**
     * Writes the given string to the content of the response.
     */
    function& write(string $content) {
      $this->_content .= $content;
      return $this;
    }

    /**
     * Sets the content type of the response to application/json and writes the given data as JSON to the response.
     * JSON conversion of the data happens internally.
     */
    function& json($json) {
      return $this->content_type('application/json')->write(json_encode($json));
    }

    /**
     * Sets the view which should be rendered when the response is sent.
     */
    function& view(array $view, array $data = []) {
      $this->_view = $view;
      $this->_view_data = $data;
      return $this;
    }

    /**
     * Sets a cookie for the response.
     * 
     * Implementation corresponds to the PHP7.3 implementation of setcookie.
     * @link https://www.php.net/manual/en/function.setcookie.php
     */
    function& set_cookie(string $name, string $value, array $options = []) {
      $expires = $options['expires'] ?? 0;
      $path = $options['path'] ?? '';
      $domain = $options['domain'] ?? '';
      $secure = $options['secure'] ?? false;
      $http_only = $options['httponly'] ?? false;

      $this->_cookies[$name] = [$value, $expires, $path, $domain, $secure, $http_only];
      return $this;
    }

    /**
     * Sets the status code, header, and cookies of the response.
     *
     * In case a view has been set, that view will be evaluated.
     * Otherwise, the response content will be printed.
     */
    function send() {
      \http_response_code($this->_status_code);
      header('Content-Type: '.$this->_content_type);
      foreach ($this->_cookies as $name => $cookie) {
        setcookie($name, ...$cookie);
      }
      if ($this->_view) {
        ($this->_view_evaluator)($this->_view, $this->_view_data);
      } else {
        print $this->_content;
      }
    }
  }