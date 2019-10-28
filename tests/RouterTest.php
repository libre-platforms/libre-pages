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

  namespace Tests;
  use Pages\{
    TestCase, Router
  };

  /**
   * Test for the router.
   */
  class RouterTest extends TestCase {
    private $data;

    function before_test_suite() {
      $this->data = [
        ['/', '/', [], function() { }],
        ['/hello', '/world', false, function() { }],
        ['/hello', '/hello', [], function() { }],
        ['/hello/world', '/hello/world', [], function() { }],
        ['/hello/foo', '/hello/world', false, function() { }],
        ['/hello', '/{moin}', ['moin' => 'hello'], function() { }],
        ['/hello', '/{moin:\w+}', ['moin' => 'hello'], function() { }],
        ['/bar', '/{foo}', ['foo' => 'bar'], function() { }],
        ['/bar', '/{foo:\w+}', ['foo' => 'bar'], function() { }],
        ['/world/hello', '/world/{moin}', ['moin' => 'hello'], function() { }],
        ['/world/a-hello', '/world/a-{moin}', ['moin' => 'hello'], function() { }],
        ['/world/hello-b', '/world/{moin}-b', ['moin' => 'hello'], function() { }],
        ['/world/a-hello', '/world/a-{moin:\d+}', false, function() { }],
        ['/world/hello-b', '/world/{moin:\w+}-b', ['moin' => 'hello'], function() { }],
        ['/world/hello-b', '/world/{moin:\w{5}}-b', ['moin' => 'hello'], function() { }],
        ['/world/hello-a', '/world/{moin:\w+}-b', false, function() { }],
        ['/world/fooo', '/world/{moin:[a-z]{4}}', ['moin' => 'fooo'], function() { }],
      ];

      $matching_data = array_filter($this->data, function($set) {
        return $set[2] !== false;
      });

      $this->parameterized('test_router_get', $matching_data);
      $this->parameterized('test_match_path_to_route', $this->data);
    }

    function test_match_path_to_route($path, $route, $actual_match) {
      $match = Router::match_path_to_route($path, $route);
      $this->assert($match === $actual_match, "Expected path '{$path}' and route '{$route}' to match! Match was: ".json_encode($match).';');
    }

    function test_router_get($path, $route, $expected_match, $handler) {
      $router = new Router();

      $router->get($route, $handler);
      [$resolved_handler, $match] = $router->get_handler('GET', $path);
      $this->assert($handler === $resolved_handler, 'Got wrong route handler! recieved '.($resolved_handler === null ? '' : 'non ').'null handler');
      $this->assert($match === $expected_match, 'Got mismatch in route parameters! Expected parameters: '.json_encode($expected_match).'; got: '.json_encode($match).'; route: '.$route.'; path: '.$path);
    }

    function test_returns_proper_handler_of_first_group() {
      $handler = function() { };
      $router = new Router();
      $router->group('/group', function($router) use ($handler) {
        $router->get('/foo', $handler);
      });
      [$resolved_handler] = $router->get_handler('GET', '/group/foo');
      $this->assert($resolved_handler === $handler);
    }

    function test_returns_proper_handler_of_second_group() {
      $handler = function() { };
      $router = new Router();
      $router->group('/group', function($router) use ($handler) {
        
      });

      $router->group('/bar', function($router) use ($handler) {
        $router->get('/foo', $handler);
      });
      [$resolved_handler] = $router->get_handler('GET', '/bar/foo');
      $this->assert($resolved_handler === $handler);
    }

    function test_expect_missing_closing_route_to_throw() {
      $this->expect_exception(\Exception::class);
      Router::match_path_to_route('/hello', '/{moin');
    }
  }