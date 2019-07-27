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

  namespace Tests;

  use Framework\{
    TestCase, Router
  };

  class RouterTest extends TestCase {
    private $data;

    function before_test_suite() {
      $this->data = [
        ['/', '/', [], function() { }],
        ['/hello', '/world', false, function() { }],
        ['/hello', '/hello', [], function() { }],
        ['/hello', '/{moin}', ['moin' => 'hello'], function() { }],
        ['/world/hello', '/world/{moin}', ['moin' => 'hello'], function() { }],
        ['/world/a-hello', '/world/a-{moin}', ['moin' => 'hello'], function() { }],
      ];
    }

    function test_match_path_to_route() {
      foreach ($this->data as [$path, $route, $actual_match]) {
        $match = Router::match_path_to_route($path, $route);
        $this->assert($match === $actual_match, "Expected path '{$path}' and route '{$route}' to match! Match was: ".json_encode($match).';');
      }
    }

    function test_router_get() {
      $router = new Router();
      $handler = function() { };

      foreach ($this->data as [$path, $route, $actual_match, $handler]) {
        if ($actual_match === false) {
          continue;
        }
        $router->get($route, $handler);
        $resolved_handler = $router->get_handler('GET', $path);
        $this->assert($handler === $resolved_handler, 'Got wrong route handler!');
      }
    }
  }