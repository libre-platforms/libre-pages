<?php
  declare(strict_types=1);
  namespace Framework;

  /**
   * Represents a router.
   */
  class Router {
    protected $_prefix;
    protected $_groups = [];
    protected $_routes = [
      'GET' => [],
      'POST' => [],
      'PUT' => [],
      'DELETE' => [],
    ];

    function __construct(string $prefix = '') {
      $this->_prefix = $prefix;
    }

    /**
     * Returns the defined prefix for the router instance.
     */
    function prefix() {
      return $this->_prefix;
    }

    /**
     * Returns a function, which builds a chain out of the given handlers.
     */
    static function make_handler_chain(array $handlers) {
      $iterator = new \ArrayIterator($handlers);
      $foo = function(&$request, &$response) use (&$iterator, &$foo) {
        if ($iterator->valid()) {
          $next_handler = $iterator->current();
          $iterator->next();
          $response = $next_handler($request, $response, $foo);
          return $response;
        }
      };
      return $foo;
    }

    /**
     * Registers a route handler for a given route and HTTP method.
     */
    protected function add_route(string $method, string $route, $handler) {
      $this->_routes[$method][] = [$this->_prefix.$route, $handler];
    }

    /**
     * Registers a handler for GET requests on the given route.
     */
    function get(string $route, $handler) {
      return $this->add_route('GET', $route, $handler);
    }

    /**
     * Registers a handler for POST requests on the given route.
     */
    function post(string $route, $handler) {
      return $this->add_route('POST', $route, $handler);
    }

    /**
     * Registers a handler for PUT requests on the given route.
     */
    function put(string $route, $handler) {
      return $this->add_route('PUT', $route, $handler);
    }

    /**
     * Registers a handler for DELETE requests on the given route.
     */
    function delete(string $route, $handler) {
      return $this->add_route('DELETE', $route, $handler);
    }

    /**
     * Registers a callback, which build sub-routes using the given route prefix.
     */
    function group(string $route, \Closure $group_builder) {
      $this->_groups[] = new RouteGroup($route, $group_builder);
    }

    /**
     * Returns an array containing the matching route handler and its parameters.
     *
     * If no fitting handler can be found for the given method and path, null will be returned.
     */
    function get_handler(string $method, string $path) {
      $routes = $this->_routes[$method];

      foreach ($routes as $route) {
        [$routePath, $routeHandler] = $route;
        $matching_result = self::match_path_to_route($path, $routePath);
        if ($matching_result !== false) {
          return [$routeHandler, $matching_result];
        }
      }

      foreach ($this->_groups as $group) {
        return $group->get_router()->get_handler($method, $path);
      }
    }

    /**
     * Matches a given path to a route.
     *
     * If path and route match, an array containing the route parameters will be returned.
     * Otherwise, false will be returned.
     */
    static function match_path_to_route(string $path, string $route) {
      $path_length = strlen($path);
      $route_length = strlen($route);
      $params = [];
      $has_params = false;

      for ($i_path = 0, $i_route = 0; ; ++$i_path, ++$i_route) {
        if ($i_path === $path_length && $i_route === $route_length) {
          break;
        }

        if ($i_path >= $path_length || $i_route >= $route_length) {
          return false;
        }

        if ($route[$i_route] === '{') {
          $has_params = true;
          $brace_counter = 1;

          for ($j = 1; $i_route + $j < $route_length; ++$j) {
            if ($route[$i_route + $j] === '{') {
              ++$brace_counter;
            } else if ($route[$i_route + $j] === '}') {
              --$brace_counter;
            }
            if ($brace_counter === 0) {
              break;
            }
          }

          if ($brace_counter !== 0) {
            throw new Exception('Missing closing curly brace in param definition!');
          }

          $param = \substr($route, $i_route + 1, $j - 1);
          $i_route += $j;
          if (\strpos($param, ':') > -1) {
            [$param_name, $regex] = explode(':', $param, 2);
            $variable_path_to_check = substr($path, $i_path);
            $matches = [];
            if (!\preg_match("/{$regex}/", $variable_path_to_check, $matches)) {
              return false;
            } else {
              $param_value = $matches[0];
              $i_path += strlen($param_value) - 1;
              $params[$param_name] = $param_value;
            }
          } else {
            for ($j = 0; $i_path + $j < $path_length; ++$j) {
              if ($path[$i_path + $j] === '/') {
                break;
              }
            }
            if ($i_path + $j === $path_length) {
              if (isset($route[$i_route + 1])) {
                if ($route[$i_route + 1] === '/') {
                  return false;
                }
              }
            }
            $params[$param] = \substr($path, $i_path);
          }
        } else {
          if ($path[$i_path] !== $route[$i_route]) {
            return false;
          }
        }
      }

      if ($has_params) {
        return $params;
      } else {
        return [];
      }
    }
  }