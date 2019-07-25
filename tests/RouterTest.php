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
    function test_match_path_to_route() {
      $data = [
        ['/', '/', true],
        ['/hello', '/world', false],
        ['/hello', '/hello', true],
      ];

      foreach ($data as [$path, $route, $should_match]) {
        $does_match = Router::match_path_to_route($path, $route) !== false;
        $this->assert($does_match === $should_match, "Expected path '{$path}' and route '{$route}' to match!");
      }
    }
  }