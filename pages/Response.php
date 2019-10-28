<?php
/**
 * This file is part of LibrePages.
 *
 * LibrePages is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * LibrePages is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with LibrePages.  If not, see <https://www.gnu.org/licenses/>.
 * 
 * @author    Jörn Neumeyer <contact@joern-neumeyer.de>
 * @copyright 2019 Jörn Neumeyer
*/
  
  declare(strict_types=1);
  namespace Pages;

  /**
   * Represents a server response.
   */
  class Response {
    /** @var int $_status_code The HTTP status code of the response. */
    protected $_status_code = 200;
    /** @var string $_content_type The Content-Type header of the response. */
    protected $_content_type = 'text/html';
    /** @var string $_content */
    protected $_content = '';
    /** @var array $_view */
    protected $_view;
    /** @var \Closure $_view_evaluator */
    protected $_view_evaluator;
    /** @var array $_view_data */
    protected $_view_data;
    /** @var arra $_cookiesy */
    protected $_cookies = [];

    /**
     * Sets the function needed to evaluate a view.
     * 
     * @param \Closure $evaluator A function which is capable of evaluating a view file.
     * 
     * @return Response
     */
    function& set_view_evaluator(\Closure $evaluator) {
      $this->_view_evaluator = $evaluator;
      return $this;
    }

    /**
     * Sets the status code of the response instance.
     * If no new status code is provided, the current status code will be returned.
     * 
     * @param ?int $status_code The new status code.
     * 
     * @return Response|int
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
     * Sets the Content-Type of the current request.
     * If no new content type is provided, the current content type will be returned.
     * 
     * @param ?string $content_type The new content type.
     * 
     * @return Response|string
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
     * 
     * @param string $content The content to be added to the response.
     * 
     * @return Response
     */
    function& write(string $content) {
      $this->_content .= $content;
      return $this;
    }

    /**
     * Sets the content type of the response to application/json and writes the given data as JSON to the response.
     * JSON conversion of the data happens internally.
     * 
     * @param * $json Any data, which should be returned in JSON format.
     * 
     * @return Response
     */
    function& json($json) {
      return $this->content_type('application/json')->write(json_encode($json));
    }

    /**
     * Sets the view which should be rendered when the response is sent.
     * 
     * @param array $view The path to the view which should be evaluated by the provided view evaluator.
     * @param array $data A collection of data which may be used by the view.
     * 
     * @return Response
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
     * 
     * @param string $name The name of the cookie.
     * @param string $value The value of the cookie.
     * @param array $options Options corresponding to default PHP cookie options.
     * 
     * @return Response
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
     * 
     * @return void
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