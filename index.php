<?php
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

  require_once __DIR__.DIRECTORY_SEPARATOR.'autoload.php';

  define('APP_ROOT', __DIR__);
  define('APP_START', time());

  $router = new Framework\Router();

  require __DIR__.DIRECTORY_SEPARATOR.'routes.php';

  $request_path = $_SERVER['REQUEST_URI'];
  if (strlen($request_path) > 1) {
    $request_path = rtrim($request_path, '/');
    if (strlen($request_path) === 0) {
      $request_path = '/';
    }
  }
  $handler_with_params = $router->get_handler($_SERVER['REQUEST_METHOD'], $request_path);

  if (is_array($handler_with_params)) {
    [$handler, $params] = $handler_with_params;
    $request = Framework\Request::from_current_request($params);
    $response = new Framework\Response;
    $handler($request, $response);
  } else {
    print '[NO HANDLER FOUND]';
  }