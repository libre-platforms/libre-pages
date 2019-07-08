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

  use Framework\Router;
  use Framework\Validation;

  $router->get('/', function(&$request, &$response) {
    return $response->write('moin');
  });

  $router->group('/grp', function ($router) {
    $router->get('/bla', function(&$request, &$response) {
      return $response->write('bla');
    });

    $router->get('/u-{abc:\d+}', Router::make_handler_chain([
      function(&$request, &$response, $next) {
        $response->status(404);
        return $next($request, $response);
      },
      function(&$request, &$response) {
        $loader = Framework\make_asset_loader($request);
        // $response->json(['hello_image' => $loader('img/hello-world.png')]);
        $response->view(['welcome']);
        $response->set_cookie('foo', 'bar');
        return $response;
      }
    ]));

    $router->post('/validated-route', Router::make_handler_chain([
      Validation::body('foo'),
      'Framework\\Validation::redirect_on_error',
      function(&$request, &$response) {
        $response->write($request->body['foo']);
        return $response;
      }
    ]));

    $router->post('/u-{abc:\d+}', 'App\\TestController::user_by_id');
  });